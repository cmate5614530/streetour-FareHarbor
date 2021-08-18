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
 * @class      turitop_booking_system_settings
 * @package    turitop
 * @since      Version 1.0.0
 * @author	   Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'turitop_booking_system_settings' ) ) {
	/**
	 * Class turitop_booking_system_settings
	 *
	 * @author Daniel Sánchez Sáez <dssaez@gmail.com>
	 */
	class turitop_booking_system_settings {

		/**
		 * Main Instance
		 *
		 * @var turitop_booking_system_settings
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
		public $integralwebsite_inputs_form_button = null;

    /**
		 * integralwebsite_inputs_form
		 *
		 * @var instance admin menu inputs
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $integralwebsite_inputs_form_cart = null;

    /**
		 * integralwebsite_inputs_form
		 *
		 * @var instance admin menu inputs
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $integralwebsite_inputs_form_services = null;

    /**
		 * integralwebsite_inputs_form
		 *
		 * @var instance admin menu inputs
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
		 */
		public $integralwebsite_inputs_form_advance = null;

        /**
		 * Main Instance
		 *
		 * @var turitop_booking_system_settings
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

            add_filter( 'integralwebsite_inputs_post_check_form_update', array( $this, 'integralwebsite_inputs_post_check_form_update_call_back' ), 10, 2 );

            add_action( 'integralwebsite_wp_inputs_check_form_submited_after', array( $this, 'integralwebsite_wp_inputs_check_form_submited_after_call_back' ), 10, 1 );

            $this->integralwebsite_inputs_form->init();
            $this->display_general_settings();

            $this->integralwebsite_inputs_form_button->init();
            $this->display_button_settings();

            $this->integralwebsite_inputs_form_cart->init();
            $this->display_cart_settings();

            /*
            $this->integralwebsite_inputs_form_advance->init();
            $this->display_advance_settings();
            */

		}

    /**
     * generate_dynamic_site_css
     *
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @since 1.0.3
     * @access public
     * @param
     * @return void
     *
     */
    public function integralwebsite_wp_inputs_check_form_submited_after_call_back( $data ) {

      if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'button' ){
        TURITOP_BS()->generate_dynamic_site_css();
      }

      if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'cart' && isset( $data[ 'cart_custom_activate' ] ) && $data[ 'cart_custom_activate' ] = 'yes' ){
        TURITOP_BS()->generate_dynamic_site_css();
      }

    }

    /**
     * integralwebsite_inputs_post_check_form_update_call_back
     *
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @since 1.0.3
     * @access public
     * @param
     * @return void
     *
     */
    public function integralwebsite_inputs_post_check_form_update_call_back( $result, $data ) {

      if ( isset( $data[ 'integralwebsite_check_form' ] ) ){

        switch (  $data[ 'integralwebsite_check_form' ] ) {

          case 'general':

            if ( isset( $data[ 'company' ] ) )
              return true;
            else
              return false;

            break;

          case 'button':

            if ( isset( $data[ 'box_button_custom_activate' ] ) )
              return true;
            else
              return false;

            break;

          case 'cart':

            if ( isset( $data[ 'cart_on_menu' ] ) )
              return true;
            else
              return false;

            break;

          case 'service_pages':

            if ( isset( $data[ 'content_service' ] ) )
              return true;
            else
              return false;

            break;

          case 'advanced':

            if ( isset( $data[ 'activate_VIP' ] ) )
              return true;
            else
              return false;

            break;

          default:

            return false;
            break;
        }

      }

      return false;

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

        $languages = array(
            array(
                'value' => 'en',
                'text' => _x( 'English', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'es',
                'text' => _x( 'Spanish', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'de',
                'text' => _x( 'German', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'ru',
                'text' => _x( 'Russian', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'it',
                'text' => _x( 'Italian', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'pt',
                'text' => _x( 'Portuguese', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'fr',
                'text' => _x( 'French', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'pl',
                'text' => _x( 'Polish', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'zh',
                'text' => _x( 'Chinese', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'tr',
                'text' => _x( 'Turkish', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'sv',
                'text' => _x( 'Swedish', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'fi',
                'text' => _x( 'Finnish', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'no',
                'text' => _x( 'Norwegian', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'el',
                'text' => _x( 'Greek', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'sk',
                'text' => _x( 'Slovak', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'cz',
                'text' => _x( 'Czech', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'nl',
                'text' => _x( 'Dutch', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'da',
                'text' => _x( 'Danish', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'eu',
                'text' => _x( 'Basque', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'ca',
                'text' => _x( 'Catalan', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'gl',
                'text' => _x( 'Galician', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'ja',
                'text' => _x( 'Japanese', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'ko',
                'text' => _x( 'Korean', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'hi',
                'text' => _x( 'Hindi', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'th',
                'text' => _x( 'Thai', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'ar',
                'text' => _x( 'Arabic', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'iw',
                'text' => _x( 'Hebrew', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'hr',
                'text' => _x( 'Croatian', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'lv',
                'text' => _x( 'Latvian', 'settings', 'turitop-booking-system' ),
            ),
            array(
                'value' => 'et',
                'text' => _x( 'Estonian', 'settings', 'turitop-booking-system' ),
            ),
        );

        $wp_menus = get_terms('nav_menu');
        $tbs_menus = array();

        foreach( $wp_menus as $menu ){
            $tbs_menus[] = array(
                'value' => $menu->term_id,
                'text' => $menu->name,
            );
        }

        $this->inputs = array(
            // GENERAL SETTINGS
            'integralwebsite_check_form' => array(
                'input_type' => 'hidden',
                'input_value' => 'general',
            ),
            'company' => array(
                'input_type' => 'text',
            ),
            'ga' => array(
                'input_type'  => 'select',
                'input_class' => 'integralwebsite_input_select integralwebsite_input_select2',
                'default'     => 'no',
                'options'   => array(
                    array(
                        'value' => 'yes',
                    ),
                    array(
                        'value' => 'no',
                    ),
                ),
            ),
            'embed' => array(
                'input_type' => 'radio',
                'default'     => 'box',
                'radios'   => array(
                    array(
                        'value' => 'box',
                    ),
                    array(
                        'value' => 'button',
                    ),
                ),
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

        /* ============== BUTTON ================ */
        $this->slug_button = TURITOP_BOOKING_SYSTEM_SERVICE_STYLES_DATA;
        $this->inputs_button = array(
          'integralwebsite_check_form' => array(
              'input_type' => 'hidden',
              'input_value' => 'button',
          ),
            'button_text' => array(
                'input_type' => 'text',
                'default'     => __( 'Book now', 'main settings', 'turitop-booking-system' ),
            ),
            'buttoncolor' => array(
                'input_type' => 'select',
                'default'     => 'green',
                'input_class' => 'integralwebsite_input_select integralwebsite_input_select2 turitop_bs_buttoncolor_select',
                'options'   => array(
                    array(
                        'value' => 'green',
                    ),
                    array(
                        'value' => 'orange',
                    ),
                    array(
                        'value' => 'blue',
                    ),
                    array(
                        'value' => 'red',
                    ),
                    array(
                        'value' => 'yellow',
                    ),
                    array(
                        'value' => 'black',
                    ),
                    array(
                        'value' => 'white',
                    ),
                    array(
                        'value' => 'custom',
                    ),
                ),
            ),
            // BUTTON CUSTOMIZATION-----------+++++++
            'box_button_custom_activate' => array(
                'input_type' => 'checkbox',
                'input_description' => _x( 'check this box to activate the button customization options', 'common translations', 'turitop-booking-system' ),
            ),
            'button_custom_class' => array(
                'input_type' => 'text',
            ),
            'button_image_activate' => array(
                'input_type' => 'checkbox',
                'input_description' => _x( 'check this box to activate the button image', 'common translations', 'turitop-booking-system' ),
            ),
            'button_image_id' => array(
                'input_type'  => 'hidden',
                'input_class' => 'integralwebsite_theme_choose_media_id',
            ),
            'button_image_url' => array(
                'input_type'  => 'hidden',
                'input_class' => 'integralwebsite_theme_choose_media_url',
            ),
            'button_image_button' => array(
                'input_type'  => 'submit',
                'input_class' => 'integralwebsite_theme_choose_media_button',
                'input_value' => _x( 'Choose an image', 'button settings', 'turitop-booking-system' ),
                'title'       => _x( 'Image', 'button settings', 'turitop-booking-system' ),
                'description' => _x( 'Select a image to display as a button', 'button settings', 'turitop-booking-system' ),
            ),
        );

        $this->button_style_elements = TURITOP_BS()->get_button_style_elements();

        foreach ( $this->button_style_elements as $element ) {
          $this->inputs_button = array_merge( $this->inputs_button, TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_css_inputs( $element ) );
          if ( isset( $element[ 'hover' ] ) && $element[ 'hover' ] == 'yes' )
            $this->inputs_button = array_merge( $this->inputs_button, TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_css_inputs_hover( $element ) );
        }

        $args = array(
            'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
            'type' => array(
                'value' => 'option',
            ),
            'inputs' => $this->inputs_button,
            'slug' => $this->slug_button,
            'common_translations' => $this->common_translations,
        );
        $this->integralwebsite_inputs_form_button = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->inputs_form( $args );

        /* ============== CART ================ */
        $this->slug_cart = TURITOP_BOOKING_SYSTEM_SERVICE_DATA;

        $this->inputs_cart = array(
          'integralwebsite_check_form' => array(
              'input_type' => 'hidden',
              'input_value' => 'cart',
          ),
          'cart_on_menu' => array(
              'input_type' => 'checkbox',
              'input_description' => _x( 'check this box to activate the cart on your menu', 'common translations', 'turitop-booking-system' ),
          ),
          'cart_menu_selected' => array(
              'input_type' => 'select',
              'attrs'       => 'multiple="multiple"',
              'input_class' => 'integralwebsite_input_select integralwebsite_input_select2 turitop_bs_carticoncolor_select',
              'options'   => $tbs_menus,
          ),
          'cart_menu_position' => array(
              'input_type' => 'radio',
              'default'     => 'last_menu_pos',
              'radios'   => array(
                  array(
                      'value' => 'first_menu_pos',
                  ),
                  array(
                      'value' => 'last_menu_pos',
                  ),
              ),
          ),
          'carticoncolor' => array(
              'input_type' => 'select',
              'default'     => 'white',
              'input_class' => 'integralwebsite_input_select integralwebsite_input_select2 turitop_bs_carticoncolor_select',
              'options'   => array(
                  array(
                      'value' => 'black',
                  ),
                  array(
                      'value' => 'white',
                  ),
              ),
          ),
          'cartbuttoncolor' => array(
              'input_type' => 'select',
              'default'     => 'green',
              'input_class' => 'integralwebsite_input_select integralwebsite_input_select2 turitop_bs_cartbuttoncolor_select',
              'options'   => array(
                  array(
                      'value' => 'green',
                  ),
                  array(
                      'value' => 'orange',
                  ),
                  array(
                      'value' => 'blue',
                  ),
                  array(
                      'value' => 'red',
                  ),
                  array(
                      'value' => 'yellow',
                  ),
                  array(
                      'value' => 'black',
                  ),
                  array(
                      'value' => 'white',
                  ),
              ),
          ),
          // CART CUSTOMIZATION
          'cart_custom_activate' => array(
              'input_type' => 'checkbox',
              'input_description' => _x( 'check this box to activate the cart customization options', 'cart customization', 'turitop-booking-system' ),
          ),
          'cart_checkbox_icon' => array(
              'input_type' => 'checkbox',
              'default' => 'yes',
              'input_description' => _x( 'check this box to display the icon on the cart', 'cart customization', 'turitop-booking-system' ),
          ),
          'cart_checkbox_text' => array(
              'input_type' => 'checkbox',
              'default' => 'yes',
              'input_description' => _x( 'check this box to display the below text on the cart', 'cart customization', 'turitop-booking-system' ),
          ),
          'cart_text' => array(
              'input_type' => 'text',
              'default'     => __( 'Cart', 'cart customization', 'turitop-booking-system' ),
          ),
          'cart_checkbox_counter' => array(
              'input_type' => 'checkbox',
              'default' => 'yes',
              'input_description' => _x( 'check this box to display the counter on the cart', 'cart customization', 'turitop-booking-system' ),
          ),
          'cart_background_color' => array(
              'input_class' => 'turitop_booking_system_color_picker',
              'input_type' => 'text_hex_color',
          ),
          'cart_border_color' => array(
              'input_class' => 'turitop_booking_system_color_picker',
              'input_type' => 'text_hex_color',
          ),
          'cart_font_color' => array(
              'input_class' => 'turitop_booking_system_color_picker',
              'input_type' => 'text_hex_color',
          ),
          'cart_radio_square' => array(
              'input_type' => 'radio',
              'default'     => 'radio',
              'radios'   => array(
                  array(
                      'value' => 'radio',
                  ),
                  array(
                      'value' => 'square',
                  ),
              ),
          ),
          'cart_min_height' => array(
              'input_type' => 'text',
          ),
          'cart_min_width' => array(
              'input_type' => 'text',
          ),

          // CART CUSTOMIZATION HOVER
          'cart_background_color_hover' => array(
              'input_class' => 'turitop_booking_system_color_picker',
              'input_type' => 'text_hex_color',
          ),
          'cart_border_color_hover' => array(
              'input_class' => 'turitop_booking_system_color_picker',
              'input_type' => 'text_hex_color',
          ),
          'cart_font_color_hover' => array(
              'input_class' => 'turitop_booking_system_color_picker',
              'input_type' => 'text_hex_color',
          ),
        );

        $this->cart_style_elements = TURITOP_BS()->get_cart_style_elements();

        foreach ( $this->cart_style_elements as $element ) {
          $this->inputs_cart = array_merge( $this->inputs_cart, TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_css_inputs( $element ) );
          if ( isset( $element[ 'hover' ] ) && $element[ 'hover' ] == 'yes' )
            $this->inputs_cart = array_merge( $this->inputs_cart, TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->generate_css_inputs_hover( $element ) );
        }

        $args = array(
            'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
            'type' => array(
                'value' => 'option',
            ),
            'inputs' => $this->inputs_cart,
            'slug' => $this->slug_cart,
            'common_translations' => $this->common_translations,
        );
        $this->integralwebsite_inputs_form_cart = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->inputs_form( $args );

        /* ============== SERVICE PAGE ================ */

        $this->slug_service_pages = TURITOP_BOOKING_SYSTEM_SERVICE_DATA;
        $this->inputs_cart = array(
          'integralwebsite_check_form' => array(
              'input_type' => 'hidden',
              'input_value' => 'service_pages',
          ),
          'layout' => array(
              'input_type'  => 'select',
              'default'     => 'image_left',
              'input_class' => 'integralwebsite_input_select integralwebsite_input_select2',
              'options'     => array(
                  array(
                      'value' => 'image_left',
                  ),
                  array(
                      'value' => 'image_rigth',
                  ),
                  array(
                      'value' => 'image_top_center',
                  ),
              ),
          ),
          'content_service' => array(
              'input_type'  => 'select',
              'default'     => 'whole_content',
              'input_class' => 'integralwebsite_input_select integralwebsite_input_select2',
              'options'     => array(
                  array(
                      'value' => 'whole_content',
                  ),
                  array(
                      'value' => 'summary_content',
                  ),
              ),
          ),
          'display_with' => array(
              'input_type' => 'radio',
              'default'     => 'box',
              'radios'   => array(
                  array(
                      'value' => 'box',
                  ),
                  array(
                      'value' => 'button',
                  ),
              ),
          ),
          'default_service_lang' => array(
              'input_type'  => 'select',
              'default'     => 'en',
              'input_class' => 'integralwebsite_input_select integralwebsite_input_select2',
              'options'     => $languages,
          ),

          // SERVICES SYNCHRONIZATION
          'synchronize' => array(
              'input_type' => 'submit',
              'input_value' => _x( 'synchronize', 'admin settings', 'turitop-booking-system' ),
              'input_class' => 'turitop_booking_system_synhronize_services',
          ),
        );

        $args = array(
            'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
            'type' => array(
                'value' => 'option',
            ),
            'inputs' => $this->inputs_cart,
            'slug' => $this->slug_service_pages,
            'common_translations' => $this->common_translations,
        );
        $this->integralwebsite_inputs_form_service_pages = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->inputs_form( $args );

        /* ============== SERVICE PAGE ================ */

        $this->slug_advance = TURITOP_BOOKING_SYSTEM_SERVICE_DATA;
        $this->inputs_advance = array(
          'integralwebsite_check_form' => array(
              'input_type' => 'hidden',
              'input_value' => 'advanced',
          ),
          'activate_VIP' => array(
              'input_type' => 'checkbox',
              'input_description' => _x( '<span>check this box to activate the VIP features. This option will allow you to synchronize your TuriTop services with your WordPress installation.<span/><span style="display: block; font-weight: bold; margin-top: 5px;">YOU MUST HAVE A TURITOP VIP ACCOUNT IN ORDER TO MAKE IT WORK</span>', 'common translations', 'turitop-booking-system' ),
          ),
          'advanced_activate' => array(
              'input_type' => 'checkbox',
              'input_description' => _x( 'check this box to activate the advanced options', 'common translations', 'turitop-booking-system' ),
          ),
          'advanced_aditional_css' => array(
              'input_type' => 'textarea',
              'input_class' => 'turitop_booking_system_additional_css',
          ),
        );

        $args = array(
            'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
            'type' => array(
                'value' => 'option',
            ),
            'inputs' => $this->inputs_advance,
            'slug' => $this->slug_advance,
            'common_translations' => $this->common_translations,
        );
        $this->integralwebsite_inputs_form_advance = TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->inputs_form( $args );

    }

        /**
         * errors call back
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function generate_css( $button_css_args, $data ) {

            $dynamic_css = '';
            foreach ( $button_css_args as $key => $value ) {
                if ( isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ){
                    if ( $value == 'border-radius: ' )
                        $dynamic_css .= $value . ( $data[ $key ] == 'radio' ? '5px' : '0' ) . ";" . PHP_EOL;
                    else
                        $dynamic_css .= $value . $data[ $key ] . ";" . PHP_EOL;
                }

            }

            return $dynamic_css;

        }

        /**
         * store data filtering
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.0
         * @access public
         * @param
         * @return void
         *
         */
        public function integralwebsite_wp_inputs_data_to_store_call_back( $data ) {

          if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'button' ){

            $tbs_data = TURITOP_BS()->get_tbs_data();

            $tbs_data[ 'button_text' ] = ( isset( $data[ 'button_text' ] ) ? $data[ 'button_text' ] : '' );
            $tbs_data[ 'buttoncolor' ] = ( isset( $data[ 'buttoncolor' ] ) ? $data[ 'buttoncolor' ] : '' );
            $tbs_data[ 'box_button_custom_activate' ] = ( isset( $data[ 'box_button_custom_activate' ] ) ? $data[ 'box_button_custom_activate' ] : '' );
            $tbs_data[ 'button_custom_class' ] = ( isset( $data[ 'button_custom_class' ] ) ? $data[ 'button_custom_class' ] : '' );
            $tbs_data[ 'button_image_id' ] = ( isset( $data[ 'button_image_id' ] ) ? $data[ 'button_image_id' ] : '' );
            $tbs_data[ 'button_image_url' ] = ( isset( $data[ 'button_image_url' ] ) ? $data[ 'button_image_url' ] : '' );
            $tbs_data[ 'button_image_activate' ] = ( isset( $data[ 'button_image_activate' ] ) ? $data[ 'button_image_activate' ] : '' );

            TURITOP_BS()->update_tbs_data( $tbs_data );

          }
          $tbs_data = TURITOP_BS()->get_tbs_data();

          if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'cart' ){

            $menu_ids = ( isset( $data[ 'cart_menu_selected' ] ) ? $data[ 'cart_menu_selected' ] : array() );
            $cart_menu_items = ( isset( $data[ 'cart_menu_items' ] ) ? $data[ 'cart_menu_items' ] : array() );
            $new_cart_menu_items = array();

            if ( isset( $data[ 'cart_on_menu' ] ) && $data[ 'cart_on_menu' ] == 'yes' ){

              if ( is_array( $menu_ids ) && ! empty( $menu_ids ) ){

                foreach ( $menu_ids as $key => $menu_id ) {

                  if ( isset( $cart_menu_items[ $menu_id ] ) ){
                    $new_cart_menu_items[ $menu_id ] = $cart_menu_items[ $menu_id ];
                  }
                  else{

                    $menu_item_id = wp_update_nav_menu_item( $menu_id, 0, array(
                        'menu-item-title' => 'TuriTop Cart',
                        'menu-item-url' => 'https://turitop.com/',
                        'menu-item-status' => 'publish',
                        'menu-item-type' => 'custom', // optional
                    ));

                    $new_cart_menu_items[ $menu_id ] = $menu_item_id;

                  }

                }

              }

            }

            foreach ( $cart_menu_items as $key => $menu_item_id ) {
              if ( ! isset( $new_cart_menu_items[ $key ] ) )
                wp_delete_post( $menu_item_id );
            }

            $data[ 'cart_menu_items' ] = $new_cart_menu_items;

          }

          if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'cart' ){

            $tbs_data[ 'cart_custom_activate' ] = ( isset( $data[ 'cart_custom_activate' ] ) ? $data[ 'cart_custom_activate' ] : '' );

            if ( isset( $data[ 'cart_custom_activate_' ] ) && $data[ 'cart_custom_activate_' ] == 'yes' ){

                // CUSTOM CART
                $dynamic_cart_css = '#turitop-booking-system-cart{ display: none; }';

                $dynamic_cart_css .= 'a.turitop_booking_system_wp_cart{' . PHP_EOL;

                $args = array(
                    'cart_background_color' => 'background: ',
                    'cart_border_color' => 'border: 1px solid ',
                    'cart_font_color' => 'color: ',
                    'cart_radio_square' => 'border-radius: ',
                    'cart_min_height' => 'min-height: ',
                    'cart_min_width' => 'min-width: ',
                );

                $dynamic_cart_css .= $this->generate_css( $args, $data );

                $dynamic_cart_css .= '}' . PHP_EOL;

                $dynamic_cart_css .= 'a.turitop_booking_system_wp_cart span.turitop_booking_system_cart_counter{' . PHP_EOL;

                $args = array(
                    'cart_font_color' => 'border: 1px solid '
                );

                $dynamic_cart_css .= $this->generate_css( $args, $data );

                $dynamic_cart_css .= '}' . PHP_EOL;

                // CUSTOM CART HOVER
                $dynamic_cart_css_hover = 'ul li a.turitop_booking_system_wp_cart:hover{' . PHP_EOL;

                $args = array(
                    'cart_background_color_hover' => 'background: ',
                    'cart_border_color_hover' => 'border: 1px solid ',
                    'cart_font_color_hover' => 'color: ',
                );

                $dynamic_cart_css_hover .= $this->generate_css( $args, $data );

                $dynamic_cart_css_hover .= '}' . PHP_EOL;

                $dynamic_cart_css_hover .= 'ul li a.turitop_booking_system_wp_cart:hover > span.turitop_booking_system_cart_counter{' . PHP_EOL;

                $args = array(
                    'cart_font_color_hover' => 'border: 1px solid '
                );

                $dynamic_cart_css_hover .= $this->generate_css( $args, $data );

                $dynamic_cart_css_hover .= '}' . PHP_EOL;

                $data[ 'dynamic_css' ] = $dynamic_cart_css . $dynamic_cart_css_hover;

            }
            else
            {

                $dynamic_cart_css = '#turitop-booking-system-cart{ display: block; }';

                $data[ 'dynamic_css' ] = $dynamic_cart_css;

            }

          }

          if ( isset( $data[ 'integralwebsite_check_form' ] ) && $data[ 'integralwebsite_check_form' ] == 'advanced' && isset( $data[ 'advanced_activate' ] ) && $data[ 'advanced_activate' ] == 'yes' ){

              $advanced_css = ( isset( $data[ 'advanced_aditional_css' ] ) ? $data[ 'advanced_aditional_css' ] : '' );

          }

          return $data;

        }

        /**
         * display settings
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

            <form action="" name="turitop_booking_system_settings_form" method="post">

              <h1 class="integralwebsite_main_title"><?php _ex( 'General settings', 'turitop settings', 'turitop-booking-system' ); ?></h1>

              <div class="integralwebsite_main_whole_wrap_block">

              <?php

          			$this->integralwebsite_inputs_form->create_nonce();

                    $args_to_display = array(
                      'integralwebsite_check_form',
                      'company',
                      'ga',
                      'embed',
                    );

                  $this->integralwebsite_inputs_form->display_inputs( $args_to_display );

          		?>

                <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save settings', 'turitop settings', 'turitop-booking-system' ); ?></button>

              </div>

            </form>

            <?php

        }

        /**
         * display button settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function display_button_settings() {

          ?>

          <form action="" name="turitop_booking_system_button_form" method="post">

            <h1 class="integralwebsite_main_title"><?php _ex( 'Button settings', 'turitop settings', 'turitop-booking-system' ); ?></h1>

            <div class="integralwebsite_main_whole_wrap_block">

                <?php

                    echo "<div style='position: relative;'>";

                        $this->integralwebsite_inputs_form_button->create_nonce();

                        $args_to_display = array(
                            'integralwebsite_check_form',
                            'button_text',
                            'buttoncolor',
                        );

                        $this->integralwebsite_inputs_form_button->display_inputs( $args_to_display );

                        ?>

                        <br>
                        <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save button settings', 'turitop settings', 'turitop-booking-system' ); ?></button>
                        <br><br>

                        <?php

                        echo "<div class='integralwebsite_main_sub_wrap'>";

                            echo "<h2>" . _x( 'Button customization', 'turitop settings', 'turitop-booking-system' ) . "</h2>";

                            $args_to_display = array(
                                'box_button_custom_activate',
                            );

                            $this->integralwebsite_inputs_form_button->display_inputs( $args_to_display );

                            echo "<div class='turitop_bs_admin_button_wrap'>";

                                $args_to_display = array(
                                    'button_custom_class',
                                );

                                $this->integralwebsite_inputs_form_button->display_inputs( $args_to_display );

                                $args_to_display = array(
                                  'button_image_activate',
                                );

                                $this->integralwebsite_inputs_form_button->display_inputs( $args_to_display );

                                ?>

                                <div class="integralwebsite_theme_choose_media_system" style="position: relative;">

                                  <?php

                                  $args_to_display = array(
                                    'button_image_id',
                                    'button_image_url',
                                    'button_image_button',
                                  );

                                  $this->integralwebsite_inputs_form_button->display_inputs( $args_to_display );

                                  $button_data = $this->integralwebsite_inputs_form_button->get_data();
                                  $image = ( isset( $button_data[ 'button_image_id' ] ) ?  $button_data[ 'button_image_id' ] : 0 );
                                  $src = wp_get_attachment_image_src( $image, 'thumbnail' );
                                  ?>

                                  <img src="<?php echo $src[ 0 ]; ?>" class="integralwebsite_theme_choose_img_media_url" />

                                  <div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_button_image_wrap'></div>

                                </div>

                                <?php

                                echo "<h2>" . _x( 'Style settings', 'settings', 'turitop-booking-system' ) . "</h2>";
                                echo "<hr />";

                                TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->display_css_inputs( $this->integralwebsite_inputs_form_button, $this->button_style_elements[ 'button_main' ] );

                                ?>
                                <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save button settings', 'turitop settings', 'turitop-booking-system' ); ?></button>
                                <?php

                                echo "<br><br>";
                                echo "<h2>" . _x( 'Hover settings', 'settings', 'turitop-booking-system' ) . "</h2>";
                                echo "<hr />";

                                TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->display_css_inputs_hover( $this->integralwebsite_inputs_form_button, $this->button_style_elements[ 'button_main' ] );

                                ?>
                                <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save button settings', 'turitop settings', 'turitop-booking-system' ); ?></button>
                                <?php

                                echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_button_wrap'></div>";

                            echo "</div>";

                        echo "</div>";

                    //echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_button_whole_wrap'></div>";

                    echo "</div>";

              ?>

            </div>

          </form>

          <?php

        }

        /**
         * display button settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function display_cart_settings() {

          ?>

          <form action="" name="turitop_booking_system_cart_form" method="post">

            <h1 class="integralwebsite_main_title"><?php _ex( 'Cart settings', 'turitop settings', 'turitop-booking-system' ); ?></h1>

            <div class="integralwebsite_main_whole_wrap_block">

                <?php

                $this->integralwebsite_inputs_form_cart->create_nonce();

                $args_to_display = array(
                    'integralwebsite_check_form',
                    'cart_on_menu',
                );

                $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                echo "<div style='position: relative'>";

                    $args_to_display = array(
                        'cart_menu_selected',
                        //'cart_menu_position',
                    );

                    $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                  $args_to_display = array(
                      'carticoncolor',
                      'cartbuttoncolor',
                  );

                  $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                  ?>

                  <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save cart settings', 'turitop settings', 'turitop-booking-system' ); ?></button>

                  <div class='integralwebsite_main_sub_wrap'>

                      <h2><?php _ex( 'Cart customization', 'turitop settings', 'turitop-booking-system' ) ?></h2>

                      <?php

                          $args_to_display = array(
                              'cart_custom_activate',
                          );

                          $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                          echo "<div class='turitop_bs_admin_custom_cart_wrap'>";

                              $args_to_display = array(
                                  'cart_checkbox_icon',
                                  'cart_checkbox_text',
                              );

                              $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                              echo "<div style='position: relative'>";

                                $args_to_display = array(
                                    'cart_text',
                                );

                                $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                                echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_cart_custom_text_wrap'></div>";

                              echo "</div>";

                              $args_to_display = array(
                                  'cart_checkbox_counter',
                              );

                              $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display );

                              echo "<h2>" . _x( 'Style settings', 'settings', 'turitop-booking-system' ) . "</h2>";
                              echo "<hr />";

                              TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->display_css_inputs( $this->integralwebsite_inputs_form_cart, $this->cart_style_elements[ 'cart_main' ] );

                              ?>
                              <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save cart settings', 'turitop settings', 'turitop-booking-system' ); ?></button>
                              <?php

                              echo "<br><br>";
                              echo "<h2>" . _x( 'Hover settings', 'settings', 'turitop-booking-system' ) . "</h2>";
                              echo "<hr />";

                              TURITOP_BS_INTEGRALWEBSITE_FUNCTIONS()->display_css_inputs_hover( $this->integralwebsite_inputs_form_cart, $this->cart_style_elements[ 'cart_main' ] );

                              /*
                              $classes = array(
                                  'integralwebsite_main_line_wrap_inline',
                                  'integralwebsite_input_wrap_block',
                              );

                              $args_to_display = array(
                                  'cart_background_color',
                                  'cart_border_color',
                                  'cart_font_color',
                              );

                              $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display, $classes );
                              echo "<div style='clear: both;'></div>";

                              $args_to_display = array(
                                  'cart_min_height',
                                  'cart_min_width',
                                  'cart_radio_square',
                              );

                              $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display, $classes );
                              echo "<div style='clear: both;'></div>";

                              $args_to_display = array(
                                  'cart_background_color_hover',
                                  'cart_border_color_hover',
                                  'cart_font_color_hover',
                              );

                              $this->integralwebsite_inputs_form_cart->display_inputs( $args_to_display, $classes );
                              echo "<div style='clear: both;'></div>";
                              */
                              echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_cart_custom_wrap'></div>";

                          echo "</div>";

                      ?>

                  </div>

                <?php

                  echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_cart_on_menu_wrap'></div>";

                echo "</div>";

                ?>

                <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save cart settings', 'turitop settings', 'turitop-booking-system' ); ?></button>

            </div>

          </form>

          <?php
        }

        /**
         * display service pages settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function display_service_pages_settings() {

          ?>

          <form action="" name="turitop_booking_system_service_pages_form" method="post">

            <h1 class="integralwebsite_main_title"><?php _ex( 'Service page settings', 'turitop settings', 'turitop-booking-system' ); ?></h1>

            <div class="integralwebsite_main_whole_wrap_block">

              <?php

              $this->integralwebsite_inputs_form_service_pages->create_nonce();

              $args_to_display = array(
                  'integralwebsite_check_form',
                  'layout',
                  'content_service',
                  'display_with',
                  'default_service_lang',
              );

              $this->integralwebsite_inputs_form_service_pages->display_inputs( $args_to_display );

              ?>

              <button class="turitop_booking_system_save_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save service page settings', 'turitop settings', 'turitop-booking-system' ); ?></button>

            </div>

          </form>

          <?php

        }

        /**
         * display advanced settings
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function display_advance_settings() {

          ?>

          <form action="" name="turitop_booking_system_advance_form" method="post">

            <h1 class="integralwebsite_main_title"><?php _ex( 'Advanced', 'turitop settings', 'turitop-booking-system' ); ?></h1>

            <div class="integralwebsite_main_whole_wrap_block">

                <input type="hidden" name="turitop_booking_system_settings_advanced_redirect" value="no">

                <?php
                    //if ( isset( $_POST[ 'turitop_booking_system_settings_advanced_redirect' ] ) && $_POST[ 'turitop_booking_system_settings_advanced_redirect' ] == 'yes' ){
                    if ( isset( $_POST[ 'turitop_booking_system_settings_advanced_redirect' ] ) ){
                      echo "<input type='hidden' name='turitop_booking_system_settings_advanced_redirect_url' value='" . menu_page_url( 'turitop_booking_system' ) . "'/>";
                    }
                    else {
                      echo "<input type='hidden' name='turitop_booking_system_settings_advanced_redirect_url' value='0'/>";
                    }

                    $this->integralwebsite_inputs_form_advance->create_nonce();

                    $args_to_display = array(
                        'integralwebsite_check_form',
                        'activate_VIP',
                        //'advanced_activate',
                    );

                    $this->integralwebsite_inputs_form_advance->display_inputs( $args_to_display );

                    /*echo "<div class='turitop_bs_admin_advanced_settings_wrap'>";

                        $args_to_display = array(
                            'advanced_aditional_css',
                        );

                        $this->integralwebsite_inputs_form->display_inputs( $args_to_display );

                        echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_advanced_wrap'></div>";

                    echo "</div>";*/

                ?>

                <button class="turitop_booking_system_save_VIP_settings integralwebsite_button_link" style="margin-left: 10px;"><?php _ex( 'Save advanced settings', 'turitop settings', 'turitop-booking-system' ); ?></button>

            </div>

          </form>

          <?php

        }

	}

}

$turitop_booking_system_settings = turitop_booking_system_settings::instance();
