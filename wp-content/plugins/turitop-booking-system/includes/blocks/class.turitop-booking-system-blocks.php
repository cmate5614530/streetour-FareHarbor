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
 * @class      turitop_booking_system_blocks
 * @package    turitop
 * @since      Version 1.0.0
 * @author     Daniel Sánchez Sáez
 *
 */

if ( ! class_exists( 'turitop_booking_system_blocks' ) ) {
    /**
     * Class turitop_booking_system_blocks
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     * @since  1.0.0
     *
     */
    class turitop_booking_system_blocks {

        /**
         * Main Instance
         *
         * @var _instance
         * @since  1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access protected
         */
        protected static $_instance = null;

        /**
         * Main Admin Instance
         *
         * @var admin
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         */
        public $admin = null;

        /**
         * Main Frontend Instance
         *
         * @var frontend
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         */
        public $frontend = null;

        /**
         * __construct
         *
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
        public function __construct() {

            /* == Plugins Init === */
            $this->init();
        }

        /**
         * Main plugin Instance
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return turitop_booking_system_blocks main instance
         *
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * Class Initializzation
         *
         * Instance the admin or frontend classes
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function init() {

            $this->turitop_booking_system_block_init();

        }

        /**
         * turitop_booking_system_block_init
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function turitop_booking_system_block_init() {

          if ( ! function_exists( 'register_block_type' ) ) {
      		    return;
        	}

        	// Register our block editor script.
        	wp_register_script(
        		'turitop_bs_block',
        		TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/js/turitop-bs-block.js',
        		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
        	);

        	$tbs_data = TURITOP_BS()->get_tbs_data();

          $tbs_data[ 'button_text' ] = ( isset( $tbs_data[ 'button_text' ] ) ? $tbs_data[ 'button_text' ] : __( 'Book now', 'main settings', 'turitop-booking-system' ) );
          $tbs_data[ 'company' ] = ( isset( $tbs_data[ 'company' ] ) ? $tbs_data[ 'company' ] : '' );
          $tbs_data[ 'embed' ] = ( isset( $tbs_data[ 'embed' ] ) ? $tbs_data[ 'embed' ] : 'box' );
          $tbs_data[ 'buttoncolor' ] = ( isset( $tbs_data[ 'buttoncolor' ] ) ? $tbs_data[ 'buttoncolor' ] : 'default' );
          $tbs_data[ 'button_custom_class' ] = ( isset( $tbs_data[ 'button_custom_class' ] ) ? $tbs_data[ 'button_custom_class' ] : '' );
          $tbs_data[ 'button_image_id' ] = ( isset( $tbs_data[ 'button_image_id' ] ) ? $tbs_data[ 'button_image_id' ] : '' );
          $tbs_data[ 'button_image_url' ] = ( isset( $tbs_data[ 'button_image_url' ] ) ? $tbs_data[ 'button_image_url' ] : '' );
          $tbs_data[ 'wc_exist' ] = 'no';

          if ( function_exists( 'WC' ) ) {
              $tbs_data[ 'wc_exist' ] = 'yes';
          }

          $common_translations = TURITOP_BS()->common_translations;

          $embed_options = array(
            array(
              'value' => 'box',
              'text' => $common_translations[ 'box' ],
            ),
            array(
              'value' => 'button',
              'text' => $common_translations[ 'button' ],
            ),
            array(
              'value' => 'gift',
              'text' => $common_translations[ 'gift' ],
            ),
            array(
              'value' => 'redeemgv',
              'text' => $common_translations[ 'redeem' ],
            ),
          );

          if ( TURITOP_BS()->get_version_services() == 'yes' ){
            $embed_options[] = array(
              'value' => 'details_and_box',
              'text' => $common_translations[ 'details_and_box' ],
            );
            $embed_options[] = array(
              'value' => 'details_and_button',
              'text' => $common_translations[ 'details_and_button' ],
            );
          }

          if ( TURITOP_BS()->get_round_trip_booking() == 'yes' ){
            $embed_options[] = array(
              'value' => 'round_trip',
              'text' => $common_translations[ 'round_trip' ],
            );
          }

        	wp_localize_script( 'turitop_bs_block', 'tbs_object', apply_filters( 'turitop_bs_block_filter', array(
        		'tbs_data' => $tbs_data,
            'common_translations' => $common_translations,
            'embed_options' => $embed_options,
            'version_services' => TURITOP_BS()->get_version_services(),
        	) ) );

        	// Register our block, and explicitly define the attributes we accept.
        	register_block_type( 'turitop/turitop-booking-system', array(
        		'attributes'      => array(
                'company' => array(
          				'type' => 'string',
          			),
          			'product_id' => array(
          				'type' => 'string',
          			),
                'wc_product_id' => array(
          				'type' => 'string',
          			),
                'embed' => array(
          				'type' => 'select',
          			),
                'layout' => array(
          				'type' => 'select',
          			),
                'content_service' => array(
          				'type' => 'select',
          			),
                'button_text' => array(
          				'type' => 'select',
          			),
                'buttoncolor' => array(
          				'type' => 'select',
          			),
                'button_custom_class' => array(
          				'type' => 'string',
          			),
                'button_image_activate' => array(
          				'type' => 'select',
          			),
                'button_image_default' => array(
          				'type' => 'select',
          			),
                'button_image_url' => array(
          				'type' => 'string',
          			),
                'className' => array(
          				'type' => 'string',
          			),
        		),
        		'editor_script'   => 'turitop_bs_block', // The script name we gave in the wp_register_script() call.
        		'render_callback' => array( TURITOP_BS()->shortcodes, 'display_turitop_booking_system' ),
        	) );

          // Register javascript block checker
        	/*wp_register_script(
        		'turitop_bs_block_checker',
        		TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/js/turitop-bs-block-checker.js',
        		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' )
        	);

          wp_enqueue_script( 'turitop_bs_block_checker' );*/

        }

    }
}
