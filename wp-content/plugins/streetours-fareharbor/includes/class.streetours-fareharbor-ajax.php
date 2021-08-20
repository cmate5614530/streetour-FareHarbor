<?php
/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


if ( ! class_exists( 'streetours_fareharbor_ajax' ) ) {

    class streetours_fareharbor_ajax {


        protected static $_instance = null;

        public function __construct() {

            /* Display round trip services */
            add_action( 'wp_ajax_checkAbilityOfSelectedDate', array(
                $this,
                'checkAbilityOfSelectedDateAction'
            ) );

            /* Display round trip services */
            add_action( 'wp_ajax_nopriv_checkAbilityOfSelectedDate', array(
                $this,
                'checkAbilityOfSelectedDateAction'
            ) );


        }

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * display round trip services
         *
         * @since 1.0.3
         * @access public
         * @param
         * @return void
         *
         */
        public function checkAbilityOfSelectedDateAction() {

            wp_verify_nonce( 'streetours_fareharbor_nonce', 'security' );

            $date = $_REQUEST['date'];

//            $to_options = '';
//            ob_start();
//            echo "<option value='0'>" . TURITOP_BS()->common_translations[ 'choose_to' ] . "</option>";
//            foreach ( TURITOP_BS()->get_round_trip_booking_data() as $trip ){
//
//                if ( $trip[ 'from' ] == $from )
//                    echo "<option value='" . $trip[ 'to' ] . "'>" . $trip[ 'to' ] . "</option>";
//
//            }
//            $to_options = ob_get_clean();

//            require_once("../../../../wp-load.php");
//            include("../generator/httpful.phar");

            $api_app = get_option('streetours-fareharbor-app-key') ?? "646cb627-293d-41dc-9f69-3d5cb25ec2fc";
            $api_user = get_option('streetours-fareharbor-user-key') ?? "c7ebe150-95e2-4dba-bb0b-64c3022ec99b";
            $company = get_option('streetours_fareharbor_company') ?? 'bodyglove';

            $root_url = 'https://demo.fareharbor.com/api/external/v1';
            $company = 'bodyglove';
            $item = 183;
            $availabilities_url = "$root_url/companies/$company/items/$item/minimal/availabilities/date/$date/";
            //https://demo.fareharbor.com/api/external/v1/companies/bodyglove/items/183/minimal/availabilities/date/2021-12-01
//            $response = \Httpful\Request::get($availabilities_url)
//                ->addHeader("X-FareHarbor-API-App", $api_app)
//                ->addHeader("X-FareHarbor-API-User", $api_user)
//                ->send();

            $response_encoded = wp_remote_get($availabilities_url, array(
                'headers' => array(
                    'X-FareHarbor-API-App' => $api_app,
                    'X-FareHarbor-API-User'=> $api_user
                ),
            ));

            $res = json_decode($response_encoded['body']);
            $availabilities = $res->availabilities;
            wp_send_json_success( array(
                'response'       => $availabilities,
                'type'=>'success'
            ) );

        }



    }
}
