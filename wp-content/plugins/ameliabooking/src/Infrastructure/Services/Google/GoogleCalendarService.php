<?php

namespace AmeliaBooking\Infrastructure\Services\Google;

use AmeliaBooking\Application\Services\Placeholder\PlaceholderService;
use AmeliaBooking\Application\Services\User\ProviderApplicationService;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Booking\Appointment\Appointment;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Entity\User\Provider;
use AmeliaBooking\Domain\Factory\Booking\Appointment\AppointmentFactory;
use AmeliaBooking\Domain\Factory\Google\GoogleCalendarFactory;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\String\Email;
use AmeliaBooking\Domain\ValueObjects\String\Token;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\AppointmentRepository;
use AmeliaBooking\Infrastructure\Repository\Location\LocationRepository;
use AmeliaBooking\Infrastructure\Repository\User\CustomerRepository;
use AmeliaBooking\Infrastructure\Repository\User\ProviderRepository;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\AppointmentAddedEventHandler;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\AppointmentDeletedEventHandler;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\AppointmentEditedEventHandler;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\AppointmentStatusUpdatedEventHandler;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\AppointmentTimeUpdatedEventHandler;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\BookingAddedEventHandler;
use AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment\BookingCanceledEventHandler;
use AmeliaGoogle_Service_Calendar_CalendarListEntry;
use AmeliaGoogle_Service_Calendar_Event;
use Exception;
use Interop\Container\Exception\ContainerException;

/**
 * Class GoogleCalendarService
 *
 * @package AmeliaBooking\Infrastructure\Services\Google
 */
class GoogleCalendarService
{

    /** @var Container $container */
    private $container;

    /** @var \AmeliaGoogle_Client $client */
    private $client;

    /** @var \AmeliaGoogle_Service_Calendar $service */
    private $service;

    /** @var SettingsService */
    private $settings;

    /** @var string */
    private $timeZone;

    private static $providersGoogleEvents = [];

    /**
     * GoogleClientService constructor.
     *
     * @param Container $container
     *
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->settings = $this->container->get('domain.settings.service')->getCategorySettings('googleCalendar');

        $this->client = new \AmeliaGoogle_Client();
        $this->client->setClientId($this->settings['clientID']);
        $this->client->setClientSecret($this->settings['clientSecret']);
    }

    /**
     * Create a URL to obtain user authorization.
     *
     * @param $providerId
     * @param $redirectUri
     *
     * @return string
     */
    public function createAuthUrl($providerId, $redirectUri)
    {
        $this->client->setRedirectUri(
            empty($redirectUri) ?
            AMELIA_SITE_URL . '/wp-admin/admin.php?page=wpamelia-employees' :
            explode('?', $redirectUri)[0]
        );
        $this->client->setState($providerId);
        $this->client->addScope('https://www.googleapis.com/auth/calendar');
        $this->client->setApprovalPrompt('force');
        $this->client->setAccessType('offline');

        return $this->client->createAuthUrl();
    }

    /**
     * Exchange a code for an valid authentication token.
     *
     * @param $authCode
     *
     * @return string
     */
    public function fetchAccessTokenWithAuthCode($authCode, $redirectUri)
    {
        $this->client->setRedirectUri($redirectUri);

        return $this->client->authenticate($authCode);
    }

    /**
     * Returns entries on the user's calendar list.
     *
     * @param Provider $provider
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    public function listCalendarList($provider)
    {
        $calendars = [];

        if ($provider->getGoogleCalendar()) {
            $this->authorizeProvider($provider);

            $calendarList = $this->service->calendarList->listCalendarList(['minAccessRole' => 'writer']);

            /** @var AmeliaGoogle_Service_Calendar_CalendarListEntry $calendar */
            foreach ($calendarList->getItems() as $calendar) {
                $calendars[] = [
                    'id'      => $calendar->getId(),
                    'primary' => $calendar->getPrimary(),
                    'summary' => $calendar->getSummary()
                ];
            }
        }

        return $calendars;
    }

    /**
     * Get Provider's Google Calendar ID.
     *
     * @param Provider $provider
     *
     * @return null|string
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    public function getProviderGoogleCalendarId($provider)
    {
        // If Google Calendar ID is not set, take the primary calendar and save it as Provider's Google Calendar ID
        if ($provider->getGoogleCalendar() && $provider->getGoogleCalendar()->getCalendarId()->getValue() === null) {
            $calendarList = $this->listCalendarList($provider);

            /** @var ProviderApplicationService $providerApplicationService */
            $providerApplicationService = $this->container->get('application.user.provider.service');

            $provider->getGoogleCalendar()->setCalendarId(new Email($calendarList[0]['id']));

            $providerApplicationService->updateProviderGoogleCalendar($provider);

            return $provider->getGoogleCalendar()->getCalendarId()->getValue();
        }

        // If Google Calendar is set, return it
        if ($provider->getGoogleCalendar() && $provider->getGoogleCalendar()->getCalendarId()->getValue() !== null) {
            return $provider->getGoogleCalendar()->getCalendarId()->getValue();
        }

        return null;
    }

    /**
     * Handle Google Calendar Event's.
     *
     * @param Appointment $appointment
     * @param string      $commandSlug
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    public function handleEvent($appointment, $commandSlug)
    {
        /** @var ProviderRepository $providerRepository */
        $providerRepository = $this->container->get('domain.users.providers.repository');

        $appointmentStatus = $appointment->getStatus()->getValue();

        $provider = $providerRepository->getById($appointment->getProviderId()->getValue());

        if ($provider->getGoogleCalendar() && $provider->getGoogleCalendar()->getCalendarId()->getValue()) {
            $this->authorizeProvider($provider);

            switch ($commandSlug) {
                case AppointmentAddedEventHandler::APPOINTMENT_ADDED:
                case BookingAddedEventHandler::BOOKING_ADDED:
                    // Add new appointment or update existing one
                    if (!$appointment->getGoogleCalendarEventId()) {
                        $this->insertEvent($appointment, $provider);
                    } else {
                        $this->updateEvent($appointment, $provider);
                    }

                    // When status is pending we must first insert the event to get event ID
                    // because if we update the status later to 'Approved' we must have ID of the event
                    if ($appointmentStatus === 'pending' && $this->settings['insertPendingAppointments'] === false) {
                        $this->deleteEvent($appointment, $provider);
                    }
                    break;
                case AppointmentEditedEventHandler::APPOINTMENT_EDITED:
                case AppointmentTimeUpdatedEventHandler::TIME_UPDATED:
                case AppointmentStatusUpdatedEventHandler::APPOINTMENT_STATUS_UPDATED:
                case BookingCanceledEventHandler::BOOKING_CANCELED:
                    if ($appointmentStatus === 'canceled' || $appointmentStatus === 'rejected' ||
                        ($appointmentStatus === 'pending' && $this->settings['insertPendingAppointments'] === false)
                    ) {
                        $this->deleteEvent($appointment, $provider);
                        break;
                    }

                    $this->updateEvent($appointment, $provider);
                    break;
                case AppointmentDeletedEventHandler::APPOINTMENT_DELETED:
                    $this->deleteEvent($appointment, $provider);
                    break;
            }
        }
    }

    /**
     * Create fake appointments in provider's list so that these slots will not be available for booking
     *
     * @param Collection $providers
     *
     * @param int        $excludeAppointmentId
     *
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws Exception
     * @throws ContainerException
     */
    public function removeSlotsFromGoogleCalendar($providers, $excludeAppointmentId = null)
    {
        if ($this->settings['removeGoogleCalendarBusySlots'] === true) {
            foreach ($providers->keys() as $providerKey) {
                /** @var Provider $provider */
                $provider = $providers->getItem($providerKey);

                if ($provider->getGoogleCalendar()) {
                    if (!array_key_exists($provider->getId()->getValue(), self::$providersGoogleEvents)) {
                        $this->authorizeProvider($provider);

                        $this->timeZone = $this->service->calendars->get('primary')->getTimeZone();

                        $events = $this->service->events->listEvents(
                            $provider->getGoogleCalendar()->getCalendarId()->getValue(),
                            [
                                'maxResults'   => $this->settings['maximumNumberOfEventsReturned'],
                                'orderBy'      => 'startTime',
                                'singleEvents' => true,
                                'timeMin'      => DateTimeService::getCustomDateTimeRFC3339(
                                    DateTimeService::getNowDate()
                                )
                            ]
                        );

                        self::$providersGoogleEvents[$provider->getId()->getValue()] = $events;
                    } else {
                        $events = self::$providersGoogleEvents[$provider->getId()->getValue()];
                    }

                    /** @var AmeliaGoogle_Service_Calendar_Event $event */
                    foreach ($events->getItems() as $event) {
                        // Continue if event is set to "Free"
                        if ($event->getTransparency() === 'transparent') {
                            continue;
                        }

                        $extendedProperties = $event->getExtendedProperties();
                        if ($extendedProperties !== null) {
                            $private = $extendedProperties->private;
                            if (is_array($private) &&
                                array_key_exists('ameliaEvent', $private) &&
                                $excludeAppointmentId !== null &&
                                array_key_exists('ameliaAppointmentId', $private) &&
                                (int)$private['ameliaAppointmentId'] === (int)$excludeAppointmentId
                            ) {
                                continue;
                            }
                        }

                        $eventStart = $event->getStart();

                        $eventEnd = $event->getEnd();

                        if ($eventStart->dateTime === null) {
                            $timesToRemove = $this->removeDateBasedEvents($eventStart, $eventEnd);
                        } else {
                            $timesToRemove = $this->removeTimeBasedEvents($eventStart, $eventEnd);
                        }

                        foreach ($timesToRemove as $timeToRemove) {
                            $eventStartParts = explode(' ', $timeToRemove['eventStartDateTime']);

                            $eventEndParts = explode(' ', $timeToRemove['eventEndDateTime']);

                            if ($eventEndParts[1] !== '00:00:00' && $eventStartParts[0] !== $eventEndParts[0]) {
                                $firstAppointmentPart = AppointmentFactory::create(
                                    [
                                        'bookingStart'       => $timeToRemove['eventStartDateTime'],
                                        'bookingEnd'         => $eventEndParts[0] . ' 00:00:00',
                                        'notifyParticipants' => false,
                                        'serviceId'          => 0,
                                        'providerId'         => $provider->getId()->getValue(),
                                    ]
                                );

                                $provider->getAppointmentList()->addItem($firstAppointmentPart);

                                $secondAppointmentPart = AppointmentFactory::create(
                                    [
                                        'bookingStart'       => $eventEndParts[0] . ' 00:00:00',
                                        'bookingEnd'         => $timeToRemove['eventEndDateTime'],
                                        'notifyParticipants' => false,
                                        'serviceId'          => 0,
                                        'providerId'         => $provider->getId()->getValue(),
                                    ]
                                );

                                $provider->getAppointmentList()->addItem($secondAppointmentPart);
                            } else {
                                $appointment = AppointmentFactory::create(
                                    [
                                        'bookingStart'       => $timeToRemove['eventStartDateTime'],
                                        'bookingEnd'         => $timeToRemove['eventEndDateTime'],
                                        'notifyParticipants' => false,
                                        'serviceId'          => 0,
                                        'providerId'         => $provider->getId()->getValue(),
                                    ]
                                );

                                $provider->getAppointmentList()->addItem($appointment);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Insert an Event in Google Calendar.
     *
     * @param Appointment $appointment
     * @param Provider    $provider
     *
     * @throws NotFoundException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    private function insertEvent($appointment, $provider)
    {
        /** @var AppointmentRepository $appointmentRepository */
        $appointmentRepository = $this->container->get('domain.booking.appointment.repository');

        $event = $this->createEvent($appointment, $provider);

        $queryParams = ['sendNotifications' => $this->settings['sendEventInvitationEmail']];

        if ($this->settings['enableGoogleMeet']) {
            $queryParams['conferenceDataVersion'] = 1;
        }

        $event = $this->service->events->insert(
            $provider->getGoogleCalendar()->getCalendarId()->getValue(),
            $event,
            $queryParams
        );

        $appointment->setGoogleCalendarEventId(new Token($event->getId()));
        $appointment->setGoogleMeetUrl($event->getHangoutLink());

        $appointmentRepository->update($appointment->getId()->getValue(), $appointment);
    }

    /**
     * Update an Event in Google Calendar.
     *
     * @param Appointment $appointment
     * @param Provider    $provider
     *
     * @throws NotFoundException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    private function updateEvent($appointment, $provider)
    {
        $event = $this->createEvent($appointment, $provider);

        if ($appointment->getGoogleCalendarEventId()) {
            $this->service->events->update(
                $provider->getGoogleCalendar()->getCalendarId()->getValue(),
                $appointment->getGoogleCalendarEventId()->getValue(),
                $event,
                ['sendNotifications' => $this->settings['sendEventInvitationEmail']]
            );
        }
    }

    /**
     * Delete an Event from Google Calendar.
     *
     * @param Appointment $appointment
     * @param Provider    $provider
     */
    private function deleteEvent($appointment, $provider)
    {
        if ($appointment->getGoogleCalendarEventId()) {
            $this->service->events->delete(
                $provider->getGoogleCalendar()->getCalendarId()->getValue(),
                $appointment->getGoogleCalendarEventId()->getValue()
            );
        }
    }

    /**
     * Create and return Google Calendar Event Object filled with appointments data.
     *
     * @param Appointment $appointment
     * @param Provider    $provider
     *
     * @return AmeliaGoogle_Service_Calendar_Event
     *
     * @throws NotFoundException
     * @throws QueryExecutionException
     * @throws ContainerException
     * @throws Exception
     */
    private function createEvent($appointment, $provider)
    {
        /** @var LocationRepository $locationRepository */
        $locationRepository = $this->container->get('domain.locations.repository');

        /** @var PlaceholderService $placeholderService */
        $placeholderService = $this->container->get('application.placeholder.appointment.service');

        $appointmentLocationId = $appointment->getLocationId() ? $appointment->getLocationId()->getValue() : null;

        $providerLocationId = $provider->getLocationId() ? $provider->getLocationId()->getValue() : null;

        $locationId = $appointmentLocationId ?: $providerLocationId;

        $location = $locationId ? $locationRepository->getById($locationId) : null;

        $attendees = $this->getAttendees($appointment);

        $placeholderData = $placeholderService->getPlaceholdersData($appointment->toArray());

        $start = clone $appointment->getBookingStart()->getValue();

        $end = clone $appointment->getBookingEnd()->getValue();

        if ($this->settings['includeBufferTimeGoogleCalendar'] === true) {
            $timeBefore = $appointment->getService()->getTimeBefore() ?
                $appointment->getService()->getTimeBefore()->getValue() : 0;

            $timeAfter = $appointment->getService()->getTimeAfter() ?
                $appointment->getService()->getTimeAfter()->getValue() : 0;

            $start->modify('-' . $timeBefore . ' second');
            $end->modify('+' . $timeAfter . ' second');
        }

        $eventData = [
            'start'                   => [
                'dateTime' => DateTimeService::getCustomDateTimeRFC3339($start->format('Y-m-d H:i:s')),
            ],
            'end'                     => [
                'dateTime' => DateTimeService::getCustomDateTimeRFC3339($end->format('Y-m-d H:i:s')),
            ],
            'guestsCanSeeOtherGuests' => $this->settings['showAttendees'],
            'attendees'               => $attendees,
            'description'             => $placeholderService->applyPlaceholders(
                $this->settings['eventDescription'],
                $placeholderData
            ),
            'extendedProperties'      => [
                'private' => [
                    'ameliaEvent'         => true,
                    'ameliaAppointmentId' => $appointment->getId()->getValue()
                ]
            ],
            'location'                => $location ? $location->getAddress()->getValue() : null,
            'locked'                  => true,
            'status'                  => $this->settings['status'],
            'summary'                 => $placeholderService->applyPlaceholders(
                $this->settings['eventTitle'],
                $placeholderData
            )
        ];

        if ($this->settings['enableGoogleMeet']) {
            $token = new Token();

            $eventData['conferenceData'] = [
                'createRequest' => [
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet',
                    ],
                    'requestId' => $appointment->getId()->getValue() . '_' . $token->getValue(),
                ]
            ];
        }

        return new AmeliaGoogle_Service_Calendar_Event($eventData);
    }

    /**
     * Get All Attendees that need to be added in Google Calendar Event based on "addAttendees" Settings.
     *
     * @param Appointment $appointment
     *
     * @return array
     *
     * @throws NotFoundException
     * @throws QueryExecutionException
     */
    private function getAttendees($appointment)
    {
        $attendees = [];

        if ($this->settings['addAttendees'] === true) {
            /** @var ProviderRepository $providerRepository */
            $providerRepository = $this->container->get('domain.users.providers.repository');

            $provider = $providerRepository->getById($appointment->getProviderId()->getValue());

            $attendees[] = [
                'displayName'    => $provider->getFirstName()->getValue() . ' ' . $provider->getLastName()->getValue(),
                'email'          => $provider->getGoogleCalendar()->getCalendarId()->getValue(),
                'responseStatus' => 'accepted'
            ];

            /** @var CustomerRepository $customerRepository */
            $customerRepository = $this->container->get('domain.users.customers.repository');

            $bookings = $appointment->getBookings()->getItems();

            /** @var CustomerBooking $booking */
            foreach ($bookings as $booking) {
                $bookingStatus = $booking->getStatus()->getValue();

                if ($bookingStatus === 'approved' ||
                    ($bookingStatus === 'pending' && $this->settings['insertPendingAppointments'] === true)
                ) {
                    $customer = $customerRepository->getById($booking->getCustomerId()->getValue());

                    if ($customer->getEmail()->getValue()) {
                        $attendees[] = [
                            'displayName'    =>
                                $customer->getFirstName()->getValue() . ' ' . $customer->getLastName()->getValue(),
                            'email'          => $customer->getEmail()->getValue(),
                            'responseStatus' => 'needsAction'
                        ];
                    }
                }
            }
        }

        return $attendees;
    }

    /**
     * Authorize Provider and create Google Calendar service
     *
     * @param Provider $provider
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    private function authorizeProvider($provider)
    {
        $this->client = new \AmeliaGoogle_Client();
        $this->client->setClientId($this->settings['clientID']);
        $this->client->setClientSecret($this->settings['clientSecret']);

        $this->client->setAccessToken($provider->getGoogleCalendar()->getToken()->getValue());

        if ($this->client->isAccessTokenExpired()) {
            $this->refreshToken($provider);
        }

        $this->service = new \AmeliaGoogle_Service_Calendar($this->client);

        return true;
    }

    /**
     * Refresh Provider's Token if it is expired and update it in database.
     *
     * @param Provider $provider
     *
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    private function refreshToken($provider)
    {
        /** @var ProviderApplicationService $providerApplicationService */
        $providerApplicationService = $this->container->get('application.user.provider.service');

        $this->client->refreshToken($this->client->getRefreshToken());

        $provider->setGoogleCalendar(
            GoogleCalendarFactory::create(
                [
                    'id'         => $provider->getGoogleCalendar()->getId()->getValue(),
                    'token'      => $this->client->getAccessToken(),
                    'calendarId' => $provider->getGoogleCalendar()->getCalendarId()->getValue()
                ]
            )
        );

        $providerApplicationService->updateProviderGoogleCalendar($provider);
    }

    /**
     * @param $eventEnd
     * @param $eventStart
     *
     * @return array
     *
     * @throws Exception
     */
    private function removeDateBasedEvents($eventStart, $eventEnd)
    {
        $timesToRemove = [];

        $eventStartString = $this->getEventDateTimeStringFromEventDate($eventStart->date);

        $eventEndString = $this->getEventDateTimeStringFromEventDate($eventEnd->date);

        $daysBetweenStartAndEnd = (int)DateTimeService::getCustomDateTimeObject($eventStartString)
            ->diff(DateTimeService::getCustomDateTimeObject($eventEndString))->format('%a');

        $eventStart = DateTimeService::getCustomDateTimeObject($eventStartString);

        for ($i = 0; $i < $daysBetweenStartAndEnd; $i++) {
            $timesToRemove[] = [
                'eventStartDateTime' => $eventStart->format('Y-m-d H:i:s'),
                'eventEndDateTime'   => $eventStart->modify('+1 days')->format('Y-m-d H:i:s'),
            ];
        }

        return $timesToRemove;
    }

    /**
     * @param string $eventDateTimeString
     *
     * @return string
     *
     * @throws Exception
     */
    private function getEventDateTimeStringFromEventDateTime($eventDateTimeString)
    {
        $googleEventDateTimeString = \DateTime::createFromFormat("Y-m-d\TH:i:sP", $eventDateTimeString);

        return DateTimeService::getCustomDateTimeFromUtc(
            $googleEventDateTimeString->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s')
        );
    }

    /**
     * @param string $eventDateString
     *
     * @return string
     *
     * @throws Exception
     */
    private function getEventDateTimeStringFromEventDate($eventDateString)
    {
        $eventDateTimeInUtc = (new \DateTime($eventDateString . ' 00:00:00', new \DateTimeZone($this->timeZone)))
            ->setTimezone(new \DateTimeZone('UTC'));

        return DateTimeService::getCustomDateTimeFromUtc($eventDateTimeInUtc->format('Y-m-d H:i:s'));
    }

    /**
     * @param $eventStart
     * @param $eventEnd
     *
     * @return array
     *
     * @throws Exception
     */
    private function removeTimeBasedEvents($eventStart, $eventEnd)
    {
        $timesToRemove = [];

        $eventStartString = $this->getEventDateTimeStringFromEventDateTime($eventStart->dateTime);

        $eventEndString = $this->getEventDateTimeStringFromEventDateTime($eventEnd->dateTime);

        $daysBetweenStartAndEnd = (int)DateTimeService::getCustomDateTimeObject($eventEndString)
            ->diff(DateTimeService::getCustomDateTimeObject($eventStartString))->format('%a');

        // If event is in the same day, or not
        if ($daysBetweenStartAndEnd === 0) {
            $timesToRemove[] = [
                'eventStartDateTime' => DateTimeService::getCustomDateTime($eventStartString),
                'eventEndDateTime'   => DateTimeService::getCustomDateTime($eventEndString)
            ];
        } else {
            for ($i = 0; $i <= $daysBetweenStartAndEnd; $i++) {
                $startDateTime = DateTimeService::getCustomDateTimeObject(
                    $eventStartString
                )->modify('+' . $i . ' days');

                $timesToRemove[] = [
                    'eventStartDateTime' => $i === 0 ?
                        $startDateTime->format('Y-m-d H:i:s') :
                        $startDateTime->format('Y-m-d') . ' 00:00:01',
                    'eventEndDateTime'   => $i === $daysBetweenStartAndEnd ?
                        DateTimeService::getCustomDateTime($eventEndString) :
                        $startDateTime->format('Y-m-d') . ' 23:59:59'
                ];
            }
        }

        return $timesToRemove;
    }
}
