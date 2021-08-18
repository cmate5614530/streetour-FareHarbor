<?php
namespace BooklyPro\Backend\Components\TinyMce\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Components\TinyMce\Proxy;

/**
 * Class Shared
 * @package BooklyPro\Backend\Components\TinyMce\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function renderMediaButtons( $version )
    {
        if ( $version < 3.5 ) {
            // show button for v 3.4 and below
            echo '<a href="#TB_inline?width=400&amp;inlineId=bookly-tinymce-appointment-popup&amp;height=300" id="add-ap-appointment" title="' . esc_attr__( 'Add Bookly appointments list', 'bookly' ) . '">' . __( 'Add Bookly appointments list', 'bookly' ) . '</a>';
            echo '<a href="#" id="add-cancellation-confirmation" title="' . esc_attr__( 'Add appointment cancellation confirmation', 'bookly' ) . '">' . __( 'Add appointment cancellation confirmation', 'bookly' ) . '</a>';
        } else {
            // display button matching new UI
            $img = '<span class="bookly-media-icon"></span> ';
            echo '<a href="#TB_inline?width=400&amp;inlineId=bookly-tinymce-appointment-popup&amp;height=300" id="add-ap-appointment" class="thickbox button bookly-media-button" title="' . esc_attr__( 'Add Bookly appointments list', 'bookly' ) . '">' . $img . __( 'Add Bookly appointments list', 'bookly' ) . '</a>';
            echo '<button href="#" id="add-cancellation-confirmation" class="button" title="' . esc_attr__( 'Add appointment cancellation confirmation', 'bookly' ) . '">' . $img . __( 'Add appointment cancellation confirmation', 'bookly' ) . '</button>';
        }
    }

    /**
     * @inheritdoc
     */
    public static function renderPopup()
    {
        $custom_fields = (array) BooklyLib\Proxy\CustomFields::getWhichHaveData();
        self::renderTemplate( 'appointment_list', compact( 'custom_fields' ) );

        self::renderTemplate( 'cancellation_confirmation' );
    }
}