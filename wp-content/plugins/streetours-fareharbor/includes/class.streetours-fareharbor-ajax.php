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
        protected $short_id;
        protected $secret_key;

        public function __construct() {

            $this->api_app = get_option('streetours-fareharbor-app-key') ?? "646cb627-293d-41dc-9f69-3d5cb25ec2fc";
            $this->api_user = get_option('streetours-fareharbor-user-key') ?? "c7ebe150-95e2-4dba-bb0b-64c3022ec99b";
            $this->company = get_option('streetours_fareharbor_company') ?? 'bodyglove';
            $this->root_url = 'https://demo.fareharbor.com/api/external/v1';
            $this->company = 'bodyglove';
            $this->item = 183;

            $this->short_id = get_option('turitop_short_id') ? get_option('turitop_short_id') : 'S1114';
            $this->secret_key = get_option('turitop_secret_key') ? get_option('turitop_secret_key') : '6lM9v5HLZieciwuEbDx4kuYXuWBBIFNJ';

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

            $customers = array();
            $name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $phone = $_REQUEST['phone_dummy'];
            $pk = (int)$_REQUEST['pk'];
            $turitop_product_short_id = $_REQUEST['turitop_product_short_id'];
            foreach ($_REQUEST['customers'] as $customer){
                for ($i = 0; $i< $customer['count']; $i++){
                    $it = ['customer_type_rate'=>(int)$customer['customer_type_rate']['pk']];
                    array_push($customers, $it);
                }
            }

            $data = [
              'voucher_number'=>'',
              'contact'=>[
                  'name'=>$name,
                  'phone'=>$phone,
                  'email'=>$email,
              ],
              'customers'=>$customers,
              'note'=>'',
            ];


            $ability = wp_remote_post($this->root_url.'/companies/'.$this->company.'/availabilities/'.$pk.'/bookings/validate/', array(
                'method'      => 'POST',
                'headers' => array(
                    'X-FareHarbor-API-App' => $this->api_app,
                    'X-FareHarbor-API-User'=> $this->api_user
                ),
                'body'=>json_encode($data)
            ));

            if($ability){
                //make booking on FareHarbor
                $response_encoded1 = wp_remote_post($this->root_url.'/companies/'.$this->company.'/availabilities/'.$pk.'/bookings/', array(
                    'method'      => 'POST',
                    'headers' => array(
                        'X-FareHarbor-API-App' => $this->api_app,
                        'X-FareHarbor-API-User'=> $this->api_user
                    ),
                    'body'=>json_encode($data)
                ));
                $res = json_decode($response_encoded1['body']);

                //make booking on Turitop
                //get access_token
                $grant_encoded = wp_remote_post('https://app.turitop.com/v1/authorization/grant', array(
                    'method'=>'POST',
                    'body'=> array(
                        'short_id'=> $this->short_id,
                        'secret_key'=> $this->secret_key
                    )
                ));
                $grant = json_decode($grant_encoded['body']);
                $access_token = $grant->code == '200' ? $grant->data->access_token : '';

                //get ticket
                $tickets_encoded = wp_remote_post('https://app.turitop.com/v1/tickets/get', array(
                   'method'=>'POST',
                   'body'=>array(
                       'access_token'=>$access_token,
                       'data'=>array(
                           'product_short_id'=>$turitop_product_short_id,
                           'language'=>'en'
                       ),

                   )
                ));
                $tickets = json_decode($tickets_encoded['body'])->data->tickets;

                $ticket_type_count = array();
                foreach ($_REQUEST['customers'] as $customer){

                    foreach ($tickets as $key=>$ticket){
                        if($ticket->name == $customer['customer_prototype']['display_name'])
                            array_push($ticket_type_count, [$key=>$customer['count']]);
                    }
                }

                $start_at = $_REQUEST['start_at'];
//                $$end_at = $_REQUEST['end_at'];
                $start_at1 = strtotime($start_at);
                $end_at_2 = $_REQUEST['start_at'];
                $turitop_booking_data = array(
                  'access_token'=>$access_token,
                  'data'=>array(
                      'product_short_id'=>$turitop_product_short_id,
                      'override_client_data'=> true,

                      'booking'=>array(
                          'event_start'=>$start_at1,
//                          'ticket_type_count'=>array(
//                              '137601'=>1
//                          ),
                        'ticket_type_count'=>$ticket_type_count,
                          'client_data'=>array(
                              'name'=>$name,
                              'email'=>$email,
                              'phone'=>$phone,

                          ),
                          'short_id_seat'=>'',
                          'status'=>'not paid',
                          'payment_gateway'=>'stripe'
                      )
                  )
                );

                $booking_encoded_turitop = wp_remote_post('https://app.turitop.com/v1/booking/tour/insert', array(
                    'method'=>'POST',
                    'body'=> $turitop_booking_data
                ));
                $booking_turitop = json_decode($booking_encoded_turitop['body']);

                wp_send_json_success(array(
                    'response' => $res,
                    'type'=>'success'
                ));
            }else{
                wp_send_json_success(array(
                    'response' => 'Booking to this day is not allowed now.',
                    'type'=>'fail'
                ));
            }


        }


    }
}
