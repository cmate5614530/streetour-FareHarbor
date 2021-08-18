/*<style>

.cart-back{
  display: none;
}

</style>*/

//<script>

var count_changing = 0;

function change_calendar_month( count_changing ){

  setTimeout( function(){

    console.log( 'count_changing -> ' + count_changing );

    if ( $( '.loading-background' ).is( ':visible' ) && count_changing < 15 ){
      console.log( 'visible' );
      count_changing = count_changing + 1;
      change_calendar_month( count_changing );
    }
    else{
      console.log( 'Finish with count_changing -> ' + count_changing );
      $( '#eventCalendarDefault' ).attr( 'data-current-month', 6 );
      $( '#eventCalendarDefault' ).find( 'a.next' ).click();
      count_changing = 0;
      change_calendar_day( count_changing );
    }

  //$( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li:nth-of-type(15) a' ).click();
   },
  500 );

}

change_calendar_month( count_changing );

function change_calendar_day( count_changing ){

  setTimeout( function(){

    console.log( 'count_changing day -> ' + count_changing );

    if ( $( '.loading-background' ).is( ':visible' ) && count_changing < 15 ){
      console.log( 'visible' );
      count_changing = count_changing + 1;
      change_calendar_day( count_changing );
    }
    else{
      console.log( 'Finish with count_changing day -> ' + count_changing );
      $( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li:nth-of-type(15)' ).click();
      setTimeout( function(){

        console.log( 'count_changing day -> ' + count_changing );

        if ( $( '.loading-background' ).is( ':visible' ) && count_changing < 15 ){
          console.log( 'visible' );
          count_changing = count_changing + 1;
          change_calendar_day( count_changing );
        }
        else{
          console.log( 'Finish with count_changing day -> ' + count_changing );

          $( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li:nth-of-type(15) a' ).click();
          /*var rel = 0;
          $( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li.eventsCalendar-day' ).each( function( index ) {

            rel =
            $( this ).removeClass( 'dayWithEvents' );
            $( this ).addClass( 'dayEmpty' );
            $( this ).addClass( 'disable' );

          });*/

        }

      //$( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li:nth-of-type(15) a' ).click();
       },
      500 );
    }

  //$( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li:nth-of-type(15) a' ).click();
   },
  500 );

}

if ( $( '.loading-background' ).is( ':visible' ) )
  console.log( 'visible' );

console.log( 'class -> ' + $( '#eventCalendarDefault' ).find( '.eventsCalendar-daysList li:nth-of-type(15)' ).attr( 'class' ) );
change_calendarchange_calendar

jQuery( document ).ready(function( $ ) {

  function readCookie(name) {

    var nameEQ = name + "=";
    var ca = document.cookie.split(';');

    for(var i=0;i < ca.length;i++) {

      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) {
        return decodeURIComponent( c.substring(nameEQ.length,c.length) );
      }

    }

    return null;

  }

  var site = readCookie( 'returnUrl' );
  var key = 'NtGhGI4fvh9uNuoxlK6v6DpvoFwxn9z1';

  var referer = $( 'input[name=referer]' ).val();
  if ( ! referer.includes( "app.turitop.com" ) ){

    var currentLocation = window.location + "";
    var currentLocation_split = currentLocation.split( "/" );
    var product = currentLocation_split[ 6 ];

  }
  else{

    var referer_split = referer.split( "/" );
    var product = referer_split[ 6 ];

  }

  var lang = document.documentElement.lang;
  switch( lang ) {
    case 'es':
        var outbound_message = "Termina la reserva seleccionando el ticket de vuelta";
      break;
    default:
      var outbound_message = "Finish the booking choosing the return ticket";
  }

  parent.postMessage(
    {
      key: key,
      product: product,
      height: $( 'html' ).height(),
    },
    site
  );

  if ( window.addEventListener ) {
    window.addEventListener( "message", handleMessage );
  } else {
    window.attachEvent( "onmessage", handleMessage );
  }

  function handleMessage(event) {

    if ( site == event.origin ) {
      console.log( "The message came from some site we don't know. We're not processing it." );
      return;
    }

    var dataFromChildIframe = event.data;

    if ( dataFromChildIframe.key ){

      switch( dataFromChildIframe.type ) {

        case 'one_way':

          switch( lang ) {
            case 'es':
                var add_button = "Añadir ida";
                var data_title = "Tus datos";
              break;
            default:
              var add_button = "Add outbound";
              var data_title = "Your data";
          }

          $( "#cart-add-submit-button" ).val( add_button );
          $( "#cart-add-submit-button" ).css( "cursor", "pointer" );

          if ( $( "#action_buy_now_2" ).length && ! $( ".booking-box-left-2" ).find( '.bookingbox-step2-header-data-return' ).length ){
            $( ".booking-box-left-2" ).find( '.cart-back' ).after( '<h1 class="bookingbox-step2-header bookingbox-step2-header-data-return" style="margin-bottom:20px;">' + data_title + '</h1>' );
            $( ".booking-box-left-2" ).find( '.cart-back' ).hide();
          }

          break;

        case 'outbound':

          switch( lang ) {
            case 'es':
                var add_button = "Añadir ida";
              break;
            default:
              var add_button = "Add outbound";
          }

          $( "#cart-add-submit-button" ).val( add_button );
          $( "#cart-add-submit-button" ).css( "cursor", "pointer" );

          if ( $( "#action_buy_now_2" ).length ){

             $( ".booking-box-left-2" ).hide();
             $( "#action_buy_now_2" ).hide();

            if ( ! $( "#turitop_message_next_way" ).length ){

              parent.postMessage(
                {
                  key: key,
                  step: 'return',
                },
                site
              );

              $( "#action_buy_now_2" ).after( '<p id="turitop_message_next_way" style="font-weight: bold;">' + outbound_message + '</p>' );

            }

          }

          break;

        case 'return':

          switch( lang ) {
            case 'es':
                var add_button = "Añadir vuelta";
                var data_title = "Tus datos";
              break;
            default:
              var add_button = "Add return";
              var data_title = "Your data";
          }

          $( "#cart-add-submit-button" ).val( add_button );
          $( "#cart-add-submit-button" ).css( "cursor", "pointer" );

          if ( $( "#action_buy_now_2" ).length && ! $( ".booking-box-left-2" ).find( '.bookingbox-step2-header-data-return' ).length ){
            $( ".booking-box-left-2" ).find( '.cart-back' ).after( '<h1 class="bookingbox-step2-header bookingbox-step2-header-data-return" style="margin-bottom:20px;">' + data_title + '</h1>' );
            $( ".booking-box-left-2" ).find( '.cart-back' ).hide();
          }

          break;

        default:

      }

    }

  }

});

//</script>
