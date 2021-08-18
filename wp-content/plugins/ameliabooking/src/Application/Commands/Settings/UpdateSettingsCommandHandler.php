<?php

namespace AmeliaBooking\Application\Commands\Settings;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Application\Services\Location\CurrentLocation;
use AmeliaBooking\Application\Services\Stash\StashApplicationService;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\Services\Frontend\LessParserService;
use AmeliaBooking\Infrastructure\WP\Integrations\WooCommerce\WooCommerceService;
use Exception;
use Interop\Container\Exception\ContainerException;
use Less_Exception_Parser;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class UpdateSettingsCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\Settings
 */
class UpdateSettingsCommandHandler extends CommandHandler
{
    /**
     * @param UpdateSettingsCommand $command
     *
     * @return CommandResult
     * @throws AccessDeniedException
     * @throws Less_Exception_Parser
     * @throws ContainerValueNotFoundException
     * @throws ContainerException
     * @throws Exception
     */
    public function handle(UpdateSettingsCommand $command)
    {
        $result = new CommandResult();

        if (!$this->getContainer()->getPermissionsService()->currentUserCanWrite(Entities::SETTINGS)) {
            throw new AccessDeniedException('You are not allowed to write settings.');
        }

        /** @var SettingsService $settingsService */
        $settingsService = $this->getContainer()->get('domain.settings.service');

        /** @var CurrentLocation $locationService */
        $locationService = $this->getContainer()->get('application.currentLocation.service');

        /** @var LessParserService $lessParserService */
        $lessParserService = $this->getContainer()->get('infrastructure.frontend.lessParser.service');

        $settingsFields = $command->getFields();

        if ($command->getField('customization')) {
            $customizationData = $command->getField('customization');

            $hash = $lessParserService->compileAndSave(
                [
                    'color-accent'                => $customizationData['primaryColor'],
                    'color-gradient1'             => $customizationData['primaryGradient1'],
                    'color-gradient2'             => $customizationData['primaryGradient2'],
                    'color-text-prime'            => $customizationData['textColor'],
                    'color-text-second'           => $customizationData['textColor'],
                    'color-white'                 => $customizationData['textColorOnBackground'],
                    'font'                        => $customizationData['font'],
                    // step by step
                    'sbs-ssf-bgr-color'           => $customizationData['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formBackgroundColor'],
                    'sbs-ssf-text-color'          => $customizationData['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formTextColor'],
                    'sbs-ssf-input-color'         => $customizationData['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formInputColor'],
                    'sbs-ssf-input-text-color'    => $customizationData['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formInputTextColor'],
                    'sbs-ssf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formDropdownColor'],
                    'sbs-ssf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formDropdownTextColor'],
                    'sbs-cf-gradient1'            => $customizationData['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formGradientColor1'],
                    'sbs-cf-gradient2'            => $customizationData['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formGradientColor2'],
                    'sbs-cf-gradient-angle'       => $customizationData['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formGradientAngle'].'deg',
                    'sbs-cf-text-color'           => $customizationData['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formTextColor'],
                    'sbs-rsf-gradient1'           => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formGradientColor1'],
                    'sbs-rsf-gradient2'           => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formGradientColor2'],
                    'sbs-rsf-gradient-angle'      => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formGradientAngle'].'deg',
                    'sbs-rsf-text-color'          => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formTextColor'],
                    'sbs-rsf-input-color'         => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formInputColor'],
                    'sbs-rsf-input-text-color'    => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formInputTextColor'],
                    'sbs-rsf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formDropdownColor'],
                    'sbs-rsf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formDropdownTextColor'],
                    'sbs-rdf-bgr-color'           => $customizationData['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formBackgroundColor'],
                    'sbs-rdf-text-color'          => $customizationData['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formTextColor'],
                    'sbs-rdf-input-color'         => $customizationData['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formInputColor'],
                    'sbs-rdf-input-text-color'    => $customizationData['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formInputTextColor'],
                    'sbs-rdf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formDropdownColor'],
                    'sbs-rdf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formDropdownTextColor'],
                    'sbs-caf-bgr-color'           => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formBackgroundColor'],
                    'sbs-caf-text-color'          => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formTextColor'],
                    'sbs-caf-input-color'         => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formInputColor'],
                    'sbs-caf-input-text-color'    => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formInputTextColor'],
                    'sbs-caf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownColor'],
                    'sbs-caf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownTextColor'],
                    'sbs-spf-bgr-color'           => $customizationData['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formBackgroundColor'],
                    'sbs-spf-text-color'          => $customizationData['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formTextColor'],
                    'sbs-spf-input-color'         => $customizationData['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formInputColor'],
                    'sbs-spf-input-text-color'    => $customizationData['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formInputTextColor'],
                    'sbs-spf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formDropdownColor'],
                    'sbs-spf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formDropdownTextColor'],
                    'sbs-pif-bgr-color'           => $customizationData['forms']['stepByStepForm']['packageInfoForm']['globalSettings']['formBackgroundColor'],
                    'sbs-pif-text-color'          => $customizationData['forms']['stepByStepForm']['packageInfoForm']['globalSettings']['formTextColor'],
                    'sbs-psf-gradient1'           => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formGradientColor1'],
                    'sbs-psf-gradient2'           => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formGradientColor2'],
                    'sbs-psf-gradient-angle'      => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formGradientAngle'].'deg',
                    'sbs-psf-text-color'          => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formTextColor'],
                    'sbs-psf-input-color'         => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formInputColor'],
                    'sbs-psf-input-text-color'    => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formInputTextColor'],
                    'sbs-psf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formDropdownColor'],
                    'sbs-psf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formDropdownTextColor'],
                    'sbs-plf-bgr-color'           => $customizationData['forms']['stepByStepForm']['packageListForm']['globalSettings']['formBackgroundColor'],
                    'sbs-plf-text-color'          => $customizationData['forms']['stepByStepForm']['packageListForm']['globalSettings']['formTextColor'],
                    'sbs-cpf-bgr-color'           => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formBackgroundColor'],
                    'sbs-cpf-text-color'          => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formTextColor'],
                    'sbs-cpf-input-color'         => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formInputColor'],
                    'sbs-cpf-input-text-color'    => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formInputTextColor'],
                    'sbs-cpf-dropdown-color'      => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formDropdownColor'],
                    'sbs-cpf-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formDropdownTextColor'],
                    'sbs-coa-bgr-color'           => $customizationData['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formBackgroundColor'],
                    'sbs-coa-text-color'          => $customizationData['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formTextColor'],
                    'sbs-coa-input-color'         => $customizationData['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formInputColor'],
                    'sbs-coa-input-text-color'    => $customizationData['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formInputTextColor'],
                    'sbs-coa-dropdown-color'      => $customizationData['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownColor'],
                    'sbs-coa-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownTextColor'],
                    'sbs-cop-bgr-color'           => $customizationData['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formBackgroundColor'],
                    'sbs-cop-text-color'          => $customizationData['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formTextColor'],
                    'sbs-cop-input-color'         => $customizationData['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formInputColor'],
                    'sbs-cop-input-text-color'    => $customizationData['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formInputTextColor'],
                    'sbs-cop-dropdown-color'      => $customizationData['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formDropdownColor'],
                    'sbs-cop-dropdown-text-color' => $customizationData['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formDropdownTextColor'],
                    // event list
                    'elf-bgr-color'               => $customizationData['forms']['eventListForm']['globalSettings']['formBackgroundColor'],
                    'elf-text-color'              => $customizationData['forms']['eventListForm']['globalSettings']['formTextColor'],
                    'elf-input-color'             => $customizationData['forms']['eventListForm']['globalSettings']['formInputColor'],
                    'elf-input-text-color'        => $customizationData['forms']['eventListForm']['globalSettings']['formInputTextColor'],
                    'elf-dropdown-color'          => $customizationData['forms']['eventListForm']['globalSettings']['formDropdownColor'],
                    'elf-dropdown-text-color'     => $customizationData['forms']['eventListForm']['globalSettings']['formDropdownTextColor'],
                    // catalog
                    'cf-ssf-bgr-color'           => $customizationData['forms']['catalogForm']['selectServiceForm']['globalSettings']['formBackgroundColor'],
                    'cf-ssf-text-color'          => $customizationData['forms']['catalogForm']['selectServiceForm']['globalSettings']['formTextColor'],
                    'cf-ssf-input-color'         => $customizationData['forms']['catalogForm']['selectServiceForm']['globalSettings']['formInputColor'],
                    'cf-ssf-input-text-color'    => $customizationData['forms']['catalogForm']['selectServiceForm']['globalSettings']['formInputTextColor'],
                    'cf-ssf-dropdown-color'      => $customizationData['forms']['catalogForm']['selectServiceForm']['globalSettings']['formDropdownColor'],
                    'cf-ssf-dropdown-text-color' => $customizationData['forms']['catalogForm']['selectServiceForm']['globalSettings']['formDropdownTextColor'],
                    'cf-cf-gradient1'            => $customizationData['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formGradientColor1'],
                    'cf-cf-gradient2'            => $customizationData['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formGradientColor2'],
                    'cf-cf-gradient-angle'       => $customizationData['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formGradientAngle'].'deg',
                    'cf-cf-text-color'           => $customizationData['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formTextColor'],
                    'cf-rsf-gradient1'           => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formGradientColor1'],
                    'cf-rsf-gradient2'           => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formGradientColor2'],
                    'cf-rsf-gradient-angle'      => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formGradientAngle'].'deg',
                    'cf-rsf-text-color'          => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formTextColor'],
                    'cf-rsf-input-color'         => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formInputColor'],
                    'cf-rsf-input-text-color'    => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formInputTextColor'],
                    'cf-rsf-dropdown-color'      => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formDropdownColor'],
                    'cf-rsf-dropdown-text-color' => $customizationData['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formDropdownTextColor'],
                    'cf-rdf-bgr-color'           => $customizationData['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formBackgroundColor'],
                    'cf-rdf-text-color'          => $customizationData['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formTextColor'],
                    'cf-rdf-input-color'         => $customizationData['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formInputColor'],
                    'cf-rdf-input-text-color'    => $customizationData['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formInputTextColor'],
                    'cf-rdf-dropdown-color'      => $customizationData['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formDropdownColor'],
                    'cf-rdf-dropdown-text-color' => $customizationData['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formDropdownTextColor'],
                    'cf-caf-bgr-color'           => $customizationData['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formBackgroundColor'],
                    'cf-caf-text-color'          => $customizationData['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formTextColor'],
                    'cf-caf-input-color'         => $customizationData['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formInputColor'],
                    'cf-caf-input-text-color'    => $customizationData['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formInputTextColor'],
                    'cf-caf-dropdown-color'      => $customizationData['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownColor'],
                    'cf-caf-dropdown-text-color' => $customizationData['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownTextColor'],
                    'cf-psf-gradient1'           => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formGradientColor1'],
                    'cf-psf-gradient2'           => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formGradientColor2'],
                    'cf-psf-gradient-angle'      => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formGradientAngle'].'deg',
                    'cf-psf-text-color'          => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formTextColor'],
                    'cf-psf-input-color'         => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formInputColor'],
                    'cf-psf-input-text-color'    => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formInputTextColor'],
                    'cf-psf-dropdown-color'      => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formDropdownColor'],
                    'cf-psf-dropdown-text-color' => $customizationData['forms']['catalogForm']['packageSetupForm']['globalSettings']['formDropdownTextColor'],
                    'cf-plf-bgr-color'           => $customizationData['forms']['catalogForm']['packageListForm']['globalSettings']['formBackgroundColor'],
                    'cf-plf-text-color'          => $customizationData['forms']['catalogForm']['packageListForm']['globalSettings']['formTextColor'],
                    'cf-cpf-bgr-color'           => $customizationData['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formBackgroundColor'],
                    'cf-cpf-text-color'          => $customizationData['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formTextColor'],
                    'cf-cpf-input-color'         => $customizationData['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formInputColor'],
                    'cf-cpf-input-text-color'    => $customizationData['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formInputTextColor'],
                    'cf-cpf-dropdown-color'      => $customizationData['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formDropdownColor'],
                    'cf-cpf-dropdown-text-color' => $customizationData['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formDropdownTextColor'],
                    'cf-coa-bgr-color'           => $customizationData['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formBackgroundColor'],
                    'cf-coa-text-color'          => $customizationData['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formTextColor'],
                    'cf-coa-input-color'         => $customizationData['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formInputColor'],
                    'cf-coa-input-text-color'    => $customizationData['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formInputTextColor'],
                    'cf-coa-dropdown-color'      => $customizationData['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownColor'],
                    'cf-coa-dropdown-text-color' => $customizationData['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownTextColor'],
                    'cf-cop-bgr-color'           => $customizationData['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formBackgroundColor'],
                    'cf-cop-text-color'          => $customizationData['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formTextColor'],
                    'cf-cop-input-color'         => $customizationData['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formInputColor'],
                    'cf-cop-input-text-color'    => $customizationData['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formInputTextColor'],
                    'cf-cop-dropdown-color'      => $customizationData['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formDropdownColor'],
                    'cf-cop-dropdown-text-color' => $customizationData['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formDropdownTextColor'],
                ]
            );

            $settingsFields['customization']['hash'] = $hash;

            $settingsFields['customization']['useGenerated'] = isset($customizationData['useGenerated']) ?
                $customizationData['useGenerated'] : true;
        }

        if (WooCommerceService::isEnabled() &&
            $command->getField('payments') &&
            $command->getField('payments')['wc']['enabled']
        ) {
            $settingsFields['payments']['wc']['productId'] = WooCommerceService::getIdForExistingOrNewProduct(
                $settingsService->getCategorySettings('payments')['wc']['productId']
            );
        }

        if ($command->getField('useWindowVueInAmelia') !== null) {
            $generalSettings = $settingsService->getCategorySettings('general');

            $settingsFields['general'] = $generalSettings;

            $settingsFields['general']['useWindowVueInAmelia'] = $command->getField('useWindowVueInAmelia');

            unset($settingsFields['useWindowVueInAmelia']);
        }

        if ($command->getField('sendAllCF') !== null) {
            $notificationsSettings = $settingsService->getCategorySettings('notifications');

            $settingsFields['notifications'] = $notificationsSettings;

            $settingsFields['notifications']['sendAllCF'] = $command->getField('sendAllCF');

            unset($settingsFields['sendAllCF']);
        }

        if (!$settingsService->getCategorySettings('activation')['stash'] &&
            !empty($settingsFields['activation']['stash'])
        ) {
            /** @var StashApplicationService $stashApplicationService */
            $stashApplicationService = $this->container->get('application.stash.service');

            $stashApplicationService->setStash();
        }

        $settingsFields['activation'] = array_merge(
            $settingsService->getCategorySettings('activation'),
            isset($settingsFields['activation']['deleteTables']) ? [
                'deleteTables' => $settingsFields['activation']['deleteTables']
            ] : [],
            isset($settingsFields['activation']['stash']) ? [
                'stash' => $settingsFields['activation']['stash']
            ] : []
        );

        if ($command->getField('usedLanguages') !== null) {
            $generalSettings = $settingsService->getCategorySettings('general');

            $settingsFields['general'] = $generalSettings;

            $settingsFields['general']['usedLanguages'] = $command->getField('usedLanguages');

            unset($settingsFields['usedLanguages']);
        }

        $settingsService->setAllSettings($settingsFields);

        $settings = $settingsService->getAllSettingsCategorized();
        $settings['general']['phoneDefaultCountryCode'] = $settings['general']['phoneDefaultCountryCode'] === 'auto' ?
            $locationService->getCurrentLocationCountryIso() : $settings['general']['phoneDefaultCountryCode'];

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully updated settings.');
        $result->setData(
            [
                'settings' => $settings
            ]
        );

        return $result;
    }
}
