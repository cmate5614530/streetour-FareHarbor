<?php
/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

do_action( 'turitop_booking_system_round_trip_before', $args );

?>

<div id="turitop_booking_system_round_trip_wrap" class="turitop_booking_system_round_trip_wrap">

  <?php do_action( 'turitop_booking_system_round_trip_before_wrap', $args ); ?>

  <div class="turitop_booking_system_round_trip_ways">

    <label class="turitop_booking_system_radio_container">Round trip
      <input type="radio" id="round_trip" name="turitop_booking_system_round_trip_type" value="round_trip" checked="checked">
      <span class="turitop_booking_system_radio_checkmark"></span>
    </label>

    <label class="turitop_booking_system_radio_container">One way
      <input type="radio" id="one_way" name="turitop_booking_system_round_trip_type" value="one_way">
      <span class="turitop_booking_system_radio_checkmark"></span>
    </label>

  </div>

  <div class="turitop_booking_system_round_trip_from_to">

    <?

    $from_options = array();
    $to_options = array();
    foreach ( TURITOP_BS()->get_round_trip_booking_data() as $trip ) {
      $from_options[ $trip[ 'from' ] ] = $trip[ 'from' ];
      $to_options[ $trip[ 'to' ] ] = $trip[ 'to' ];
    }

    ?>

    <span class="turitop_booking_system_round_trip_span_from">

      <select class="turitop_booking_system_round_trip_select" name="turitop_booking_system_round_trip_from">

      <?php

        echo "<option value='0'>" . TURITOP_BS()->common_translations[ 'choose_from' ] . "</option>";

        foreach ( $from_options as $from ) {
          echo "<option value='" . $from . "'>" . $from . "</option>";
        }

      ?>

      </select>

    </span>

    <span class="fa fa-exchange"></span>

    <span class="turitop_booking_system_round_trip_select_spacer">

    </span>

    <span class="turitop_booking_system_round_trip_select_to_loading">
      <img src="<?php echo TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/images/turitop-ajax-loader-bar.gif' ?>"/>
    </span>

    <span class="turitop_booking_system_round_trip_span_to">

      <select class="turitop_booking_system_round_trip_select" name="turitop_booking_system_round_trip_to">

      <?php

        echo "<option value='0'>" . TURITOP_BS()->common_translations[ 'choose_origin' ] . "</option>";

      ?>

      </select>

    </span>

    <div class="turitop_booking_system_round_trip_services_no_selected">

      <span> <?php echo ( isset( $args[ 'tbs_data' ][ 'round_trip_message_below' ] ) ? $args[ 'tbs_data' ][ 'round_trip_message_below' ] : '' ); ?> </span>

    </div>

  </div>

  <div class="turitop_booking_system_round_trip_services_wrap">

    <?php do_action( 'turitop_booking_system_round_trip_before_services_wrap', $args ); ?>

    <div class="turitop_booking_system_round_trip_services_display">

      <div class="turitop_booking_system_round_trip_services_menu">

        <div id="turitop_booking_system_round_trip_service_menu_outbound" class="turitop_booking_system_round_trip_service_menu_outbound turitop_booking_system_round_trip_service_menu_tab turitop_booking_system_round_trip_service_menu_selected"><?php echo _x( 'Outbound', 'settings', 'turitop-booking-system' ); ?></div>

        <div id="turitop_booking_system_round_trip_service_menu_return" class="turitop_booking_system_round_trip_service_menu_return turitop_booking_system_round_trip_service_menu_tab turitop_booking_system_round_trip_service_menu_no_selected"><?php echo _x( 'Return', 'settings', 'turitop-booking-system' ); ?></div>

      </div>

      <div id="turitop_booking_system_round_trip_services" class="turitop_booking_system_round_trip_services">

        <input type="hidden" name="turitop_booking_system_outbound_service" value="" />
        <input type="hidden" name="turitop_booking_system_return_service" value="" />

        <div class="turitop_booking_system_round_trip_services_img_loading">
          <img src="<?php echo TURITOP_BOOKING_SYSTEM_ASSETS_URL . '/images/turitop-ajax-loader.gif'  ?>" />
        </div>

        <div class="turitop_booking_system_round_trip_service_outbound">

        </div>

        <div class="turitop_booking_system_round_trip_service_return">

        </div>

      </div>

    </div>

    <?php do_action( 'turitop_booking_system_round_trip_after_services_wrap', $args ); ?>

  </div>

  <?php do_action( 'turitop_booking_system_round_trip_after_wrap', $args ); ?>

</div>

<?php do_action( 'turitop_booking_system_round_trip_after', $args ); ?>
