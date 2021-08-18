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
 * @class      turitop_booking_system_frontend_ajax
 * @since      Version 1.0.3
 * @author
 *
 */

if ( ! class_exists( 'turitop_booking_system_frontend_ajax' ) ) {
    /**
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     * @since  1.0.3
     *
     */
    class turitop_booking_system_frontend_ajax {

      /**
       * Main Instance
       *
       * @var _instance
       * @since  1.0.3
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access protected
       */
      protected static $_instance = null;

      /**
       * __construct
       *
       * @since 1.0.3
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access public
       */
      public function __construct() {

        /* Display round trip services */
        add_action( 'wp_ajax_turitop_booking_system_round_trip', array(
          $this,
          'turitop_booking_system_round_trip_call_back'
        ) );

        /* Display round trip services */
        add_action( 'wp_ajax_nopriv_turitop_booking_system_round_trip', array(
          $this,
          'turitop_booking_system_round_trip_call_back'
        ) );

        /* Display round trip destinations */
        add_action( 'wp_ajax_turitop_booking_system_round_trip_select_to', array(
          $this,
          'turitop_booking_system_round_trip_select_to_call_back'
        ) );

        /* Display round trip destinations */
        add_action( 'wp_ajax_nopriv_turitop_booking_system_round_trip_select_to', array(
          $this,
          'turitop_booking_system_round_trip_select_to_call_back'
        ) );

      }

      /**
       * Main plugin Instance
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.3
       * @access public
       * @param
       * @return turitop_booking_system_frontend_ajax main instance
       *
       */
      public static function instance() {
          if ( is_null( self::$_instance ) ) {
              self::$_instance = new self();
          }

          return self::$_instance;
      }

      /**
       * display round trip services
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.3
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_booking_system_round_trip_select_to_call_back() {

          check_ajax_referer( 'round_trip_nonce', 'security' );

          $args = ( isset( $_POST[ 'args' ] ) ? $_POST[ 'args' ] : array() );

          $from = ( isset( $args[ 'from' ] ) ? $args[ 'from' ] : '' );

          $to_options = '';
          ob_start();
          echo "<option value='0'>" . TURITOP_BS()->common_translations[ 'choose_to' ] . "</option>";
          foreach ( TURITOP_BS()->get_round_trip_booking_data() as $trip ){

            if ( $trip[ 'from' ] == $from )
              echo "<option value='" . $trip[ 'to' ] . "'>" . $trip[ 'to' ] . "</option>";

          }
          $to_options = ob_get_clean();

          wp_send_json_success( array(
            'to_options'       => $to_options,
          ) );

      }

      /**
       * display round trip services
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.3
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_booking_system_round_trip_call_back() {

          check_ajax_referer( 'round_trip_nonce', 'security' );

          $args = ( isset( $_POST[ 'args' ] ) ? $_POST[ 'args' ] : array() );

          $type= ( isset( $args[ 'type' ] ) ? $args[ 'type' ] : '' );
          $from = ( isset( $args[ 'from' ] ) ? $args[ 'from' ] : '' );
          $to = ( isset( $args[ 'to' ] ) ? $args[ 'to' ] : '' );

          $outbound_service = '';
          $return_service = '';

          foreach ( TURITOP_BS()->get_round_trip_booking_data() as $trip ) {

            if ( $trip[ 'from' ] == $from && $trip[ 'to' ] == $to ){
              $outbound_service = trim( $trip[ 'outbound' ] );
              $return_service = trim( $trip[ 'return' ] );
              break;
            }

          }

          $outbound_box = '';
          if ( ! empty( $outbound_service ) ){

            ob_start();

            ?>

              <div class="turitop_bswp_button_box_wrap">

                      <div class="load-turitop loading-turitop" style="text-align: center;" data-service="<?php echo $outbound_service; ?>" data-embed="box"></div>

              </div>

            <?php

            $outbound_box = ob_get_clean();

          }

          $return_box = '';
          if ( ! empty( $return_service ) ){

            ob_start();

            ?>

            <div class="turitop_bswp_button_box_wrap">

                    <div class="load-turitop loading-turitop" data-service="<?php echo $return_service; ?>" data-embed="box"></div>

            </div>

            <?php

            $return_box = ob_get_clean();

          }

          wp_send_json_success( array(
            'outbound_box'       => $outbound_box,
            'outbound_service'   => $outbound_service,
            'return_box'         => $return_box,
            'return_service'     => $return_service,
          ) );

      }

    }
}
