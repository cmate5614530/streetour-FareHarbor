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
 * @class      turitop_booking_system_admin
 * @since      Version 1.0.0
 * @author
 *
 */

if ( ! class_exists( 'turitop_booking_system_admin' ) ) {
    /**
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     * @since  1.0.0
     *
     */
    class turitop_booking_system_admin {

      /**
       * Setting page of the plugin
       *
       * @var settings_page
       * @since  1.0.0
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access public
       */
      public $settings_page = null;

      /**
       * Admin ajax
       *
       * @var admin_ajax
       * @since 1.0.0
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access public
       */
      public $admin_ajax = null;

      /**
       * integralwebsite admin page
       *
       * @var class integralwebsite_wp_admin_page
       * @since 1.0.0
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access public
       */
      public $integralwebsite_admin_page = null;

      /**
       * __construct
       *
       * @since 1.0.0
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @access public
       */
      public function __construct() {

          require_once( TURITOP_BOOKING_SYSTEM_PATH . 'admin-settings-page/class.turitop-booking-system-settings-page-main.php' );
          $this->settings_page = turitop_booking_system_settings_page_main::instance();

          require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-admin-ajax.php' );
          $this->admin_ajax = turitop_booking_system_admin_ajax::instance();

          /* ====== ENQUEUE STYLES AND JS ====== */

          add_action( 'admin_enqueue_scripts', array( $this, 'turitop_bs_enqueue_scripts' ) );

          add_action( 'woocommerce_product_options_pricing', array( $this, 'turitop_wc_simple_product_options' ) );

          // Save Product Fields
          add_action( 'woocommerce_process_product_meta', array( $this, 'turitop_wc_save_product_options' ) );

      }

      /**
       * Woocommerce save product options
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.0
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_wc_save_product_options( $post_id ) {

          $tbs_product_data = array();

          if( isset( $_POST[ '_turitop_booking_system_activated' ] ) )
            $tbs_product_data[ 'activated' ] = 'yes';
          else
            $tbs_product_data[ 'activated' ] = 'no';

          if( isset( $_POST[ '_turitop_booking_system_company' ] ) )
                $tbs_product_data[ 'company' ] = sanitize_text_field( $_POST[ '_turitop_booking_system_company' ] );

          if( isset( $_POST[ '_turitop_booking_system_product_id' ] ) )
                $tbs_product_data[ 'product_id' ] = sanitize_text_field( $_POST[ '_turitop_booking_system_product_id' ] );

          if( isset( $_POST[ '_turitop_booking_system_display_price' ] ) )
            $tbs_product_data[ 'display_price' ] = 'yes';
          else
            $tbs_product_data[ 'display_price' ] = 'no';

          if( isset( $_POST[ '_turitop_booking_system_embed' ] ) )
                $tbs_product_data[ 'embed' ] = sanitize_key( $_POST[ '_turitop_booking_system_embed' ] );

          if( isset( $_POST[ '_turitop_booking_system_button_text' ] ) )
                $tbs_product_data[ 'button_text' ] = sanitize_text_field( $_POST[ '_turitop_booking_system_button_text' ] );

          if( isset( $_POST[ '_turitop_booking_system_buttoncolor' ] ) )
                $tbs_product_data[ 'buttoncolor' ] = sanitize_key( $_POST[ '_turitop_booking_system_buttoncolor' ] );

          if( isset( $_POST[ '_turitop_booking_system_additional_data' ] ) )
                $tbs_product_data[ 'additional_data' ] = sanitize_text_field( $_POST[ '_turitop_booking_system_additional_data' ] );

          update_post_meta( $post_id, '_turitop_booking_system_data', $tbs_product_data );

      }

      /**
       * Woocommerce simple product options
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0.0
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_wc_simple_product_options() {

          global $thepostid, $post;
      	  $thepostid = empty( $thepostid ) ? $post->ID : $thepostid;

          if ( wc_get_product( $thepostid )->get_type() == 'simple' ){

              $tbs_data = ( get_option( 'turitop_booking_system_data' ) ? get_option( 'turitop_booking_system_data' ) : array() );

              $tbs_product_data_default = TURITOP_BS()->get_tbs_data();
              $tbs_product_data = get_post_meta( $thepostid, '_turitop_booking_system_data', true );
              $tbs_product_data = ( empty( $tbs_product_data ) || ! is_array( $tbs_product_data ) ? array() : $tbs_product_data );

              $tbs_product_data = array_merge( $tbs_product_data_default, $tbs_product_data );

              $common_translations = TURITOP_BS()->common_translations;

              echo "<div class='turitop_bs_admin_whole_wrap'>";

                  echo "<div class='turitop_bs_title_admin_product_page'>Turitop Booking System</div>";

                  // Enable turitop for this product
                  woocommerce_wp_checkbox(
                    array(
                    	'id'            => '_turitop_booking_system_activated',
                      'value'         => ( isset( $tbs_product_data[ 'activated' ] ) ? $tbs_product_data[ 'activated' ] : false ),
                    	'wrapper_class' => 'show_if_simple',
                    	'label'         => _x( 'Activate', 'product admin page', 'turitop-booking-system' ),
                    	'description'   => __( 'Check this option to activate this product as a turitop booking system', 'woocommerce' )
                    	)
                  );

                  echo "<div class='turitop_bs_admin_wrap'>";

                    // Company ID
                    woocommerce_wp_text_input(
              			array(
              				'id'          => '_turitop_booking_system_company',
              				'label'       => $common_translations[ 'company' ],
                      'value'       => ( isset( $tbs_product_data[ 'company' ] ) ? $tbs_product_data[ 'company' ] : ( isset( $tbs_data[ 'company' ] ) ? $tbs_data[ 'company' ] : null ) ),
                      'desc_tip'    => 'true',
              				'description' => $common_translations[ 'company_desc' ],
              			)
              		  );

                      // Company ID
                      woocommerce_wp_text_input(
                       array(
                           'id'          => '_turitop_booking_system_product_id',
                           'label'       => $common_translations[ 'product_id' ],
                           'value'       => ( isset( $tbs_product_data[ 'product_id' ] ) ? $tbs_product_data[ 'product_id' ] : '' ),
                           'placeholder' => _x( 'Product ID', 'product admin page', 'turitop-booking-system' ),
                            'desc_tip' => 'true',
                           'description' => $common_translations[ 'company_desc' ],
                       )
                     );

                     woocommerce_wp_checkbox(
                       array(
                       	'id'            => '_turitop_booking_system_display_price',
                           'value'         => ( isset( $tbs_product_data[ 'display_price' ] ) ? $tbs_product_data[ 'display_price' ] : false ),
                       	'wrapper_class' => 'show_if_simple',
                       	'label'         => _x( 'Display price', 'product admin page', 'turitop-booking-system' ),
                       	'description'   => __( 'Check this option to display the price on the product page', 'woocommerce' )
                       	)
                     );

                       // Select google analytics
                       woocommerce_wp_select(
                         array(
                         	'id'      => '_turitop_booking_system_embed',
                         	'label'   => $common_translations[ 'embed' ],
                            'value'       => ( isset( $tbs_product_data[ 'embed' ] ) ? $tbs_product_data[ 'embed' ] : 'default' ),
                         	'options' => array(
                         		'default'   => $common_translations[ 'default' ],
                         		'box'   => $common_translations[ 'box' ],
                         		'button' => $common_translations[ 'button' ],
                             ),
                             'desc_tip' => 'true',
               				 'description' => $common_translations[ 'embed_desc' ],
                         	)
                        );

                        echo "<div class='turitop_bs_admin_button_wrap'>";

                            // Button text
                            woocommerce_wp_text_input(
                    			array(
                    				'id'          => '_turitop_booking_system_button_text',
                    				'label'       => $common_translations[ 'button_text' ],
                    				'value'       => ( isset( $tbs_product_data[ 'button_text' ] ) ? $tbs_product_data[ 'button_text' ] : ( isset( $tbs_data[ 'button_text' ] ) ? $tbs_data[ 'button_text' ] : __( 'Book now', 'main settings', 'turitop-booking-system' ) ) ),
                                    'desc_tip' => 'true',
                    				'description' => $common_translations[ 'button_text_desc' ],
                    			)
                    		  );

                            // Select google analytics
                            woocommerce_wp_select(
                              array(
                              	'id'      => '_turitop_booking_system_buttoncolor',
                              	'label'   => $common_translations[ 'buttoncolor' ],
                                'value'       => ( isset( $tbs_product_data[ 'buttoncolor' ] ) ? $tbs_product_data[ 'buttoncolor' ] : 'default' ),
                              	'options' => array(
                              		'default'   => $common_translations[ 'default' ],
                              		'green'   => $common_translations[ 'green' ],
                              		'orange'   => $common_translations[ 'orange' ],
                                    'blue'   => $common_translations[ 'blue' ],
                                    'red'   => $common_translations[ 'red' ],
                                    'yellow'   => $common_translations[ 'yellow' ],
                                    'black'   => $common_translations[ 'black' ],
                                    'white'   => $common_translations[ 'white' ],
                                  ),
                                 'desc_tip' => 'true',
                    			 'description' => $common_translations[ 'buttoncolor_desc' ],
                              	)
                             );

                             echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_button_wrap'></div>";

                         echo "</div>";

                         // Addit
                         woocommerce_wp_textarea_input(
                         array(
                           'id'          => '_turitop_booking_system_additional_data',
                           'label'       => $common_translations[ 'additional_data' ],
                           'value'       => ( isset( $tbs_product_data[ 'additional_data' ] ) ? $tbs_product_data[ 'additional_data' ] : '' ),
                                   'desc_tip' => 'true',
                           'description' => $common_translations[ 'additional_data_desc' ],
                         )
                       );

                         echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_admin_product'></div>";

                    echo "</div>";

                echo "</div>";

          }

        }

      /**
       * enqueue scripts
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since 1.0
       * @access public
       * @param
       * @return void
       *
       */
      public function turitop_bs_enqueue_scripts() {

          /* ====== Style ====== */

          wp_register_style( 'turitop_booking_system_admin_css', apply_filters( 'turitop_booking_system_admin_css_filter', TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/css/turitop-bs-admin.css' ), array(), TURITOP_BOOKING_SYSTEM_VERSION );
          wp_enqueue_style( 'turitop_booking_system_admin_css' );

          /* ====== Admin Script ====== */

          wp_register_script( 'turitop_booking_system_admin_js', apply_filters( 'turitop_booking_system_admin_js_filter',
          TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/js/turitop-bs-admin.js' ), array(
              'jquery',
              'jquery-ui-sortable',
              'wp-color-picker',
          ), TURITOP_BOOKING_SYSTEM_VERSION, true );

          wp_localize_script( 'turitop_booking_system_admin_js', 'turitop_object_admin', apply_filters( 'turitop_booking_system_admin_js_localize', array(
              'ajax_url'         => admin_url( 'admin-ajax.php' ),
              'admin_nonce'      => wp_create_nonce( 'sync_nonce' ),
              'ajax_loader_bar'  => TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/images/turitop-ajax-loader-bar.gif',
          ) ) );

          wp_enqueue_script( 'turitop_booking_system_admin_js' );

          /***********************************************************************************/

        }

    }
}
