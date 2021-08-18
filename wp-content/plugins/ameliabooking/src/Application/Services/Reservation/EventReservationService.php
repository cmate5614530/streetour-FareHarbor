<?php

namespace AmeliaBooking\Application\Services\Reservation;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\BookingCancellationException;
use AmeliaBooking\Domain\Common\Exceptions\BookingUnavailableException;
use AmeliaBooking\Domain\Common\Exceptions\CustomerBookedException;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\AbstractBookable;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBookingExtra;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Booking\Event\EventPeriod;
use AmeliaBooking\Domain\Entity\Booking\Reservation;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\Location\Location;
use AmeliaBooking\Domain\Entity\Payment\Payment;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Factory\Booking\Appointment\CustomerBookingFactory;
use AmeliaBooking\Domain\Factory\Booking\Event\CustomerBookingEventPeriodFactory;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\BooleanValueObject;
use AmeliaBooking\Domain\ValueObjects\Number\Float\Price;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Domain\ValueObjects\String\PaymentType;
use AmeliaBooking\Domain\ValueObjects\String\Token;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\CustomerBookingExtraRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\CustomerBookingRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Event\CustomerBookingEventPeriodRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Event\EventRepository;
use AmeliaBooking\Infrastructure\Repository\Location\LocationRepository;
use DateTime;
use Exception;
use Slim\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class EventReservationService
 *
 * @package AmeliaBooking\Application\Services\Reservation
 */
class EventReservationService extends AbstractReservationService
{
    /**
     * @return string
     */
    public function getType()
    {
        return Entities::EVENT;
    }

    /**
     * @param array       $eventData
     * @param Reservation $reservation
     * @param bool        $save
     *
     * @return void
     *
     * @throws BookingUnavailableException
     * @throws ContainerValueNotFoundException
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws Exception
     */
    public function book($eventData, $reservation, $save)
    {
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->container->get('domain.booking.event.repository');

        /** @var LocationRepository $locationRepository */
        $locationRepository = $this->container->get('domain.locations.repository');

        /** @var Event $event */
        $event = $eventRepository->getById($eventData['eventId']);

        $booking = CustomerBookingFactory::create(
            array_merge($eventData['bookings'][0], ['status' => BookingStatus::APPROVED])
        );

        $booking->setStatus(
            new BookingStatus(
                !empty($eventData['payment']['gateway']) &&
                $eventData['payment']['gateway'] === PaymentType::MOLLIE ?
                    BookingStatus::PENDING : BookingStatus::APPROVED
            )
        );

        $personsCount = 0;

        /** @var CustomerBooking $customerBooking */
        foreach ($event->getBookings()->getItems() as $customerBooking) {
            if ($customerBooking->getStatus()->getValue() === BookingStatus::APPROVED) {
                $personsCount += $customerBooking->getPersons()->getValue();
            }
            if ($customerBooking->getStatus()->getValue() !== BookingStatus::CANCELED &&
                !$event->getBookMultipleTimes()->getValue() &&
                $booking->getCustomerId()->getValue() === $customerBooking->getCustomerId()->getValue()) {
                throw new CustomerBookedException('');
            }
        }

        /** @var AbstractUser $currentUser */
        $currentUser = $this->container->get('logged.in.user');

        if ($reservation->hasAvailabilityValidation()->getValue() &&
            (!$currentUser || ($currentUser->getType() === AbstractUser::USER_ROLE_CUSTOMER)) &&
            (
                !$this->isBookable($event, null, DateTimeService::getNowDateTimeObject()) ||
                $personsCount + $booking->getPersons()->getValue() > $event->getMaxCapacity()->getValue()
            )
        ) {
            throw new BookingUnavailableException('');
        }


        $booking->setAggregatedPrice(new BooleanValueObject(true));

        $paymentAmount = $this->getPaymentAmount($booking, $event);

        $applyDeposit =
            $eventData['bookings'][0]['deposit'] && $eventData['payment']['gateway'] !== PaymentType::ON_SITE;

        if ($applyDeposit) {
            $paymentDeposit = $this->calculateDepositAmount(
                $paymentAmount,
                $event,
                $booking->getPersons()->getValue()
            );

            $eventData['payment']['deposit'] = $paymentAmount !== $paymentDeposit;

            $paymentAmount = $paymentDeposit;
        }

        if ($save) {
            /** @var CustomerBookingRepository $bookingRepository */
            $bookingRepository = $this->container->get('domain.booking.customerBooking.repository');

            /** @var CustomerBookingExtraRepository $bookingExtraRepository */
            $bookingExtraRepository = $this->container->get('domain.booking.customerBookingExtra.repository');

            /** @var CustomerBookingEventPeriodRepository $bookingEventPeriodRepository */
            $bookingEventPeriodRepository =
                $this->container->get('domain.booking.customerBookingEventPeriod.repository');

            $booking->setPrice(new Price($event->getPrice()->getValue()));
            $booking->setToken(new Token());

            $bookingId = $bookingRepository->add($booking);

            /** @var CustomerBookingExtra $bookingExtra */
            foreach ($booking->getExtras()->getItems() as $bookingExtra) {
                $bookingExtra->setCustomerBookingId(new Id($bookingId));
                $bookingExtraId = $bookingExtraRepository->add($bookingExtra);
                $bookingExtra->setId(new Id($bookingExtraId));
            }

            $booking->setId(new Id($bookingId));

            /** @var Payment $payment */
            $payment = $this->addPayment(
                $booking->getId()->getValue(),
                null,
                $eventData['payment'],
                $paymentAmount,
                $event->getPeriods()->getItem(0)->getPeriodStart()->getValue()
            );

            /** @var Collection $payments */
            $payments = new Collection();

            $payments->addItem($payment);

            $booking->setPayments($payments);

            /** @var EventPeriod $eventPeriod */
            foreach ($event->getPeriods()->getItems() as $eventPeriod) {
                $bookingEventPeriod = CustomerBookingEventPeriodFactory::create(
                    [
                        'eventPeriodId' => $eventPeriod->getId()->getValue(),
                        'customerBookingId' => $bookingId
                    ]
                );

                $bookingEventPeriodRepository->add($bookingEventPeriod);
            }

            $event->getBookings()->addItem($booking, $booking->getId()->getValue());
        }

        if ($event->getLocationId()) {
            /** @var Location $location */
            $location = $locationRepository->getById($event->getLocationId()->getValue());

            $event->setLocation($location);
        }

        $reservation->setApplyDeposit(new BooleanValueObject($applyDeposit));
        $reservation->setCustomer($booking->getCustomer());
        $reservation->setBookable($event);
        $reservation->setBooking($booking);
        $reservation->setReservation($event);
        $reservation->setRecurring(new Collection());
        $reservation->setPackageReservations(new Collection());
        $reservation->setIsStatusChanged(new BooleanValueObject(false));
    }

    /**
     * @param CustomerBooking $booking
     * @param string          $requestedStatus
     *
     * @return array
     *
     * @throws ContainerException
     * @throws ContainerValueNotFoundException
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws BookingCancellationException
     */
    public function updateStatus($booking, $requestedStatus)
    {
        /** @var CustomerBookingRepository $bookingRepository */
        $bookingRepository = $this->container->get('domain.booking.customerBooking.repository');
        /** @var SettingsService $settingsDS */
        $settingsDS = $this->container->get('domain.settings.service');

        /** @var Event $reservation */
        $event = $this->getReservationByBookingId($booking->getId()->getValue());

        if ($requestedStatus === BookingStatus::CANCELED) {
             $minimumCancelTimeInSeconds = $settingsDS
                ->getEntitySettings($event->getSettings())
                ->getGeneralSettings()
                ->getMinimumTimeRequirementPriorToCanceling();

            $this->inspectMinimumCancellationTime(
                $event->getPeriods()->getItem(0)->getPeriodStart()->getValue(),
                $minimumCancelTimeInSeconds
            );
        }

        $booking->setStatus(new BookingStatus($requestedStatus));

        $bookingRepository->update($booking->getId()->getValue(), $booking);

        return [
            Entities::EVENT            => $event->toArray(),
            'appointmentStatusChanged' => false,
            Entities::BOOKING          => $booking->toArray()
        ];
    }

    /**
     * @param Event            $reservation
     * @param CustomerBooking  $booking
     * @param AbstractBookable $bookable
     *
     * @return array
     */
    public function getBookingPeriods($reservation, $booking, $bookable)
    {
        $dates = [];

        /** @var EventPeriod $period */
        foreach ($reservation->getPeriods()->getItems() as $period) {
            $dates[] = [
                'start' => DateTimeService::getCustomDateTimeInUtc(
                    $period->getPeriodStart()->getValue()->format('Y-m-d H:i:s')
                ),
                'end'   => DateTimeService::getCustomDateTimeInUtc(
                    $period->getPeriodEnd()->getValue()->format('Y-m-d H:i:s')
                )
            ];
        }

        return $dates;
    }

    /**
     * @param array $data
     *
     * @return AbstractBookable
     *
     * @throws ContainerValueNotFoundException
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     */
    public function getBookableEntity($data)
    {
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->container->get('domain.booking.event.repository');

        return $eventRepository->getById($data['eventId']);
    }

    /**
     * @param Event $bookable
     *
     * @return boolean
     */
    public function isAggregatedPrice($bookable)
    {
        return true;
    }

    /**
     * @param BooleanValueObject $bookableAggregatedPrice
     * @param BooleanValueObject $extraAggregatedPrice
     *
     * @return boolean
     */
    public function isExtraAggregatedPrice($extraAggregatedPrice, $bookableAggregatedPrice)
    {
        return true;
    }

    /**
     * @param Reservation $reservation
     * @param string      $paymentGateway
     * @param array       $requestData
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function getWooCommerceData($reservation, $paymentGateway, $requestData)
    {
        /** @var Event $event */
        $event = $reservation->getBookable();

        /** @var AbstractUser $customer */
        $customer = $reservation->getCustomer();

        /** @var CustomerBooking $booking */
        $booking = $reservation->getBooking();

        $dateTimeValues = [];

        /** @var EventPeriod $period */
        foreach ($event->getPeriods()->getItems() as $period) {
            $dateTimeValues[] = [
                'start' => $period->getPeriodStart()->getValue()->format('Y-m-d H:i'),
                'end'   => $period->getPeriodEnd()->getValue()->format('Y-m-d H:i')
            ];
        }

        $info = [
            'type'               => Entities::EVENT,
            'eventId'            => $event->getId()->getValue(),
            'name'               => $event->getName()->getValue(),
            'couponId'           => $booking->getCoupon() ? $booking->getCoupon()->getId()->getValue() : '',
            'couponCode'         => $booking->getCoupon() ? $booking->getCoupon()->getCode()->getValue() : '',
            'dateTimeValues'     => $dateTimeValues,
            'bookings'           => [
                [
                    'customerId'   => $customer->getId() ? $customer->getId()->getValue() : null,
                    'customer'     => [
                        'email'      => $customer->getEmail()->getValue(),
                        'externalId' => $customer->getExternalId() ? $customer->getExternalId()->getValue() : null,
                        'firstName'  => $customer->getFirstName()->getValue(),
                        'id'         => $customer->getId() ? $customer->getId()->getValue() : null,
                        'lastName'   => $customer->getLastName()->getValue(),
                        'phone'      => $customer->getPhone()->getValue()
                    ],
                    'info'         => $booking->getInfo()->getValue(),
                    'persons'      => $booking->getPersons()->getValue(),
                    'extras'       => [],
                    'utcOffset'    => $booking->getUtcOffset() ? $booking->getUtcOffset()->getValue() : null,
                    'customFields' => $booking->getCustomFields() ?
                        json_decode($booking->getCustomFields()->getValue(), true) : null,
                    'deposit'      => $reservation->getApplyDeposit()->getValue(),
                ]
            ],
            'payment'            => [
                'gateway' => $paymentGateway
            ],
            'locale'             => $reservation->getLocale()->getValue(),
            'timeZone'           => $reservation->getTimeZone()->getValue(),
            'recurring'          => [],
            'package'            => [],
        ];

        foreach ($booking->getExtras()->keys() as $extraKey) {
            /** @var CustomerBookingExtra $bookingExtra */
            $bookingExtra = $booking->getExtras()->getItem($extraKey);

            $info['bookings'][0]['extras'][] = [
                'extraId'  => $bookingExtra->getExtraId()->getValue(),
                'quantity' => $bookingExtra->getQuantity()->getValue()
            ];
        }

        return $info;
    }

    /**
     * @param int $id
     *
     * @return Event
     *
     * @throws ContainerValueNotFoundException
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     */
    public function getReservationByBookingId($id)
    {
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->container->get('domain.booking.event.repository');

        /** @var Collection $events */
        $events = $eventRepository->getByBookingIds([$id]);

        /** @var Event $event */
        return $events->length() ? $events->getItem($events->keys()[0]) : null;
    }

    /**
     * @param Event     $reservation
     * @param Event     $bookable
     * @param DateTime  $dateTime
     *
     * @return boolean
     *
     * @throws InvalidArgumentException
     */
    public function isBookable($reservation, $bookable, $dateTime)
    {
        $persons = 0;

        /** @var CustomerBooking $booking */
        foreach ($reservation->getBookings()->getItems() as $booking) {
            if ($booking->getStatus()->getValue() === BookingStatus::APPROVED) {
                $persons += $booking->getPersons()->getValue();
            }
        }

        $bookingCloses = $reservation->getBookingCloses() ?
            $reservation->getBookingCloses()->getValue() :
            $reservation->getPeriods()->getItem(0)->getPeriodStart()->getValue();

        $bookingOpens = $reservation->getBookingOpens() ?
            $reservation->getBookingOpens()->getValue() :
            $reservation->getCreated()->getValue();

        return $dateTime > $bookingOpens &&
            $dateTime < $bookingCloses &&
            $reservation->getMaxCapacity()->getValue() - $persons > 0 &&
            in_array($reservation->getStatus()->getValue(), [BookingStatus::APPROVED, BookingStatus::PENDING], true);
    }

    /**
     * @param Reservation  $reservation
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public function getReservationPaymentAmount($reservation)
    {
        /** @var Event $bookable */
        $bookable = $reservation->getBookable();

        $paymentAmount = $this->getPaymentAmount($reservation->getBooking(), $bookable);

        if ($reservation->getApplyDeposit()->getValue()) {
            $paymentAmount = $this->calculateDepositAmount(
                $paymentAmount,
                $bookable,
                $reservation->getBooking()->getPersons()->getValue()
            );
        }

        return $paymentAmount;
    }
}
