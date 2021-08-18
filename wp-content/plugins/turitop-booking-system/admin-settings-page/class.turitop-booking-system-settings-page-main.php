<?php
/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
 if ( ! defined( 'TURITOP_BOOKING_SYSTEM_VERSION' ) ) {
     exit( 'Direct access forbidden.' );
 }

/**
 *
 *
 * @class      turitop_booking_system_master_slave_admin
 * @package    Simpledevel
 * @since      Version 1.0.0
 * @author
 *
 */

if ( ! class_exists( 'turitop_booking_system_settings_page_main' ) ) {
    /**
     *
     * @author
     */
    class turitop_booking_system_settings_page_main {

        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Main Instance
         *
         * @var _instance
         * @since  1.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access protected
         */
        protected static $_instance = null;

        public function __construct() {

            // Activate admin page
            add_action( 'admin_menu', array( $this, 'turitop_booking_system_add_plugin_menu_page' ) );

            //$screen_ids = array( 'toplevel_page_turitop_main' );
            //$this->integralwebsite_admin_page = new integralwebsite_wp_admin_page ( TURITOP_BOOKING_SYSTEM_VENDOR_URL, $screen_ids );

        }

        /**
         * Main plugin Instance
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0
         * @access public
         * @param
         * @return turitop_booking_system_settings_page_main instance
         *
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * create pages
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function turitop_booking_system_add_plugin_menu_page() {

            if ( apply_filters( 'turitop_booking_system_display_api_settings', false ) ){

                add_menu_page( 'Turitop', 'Turitop', 'manage_options', 'turitop_booking_system', array( $this, 'turitop_booking_system_connection' ), 'dashicons-chart-bar', 110 );

                add_submenu_page( 'turitop_booking_system', 'TuriTop Connection', _x( 'Connection', 'admin pages', 'turitop-booking-system' ), 'manage_options', 'turitop_booking_system', array( $this, 'turitop_booking_system_connection' ) );

                add_submenu_page( 'turitop_booking_system', 'TuriTop General Settings', _x( 'Settings', 'admin pages', 'turitop-booking-system' ), 'manage_options', 'turitop_booking_system_settings', array( $this, 'turitop_booking_system_general_settings' ) );

                add_submenu_page( 'turitop_booking_system', 'TuriTop Instructions', _x( 'Instructions', 'admin pages', 'turitop-booking-system' ), 'manage_options', 'turitop_booking_system_instructions', array( $this, 'turitop_booking_system_instructions' ) );

            }
            else{

                add_menu_page( 'Turitop', 'Turitop', 'manage_options', 'turitop_booking_system', array( $this, 'turitop_booking_system_general_settings' ), 'dashicons-chart-bar', 110 );

                add_submenu_page( 'turitop_booking_system', 'TuriTop General Settings', _x( 'Settings', 'admin pages', 'turitop-booking-system' ), 'manage_options', 'turitop_booking_system', array( $this, 'turitop_booking_system_general_settings' ) );

                add_submenu_page( 'turitop_booking_system', 'TuriTop Instructions', _x( 'Instructions', 'admin pages', 'turitop-booking-system' ), 'manage_options', 'turitop_booking_system_instructions', array( $this, 'turitop_booking_system_instructions' ) );

            }

        }

        /**
         * Connection page
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function turitop_booking_system_connection() {

            include TURITOP_BOOKING_SYSTEM_PATH . 'admin-settings-page/class.turitop-booking-system-connection.php';

        }

        /**
         * Settings page
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function turitop_booking_system_general_settings() {

            include TURITOP_BOOKING_SYSTEM_PATH . 'admin-settings-page/class.turitop-booking-system-settings.php';

        }

        /**
         * Instructions
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.5
         * @access public
         * @param
         * @return void
         *
         */
        public function turitop_booking_system_instructions() {

            include TURITOP_BOOKING_SYSTEM_PATH . 'admin-settings-page/class.turitop-booking-system-instructions.php';

        }

    }
}
