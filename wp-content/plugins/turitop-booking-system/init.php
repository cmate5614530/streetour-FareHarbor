<?php
/*
Plugin Name: Turitop Booking System
Plugin URI: https://turitop.com/wordpress-turitop-booking-system
Description: <a href="https://www.turitop.com"> TuriTop </a> is an integral platform for the management of your reservations, no matter if it made from your web, third party web, from your social networks or directly (phone, email, agency). We offer a fully responsive control panel for easy and quick management.
Author: TuriTop
Text Domain: turitop-booking-system
Version: 1.0.9
Author URI: https://www.turitop.com
*/

/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/*
==============================
           DEFINE
==============================
*/

! defined( 'TURITOP_BOOKING_SYSTEM_VERSION' ) && define( 'TURITOP_BOOKING_SYSTEM_VERSION', '1.0.9' );
! defined( 'TURITOP_BOOKING_SYSTEM_SLUG' ) && define( 'TURITOP_BOOKING_SYSTEM_SLUG', 'turitop-booking-system' );
! defined( 'TURITOP_BOOKING_SYSTEM_INIT' ) && define( 'TURITOP_BOOKING_SYSTEM_INIT', plugin_basename( __FILE__ ) );
! defined( 'TURITOP_BOOKING_SYSTEM_PATH' ) && define( 'TURITOP_BOOKING_SYSTEM_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'TURITOP_BOOKING_SYSTEM_TEMPLATE_PATH' ) && define( 'TURITOP_BOOKING_SYSTEM_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'templates/' );
! defined( 'TURITOP_BOOKING_SYSTEM_VENDOR_PATH' ) && define( 'TURITOP_BOOKING_SYSTEM_VENDOR_PATH', plugin_dir_path( __FILE__ ) . 'vendor/' );
! defined( 'TURITOP_BOOKING_SYSTEM_ASSETS_URL' ) && define( 'TURITOP_BOOKING_SYSTEM_ASSETS_URL', plugins_url( '/', __FILE__ ) . 'assets' );
! defined( 'TURITOP_BOOKING_SYSTEM_VENDOR_URL' ) && define( 'TURITOP_BOOKING_SYSTEM_VENDOR_URL', plugins_url( '/', __FILE__ ) . 'vendor/' );
! defined( 'TURITOP_BOOKING_SYSTEM_JS_URL' ) && define( 'TURITOP_BOOKING_SYSTEM_JS_URL', 'https://app.turitop.com/js/load-turitop.min.js' );
//! defined( 'TURITOP_BOOKING_SYSTEM_JS_URL' ) && define( 'TURITOP_BOOKING_SYSTEM_JS_URL', TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/js/load-turitop.js' );

! defined( 'TURITOP_BOOKING_SYSTEM_SERVICE_CPT' ) && define( 'TURITOP_BOOKING_SYSTEM_SERVICE_CPT', 'turitop_service' );
! defined( 'TURITOP_BOOKING_SYSTEM_SERVICE_DATA' ) && define( 'TURITOP_BOOKING_SYSTEM_SERVICE_DATA', 'turitop_booking_system_settings' );
! defined( 'TURITOP_BOOKING_SYSTEM_BUTTON_DATA' ) && define( 'TURITOP_BOOKING_SYSTEM_BUTTON_DATA', 'turitop_booking_system_button' );
! defined( 'TURITOP_BOOKING_SYSTEM_CART_DATA' ) && define( 'TURITOP_BOOKING_SYSTEM_CART_DATA', 'turitop_booking_system_cart' );
! defined( 'TURITOP_BOOKING_SYSTEM_SERVICE_PAGES_DATA' ) && define( 'TURITOP_BOOKING_SYSTEM_SERVICE_PAGES_DATA', 'turitop_booking_system_service_pages' );
! defined( 'TURITOP_BOOKING_SYSTEM_ADVANCED_DATA' ) && define( 'TURITOP_BOOKING_SYSTEM_ADVANCED_DATA', 'turitop_booking_system_advance' );
! defined( 'TURITOP_BOOKING_SYSTEM_SERVICE_STYLES_DATA' ) && define( 'TURITOP_BOOKING_SYSTEM_SERVICE_STYLES_DATA', 'turitop_booking_system_styles' );

/*
==============================
       INTEGRALWEBSITE FUNCTION
==============================
*/

if ( ! function_exists( 'TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS' ) ) {
	/**
	 * Get the main theme class
	 *
	 * @author Daniel Sanchez Saez
	 * @since  1.0.0
	 */
	function TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS() {

		// Load functions class
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            require_once TURITOP_BOOKING_SYSTEM_VENDOR_PATH . '/integralwebsite-lib/class.integralwebsite-functions.php';
		$args = array(
			'path'       => TURITOP_BOOKING_SYSTEM_VENDOR_PATH . "/integralwebsite-lib",
			'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL . "/integralwebsite-lib",
			'version' 	 => TURITOP_BOOKING_SYSTEM_VERSION,
			'slug' 			 => TURITOP_BOOKING_SYSTEM_SLUG,
		);
		return integralwebsite_functions::instance( $args );

	}

}

/*
==============================
        MAIN FUNCTION
==============================
*/

if ( ! function_exists( 'TURITOP_BS' ) ) {
	/**
	 * Get the main plugin class
	 *
	 * @author Daniel Sanchez Saez
	 * @since  1.0.0
	 */
	function TURITOP_BS() {

		// TEXT DOMAIN
		load_plugin_textdomain( 'turitop-booking-system', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load required classes and functions
		require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-main.php' );
		return turitop_bokking_system_main::instance();

	}

}

if ( ! function_exists( 'turitop_booking_system_init' ) ) {

	function turitop_booking_system_init() {

		TURITOP_BS();

	}

}

add_action( 'plugins_loaded', 'turitop_booking_system_init', 11 );
