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
 * @class      turitop_booking_system_frontend
 * @package    turitop
 * @since      Version 1.0.0
 * @author     Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'turitop_booking_system_frontend' ) ) {
    /**
     *
     * @author Daniel Sanchez Saez
     */
    class turitop_booking_system_frontend {

        /**
         * turitop system data
         *
         * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
        public $tbs_data = null;

        /**
         * __construct
         *
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
        public function __construct() {

            require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-frontend-ajax.php' );
            $this->admin_ajax = turitop_booking_system_frontend_ajax::instance();

            $this->tbs_data = TURITOP_BS()->get_tbs_data();

            $lang_array = explode( '_', get_locale() );
            $lang = array_shift( $lang_array );

            $this->tbs_data[ 'lang' ] = ( isset( $atts[ 'lang' ] ) ? $atts[ 'lang' ] : $lang );

            /* ====== ADD TURITOP ATTRIBUTES TO THE JS ====== */
            add_filter( 'script_loader_tag',
                        array( $this, 'add_attributes_to_script'),
                        99, 3 );

            /* ====== Checking if WooCommerce is installed ====== */
            if ( function_exists( 'WC' ) ) {

                add_action( 'woocommerce_loop_add_to_cart_link',
                            array( $this, 'check_turitop_woocommerce_settings_product_shop_page_cart_link' ), 10, 3 );

                add_action( 'woocommerce_before_single_product',
                            array( $this, 'check_turitop_woocommerce_settings_product' ) );

    		    }

            //add_filter( 'wp_get_nav_menu_items', array( $this, 'custom_nav_menu_items_call_back' ), 20, 2 );

            add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el_call_back' ), 20, 4 );


            /* ====== ENQUEUE STYLES AND JS ====== */
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_dynamic_scripts' ), 20 );

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

        }

        /**
         * Check turitop woocommerce settings product shop page cart link
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
         public function walker_nav_menu_start_el_call_back( $new_item_output, $new_item, $depth, $args ) {

             $cart_on_menu = ( isset( $this->tbs_data[ 'cart_on_menu' ] ) ? $this->tbs_data[ 'cart_on_menu' ] : 'no' );
             $cart_menu_items = ( isset( $this->tbs_data[ 'cart_menu_items' ] ) ? $this->tbs_data[ 'cart_menu_items' ] : array() );

             if ( in_array( $new_item->ID, $cart_menu_items ) && $cart_on_menu == 'yes' ){

                 $cartbuttoncolor = ( isset( $this->tbs_data[ 'cartbuttoncolor' ] ) ? $this->tbs_data[ 'cartbuttoncolor' ] : 'green' );
                 $carticoncolor = ( isset( $this->tbs_data[ 'carticoncolor' ] ) ? $this->tbs_data[ 'carticoncolor' ] : 'white' );

                 if ( isset( $this->tbs_data[ 'cart_custom_activate' ] ) && $this->tbs_data[ 'cart_custom_activate' ] == 'yes' ){

                   $new_item_output = '<span style="display: none;" id="turitop-booking-system-cart" class="menu-item load-turitop turitop_booking_system_cart ' . apply_filters( 'turitop_booking_system_box_button_custom_class', '' ) . '" data-embed="cart" data-cartbuttoncolor="' . $cartbuttoncolor . '" data-carticoncolor="' . $carticoncolor . '"></span>';

                   $new_item_output = $new_item_output . '<a href="https://www.turitop.com" class="turitop_booking_system_wp_cart">';

                   if ( isset( $this->tbs_data[ 'cart_checkbox_icon' ] ) && $this->tbs_data[ 'cart_checkbox_icon' ] == 'yes' )
                    $new_item_output = $new_item_output . '<i class="fa fa-shopping-cart"></i>';

                   if ( isset( $this->tbs_data[ 'cart_checkbox_text' ] ) && $this->tbs_data[ 'cart_checkbox_text' ] == 'yes' )
                    $new_item_output = $new_item_output . '<span class="turitop_booking_system_cart_text">' . ( isset( $this->tbs_data[ 'cart_text' ] ) ? $this->tbs_data[ 'cart_text' ] : '' ) . '</span>';

                   if ( isset( $this->tbs_data[ 'cart_checkbox_counter' ] ) && $this->tbs_data[ 'cart_checkbox_counter' ] == 'yes' )
                    $new_item_output = $new_item_output . '<span class="turitop_booking_system_cart_counter"><img style="width: 10px;" src="' . TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/images/black-loader.gif' . '" /></span>';

                   $new_item_output = $new_item_output . '</a>';
                 }
                 else {

                   $new_item_output = '<span id="turitop-booking-system-cart" class="menu-item load-turitop turitop_booking_system_cart ' . apply_filters( 'turitop_booking_system_box_button_custom_class', '' ) . '" data-embed="cart" data-cartbuttoncolor="' . $cartbuttoncolor . '" data-carticoncolor="' . $carticoncolor . '"></span>';

                 }

             }


             return $new_item_output;

         }

        /**
         * Check turitop woocommerce settings product shop page cart link
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
         public function custom_nav_menu_items_call_back( $items, $menu ) {

             if ( $this->tbs_data[ 'cart_on_menu' ] == 'yes' && $menu->term_id == $this->tbs_data[ 'cart_menu_selected' ] ){

                // Insert new nav_menu_item

                $new_item = new stdClass();
                $new_item->ID = 'turitop_bs_cart';
                $new_item->db_id = $new_item->ID;
                $new_item->title = 'tbs';
                $new_item->url = '#';
                $new_item->menu_item_parent = 0;
                $new_item->type = '';
                $new_item->object = '';
                $new_item->object_id = '';
                $new_item->classes = '';
                $new_item->target = '';
                $new_item->attr_title = '';
                $new_item->description = '';
                $new_item->xfn = '';
                $new_item->status = '';

                if ( $this->tbs_data[ 'cart_menu_position' ] == 'first_menu_pos' ){

                    $new_item->menu_order = 1;
                    $new_items = array();
                    $new_items[] = $new_item;
                    foreach ( $items as $item ) {
                        $item->menu_order = $item->menu_order + 1;
                        $new_items[] = $item;
                    }

                    $items = $new_items;

                }
                else{

                    $highest = 0;
                    foreach ( $items as $item ) {
                        $highest = ( $item->menu_order > $highest ? $item->menu_order : $highest );
                    }
                    $new_item->menu_order = $highest + 1;
                    $items[] = $new_item;

                }

             }

             return $items;

         }

        /**
         * Check turitop woocommerce settings product shop page cart link
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
         public function check_turitop_woocommerce_settings_product_shop_page_cart_link( $link, $product, $args ) {

             if ( $product->get_type() == 'simple' ){

                     $tbs_product_data = get_post_meta( $product->get_id(), '_turitop_booking_system_data', true );

                     if ( isset( $tbs_product_data[ 'activated' ] ) && $tbs_product_data[ 'activated' ] == 'yes' ){

                         $link = "<a class='button' href='" . $product->get_permalink() . "'>" . $tbs_product_data[ 'button_text' ] . "</a>";

                         $link = apply_filters( 'turitop_booking_system_product_shop_page_cart_link_filter', $link, $product );

                     }

             }

             return $link;

         }

        /**
         * Check turitop woocommerce settings product
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0
         * @access public
         * @param
         * @return void
         *
         */
        public function check_turitop_woocommerce_settings_product() {

            global $product;

            switch ( $product->get_type() ) {

                case 'simple':

                    $tbs_product_data = get_post_meta( $product->get_id(), '_turitop_booking_system_data', true );

                    if ( isset( $tbs_product_data[ 'activated' ] ) && $tbs_product_data[ 'activated' ] == 'yes' ){

                      if ( ! isset( $tbs_product_data[ 'display_price' ] ) || $tbs_product_data[ 'display_price' ] != 'yes' )
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

                      remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

                      if ( $tbs_product_data[ 'embed' ] == 'button' )
                        add_action( 'woocommerce_single_product_summary', array( $this, 'display_turitop_booking_system_button' ), 99 );

                      if ( $tbs_product_data[ 'embed' ] == 'box' )
                        add_action( 'woocommerce_after_single_product_summary', array( $this, 'display_turitop_booking_system_box' ), 5 );

                    }

                    break;

                case 'variable':

                    /*remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                    //remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
                    //remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
                    add_action( 'woocommerce_single_variation', array( $this, 'display_turitop_booking_system' ), 10 );*/

                    break;
            }


        }

        /**
         * Display turitop booking system button
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0
         * @access public
         * @param
         * @return turitop booking system
         *
         */
        public function display_turitop_booking_system_button() {

            global $product;

            ?> <div class="turitop_booking_system_button_woocommerce_wrap"><?php

              echo do_shortcode( '[turitop_booking_system wc_product_id="' . $product->get_id() . '"]' );

            ?> </div><?php

        }

        /**
         * Display turitop booking system box
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0
         * @access public
         * @param
         * @return turitop booking system
         *
         */
        public function display_turitop_booking_system_box() {

            global $product;

            ?> <div class="turitop_booking_system_box_woocommerce_wrap"><?php

              echo do_shortcode( '[turitop_booking_system wc_product_id="' . $product->get_id() . '"]' );

            ?> </div><?php

        }

        /**
         *
         * Enqueues dynamic scripts and styles.
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @access public
         * @param
         * @return array
         *
         */
        public function enqueue_scripts_dynamic_scripts() {

          do_action( 'turitop_booking_system_wp_enqueue_dynamic_style_before' );

          // DYNAMIC CSS

          $child_path = get_stylesheet_directory();

          if ( file_exists( $child_path . '/turitop-dynamic-style.css' ) ){
            wp_register_style( 'turitop_booking_system_dynamic_style', apply_filters( 'turitop_booking_system_dynamic_style_filter', get_stylesheet_directory_uri() . '/turitop-dynamic-style.css' ), array(), TURITOP_BOOKING_SYSTEM_VERSION );
            wp_enqueue_style( 'turitop_booking_system_dynamic_style' );
          }

          do_action( 'turitop_booking_system_wp_enqueue_dynamic_style_after' );

        }

        public function enqueue_scripts() {

          $min = ( defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min' );

            //if ( ( isset( $this->tbs_data[ 'box_button_custom_activate' ] ) && $this->tbs_data[ 'box_button_custom_activate' ] == 'yes' ) || ( isset( $this->tbs_data[ 'cart_custom_activate' ] ) && $this->tbs_data[ 'cart_custom_activate' ] == 'yes' ) || ( isset( $this->tbs_data[ 'advanced_activate' ] ) && $this->tbs_data[ 'advanced_activate' ] == 'yes' ) ){

                /* ====== Style ====== */

                wp_register_style( 'turitop_booking_system_frontend_css', apply_filters( 'turitop_booking_system_frontend_css_filter', TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/css/turitop-bs-frontend' . $min . '.css' ), array(), TURITOP_BOOKING_SYSTEM_VERSION );
                wp_enqueue_style( 'turitop_booking_system_frontend_css' );

                if ( isset( $this->tbs_data[ 'dynamic_css' ] ) && ! empty( $this->tbs_data[ 'dynamic_css' ] ) )
                  wp_add_inline_style( 'turitop_booking_system_frontend_css', $this->tbs_data[ 'dynamic_css' ] );

            //}

            if ( isset( $this->tbs_data[ 'cart_custom_activate' ] ) && $this->tbs_data[ 'cart_custom_activate' ] == 'yes' ){

              // CSS FONT AWESOME
              TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->enqueue_font_awesome();

            }

              wp_register_script( 'turitop_booking_system_frontend_js_url',
                  apply_filters( 'turitop_booking_system_frontend_js_url_filter', TURITOP_BOOKING_SYSTEM_JS_URL ), array(),
                  TURITOP_BOOKING_SYSTEM_VERSION,
                  true );

              wp_enqueue_script( 'turitop_booking_system_frontend_js_url' );

              if ( isset( $this->tbs_data[ 'cart_custom_activate' ] ) && $this->tbs_data[ 'cart_custom_activate' ] == 'yes' ){

                wp_register_script( 'turitop_booking_system_frontend_js',
                    apply_filters( 'turitop_booking_system_frontend_js_filter', TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/js/turitop-bs-frontend' . $min . '.js' ), array(
                      'jquery',
                      'jquery-ui-sortable'
                    ),
                    TURITOP_BOOKING_SYSTEM_VERSION,
                    true );

                wp_enqueue_script( 'turitop_booking_system_frontend_js' );

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

              $lang_array = explode( '_', get_locale() );
              $lang = array_shift( $lang_array );
              $afftag = ( isset( $this->tbs_data[ 'afftag' ] ) && ! empty( $this->tbs_data[ 'afftag' ] ) ? $this->tbs_data[ 'afftag' ] : 'ttafid' );
              $ga = ( isset( $this->tbs_data[ 'ga' ] ) && ! empty( $this->tbs_data[ 'ga' ] ) ? $this->tbs_data[ 'ga' ] : '' );

              $tag = '<script type="text/javascript" id="js-turitop" src="' . esc_url( $src ) . '" data-lang="' . $lang . '" data-company="' . $this->tbs_data[ 'company' ] . '" data-ga="' . $ga . '" data-afftag="' . $afftag . '"></script>';

            }
            return $tag;

        }

    }

}
