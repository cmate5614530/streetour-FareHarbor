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
 * @class      turitop_bokking_system_main
 * @package    turitop
 * @since      Version 1.0.0
 * @author     Daniel Sánchez Sáez
 *
 */

if ( ! class_exists( 'turitop_bokking_system_main' ) ) {
    /**
     * Class turitop_bokking_system_main
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     * @since  1.0.0
     *
     */
    class turitop_bokking_system_main {

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
         * shortcodes Instance
         *
         * @var shortcodes
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         */
        public $shortcodes = null;

        /**
         * blocks Instance
         *
         * @var blocks
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         */
        public $blocks = null;

        /**
         * tbs_data
         *
         * @var array with turitop booking system data
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         */
        public $tbs_data = 'not_loaded';

        /**
         * dynamic_css_data
         *
         * @var array with turitop booking system style data
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         */
        public $dynamic_css_data = 'not_loaded';

        /**
         * strings tranalatables
         *
         * @var common_translations
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         */
        public $common_translations = array();

        /**
         * version_services
         *
         * @var boolean
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         */
        public $version_services = 'no';

        /**
         * button_style_elements
         *
         * @var boolean
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         */
        public $button_style_elements = array();

        /**
         * cart_style_elements
         *
         * @var boolean
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         * @access public
         */
        public $cart_style_elements = array();

        /**
         * __construct
         *
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
        public function __construct() {

          $this->tbs_data = $this->get_tbs_data();
            /* == Plugins Init === */
            add_action( 'init', array( $this, 'init' ) );

        }

        /**
         * Main plugin Instance
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return turitop_bokking_system_main main instance
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

            $this->tbs_data = $this->get_tbs_data();

            $this->set_common_translations();

            require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-shortcodes.php' );
            $this->shortcodes = turitop_booking_system_shortcodes::instance();

            include_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/blocks/class.turitop-booking-system-blocks.php' );
            $this->blokcs = turitop_booking_system_blocks::instance();

            if ( is_admin() ) {

                if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

                    require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-admin.php' );
                    $this->admin = new turitop_booking_system_admin();

                    require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-frontend.php');
                    $this->frontend = new turitop_booking_system_frontend();

                } else {

                    require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-admin.php' );
                    $this->admin = new turitop_booking_system_admin();

                }

            }
            else {

              require_once( TURITOP_BOOKING_SYSTEM_PATH . 'includes/class.turitop-booking-system-frontend.php');
              $this->frontend = new turitop_booking_system_frontend();

            }

        }

        /**
         *
         * get_version_services
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return array
         *
         */
        public function get_version_services() {

          $this->version_services = ( isset( $this->tbs_data[ 'activate_VIP' ] ) ? $this->tbs_data[ 'activate_VIP' ] : 'no' );
          return apply_filters( 'turitop_booking_system_version_services' , $this->version_services );

        }

        /**
         *
         * get_round_trip_booking
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return array
         *
         */
        public function get_round_trip_booking() {

          $this->round_trip_booking = ( isset( $this->tbs_data[ 'round_trip_activate' ] ) ? $this->tbs_data[ 'round_trip_activate' ] : 'no' );
          return apply_filters( 'turitop_booking_system_version_round_trip_booking' , $this->round_trip_booking );

        }

        /**
         *
         * get_round_trip_booking_data
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return array
         *
         */
        public function get_round_trip_booking_data() {

          $this->round_trip_booking_data = ( isset( $this->tbs_data[ 'round_trip_data_trips' ] ) ? $this->tbs_data[ 'round_trip_data_trips' ] : array() );
          return apply_filters( 'turitop_booking_system_version_round_trip_booking_data' , $this->round_trip_booking_data );

        }

        /**
         * set_button_style_elements
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function set_button_style_elements() {

          $this->button_style_elements = array(
            'button_main' => array(
                'value' => 'button_main',
                'text' => 'Button main',
                'prefix' => 'turitop_booking_system_button_main',
                'css_tag' => '.turitop_bswp_button_box_wrap .turitop_booking_system_box_button a.lightbox-button-turitop',
                'hover' => 'yes',
                'tooltip' => 'yes',
            ),
          );

        }

        /**
         *
         * get_button_style_elements
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.4
         * @access public
         * @param
         * @return array
         *
         */
        public function get_button_style_elements() {

          if ( empty( $this->button_style_elements ) )
            $this->set_button_style_elements();

          return $this->button_style_elements;

        }

        /**
         * set_cart_style_elements
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.4
         * @access public
         * @param
         * @return void
         *
         */
        public function set_cart_style_elements() {

          $this->cart_style_elements = array(
            'cart_main' => array(
                'value' => 'cart_main',
                'text' => 'Cart main',
                'prefix' => 'turitop_booking_system_cart_main',
                'css_tag' => 'a.turitop_booking_system_wp_cart',
                'hover' => 'yes',
                'tooltip' => 'yes',
            ),
          );

        }

        /**
         *
         * get_cart_style_elements
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.4
         * @access public
         * @param
         * @return array
         *
         */
        public function get_cart_style_elements() {

          if ( empty( $this->cart_style_elements ) )
            $this->set_cart_style_elements();

          return $this->cart_style_elements;

        }


        /**
         *
         * get_dynamic_css_data
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @access public
         * @param
         * @return array
         *
         */
        public function get_dynamic_css_data( $force = false ) {

            if ( $this->dynamic_css_data == 'not_loaded' || $force ){
              $dynamic_css_data = ( get_option( TURITOP_BOOKING_SYSTEM_SERVICE_STYLES_DATA . '_data' ) ? get_option( TURITOP_BOOKING_SYSTEM_SERVICE_STYLES_DATA . '_data' ) : array() );
              $button_dynamic_css_data = ( is_array( $dynamic_css_data ) ? $dynamic_css_data : array() );

              $dynamic_css_data = ( get_option( TURITOP_BOOKING_SYSTEM_SERVICE_DATA . '_data' ) ? get_option( TURITOP_BOOKING_SYSTEM_SERVICE_DATA . '_data' ) : array() );
              $cart_dynamic_css_data = ( is_array( $dynamic_css_data ) ? $dynamic_css_data : array() );

              $this->dynamic_css_data = array_merge( $button_dynamic_css_data, $cart_dynamic_css_data );
            }

            return $this->dynamic_css_data;

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
        public function generate_dynamic_site_css() {

            $this->dynamic_css_data = $this->get_dynamic_css_data();
            $this->button_style_elements = $this->get_button_style_elements();
            $this->cart_style_elements = $this->get_cart_style_elements();

            $dynamic_css = '';

            $array_fonts = array();
            foreach ( $this->dynamic_css_data as $key => $value ) {

              if ( strpos( $key, 'google_font' ) !== false && ! empty( $value ) ) {
                $array_fonts[ $value ] = $value;
              }

            }

            $google_fonts = '';
            foreach ( $array_fonts as $value ) {
              $google_fonts .= ( empty( $google_fonts ) ? $value : "|" . $value );
            }

            $this->tbs_data = $this->get_tbs_data( true );
            $this->tbs_data[ 'google_fonts' ] = $google_fonts;
            $this->update_tbs_data( $this->tbs_data );

            // BUTTON STYLES

            if ( isset( $this->dynamic_css_data[ 'box_button_custom_activate' ] ) && $this->dynamic_css_data[ 'box_button_custom_activate' ] == 'yes' )
              $dynamic_css .= TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_dynamic_css( $this->button_style_elements, $this->dynamic_css_data );

            // CART STYLES

            if ( isset( $this->tbs_data[ 'cart_custom_activate' ] ) && $this->tbs_data[ 'cart_custom_activate' ] == 'yes' ){

              $dynamic_css .= TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_dynamic_css( $this->cart_style_elements, $this->dynamic_css_data );

            }

            $dynamic_css = ( isset( $this->dynamic_css_data[ 'custom_css' ] ) && ! empty( $this->dynamic_css_data[ 'custom_css' ] ) ? $dynamic_css . PHP_EOL . $this->dynamic_css_data[ 'custom_css' ] . PHP_EOL : $dynamic_css );

            $dynamic_css = apply_filters( 'turitop_booking_system_dynamic_css', $dynamic_css );

            $child_path = get_stylesheet_directory();
            file_put_contents( $child_path . '/turitop-dynamic-style.css', $dynamic_css );

        }

        /**
         *
         * get tbs data
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return array
         *
         */
        public function get_tbs_data( $force = false ) {

            if ( $this->tbs_data == 'not_loaded' || $force ){
              $tbs_data = ( get_option( 'turitop_booking_system_settings_data' ) ? get_option( 'turitop_booking_system_settings_data' ) : array() );
              $this->tbs_data = ( is_array( $tbs_data ) ? $tbs_data : array() );
            }

            return $this->tbs_data;

        }

        /**
         *
         * update tbs data
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.3
         * @access public
         * @param
         * @return array
         *
         */
        public function update_tbs_data( $data ) {

          $this->tbs_data = $data;
          update_option( TURITOP_BOOKING_SYSTEM_SERVICE_DATA . '_data', $data );

        }

        /**
         *
         * set_common_translations
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return array
         *
         */
        public function set_common_translations() {

            $this->common_translations = array(
                // GENERAL SETTINGS
                'company' => _x( 'TuriTop Company ID', 'common translations', 'turitop-booking-system' ),
                'company_desc' => _x( 'Introduce your Turitop company ID which is located on the top right corner of your control panel.', 'common translations', 'turitop-booking-system' ),
                'company_tooltip' => _x( "If you don't have an account yet. Please, go to", 'common translations', 'turitop-booking-system' ) . ' <a href="https://turitop.com" target="_blank">turitop.com</a> ' . _x( "and sign up for free.", 'common translations', 'turitop-booking-system' ),
                'secret_key' => _x( 'TuriTop Secret key', 'common translations', 'turitop-booking-system' ),
                'secret_key_desc' => _x( 'Introduce the secret key in order to synchronize the TuriTop services on your WordPres installation', 'common translations', 'turitop-booking-system' ),
                'product_id' => _x( 'Turitop service ID', 'common translations', 'turitop-booking-system' ),
                'product_id_desc' => _x( 'Introduce your Turitop service ID.', 'common translations', 'turitop-booking-system' ),
                'wc_product_id' => _x( 'Woocommerce product ID', 'common translations', 'turitop-booking-system' ),
                'ga' => _x( 'Google analytics', 'common translations', 'turitop-booking-system' ),
                'ga_desc' => _x( 'Select if you are using google analytics.', 'common translations', 'turitop-booking-system' ),
                'ga_tooltip' => _x( 'Connect TuriTop with Google Analytics and track the source of each booking before it reaches your website.', 'common translations', 'turitop-booking-system' ),
                'yes' => _x( 'Yes', 'common translations', 'turitop-booking-system' ),
                'no' => _x( 'No', 'common translations', 'turitop-booking-system' ),
                'embed' => _x( 'Display as', 'common translations', 'turitop-booking-system' ),
                'embed_desc' => _x( 'Select how to display your turitop booking system.', 'common translations', 'turitop-booking-system' ),
                'embed_tooltip' => _x( 'Select how to display your turitop booking system. You have 2 options: Embed a booking box that will load alongside your website and display the Calendar or a button.', 'common translations', 'turitop-booking-system' ),
                'box' => _x( 'Box', 'common translations', 'turitop-booking-system' ),
                'button' => _x( 'Button', 'common translations', 'turitop-booking-system' ),
                'gift' => _x( 'Gift voucher', 'common translations', 'turitop-booking-system' ),
                'redeem' => _x( 'Redeem voucher', 'common translations', 'turitop-booking-system' ),

                // BUTTON SETTINGS
                'button_text' => _x( 'Button text', 'common translations', 'turitop-booking-system' ),
                'button_text_desc' => _x( 'Introduce the text you want to display in the button.', 'common translations', 'turitop-booking-system' ),
                'buttoncolor' => _x( 'Button color', 'common translations', 'turitop-booking-system' ),
                'buttoncolor_desc' => _x( 'Select the color of the button. Select the "customize" option in order to adjust your own button', 'common translations', 'turitop-booking-system' ),
                'green' => _x( 'Green', 'common translations', 'turitop-booking-system' ),
                'orange' => _x( 'Orange', 'common translations', 'turitop-booking-system' ),
                'blue' => _x( 'Blue', 'common translations', 'turitop-booking-system' ),
                'red' => _x( 'Red', 'common translations', 'turitop-booking-system' ),
                'yellow' => _x( 'Yellow', 'common translations', 'turitop-booking-system' ),
                'black' => _x( 'Black', 'common translations', 'turitop-booking-system' ),
                'white' => _x( 'White', 'common translations', 'turitop-booking-system' ),
                'custom' => _x( 'Customized', 'common translations', 'turitop-booking-system' ),
                'default' => _x( 'Default', 'common translations', 'turitop-booking-system' ),

                // BUTTON CUSTOMIZATION
                'box_button_custom_activate' => _x( 'Activate', 'common translations', 'turitop-booking-system' ),
                'button_custom_class' => _x( 'Custom class', 'common translations', 'turitop-booking-system' ),
                'button_custom_class_tooltip' => _x( 'Introduce a custom class you want to use for your button', 'common translations', 'turitop-booking-system' ),
                'button_image_default' => _x( 'Button image url settings', 'common translations', 'turitop-booking-system' ),
                'button_image_url' => _x( 'Button image url', 'common translations', 'turitop-booking-system' ),
                'button_image_activate' => _x( 'Activate button image', 'common translations', 'turitop-booking-system' ),
                'button_background_color' => _x( 'Background color', 'common translations', 'turitop-booking-system' ),
                'button_background_color_desc' => _x( 'Introduce the background of the button', 'common translations', 'turitop-booking-system' ),
                'button_border_color' => _x( 'Border color', 'common translations', 'turitop-booking-system' ),
                'button_border_color_desc' => _x( 'Introduce the border color of the button', 'common translations', 'turitop-booking-system' ),
                'button_font_color' => _x( 'Font color', 'common translations', 'turitop-booking-system' ),
                'button_font_color_desc' => _x( 'Introduce the color of the button text', 'common translations', 'turitop-booking-system' ),
                'button_font_size' => _x( 'Font size', 'common translations', 'turitop-booking-system' ),
                'button_font_size_desc' => _x( 'Introduce the size of the font button text ( examples: 20px, 2rem, 2em )', 'common translations', 'turitop-booking-system' ),
                'button_font_weight' => _x( 'Font weight', 'common translations', 'turitop-booking-system' ),
                'button_font_weight_desc' => _x( 'Introduce the weight of the font button text', 'common translations', 'turitop-booking-system' ),
                'button_radio_square' => _x( 'Border radio', 'common translations', 'turitop-booking-system' ),
                'button_radio_square_desc' => _x( 'Choose the how you want to display the corners of your button', 'common translations', 'turitop-booking-system' ),
                'radio' => _x( 'radio', 'common translations', 'turitop-booking-system' ),
                'square' => _x( 'square', 'common translations', 'turitop-booking-system' ),
                'button_min_height' => _x( 'Minimun heigh', 'common translations', 'turitop-booking-system' ),
                'button_min_height_desc' => _x( 'Introduce the minimun heigh of the button  examples: 20px, 2rem, 2em ).', 'common translations', 'turitop-booking-system' ),
                'button_min_width' => _x( 'Minimun width', 'common translations', 'turitop-booking-system' ),
                'button_min_width_desc' => _x( 'Introduce the minimun width of the button  examples: 20px, 2rem, 2em )', 'common translations', 'turitop-booking-system' ),

                // BUTTON CUSTOMIZATION HOVER
                'button_background_color_hover' => _x( 'Background color hover', 'common translations', 'turitop-booking-system' ),
                'button_background_color_hover_desc' => _x( 'Introduce the background of the button when mouser hover', 'common translations', 'turitop-booking-system' ),
                'button_border_color_hover' => _x( 'Color hover', 'common translations', 'turitop-booking-system' ),
                'button_border_color_hover_desc' => _x( 'Introduce the border color of the button when mouse hover', 'common translations', 'turitop-booking-system' ),
                'button_font_color_hover' => _x( 'Font color hover', 'common translations', 'turitop-booking-system' ),
                'button_font_color_hover_desc' => _x( 'Introduce the color of the button text when mouse hover', 'common translations', 'turitop-booking-system' ),
                'button_font_size_hover' => _x( 'Font size hover', 'common translations', 'turitop-booking-system' ),
                'button_font_size_hover_desc' => _x( 'Introduce the size of the font button text when mouse hover ( examples: 20px, 2rem, 2em )', 'common translations', 'turitop-booking-system' ),
                'button_font_weight_hover' => _x( 'Font weight hover', 'common translations', 'turitop-booking-system' ),
                'button_font_weight_hover_desc' => _x( 'Introduce the weight of the font button text when mouse hover', 'common translations', 'turitop-booking-system' ),

                // CART SETTINGS
                'cart_on_menu' => _x( 'Cart on menu', 'common translations', 'turitop-booking-system' ),
                'cart_menu_content' => _x( 'Cart elements', 'common translations', 'turitop-booking-system' ),
                'cart_menu_content_desc' => _x( 'Choose which elements you want to display on the cart ( Icon, Text and Counter )', 'common translations', 'turitop-booking-system' ),
                'cart_menu_selected' => _x( 'Choose menu', 'common translations', 'turitop-booking-system' ),
                'cart_menu_position' => _x( 'Menu position', 'common translations', 'turitop-booking-system' ),
                'first_menu_pos' => _x( 'First menu item', 'common translations', 'turitop-booking-system' ),
                'last_menu_pos' => _x( 'Last menu item', 'common translations', 'turitop-booking-system' ),
                'cart_custom_activate' => _x( 'Activate', 'common translations', 'turitop-booking-system' ),
                'carticoncolor' => _x( 'Icon color', 'common translations', 'turitop-booking-system' ),
                'carticoncolor_tooltip' => _x( 'Select the color of the icon.', 'common translations', 'turitop-booking-system' ),
                'cartbuttoncolor' => _x( 'Background color', 'common translations', 'turitop-booking-system' ),
                'cartbuttoncolor_tooltip' => _x( 'Select the color of the backgroubd.', 'common translations', 'turitop-booking-system' ),

                // CART CUSTOMIZATION
                'cart_checkbox_icon' => _x( 'Icon', 'common translations', 'turitop-booking-system' ),
                'cart_checkbox_text' => _x( 'Text', 'common translations', 'turitop-booking-system' ),
                'cart_text' => _x( 'Cart name', 'common translations', 'turitop-booking-system' ),
                'cart_text_tooltip' => _x( 'Type the text you want to display on the cart', 'common translations', 'turitop-booking-system' ),
                'cart_checkbox_counter' => _x( 'Counter', 'common translations', 'turitop-booking-system' ),
                'cart_background_color' => _x( 'Background color', 'common translations', 'turitop-booking-system' ),
                'cart_background_color_desc' => _x( 'Introduce the background of the cart', 'common translations', 'turitop-booking-system' ),
                'cart_border_color' => _x( 'Border color', 'common translations', 'turitop-booking-system' ),
                'cart_border_color_desc' => _x( 'Introduce the border color of the cart', 'common translations', 'turitop-booking-system' ),
                'cart_font_color' => _x( 'Font color', 'common translations', 'turitop-booking-system' ),
                'cart_font_color_desc' => _x( 'Introduce the color of the cart content', 'common translations', 'turitop-booking-system' ),
                'cart_min_height' => _x( 'Minimun heigh', 'common translations', 'turitop-booking-system' ),
                'cart_min_height_desc' => _x( 'Introduce the minimun heigh of the cart  examples: 20px, 2rem, 2em )', 'common translations', 'turitop-booking-system' ),
                'cart_min_width' => _x( 'Minimun width', 'common translations', 'turitop-booking-system' ),
                'cart_min_width_desc' => _x( 'Introduce the minimun width of the cart  examples: 20px, 2rem, 2em )', 'common translations', 'turitop-booking-system' ),
                'cart_radio_square' => _x( 'Border radio', 'common translations', 'turitop-booking-system' ),
                'cart_radio_square_desc' => _x( 'Choose the how you want to display the corners of your cart', 'common translations', 'turitop-booking-system' ),

                // CART CUSTOMIZATION HOVER
                'cart_background_color_hover' => _x( 'Background color hover', 'common translations', 'turitop-booking-system' ),
                'cart_background_color_hover_desc' => _x( 'Introduce the background of the cart when mouse hover', 'common translations', 'turitop-booking-system' ),
                'cart_border_color_hover' => _x( 'Border color hover ', 'common translations', 'turitop-booking-system' ),
                'cart_border_color_hover_desc' => _x( 'Introduce the border color of the cart when mouse hover', 'common translations', 'turitop-booking-system' ),
                'cart_font_color_hover' => _x( 'Font color hover', 'common translations', 'turitop-booking-system' ),
                'cart_font_color_hover_desc' => _x( 'Introduce the color of the cart content when mouse hover', 'common translations', 'turitop-booking-system' ),

                //ADVANCED SETTINGS
                'advanced_activate' => _x( 'Activate', 'common translations', 'turitop-booking-system' ),
                'advanced_aditional_css' => _x( 'Additional CSS', 'common translations', 'turitop-booking-system' ),
                'advanced_aditional_css_desc' => _x( 'Introduce your additional css you may want to use to cusomize your TuriTop Booking System', 'common translations', 'turitop-booking-system' ),
                'activate_VIP' => _x( 'Activate VIP', 'common translations', 'turitop-booking-system' ),

                 'additional_data' => _x( 'Additional data', 'common translations', 'turitop-booking-system' ),
                 'additional_data_desc' => _x( 'Introduce additional data', 'common translations', 'turitop-booking-system' ),

                // SYNCHRONIZATION
                'services_syncrhronize_langs' => _x( 'Choose languages', 'common translations', 'turitop-booking-system' ),
                'services_syncrhronize_langs_desc' => _x( 'Choose the languages you want to use to synchronize your TuriTop services with your WordPres installation', 'common translations', 'turitop-booking-system' ),
                'create_service_page_activate' => _x( 'Service pages', 'common translations', 'turitop-booking-system' ),

                // COMMON
                'custom_url' => _x( 'Custom URL', 'common translations', 'turitop-booking-system' ),
            );

        }

    }
}
