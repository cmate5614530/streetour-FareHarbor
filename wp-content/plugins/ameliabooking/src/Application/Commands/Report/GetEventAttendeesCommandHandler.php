<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Application\Commands\Report;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\Services\Report\ReportServiceInterface;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\Repository\Booking\Event\EventRepository;
use AmeliaBooking\Infrastructure\Repository\CustomField\CustomFieldRepository;
use AmeliaBooking\Infrastructure\WP\Translations\BackendStrings;

/**
 * Class GetCustomersCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\Report
 */
class GetEventAttendeesCommandHandler extends CommandHandler
{
    /**
     * @param GetEventAttendeesCommand $command
     *
     * @return CommandResult
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws AccessDeniedException
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function handle(GetEventAttendeesCommand $command)
    {
        if (!$this->getContainer()->getPermissionsService()->currentUserCanRead(Entities::APPOINTMENTS)) {
            throw new AccessDeniedException('You are not allowed to read appointments.');
        }

        $result = new CommandResult();

        $this->checkMandatoryFields($command);

        $params = $command->getField('params');

        /** @var CustomFieldRepository $customFieldRepository */
        $customFieldRepository = $this->container->get('domain.customField.repository');

        /** @var Collection $customFields */
        $customFieldsList = $customFieldRepository->getAll();

        /** @var EventRepository $eventRepository */
        $eventRepository = $this->container->get('domain.booking.event.repository');

        /** @var Event $event */
        $event = $eventRepository->getById((int)$params['id']);

        /** @var ReportServiceInterface $reportService */
        $reportService = $this->container->get('infrastructure.report.csv.service');

        /** @var SettingsService $settingsDomainService */
        $settingsDomainService = $this->container->get('domain.settings.service');

        $rows = [];

        $fields = $command->getField('params')['fields'];

        $delimiter = $command->getField('params')['delimiter'];

        $dateFormat = $settingsDomainService->getSetting('wordpress', 'dateFormat');

        /** @var CustomerBooking $booking */
        foreach ($event->getBookings()->getItems() as $booking) {
            /** @var AbstractUser $customer */
            $customer = $booking->getCustomer();

            $row = [];

            $customFields = [];

            $customFieldsValues = [];

            $customFieldsJson = $booking->getCustomFields() ?
                json_decode($booking->getCustomFields()->getValue(), true) : [];

            foreach ((array)$customFieldsJson as $customFieldId => $customFiled) {
                /** @var Collection $customFieldEvents */
                $customFieldEvents = $customFieldsList->keyExists($customFieldId) && $customFieldsList->getItem($customFieldId)->getEvents() ? $customFieldsList->getItem($customFieldId)->getEvents(): new Collection();

                $eventHasCustomField = false;

                /** @var Event $customFieldEvent */
                foreach ($customFieldEvents->getItems() as $customFieldEvent) {
                    if ($customFieldEvent->getId()->getValue() === (int)$params['id']) {
                        $eventHasCustomField = true;
                        break;
                    }
                }

                if ((array_key_exists('type', $customFiled) && $customFiled['type'] === 'file') ||
                    !$eventHasCustomField
                ) {
                    continue;
                }

                if (is_array($customFiled['value'])) {
                    foreach ($customFiled['value'] as $customFiledValue) {
                        $customFieldsValues[] =  $customFiledValue;
                    }
                    $customFields[] = $customFiled['label'] . ': ' . implode('|', $customFieldsValues);
                } else {
                    $customFields[] = $customFiled['label'] . ': ' . $customFiled['value'];
                }
            }

            $infoJson = $booking->getInfo() ? json_decode($booking->getInfo()->getValue(), true) : null;

            $customerInfo = $infoJson ?: $customer->toArray();

            if (in_array('firstName', $fields, true)) {
                $row[BackendStrings::getUserStrings()['first_name']] = $customerInfo['firstName'];
            }

            if (in_array('lastName', $fields, true)) {
                $row[BackendStrings::getUserStrings()['last_name']] = $customerInfo['lastName'];
            }

            if (in_array('email', $fields, true)) {
                $row[BackendStrings::getUserStrings()['email']] =
                    $customer->getEmail() ? $customer->getEmail()->getValue() : '';
            }

            $phone = $customer->getPhone() ? $customer->getPhone()->getValue() : '';

            if (in_array('phone', $fields, true)) {
                $row[BackendStrings::getCommonStrings()['phone']] =
                    $customerInfo['phone'] ? $customerInfo['phone'] : $phone;
            }

            if (in_array('gender', $fields, true)) {
                $row[BackendStrings::getCustomerStrings()['gender']] =
                    $customer->getGender() ? $customer->getGender()->getValue() : '';
            }

            if (in_array('birthday', $fields, true)) {
                $row[BackendStrings::getCustomerStrings()['date_of_birth']] =
                    $customer->getBirthday() ?
                        DateTimeService::getCustomDateTimeObject($customer->getBirthday()->getValue()->format('Y-m-d'))
                        ->format($dateFormat) : '';
            }

            if (in_array('note', $fields, true)) {
                $row[BackendStrings::getCustomerStrings()['customer_note']] =
                    $customer->getNote() ? $customer->getNote()->getValue() : '';
            }

            if (in_array('persons', $fields, true)) {
                $row[BackendStrings::getEventStrings()['event_book_persons']] = $booking->getPersons()->getValue();
            }

            if (in_array('customFields', $params['fields'], true)) {
                $row[BackendStrings::getSettingsStrings()['custom_fields']] = implode(', ', $customFields);
            }

            $rows[] = $row;
        }

        $reportService->generateReport($rows, Entities::EVENT, $delimiter);

        $result->setAttachment(true);

        return $result;
    }
}
