<?php
namespace Bookly\Backend\Modules\Notifications;

use Bookly\Lib;

/**
 * Class Page
 * @package Bookly\Backend\Modules\Notifications
 */
class Page extends Lib\Base\Component
{
    /**
     * Render page.
     */
    public static function render()
    {
        self::enqueueStyles( array(
            'alias' => array( 'bookly-backend-globals', ),
        ) );

        self::enqueueScripts( array(
            'bookly' => array( 'backend/modules/cloud_sms/resources/js/notifications-list.js' => array( 'bookly-backend-globals' ), ),
        ) );

        $datatables = Lib\Utils\Tables::getSettings( 'email_notifications' );

        wp_localize_script( 'bookly-alert.js', 'BooklyL10n', array(
            'csrfToken'        => Lib\Utils\Common::getCsrfToken(),
            'sentSuccessfully' => __( 'Sent successfully.', 'bookly' ),
            'settingsSaved'    => __( 'Settings saved.', 'bookly' ),
            'areYouSure'       => __( 'Are you sure?', 'bookly' ),
            'noResults'        => __( 'No records.', 'bookly' ),
            'processing'       => __( 'Processing...', 'bookly' ),
            'state'            => array( __( 'Disabled', 'bookly' ), __( 'Enabled', 'bookly' ) ),
            'action'           => array( __( 'enable', 'bookly' ), __( 'disable', 'bookly' ) ),
            'edit'             => __( 'Edit', 'bookly' ),
            'gateway'          => 'email',
            'datatables'      => $datatables,
        ) );

        self::renderTemplate( 'index', compact( 'datatables' ) );
    }
}