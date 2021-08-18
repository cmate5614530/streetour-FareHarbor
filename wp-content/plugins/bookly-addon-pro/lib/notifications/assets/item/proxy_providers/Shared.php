<?php
namespace BooklyPro\Lib\Notifications\Assets\Item\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Lib\Entities\CustomerAppointment;
use Bookly\Lib\Notifications\Assets\Item\Codes;
use Bookly\Lib\Notifications\Assets\Item\Proxy;

/**
 * Class Shared
 * @package BooklyPro\Lib\Notifications\Assets\Item\ProxyProviders
 */
abstract class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareCodes( Codes $codes )
    {
        $item = $codes->getItem();

        $codes->appointment_online_meeting_url = BooklyLib\Proxy\Shared::buildOnlineMeetingUrl( '', $item->getAppointment() );
        $codes->appointment_online_meeting_password = BooklyLib\Proxy\Shared::buildOnlineMeetingPassword( '', $item->getAppointment() );
        $codes->appointment_online_meeting_start_url = BooklyLib\Proxy\Shared::buildOnlineMeetingStartUrl( '', $item->getAppointment() );
        $codes->appointment_online_meeting_join_url = BooklyLib\Proxy\Shared::buildOnlineMeetingJoinUrl( '', $item->getAppointment() );
        $codes->status = $item->getCA()->getStatus();
    }

    /**
     * @inheritdoc
     */
    public static function prepareReplaceCodes( array $replace_codes, Codes $codes, $format )
    {
        $replace_codes['online_meeting_url'] = $codes->appointment_online_meeting_url;
        $replace_codes['online_meeting_password'] = $codes->appointment_online_meeting_password;
        $replace_codes['online_meeting_start_url'] = $codes->appointment_online_meeting_start_url;
        $replace_codes['online_meeting_join_url'] = $codes->appointment_online_meeting_join_url;
        $replace_codes['status'] = CustomerAppointment::statusToString( $codes->status );

        return $replace_codes;
    }
}