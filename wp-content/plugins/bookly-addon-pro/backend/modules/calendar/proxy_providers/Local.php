<?php
namespace BooklyPro\Backend\Modules\Calendar\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Calendar\Proxy;
use BooklyPro\Lib;

/**
 * Class Local
 * @package BooklyPro\Backend\Modules\Calendar\ProxyProviders
 */
class Local extends Proxy\Pro
{
    /**
     * @inheritDoc
     */
    public static function renderServicesFilterOption()
    {
        echo sprintf( '<li data-value="custom">%s</li>', esc_attr__( 'Custom', 'bookly' ) );
    }
}