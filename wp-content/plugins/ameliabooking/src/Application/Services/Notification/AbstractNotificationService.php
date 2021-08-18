<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Application\Services\Notification;

use AmeliaBooking\Application\Services\Booking\BookingApplicationService;
use AmeliaBooking\Domain\Collection\AbstractCollection;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Booking\Event\EventPeriod;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\Notification\Notification;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\ValueObjects\BooleanValueObject;
use AmeliaBooking\Domain\ValueObjects\String\NotificationSendTo;
use AmeliaBooking\Domain\ValueObjects\String\NotificationStatus;
use AmeliaBooking\Domain\ValueObjects\String\Token;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Notification\NotificationLogRepository;
use AmeliaBooking\Infrastructure\Repository\Notification\NotificationRepository;
use AmeliaBooking\Infrastructure\Repository\Notification\NotificationsToEntitiesRepository;
use Exception;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class AbstractNotificationService
 *
 * @package AmeliaBooking\Application\Services\Notification
 */
abstract class AbstractNotificationService
{
    /** @var Container */
    protected $container;

    /** @var string */
    protected $type;

    /**
     * AbstractNotificationService constructor.
     *
     * @param Container $container
     * @param string    $type
     */
    public function __construct(Container $container, $type)
    {
        $this->container = $container;

        $this->type = $type;
    }

    /**
     * @param array        $appointmentArray
     * @param Notification $notification
     * @param bool         $logNotification
     * @param null         $bookingKey
     *
     * @return mixed
     */
    abstract public function sendNotification(
        $appointmentArray,
        $notification,
        $logNotification,
        $bookingKey = null
    );


    /**
     * @throws NotFoundException
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     * @throws ContainerException
     * @throws Exception
     */
    abstract public function sendBirthdayGreetingNotifications();

    /**
     *
     * @param string $name
     * @param string $type
     *
     * @return mixed
     *
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     */
    public function getByNameAndType($name, $type)
    {
        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = $this->container->get('domain.notification.repository');
        /** @var NotificationsToEntitiesRepository $notificationEntitiesRepo */
        $notificationEntitiesRepo = $this->container->get('domain.notificationEntities.repository');

        /** @var Collection $notifications */
        $notifications = $notificationRepo->getByNameAndType($name, $type);
        /** @var Notification $notification */
        foreach ($notifications->getItems() as $notification) {
            if ($notification->getCustomName() !== null) {
                $notification->setEntityIds($notificationEntitiesRepo->getEntities($notification->getId()->getValue()));
            }
        }

        return $notifications;
    }

    /**
     *
     * @param int $id
     *
     * @return mixed
     *
     * @throws QueryExecutionException
     * @throws NotFoundException
     */
    public function getById($id)
    {
        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = $this->container->get('domain.notification.repository');

        return $notificationRepo->getById($id);
    }

    /**
     * @param array $appointmentArray
     * @param bool  $forcedStatusChange - True when appointment status is changed to 'pending' because minimum capacity
     * condition is not satisfied
     * @param bool  $logNotification
     *
     * @throws ContainerValueNotFoundException
     * @throws QueryExecutionException
     */
    public function sendAppointmentStatusNotifications($appointmentArray, $forcedStatusChange, $logNotification)
    {
        /** @var BookingApplicationService $bookingAS */
        $bookingAS = $this->container->get('application.booking.booking.service');

        // Notify provider
        /** @var Collection $providerNotifications */
        $providerNotifications = $this->getByNameAndType(
            "provider_{$appointmentArray['type']}_{$appointmentArray['status']}",
            $this->type
        );

        $sendDefault = $this->sendDefault($providerNotifications, $appointmentArray);


        /** @var Notification $providerNotification */
        foreach ($providerNotifications->getItems() as $providerNotification) {
            if ($providerNotification && $providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                if (!$this->checkCustom($providerNotification, $appointmentArray, $sendDefault)) {
                    continue;
                }
                $this->sendNotification(
                    $appointmentArray,
                    $providerNotification,
                    $logNotification
                );
            }
        }

        // Notify customers
        if ($appointmentArray['notifyParticipants']) {

            /** @var Collection $customerNotifications */
            $customerNotifications = $this->getByNameAndType(
                "customer_{$appointmentArray['type']}_{$appointmentArray['status']}",
                $this->type
            );

            $sendDefault = $this->sendDefault($customerNotifications, $appointmentArray);

            foreach ($customerNotifications->getItems() as $customerNotification) {
                if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                    if (!$this->checkCustom($customerNotification, $appointmentArray, $sendDefault)) {
                        continue;
                    }
                    // If appointment status is changed to 'pending' because minimum capacity condition is not satisfied,
                    // return all 'approved' bookings and send them notification that appointment is now 'pending'.
                    if ($forcedStatusChange === true) {
                        $appointmentArray['bookings'] = $bookingAS->filterApprovedBookings($appointmentArray['bookings']);
                    }

                    // Notify each customer from customer bookings
                    foreach (array_keys($appointmentArray['bookings']) as $bookingKey) {
                        if (!$appointmentArray['bookings'][$bookingKey]['isChangedStatus'] ||
                            (
                                isset($appointmentArray['bookings'][$bookingKey]['skipNotification']) &&
                                $appointmentArray['bookings'][$bookingKey]['skipNotification']
                            )
                        ) {
                            continue;
                        }

                        if (empty($dontSendDefault) || $providerNotification->getCustomName()) {
                            $this->sendNotification(
                                $appointmentArray,
                                $customerNotification,
                                $logNotification,
                                $bookingKey
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $appointmentArray
     * @param array $bookingsArray
     * @param bool  $forcedStatusChange
     *
     * @throws QueryExecutionException
     */
    public function sendAppointmentEditedNotifications($appointmentArray, $bookingsArray, $forcedStatusChange)
    {
        /** @var BookingApplicationService $bookingAS */
        $bookingAS = $this->container->get('application.booking.booking.service');

        // Notify customers
        if ($appointmentArray['notifyParticipants']) {
            // If appointment status is 'pending', remove all 'approved' bookings because they can't receive
            // notification that booking is 'approved' until appointment status is changed to 'approved'
            if ($appointmentArray['status'] === 'pending') {
                $bookingsArray = $bookingAS->removeBookingsByStatuses($bookingsArray, ['approved']);
            }

            // If appointment status is changed, because minimum capacity condition is satisfied or not,
            // remove all 'approved' bookings because notification is already sent to them.
            if ($forcedStatusChange === true) {
                $bookingsArray = $bookingAS->removeBookingsByStatuses($bookingsArray, ['approved']);
            }

            if (!$appointmentArray['employee_changed']) {
                $appointmentArray['bookings'] = $bookingsArray;
            }

            foreach (array_keys($appointmentArray['bookings']) as $bookingKey) {
                /** @var Collection $customerNotifications */
                $customerNotifications =
                    $this->getByNameAndType(
                        "customer_appointment_{$appointmentArray['bookings'][$bookingKey]['status']}",
                        $this->type
                    );

                $sendDefault = $this->sendDefault($customerNotifications, $appointmentArray);
                foreach ($customerNotifications->getItems() as $customerNotification) {
                    if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                        if (!$this->checkCustom($customerNotification, $appointmentArray, $sendDefault)) {
                            continue;
                        }
                        if (!$appointmentArray['bookings'][$bookingKey]['isChangedStatus'] &&
                            !$appointmentArray['employee_changed']
                        ) {
                            continue;
                        }

                        $this->sendNotification(
                            $appointmentArray,
                            $customerNotification,
                            true,
                            $bookingKey
                        );

                        if ($appointmentArray['employee_changed']) {
                            // Notify provider
                            /** @var Collection $providerNotifications */
                            $providerNotifications = $this->getByNameAndType(
                                "provider_{$appointmentArray['type']}_rescheduled",
                                $this->type
                            );

                            $sendDefault = $this->sendDefault($providerNotifications, $appointmentArray);

                            foreach ($providerNotifications->getItems() as $providerNotification) {
                                if ($providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                                    if (!$this->checkCustom($providerNotification, $appointmentArray, $sendDefault)) {
                                        continue;
                                    }
                                    $this->sendNotification(
                                        $appointmentArray,
                                        $providerNotification,
                                        true
                                    );
                                }
                            }

                        }
                    }
                }

            }
        }
    }

    /**
     * @param $appointmentArray
     *
     * @throws QueryExecutionException
     */
    public function sendAppointmentRescheduleNotifications($appointmentArray)
    {
        // Notify customers
        if ($appointmentArray['notifyParticipants']) {

            /** @var Collection $customerNotifications */
            $customerNotifications = $this->getByNameAndType(
                "customer_{$appointmentArray['type']}_rescheduled",
                $this->type
            );

            $sendDefault = $this->sendDefault($customerNotifications, $appointmentArray);
            foreach ($customerNotifications->getItems() as $customerNotification) {
                if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                    if (!$this->checkCustom($customerNotification, $appointmentArray, $sendDefault)) {
                        continue;
                    }
                    // Notify each customer from customer bookings
                    foreach (array_keys($appointmentArray['bookings']) as $bookingKey) {
                        $this->sendNotification(
                            $appointmentArray,
                            $customerNotification,
                            true,
                            $bookingKey
                        );
                    }
                }
            }
        }

        // Notify provider
        /** @var Collection $providerNotifications */
        $providerNotifications = $this->getByNameAndType(
            "provider_{$appointmentArray['type']}_rescheduled",
            $this->type
        );

        $sendDefault = $this->sendDefault($providerNotifications, $appointmentArray);
        foreach ($providerNotifications->getItems() as $providerNotification) {
            if ($providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                if (!$this->checkCustom($providerNotification, $appointmentArray, $sendDefault)) {
                    continue;
                }
                $this->sendNotification(
                    $appointmentArray,
                    $providerNotification,
                    true
                );
            }
        }
    }

    /**
     * @param array $appointmentArray
     * @param array $bookingArray
     * @param bool  $logNotification
     *
     * @throws QueryExecutionException
     */
    public function sendBookingAddedNotifications($appointmentArray, $bookingArray, $logNotification)
    {
        /** @var Collection $customerNotifications */
        $customerNotifications = $this->getByNameAndType(
            "customer_{$appointmentArray['type']}_{$appointmentArray['status']}",
            $this->type
        );

        $sendDefault = $this->sendDefault($customerNotifications, $appointmentArray);

        foreach ($customerNotifications->getItems() as $customerNotification) {
            if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                if (!$this->checkCustom($customerNotification, $appointmentArray, $sendDefault)) {
                    continue;
                }

                // Notify customer that scheduled the appointment
                $this->sendNotification(
                    $appointmentArray,
                    $customerNotification,
                    $logNotification,
                    array_search($bookingArray['id'], array_column($appointmentArray['bookings'], 'id'), true)
                );
            }
        }

        // Notify provider
        /** @var Collection $providerNotifications */
        $providerNotifications = $this->getByNameAndType(
            "provider_{$appointmentArray['type']}_{$appointmentArray['status']}",
            $this->type
        );

        $sendDefault = $this->sendDefault($providerNotifications, $appointmentArray);
        foreach ($providerNotifications->getItems() as $providerNotification) {
            if ($providerNotification && $providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                if (!$this->checkCustom($providerNotification, $appointmentArray, $sendDefault)) {
                    continue;
                }
                $this->sendNotification(
                    $appointmentArray,
                    $providerNotification,
                    $logNotification
                );
            }
        }
    }

    /**
     * Notify the customer when he changes his booking status.
     *
     * @param $appointmentArray
     * @param $bookingArray
     *
     * @throws QueryExecutionException
     */
    public function sendCustomerBookingNotification($appointmentArray, $bookingArray)
    {
        // Notify customers
        if ($appointmentArray['notifyParticipants']) {

            /** @var Collection $customerNotifications */
            $customerNotifications = $this->getByNameAndType("customer_{$appointmentArray['type']}_{$bookingArray['status']}", $this->type);

            $sendDefault = $this->sendDefault($customerNotifications, $appointmentArray);
            foreach ($customerNotifications->getItems() as $customerNotification) {
                if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                    if (!$this->checkCustom($customerNotification, $appointmentArray, $sendDefault)) {
                        continue;
                    }
                    // Notify customer
                    $bookingKey = array_search(
                        $bookingArray['id'],
                        array_column($appointmentArray['bookings'], 'id'),
                        true
                    );

                    $this->sendNotification(
                        $appointmentArray,
                        $customerNotification,
                        true,
                        $bookingKey
                    );
                }
            }
        }
    }

    /**
     * Notify the provider when the customer cancels event booking.
     *
     * @param $eventArray
     * @param $bookingArray
     *
     * @throws QueryExecutionException
     */
    public function sendProviderEventCancelledNotification($eventArray, $bookingArray)
    {
        /** @var Collection $providerNotifications */
        $providerNotifications = $this->getByNameAndType(
            "provider_event_canceled",
            $this->type
        );

        $eventArray['bookings'] = [$bookingArray];

        $sendDefault = $this->sendDefault($providerNotifications, $eventArray);
        foreach ($providerNotifications->getItems() as $providerNotification) {
            if ($providerNotification && $providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                if (!$this->checkCustom($providerNotification, $eventArray, $sendDefault)) {
                    continue;
                }
                $this->sendNotification(
                    $eventArray,
                    $providerNotification,
                    false,
                    null
                );
            }
        }
    }

    /**
     * Returns an array of next day reminder notifications that have to be sent to customers with cron
     *
     * @param string $entityType
     *
     * @return void
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function sendNextDayReminderNotifications($entityType)
    {
        /** @var NotificationLogRepository $notificationLogRepo */
        $notificationLogRepo = $this->container->get('domain.notificationLog.repository');

        /** @var Collection $customerNotifications */
        $customerNotifications  = $this->getByNameAndType("customer_{$entityType}_next_day_reminder", $this->type);
        $customerNotifications2 = $this->getByNameAndType("customer_{$entityType}_scheduled", $this->type);

        foreach ($customerNotifications2->getItems() as $notification) {
            $customerNotifications->addItem($notification);
        }

        $reservations = new Collection();

        /** @var Notification $customerNotification */
        foreach ($customerNotifications->getItems() as $customerNotification) {
            // Check if notification is enabled and it is time to send notification
            if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED &&
                DateTimeService::getNowDateTimeObject() >=
                DateTimeService::getCustomDateTimeObject($customerNotification->getTime()->getValue())
            ) {
                switch ($entityType) {
                    case Entities::APPOINTMENT:
                        $reservations = $notificationLogRepo->getCustomersNextDayAppointments($customerNotification->getId()->getValue(), $customerNotification->getCustomName() === null);

                        break;
                    case Entities::EVENT:
                        $reservations = $notificationLogRepo->getCustomersNextDayEvents($customerNotification->getId()->getValue(), $customerNotification->getCustomName() === null);

                        break;
                }

                $this->sendBookingsNotifications($customerNotification, $reservations, true);
            }
        }


        /** @var Collection $providerNotifications */
        $providerNotifications  = $this->getByNameAndType("provider_{$entityType}_next_day_reminder", $this->type);
        $providerNotifications2 = $this->getByNameAndType("provider_{$entityType}_scheduled", $this->type);

        foreach ($providerNotifications2->getItems() as $notification) {
            $providerNotifications->addItem($notification);
        }

        /** @var Notification $providerNotification */
        foreach ($providerNotifications->getItems() as $providerNotification) {
            // Check if notification is enabled and it is time to send notification
            if ($providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED &&
                DateTimeService::getNowDateTimeObject() >=
                DateTimeService::getCustomDateTimeObject($providerNotification->getTime()->getValue())
            ) {
                switch ($entityType) {
                    case Entities::APPOINTMENT:
                        $reservations = $notificationLogRepo->getProvidersNextDayAppointments($providerNotification->getId()->getValue(), $providerNotification->getCustomName() === null);

                        break;
                    case Entities::EVENT:
                        $reservations = $notificationLogRepo->getProvidersNextDayEvents($providerNotification->getId()->getValue(), $providerNotification->getCustomName() === null);

                        break;
                }

                foreach ((array)$reservations->toArray() as $reservationArray) {
                    if (!$this->checkCustom($providerNotification, $reservationArray, true)) {
                        continue;
                    }
                    if ($providerNotification->getCustomName() === null && !$this->checkShouldSend($reservationArray, true)) {
                        continue;
                    }
                    $this->sendNotification(
                        $reservationArray,
                        $providerNotification,
                        true
                    );
                }
            }
        }
    }

    /**
     * @param string $entityType
     *
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     */
    public function sendScheduledNotifications($entityType)
    {
        /** @var Collection $notifications */
        $notifications  = $this->getByNameAndType("customer_{$entityType}_follow_up", $this->type);
        $notifications2 = $this->getByNameAndType("customer_{$entityType}_scheduled_%", $this->type);
        foreach ($notifications2->getItems() as $notification) {
            $notifications->addItem($notification);
        }
        $notifications2 = $this->getByNameAndType("provider_{$entityType}_scheduled_%", $this->type);
        foreach ($notifications2->getItems() as $notification) {
            $notifications->addItem($notification);
        }

        /** @var Notification $notification */
        foreach ($notifications->getItems() as $notification) {
            if ($notification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                /** @var NotificationLogRepository $notificationLogRepo */
                $notificationLogRepo = $this->container->get('domain.notificationLog.repository');

                $reservations = new Collection();

                switch ($entityType) {
                    case Entities::APPOINTMENT:
                        $reservations = $notificationLogRepo->getScheduledAppointments($notification);

                        break;
                    case Entities::EVENT:
                        $reservations = $notificationLogRepo->getScheduledEvents($notification);

                        $currentDateTime = DateTimeService::getNowDateTimeObject();

                        /** @var Event $event */
                        foreach ($reservations->getItems() as $eventKey => $event) {
                            if ($notification->getTimeAfter()) {
                                $period = $event->getPeriods()->getItem($event->getPeriods()->length() - 1);

                                $afterPeriodEndDateTime = DateTimeService::getCustomDateTimeObject(
                                    $period->getPeriodEnd()->getValue()->format('Y-m-d H:i:s')
                                )->modify("+{$notification->getTimeAfter()->getValue()} seconds");

                                $lastPossibleNotificationMoment = DateTimeService::getCustomDateTimeObject(
                                    $afterPeriodEndDateTime->format('Y-m-d H:i:s')
                                )->modify('+432000 seconds');

                                if (!($currentDateTime >= $afterPeriodEndDateTime && $currentDateTime <= $lastPossibleNotificationMoment)) {
                                    $reservations->deleteItem($eventKey);
                                }
                            } else if ($notification->getTimeBefore()) {
                                $period = $event->getPeriods()->getItem(0);

                                $eventStarts = DateTimeService::getCustomDateTimeObject(
                                    $period->getPeriodStart()->getValue()->format('Y-m-d H:i:s')
                                );

                                $beforePeriodStartDateTime = DateTimeService::getCustomDateTimeObject(
                                    $eventStarts->format('Y-m-d H:i:s')
                                )->modify("-{$notification->getTimeBefore()->getValue()} seconds");

                                if (!($currentDateTime >= $beforePeriodStartDateTime && $currentDateTime <= $eventStarts)) {
                                    $reservations->deleteItem($eventKey);
                                }
                            }

                        }

                        break;
                }

                $this->sendBookingsNotifications($notification, $reservations, $notification->getTimeBefore() !== null);
            }
        }
    }

    /**
     * Send passed notification for all passed bookings and save log in the database
     *
     * @param Notification $notification
     * @param Collection $appointments
     * @param bool $before
     * @throws QueryExecutionException
     */
    private function sendBookingsNotifications($notification, $appointments, $before)
    {
        /** @var array $appointmentArray */
        foreach ($appointments->toArray() as $appointmentArray) {
            if (!$this->checkCustom($notification, $appointmentArray, true)) {
                continue;
            }
            if ($notification->getCustomName() === null && !$this->checkShouldSend($appointmentArray, $before)) {
                continue;
            }

            if ($notification->getSendTo() === NotificationSendTo::PROVIDER) {
                $this->sendNotification(
                    $appointmentArray,
                    $notification,
                    true
                );
            } else {
                // Notify each customer from customer bookings
                foreach (array_keys($appointmentArray['bookings']) as $bookingKey) {
                    $this->sendNotification(
                        $appointmentArray,
                        $notification,
                        true,
                        $bookingKey
                    );
                }
            }
        }
    }

    /**
     * Check if schedule default notification should be sent
     *
     * @param array $appointmentArray
     * @param bool $before
     * @throws QueryExecutionException
     *
     * return bool
     *
     */
    private function checkShouldSend($appointmentArray, $before)
    {
        $time = $before ? 'timeBefore' : 'timeAfter';
        $entityId = $appointmentArray['type'] === Entities::EVENT ? $appointmentArray['id'] : $appointmentArray['serviceId'];
        $notifications = $this->getByNameAndType("customer_{$appointmentArray['type']}_scheduled_%", $this->type);
        return empty(
            array_filter(
                $notifications->toArray(),
                function ($a) use (&$entityId, &$time) {
                    return $a['customName'] && $a[$time] && $a['sendOnlyMe'] &&
                        ($a['entityIds'] === null || in_array($entityId, $a['entityIds']));
                }
            )
        );
    }

    /**
     * Check if custom notification should be sent
     *
     * @param Notification $notification
     * @param array   $appointmentArray
     *
     * @return bool
     *
     */
    private function checkCustom($notification, $appointmentArray, $sendDefault)
    {
        if (!$sendDefault && !$notification->getCustomName()) {
            return false;
        }
        if ($notification->getCustomName() && $notification->getEntityIds()) {
            $entityId = $appointmentArray['type'] === Entities::EVENT ? $appointmentArray['id'] : $appointmentArray['serviceId'];
            if (!in_array($entityId, $notification->getEntityIds())) {
                if (!in_array($appointmentArray['parentId'], $notification->getEntityIds())) {
                    //Shouldn't be sent
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check if default notification should be sent
     *
     * @param Collection $notifications
     * @param array   $appointmentArray
     *
     * @return bool
     *
     */
    private function sendDefault($notifications, $appointmentArray)
    {
        $entityId = $appointmentArray['type'] === Entities::EVENT ? $appointmentArray['id'] : $appointmentArray['serviceId'];
        return empty(
            array_filter(
                $notifications->toArray(),
                function ($a) use (&$entityId) {
                    return $a['customName'] && $a['sendOnlyMe'] &&
                        ($a['entityIds'] === null || in_array($entityId, $a['entityIds']));
                }
            )
        );
    }

    /**
     * @param array  $data
     * @param bool   $logNotification
     *
     * @throws QueryExecutionException
     */
    public function sendPackagePurchasedNotifications($data, $logNotification)
    {
        /** @var Collection $customerNotifications */
        $customerNotifications = $this->getByNameAndType(
            "customer_package_purchased",
            $this->type
        );

        $data['isForCustomer'] = true;

        foreach ($customerNotifications->getItems() as $customerNotification) {
            if ($customerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                $this->sendNotification(
                    $data,
                    $customerNotification,
                    $logNotification
                );
            }

        }

        /** @var Collection $providerNotifications */
        $providerNotifications = $this->getByNameAndType(
            "provider_package_purchased",
            $this->type
        );

        $data['isForCustomer'] = false;
        foreach ($providerNotifications->getItems() as $providerNotification) {
            if ($providerNotification->getStatus()->getValue() === NotificationStatus::ENABLED) {
                $this->sendNotification(
                    $data,
                    $providerNotification,
                    $logNotification
                );
            }
        }
    }

    /**
     * Get User info for notification
     *
     * @param string $userType
     * @param array  $entityData
     * @param int    $bookingKey
     * @param array  $emailData
     *
     * @return array
     */
    protected function getUsersInfo($userType, $entityData, $bookingKey, $emailData)
    {
        $usersInfo = [];

        switch ($userType) {
            case (Entities::CUSTOMER):
                switch ($entityData['type']) {
                    case (Entities::APPOINTMENT):
                    case (Entities::EVENT):
                        if ($bookingKey !== null) {
                            $usersInfo[$entityData['bookings'][$bookingKey]['customerId']] = [
                                'id'    => $entityData['bookings'][$bookingKey]['customerId'],
                                'email' => $emailData['customer_email'],
                                'phone' => $emailData['customer_phone']
                            ];
                        }

                        break;

                    case (Entities::PACKAGE):
                        $usersInfo[$entityData['customer']['id']] = [
                            'id'    => $entityData['customer']['id'],
                            'email' => $entityData['customer']['email'],
                            'phone' => $entityData['customer']['phone']
                        ];

                        break;
                }


                break;

            case (Entities::PROVIDER):
                switch ($entityData['type']) {
                    case (Entities::APPOINTMENT):
                        $usersInfo[$entityData['providerId']] = [
                            'id'    => $entityData['providerId'],
                            'email' => $emailData['employee_email'],
                            'phone' => $emailData['employee_phone']
                        ];

                        break;

                    case (Entities::EVENT):
                        foreach ((array)$entityData['providers'] as $provider) {
                            $usersInfo[$provider['id']] = [
                                'id'    => $provider['id'],
                                'email' => $provider['email'],
                                'phone' => $provider['phone']
                            ];
                        }

                        break;

                    case (Entities::PACKAGE):
                        foreach ($entityData['recurring'] as $reservation) {
                            $usersInfo[$reservation['appointment']['provider']['id']] = [
                                'id'    => $reservation['appointment']['provider']['id'],
                                'email' => $reservation['appointment']['provider']['email'],
                                'phone' => $reservation['appointment']['provider']['phone']
                            ];
                        }

                        break;
                }

                break;
        }

        return $usersInfo;
    }
}
