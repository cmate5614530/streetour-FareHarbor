<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'FareHarbor_BS' ) ) {

    function FareHarbor_BS() {

        // TEXT DOMAIN
        load_plugin_textdomain( 'streetours-fareharbor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        // Load required classes and functions
        require_once( 'streetours-fareharbor.php' );
        return StreetoursFareharbor_Plugin::instance();

    }

}

if ( ! function_exists( 'Fareharbor_booking_system_init' ) ) {

    function Fareharbor_booking_system_init() {

        FareHarbor_BS();

    }

}

//add_action( 'plugins_loaded', 'Fareharbor_booking_system_init', 11 );