<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Event;

use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Services\Booking\IcsApplicationService;
use AmeliaBooking\Application\Services\Notification\EmailNotificationService;
use AmeliaBooking\Application\Services\Notification\SMSNotificationService;
use AmeliaBooking\Application\Services\WebHook\WebHookApplicationService;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Booking\Event\EventPeriod;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Factory\Booking\Event\EventFactory;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Application\Services\Zoom\ZoomApplicationService;

/**
 * Class EventEditedEventHandler
 *
 * @package AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Event
 */
class EventEditedEventHandler
{
    /** @var string */
    const TIME_UPDATED = 'bookingTimeUpdated';

    /** @var string */
    const EVENT_DELETED = 'eventDeleted';

    /** @var string */
    const EVENT_ADDED = 'eventAdded';

    /** @var string */
    const EVENT_PERIOD_DELETED = 'eventPeriodDeleted';

    /** @var string */
    const EVENT_PERIOD_ADDED = 'eventPeriodAdded';

    /** @var string */
    const ZOOM_USER_ADDED = 'zoomUserAdded';

    /**
     * @param CommandResult $commandResult
     * @param Container     $container
     *
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Exception
     */
    public static function handle($commandResult, $container)
    {
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

        $eventsData = $commandResult->getData()[Entities::EVENTS];

        /** @var Collection $deletedEvents */
        $deletedEvents = self::getCollection($eventsData['deleted']);

        /** @var Collection $rescheduledEvents */
        $rescheduledEvents = self::getCollection($eventsData['rescheduled']);

        /** @var Collection $addedEvents */
        $addedEvents = self::getCollection($eventsData['added']);

        /** @var Collection $clonedEvents */
        $clonedEvents = self::getCollection($eventsData['cloned']);

        /** @var Event $event */
        foreach ($deletedEvents->getItems() as $event) {
            $eventId = $event->getId()->getValue();

            if ($zoomService &&
                $clonedEvents->keyExists($eventId) &&
                $clonedEvents->getItem($eventId)->getStatus()->getValue() === BookingStatus::APPROVED
            ) {
                $zoomService->handleEventMeeting($event, $event->getPeriods(), self::EVENT_DELETED);
            }
        }

        /** @var Event $event */
        foreach ($addedEvents->getItems() as $event) {
            if ($zoomService) {
                $zoomService->handleEventMeeting($event, $event->getPeriods(), self::EVENT_ADDED);
            }
        }

        /** @var Event $event */
        foreach ($rescheduledEvents->getItems() as $event) {
            $eventId = $event->getId()->getValue();

            /** @var Event $clonedEvent */
            $clonedEvent = $clonedEvents->keyExists($eventId) ? $clonedEvents->getItem($eventId) : null;

            if ($zoomService && $clonedEvent && $clonedEvent->getStatus()->getValue() === BookingStatus::APPROVED) {
                /** @var Collection $rescheduledPeriods */
                $rescheduledPeriods = new Collection();

                /** @var Collection $addedPeriods */
                $addedPeriods = new Collection();

                /** @var Collection $deletedPeriods */
                $deletedPeriods = new Collection();

                /** @var EventPeriod $eventPeriod */
                foreach ($event->getPeriods()->getItems() as $eventPeriod) {
                    $eventPeriodId = $eventPeriod->getId()->getValue();

                    /** @var EventPeriod $clonedEventPeriod */
                    $clonedEventPeriod = $clonedEvent->getPeriods()->keyExists($eventPeriodId) ?
                        $clonedEvent->getPeriods()->getItem($eventPeriodId) : null;

                    if ($clonedEventPeriod && $clonedEventPeriod->toArray() !== $eventPeriod->toArray()) {
                        $rescheduledPeriods->addItem($eventPeriod, $eventPeriodId);
                    } elseif (!$clonedEventPeriod) {
                        $addedPeriods->addItem($eventPeriod, $eventPeriodId);
                    }
                }

                /** @var EventPeriod $clonedEventPeriod */
                foreach ($clonedEvent->getPeriods() as $clonedEventPeriod) {
                    if (!$event->getPeriods()->keyExists($clonedEventPeriod)) {
                        $deletedPeriods->addItem($clonedEventPeriod, $clonedEventPeriod->getId()->getValue());
                    }
                }

                if ($rescheduledPeriods->length()) {
                    $zoomService->handleEventMeeting($event, $rescheduledPeriods, self::TIME_UPDATED);
                }

                if ($addedPeriods->length()) {
                    $zoomService->handleEventMeeting($event, $addedPeriods, self::EVENT_PERIOD_ADDED);
                }

                if ($deletedPeriods->length()) {
                    $zoomService->handleEventMeeting($event, $deletedPeriods, self::EVENT_PERIOD_DELETED);
                }
            }
        }

        if ($commandResult->getData()['zoomUserAdded'] || $commandResult->getData()['newInfo']) {
            if (!$rescheduledEvents->length()) {
                /** @var Event $event */
                foreach ($clonedEvents->getItems() as $event) {
                    $zoomService->handleEventMeeting($event, $event->getPeriods(), self::ZOOM_USER_ADDED);
                }
            }
        }

        foreach ($eventsData['rescheduled'] as $eventArray) {

            /** @var IcsApplicationService $icsService */
            $icsService = $container->get('application.ics.service');

            foreach ($eventArray['bookings'] as $index => $booking) {
                if ($booking['status'] === BookingStatus::APPROVED || $booking['status'] === BookingStatus::PENDING) {
                    $eventArray['bookings'][$index]['icsFiles'] = $icsService->getIcsData(
                        Entities::EVENT,
                        $booking['id'],
                        [],
                        true
                    );
                }
            }

            $emailNotificationService->sendAppointmentRescheduleNotifications($eventArray);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendAppointmentRescheduleNotifications($eventArray);
            }

            $webHookService->process(self::TIME_UPDATED, $eventArray, []);
        }
    }

    /**
     * @param array $eventsArray
     *
     * @return Collection
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     */
    private static function getCollection($eventsArray)
    {
        /** @var Collection $events */
        $events = new Collection();

        foreach ($eventsArray as $eventArray) {
            /** @var Event $eventObject */
            $eventObject = EventFactory::create($eventArray);

            /** @var Collection $eventPeriods */
            $eventPeriods = new Collection();

            /** @var EventPeriod $period */
            foreach ($eventObject->getPeriods()->getItems() as $period) {
                $eventPeriods->addItem($period, $period->getId()->getValue());
            }

            $eventObject->setPeriods($eventPeriods);

            $events->addItem($eventObject, $eventObject->getId()->getValue());
        }
        return $events;
    }
}
