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
 * @since      Version 1.0.1
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
		 * simpledevel_inputs_form
		 *
		 * @var instance admin menu inputs
     * @since 1.0.0
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @access public
		 */
		public $simpledevel_inputs_form = null;

    /**
     * service_cpt_table
     *
     * @var class
     * @since 1.0.1
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @access public
     */
     public $service_cpt_table = null;

    /**
		 * Main Instance
		 *
		 * @var turitop_booking_system_settings
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

          $this->simpledevel_inputs_form->init();

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

        include TURITOP_BOOKING_SYSTEM_PATH . 'includes/cpt/service/class.turitop-booking-system-service-cpt-table.php';
        $this->service_cpt_table = new turitop_booking_system_service_cpt_table ();

        $this->common_translations = TURITOP_BS()->common_translations;

        $this->tbs_data = TURITOP_BS()->get_tbs_data();

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

        $this->inputs = array(
          'services_syncrhronize_langs' => array(
              'input_type'  => 'select',
              //'default'     => 'en',
              'attrs'       => 'multiple="multiple"',
              'input_class' => 'simpled_input_select simpled_input_select2',
              'options'     => $languages,
          ),
          'create_service_page_activate' => array(
              'input_type' => 'checkbox',
              'input_description' => _x( 'check this box to crete a page for each service', 'common translations', 'turitop-booking-system' ),
          ),
          'syncrhronize_button' => array(
              'input_type'  => 'submit',
              'input_value' => 'Synchronize',
              'input_class' => 'turitop_booking_system_synhronize_services',
          ),
          /*array(
              'input_type' => 'submit',
              'input_name' => 'simpled_wc_master_settings_name_disconnect_button',
              'input_value' => _x( 'Disconnect', 'local slave settings', 'simpledevel-pos-master-slave-for-woocommerce' ),
              'input_class' => 'simpled_button_link_disconnect',
              'input_dashicons' => 'dashicons-no-alt',
          ),*/
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
        $this->simpledevel_inputs_form = TURITOP_BS_SIMPLED_FUNCTIONS()->inputs_form( $args );

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

            <h1 class="simpled_main_title"><?php _ex( 'Services Synchronization', 'turitop settings', 'turitop-booking-system' ); ?></h1>

            <div id="turitop_booking_system_services_sycnchronization" class="simpled_main_whole_wrap_block">

              <?php  if ( $this->tbs_data[ 'credentials' ] != 'ok' ) : ?>

                <p style="display: flex; column-gap: 5px;">
                  <span class="dashicons dashicons-warning"></span><?php _ex( 'In order to synchronize the services you have to fill the fields "Turitop Company ID" and "TuriTop Secret key" located on the "Connection" tab.', 'turitop settings', 'turitop-booking-system' ) ?>
                </p>

              <?php

              endif;

              $args_to_display = array(
                'services_syncrhronize_langs',
                'create_service_page_activate',
                'syncrhronize_button',
              );

              $this->simpledevel_inputs_form->display_inputs( $args_to_display );

              if ( isset( $_REQUEST[ 'tbs_num' ] ) ) :

                ?>

                  <h2><span class="dashicons dashicons-yes"></span><?php echo $_REQUEST[ 'tbs_num' ] . " " . _x( 'services has been syncrhonized', 'turitop settings', 'turitop-booking-system' ) ?></h2>



                <?php

              endif;

              $admin_url = admin_url( 'post-new.php' );
              $params    = array(
              	'post_type' => TURITOP_BOOKING_SYSTEM_SERVICE_CPT
              );
              $add_new_url = esc_url( add_query_arg( $params, $admin_url ) );

              ?>

              <div class="wrap">

              	<div id="poststuff">
              		<div id="post-body" class="metabox-holder">
              			<div id="post-body-content">
              				<div class="meta-box-sortables ui-sortable">
              					<form method="post">
              						<input type="hidden" name="page" value="<?php echo TURITOP_BOOKING_SYSTEM_SERVICE_CPT; ?>" />
              					</form>
              					<form method="post">
              						<?php
              						$this->service_cpt_table->views();
              						$this->service_cpt_table->prepare_items();
              						$this->service_cpt_table->display(); ?>
              					</form>
              				</div>
              			</div>
              		</div>
              		<br class="clear">
              	</div>
              </div>

            </div>

            <?php

        }

	}

}

$turitop_booking_system_settings = turitop_booking_system_settings::instance();
