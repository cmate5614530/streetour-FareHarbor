<?php
namespace Bookly\Backend\Components\Dialogs\Staff\Order;

use Bookly\Lib;

/**
 * Class Dialog
 * @package Bookly\Backend\Components\Dialogs\Staff\Order
 */
class Dialog extends Lib\Base\Component
{
    /**
     * Render create service dialog.
     */
    public static function render()
    {
        global $wpdb;

        self::enqueueStyles( array(
            'backend' => array( 'css/fontawesome-all.min.css' => array( 'bookly-backend-globals' ), ),
        ) );

        self::enqueueScripts( array(
            'backend' => array( 'js/sortable.min.js' => array( 'bookly-backend-globals' ) ),
            'module' => array( 'js/staff-order-dialog.js' => array( 'bookly-sortable.min.js' ) ),
        ) );

        $query = Lib\Entities\Staff::query( 's' )
            ->select( 's.id, s.full_name' )
            ->tableJoin( $wpdb->users, 'wpu', 'wpu.ID = s.wp_user_id' );

        if ( ! Lib\Utils\Common::isCurrentUserAdmin() ) {
            $query->where( 's.wp_user_id', get_current_user_id() );
        }

        wp_localize_script( 'bookly-staff-order-dialog.js', 'BooklyStaffOrderDialogL10n', array(
            'csrfToken' => Lib\Utils\Common::getCsrfToken(),
            'staff' => $query->sortBy( 'position' )->fetchArray()
        ) );

        self::renderTemplate( 'dialog' );
    }
}