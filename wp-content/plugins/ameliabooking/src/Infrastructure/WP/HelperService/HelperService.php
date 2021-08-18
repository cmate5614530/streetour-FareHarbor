<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\HelperService;

/**
 * Class HelperService
 *
 * @package AmeliaBooking\Infrastructure\WP\HelperService
 */
class HelperService
{
    public static $jsVars = [];

    /**
     * Helper method to add PHP vars to JS vars
     *
     * @param $varName
     * @param $phpVar
     */
    public static function exportJSVar($varName, $phpVar)
    {
        self::$jsVars[$varName] = $phpVar;
    }

    /**
     * Helper method to print PHP vars to JS vars
     */
    public static function printJSVars()
    {
        if (!empty(self::$jsVars)) {
            $jsBlock = '<script type="text/javascript">';
            foreach (self::$jsVars as $varName => $jsVar) {
                $jsBlock .= "var {$varName} = " . json_encode($jsVar) . ';';
            }
            $jsBlock .= '</script>';
            echo $jsBlock;
        }
    }
}
