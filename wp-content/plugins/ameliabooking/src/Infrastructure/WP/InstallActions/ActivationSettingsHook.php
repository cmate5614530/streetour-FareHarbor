<?php
/**
 * Settings hook for activation
 */

namespace AmeliaBooking\Infrastructure\WP\InstallActions;

use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\String\Token;
use AmeliaBooking\Infrastructure\Services\Frontend\LessParserService;
use AmeliaBooking\Infrastructure\WP\SettingsService\SettingsStorage;
use Exception;

/**
 * Class ActivationSettingsHook
 *
 * @package AmeliaBooking\Infrastructure\WP\InstallActions
 */
class ActivationSettingsHook
{
    /**
     * Initialize the plugin
     *
     * @throws Exception
     */
    public static function init()
    {
        self::initDBSettings();

        self::initGeneralSettings();

        self::initCompanySettings();

        self::initNotificationsSettings();

        self::initDaysOffSettings();

        self::initWeekScheduleSettings();

        self::initGoogleCalendarSettings();

        self::initOutlookCalendarSettings();

        self::initPaymentsSettings();

        self::initActivationSettings();

        self::initCustomizationSettings();

        self::initLabelsSettings();

        self::initRolesSettings();

        self::initAppointmentsSettings();

        self::initWebHooksSettings();

        self::initZoomSettings();

        self::initIcsSettings();
    }

    /**
     * @param string $category
     * @param array  $settings
     * @param bool   $replace
     */
    public static function initSettings($category, $settings, $replace = false)
    {
        $settingsService = new SettingsService(new SettingsStorage());

        if (!$settingsService->getCategorySettings($category)) {
            $settingsService->setCategorySettings(
                $category,
                []
            );
        }

        foreach ($settings as $key => $value) {
            if ($replace || null === $settingsService->getSetting($category, $key)) {
                $settingsService->setSetting(
                    $category,
                    $key,
                    $value
                );
            }
        }
    }

    /**
     * Init General Settings
     */
    private static function initGeneralSettings()
    {
        $settingsService = new SettingsService(new SettingsStorage());

        $savedSettings = $settingsService->getCategorySettings('general');

        $settings = [
            'timeSlotLength'                         => 1800,
            'serviceDurationAsSlot'                  => false,
            'bufferTimeInSlot'                       => true,
            'defaultAppointmentStatus'               => 'approved',
            'minimumTimeRequirementPriorToBooking'   => 0,
            'minimumTimeRequirementPriorToCanceling' => 0,
            'minimumTimeRequirementPriorToRescheduling' =>
                isset($savedSettings['minimumTimeRequirementPriorToCanceling']) &&
                !isset($savedSettings['minimumTimeRequirementPriorToRescheduling']) ?
                    $savedSettings['minimumTimeRequirementPriorToCanceling'] : 0,
            'numberOfDaysAvailableForBooking'        => SettingsService::NUMBER_OF_DAYS_AVAILABLE_FOR_BOOKING,
            'backendSlotsDaysInFuture'               => SettingsService::NUMBER_OF_DAYS_AVAILABLE_FOR_BOOKING,
            'backendSlotsDaysInPast'                 => SettingsService::NUMBER_OF_DAYS_AVAILABLE_FOR_BOOKING,
            'phoneDefaultCountryCode'                => 'auto',
            'requiredPhoneNumberField'               => false,
            'requiredEmailField'                     => true,
            'itemsPerPage'                           => 12,
            'appointmentsPerPage'                    => 100,
            'servicesPerPage'                        => 100,
            'customersFilterLimit'                   => 100,
            'gMapApiKey'                             => '',
            'addToCalendar'                          => true,
            'sendIcsAttachment'                      => false,
            'defaultPageOnBackend'                   => 'Dashboard',
            'showClientTimeZone'                     => false,
            'redirectUrlAfterAppointment'            => '',
            'customFieldsUploadsPath'                => '',
            'runInstantPostBookingActions'           => false,
            'useWindowVueInAmelia'                   => true,
            'useWindowVueInAmeliaBack'               =>
                isset($savedSettings['useWindowVueInAmelia']) &&
                !isset($savedSettings['useWindowVueInAmeliaBack']) ?
                    $savedSettings['useWindowVueInAmelia'] : true,
            'sortingPackages'                        => 'nameAsc',
            'sortingServices'                        => 'nameAsc',
            'calendarLocaleSubstitutes'              => [
            ],
            'googleRecaptcha'                        => [
                'enabled'   => false,
                'invisible' => true,
                'siteKey'   => '',
                'secret'    => '',
            ],
            'usedLanguages'                          => [],
        ];

        self::initSettings('general', $settings);
    }

    /**
     * Init DB Settings
     */
    private static function initDBSettings()
    {
        $settings = [
            'mysqliEnabled'      => false,
            'pdoEmulatePrepares' => false,
            'pdoBigSelect'       => false,
            'ssl' => [
                'enable' => false,
                'key'    => null,
                'cert'   => null,
                'ca'     => null,
            ],
        ];

        self::initSettings('db', $settings);
    }

    /**
     * Init Company Settings
     */
    private static function initCompanySettings()
    {

        $settings = [
            'pictureFullPath'  => '',
            'pictureThumbPath' => '',
            'name'             => '',
            'address'          => '',
            'phone'            => '',
            'countryPhoneIso'  => '',
            'website'          => ''
        ];

        self::initSettings('company', $settings);
    }

    /**
     * Init Notification Settings
     */
    private static function initNotificationsSettings()
    {
        $settings = [
            'mailService'      => 'php',
            'smtpHost'         => '',
            'smtpPort'         => '',
            'smtpSecure'       => 'ssl',
            'smtpUsername'     => '',
            'smtpPassword'     => '',
            'mailgunApiKey'    => '',
            'mailgunDomain'    => '',
            'mailgunEndpoint'  => '',
            'senderName'       => '',
            'senderEmail'      => '',
            'notifyCustomers'  => true,
            'sendAllCF'        => true,
            'smsAlphaSenderId' => 'Amelia',
            'smsSignedIn'      => false,
            'smsApiToken'      => '',
            'bccEmail'         => '',
            'bccSms'           => '',
            'cancelSuccessUrl' => '',
            'cancelErrorUrl'   => '',
            'breakReplacement' => '<br>'
        ];

        self::initSettings('notifications', $settings);
    }

    /**
     * Init Days Off Settings
     */
    private static function initDaysOffSettings()
    {
        self::initSettings('daysOff', []);
    }

    /**
     * Init Work Schedule Settings
     */
    private static function initWeekScheduleSettings()
    {
        self::initSettings('weekSchedule', [
            [
                'day'     => 'Monday',
                'time'    => ['09:00', '17:00'],
                'breaks'  => [],
                'periods' => []
            ],
            [
                'day'     => 'Tuesday',
                'time'    => ['09:00', '17:00'],
                'breaks'  => [],
                'periods' => []
            ],
            [
                'day'     => 'Wednesday',
                'time'    => ['09:00', '17:00'],
                'breaks'  => [],
                'periods' => []
            ],
            [
                'day'     => 'Thursday',
                'time'    => ['09:00', '17:00'],
                'breaks'  => [],
                'periods' => []
            ],
            [
                'day'     => 'Friday',
                'time'    => ['09:00', '17:00'],
                'breaks'  => [],
                'periods' => []
            ],
            [
                'day'     => 'Saturday',
                'time'    => [],
                'breaks'  => [],
                'periods' => []
            ],
            [
                'day'     => 'Sunday',
                'time'    => [],
                'breaks'  => [],
                'periods' => []
            ]
        ]);
    }

    /**
     * Init Google Calendar Settings
     */
    private static function initGoogleCalendarSettings()
    {
        $settings = [
            'clientID'                        => '',
            'clientSecret'                    => '',
            'redirectURI'                     => AMELIA_SITE_URL . '/wp-admin/admin.php?page=wpamelia-employees',
            'showAttendees'                   => false,
            'insertPendingAppointments'       => false,
            'addAttendees'                    => false,
            'sendEventInvitationEmail'        => false,
            'removeGoogleCalendarBusySlots'   => false,
            'maximumNumberOfEventsReturned'   => 50,
            'eventTitle'                      => '%service_name%',
            'eventDescription'                => '',
            'includeBufferTimeGoogleCalendar' => false,
            'status'                          => 'tentative',
            'enableGoogleMeet'                => false,
        ];

        self::initSettings('googleCalendar', $settings);
    }

    /**
     * Init Outlook Calendar Settings
     */
    private static function initOutlookCalendarSettings()
    {
        $settings = [
            'clientID'                         => '',
            'clientSecret'                     => '',
            'redirectURI'                      => AMELIA_SITE_URL . '/wp-admin/',
            'insertPendingAppointments'        => false,
            'addAttendees'                     => false,
            'sendEventInvitationEmail'         => false,
            'removeOutlookCalendarBusySlots'   => false,
            'maximumNumberOfEventsReturned'    => 50,
            'eventTitle'                       => '%service_name%',
            'eventDescription'                 => '',
            'includeBufferTimeOutlookCalendar' => false,
        ];

        self::initSettings('outlookCalendar', $settings);
    }

    /**
     * Init Zoom Settings
     */
    private static function initZoomSettings()
    {
        $settings = [
            'enabled'                     => true,
            'apiKey'                      => '',
            'apiSecret'                   => '',
            'meetingTitle'                => '%reservation_name%',
            'meetingAgenda'               => '%reservation_description%',
            'pendingAppointmentsMeetings' => false,
            'maxUsersCount'               => 300
        ];

        self::initSettings('zoom', $settings);
    }

    /**
     * Init Ics Settings
     */
    private static function initIcsSettings()
    {
        $settings = [
            'description' => [
                'appointment'  => '',
                'event'        => '',
                'translations' => [
                    'appointment' => null,
                    'event'       => null,
                ],
            ],
        ];

        self::initSettings('ics', $settings);
    }

    /**
     * Init Payments Settings
     */
    private static function initPaymentsSettings()
    {
        $settings = [
            'currency'                   => 'USD',
            'symbol'                     => '$',
            'priceSymbolPosition'        => 'before',
            'priceNumberOfDecimals'      => 2,
            'priceSeparator'             => 1,
            'hideCurrencySymbolFrontend' => false,
            'defaultPaymentMethod'       => 'onSite',
            'onSite'                     => true,
            'coupons'                    => true,
            'payPal'                     => [
                'enabled'         => false,
                'sandboxMode'     => false,
                'liveApiClientId' => '',
                'liveApiSecret'   => '',
                'testApiClientId' => '',
                'testApiSecret'   => '',
                'description'     => [
                    'enabled'     => false,
                    'appointment' => '',
                    'package'     => '',
                    'event'       => ''
                ],
            ],
            'stripe'                     => [
                'enabled'            => false,
                'testMode'           => false,
                'livePublishableKey' => '',
                'liveSecretKey'      => '',
                'testPublishableKey' => '',
                'testSecretKey'      => '',
                'description'        => [
                    'enabled'     => false,
                    'appointment' => '',
                    'package'     => '',
                    'event'       => ''
                ],
                'metaData'           => [
                    'enabled'     => false,
                    'appointment' => null,
                    'package'     => null,
                    'event'       => null
                ],
                'manualCapture'   => false,
            ],
            'wc'                         => [
                'enabled'      => false,
                'productId'    => '',
                'onSiteIfFree' => false,
                'page'         => 'cart',
                'dashboard'    => true,
                'checkoutData' => [
                    'appointment' => '',
                    'package'     => '',
                    'event'       => '',
                    'translations' => [
                        'appointment' => null,
                        'event'       => null,
                        'package'     => null,
                    ],
                ],
                'skipCheckoutGetValueProcessing' => false,
            ],
            'mollie'           => [
                'enabled'         => false,
                'testMode'        => false,
                'liveApiKey'      => '',
                'testApiKey'      => '',
                'description'        => [
                    'enabled'     => false,
                    'appointment' => '',
                    'package'     => '',
                    'event'       => ''
                ],
                'metaData'           => [
                    'enabled'     => false,
                    'appointment' => null,
                    'package'     => null,
                    'event'       => null
                ],
                'method'          => [],
            ],
        ];

        self::initSettings('payments', $settings);

        self::setNewSettingsToExistingSettings(
            'payments',
            [
                ['stripe', 'description'],
                ['stripe', 'description', 'package'],
                ['stripe', 'metaData'],
                ['stripe', 'metaData', 'package'],
                ['stripe', 'manualCapture'],
                ['payPal', 'description'],
                ['payPal', 'description', 'package'],
                ['wc', 'onSiteIfFree'],
                ['wc', 'page'],
                ['wc', 'dashboard'],
                ['wc', 'skipCheckoutGetValueProcessing'],
                ['wc', 'checkoutData'],
                ['wc', 'checkoutData', 'package'],
                ['wc', 'checkoutData', 'translations'],
                ['wc', 'checkoutData', 'translations', 'appointment'],
                ['wc', 'checkoutData', 'translations', 'event'],
                ['wc', 'checkoutData', 'translations', 'package'],
            ],
            $settings
        );
    }

    /**
     * Init Purchase Code Settings
     */
    private static function initActivationSettings()
    {
        $settings = [
            'showActivationSettings' => true,
            'active'                 => false,
            'purchaseCodeStore'      => '',
            'envatoTokenEmail'       => '',
            'version'                => '',
            'deleteTables'           => false,
            'stash'                  => false,
        ];

        self::initSettings('activation', $settings);
    }

    /**
     * Init Customization Settings
     *
     * @throws Exception
     */
    private static function initCustomizationSettings()
    {
        $settingsService = new SettingsService(new SettingsStorage());

        $settings = $settingsService->getCategorySettings('customization');
        unset($settings['hash']);

        $lessParserService = new LessParserService(
            AMELIA_PATH . '/assets/less/frontend/amelia-booking.less',
            UPLOADS_PATH . '/amelia/css',
            $settingsService
        );

        if (!$settings) {
            $settings = [
                'primaryColor'                => '#1A84EE',
                'primaryGradient1'            => '#1A84EE',
                'primaryGradient2'            => '#0454A2',
                'textColor'                   => '#354052',
                'textColorOnBackground'       => '#FFFFFF',
                'font'                        => 'Amelia Roboto',
                'useGenerated'                => false,
            ];
        }

        $settingsForm = !isset($settings['forms']) ? [
            'sbs-ssf-bgr-color'           => '#FFFFFF',
            'sbs-ssf-text-color'          => $settings['textColor'],
            'sbs-ssf-input-color'         => '#FFFFFF',
            'sbs-ssf-input-text-color'    => $settings['textColor'],
            'sbs-ssf-dropdown-color'      => '#FFFFFF',
            'sbs-ssf-dropdown-text-color' => $settings['textColor'],
            'sbs-cf-gradient1'            => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
            'sbs-cf-gradient2'            => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
            'sbs-cf-gradient-angle'       => '135deg',
            'sbs-cf-text-color'           => $settings['textColorOnBackground'],
            'sbs-rsf-gradient1'           => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
            'sbs-rsf-gradient2'           => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
            'sbs-rsf-gradient-angle'      => '135deg',
            'sbs-rsf-text-color'          => $settings['textColorOnBackground'],
            'sbs-rsf-input-color'         => 'rgba(0, 0, 0, 0)',
            'sbs-rsf-input-text-color'    => $settings['textColorOnBackground'],
            'sbs-rsf-dropdown-color'      => '#FFFFFF',
            'sbs-rsf-dropdown-text-color' => $settings['textColor'],
            'sbs-rdf-bgr-color'           => '#FFFFFF',
            'sbs-rdf-text-color'          => $settings['textColor'],
            'sbs-rdf-input-color'         => $settings['textColorOnBackground'],
            'sbs-rdf-input-text-color'    => $settings['textColor'],
            'sbs-rdf-dropdown-color'      => '#FFFFFF',
            'sbs-rdf-dropdown-text-color' => $settings['textColor'],
            'sbs-caf-bgr-color'           => '#FFFFFF',
            'sbs-caf-text-color'          => $settings['textColor'],
            'sbs-caf-input-color'         => '#FFFFFF',
            'sbs-caf-input-text-color'    => $settings['textColor'],
            'sbs-caf-dropdown-color'      => '#FFFFFF',
            'sbs-caf-dropdown-text-color' => $settings['textColor'],
            'sbs-spf-bgr-color'           => '#FFFFFF',
            'sbs-spf-text-color'          => $settings['textColor'],
            'sbs-spf-input-color'         => '#FFFFFF',
            'sbs-spf-input-text-color'    => $settings['textColor'],
            'sbs-spf-dropdown-color'      => '#FFFFFF',
            'sbs-spf-dropdown-text-color' => $settings['textColor'],
            'sbs-pif-bgr-color'           => '#FFFFFF',
            'sbs-pif-text-color'          => $settings['textColor'],
            'sbs-psf-gradient1'           => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
            'sbs-psf-gradient2'           => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
            'sbs-psf-gradient-angle'      => '135deg',
            'sbs-psf-text-color'          => $settings['textColorOnBackground'],
            'sbs-psf-input-color'         => 'rgba(0, 0, 0, 0.1)',
            'sbs-psf-input-text-color'    => $settings['textColorOnBackground'],
            'sbs-psf-dropdown-color'      => '#FFFFFF',
            'sbs-psf-dropdown-text-color' => $settings['textColor'],
            'sbs-plf-dropdown-color'      => '#FFFFFF',
            'sbs-plf-dropdown-text-color' => $settings['textColor'],
            'sbs-cpf-bgr-color'           => '#FFFFFF',
            'sbs-cpf-text-color'          => $settings['textColor'],
            'sbs-cpf-input-color'         => '#FFFFFF',
            'sbs-cpf-input-text-color'    => $settings['textColor'],
            'sbs-cpf-dropdown-color'      => '#FFFFFF',
            'sbs-cpf-dropdown-text-color' => $settings['textColor'],
            'sbs-coa-bgr-color'           => '#FFFFFF',
            'sbs-coa-text-color'          => $settings['textColor'],
            'sbs-coa-input-color'         => '#FFFFFF',
            'sbs-coa-input-text-color'    => $settings['textColor'],
            'sbs-coa-dropdown-color'      => '#FFFFFF',
            'sbs-coa-dropdown-text-color' => $settings['textColor'],
            'sbs-cop-bgr-color'           => '#FFFFFF',
            'sbs-cop-text-color'          => $settings['textColor'],
            'sbs-cop-input-color'         => '#FFFFFF',
            'sbs-cop-input-text-color'    => $settings['textColor'],
            'sbs-cop-dropdown-color'      => '#FFFFFF',
            'sbs-cop-dropdown-text-color' => $settings['textColor'],
            'elf-bgr-color'               => '#FFFFFF',
            'elf-text-color'              => $settings['textColor'],
            'elf-input-color'             => '#FFFFFF',
            'elf-input-text-color'        => $settings['textColor'],
            'elf-dropdown-color'          => '#FFFFFF',
            'elf-dropdown-text-color'     => $settings['textColor'],
            'cf-ssf-bgr-color'           => '#FFFFFF',
            'cf-ssf-text-color'          => $settings['textColor'],
            'cf-ssf-input-color'         => '#FFFFFF',
            'cf-ssf-input-text-color'    => $settings['textColor'],
            'cf-ssf-dropdown-color'      => '#FFFFFF',
            'cf-ssf-dropdown-text-color' => $settings['textColor'],
            'cf-cf-gradient1'            => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
            'cf-cf-gradient2'            => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
            'cf-cf-gradient-angle'       => '135deg',
            'cf-cf-text-color'           => $settings['textColorOnBackground'],
            'cf-rsf-gradient1'           => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
            'cf-rsf-gradient2'           => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
            'cf-rsf-gradient-angle'      => '135deg',
            'cf-rsf-text-color'          => $settings['textColorOnBackground'],
            'cf-rsf-input-color'         => 'rgba(0, 0, 0, 0)',
            'cf-rsf-input-text-color'    => $settings['textColorOnBackground'],
            'cf-rsf-dropdown-color'      => '#FFFFFF',
            'cf-rsf-dropdown-text-color' => $settings['textColor'],
            'cf-rdf-bgr-color'           => '#FFFFFF',
            'cf-rdf-text-color'          => $settings['textColor'],
            'cf-rdf-input-color'         => $settings['textColorOnBackground'],
            'cf-rdf-input-text-color'    => $settings['textColor'],
            'cf-rdf-dropdown-color'      => '#FFFFFF',
            'cf-rdf-dropdown-text-color' => $settings['textColor'],
            'cf-caf-bgr-color'           => '#FFFFFF',
            'cf-caf-text-color'          => $settings['textColor'],
            'cf-caf-input-color'         => '#FFFFFF',
            'cf-caf-input-text-color'    => $settings['textColor'],
            'cf-caf-dropdown-color'      => '#FFFFFF',
            'cf-caf-dropdown-text-color' => $settings['textColor'],
            'cf-psf-gradient1'           => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
            'cf-psf-gradient2'           => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
            'cf-psf-gradient-angle'      => '135deg',
            'cf-psf-text-color'          => $settings['textColorOnBackground'],
            'cf-psf-input-color'         => 'rgba(0, 0, 0, 0.1)',
            'cf-psf-input-text-color'    => $settings['textColorOnBackground'],
            'cf-psf-dropdown-color'      => '#FFFFFF',
            'cf-psf-dropdown-text-color' => $settings['textColor'],
            'cf-plf-dropdown-color'      => '#FFFFFF',
            'cf-plf-dropdown-text-color' => $settings['textColor'],
            'cf-cpf-bgr-color'           => '#FFFFFF',
            'cf-cpf-text-color'          => $settings['textColor'],
            'cf-cpf-input-color'         => '#FFFFFF',
            'cf-cpf-input-text-color'    => $settings['textColor'],
            'cf-cpf-dropdown-color'      => '#FFFFFF',
            'cf-cpf-dropdown-text-color' => $settings['textColor'],
            'cf-coa-bgr-color'           => '#FFFFFF',
            'cf-coa-text-color'          => $settings['textColor'],
            'cf-coa-input-color'         => '#FFFFFF',
            'cf-coa-input-text-color'    => $settings['textColor'],
            'cf-coa-dropdown-color'      => '#FFFFFF',
            'cf-coa-dropdown-text-color' => $settings['textColor'],
            'cf-cop-bgr-color'           => '#FFFFFF',
            'cf-cop-text-color'          => $settings['textColor'],
            'cf-cop-input-color'         => '#FFFFFF',
            'cf-cop-input-text-color'    => $settings['textColor'],
            'cf-cop-dropdown-color'      => '#FFFFFF',
            'cf-cop-dropdown-text-color' => $settings['textColor'],
        ] : [
            'sbs-ssf-bgr-color'           => $settings['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formBackgroundColor'],
            'sbs-ssf-text-color'          => $settings['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formTextColor'],
            'sbs-ssf-input-color'         => $settings['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formInputColor'],
            'sbs-ssf-input-text-color'    => $settings['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formInputTextColor'],
            'sbs-ssf-dropdown-color'      => $settings['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formDropdownColor'],
            'sbs-ssf-dropdown-text-color' => $settings['forms']['stepByStepForm']['selectServiceForm']['globalSettings']['formDropdownTextColor'],
            'sbs-cf-gradient1'            => $settings['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formGradientColor1'],
            'sbs-cf-gradient2'            => $settings['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formGradientColor2'],
            'sbs-cf-gradient-angle'       => $settings['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formGradientAngle'].'deg',
            'sbs-cf-text-color'           => $settings['forms']['stepByStepForm']['calendarDateTimeForm']['globalSettings']['formTextColor'],
            'sbs-rsf-gradient1'           => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formGradientColor1'],
            'sbs-rsf-gradient2'           => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formGradientColor2'],
            'sbs-rsf-gradient-angle'      => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formGradientAngle'].'deg',
            'sbs-rsf-text-color'          => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formTextColor'],
            'sbs-rsf-input-color'         => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formInputColor'],
            'sbs-rsf-input-text-color'    => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formInputTextColor'],
            'sbs-rsf-dropdown-color'      => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formDropdownColor'],
            'sbs-rsf-dropdown-text-color' => $settings['forms']['stepByStepForm']['recurringSetupForm']['globalSettings']['formDropdownTextColor'],
            'sbs-rdf-bgr-color'           => $settings['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formBackgroundColor'],
            'sbs-rdf-text-color'          => $settings['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formTextColor'],
            'sbs-rdf-input-color'         => $settings['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formInputColor'],
            'sbs-rdf-input-text-color'    => $settings['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formInputTextColor'],
            'sbs-rdf-dropdown-color'      => $settings['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formDropdownColor'],
            'sbs-rdf-dropdown-text-color' => $settings['forms']['stepByStepForm']['recurringDatesForm']['globalSettings']['formDropdownTextColor'],
            'sbs-caf-bgr-color'           => $settings['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formBackgroundColor'],
            'sbs-caf-text-color'          => $settings['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formTextColor'],
            'sbs-caf-input-color'         => $settings['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formInputColor'],
            'sbs-caf-input-text-color'    => $settings['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formInputTextColor'],
            'sbs-caf-dropdown-color'      => $settings['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownColor'],
            'sbs-caf-dropdown-text-color' => $settings['forms']['stepByStepForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownTextColor'],
            'sbs-spf-bgr-color'           => $settings['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formBackgroundColor'],
            'sbs-spf-text-color'          => $settings['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formTextColor'],
            'sbs-spf-input-color'         => $settings['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formInputColor'],
            'sbs-spf-input-text-color'    => $settings['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formInputTextColor'],
            'sbs-spf-dropdown-color'      => $settings['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formDropdownColor'],
            'sbs-spf-dropdown-text-color' => $settings['forms']['stepByStepForm']['selectPackageForm']['globalSettings']['formDropdownTextColor'],
            'sbs-pif-bgr-color'           => $settings['forms']['stepByStepForm']['packageInfoForm']['globalSettings']['formBackgroundColor'],
            'sbs-pif-text-color'          => $settings['forms']['stepByStepForm']['packageInfoForm']['globalSettings']['formTextColor'],
            'sbs-psf-gradient1'           => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formGradientColor1'],
            'sbs-psf-gradient2'           => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formGradientColor2'],
            'sbs-psf-gradient-angle'      => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formGradientAngle'].'deg',
            'sbs-psf-text-color'          => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formTextColor'],
            'sbs-psf-input-color'         => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formInputColor'],
            'sbs-psf-input-text-color'    => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formInputTextColor'],
            'sbs-psf-dropdown-color'      => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formDropdownColor'],
            'sbs-psf-dropdown-text-color' => $settings['forms']['stepByStepForm']['packageSetupForm']['globalSettings']['formDropdownTextColor'],
            'sbs-plf-bgr-color'           => $settings['forms']['stepByStepForm']['packageListForm']['globalSettings']['formBackgroundColor'],
            'sbs-plf-text-color'          => $settings['forms']['stepByStepForm']['packageListForm']['globalSettings']['formTextColor'],
            'sbs-cpf-bgr-color'           => $settings['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formBackgroundColor'],
            'sbs-cpf-text-color'          => $settings['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formTextColor'],
            'sbs-cpf-input-color'         => $settings['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formInputColor'],
            'sbs-cpf-input-text-color'    => $settings['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formInputTextColor'],
            'sbs-cpf-dropdown-color'      => $settings['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formDropdownColor'],
            'sbs-cpf-dropdown-text-color' => $settings['forms']['stepByStepForm']['confirmBookingForm']['package']['globalSettings']['formDropdownTextColor'],
            'sbs-coa-bgr-color'           => $settings['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formBackgroundColor'],
            'sbs-coa-text-color'          => $settings['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formTextColor'],
            'sbs-coa-input-color'         => $settings['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formInputColor'],
            'sbs-coa-input-text-color'    => $settings['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formInputTextColor'],
            'sbs-coa-dropdown-color'      => $settings['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownColor'],
            'sbs-coa-dropdown-text-color' => $settings['forms']['stepByStepForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownTextColor'],
            'sbs-cop-bgr-color'           => $settings['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formBackgroundColor'],
            'sbs-cop-text-color'          => $settings['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formTextColor'],
            'sbs-cop-input-color'         => $settings['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formInputColor'],
            'sbs-cop-input-text-color'    => $settings['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formInputTextColor'],
            'sbs-cop-dropdown-color'      => $settings['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formDropdownColor'],
            'sbs-cop-dropdown-text-color' => $settings['forms']['stepByStepForm']['congratulationsForm']['package']['globalSettings']['formDropdownTextColor'],
            'elf-bgr-color'               => $settings['forms']['eventListForm']['globalSettings']['formBackgroundColor'],
            'elf-text-color'              => $settings['forms']['eventListForm']['globalSettings']['formTextColor'],
            'elf-input-color'             => $settings['forms']['eventListForm']['globalSettings']['formInputColor'],
            'elf-input-text-color'        => $settings['forms']['eventListForm']['globalSettings']['formInputTextColor'],
            'elf-dropdown-color'          => $settings['forms']['eventListForm']['globalSettings']['formDropdownColor'],
            'elf-dropdown-text-color'     => $settings['forms']['eventListForm']['globalSettings']['formDropdownTextColor'],
            'cf-ssf-bgr-color'           => $settings['forms']['catalogForm']['selectServiceForm']['globalSettings']['formBackgroundColor'],
            'cf-ssf-text-color'          => $settings['forms']['catalogForm']['selectServiceForm']['globalSettings']['formTextColor'],
            'cf-ssf-input-color'         => $settings['forms']['catalogForm']['selectServiceForm']['globalSettings']['formInputColor'],
            'cf-ssf-input-text-color'    => $settings['forms']['catalogForm']['selectServiceForm']['globalSettings']['formInputTextColor'],
            'cf-ssf-dropdown-color'      => $settings['forms']['catalogForm']['selectServiceForm']['globalSettings']['formDropdownColor'],
            'cf-ssf-dropdown-text-color' => $settings['forms']['catalogForm']['selectServiceForm']['globalSettings']['formDropdownTextColor'],
            'cf-cf-gradient1'            => $settings['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formGradientColor1'],
            'cf-cf-gradient2'            => $settings['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formGradientColor2'],
            'cf-cf-gradient-angle'       => $settings['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formGradientAngle'].'deg',
            'cf-cf-text-color'           => $settings['forms']['catalogForm']['calendarDateTimeForm']['globalSettings']['formTextColor'],
            'cf-rsf-gradient1'           => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formGradientColor1'],
            'cf-rsf-gradient2'           => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formGradientColor2'],
            'cf-rsf-gradient-angle'      => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formGradientAngle'].'deg',
            'cf-rsf-text-color'          => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formTextColor'],
            'cf-rsf-input-color'         => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formInputColor'],
            'cf-rsf-input-text-color'    => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formInputTextColor'],
            'cf-rsf-dropdown-color'      => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formDropdownColor'],
            'cf-rsf-dropdown-text-color' => $settings['forms']['catalogForm']['recurringSetupForm']['globalSettings']['formDropdownTextColor'],
            'cf-rdf-bgr-color'           => $settings['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formBackgroundColor'],
            'cf-rdf-text-color'          => $settings['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formTextColor'],
            'cf-rdf-input-color'         => $settings['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formInputColor'],
            'cf-rdf-input-text-color'    => $settings['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formInputTextColor'],
            'cf-rdf-dropdown-color'      => $settings['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formDropdownColor'],
            'cf-rdf-dropdown-text-color' => $settings['forms']['catalogForm']['recurringDatesForm']['globalSettings']['formDropdownTextColor'],
            'cf-caf-bgr-color'           => $settings['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formBackgroundColor'],
            'cf-caf-text-color'          => $settings['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formTextColor'],
            'cf-caf-input-color'         => $settings['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formInputColor'],
            'cf-caf-input-text-color'    => $settings['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formInputTextColor'],
            'cf-caf-dropdown-color'      => $settings['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownColor'],
            'cf-caf-dropdown-text-color' => $settings['forms']['catalogForm']['confirmBookingForm']['appointment']['globalSettings']['formDropdownTextColor'],
            'cf-psf-gradient1'           => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formGradientColor1'],
            'cf-psf-gradient2'           => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formGradientColor2'],
            'cf-psf-gradient-angle'      => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formGradientAngle'].'deg',
            'cf-psf-text-color'          => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formTextColor'],
            'cf-psf-input-color'         => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formInputColor'],
            'cf-psf-input-text-color'    => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formInputTextColor'],
            'cf-psf-dropdown-color'      => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formDropdownColor'],
            'cf-psf-dropdown-text-color' => $settings['forms']['catalogForm']['packageSetupForm']['globalSettings']['formDropdownTextColor'],
            'cf-plf-bgr-color'           => $settings['forms']['catalogForm']['packageListForm']['globalSettings']['formBackgroundColor'],
            'cf-plf-text-color'          => $settings['forms']['catalogForm']['packageListForm']['globalSettings']['formTextColor'],
            'cf-cpf-bgr-color'           => $settings['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formBackgroundColor'],
            'cf-cpf-text-color'          => $settings['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formTextColor'],
            'cf-cpf-input-color'         => $settings['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formInputColor'],
            'cf-cpf-input-text-color'    => $settings['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formInputTextColor'],
            'cf-cpf-dropdown-color'      => $settings['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formDropdownColor'],
            'cf-cpf-dropdown-text-color' => $settings['forms']['catalogForm']['confirmBookingForm']['package']['globalSettings']['formDropdownTextColor'],
            'cf-coa-bgr-color'           => $settings['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formBackgroundColor'],
            'cf-coa-text-color'          => $settings['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formTextColor'],
            'cf-coa-input-color'         => $settings['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formInputColor'],
            'cf-coa-input-text-color'    => $settings['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formInputTextColor'],
            'cf-coa-dropdown-color'      => $settings['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownColor'],
            'cf-coa-dropdown-text-color' => $settings['forms']['catalogForm']['congratulationsForm']['appointment']['globalSettings']['formDropdownTextColor'],
            'cf-cop-bgr-color'           => $settings['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formBackgroundColor'],
            'cf-cop-text-color'          => $settings['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formTextColor'],
            'cf-cop-input-color'         => $settings['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formInputColor'],
            'cf-cop-input-text-color'    => $settings['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formInputTextColor'],
            'cf-cop-dropdown-color'      => $settings['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formDropdownColor'],
            'cf-cop-dropdown-text-color' => $settings['forms']['catalogForm']['congratulationsForm']['package']['globalSettings']['formDropdownTextColor'],
        ];


        $hash = $lessParserService->compileAndSave(
            array_merge(
                [
                    'color-accent'                => $settings['primaryColor'],
                    'color-gradient1'             => $settings['primaryGradient1'] ? $settings['primaryGradient1'] : '#FFFFFF',
                    'color-gradient2'             => $settings['primaryGradient2'] ? $settings['primaryGradient2'] : '#FFFFFF',
                    'color-text-prime'            => $settings['textColor'],
                    'color-text-second'           => $settings['textColor'],
                    'color-white'                 => $settings['textColorOnBackground'],
                    'roboto'                      => $settings['font'],
                ],
                $settingsForm
            )
        );

        $settings['hash'] = $hash;

        self::initSettings('customization', $settings, true);
    }

    /**
     * Init Labels Settings
     */
    private static function initLabelsSettings()
    {
        $settings = [
            'enabled'   => true,
            'employee'  => 'employee',
            'employees' => 'employees',
            'service'   => 'service',
            'services'  => 'services'
        ];

        self::initSettings('labels', $settings);
    }

    /**
     * Init Roles Settings
     */
    private static function initRolesSettings()
    {
        $settings = [
            'allowConfigureSchedule'      => false,
            'allowConfigureDaysOff'       => false,
            'allowConfigureSpecialDays'   => false,
            'allowConfigureServices'      => false,
            'allowWriteAppointments'      => false,
            'automaticallyCreateCustomer' => false,
            'inspectCustomerInfo'         => false,
            'allowCustomerReschedule'     => false,
            'allowCustomerDeleteProfile'  => false,
            'allowWriteEvents'            => false,
            'enabledHttpAuthorization'    => true,
            'customerCabinet'             => [
                'enabled'         => true,
                'headerJwtSecret' => (new Token(null, 20))->getValue(),
                'urlJwtSecret'    => (new Token(null, 20))->getValue(),
                'tokenValidTime'  => 2592000,
                'pageUrl'         => '',
                'loginEnabled'    => true,
                'filterDate'      => false,
                'translations'    => [],
            ],
            'providerCabinet'             => [
                'enabled'         => true,
                'headerJwtSecret' => (new Token(null, 20))->getValue(),
                'urlJwtSecret'    => (new Token(null, 20))->getValue(),
                'tokenValidTime'  => 2592000,
                'pageUrl'         => '',
                'loginEnabled'    => true,
                'filterDate'      => false,
            ],
            'urlAttachment'       => [
                'enabled'         => true,
                'headerJwtSecret' => (new Token(null, 20))->getValue(),
                'urlJwtSecret'    => (new Token(null, 20))->getValue(),
                'tokenValidTime'  => 2592000,
                'pageUrl'         => '',
                'loginEnabled'    => true,
                'filterDate'      => false,
            ],
        ];

        self::initSettings('roles', $settings);

        self::setNewSettingsToExistingSettings(
            'roles',
            [
                ['customerCabinet', 'filterDate'],
                ['customerCabinet', 'translations'],
            ],
            $settings
        );
    }

    /**
     * Init Appointments Settings
     */
    private static function initAppointmentsSettings()
    {
        $settings = [
            'isGloballyBusySlot'                => false,
            'allowBookingIfPending'             => true,
            'allowBookingIfNotMin'              => true,
            'openedBookingAfterMin'             => false,
            'recurringPlaceholders'             => 'DateTime: %appointment_date_time%',
            'recurringPlaceholdersSms'          => 'DateTime: %appointment_date_time%',
            'recurringPlaceholdersCustomer'     => 'DateTime: %appointment_date_time%',
            'recurringPlaceholdersCustomerSms'  => 'DateTime: %appointment_date_time%',
            'packagePlaceholders'               => 'DateTime: %appointment_date_time%',
            'packagePlaceholdersSms'            => 'DateTime: %appointment_date_time%',
            'packagePlaceholdersCustomer'       => 'DateTime: %appointment_date_time%',
            'packagePlaceholdersCustomerSms'    => 'DateTime: %appointment_date_time%',
            'translations'                      => [
                'recurringPlaceholdersCustomer'    => null,
                'recurringPlaceholdersCustomerSms' => null,
                'packagePlaceholdersCustomer'      => null,
                'packagePlaceholdersCustomerSms'   => null,
            ],
        ];

        self::initSettings('appointments', $settings);
    }

    /**
     * Init Web Hooks Settings
     */
    private static function initWebHooksSettings()
    {
        $settings = [];

        self::initSettings('webHooks', $settings);
    }

    /**
     * Add new settings ti global parent settings
     *
     * @param string $category
     * @param array  $pathsKeys
     * @param array  $initSettings
     */
    private static function setNewSettingsToExistingSettings($category, $pathsKeys, $initSettings)
    {
        $settingsService = new SettingsService(new SettingsStorage());

        $savedSettings = $settingsService->getCategorySettings($category);

        $setSettings = false;

        foreach ($pathsKeys as $keys) {
            $current = &$savedSettings;
            $currentInit = &$initSettings;

            foreach ((array)$keys as $key) {
                if (!isset($current[$key])) {
                    $current[$key] = !empty($currentInit[$key]) ? $currentInit[$key] : null;
                    $setSettings = true;

                    continue 2;
                }

                $current = &$current[$key];
                $currentInit = &$initSettings[$key];
            }
        }

        if ($setSettings) {
            self::initSettings($category, $savedSettings, true);
        }
    }
}
