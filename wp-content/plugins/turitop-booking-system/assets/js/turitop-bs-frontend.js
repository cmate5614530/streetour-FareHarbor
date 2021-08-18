jQuery( document ).ready(function( $ ) {

    function turitop_bs_frontend() {

    }

    turitop_bs_frontend.prototype.cart_change_event = function(){

      $( "#turitop-cart-button span.badge-cart" ).on( 'DOMSubtreeModified', function () {
        $( 'a.turitop_booking_system_wp_cart span.turitop_booking_system_cart_counter' ).html( $(this).html() );
      });

    };

    turitop_bs_frontend.prototype.cart_click_event = function(){

      $( "body" ).on( "click", 'a.turitop_booking_system_wp_cart', function ( event ){

        event.preventDefault();
        $( '#turitop-cart-button' ).click();

      });

    };

    var turitop_bs_frontend_instance = new turitop_bs_frontend();

    turitop_bs_frontend_instance.cart_change_event();
    turitop_bs_frontend_instance.cart_click_event();

});
