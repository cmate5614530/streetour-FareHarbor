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

        protected $api_app;
        protected $api_user;
        protected $root_url;
        protected $company;
        protected $item;

        public function __construct() {

            $this->api_app = get_option('streetours-fareharbor-app-key') ?? "646cb627-293d-41dc-9f69-3d5cb25ec2fc";
            $this->api_user = get_option('streetours-fareharbor-user-key') ?? "c7ebe150-95e2-4dba-bb0b-64c3022ec99b";
            $this->company = get_option('streetours_fareharbor_company') ?? 'bodyglove';
            $this->root_url = 'https://demo.fareharbor.com/api/external/v1';
            $this->company = 'bodyglove';
            $this->item = 183;


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
            //clicked Next Button during booking
            add_action( 'wp_ajax_clickedNextButton', array(
                $this,
                'clickedNextButton'
            ) );
            add_action( 'wp_ajax_nopriv_clickedNextButton', array(
                $this,
                'clickedNextButton'
            ) );

            //check ability and book
            add_action('wp_ajax_checkAbilityAndBook', array(
                $this,
                'checkAbilityAndBook'
            ));
            add_action('wp_ajax_nopriv_checkAbilityAndBook', array(
                $this,
                'checkAbilityAndBook'
            ));

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


            $availabilities_url = "$this->root_url/companies/$this->company/items/$this->item/minimal/availabilities/date/$date/";
            //https://demo.fareharbor.com/api/external/v1/companies/bodyglove/items/183/minimal/availabilities/date/2021-12-01
//            $response = \Httpful\Request::get($availabilities_url)
//                ->addHeader("X-FareHarbor-API-App", $api_app)
//                ->addHeader("X-FareHarbor-API-User", $api_user)
//                ->send();

            $response_encoded = wp_remote_get($availabilities_url, array(
                'headers' => array(
                    'X-FareHarbor-API-App' => $this->api_app,
                    'X-FareHarbor-API-User'=> $this->api_user
                ),
            ));
            $res = json_decode($response_encoded['body']);
            $availabilities = $res->availabilities;
            wp_send_json_success( array(
                'response'       => $availabilities,
                'type'=>'success'
            ) );

        }

        public function clickedNextButton(){
            wp_verify_nonce( 'streetours_fareharbor_nonce', 'security' );



        }

        public function checkAbilityAndBook(){
            wp_verify_nonce( 'streetours_fareharbor_nonce', 'security' );

            //check ability from FareHarbor

            $name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $phone = $_REQUEST['phone_dummy'];


//            $response_encoded = wp_remote_get('https://demo.fareharbor.com/api/external/v1/companies/bodyglove/availabilities/25635933/bookings/validate', array(
//                'headers' => array(
//                    'X-FareHarbor-API-App' => $this->api_app,
//                    'X-FareHarbor-API-User'=> $this->api_user
//                ),
//            ));


            $p = 1;
                //{
            //  "voucher_number": "VN-123456",
            //  "contact": {
            //    "name": "John Doe",
            //    "phone": "123-456-7890",
            //    "email": "example@example.com"
            //  },
            //  "customers": [
            //    {
            //      "customer_type_rate": 63165761
            //    },
            //    {
            //      "customer_type_rate": 63165761
            //    },
            //    {
            //      "customer_type_rate": 63165761
            //    }
            //  ],
            //  "note": "Optional booking note"
            //}
              $data = [
                  'voucher_number'=>'',
                  'contact'=>[
                      'name'=>$name,
                      'phone'=>$phone,
                      'email'=>$email,
                  ],
                  'customers'=>[
                      ['customer_type_rate'=>63165485],
                      ['customer_type_rate'=>63165486],
                      ['customer_type_rate'=>63165487],
                  ],
                  'note'=>'PPPPPPPP',
              ];
//            $data = new stdClass();
//            $data->voucher_number = NULL;
//            $data->contact = new stdClass();
//            $data->contact->name = $name;
//            $data->contact->email = $email;
//            $data->contact->phone = $phone;
//            $data->customers = array();
//
//            //array_push($data->customers, $name);
//            $data->note = NULL;

            $response_encoded = wp_remote_post('https://demo.fareharbor.com/api/external/v1/companies/bodyglove/availabilities/25635841/bookings/validate/', array(
                'method'      => 'POST',
                'headers' => array(
                    'X-FareHarbor-API-App' => $this->api_app,
                    'X-FareHarbor-API-User'=> $this->api_user
                ),
                'body'=>json_encode($data)
            ));

            $response_encoded1 = wp_remote_post('https://demo.fareharbor.com/api/external/v1/companies/bodyglove/availabilities/25635841/bookings/', array(
                'method'      => 'POST',
                'headers' => array(
                    'X-FareHarbor-API-App' => $this->api_app,
                    'X-FareHarbor-API-User'=> $this->api_user
                ),
                'body'=>json_encode($data)
            ));
            $p = 1;
            $res = json_decode($response_encoded1['body']);
            wp_send_json_success(array(
                'response' => 'ddddd',
                'type'=>'success'
            ));
        }


    }
}
