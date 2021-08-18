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
 * @class      turitop_booking_system_round_trip
 * @package    turitop
 * @since      Version 1.0.0
 * @author	   Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'turitop_booking_system_round_trip' ) ) {
	/**
	 * Class turitop_booking_system_round_trip
	 *
	 * @author Daniel Sánchez Sáez <dssaez@gmail.com>
	 */
	class turitop_booking_system_round_trip {

		/**
		 * Main Instance
		 *
		 * @var turitop_booking_system_round_trip
		 * @since  1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @access protected
		 */
		protected static $_instance = null;

        /**
		 * array_errors
		 *
		 * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access protected
		 */
		protected $array_errors = array();

        /**
		 * slug
		 *
		 * @var string
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $slug = null;

        /**
		 * args
		 *
		 * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $args = array();

        /**
		 * turitop booking system data
		 *
		 * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $tbs_data = array();

        /**
		 * common_translations
		 *
		 * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $common_translations = array();

    /**
		 * integralwebsite_inputs_form
		 *
		 * @var instance admin menu inputs
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $integralwebsite_inputs_form = null;

    /**
		 * integralwebsite_inputs_form
		 *
		 * @var instance admin menu inputs
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $integralwebsite_inputs_form_styles = null;

        /**
		 * Main Instance
		 *
		 * @var turitop_booking_system_round_trip
		 * @since  1.0.0
		 * @access protected
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

        /**
         * __construct
         *
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
		public function __construct() {

      $this->init();

      add_filter( 'integralwebsite_wp_inputs_data_to_store', array( $this, 'integralwebsite_wp_inputs_data_to_store_call_back' ), 10, 1 );

      add_action( 'integralwebsite_wp_inputs_check_form_submited_after', array( $this, 'integralwebsite_wp_inputs_check_form_submited_after_call_back' ), 10, 1 );

      $this->integralwebsite_inputs_form->init();

      $this->display_general_settings();

      $this->integralwebsite_inputs_form_styles->init();

      $this->display_style_settings();

		}

    /**
     * generate_dynamic_site_css
     *
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @since 1.0.0
     * @access public
     * @param
     * @return void
     *
     */
    public function integralwebsite_wp_inputs_check_form_submited_after_call_back( $data ) {

      if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'round_trip_styles' ){

        TURITOP_BS()->generate_dynamic_site_css();
      }

    }

        /**
         * init
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function init() {

            $this->common_translations = TURITOP_BS()->common_translations;

            $this->slug = TURITOP_BOOKING_SYSTEM_SERVICE_DATA;

            $this->inputs = array(

                //ROUND TRIP BOOKING
                'round_trip_activate' => array(
                    'input_type' => 'checkbox',
                    'input_description' => _x( 'Check this option to activate the Round Trip Booking', 'common translations', 'turitop-booking-system' ),
                ),
                'round_trip_data' => array(
                    'input_type' => 'textarea',
                    'input_class' => 'integralwebsite_textarea_custom_css',
                ),
                'round_trip_message_below' => array(
                    'input_type' => 'textarea',
                    'input_value' => _x( 'Select your destination', 'common translations', 'turitop-booking-system' ),
                ),

            );

            $args = array(
                'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
                'type' => array(
                    'value' => 'option',
                ),
                'inputs' => $this->inputs,
                'slug' => $this->slug,
                'common_translations' => $this->common_translations,
            );
            $this->integralwebsite_inputs_form = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->inputs_form( $args );

            // STYLE INPUTS

            $this->slug_styles = TURITOP_BOOKING_SYSTEM_SERVICE_STYLES_DATA;

            $this->inputs_styles = array(
              'integralwebsite_check_form' => array(
                  'input_type' => 'hidden',
                  'input_value' => 'round_trip_styles',
              ),
              'color_radio_button_ticket' => array(
                'input_class' => 'integralwebsite_input_color_picker',
                'input_type' => 'text_alpha_color',
                'title' => _x( 'Radio button color', 'turitop settings', 'turitop-booking-system' ),
                'description' => _x( 'Choose the color of the radio button when choosing the type of the ticket (round trip or one way)', 'turitop settings', 'turitop-booking-system' ),
              ),

            );

            $this->style_elements = TURITOP_BS()->get_round_trip_style_elements();

            foreach ( $this->style_elements as $element ) {
              $this->inputs_styles = array_merge( $this->inputs_styles, TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_css_inputs( $element ) );
              if ( isset( $element[ 'hover' ] ) && $element[ 'hover' ] == 'yes' )
                $this->inputs_styles = array_merge( $this->inputs_styles, TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_css_inputs_hover( $element ) );
            }

            $args = array(
                'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
                'type' => array(
                    'value' => 'option',
                ),
                'inputs' => $this->inputs_styles,
                'slug' => $this->slug_styles,
                'common_translations' => $this->common_translations,
            );
            $this->integralwebsite_inputs_form_styles = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->inputs_form( $args );

        }

        /**
         * save dynamic css file call back
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function integralwebsite_wp_inputs_data_to_store_call_back( $data ) {

          if ( isset( $data[ 'round_trip_data' ] ) && ! empty( $data[ 'round_trip_data' ] ) == 'yes' ){

            $round_trip_data = array();

            $round_trip_array = explode( "\n",  $data[ 'round_trip_data' ] );
            foreach ( $round_trip_array as $trip ) {

              $array_trip = explode( ",",  $trip );
              if ( is_array( $array_trip ) && ! empty( $array_trip ) && count( $array_trip ) == 4 ){

                $round_trip_data[] = array(
                  'from' => $array_trip[ 0 ],
                  'to' => $array_trip[ 1 ],
                  'outbound' => $array_trip[ 2 ],
                  'return' => $array_trip[ 3 ],
                );

              }
            }
            $data[ 'round_trip_data_trips' ] = $round_trip_data;
            
          }

          return $data;

        }

        /**
         * display general settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function display_general_settings() {

            ?>

            <form action="" name="turitop_booking_system_round_trip_form" method="post">

              <h1 class="integralwebsite_main_title"><?php _ex( 'Round Trip Booking settings', 'turitop settings', 'turitop-booking-system' ); ?></h1>

              <div class="integralwebsite_main_whole_wrap_block">

                  <?php

                  $this->integralwebsite_inputs_form->create_nonce();

                  $args_to_display = array(
                      'round_trip_activate',
                  );

                  $this->integralwebsite_inputs_form->display_inputs( $args_to_display );

                  echo "<div style='position: relative'>";

                      $args_to_display = array(
                          'round_trip_data',
                          'round_trip_message_below',
                      );

                      $this->integralwebsite_inputs_form->display_inputs( $args_to_display );

                      echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_round_trip_data_wrap'></div>";

                  echo "</div>";

                  ?>

              </div>

              <button class="integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save general settings', 'turitop settings', 'turitop-booking-system' ); ?></button>

            </form>

            <?php

        }

        /**
         * display style settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function display_style_settings() {

          ?>
          <br><br>
          <form action="" name="turitop_booking_system_round_trip_form_styles" method="post">

            <h1 class="integralwebsite_main_title"><?php _ex( 'Style settings', 'turitop settings', 'turitop-booking-system' ); ?></h1>

            <div class="integralwebsite_main_whole_wrap_block">

                <?php

                $this->integralwebsite_inputs_form_styles->create_nonce();

                $args_to_display = array(
                  'integralwebsite_check_form',
                  'color_radio_button_ticket',
                );

                $this->integralwebsite_inputs_form_styles->display_inputs( $args_to_display );

                echo "<div class='integralwebsite_main_sub_wrap'>";

                    echo "<h2>" . _x( 'Main box style settings', 'turitop settings', 'Round trip settings', 'turitop-booking-system' ) . "</h2>";

                    TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->display_css_inputs( $this->integralwebsite_inputs_form_styles, $this->style_elements[ 'main_box' ] );

                    ?>
                    <button class="integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save style settings', 'turitop settings', 'turitop-booking-system' ); ?></button>
                    <?php

                echo "</div>";

                echo "<div class='integralwebsite_main_sub_wrap'>";

                    echo "<h2>" . _x( 'Tickets box style settings', 'turitop settings', 'Round trip settings', 'turitop-booking-system' ) . "</h2>";

                    TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->display_css_inputs( $this->integralwebsite_inputs_form_styles, $this->style_elements[ 'tickets_box' ] );

                    ?>
                    <button class="integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save style settings', 'turitop settings', 'turitop-booking-system' ); ?></button>
                    <?php

                echo "</div>";

                ?>

            </div>

          </form>

          <?php

        }

	}

}

$turitop_booking_system_round_trip = turitop_booking_system_round_trip::instance();
