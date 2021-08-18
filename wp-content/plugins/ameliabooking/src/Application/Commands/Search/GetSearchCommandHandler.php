<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Application\Commands\Search;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Services\Bookable\BookableApplicationService;
use AmeliaBooking\Application\Services\Booking\AppointmentApplicationService;
use AmeliaBooking\Application\Services\Booking\EventApplicationService;
use AmeliaBooking\Application\Services\User\ProviderApplicationService;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Entity\Bookable\Service\Service;
use AmeliaBooking\Domain\Entity\Booking\Appointment\Appointment;
use AmeliaBooking\Domain\Entity\User\Provider;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\Services\TimeSlot\TimeSlotService;
use AmeliaBooking\Domain\ValueObjects\String\Status;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\ServiceRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\AppointmentRepository;
use AmeliaBooking\Infrastructure\Repository\Location\LocationRepository;
use AmeliaBooking\Infrastructure\Repository\User\ProviderRepository;
use AmeliaBooking\Infrastructure\Services\Google\GoogleCalendarService;
use AmeliaBooking\Infrastructure\Services\Outlook\OutlookCalendarService;

/**
 * Class GetSearchCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\Search
 */
class GetSearchCommandHandler extends CommandHandler
{
    /**
     * @param GetSearchCommand $command
     *
     * @return CommandResult
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Exception
     */
    public function handle(GetSearchCommand $command)
    {
        $result = new CommandResult();

        $resultData = [];

        $params = $command->getField('params');

        /** @var ProviderRepository $providerRepository */
        $providerRepository = $this->container->get('domain.users.providers.repository');
        /** @var AppointmentRepository $appointmentRepo */
        $appointmentRepo = $this->container->get('domain.booking.appointment.repository');
        /** @var ServiceRepository $serviceRepository */
        $serviceRepository = $this->container->get('domain.bookable.service.repository');

        /** @var \AmeliaBooking\Application\Services\TimeSlot\TimeSlotService $applicationTimeSlotService */
        $applicationTimeSlotService = $this->container->get('application.timeSlot.service');
        /** @var BookableApplicationService $bookableService */
        $bookableService = $this->container->get('application.bookable.service');
        /** @var \AmeliaBooking\Domain\Services\Settings\SettingsService $settingsDS */
        $settingsDS = $this->container->get('domain.settings.service');
        /** @var \AmeliaBooking\Application\Services\Settings\SettingsService $settingsAS */
        $settingsAS = $this->container->get('application.settings.service');
        /** @var TimeSlotService $timeSlotService */
        $timeSlotService = $this->container->get('domain.timeSlot.service');
        /** @var AppointmentApplicationService $appointmentAS */
        $appointmentAS = $this->container->get('application.booking.appointment.service');
        /** @var ProviderApplicationService $providerAS */
        $providerAS = $this->container->get('application.user.provider.service');

        if (isset($params['startOffset'], $params['endOffset'])
            && $settingsDS->getSetting('general', 'showClientTimeZone')) {
            $searchStartDateTimeString = DateTimeService::getCustomDateTimeFromUtc(
                DateTimeService::getClientUtcCustomDateTime(
                    $params['date'] . ' ' . (isset($params['timeFrom']) ? $params['timeFrom'] : '00:00:00'),
                    -$params['startOffset']
                )
            );

            $searchEndDateTimeString = DateTimeService::getCustomDateTimeFromUtc(
                DateTimeService::getClientUtcCustomDateTime(
                    $params['date'] . ' ' . (isset($params['timeTo']) ? $params['timeTo'] : '23:59:00'),
                    -$params['endOffset']
                )
            );

            $searchStartDateString = explode(' ', $searchStartDateTimeString)[0];
            $searchEndDateString = explode(' ', $searchEndDateTimeString)[0];

            $searchTimeFrom = explode(' ', $searchStartDateTimeString)[1];
            $searchTimeTo = explode(' ', $searchEndDateTimeString)[1];
        } else {
            $searchStartDateString = $params['date'];
            $searchEndDateString = $params['date'];
            $searchTimeFrom = isset($params['timeFrom']) ? $params['timeFrom'] : null;
            $searchTimeTo = isset($params['timeTo']) ? $params['timeTo'] : null;
        }

        /** @var Collection $appointments */
        $appointments = new Collection();

        $startDateTimeObject = DateTimeService::getCustomDateTimeObject($searchStartDateString . ' 00:00:00');

        $endDateTimeObject = DateTimeService::getCustomDateTimeObject($searchEndDateString . ' 23:59:00');

        $appointmentRepo->getFutureAppointments(
            $appointments,
            !empty($params['providers']) ? $params['providers'] : [],
            $startDateTimeObject->modify('-1 days')->format('Y-m-d H:i:s'),
            $endDateTimeObject->modify('+1 days')->format('Y-m-d H:i:s')
        );

        /** @var LocationRepository $locationRepository */
        $locationRepository = $this->getContainer()->get('domain.locations.repository');

        /** @var Collection $locationsList */
        $locationsList = $locationRepository->getAllOrderedByName();

        /** @var Collection $providers */
        $providers = $providerRepository->getByCriteria(
            array_merge(
                $params,
                [
                    'serviceStatus'  => Status::VISIBLE,
                    'providerStatus' => Status::VISIBLE
                ]
            )
        );

        // Find services for providers and add providers to services
        $servicesCriteria = $this->buildServicesSearchCriteria($providers, $params);

        /** @var Collection $services */
        $services = $serviceRepository->getByCriteria($servicesCriteria);

        // Get time slot setting
        $timeSlotLength = $settingsDS->getSetting('general', 'timeSlotLength');

        // Get global days off
        $globalDaysOff = $settingsAS->getGlobalDaysOff();

        $bookIfPending = $settingsDS->getSetting('appointments', 'allowBookingIfPending');

        $bookIfNotMin = $settingsDS->getSetting('appointments', 'allowBookingIfNotMin');

        $missingServicesIds = [];

        /** @var Appointment $appointment */
        foreach ($appointments->getItems() as $index => $appointment) {
            if (!$services->keyExists($appointment->getServiceId()->getValue())) {
                $missingServicesIds[$appointment->getServiceId()->getValue()] = true;
            }
        }

        /** @var Collection $missingServices */
        $missingServices = $missingServicesIds ?
            $serviceRepository->getByCriteria(['services' => array_keys($missingServicesIds)]) : new Collection();

        /** @var Appointment $appointment */
        foreach ($appointments->getItems() as $index => $appointment) {
            /** @var Service $providerService */
            $providerService = null;

            /** @var Provider $provider */
            $provider = $providers->keyExists($appointment->getProviderId()->getValue()) ?
                $providers->getItem($appointment->getProviderId()->getValue()) : null;

            if ($provider &&
                $provider->getServiceList()->keyExists($appointment->getServiceId()->getValue())) {
                $providers->getItem($appointment->getProviderId()->getValue());

                $providerService = $provider->getServiceList()->getItem($appointment->getServiceId()->getValue());
            } else if ($services->keyExists($appointment->getServiceId()->getValue())) {
                $providerService = $services->getItem($appointment->getServiceId()->getValue());
            } else if ($missingServices->keyExists($appointment->getServiceId()->getValue())) {
                $providerService = $missingServices->getItem($appointment->getServiceId()->getValue());
            }

            $bookableService->checkServiceTimes($providerService);

            $appointment->setService($providerService);
        }

        // Add future appointments to provider's appointment list
        $providerAS->addAppointmentsToAppointmentList($providers, $appointments);

        /** @var Service $service */
        foreach ($services->getItems() as $service) {
            if (!$service->getShow()->getValue()) {
                continue;
            }

            $bookableService->checkServiceTimes($service);

            $minimumBookingTimeInSeconds = $settingsDS
                ->getEntitySettings($service->getSettings())
                ->getGeneralSettings()
                ->getMinimumTimeRequirementPriorToBooking();

            // get start DateTime based on minimum time prior to booking
            $offset = DateTimeService::getNowDateTimeObject()
                ->modify("+{$minimumBookingTimeInSeconds} seconds");

            $startDateTime = DateTimeService::getCustomDateTimeObject($searchStartDateString);
            $startDateTime = $offset > $startDateTime ? $offset : $startDateTime;

            $endDateTime = DateTimeService::getCustomDateTimeObject($searchEndDateString);

            $maximumBookingTimeInDays = $settingsDS
                ->getEntitySettings($service->getSettings())
                ->getGeneralSettings()
                ->getNumberOfDaysAvailableForBooking();

            $maxEndDateTime = $applicationTimeSlotService->getMaximumDateTimeForBooking(
                $endDateTime->format('Y-m-d H:i:s'),
                true,
                $maximumBookingTimeInDays
            );

            if ($maxEndDateTime < $endDateTime) {
                continue;
            }

            /** @var Collection $providersList */
            $providersList = $bookableService->getServiceProviders($service, $providers);

            /** @var GoogleCalendarService $googleCalendarService */
            $googleCalendarService = $this->container->get('infrastructure.google.calendar.service');

            try {
                // Remove Google Calendar Busy Slots
                $googleCalendarService->removeSlotsFromGoogleCalendar($providersList, null);
            } catch (\Exception $e) {
            }

            /** @var OutlookCalendarService $outlookCalendarService */
            $outlookCalendarService = $this->container->get('infrastructure.outlook.calendar.service');

            try {
                // Remove Outlook Calendar Busy Slots
                $outlookCalendarService->removeSlotsFromOutlookCalendar($providers, null);
            } catch (\Exception $e) {
            }

            /** @var EventApplicationService $eventApplicationService */
            $eventApplicationService = $this->container->get('application.booking.event.service');

            $eventApplicationService->removeSlotsFromEvents($providers, [
                DateTimeService::getCustomDateTimeObject($startDateTime->format('Y-m-d H:i:s'))
                    ->modify('-10 day')
                    ->format('Y-m-d H:i:s'),
                DateTimeService::getCustomDateTimeObject($startDateTime->format('Y-m-d H:i:s'))
                    ->modify('+2 years')
                    ->format('Y-m-d H:i:s')
            ]);

            $freeIntervals = $timeSlotService->getFreeTime(
                $service,
                !empty($params['location']) ? (int)$params['location'] : null,
                $locationsList,
                $providersList,
                $globalDaysOff,
                $startDateTime,
                $endDateTime->modify('+1 day'),
                1,
                $bookIfPending,
                $bookIfNotMin,
                false
            );

            $requiredTime = $appointmentAS->getAppointmentRequiredTime($service);

            $freeSlots = $timeSlotService->getAppointmentFreeSlots(
                $service,
                $requiredTime,
                $freeIntervals,
                $timeSlotLength,
                $startDateTime,
                $settingsDS->getSetting('general', 'serviceDurationAsSlot'),
                $settingsDS->getSetting('general', 'bufferTimeInSlot'),
                true
            );

            if ($searchTimeFrom && array_key_exists($searchStartDateString, $freeSlots)) {
                $freeSlots = $this->filterByTimeFrom($searchStartDateString, $searchTimeFrom, $freeSlots);
            }

            if ($searchTimeTo && array_key_exists($searchEndDateString, $freeSlots)) {
                $freeSlots = $this->filterByTimeTo($searchEndDateString, $searchTimeTo, $freeSlots);
            }

            if (!array_key_exists($searchStartDateString, $freeSlots) &&
                !array_key_exists($searchEndDateString, $freeSlots)
            ) {
                continue;
            }

            $providersIds = [];

            foreach ($freeSlots as $dateSlot) {
                foreach ($dateSlot as $timeSlot) {
                    foreach ($timeSlot as $infoSlot) {
                        $providersIds[$infoSlot[0]][] = [
                            $infoSlot[1],
                            isset($infoSlot[2]) ? $infoSlot[2] : null
                        ];
                    }
                }
            }

            foreach ($providersIds as $providersId => $providersData) {
                $resultData[] = [
                    $service->getId()->getValue() => $providersId,
                    'places'                      => (min(array_filter(array_column($providersData, 1)) ?: [0]) ?: 0) ?: null,
                    'locations'                   => array_values(array_unique(array_column($providersData, 0))),
                    'price'                       => $providersList
                        ->getItem($providersId)
                        ->getServiceList()
                        ->getItem($service->getId()->getValue())
                        ->getPrice()
                        ->getValue()
                ];
            }
        }

        // Sort results by price
        if (strpos($params['sort'], 'price') !== false) {
            usort($resultData, function ($service1, $service2) {
                return $service1['price'] > $service2['price'];
            });

            if ($params['sort'] === '-price') {
                $resultData = array_reverse($resultData);
            }
        }

        // Pagination
        $resultDataPaginated = $this->paginateData($resultData, $params['page']);

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully retrieved searched services.');
        $result->setData([
            'providersServices' => $resultDataPaginated,
            'total'             => count($resultData)
        ]);

        return $result;
    }

    /**
     * @param $date
     * @param $time
     * @param $freeSlots
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function filterByTimeFrom($date, $time, $freeSlots)
    {
        foreach (array_keys($freeSlots[$date]) as $freeSlotKey) {
            if (DateTimeService::getCustomDateTimeObject($date . ' ' . $freeSlotKey) >=
                DateTimeService::getCustomDateTimeObject($date . ' ' . $time)) {
                break;
            }

            unset($freeSlots[$date][$freeSlotKey]);

            if (empty($freeSlots[$date])) {
                unset($freeSlots[$date]);
            }
        }

        return $freeSlots;
    }

    /**
     * @param $date
     * @param $time
     * @param $freeSlots
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function filterByTimeTo($date, $time, $freeSlots)
    {
        foreach (array_reverse(array_keys($freeSlots[$date])) as $freeSlotKey) {
            if (DateTimeService::getCustomDateTimeObject($date . ' ' . $freeSlotKey) <=
                DateTimeService::getCustomDateTimeObject($date . ' ' . $time)) {
                break;
            }

            unset($freeSlots[$date][$freeSlotKey]);

            if (empty($freeSlots[$date])) {
                unset($freeSlots[$date]);
            }
        }

        return $freeSlots;
    }

    /**
     * @param Collection $providers
     * @param array      $params
     *
     * @return array
     */
    private function buildServicesSearchCriteria($providers, $params)
    {
        return [
            'providers' => array_column($providers->toArray(), 'id'),
            'search'    => !empty($params['search']) ? $params['search'] : null,
            'services'  => $params['services'],
            'sort'      => $params['sort'],
            'status'    => 'visible',
        ];
    }

    /**
     * @param array $data
     * @param int   $page
     *
     * @return array
     * @throws \Interop\Container\Exception\ContainerException
     */
    private function paginateData($data, $page)
    {
        /** @var \AmeliaBooking\Domain\Services\Settings\SettingsService $settingsDS */
        $settingsDS = $this->container->get('domain.settings.service');

        $itemsPerPage = $settingsDS->getSetting('general', 'itemsPerPage');
        $offset = ($page - 1) * $itemsPerPage;

        return array_slice($data, $offset, $itemsPerPage);
    }
}
