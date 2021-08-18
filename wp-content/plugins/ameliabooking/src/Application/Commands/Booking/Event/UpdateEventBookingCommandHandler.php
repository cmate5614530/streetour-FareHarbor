<?php

namespace AmeliaBooking\Application\Commands\Booking\Event;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Application\Services\User\UserApplicationService;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Factory\Coupon\CouponFactory;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\Json;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\IntegerValue;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\CustomerBookingRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Event\EventRepository;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class UpdateEventBookingCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\Booking\Event
 */
class UpdateEventBookingCommandHandler extends CommandHandler
{
    /**
     * @param UpdateEventBookingCommand $command
     *
     * @return CommandResult
     * @throws ContainerValueNotFoundException
     * @throws AccessDeniedException
     * @throws QueryExecutionException
     * @throws ContainerException
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function handle(UpdateEventBookingCommand $command)
    {
        $result = new CommandResult();

        /** @var UserApplicationService $userAS */
        $userAS = $this->getContainer()->get('application.user.service');

        /** @var SettingsService $settingsDS */
        $settingsDS = $this->container->get('domain.settings.service');

        /** @var AbstractUser $user */
        $user = $this->container->get('logged.in.user');

        if (!$this->getContainer()->getPermissionsService()->currentUserCanWrite(Entities::EVENTS)) {
            $user = $userAS->getAuthenticatedUser($command->getToken(), false, 'providerCabinet');

            if ($user === null) {
                $result->setResult(CommandResult::RESULT_ERROR);
                $result->setMessage('Could not retrieve user');
                $result->setData(
                    [
                        'reauthorize' => true
                    ]
                );

                return $result;
            }
        }

        $this->checkMandatoryFields($command);

        $bookingData = $command->getField('bookings') ? $command->getField('bookings')[0] : null;

        /** @var CustomerBookingRepository $customerBookingRepository */
        $customerBookingRepository = $this->container->get('domain.booking.customerBooking.repository');

        /** @var EventRepository $eventRepository */
        $eventRepository = $this->container->get('domain.booking.event.repository');

        /** @var CustomerBooking $customerBooking */
        $customerBooking = $customerBookingRepository->getById((int)$command->getField('id'));

        /** @var Collection $events */
        $events = $eventRepository->getByBookingIds([$customerBooking->getId()->getValue()]);

        /** @var Event $event */
        $event = $events->getItem($events->keys()[0]);

        if ($user &&
            $userAS->isProvider($user) &&
            (
                !$settingsDS->getSetting('roles', 'allowWriteEvents') ||
                !$event->getProviders()->keyExists($user->getId()->getValue())
            )
        ) {
            throw new AccessDeniedException('You are not allowed to update booking');
        }

        $isBookingStatusChanged =
            $bookingData &&
            isset($bookingData['status']) &&
            $customerBooking->getStatus()->getValue() !== $bookingData['status'];

        if (isset($bookingData['customFields'])) {
            $customerBooking->setCustomFields(new Json(json_encode($bookingData['customFields'])));
        }

        if (isset($bookingData['persons'])) {
            $customerBooking->setPersons(new IntegerValue($bookingData['persons']));
        }

        if (isset($bookingData['status'])) {
            $customerBooking->setStatus(new BookingStatus($bookingData['status']));
        }

        if ($customerBooking->getCouponId()) {
            $customerBooking->setCoupon(CouponFactory::create($bookingData['coupon']));
        }

        $customerBookingRepository->update($customerBooking->getId()->getValue(), $customerBooking);

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully booking');
        $result->setData(
            [
                'type'                     => Entities::EVENT,
                Entities::EVENT            => $event->toArray(),
                Entities::BOOKING          => $customerBooking->toArray(),
                'appointmentStatusChanged' => false,
                'bookingStatusChanged'     => $isBookingStatusChanged
            ]
        );

        return $result;
    }
}
