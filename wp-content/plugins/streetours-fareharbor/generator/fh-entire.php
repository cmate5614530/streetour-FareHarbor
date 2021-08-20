<?php

require 'fh-header.php';
//require_once '../includes/class.streetours-fareharbor-ajax.php';
$type = $_REQUEST['type'] ?? 'list';
$book_item = $_REQUEST['item'] ?? '183';
$date = $_REQUEST['date'] ?? '';
//$ajax_nonce = wp_create_nonce('streetours_fareharbor_nonce');
if (!empty($book_item)) {

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
               });

               function checkAbilityOfSelectedDate(date) {
                   var data = {
                       'date': date,
                       'action':'checkAbilityOfSelectedDate',
                       'security':'streetours_fareharbor_nonce'
                   };
                   $.post('<?php echo admin_url('admin-ajax.php');?>', data, function (response) {
                        console.log('----response---', response);
                   })
               }
           </script>

           <div class="container">
               <h3 style="text-align: center;"><?php echo $fhItem->name;?></h3>
               <div class="row">
                   <div class="col-12 col-sm-12">
                       <div class="card-deck st-fare" >
                           <div id="calendar">

                           </div>
                           <div class="eventsCalendar-list-wrap" style="width: 216px;">
                               <div class="eventsCalendar-subtitle" style="height: 2em;">
                                   <img class="arrow-select-hour" src="/img/arrow-select-hour.png?20161020" style="height: 30px;">
                                   <span class="arrow-select-hour">Select time</span>
                               </div>
                               <div class="eventsCalendar-list-content">
                                   <ul class="eventsCalendar-list" style="opacity: 1; left: 0px; height: auto; display: block;">
                                       <label class="event-label event-label-selected" id="event_label_1700" onclick="updateBox('2021-08-20','17:00');">
                                           <span id="0" class="event">
                                               <input style="display:none" class="hour-to-select" type="radio" id="hour-2021-08-20" name="hour-to-select" value="17:00">
                                               17:00
                                           </span>
                                       </label>
                                   </ul>
                               </div>
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