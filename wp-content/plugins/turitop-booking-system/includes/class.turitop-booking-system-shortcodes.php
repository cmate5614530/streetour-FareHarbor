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

if ( ! class_exists( 'turitop_booking_system_shortcodes' ) ) {

	class turitop_booking_system_shortcodes {

        /**
         * turitop_booking_system_data
         *
         * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
        public $tbs_data = array();

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
         * __construct
         *
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
        public function __construct() {

            $this->init();

        }

        /**
         * turitop_booking_system_shortcodes Instance
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return turitop_booking_system_shortcodes main instance
         *
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
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

			$shortcodes = array(
				//'turitop_booking_system' => __CLASS__ . '::display_turitop_booking_system'
          'turitop_booking_system' => array( $this, 'display_turitop_booking_system' ),
          'turitop_services' => array( $this, 'display_turitop_services' ),
			);

			foreach ( $shortcodes as $shortcode => $function ) {
				add_shortcode( $shortcode, $function );
			}

			shortcode_atts( array(
                      'product_id' => '',
                      'embed' => '' ,
                      'lang' => '',
                      'button_text' => '',
                      'ga' => '',
                      'buttoncolor' => '',
                      'afftag' => '',
                      'compnay' => '',
                      'wc_product_id' => '',
                      'button_image_default' => '',
                      'button_image_url' => '',
                      ),
                      array(),
                      'turitop_booking_system' );

      /* ====== REGISTER STYLES AND JS ====== */
      $this->prefix_register_resources();

      /* ====== ADD TURITOP ATTRIBUTES TO THE JS ====== */
      add_filter( 'script_loader_tag',
                  array( $this, 'add_attributes_to_script'),
                  99, 3 );

		}

        /**
         * prefix_register_resources
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function prefix_register_resources() {

            wp_register_style( 'turitop_booking_system_shortcode_frontend_style',
                apply_filters( 'turitop_booking_system_fronted_style_filter', TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/css/turitop-bs-shortcode-frontend.css' ),
                array(),
                TURITOP_BOOKING_SYSTEM_VERSION );

            /*wp_register_script( 'turitop_booking_system_frontend_js_url',
                apply_filters( 'turitop_booking_system_frontend_js_url_filter', TURITOP_BOOKING_SYSTEM_JS_URL ), array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ),
                TURITOP_BOOKING_SYSTEM_VERSION,
                true );*/

        }

        /**
         * display turitop booking system
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param shortcode attributes
         * @return html booking system
         *
         */
    		public function display_turitop_booking_system( $atts ) {

    			ob_start();

                $this->tbs_data = TURITOP_BS()->get_tbs_data();

                if ( ! empty( $this->tbs_data ) && isset( $this->tbs_data[ 'company' ] ) && ! empty( $this->tbs_data[ 'company' ] ) ){

                    /* ====== ENQUEUE STYLES AND JS ====== */
                    wp_enqueue_style( 'turitop_booking_system_shortcode_frontend_style' );

                    if ( ! is_admin() && ( ! isset( $this->tbs_data[ 'cart_on_menu' ] ) || $this->tbs_data[ 'cart_on_menu' ] != 'yes' ) )
                        wp_enqueue_script( 'turitop_booking_system_frontend_js_url' );

                    $this->check_attributes( $atts );

                    $this->check_woocommerce_product( $atts );

                    $this->tbs_data = apply_filters( 'turitop_booking_system_shortcode_data_filter', $this->tbs_data, $atts );

                    $classes = 'load-turitop loading-turitop';
                    $classes = ( $this->tbs_data[ 'buttoncolor_default' ] == 'yes' || $this->tbs_data[ 'embed' ] == 'box' ? $classes . " turitop_booking_system_box_button" : $classes );
                    $classes = ( isset ( $this->tbs_data[ 'classname' ] ) && ! empty( $this->tbs_data[ 'classname' ] ) ? $classes . " " . $this->tbs_data[ 'classname' ] : $classes );
                    $classes = apply_filters( 'turitop_booking_system_box_button_classes', $classes, $this->tbs_data, $atts );

                    $embed = $this->tbs_data[ 'embed' ];

                    $this->display_button( $embed, $atts, $classes );

                }

    			$content = ob_get_clean();

    			return $content;

    		}

        /**
         * display_button
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
    		public function display_button( $embed, $atts, $classes ) {

          $box_button_custom_activate = ( isset( $this->tbs_data[ 'box_button_custom_activate' ] ) && $this->tbs_data[ 'box_button_custom_activate' ] == 'yes' ? 'yes' : 'no' );

          /** CHECKING CUSTOM CLASS **/
          $buttoncolor = ( $box_button_custom_activate == 'yes' && isset( $this->tbs_data[ 'button_custom_class' ] ) && ! empty( $this->tbs_data[ 'button_custom_class' ] ) ? 'none' : $this->tbs_data[ 'buttoncolor' ] );

          $cssclass = ( isset( $this->tbs_data[ 'button_custom_class' ] ) && ! empty( $this->tbs_data[ 'button_custom_class' ] ) ? 'data-cssclass="' . $this->tbs_data[ 'button_custom_class' ] . '"' : '' );

          /** CHECKING BUTTON IMAGE **/
          $button_image_activate = ( isset( $this->tbs_data[ 'button_image_activate' ] ) && $this->tbs_data[ 'button_image_activate' ] == 'yes' ? 'yes' : 'no' );
          $buttoncolor = ( $box_button_custom_activate == 'yes' && $button_image_activate == 'yes' && isset( $this->tbs_data[ 'button_image_url' ] ) && ! empty( $this->tbs_data[ 'button_image_url' ] ) ? 'none' : $this->tbs_data[ 'buttoncolor' ] );

          /* TO DEVELOP WHEN UPLOADMEDIA ON GUTENBER BLOCK */
          /*$buttoncolor = ( $box_button_custom_activate == 'yes' && $button_image_activate == 'yes' && isset( $this->tbs_data[ 'button_image_url' ] ) && ! empty( $this->tbs_data[ 'button_image_url' ] ) && isset( $this->tbs_data[ 'button_image_id' ] ) && ! empty( $this->tbs_data[ 'button_image_id' ] ) ? 'none' : $this->tbs_data[ 'buttoncolor' ] );
          $button_text =( $box_button_custom_activate == 'yes' && $button_image_activate == 'yes' && isset( $this->tbs_data[ 'button_image_url' ] ) && ! empty( $this->tbs_data[ 'button_image_id' ] ) ? wp_get_attachment_image( $this->tbs_data[ 'button_image_id' ], 'full' ) : $this->tbs_data[ 'button_text' ] );*/

          $button_text =( $box_button_custom_activate == 'yes' && $button_image_activate == 'yes' && isset( $this->tbs_data[ 'button_image_url' ] ) && ! empty( $this->tbs_data[ 'button_image_url' ] ) ? '<img src="' . $this->tbs_data[ 'button_image_url' ] . '"/>' : $this->tbs_data[ 'button_text' ] );

          //$button_text = ( $embed == 'box' ? '' : $button_text );

          $extra_data = '';

          switch ( $embed ) {

            case 'button':

              $embed = 'button';

              break;

            case 'gift':

              $embed = 'button';
              $extra_data = 'data-gift="checked"';

              break;

            case 'redeemgv':

              $extra_data = 'id="redeemgv" data-lang="' . $this->tbs_data[ 'lang' ] . '"';

              break;

            case 'box':

              $button_text = '';

              break;

          }

          ?>

          <div class="turitop_bswp_button_box_wrap">

                  <div class="<?php echo $classes; ?>" <?php echo $extra_data; ?> data-service="<?php echo $this->tbs_data[ 'product_id' ]; ?>"  data-embed="<?php echo $embed; ?>" data-buttoncolor="<?php echo $buttoncolor; ?>" <?php echo $cssclass; ?>><?php echo $button_text; ?></div>

          </div>

          <?php

        }

        /**
         * check attributes
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param shortcode attributes
         * @return void
         *
         */
    		public function check_attributes( $atts ) {

          $this->tbs_data[ 'embed' ] = ( isset( $atts[ 'embed' ] ) ? $atts[ 'embed' ] : ( isset( $this->tbs_data[ 'embed' ] ) ? $this->tbs_data[ 'embed' ] : 'box' ) );

          $this->tbs_data[ 'button_text' ] = ( isset( $atts[ 'button_text' ] ) ? $atts[ 'button_text' ] : ( isset( $this->tbs_data[ 'button_text' ] ) ? $this->tbs_data[ 'button_text' ] : __( 'Book now', 'main settings', 'turitop-booking-system' ) ) );


          $this->tbs_data[ 'buttoncolor_default' ] = ( isset( $atts[ 'buttoncolor' ] ) && $atts[ 'buttoncolor' ] != 'default' ? 'no' : 'yes' );

          $buttoncolor = ( isset( $this->tbs_data[ 'buttoncolor' ] ) ? $this->tbs_data[ 'buttoncolor' ] : 'green' );
          $this->tbs_data[ 'buttoncolor' ] = ( isset( $atts[ 'buttoncolor' ] ) ? ( $atts[ 'buttoncolor' ] == 'default' ? $buttoncolor : $atts[ 'buttoncolor' ] ) : $buttoncolor );

          $this->tbs_data[ 'afftag' ] = ( isset( $atts[ 'afftag' ] ) ? $atts[ 'afftag' ] : ( isset( $this->tbs_data[ 'afftag' ] ) ? $this->tbs_data[ 'afftag' ] : 'ttafid' ) );

          $lang_array = explode( '_', get_locale() );
          $lang = array_shift( $lang_array );

          $this->tbs_data[ 'lang' ] = ( isset( $atts[ 'lang' ] ) ? $atts[ 'lang' ] : $lang );

          $this->tbs_data[ 'product_id' ] = ( isset( $atts[ 'product_id' ] ) ? $atts[ 'product_id' ] : '' );
          $this->tbs_data[ 'classname' ] = ( isset( $atts[ 'classname' ] ) ? $atts[ 'classname' ] : '' );

          $this->tbs_data[ 'layout' ] = ( isset( $atts[ 'layout' ] ) ? $atts[ 'layout' ] : 'image_left' );
          $this->tbs_data[ 'content_service' ] = ( isset( $atts[ 'content_service' ] ) ? $atts[ 'content_service' ] : 'whole_content' );

          $button_image_activate = ( isset( $this->tbs_data[ 'button_image_activate' ] ) ? $this->tbs_data[ 'button_image_activate' ] : 'no' );
          $this->tbs_data[ 'button_image_activate' ] = ( isset( $atts[ 'button_image_activate' ] ) ? ( $atts[ 'button_image_activate' ] == 'default' ? $button_image_activate : $atts[ 'button_image_activate' ] ) : $button_image_activate );

          $this->tbs_data[ 'button_custom_class' ] = ( isset( $atts[ 'button_custom_class' ] ) ? $atts[ 'button_custom_class' ] : ( isset( $this->tbs_data[ 'button_custom_class' ] ) ? $this->tbs_data[ 'button_custom_class' ] : '' ) );

          $button_image_id = ( isset( $this->tbs_data[ 'button_image_id' ] ) ? $this->tbs_data[ 'button_image_id' ] : 0 );
          $this->tbs_data[ 'button_image_id' ] = ( isset( $atts[ 'button_image_id' ] ) ? $atts[ 'button_image_id' ] : $button_image_id );

          if ( isset( $atts[ 'button_image_default' ] ) && $atts[ 'button_image_default' ] == 'default' ){
            $this->tbs_data[ 'button_image_url' ] = ( isset( $this->tbs_data[ 'button_image_url' ] ) ? $this->tbs_data[ 'button_image_url' ] : '' );
          }
          else {
            $this->tbs_data[ 'button_image_url' ] = ( isset( $atts[ 'button_image_url' ] ) ? $atts[ 'button_image_url' ] : '' );
          }

    		}

        /**
         * display details
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param shortcode attributes
         * @return void
         *
         */
    		public function display_details( $embed, $atts, $classes ) {

          $embed = ( $embed == 'details_and_box' ? 'box' : 'button' );

          if ( $this->tbs_data[ 'content_service' ] == 'summary_content' ){

            $embed = 'button';

            $service_classes = 'turitop_booking_system_service_grid_summary';
            $service_title_classes = 'turitop_booking_system_service_title_summary';
            $service_image_classes = 'turitop_booking_system_service_image_summary';
            $service_summary_classes = 'turitop_booking_system_service_summary_summary';
            $service_box_button_classes = 'turitop_booking_system_service_box_button_summary';
            $service_description_classes = 'turitop_booking_system_service_description_summary';

            $template = 'summary_content';

          }
          else switch ( $this->tbs_data[ 'layout' ] ) {
            case 'image_left':
              if ( $embed == 'box' ){
                $service_classes = 'turitop_booking_system_service_grid_image_left_box';
                $service_title_classes = 'turitop_booking_system_service_title_image_left_box';
                $service_image_classes = 'turitop_booking_system_service_image_image_left_box';
                $service_summary_classes = 'turitop_booking_system_service_summary_image_left_box';
                $service_box_button_classes = 'turitop_booking_system_service_box_button_image_left_box';
                $service_description_classes = 'turitop_booking_system_service_description_image_left_box';
              }
              else{
                $service_classes = 'turitop_booking_system_service_grid_image_left_button';
                $service_title_classes = 'turitop_booking_system_service_title_image_left_button';
                $service_image_classes = 'turitop_booking_system_service_image_image_left_button';
                $service_summary_classes = 'turitop_booking_system_service_summary_image_left_button';
                $service_box_button_classes = 'turitop_booking_system_service_box_button_image_left_button';
                $service_description_classes = 'turitop_booking_system_service_description_image_left_button';
              }

              $template = 'image_left';

              break;

            case 'image_rigth':
              if ( $embed == 'box' ){
                $service_classes = 'turitop_booking_system_service_grid_image_right_box';
                $service_title_classes = 'turitop_booking_system_service_title_image_right_box';
                $service_image_classes = 'turitop_booking_system_service_image_image_right_box';
                $service_summary_classes = 'turitop_booking_system_service_summary_image_right_box';
                $service_box_button_classes = 'turitop_booking_system_service_box_button_image_right_box';
                $service_description_classes = 'turitop_booking_system_service_description_image_right_box';
              }
              else{
                $service_classes = 'turitop_booking_system_service_grid_image_right_button';
                $service_title_classes = 'turitop_booking_system_service_title_image_right_button';
                $service_image_classes = 'turitop_booking_system_service_image_image_right_button';
                $service_summary_classes = 'turitop_booking_system_service_summary_image_right_button';
                $service_box_button_classes = 'turitop_booking_system_service_box_button_image_right_button';
                $service_description_classes = 'turitop_booking_system_service_description_image_right_button';
              }

              $template = 'image_rigth';

              break;

            case 'image_top_center';

              $service_classes = '';
              $service_title_classes = '';
              $service_image_classes = '';
              $service_summary_classes = '';
              $service_box_button_classes = '';
              $service_description_classes = '';

              $template = 'image_top_center';

              break;

            default:

              $template = apply_filters( 'turitop_booking_system_service_block_template', $this->tbs_data[ 'layout' ] );

              break;
          }

          global $wpdb;

          if ( $this->tbs_data[ 'product_id' ] == 'all' ){
            global $wpdb;
            $args = apply_filters( 'turitop_booking_system_services_all_args', "SELECT ID FROM $wpdb->posts WHERE post_type = '" . TURITOP_BOOKING_SYSTEM_SERVICE_CPT . "' ORDER BY menu_order", $this->tbs_data, $atts );
            $product_ids = $wpdb->get_results( $args );
          }
          else{
            $product_ids = explode( ",", $this->tbs_data[ 'product_id' ] );
          }

          if ( $this->tbs_data[ 'content_service' ] == 'summary_content' ){

            echo '<div class="turitop_booking_system_service_flex_wrap">';

          }

          do_action( 'turitop_booking_system_displaying_services_before', $product_ids, $this->tbs_data );

          foreach ( $product_ids as $product_id ) {

            if ( $this->tbs_data[ 'product_id' ] == 'all' ){
              $service_id = $product_id->ID;
            }
            else{
              $posttitle = str_replace( ' ', '', $product_id );
              $service_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' and post_type = '" . TURITOP_BOOKING_SYSTEM_SERVICE_CPT . "'" );
            }

            if ( ! $service_id )
              continue;

            $data = get_post_meta( $service_id, 'turitop_booking_system_service_data', true );

            $lang_content = ( isset( $data[ 'langs' ][ $this->tbs_data[ 'lang' ] ] ) ? $data[ 'langs' ][ $this->tbs_data[ 'lang' ] ] : ( isset( $this->tbs_data[ 'default_service_lang' ] ) && isset( $data[ 'langs' ][ $this->tbs_data[ 'default_service_lang' ] ] ) ? $data[ 'langs' ][ $this->tbs_data[ 'default_service_lang' ] ] : ( isset( $data[ 'langs' ][ 'en' ] ) ? $data[ 'langs' ][ 'en' ] : null ) ) );

            if ( empty( $lang_content ) )
              if ( empty( $data[ 'langs' ] ) )
                continue;
              else
                $lang_content = array_shift( $data[ 'langs' ] );

            $short_id = apply_filters( 'turitop_booking_system_services_short_id', $data[ 'short_id' ], $data, $service_id, $this->tbs_data, $atts );
            $title = apply_filters( 'turitop_booking_system_services_title', $lang_content[ 'name' ], $data, $service_id, $this->tbs_data, $atts );
            $summary = apply_filters( 'turitop_booking_system_services_summary', $lang_content[ 'summary' ], $data, $service_id, $this->tbs_data, $atts );
            $description = apply_filters( 'turitop_booking_system_services_description', $lang_content[ 'description' ], $data, $service_id, $this->tbs_data, $atts );
            $image = apply_filters( 'turitop_booking_system_services_image', ( isset( $data[ 'images' ][ '0' ] ) ? $data[ 'images' ][ '0' ] : '' ), $data, $service_id, $this->tbs_data, $atts );

            $page_id = ( isset( $data[ 'page_id' ] ) ? $data[ 'page_id' ] : 0 );
            if ( $page_id == 'custom' ){
              $page_url = ( isset( $data[ 'service_custom_url' ] ) && ! empty( $data[ 'service_custom_url' ] ) ? $data[ 'service_custom_url' ] : get_site_url() );
            }else {

              $translate_args = array(
                'filter_id'       => 'wpml_object_id',
                'id'              => $page_id,
                'element_type'    => 'page',
                'return_original' => true,
                //'lang_code'       => 'en',
              );
              $page_id = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->translate_id( $translate_args );

              $post = get_post( $page_id );

              if ( $post instanceof WP_Post ) {
                 $page_url = get_permalink( $post );
              }
              else {
                $page_url = get_site_url();
              }

              $page_url .= ( isset( $data[ 'url_parameters' ] ) && ! empty( $data[ 'url_parameters' ] ) ? $data[ 'url_parameters' ] : '' );

            }

           $target_blank = ( isset( $data[ 'service_target_blank' ] ) && $data[ 'service_target_blank' ] == 'yes' ? 'target="_blank"' : '' );

           ob_start();
             ?>
             <div class="<?php echo $classes; ?>" data-service="<?php echo $short_id; ?>"  data-embed="<?php echo $embed; ?>" data-buttoncolor="<?php echo $this->tbs_data[ 'buttoncolor' ] ?>" ><?php echo $this->tbs_data[ 'button_text' ]; ?></div>
             <?php
           $button = ob_get_clean();
           $button = ( apply_filters( 'turitop_booking_system_template_button' ,$button, $data ) );

            $args = array(
              'this->tbs_data'  => $this->tbs_data,
              'data'            => $data,
              'lang_content'    => $lang_content,
              '$service_id'     => $service_id,
            );

            include apply_filters( 'turitop_booking_system_path_service_block', TURITOP_BOOKING_SYSTEM_TEMPLATE_PATH . "services/service_block_" . $template . ".php", $args, $template );

          } //foreach product_ids

          if ( $this->tbs_data[ 'content_service' ] == 'summary_content' ){

            echo '</div>';

          }

    		}

        /**
         * check woocommerce product
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         * @param shortcode attributes
         * @return void
         *
         */
    		public function check_woocommerce_product( $atts ) {

          if ( function_exists( 'WC' ) && isset( $atts[ 'wc_product_id' ] ) && ! empty( $atts[ 'wc_product_id' ] ) ){

              $tbs_wc_product_data = get_post_meta( $atts[ 'wc_product_id' ], '_turitop_booking_system_data', true );

              if ( isset( $tbs_wc_product_data[ 'activated' ] )
                  && $tbs_wc_product_data[ 'activated' ] == 'yes' ){

                  $this->tbs_data[ 'company' ] = ( isset( $tbs_wc_product_data[ 'company' ] ) ? $tbs_wc_product_data[ 'company' ] :  $this->tbs_data[ 'company' ] );
                  $this->tbs_data[ 'product_id' ] = ( isset( $tbs_wc_product_data[ 'product_id' ] ) ? $tbs_wc_product_data[ 'product_id' ] : null );
                  $this->tbs_data[ 'embed' ] = ( isset( $tbs_wc_product_data[ 'embed' ] ) ? ( $tbs_wc_product_data[ 'embed' ] == 'default' ? $this->tbs_data[ 'embed' ] : $tbs_wc_product_data[ 'embed' ] ) :  $this->tbs_data[ 'embed' ] );
                  $this->tbs_data[ 'button_text' ] = ( isset( $tbs_wc_product_data[ 'button_text' ] ) ? $tbs_wc_product_data[ 'button_text' ] :  $this->tbs_data[ 'button_text' ] );

                  $this->tbs_data[ 'buttoncolor_default' ] = ( isset( $tbs_wc_product_data[ 'buttoncolor' ] ) && $tbs_wc_product_data[ 'buttoncolor' ] != 'default' ? 'no' : $this->tbs_data[ 'buttoncolor_default' ] );

                  $this->tbs_data[ 'buttoncolor' ] = ( isset( $tbs_wc_product_data[ 'buttoncolor' ] ) ? ( $tbs_wc_product_data[ 'buttoncolor' ] == 'default' ? $this->tbs_data[ 'buttoncolor' ] : $tbs_wc_product_data[ 'buttoncolor' ] ) :  $this->tbs_data[ 'buttoncolor' ] );

              }

          }

    		}

        /**
         * Modify the script included with the WordPress enqueue
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0
         * @access public
         * @param
         * @return turitop script
         *
         */
        public function add_attributes_to_script( $tag, $handle, $src ) {

            if ( 'turitop_booking_system_frontend_js_url' === $handle && ! empty( $this->tbs_data ) && isset( $this->tbs_data[ 'company' ] ) && ! empty( $this->tbs_data[ 'company' ] ) ) {

                $tag = '<script type="text/javascript" id="js-turitop" src="' . esc_url( $src ) . '" data-lang="' . $this->tbs_data[ 'lang' ] . '" data-company="' . $this->tbs_data[ 'company' ] . '" data-ga="' . $this->tbs_data[ 'ga' ] . '" data-afftag="' . $this->tbs_data[ 'afftag' ] . '"></script>';

            }
            return $tag;

        }

	}

}
