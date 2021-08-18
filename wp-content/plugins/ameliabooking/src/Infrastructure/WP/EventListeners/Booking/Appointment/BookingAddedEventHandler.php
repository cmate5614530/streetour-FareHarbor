<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment;

use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Services\Booking\BookingApplicationService;
use AmeliaBooking\Application\Services\Booking\IcsApplicationService;
use AmeliaBooking\Application\Services\Notification\EmailNotificationService;
use AmeliaBooking\Application\Services\Notification\SMSNotificationService;
use AmeliaBooking\Application\Services\WebHook\WebHookApplicationService;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\Service\Package;
use AmeliaBooking\Domain\Entity\Booking\Appointment\Appointment;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Factory\Booking\Appointment\AppointmentFactory;
use AmeliaBooking\Domain\Factory\Booking\Event\EventFactory;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\PackageRepository;
use AmeliaBooking\Infrastructure\Services\Google\GoogleCalendarService;
use AmeliaBooking\Application\Services\Zoom\ZoomApplicationService;
use AmeliaBooking\Infrastructure\Services\Outlook\OutlookCalendarService;
use Exception;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class BookingAddedEventHandler
 *
 * @package AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment
 */
class BookingAddedEventHandler
{
    /** @var string */
    const BOOKING_ADDED = 'bookingAdded';

    /**
     * @param CommandResult $commandResult
     * @param Container     $container
     *
     * @throws NotFoundException
     * @throws InvalidArgumentException
     * @throws ContainerValueNotFoundException
     * @throws QueryExecutionException
     * @throws ContainerException
     * @throws Exception
     */
    public static function handle($commandResult, $container)
    {
        /** @var GoogleCalendarService $googleCalendarService */
        $googleCalendarService = $container->get('infrastructure.google.calendar.service');
        /** @var OutlookCalendarService $outlookCalendarService */
        $outlookCalendarService = $container->get('infrastructure.outlook.calendar.service');
        /** @var EmailNotificationService $emailNotificationService */
        $emailNotificationService = $container->get('application.emailNotification.service');
        /** @var SMSNotificationService $smsNotificationService */
        $smsNotificationService = $container->get('application.smsNotification.service');
        /** @var SettingsService $settingsService */
        $settingsService = $container->get('domain.settings.service');
        /** @var WebHookApplicationService $webHookService */
        $webHookService = $container->get('application.webHook.service');
        /** @var ZoomApplicationService $zoomService */
        $zoomService = $container->get('application.zoom.service');
        /** @var BookingApplicationService $bookingApplicationService */
        $bookingApplicationService = $container->get('application.booking.booking.service');

        $type = $commandResult->getData()['type'];

        $booking = $commandResult->getData()[Entities::BOOKING];
        $appointmentStatusChanged = $commandResult->getData()['appointmentStatusChanged'];

        $recurringData = $commandResult->getData()['recurring'];

        if ($commandResult->getData()['packageId'] && $booking === null) {
            /** @var PackageRepository $packageRepository */
            $packageRepository = $container->get('domain.bookable.package.repository');

            /** @var Package $package */
            $package = $packageRepository->getById($commandResult->getData()['packageId']);

            $packageReservation = array_merge(
                array_merge($package->toArray(), ['customer' => $commandResult->getData()['customer']]),
                [
                    'recurring' => []
                ]
            );

            $emailNotificationService->sendPackagePurchasedNotifications($packageReservation, false);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendPackagePurchasedNotifications($packageReservation, false);
            }

            return;
        }

        /** @var Appointment|Event $reservationObject */
        $reservationObject = null;

        if ($type === Entities::APPOINTMENT) {
            $reservationObject = AppointmentFactory::create($commandResult->getData()[$type]);
        }

        if ($type === Entities::EVENT) {
            $reservationObject = EventFactory::create($commandResult->getData()[$type]);
        }

        $reservation = $reservationObject->toArray();

        if ($type === Entities::APPOINTMENT) {
            $bookingApplicationService->setReservationEntities($reservationObject);

            $reservation['provider'] = $reservationObject->getProvider()->toArray();

            if ($zoomService) {
                $zoomService->handleAppointmentMeeting($reservationObject, self::BOOKING_ADDED);

                if ($reservationObject->getZoomMeeting()) {
                    $reservation['zoomMeeting'] = $reservationObject->getZoomMeeting()->toArray();
                }
            }

            if ($googleCalendarService) {
                try {
                    $googleCalendarService->handleEvent($reservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }
            }

            if ($reservationObject->getGoogleCalendarEventId() !== null) {
                $reservation['googleCalendarEventId'] = $reservationObject->getGoogleCalendarEventId()->getValue();
            }
            if ($reservationObject->getGoogleMeetUrl() !== null) {
                $reservation['googleMeetUrl'] = $reservationObject->getGoogleMeetUrl();
            }

            if ($outlookCalendarService) {
                try {
                    $outlookCalendarService->handleEvent($reservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }
            }

            if ($reservationObject->getOutlookCalendarEventId() !== null) {
                $reservation['outlookCalendarEventId'] = $reservationObject->getOutlookCalendarEventId()->getValue();
            }
        }

        if (($type === Entities::EVENT) && $zoomService) {
            $zoomService->handleEventMeeting(
                $reservationObject,
                $reservationObject->getPeriods(),
                self::BOOKING_ADDED
            );

            $reservation['periods'] = $reservationObject->getPeriods()->toArray();
        }

        foreach ($recurringData as $key => $recurringReservationData) {
            $recurringReservationObject = AppointmentFactory::create($recurringReservationData[$type]);

            $bookingApplicationService->setReservationEntities($recurringReservationObject);

            $recurringData[$key][$type]['provider'] = $recurringReservationObject->getProvider()->toArray();

            if ($zoomService) {
                $zoomService->handleAppointmentMeeting($recurringReservationObject, self::BOOKING_ADDED);

                if ($recurringReservationObject->getZoomMeeting()) {
                    $recurringData[$key][$type]['zoomMeeting'] =
                        $recurringReservationObject->getZoomMeeting()->toArray();
                }
            }

            if ($googleCalendarService) {
                try {
                    $googleCalendarService->handleEvent($recurringReservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }

                if ($recurringReservationObject->getGoogleCalendarEventId() !== null) {
                    $recurringData[$key][$type]['googleCalendarEventId'] =
                        $recurringReservationObject->getGoogleCalendarEventId()->getValue();
                }
                if ($recurringReservationObject->getGoogleMeetUrl() !== null) {
                    $recurringData[$key][$type]['googleMeetUrl'] =
                        $recurringReservationObject->getGoogleMeetUrl();
                }
            }

            if ($outlookCalendarService) {
                try {
                    $outlookCalendarService->handleEvent($recurringReservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }

                if ($recurringReservationObject->getOutlookCalendarEventId() !== null) {
                    $recurringData[$key][$type]['outlookCalendarEventId'] =
                        $recurringReservationObject->getOutlookCalendarEventId()->getValue();
                }
            }
        }

        /** @var IcsApplicationService $icsService */
        $icsService = $container->get('application.ics.service');

        $recurringBookingIds = [];

        $icsFiles = [];

        foreach ($recurringData as $recurringReservation) {
            $recurringBookingIds[] = $recurringReservation[Entities::BOOKING]['id'];
        }

        foreach ($reservation['bookings'] as $index => $reservationBooking) {
            if ($reservationBooking['id'] === $booking['id'] && $booking['status'] === BookingStatus::APPROVED) {
                $icsFiles = $icsService->getIcsData(
                    $type,
                    $booking['id'],
                    $recurringBookingIds,
                    true
                );

                $reservation['bookings'][$index]['icsFiles'] = $icsFiles;
            }

            $reservation['bookings'][$index]['isLastBooking'] = $booking['id'] === $reservationBooking['id'];
        }

        $reservation['recurring'] = $recurringData;

        if ($appointmentStatusChanged === true && !$commandResult->getData()['packageId']) {
            foreach ($reservation['bookings'] as $bookingKey => $bookingArray) {
                if ($bookingArray['id'] !== $booking['id'] &&
                    $bookingArray['status'] === BookingStatus::APPROVED &&
                    $reservation['status'] === BookingStatus::APPROVED
                ) {
                    $reservation['bookings'][$bookingKey]['isChangedStatus'] = true;
                }
            }
        }

        if ($appointmentStatusChanged === true && !$commandResult->getData()['packageId']) {
            $emailNotificationService->sendAppointmentStatusNotifications($reservation, true, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendAppointmentStatusNotifications($reservation, true, true);
            }
        }

        if ($appointmentStatusChanged !== true && !$commandResult->getData()['packageId']) {
            $emailNotificationService->sendBookingAddedNotifications($reservation, $booking, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendBookingAddedNotifications($reservation, $booking, true);
            }
        }

        if ($commandResult->getData()['packageId']) {
            /** @var PackageRepository $packageRepository */
            $packageRepository = $container->get('domain.bookable.package.repository');

            /** @var Package $package */
            $package = $packageRepository->getById($commandResult->getData()['packageId']);

            $packageReservation = array_merge(
                array_merge(
                    $package->toArray(),
                    ['customer' => $commandResult->getData()['customer'], 'icsFiles' => $icsFiles]
                ),
                [
                    'recurring' => array_merge(
                        [
                            [
                                'type'                     => Entities::APPOINTMENT,
                                Entities::APPOINTMENT      => $reservation,
                                Entities::BOOKING          => $booking,
                                'appointmentStatusChanged' => $appointmentStatusChanged,
                            ]
                        ],
                        $reservation['recurring']
                    )
                ]
            );

            $emailNotificationService->sendPackagePurchasedNotifications($packageReservation, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendPackagePurchasedNotifications($packageReservation, true);
            }
        }

        foreach ($recurringData as $key => $recurringReservationData) {
            if ($recurringReservationData['appointmentStatusChanged'] === true) {
                foreach ($recurringData[$key][$type]['bookings'] as $bookingKey => $recurringReservationBooking) {
                    if ($recurringReservationBooking['customerId'] === $booking['customerId']) {
                        $recurringData[$key][$type]['bookings'][$bookingKey]['skipNotification'] = true;
                    }

                    if ($recurringReservationBooking['id'] !== $booking['id'] &&
                        $recurringReservationBooking['status'] === BookingStatus::APPROVED &&
                        $recurringData[$key][$type]['status'] === BookingStatus::APPROVED
                    ) {
                        $recurringData[$key][$type]['bookings'][$bookingKey]['isChangedStatus'] = true;
                    }
                }

                $emailNotificationService->sendAppointmentStatusNotifications(
                    $recurringData[$key][$type],
                    true,
                    true
                );

                if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                    $smsNotificationService->sendAppointmentStatusNotifications(
                        $recurringData[$key][$type],
                        true,
                        true
                    );
                }
            }
        }

        $webHookService->process(
            self::BOOKING_ADDED,
            $reservation,
            [
                array_merge(
                    $booking,
                    [
                        'isRecurringBooking' => $recurringData && !$commandResult->getData()['packageId'],
                        'isPackageBooking'   => !!$commandResult->getData()['packageId'],
                    ]
                )
            ]
        );

        foreach ($recurringData as $key => $recurringReservationData) {
            $webHookService->process(
                self::BOOKING_ADDED,
                $recurringReservationData[$type],
                [
                    array_merge(
                        $recurringReservationData['booking'],
                        [
                            'isRecurringBooking' => !$commandResult->getData()['packageId'],
                            'isPackageBooking'   => !!$commandResult->getData()['packageId'],
                        ]
                    )
                ]
            );
        }
    }
}
