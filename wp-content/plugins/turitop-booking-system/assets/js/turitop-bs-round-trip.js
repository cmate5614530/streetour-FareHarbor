function turitop_bs_round_trip() {

  this.round_trip_key = 'NtGhGI4fvh9uNuoxlK6v6DpvoFwxn9z1';
  this.wrap_parent = '';
  this.old_text_align = '';
  this.old_width = '';

}

jQuery( document ).ready(function( $ ) {

    turitop_bs_round_trip.prototype.init = function( wrap_parent ){

      var $this = this;

      if ( $( '.turitop_booking_system_round_trip_select' ).length )
          $( '.turitop_booking_system_round_trip_select' ).select2();

      this.wrap_parent = wrap_parent;

      var type = $( this.wrap_parent ).find( 'input[name=turitop_booking_system_round_trip_type]:checked' ).val();
      this.trip_type_selected( type );

      this.events();

      this.old_text_align = $( this.wrap_parent ).css( 'text-align' );

      this.responsive();

      $( window ).resize( function () {
          $this.responsive();
      });

    }

    turitop_bs_round_trip.prototype.responsive = function(){

      var paret_total_width = $( this.wrap_parent ).parent().width();

      var main_box_total_width = $( this.wrap_parent ).outerWidth( true );

      if ( paret_total_width <= main_box_total_width ){
        $( this.wrap_parent ).css( 'width', 'auto' );
      }

      main_box_total_width = $( this.wrap_parent ).outerWidth( true );

      if ( main_box_total_width <= 485 ){
        $( this.wrap_parent ).find( '.fa-exchange' ).hide();
        $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_select_spacer' ).show();
        $( this.wrap_parent ).css( 'text-align', 'center' );
      }
      else{
        $( this.wrap_parent ).find( '.fa-exchange' ).show();
        $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_select_spacer' ).hide();
        $( this.wrap_parent ).css( 'text-align', this.old_text_align );
      }


    }

    turitop_bs_round_trip.prototype.trip_type_selected = function( type ){

      if ( type == 'round_trip' ){
        $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_ways label:nth-of-type(1)' ).css( 'font-weight', 'bold' );
        $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_ways label:nth-of-type(2)' ).css( 'font-weight', 'normal' );
      }
      else{
        $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_ways label:nth-of-type(2)' ).css( 'font-weight', 'bold' );
        $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_ways label:nth-of-type(1)' ).css( 'font-weight', 'normal' );
      }

    }

    turitop_bs_round_trip.prototype.hide_services = function(){

      $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_services_no_selected' ).show();
      $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_services_wrap' ).hide();

    }

    turitop_bs_round_trip.prototype.show_services = function(){

      $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_services_no_selected' ).hide();
      $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_services_wrap' ).show();

    }

    turitop_bs_round_trip.prototype.events = function(){

      var $this = this;

      $( ".turitop_booking_system_round_trip_wrap input[name=turitop_booking_system_round_trip_type]" ).on( 'change', function ( event ) {

        event.preventDefault();

        var type = $( this ).val();

        if ( type == "one_way" ){
          $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).hide();
          $this.change_tab( 'outbound' );
        }
        else{
          $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).show();
          $this.change_tab( 'outbound' );
        }

        var from = $( $this.wrap_parent ).find( 'select[name=turitop_booking_system_round_trip_from]' ).val();
        var to = $( $this.wrap_parent ).find( 'select[name=turitop_booking_system_round_trip_to]' ).val();

        $this.trip_type_selected( type );

        if ( from != 0 && to != 0 ){
          args = {
            type: type,
            from: from,
            to: to,
          };
          $this.display_services( args );
        }
        else{
          $this.hide_services();
        }

      });

      $( ".turitop_booking_system_round_trip_wrap select[name=turitop_booking_system_round_trip_from]" ).on( 'change', function ( event ) {

        event.preventDefault();
        //$this.wrap_parent = $( this ).closest( '.turitop_booking_system_round_trip_wrap' );

        $this.hide_services();

        $( $this.wrap_parent ).find( 'select[name=turitop_booking_system_round_trip_to]' ).next( '.select2-container' ).hide();
        $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_select_to_loading' ).show();

        var from = $( this ).val();

        var ajax_args = {
          from: from,
        }

        var ajax_data = {
          action      : 'turitop_booking_system_round_trip_select_to',
          args        : ajax_args,
          security    : tbs_round_trip.round_trip_nonce,
        }

        $.ajax({
            data      : ajax_data,
            url       : tbs_round_trip.ajax_url,
            type      : 'post',
            error     : function ( response ) {
                console.log( response );
            },
            success   : function ( response ) {

              // Display 'to' options
              $( $this.wrap_parent ).find( 'select[name=turitop_booking_system_round_trip_to]' ).html( response.data.to_options );

              $( $this.wrap_parent ).find( 'select[name=turitop_booking_system_round_trip_to]' ).next( '.select2-container' ).show();
              $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_select_to_loading' ).hide();

            }

        });

      });

      $( ".turitop_booking_system_round_trip_wrap select[name=turitop_booking_system_round_trip_to]" ).on( 'change', function ( event ) {

        event.preventDefault();

        var type = $( $this.wrap_parent ).find( 'input[name=turitop_booking_system_round_trip_type]:checked' ).val();
        var from = $( $this.wrap_parent ).find( 'select[name=turitop_booking_system_round_trip_from]' ).val();
        var to = $( this ).val();

        if ( from != 0 && to != 0 ){
          args = {
            type: type,
            from: from,
            to: to,
          };
          $this.display_services( args );
        }
        else{
          $this.hide_services();
        }

      });

      $( ".turitop_booking_system_round_trip_wrap .turitop_booking_system_round_trip_service_menu_outbound" ).on( 'click', function ( event ) {

        event.preventDefault();
        $this.change_tab( 'outbound' );

      });

      $( ".turitop_booking_system_round_trip_wrap .turitop_booking_system_round_trip_service_menu_return" ).on( 'click', function ( event ) {

        event.preventDefault();
        $this.change_tab( 'return' );

      });

      if ( window.addEventListener ) {
      	window.addEventListener( "message", function( event ){

          var args = {
            round_trip_key : $this.round_trip_key,
            wrap_parent: $this.wrap_parent,
          };
          $this.handleMessage( event, args );

        });
      } else {
      	window.attachEvent( "onmessage", function( event ){

          var args = {
            round_trip_key : $this.round_trip_key,
            wrap_parent: $this.wrap_parent,
          };
          $this.handleMessage( event, args );

        });
      }

    };

    turitop_bs_round_trip.prototype.display_services = function( args ){

      var $this = this;

      this.show_services();
      $( this.wrap_parent ).find( ".turitop_booking_system_round_trip_services_img_loading" ).show();
      $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).hide();
      $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_return' ).hide();

      var window_width = $( window ).width();
      if ( window_width < 650 ){
        $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_services_display' ).width( window_width - 30 );
      }
      else{
        $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_services_display' ).width( '600' );
      }

      var ajax_args = {
        type: args[ 'type' ],
        from: args[ 'from' ],
        to: args[ 'to' ],
      }

      var ajax_data = {
        action      : 'turitop_booking_system_round_trip',
        args        : ajax_args,
        security    : tbs_round_trip.round_trip_nonce,
      }

      $.ajax({
          data      : ajax_data,
          url       : tbs_round_trip.ajax_url,
          type      : 'post',
          error     : function ( response ) {
              console.log( response );
          },
          success   : function ( response ) {

            // OUTBOUND SERVICE
            $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).html( response.data.outbound_box );
            $( $this.wrap_parent ).find( 'input[name=turitop_booking_system_outbound_service]' ).val( response.data.outbound_service );

            // RETURN SERVICE
            $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_return' ).html( response.data.return_box );
            $( $this.wrap_parent ).find( 'input[name=turitop_booking_system_return_service]' ).val( response.data.return_service );

            $this.change_tab( 'outbound' );

            // TuriTop bulding
            if ( typeof turitopBuild == 'function' ) {

              turitopBuild();
              $( $this.wrap_parent ).find( ".turitop_booking_system_round_trip_services_img_loading" ).hide();

              setTimeout( function(){

                  $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).find( 'iframe' ).load( function(){

                      $( $this.wrap_parent ).find( ".turitop_booking_system_round_trip_services_img_loading" ).hide();

                      if ( window_width < 650 ){
                        $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).find( 'iframe' ).css( 'height', '800px' );
                      }
                      else{
                        $( $this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).find( 'iframe' ).css( 'height', '400px' );
                      }

                  });
                },
                4000
              );

            }

          }

      });

    };

    turitop_bs_round_trip.prototype.change_tab = function( tab ){

      switch ( tab ) {

        case 'outbound':
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).show();
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).css( 'font-weight', 'bold' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).removeClass( 'turitop_booking_system_round_trip_service_menu_no_selected' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).addClass( 'turitop_booking_system_round_trip_service_menu_selected' );

          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_return' ).hide();
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).css( 'font-weight', 'normal' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).removeClass( 'turitop_booking_system_round_trip_service_menu_selected' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).addClass( 'turitop_booking_system_round_trip_service_menu_no_selected' );

          break;

        case 'return':

          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).hide();
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).css( 'font-weight', 'normal' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).addClass( 'turitop_booking_system_round_trip_service_menu_no_selected' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).removeClass( 'turitop_booking_system_round_trip_service_menu_selected' );

          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_return' ).show();
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).css( 'font-weight', 'bold' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).removeClass( 'turitop_booking_system_round_trip_service_menu_no_selected' );
          $( this.wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).addClass( 'turitop_booking_system_round_trip_service_menu_selected' );

          break;
      }

    }

    turitop_bs_round_trip.prototype.handleMessage = function( event, args ){

      var round_trip_key = args.round_trip_key;
      var wrap_parent = args.wrap_parent;

      if ( event.origin != "https://app.turitop.com" ) {
        console.log("The message came from some site we don't know. We're not processing it.");
        return;
      }

      var dataFromChildIframe = event.data;

      if ( ! dataFromChildIframe.key || 0 === dataFromChildIframe.key.length || typeof dataFromChildIframe.key == 'undefined' || dataFromChildIframe.key != round_trip_key )
        return;

      if ( 'return' == dataFromChildIframe.step ){

        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).hide();
        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).css( 'font-weight', 'normal' );
        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).addClass( 'turitop_booking_system_round_trip_service_menu_no_selected' );
        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_outbound' ).removeClass( 'turitop_booking_system_round_trip_service_menu_selected' );

        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_return' ).show();
        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).css( 'font-weight', 'bold' );
        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).removeClass( 'turitop_booking_system_round_trip_service_menu_no_selected' );
        $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_menu_return' ).addClass( 'turitop_booking_system_round_trip_service_menu_selected' );

        return;
      }

      var service_type = $( wrap_parent ).find( 'input[name=turitop_booking_system_round_trip_type]:checked' ).val();
      if ( service_type == "one_way" ){
        var iFrame = $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).find( 'iframe' );
        $( iFrame ).height( parseInt( dataFromChildIframe.height ) + 50 );

        $( iFrame ).get( 0 ).contentWindow.postMessage(
          {
            key: round_trip_key,
            type: 'one_way',
          },
          "https://app.turitop.com"
        );
        return;
      }

      var outbound_service = $( wrap_parent ).find( 'input[name=turitop_booking_system_outbound_service]' ).val();
      if ( outbound_service == dataFromChildIframe.product ){
        var iFrame = $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_outbound' ).find( 'iframe' );
        $( iFrame ).height( parseInt( dataFromChildIframe.height ) + 50 );

        $( iFrame ).get( 0 ).contentWindow.postMessage(
          {
            key: round_trip_key,
            type: 'outbound',
          },
          "https://app.turitop.com"
        );
        return;
      }

      var return_service = $( wrap_parent ).find( 'input[name=turitop_booking_system_return_service]' ).val();
      if ( return_service == dataFromChildIframe.product ){
        var iFrame = $( wrap_parent ).find( '.turitop_booking_system_round_trip_service_return' ).find( 'iframe' );
        $( iFrame ).height( parseInt( dataFromChildIframe.height ) + 50 );

        $( iFrame ).get( 0 ).contentWindow.postMessage(
          {
            key: round_trip_key,
            type: 'return',
          },
          "https://app.turitop.com"
        );
        return;
      }

    };

    $( '.turitop_booking_system_round_trip_wrap' ).each( function(){

        var turitop_bs_round_trip_instance = new turitop_bs_round_trip();

        turitop_bs_round_trip_instance.init( this );

    });

});
