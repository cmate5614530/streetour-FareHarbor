<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\ButtonService;

use AmeliaBooking\Infrastructure\WP\HelperService\HelperService;
use AmeliaBooking\Infrastructure\WP\Translations\BackendStrings;

/**
 * Class ButtonService
 *
 * @package AmeliaBooking\Infrastructure\WP\ShortcodeService
 */
class ButtonService
{
    /**
     * Function that adds shortcode button to WordPress TinyMCE editor on Page and Post pages
     */
    public static function renderButton()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') !== 'true') {
            return;
        }

        add_filter('mce_external_plugins', [self::class, 'addButton']);
        add_filter('mce_buttons', [self::class, 'registerButton']);
        // Export JS variables for classic editor
        add_action('after_wp_tiny_mce', [self::class, 'exportVars']);
        // Export JS variables for Gutenberg editor
        add_action('admin_enqueue_scripts', [self::class, 'exportVars']);
    }

    /**
     * Function that add buttons for MCE editor
     *
     * @param $pluginArray
     *
     * @return mixed
     */
    public static function addButton($pluginArray)
    {
        $pluginArray['ameliaBookingPlugin'] = AMELIA_URL . 'public/js/tinymce/amelia-mce.js';

        return $pluginArray;
    }

    /**
     * Function that register buttons for MCE editor
     *
     * @param $buttons
     *
     * @return mixed
     */
    public static function registerButton($buttons)
    {
        $buttons[] = 'ameliaButton';

        return $buttons;
    }

    public static function exportVars()
    {
        HelperService::exportJSVar('wpAmeliaPluginURL', AMELIA_URL);
        HelperService::exportJSVar(
            'wpAmeliaLabels',
            array_merge(
                BackendStrings::getCommonStrings(),
                BackendStrings::getWordPressStrings()
            )
        );

        HelperService::printJSVars();
    }
}
