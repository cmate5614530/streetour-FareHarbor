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
 * @class      turitop_booking_system_api_client
 * @package    turitop
 * @since      Version 1.0.1
 * @author     Daniel Sánchez Sáez
 *
 */

if ( ! class_exists( 'turitop_booking_system_api_client' ) ) {
    /**
     * Class turitop_booking_system_api_client
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     */
    class turitop_booking_system_api_client {

        /**
         * data
         *
         * @var array
         * @since  1.0.1
         * @access public
         */
        public $data;

        /**
         * result
         *
         * @var array
         * @since  1.0.1
         * @access public
         */
        public $result;

        /**
         * error_found
         *
         * @var error_found
         * @since  1.0.1
         * @access public
         */
        public $error_found = false;

        /**
         * server_url
         *
         * @var server_url
         * @since  1.0.1
         * @access public
         */
        protected $server_url = null;

        public function __construct() {

        }

        /**
         *
         * preparing client data to grant
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function connect_to_turitop_api( $args ) {

            $this->prepare_data_to_grant( $args );

            $result = $this->send_client_data_to_server();

            if ( empty( $result ) || ! isset( $result[ 'data' ] ) )
                $this->result = array();
            else
              $this->result = array(
                  'access_token'      => $result[ 'data' ]->access_token,
                  'refresh_token'     => $result[ 'data' ]->refresh_token,
                  'company_short_id'  => $result[ 'data' ]->company_short_id,
              );

            return $result;

        }

        /**
         *
         * preparing client data to grant
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function prepare_data_to_grant( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/authorization/grant';
            $this->data = $args;

            return 1;

        }

        /**
         *
         * renew token
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function renew_token() {

            $this->server_url = 'https://app.turitop.com/v1/authorization/renew';
            $this->data = array(
              'refresh_token'              => $this->result[ 'refresh_token' ],
            );

            $result = $this->send_client_data_to_server();

            if ( empty( $result ) || ! isset( $result[ 'data' ] ) )
                $this->result = array();
            else
              $this->result = array(
                  'access_token'      => $result[ 'data' ]->access_token,
                  'refresh_token'     => $result[ 'data' ]->refresh_token,
                  'company_short_id'  => $result[ 'data' ]->company_short_id,
              );

            return 1;

        }

        /**
         *
         * get turitop products
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function get_turitop_products( $args ) {

            $this->prepare_data_to_get_products( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to get products
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function prepare_data_to_get_products( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/product/getproducts';

            if ( ! empty( $this->result ) )
              $this->data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array( 'language_code' => ( isset( $args[ 'lang' ] ) ? $args[ 'lang' ] : '' ), )
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * get available by tour
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function get_available_by_tour( $args ) {

            $this->prepare_data_to_get_available_by_tour( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to check promo code
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function prepare_data_to_check_promo_code( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/product/checkpromocode';

            if ( ! empty( $this->result ) )
              $this->data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array(
                  'product_short_id' => ( isset( $args[ 'product_short_id' ] ) ? $args[ 'product_short_id' ] : '' ),
                  'date_event'       => ( isset( $args[ 'date_event' ] ) ? $args[ 'date_event' ] : '' ),
                  'promo_code'       => ( isset( $args[ 'promo_code' ] ) ? $args[ 'promo_code' ] : '' ),
                )
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * check promo code
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function check_promo_code( $args ) {
            error_log( 'check_promo_code $args -> ' . print_r( $args, true ) );
            $this->prepare_data_to_check_promo_code( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to insert individual event
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return void
         * @access public
         */
        public function prepare_data_to_insert_individual_event( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/product/tour/insertindividualevents';

            if ( ! empty( $this->result ) )
              $this->data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array(
                  'product_short_id'      => ( isset( $args[ 'product_short_id' ] ) ? $args[ 'product_short_id' ] : '' ),
                  'start_date'            => ( isset( $args[ 'start_date' ] ) ? $args[ 'start_date' ] : '' ),
                  'end_date'              => ( isset( $args[ 'end_date' ] ) ? $args[ 'end_date' ] : '' ),
                  'time'                  => ( isset( $args[ 'time' ] ) ? $args[ 'time' ] : '' ),
                  'current_service_only'  => ( isset( $args[ 'current_service_only' ] ) ? $args[ 'current_service_only' ] : '1' ),
                )
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * insert individual event
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function insert_individual_event( $args ) {

            $this->prepare_data_to_insert_individual_event( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * get available by tour
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function get_events( $args ) {

            $this->prepare_data_to_get_events( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to get products
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function prepare_data_to_get_events( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/product/tour/getevents';

            if ( ! empty( $this->result ) )
              $this->data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array(
                  'product_short_id' => $args[ 'product_short_id' ],
                  'start_date'       => $args[ 'start_date' ],
                  'end_date'         => $args[ 'end_date' ],
                  'language_code'    => ( isset( $args[ 'language_code' ] ) ? $args[ 'language_code' ] : '' ),
                )
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * preparing data to get products
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function prepare_data_to_get_available_by_tour( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/product/tour/getavailable';

            if ( ! empty( $this->result ) )
              $this->data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array(
                  'product_short_id' => $args[ 'product_short_id' ],
                  'start_date'       => $args[ 'start_date' ],
                  'end_date'         => $args[ 'end_date' ],
                  'language_code'    => ( isset( $args[ 'language_code' ] ) ? $args[ 'language_code' ] : '' ),
                )
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * get_ticket for a product
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function get_ticket( $args ) {

            $this->prepare_data_to_get_ticket( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to get ticket
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @return void
         * @access public
         */
        public function prepare_data_to_get_ticket( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/tickets/get';

            if ( ! empty( $this->result ) )
              $this->data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array(
                  'product_short_id' => $args[ 'product_short_id' ],
                  'language_code' => ( isset( $args[ 'language_code' ] ) ? $args[ 'language_code' ] : '' ),
                )
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * insert_tour
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.4
         * @return void
         * @access public
         */
        public function insert_tour( $args ) {

            $this->prepare_data_to_insert_tour( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to insert tour
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.4
         * @return void
         * @access public
         */
        public function prepare_data_to_insert_tour( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/booking/tour/insert';

            if ( ! empty( $this->result ) )
              $this->data = apply_filters( 'integralwebsite_prepare_data_to_insert_tour_args',
                array(
                  'access_token'  => $this->result[ 'access_token' ],
                  'data'          => array(
                    'product_short_id' => $args[ 'product_short_id' ],
                    'booking'          => array(
                      'event_start'       => $args[ 'event_start' ],
                      'ticket_type_count' => $args[ 'ticket_type_count' ],
                      'client_data'       => $args[ 'client_data' ],
                      'status'            => $args[ 'status' ],
                      'notes'             => $args[ 'notes' ],
                      'payment_gateway'   => $args[ 'payment_gateway' ],
                      'promo_code'        => ( isset ( $args[ 'promo_code' ] ) ? $args[ 'promo_code' ] : '' ),
                    )
                  )
                ),
                $args
              );
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * insert_tour
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.4
         * @return void
         * @access public
         */
        public function edit_tour( $args ) {

            $this->prepare_data_to_edit_tour( $args );

            $result = $this->send_client_data_to_server();

            return $result;

        }

        /**
         *
         * preparing data to insert tour
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.4
         * @return void
         * @access public
         */
        public function prepare_data_to_edit_tour( $args ) {

            $this->server_url = 'https://app.turitop.com/v1/booking/tour/edit';

            if ( ! empty( $this->result ) ){

              $data = array(
                'access_token'  => $this->result[ 'access_token' ],
                'data'          => array(
                  'short_id' => $args[ 'short_id' ],
                  'booking'          => array(
                    'event_start'       => $args[ 'event_start' ],
                    'ticket_type_count' => $args[ 'ticket_type_count' ],
                    'client_data'       => $args[ 'client_data' ],
                    'status'            => $args[ 'status' ],
                    'notes'             => $args[ 'notes' ],
                    'payment_gateway'   => $args[ 'payment_gateway' ],
                  )
                )
              );
              $this->data = apply_filters( 'integralwebsite_prepare_data_to_edit_tour_args', $data, $args );

            }
            else
              $this->data = array();

            return 1;

        }

        /**
         *
         * sending data to the TuriTop API
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.1
         * @return json
         * @access public
         */
        public function send_client_data_to_server() {

            $ch = curl_init();
  	        curl_setopt( $ch, CURLOPT_URL, $this->server_url );
            # Setup request to send json via POST.
            $payload = json_encode( $this->data );
            //error_log( 'send_client_data_to_server $payload -> ' . print_r( $payload, true ) );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
            # Return response instead of printing.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            # Send request.

            $result = json_decode( curl_exec( $ch ) );

            curl_close( $ch );

            # return response.
            return ( ! is_array( $result ) ? (array) $result : $result );

        }

    }

}
