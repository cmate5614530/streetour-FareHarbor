<?php

require 'fh-header.php';
//require_once '../includes/class.streetours-fareharbor-ajax.php';


$type = $_REQUEST['type'] ?? 'list';
$book_item = $_REQUEST['fareharbor_item'] ?? '183';
$date = $_REQUEST['date'] ?? '';
$turitop_product_short_id = $_REQUEST['turitop_product_short_id'] ?? '';
//$ajax_nonce = wp_create_nonce('streetours_fareharbor_nonce');
if (!empty($book_item) && !empty($turitop_product_short_id)) {

    require_once("../../../../wp-load.php");
    include("./httpful.phar");

    $api_app = get_option('streetours-fareharbor-app-key') ?? "646cb627-293d-41dc-9f69-3d5cb25ec2fc";
    $api_user = get_option('streetours-fareharbor-user-key') ?? "c7ebe150-95e2-4dba-bb0b-64c3022ec99b";
    $company = get_option('streetours_fareharbor_company') ?? 'bodyglove';

    $root_url = 'https://demo.fareharbor.com/api/external/v1';
    $company = 'bodyglove';
    $availabilities_url = "$root_url/companies/$company/items/";

    $response = \Httpful\Request::get($availabilities_url)
        ->addHeader("X-FareHarbor-API-App", $api_app)
        ->addHeader("X-FareHarbor-API-User", $api_user)
        ->send();

    $fhItems = $response->body->items;

    foreach ($fhItems as $fhItem):
       if ($fhItem->pk == $book_item):
        ?>
           <link rel="stylesheet" href="../js/fullcalendar/fullcalendar.min.css">
           <!--    <script src="../js/jquery.min.js"></script>-->
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
           <script src="../js/popper.min.js"></script>
           <!--    <script src="../js/bootstrap.min.js"></script>-->
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
           <script src="../js/swiper/js/swiper.min.js"></script>
           <script src="../js/moment.min.js"></script>
           <script src="../js/bootstrap-datetimepicker.min.js"></script>
           <script src="../js/daterangepicker/daterangepicker.js"></script>

           <script src="../js/jquery-ui/jquery-ui.min.js"></script>
           <script src="../js/fullcalendar/fullcalendar.min.js"></script>
           <script type="text/javascript">


               $(document).ready(function(){
                   var calendar = $('#calendar').fullCalendar({
                       unselectAuto:false,
                       allDay:true,
                       defaultView: 'month',
                       handleWindowResize: true,
                       header: {
                           left: 'prev',
                           center: 'title',
                           right: 'next'
                       },
                       editable: false,
                       droppable: false, // this allows things to be dropped onto the calendar !!!
                       selectable: true,
                       dayClick: function (date, jsEvent, view) {
                            console.log('---clicked---',  date.format());
                           checkAbilityOfSelectedDate(date.format());
                       }

                   });
                   $('#events').html('<h4 style="color: green;">Click a date to browse availability</h4>');
               });

               var i = 0;
               var res_global = [];
               var event_start_time;
               var event_start_at;
               function checkAbilityOfSelectedDate(date) {
                   var data = {
                       'date': date,
                       'action':'checkAbilityOfSelectedDate',
                       'security':'streetours_fareharbor_nonce'
                   };
                   $.post('<?php echo admin_url('admin-ajax.php');?>', data, function (response) {
                        console.log('----response---', response);
                        if(response.success === true){
                            let res = response.data.response;
                            if(res.length>0){
                                res_global = res;
                                let a= new Date(res[0].end_at);
                                console.log(a, a.getHours(), a.toDateString(), a.toLocaleDateString());
                                let event_time_html = '';
                                // for(let i=0; i<res.length; i++){
                                i = 0;
                                event_start_at = res[i].start_at;
                                event_start_time = new Date(res[i].start_at);
                                let event_end_time = new Date(res[i].end_at);
                                $('#date').html('<h4>'+event_start_time.toDateString()+'</h4>');
                                event_time_html += '<h4>'+event_start_time.toDateString()+'</h4>';
                                event_time_html += '<h5>'+('0'+event_start_time.getHours()).slice(-2)+':'+('0'+event_start_time.getMinutes()).slice(-2);
                                event_time_html += ' - ' +('0'+event_end_time.getHours()).slice(-2)+':'+('0'+event_end_time.getMinutes()).slice(-2)+'</h5>';

                                event_time_html += '<table style="font-size: small;"><tbody>';
                                let customer_type_rates = res[i].customer_type_rates;
                                for(let k=0; k<customer_type_rates.length; k++){
                                    event_time_html += '<tr>';
                                    event_time_html += '<td class="col1" style="padding: .5em 0;width: 60%; font-weight: 700;">'+
                                        '<div class="ticket-name">'+
                                        customer_type_rates[k].customer_prototype.display_name +
                                        '</div>'+
                                        '</td>' +
                                        '<td class="col2" style="text-align: right;">' +
                                        '<span> $'+Math.ceil(customer_type_rates[k].customer_prototype.total_including_tax/100)+'</span>'+
                                        '</td>' +
                                        '<td class="col3" style="text-align: right; width: 4.3em;">' ;

                                    // if(k == 0){
                                    event_time_html += '<select name="ticket_type_count" id="ticket_type_count_'+i+'_'+k+'" onchange="validate_and_calculate()">';
                                    for(let m=0; m<=customer_type_rates[k].capacity; m++){
                                        event_time_html += '<option value="'+m+'">'+m+'</option>';
                                    }
                                    event_time_html +=  '</select>';
                                    // }


                                    event_time_html +=  '</td>';
                                    event_time_html += '</tr>';
                                }
                                event_time_html += '</tbody></table>';
                                $('#announcement').html('<h5 style="color:green;">Max capacity(excluding infants): '+ res[i].capacity+'</h5>');

                                // }
                                $('#events').html(event_time_html);
                                $('#next_btn').html('<button onclick="next_step()" style="background: #00b22d;color: white; border: none; padding: .5em 1em;border-radius: 3px; margin-top: 20px;">NEXT'+'</button>')

                            }else{
                                $('#announcement').html('<h5 style="color: red;">Not available date!</h5>');
                            }
                        }
                   })
               }
               var customers = [];
               var pk = '';
               function next_step() {
                   console.log('====', res_global, res_global[0].capacity);
                    if(!!res_global){
                        let count1 = parseFloat($('#ticket_type_count_0_0').val());
                        let count2 = parseFloat($('#ticket_type_count_0_1').val());
                        let count3 = parseFloat($('#ticket_type_count_0_2').val());
                        console.log(count1, count2, count3, count3+count2+count1);
                        if(count1 == 0 && (count2 > 0 || count3 > 0)){
                            $('#error').html('<h5 style="color:red;">Error: Children and Infants must be accompanied by 18+ adult!</h5>')
                        }else if(count1 + count2 + count3 > res_global[0].capacity){
                            $('#error').html('<h5 style="color:red;">Error: Capacity exceeded!</h5>')
                        }else if(count1 + count2 + count3 == 0){
                            $('#error').html('<h5 style="color: red;">Error: Please input participants</h5>');
                        }else{
                            //var data = {
                            //    'date': date,
                            //    'action':'clickedNextButton',
                            //    'security':'streetours_fareharbor_nonce'
                            //};
                            //$.post('<?php //echo admin_url('admin-ajax.php');?>//', data, function (response){
                            //
                            //}
                            $('#step1').attr('style', 'display:none;');
                            $('#step2').attr('style', 'display:block;');

                            let customer1 = {
                                'customer_type_rate':res_global[0].customer_type_rates[0],
                                'count': count1
                            };
                            let customer2 = {
                                'customer_type_rate':res_global[0].customer_type_rates[1],
                                'count': count2
                            };
                            let customer3 = {
                                'customer_type_rate':res_global[0].customer_type_rates[2],
                                'count': count3
                            };
                            customers.push(customer1);
                            customers.push(customer2);
                            customers.push(customer3);
                            pk = res_global[0].pk;
                        }
                    }
               }

               function validate_and_calculate() {
                   $('#error').html('');
                    let count1 = $('#ticket_type_count_0_0').val();
                    let count2 = $('#ticket_type_count_0_1').val();
                    let count3 = $('#ticket_type_count_0_2').val();

                    if(parseFloat(count1) + parseFloat(count2) + parseFloat(count3) > 0 && parseFloat(count1) + parseFloat(count2) + parseFloat(count3) < res_global[0].capacity){

                    }
                    let total_price = Math.ceil(res_global[0].customer_type_rates[0].customer_prototype.total_including_tax/100) * count1
                        + Math.ceil(res_global[0].customer_type_rates[1].customer_prototype.total_including_tax/100) * count2;
                    $('#total_price').html('<h3>Total:  $' + total_price + '</h3>');
                    $('#price').html('<h4>Total:  $' + total_price + '</h4>');
               }
               
               function checkAbilityAndBook() {
                   var data = {
                       'name': $('#name').val(),
                       'email':$('#email').val(),
                       'phone_dummy':$('#phone_dummy').val(),
                       'customers':customers,
                       'pk':pk,
                       'start_at':event_start_at,
                       'turitop_product_short_id':'<?php echo $turitop_product_short_id;?>',
                       'action':'checkAbilityAndBook',
                       'security':'streetours_fareharbor_nonce'
                   };
                   $.post('<?php echo admin_url('admin-ajax.php');?>', data, function (response) {
                       console.log('----ability check response---', response);
                       if(response.success === true) {
                           let res = response.data.response;
                       }
                   });
               }
           </script>

           <div class="container">
               <h3 style="text-align: center;"><?php echo $fhItem->name;?></h3>
               <div class="row">
                   <div class="col-12 col-sm-12">
                       <div class="card-deck st-fare" id="step1" style="display:block;">
                           <div id="calendar">

                           </div>
                           <div id="events" style="margin-top: 20px;">

                           </div>
                           <div id="announcement">

                           </div>
                           <div id="error">

                           </div>
                           <div id="total_price" style="text-align: right;">

                           </div>
                           <div id="next_btn" style="text-align: center;">

                           </div>
                       </div>

                       <div class="card-deck st-fare" id="step2" style="display: none;">
                            <div id="date" style="margin-left: 40px;">

                            </div>
                           <div id="price" style="margin-left: 40px;">

                           </div>
                           <div id="payment_form">
                               <ul class="form-generator-front-fields-list">
                                    <li>
                                        <div class="label1"> Full name <span id="name_required" class="required-asterisk">*</span> <div class="error2"></div> </div>
                                        <div class="input"> <input type="text" name="name" value="" id="name" maxlength="100" class="name"> </div>
                                    </li>
                                   <li>
                                       <div class="label1"> Email <span id="email_required" class="required-asterisk">*</span> <div class="error2"></div> </div>
                                       <div class="input"> <input type="text" name="email" value="" id="email" maxlength="512" class="email"> </div>
                                   </li>
                                   <li>
                                       <div class="label1"> Phone <span id="phone_required" class="required-asterisk">*</span> <div class="error2"></div> </div>
                                       <div id="phone_field_" class="input"> <input type="text" name="phone_dummy" value="" id="phone_dummy" maxlength="50" style="width: 182px;" class="phone" autocomplete="off"><input type="hidden" name="phone"> </div>
                                   </li>

                               </ul>
                           </div>
                           <div id="book_btn" style="text-align: center;">
                               <button onclick="checkAbilityAndBook()" style="background: #00b22d;color: white; border: none; padding: .5em 1em;border-radius: 3px; margin-top: 20px;">MAKE BOOKING</button>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
    <?php
        endif;
    endforeach;
}
else{?>
    <link rel="stylesheet" href="../js/fullcalendar/fullcalendar.min.css">
<!--    <script src="../js/jquery.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
<!--    <script src="../js/bootstrap.min.js"></script>-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="../js/swiper/js/swiper.min.js"></script>
    <script src="../js/moment.min.js"></script>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <script src="../js/daterangepicker/daterangepicker.js"></script>

    <script src="../js/jquery-ui/jquery-ui.min.js"></script>
    <script src="../js/fullcalendar/fullcalendar.min.js"></script>
    <script type="text/javascript">


        $(document).ready(function(){
            var $this = this;
            var calendar = $('#calendar').fullCalendar({
                //slotDuration: '00:30:00', /* If we want to split day time each 30minutes */
                //minTime: '08:00:00',
                //maxTime: '18:00:00',
                unselectAuto:false,
                allDay:true,
                defaultView: 'month',
                handleWindowResize: true,
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                selectable: true,
                drop: function(date) { $this.onDrop($(this), date); },
                select: function (start, end, allDay) {
                        // if(end.getDate() != start.getDate()){
                        //     calendar.fullCalendar( 'unselect' ) ;
                        // }
                    console.log('--type of start date--', typeof start, start);
                    },
                eventClick: function(calEvent, jsEvent, view) {  }

            });
        });
        var CalendarApp = function() {
            this.$body = $("body")
            this.$calendar = $('#calendar'),
                this.$event = ('#calendar-events div.calendar-events'),
                this.$categoryForm = $('#add_new_event form'),
                this.$extEvents = $('#calendar-events'),
                this.$modal = $('#my_event'),
                this.$saveCategoryBtn = $('.save-category'),
                this.$calendarObj = null
        };
        //init CalendarApp
        $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp
    </script>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card-deck st-fare" >
                    <div id="calendar">

                    </div>
                    <div class="card st-item" style="width: 100% !important;display: none;">

                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?item=183&company=bodyglove"
                           class="st-item-dolphin st-f-1">
                            <div class="card-body st-p-0">
                                <img src="https://d1a2dkr8rai8e2.cloudfront.net/api/file/goyMvVpnQkWAAGm9vLbH">
                            </div>
                        </a>
                        <div class="card-body">
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?item=183&company=bodyglove"
                               class="st-item-dolphin st-f-1"><b>Snorkel
                                    & Dolphin Adventure</b></a>
                            <p>
                                Step aboard our 65′ Catamaran for a fun-filled day of adventure! This 4.5 hour cruise
                                offers breakfast, lunch, plenty of shade, cushioned seating, 20′ waterslide, and
                                more!
                            </p>
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?item=183&company=bodyglove" class="st-a-btn">
                                <span id="btn-1" style="margin-top: 1rem!important;" class="btn st-learn-more">
                                    <span class="inner btn-1">
                                        <span class="label">Learn More</span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

}
?>

<?php //require 'fh-footer.php' ?>