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
 * @class      turitop_booking_system_admin_ajax
 * @since      Version 1.0.1
 * @author
 *
 */

if ( ! class_exists( 'turitop_booking_system_admin_ajax' ) ) {
    /**
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     * @since  1.0.1
     *
     */
    class turitop_booking_system_admin_ajax {

      /**
       * Main Instance
       *
       * @var _instance
       * @since  1.0.1
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access protected
       */
      protected static $_instance = null;

      /**
       * __construct
       *
       * @since 1.0.1
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access public
       */
      public function __construct() {

        /* Synchronize services */
        add_action( 'wp_ajax_turitop_booking_system_synchronize_services', array(
          $this,
          'turitop_booking_system_synchronize_services_call_back'
        ) );

        /* Synchronize services upload */
        add_action( 'wp_ajax_turitop_booking_system_synchronize_services_upload', array(
          $this,
          'turitop_booking_system_synchronize_services_upload_call_back'
        ) );

      }

      /**
       * Main plugin Instance
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.1
       * @access public
       * @param
       * @return turitop_booking_system_admin_ajax main instance
       *
       */
      public static function instance() {
          if ( is_null( self::$_instance ) ) {
              self::$_instance = new self();
          }

          return self::$_instance;
      }

      /**
       * Ajax call to synchronize services
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.1
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_booking_system_synchronize_services_upload_call_back() {

          check_ajax_referer( 'sync_nonce', 'security' );

          $service_id = ( isset( $_POST[ 'service_id' ] ) ? $_POST[ 'service_id' ] : 0 );
          $order = ( isset( $_POST[ 'order' ] ) ? $_POST[ 'order' ] : 0 );
          $page_id = ( isset( $_POST[ 'page_id' ] ) ? $_POST[ 'page_id' ] : 0 );

          $my_service = array(
              'ID'           => $service_id,
              'menu_order'   => $order,
          );
          wp_update_post( $my_service );
          $data = get_post_meta( $service_id, 'turitop_booking_system_service_data', true );
          $data[ 'page_id' ] = $page_id;
          update_post_meta( $service_id, 'turitop_booking_system_service_data', $data );

          wp_send_json_success( array(
            'result'       => 'done',
          ) );

      }

      /**
       * Ajax call to synchronize services
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.1
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_booking_system_synchronize_services_call_back() {

          check_ajax_referer( 'sync_nonce', 'security' );

          $products = ( isset( $_POST[ 'products' ] ) ? $_POST[ 'products' ] : array() );
          $step = ( isset( $_POST[ 'step' ] ) ? $_POST[ 'step' ] : 'init' );
          $service_page = ( isset( $_POST[ 'service_page' ] ) ? $_POST[ 'service_page' ] : 'no' );

          $tbs_data = TURITOP_BS()->get_tbs_data();

          if ( $step == 'init' ){

            require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-api-client.php' );
            $api_client = new turitop_booking_system_api_client();

            $company_id = ( isset( $tbs_data[ 'company' ] ) ? $tbs_data[ 'company' ] : 0 );
            $secret_key = ( isset( $tbs_data[ 'secret_key' ] ) ? $tbs_data[ 'secret_key' ] : 0 );

            $args = array(
                'short_id'    => $company_id,
                'secret_key'  => $secret_key,
            );

            $result = $api_client->connect_to_turitop_api( $args );

            if ( ! isset( $result[ 'code' ] ) || $result[ 'code' ] != '200' )
              wp_send_json_success( array(
                'result'       => 'wrong_credentials',
              ) );

            $products = array();
            $check_langs = ( isset( $_POST[ 'langs' ] ) ? $_POST[ 'langs' ] : array() );

            if ( empty( $check_langs ) ){
              wp_send_json_success( array(
                'result'       => 'empty_langs',
              ) );
            }

            foreach ( $check_langs as $lang ) {
              $result = $api_client->get_turitop_products( array( 'lang' => $lang ) );

              foreach ( $result[ 'data' ]->products as $product ) {

                $images = array();

                if ( is_array( $product->images ) )
                  foreach ( $product->images as $image ) {
                    $url_array = explode( '?', $image->url );
                    $images[] = $url_array[ 0 ];
                  }

                $langs = ( isset( $products[ $product->short_id ][ 'langs' ] ) ? $products[ $product->short_id ][ 'langs' ] : array() );
                $langs[ $lang ] = array(
                  'name' => $product->name,
                  'summary' => str_replace( '\n', '<br>', $product->summary ),
                  'description' => str_replace( '\n', '<br>', $product->description ),
                );

                $products[ $product->short_id ] = array(
                  'short_id' => $product->short_id,
                  'langs' => $langs,
                  'flow' => $product->flow,
                  'pricing_notes' => $product->pricing_notes,
                  'duration' => $product->duration,
                  'timezone' => $product->timezone,
                  'company_categories' => $product->company_categories,
                  'images' => $images,
                );

              }

            }

            wp_send_json_success( array(
              'result'       => 'init',
              'products'     => base64_encode( serialize( $products ) ),
              'num_products' => count( $products ),
            ) );

          }
          else{

            $products = ( ! empty( $products ) ? unserialize( base64_decode( $products ) ) : array() );

            $cont = 0;
            $step_counter = 1;
            while( ! empty( $products ) && $cont < $step_counter ){

              $cont++;

              $products_num = count( $products );
              $product = array_pop( $products );

              global $wpdb;
              $posttitle = $product[ 'short_id' ];
              $service_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' and post_type = '" . TURITOP_BOOKING_SYSTEM_SERVICE_CPT . "'" );

              if ( ! empty( $service_id ) ){

                $data = get_post_meta( $service_id, 'turitop_booking_system_service_data', true );
                $my_service = array(
                    'ID'           => $service_id,
                    'post_title'   => $product[ 'short_id' ],
                );
                wp_update_post( $my_service );
                $product[ 'page_id' ] = $data[ 'page_id' ];
                $old_product = get_post_meta( $service_id, 'turitop_booking_system_service_data', true );
                foreach ( $old_product[ 'langs' ] as $old_key => $old_lang ) {
                  if ( ! isset( $product[ 'langs' ][ $old_key ] ) )
                    $product[ 'langs' ][ $old_key ] = $old_lang;
                }

                if ( $service_page == 'yes' && empty( $data[ 'page_id' ] ) ){

                  $embed = ( ! isset( $tbs_data[ 'display_with' ] ) || $tbs_data[ 'display_with' ] == 'box' ? 'details_and_box' : 'details_and_button' );
                  $content_service = ( isset( $tbs_data[ 'content_service' ] ) ? $tbs_data[ 'content_service' ] : 'whole_content' );
                  $layout = ( isset( $tbs_data[ 'layout' ] ) ? $tbs_data[ 'layout' ] : 'image_left' );
                  $button_text = ( isset( $tbs_data[ 'button_text' ] ) ? $tbs_data[ 'button_text' ] : 'Book now' );
                  $buttoncolor = ( isset( $tbs_data[ 'buttoncolor' ] ) ? $tbs_data[ 'buttoncolor' ] : 'green' );

                  $page_content = '<!-- wp:turitop/turitop-booking-system {"product_id":"' . $product[ 'short_id' ] . '","embed":"' . $embed . '","layout":"' . $layout . '","content_service":"' . $content_service . '","button_text":"' . $button_text . '","buttoncolor":"' . $buttoncolor . '"} /-->';

                  if ( isset( $product[ 'langs' ][ 'en' ] ) )
                    $lang = $product[ 'langs' ][ 'en' ];
                  else {
                    foreach ( $product[ 'langs' ] as $key => $lang_for ) {
                      $lang = $lang_for;
                      break;
                    }
                  }

                  $page_id = wp_insert_post( array(
                      'post_title'   => $lang[ 'name' ],
                      'post_content' => $page_content,
                      'post_type'    => 'page',
                      'post_status'  => 'publish',
                      'menu_order'   => $products_num,
                  ) );

                  $product[ 'page_id' ] = $page_id;

                }

                update_post_meta( $service_id, 'turitop_booking_system_service_data', $product );
                /*$my_page = array(
                    'ID'           => $data[ 'page_id' ],
                    'post_title'   => $product[ 'langs' ][ 'en' ][ 'name' ],
                    'post_content' => $page_content,
                );
                wp_update_post( $my_page );*/

              }
              else{

                $embed = ( ! isset( $tbs_data[ 'display_with' ] ) || $tbs_data[ 'display_with' ] == 'box' ? 'details_and_box' : 'details_and_button' );
                $content_service = ( isset( $tbs_data[ 'content_service' ] ) ? $tbs_data[ 'content_service' ] : 'whole_content' );
                $layout = ( isset( $tbs_data[ 'layout' ] ) ? $tbs_data[ 'layout' ] : 'image_left' );
                $button_text = ( isset( $tbs_data[ 'button_text' ] ) ? $tbs_data[ 'button_text' ] : 'Book now' );
                $buttoncolor = ( isset( $tbs_data[ 'buttoncolor' ] ) ? $tbs_data[ 'buttoncolor' ] : 'green' );

                $page_content = '<!-- wp:turitop/turitop-booking-system {"product_id":"' . $product[ 'short_id' ] . '","embed":"' . $embed . '","layout":"' . $layout . '","content_service":"' . $content_service . '","button_text":"' . $button_text . '","buttoncolor":"' . $buttoncolor . '"} /-->';

                $service_id = wp_insert_post( array(
                    'post_title'   => $product[ 'short_id' ],
                    'post_type'    => TURITOP_BOOKING_SYSTEM_SERVICE_CPT,
                    'post_status'  => 'publish',
                    'menu_order'   => $products_num,
                ) );

                if ( isset( $product[ 'langs' ][ 'en' ] ) )
                  $lang = $product[ 'langs' ][ 'en' ];
                else {
                  foreach ( $product[ 'langs' ] as $key => $lang_for ) {
                    $lang = $lang_for;
                    break;
                  }
                }

                if ( $service_page == 'yes' ){

                  $page_id = wp_insert_post( array(
                      'post_title'   => $lang[ 'name' ],
                      'post_content' => $page_content,
                      'post_type'    => 'page',
                      'post_status'  => 'publish',
                      'menu_order'   => $products_num,
                  ) );

                  $product[ 'page_id' ] = $page_id;

                }
                else{
                  $product[ 'page_id' ] = 0;
                }

                update_post_meta( $service_id, 'turitop_booking_system_service_data', $product );

              }

            }

            if ( empty( $products ) )
              wp_send_json_success( array(
                'result'  => 'done',
                'count'   => $cont,
              ) );
            else
              wp_send_json_success( array(
                'result'       => 'forward',
                'products'     => base64_encode( serialize( $products ) ),
                'count'   => $cont,
              ) );

          }

      }

    }
}
