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
 * @class      turitop_booking_system_instructions
 * @package    turitop
 * @since      Version 1.0.0
 * @author	   Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'turitop_booking_system_instructions' ) ) {
	/**
	 * Class turitop_booking_system_instructions
	 *
	 * @author Daniel Sánchez Sáez <dssaez@gmail.com>
	 */
	class turitop_booking_system_instructions {

		/**
		 * Main Instance
		 *
		 * @var turitop_booking_system_instructions
		 * @since  1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @access protected
		 */
		protected static $_instance = null;

        /**
		 * Main Instance
		 *
		 * @var turitop_booking_system_instructions
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

            wp_register_style( 'integralwebsite_admin_functions_css', apply_filters( 'integralwebsite_admin_functions_css_filter', TURITOP_BOOKING_SYSTEM_VENDOR_URL . '/integralwebsite/css/integralwebsite-admin-functions.css' ), array(), TURITOP_BOOKING_SYSTEM_VERSION );
            wp_enqueue_style( 'integralwebsite_admin_functions_css' );

            $this->display_instructions();

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
        public function display_instructions() {

            ?>

            <h1 class="integralwebsite_main_title"><?php _ex( 'TuriTop Booking System Instructions', 'turitop instructions', 'turitop-booking-system' ); ?></h1>

            <div class="integralwebsite_main_whole_wrap_block">

              <h2><?php _ex( 'Installation', 'turitop instructions', 'turitop-booking-system' ); ?></h2>

              <ul>
                  <li><?php _ex( 'Install the TuriTop Booking plugin in your WordPress admin by going to "Plugins / Add New" and searching for TuriTop, (or) If doing a manual install, download the plugin and unzip into your /wp-content/plugins/ directory.', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                  <li><?php _ex( 'Activate the the plugin through the "Plugins" menu in WordPress.', 'turitop instructions', 'turitop-booking-system' ); ?></li>
              </ul>

              <h2><?php _ex( 'Configuration', 'turitop instructions', 'turitop-booking-system' ); ?></h2>

              <ul>
                  <li><?php _ex( 'Create your', 'turitop instructions', 'turitop-booking-system' ); ?> <a target="_blank" href="https://www.turitop.com/en/register-free"><?php _ex( 'TuriTop account.', 'turitop instructions', 'turitop-booking-system' ); ?></a></li>
                  <li><?php _ex( 'Configure your account and services on TuriTop. If you need any help, please do not hesitate to request', 'turitop instructions', 'turitop-booking-system' ); ?> <a target="_blank" href="https://www.turitop.com/en/get-a-demo"><?php _ex( 'request a live demo', 'turitop instructions', 'turitop-booking-system' ); ?></a> <?php _ex( 'and we will show you how TuriTop works.', 'turitop instructions', 'turitop-booking-system' ); ?> </li>
                  <li><?php _ex( 'Create a Wordpress Post and embed the TuriTop Booking System by using:', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                  <li style="list-style: none;">

                    <ul>
                        <li><?php _ex( 'Use the gutenberg block "Turitop Booking System".', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                        <li><?php _ex( 'Use the shortcode "turitop_booking_system" with the following attributes:', 'turitop instructions', 'turitop-booking-system' ); ?>
                            <ul>
                                <li><?php _ex( 'company ( Turitop company ID ).', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                                <li><?php _ex( 'product_id ( Turitop product ID ).', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                                <li><?php _ex( 'embed ( button, box ).', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                                <li><?php _ex( 'buttoncolor ( green, orange, blue, red, yellow, black, white).', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                                <li><?php _ex( 'button_text ( text of the button ).', 'turitop instructions', 'turitop-booking-system' ); ?></li>
                                <?php
                                if ( function_exists( 'WC' ) )
                                    echo "<li>" . _x( 'wc_product_id ( WooCommerce product id )', 'turitop instructions', 'turitop-booking-system' ) . "</li>"
                                ?>
                            </ul>
                        </li>
                        <?php
                        if ( function_exists( 'WC' ) )
                            echo "<li>" . _x( 'Configure a WooCoomerce simple prodruct.', 'turitop instructions', 'turitop-booking-system' ) . "</li>"
                        ?>
                    </ul>

                  </li>
              </ul>

              <h2><?php _ex( 'Shortcode examples:', 'turitop instructions', 'turitop-booking-system' ); ?></h2>
              <ul>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Widget box for service "P1":', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="box"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Red Button with text "Click here to book" for service "P1":', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="button" buttoncolor="red" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Default Button color with text "Click here to book" for service "P1":', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="button" buttoncolor="default" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Custom class "my_custom_class" Button with text "Click here to book" for service "P1":', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="button" buttoncolor="none" box_button_custom_activate="yes" button_custom_class="my_custom_class" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . 'Image "http://my-site/image.png" to use as a Button for service "P1":' . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="button" buttoncolor="none" box_button_custom_activate="yes" button_image_activate="yes" button_image_url="http://my-site/image.png" button_text=""]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . 'Defatult image as a Button for service "P1":' . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="button" buttoncolor="none" box_button_custom_activate="yes" button_image_activate="yes" button_image_default="default" button_text=""]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Daily Widget:', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="all" embed="box" buttoncolor="red" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'All services buttons:', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="C0" embed="box" buttoncolor="red" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'All categories buttons:', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="C" embed="box" buttoncolor="red" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Specific Category:', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="C1" embed="box" buttoncolor="red" button_text="Click here to book"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Gift Voucher:', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system product_id="P1" embed="gift" buttoncolor="red" button_text="Gift this product"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <?php
                    echo "<span class='turitop_booking_system_instructions_shortcode_example_title'>" . _x( 'Redeem Voucher:', 'turitop instructions', 'turitop-booking-system' ) . "</span>";
                    ?>
                    <ul>
                      <li>
                        <?php
                        echo "<span class='turitop_booking_system_instructions_shortcode_example'>" . '[turitop_booking_system embed="redeemgv" buttoncolor="red" button_text="Redeem gift voucher"]' . "</span>";
                        ?>
                        <br>
                      </li>
                    </ul>
                  </li>
              </ul>

            </div>

            <?php

        }

	}

}

$turitop_booking_system_instructions = turitop_booking_system_instructions::instance();
