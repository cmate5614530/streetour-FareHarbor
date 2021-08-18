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
 * @class      turitop_booking_system_service_cpt
 * @package    TuriTop
 * @since      Version 1.0.1
 * @author		 Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'turitop_booking_system_service_cpt' ) ) {
	/**
	 * Class turitop_booking_system_service_cpt
	 *
	 * @author Daniel Sánchez Sáez <dssaez@gmail.com>
	 */
	class turitop_booking_system_service_cpt {

		/**
		 * Main Instance
		 *
		 * @var turitop_booking_system_service_cpt
		 * @since  1.0.1
		 * @access protected
		 */
		protected static $_instance = null;

    /**
		 * simpledevel_inputs_form
		 *
		 * @var instance admin menu inputs
     * @since 1.0.0
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @access public
		 */
		public $simpledevel_inputs_form = null;

		/**
		 * Simpled CPT
		 *
		 * @var string
		 * @static
		 */
		public static $turitop_booking_system_service_cpt_id = TURITOP_BOOKING_SYSTEM_SERVICE_CPT;

		public static function get_instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		//setup the widget name, description, etc...
		public function __construct() {

		    $this->init();

        //Adding settings form meta boxes
        add_action( 'add_meta_boxes', array( $this, 'turitop_booking_system_service_add_meta_box_settings' ) );

        // Saving the data of each form
  			add_action( 'save_post', array( $this, 'turitop_booking_system_service_save_data' ) );

		}

        /**
         * init
         *
         * @return
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since 1.0.1
         */
        public function init() {

          // Initiation of the custom post type
		      add_action( 'init', array( $this, 'register_post_types' ) );

        }

    		/**
    		 * Register core post types.
    		 */
    		public function register_post_types() {

    			if ( post_type_exists( self::$turitop_booking_system_service_cpt_id ) ) {
    				return;
    			}

    			do_action( 'turitop_booking_system_service_register_post_type' );

    			/*  TPV MASTER CPT */

    			$labels = array(
    				'name'               => __( 'TuriTop service', 'turitop-booking-system' ),
    				'singular_name'      => __( 'TuriTop service', 'turitop-booking-system' ),
    				'edit'               => __( 'Edit', 'turitop-booking-system' ),
    				'edit_item'          => __( 'TuriTop service name', 'turitop-booking-system' ),
    				'view'               => __( 'View TuriTop service', 'turitop-booking-system' ),
    				'view_item'          => __( 'View TuriTop service', 'turitop-booking-system' ),
    				'search_items'       => __( 'Search TuriTop service', 'turitop-booking-system' ),
    				'not_found'          => __( 'No TuriTop service found', 'turitop-booking-system' ),
    				'not_found_in_trash' => __( 'No TuriTop service found in trash', 'turitop-booking-system' ),
    				'parent'             => __( 'Parent TuriTop service', 'turitop-booking-system' ),
    				'menu_name'          => _x( 'TuriTop services', 'Admin menu name', 'turitop-booking-system' ),
    				'all_items'          => __( 'All TuriTop services', 'turitop-booking-system' ),
    			);

    			$simpledevel_local_slave_args = array(
            'label'               => __( 'List of TuriTop services', 'turitop-booking-system' ),
    				'labels'              => $labels,
    				'description'         => __( 'TuriTop services.', 'turitop-booking-system' ),
    				//'public'              => true,
    				'show_ui'             => true,
    				'capability_type'     => 'post',
    				'map_meta_cap'        => true,
    				'publicly_queryable'  => false,
    				'exclude_from_search' => true,
    				'show_in_menu'        => false,
    				'hierarchical'        => false,
    				'show_in_nav_menus'   => false,
    				'rewrite'             => false,
    				'query_var'           => false,
    				'supports'            => array( 'title', 'Date' ),
    				'has_archive'         => false,
    				'menu_icon'           => 'dashicons-edit',
    			);

    			register_post_type( self::$turitop_booking_system_service_cpt_id, apply_filters( 'turitop_booking_system_service_register_post_type_filter', $simpledevel_local_slave_args ) );

    		}

        /**
         *
         * INIT
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @access public
         * @param
         * @return array
         *
         */
        public function simpledevel_wp_inputs_form_init() {

          $this->common_translations = TURITOP_BS()->common_translations;

          $this->slug = "turitop_booking_system_service";

          $pages = get_pages( array( 'post_status' => array( 'publish', 'draft', 'private' ) ) );

          $page_options = array(
            array(
              'text'  => __( 'Choose a page', 'turitop-booking-system' ),
              'value' => '0',
            ),
          );
          foreach ( $pages as $page ) {
            $page_options[] = array(
              'text'  => $page->post_title,
              'value' => $page->ID,
            );
          }
          $page_options[] = array(
            'text'  => TURITOP_BS()->common_translations[ 'custom_url' ],
            'value' => 'custom',
          );

          $this->inputs = array(
              // MAIN SETTINGS
              'page_id' => array(
                  'input_type' => 'select',
                  'default'     => '0',
                  'input_class' => 'simpled_input_select simpled_input_select2',
                  'options'     => $page_options,
              ),
              'url_parameters' => array(
                  'input_type' => 'text',
              ),
              'service_custom_url' => array(
                  'input_type' => 'text',
              ),
              'service_target_blank' => array(
                  'input_type' => 'checkbox',
                  'input_description' => _x( 'check this option to open a new tab when click on the service on the shop page', 'service cpt settings', 'turitop-booking-system' ),
              ),
          );

          $args = array(
              'vendor_url' => TURITOP_BOOKING_SYSTEM_VENDOR_URL,
              'type' => array(
                  'value' => 'post_meta',
                  'post_id' => 'post_meta',
              ),
              'inputs' => $this->inputs,
              'slug' => $this->slug,
              'common_translations' => $this->common_translations,
          );

          $this->simpledevel_inputs_form = TURITOP_BS_SIMPLED_FUNCTIONS()->inputs_form( apply_filters( 'turitop_booking_system_service_cpt_inputs_filter', $args ) );

        }

        /* ====================== SETTINGS ==================== */
    		public function turitop_booking_system_service_add_meta_box_settings() {

        		add_meta_box( 'turitop_booking_system_service_add_meta_box_settings_id', _x( 'Service settings', 'TuriTop Service cpt settings', 'turitop-booking-system' ), array(
        			$this,
        			'turitop_booking_system_service_add_meta_box_settings_callback'
        		), self::$turitop_booking_system_service_cpt_id, 'normal', 'high' );

    		}

    		public function turitop_booking_system_service_add_meta_box_settings_callback( $post ) {

          $this->simpledevel_wp_inputs_form_init();

          wp_nonce_field( 'turitop_booking_system_service_action', 'turitop_booking_system_service_field' );

          $this->simpledevel_inputs_form->retrieve_data( $post->ID );

          $this->simpledevel_inputs_form->load_inputs();

          $this->simpledevel_inputs_form->create_nonce();

          $data = $this->simpledevel_inputs_form->get_data();
          $lang = ( isset( $data[ 'langs' ][ 'en' ] ) ? $data[ 'langs' ][ 'en' ] : array_shift( $data[ 'langs' ] ) );

          echo '<h1 class="simpled_main_title">' . ( isset( $lang[ 'name' ] ) ? $lang[ 'name' ] : '' ) . '</h1>';

          $args_to_display = array(
            'page_id',
            'url_parameters',
          );

          $this->simpledevel_inputs_form->display_inputs( $args_to_display );

          echo "<div style='position: relative;'>";

              $args_to_display = array(
                  'service_custom_url',
              );

              $this->simpledevel_inputs_form->display_inputs( $args_to_display );

              echo "<div class='turitop_bs_blank_brightness turitop_bs_blank_brightness_service_custom_url_wrap'></div>";

          echo "</div>";

          $args_to_display = array(
            'service_target_blank',
          );

          $this->simpledevel_inputs_form->display_inputs( $args_to_display );

          do_action( 'turitop_booking_system_services_cpt_settings_displaying', $this->simpledevel_inputs_form );

        }

        /* ========================================================= */
        /* ====================== SAVING ====================== */
    		public function turitop_booking_system_service_save_data( $post_id ) {

          if ( ! isset( $_POST[ 'turitop_booking_system_service_field' ] ) ){
              return;
          }

          if ( ! wp_verify_nonce( $_POST[ 'turitop_booking_system_service_field' ], 'turitop_booking_system_service_action' ) ){
              return;
          }

          $this->simpledevel_wp_inputs_form_init();

          $this->simpledevel_inputs_form->check_form_submited( $post_id );

    		}

	}

}
