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
 * @class      turitop_booking_system_connection
 * @package    turitop
 * @since      Version 1.0.1
 * @author	   Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'turitop_booking_system_connection' ) ) {
	/**
	 * Class turitop_booking_system_connection
	 *
	 * @author Daniel Sánchez Sáez <dssaez@gmail.com>
	 */
	class turitop_booking_system_connection {

		/**
		 * Main Instance
		 *
		 * @var turitop_booking_system_connection
		 * @since  1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @access protected
		 */
		protected static $_instance = null;

        /**
		 * array_errors
		 *
		 * @var array
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access protected
		 */
		protected $array_errors = array();

        /**
		 * slug
		 *
		 * @var string
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $slug = null;

        /**
		 * args
		 *
		 * @var array
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $args = array();

        /**
		 * turitop booking system data
		 *
		 * @var array
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $tbs_data = array();

        /**
		 * common_translations
		 *
		 * @var array
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $common_translations = array();

    /**
		 * integralwebsite_inputs_form
		 *
		 * @var instance admin menu inputs
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $integralwebsite_inputs_form = null;

        /**
		 * Main Instance
		 *
		 * @var turitop_booking_system_connection
		 * @since  1.0.1
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
         * @since 1.0.1
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
		public function __construct() {

            $this->init();

            //add_filter( 'integralwebsite_wp_inputs_single_error', array( $this, 'integralwebsite_wp_inputs_single_error_call_back' ), 10, 4 );

            add_filter( 'integralwebsite_wp_inputs_data_to_store', array( $this, 'integralwebsite_wp_inputs_data_to_store_call_back' ), 10, 1 );

            $this->integralwebsite_inputs_form->init();

            $this->display_settings();

		}

        /**
         * init
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function init() {

            $this->common_translations = TURITOP_BS()->common_translations;

            $this->slug = TURITOP_BOOKING_SYSTEM_SERVICE_DATA;

            $this->inputs = array(
                // CONNECTION
                'company' => array(
                    'input_type' => 'text',
                ),
                'secret_key' => array(
                    'input_type' => 'text',
                ),
                'status_pending' => array(
                    'title' => 'Status',
                    'text' => _x( 'Pending to connect', 'TuriTop connection', 'turitop-booking-system' ),
                    'text_dashicons' => 'dashicons-warning',
                    'text_class' => 'integralwebsite_status_text'
                ),
                'status_ok' => array(
                    'title' => 'Status',
                    'text' => _x( 'Credentials ok', 'TuriTop connection', 'turitop-booking-system' ),
                    'text_dashicons' => 'dashicons-yes',
                    'text_class' => 'integralwebsite_status_text'
                ),
                'status_wrong_credentials' => array(
                    'title' => 'Status',
                    'text' => _x( 'Wrong credentials', 'TuriTop connection', 'turitop-booking-system' ),
                    'text_dashicons' => 'dashicons-info',
                    'text_class' => 'integralwebsite_status_text'
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

        }

        /**
         * errors call back
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function integralwebsite_wp_inputs_single_error_call_back( $error, $key, $slug, $data ) {

            $errors = '';

            if ( $key == 'company' && empty( $_POST[ $slug . "_" . $key ] ) )
                $errors = $errors . _x( 'The TuriTop company ID has been saved as empty, the booking system will not be displayed', 'TuriTop connection', 'turitop-booking-system' );

            if ( $key == 'secret_key' && empty( $_POST[ $slug . "_" . $key ] ) ){
                $errors = ( empty( $errors ) ? $errors : $errors . "<br/>" );
                $errors = $errors . _x( 'The TuriTop Secret Key has been saved as empty, the services will not be syncrhonized', 'TuriTop connection', 'turitop-booking-system' );
            }

            return $errors;

        }

        /**
         * checking the credentials data to store
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function integralwebsite_wp_inputs_data_to_store_call_back( $data ) {

            require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-api-client.php' );
            $api_client = new turitop_booking_system_api_client();

            $company_id = ( isset( $data[ 'company' ] ) ? $data[ 'company' ] : 0 );
            $secret_key = ( isset( $data[ 'secret_key' ] ) ? $data[ 'secret_key' ] : 0 );

            $args = array(
                'short_id'    => $company_id,
                'secret_key'  => $secret_key,
            );

            $result = $api_client->connect_to_turitop_api( $args );

            $data[ 'credentials' ] = 'ok';

            if ( ! isset( $result[ 'code' ] ) || $result[ 'code' ] != '200' ){

              ?>

                  <div class="error settings-error notice is-dismissible">

                      <?php

                         echo "<p>" . _x( 'The TuriTop Company ID or The TuriTop Secret Key are not correct, the connection between your WordPress installation and TuriTop could not be done.', 'TuriTop connection', 'turitop-booking-system' ) . "</p>";

                      ?>

                  </div>

              <?php

              $data[ 'credentials' ] = 'wrong_credentials';

            }

            return $data;

        }

        /**
         * display settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param
         * @return void
         *
         */
        public function display_settings() {

            ?>

            <form action="" name="turitop_booking_system_connection_form" method="post">

              <h1 class="integralwebsite_main_title"><?php _ex( 'Connection', 'TuriTop conenction', 'turitop-booking-system' ); ?></h1>

              <div class="integralwebsite_main_whole_wrap_block">

                  <?php

          			     $this->integralwebsite_inputs_form->create_nonce();

                      $args_to_display = array(
                          'company',
                          'secret_key',
                      );

                      $this->tbs_data = TURITOP_BS()->get_tbs_data( true );

                      if ( ! isset( $this->tbs_data[ 'credentials' ] ) )
                        $args_to_display[] = 'status_pending';
                      else if ( $this->tbs_data[ 'credentials' ] == 'ok' )
                              $args_to_display[] = 'status_ok';
                           else
                              $args_to_display[] = 'status_wrong_credentials';

                      $this->integralwebsite_inputs_form->display_inputs( $args_to_display );

          		    ?>

              </div>

              <button class="integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Connect to TuriTop', 'TuriTop connection', 'turitop-booking-system' ); ?></button>

            </form>

            <?php

        }

	}

}

$turitop_booking_system_connection = turitop_booking_system_connection::instance();
