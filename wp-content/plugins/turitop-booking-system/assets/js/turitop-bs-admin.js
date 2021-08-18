jQuery( document ).ready(function( $ ) {

    function turitop_bs_admin() {

      this.num_products = 0;
      this.count = 0;

    }

    turitop_bs_admin.prototype.upload_service_event = function(){

        var $this = this;

        $( "body" ).on( "click", 'a.turitop_booking_system_synhronize_upload_button_services', function ( event ) {

            event.preventDefault();

            var service_id = $( this ).data( 'service_id' );
            var order = $( '#turitop_booking_system_select_service_order_' + service_id ).val();
            var page_id = $( '#turitop_booking_system_select_page_for_service_' + service_id ).val();

            console.log( 'service_id -> ' + service_id + ' order -> ' + order + ' page_id -> ' + page_id );
            $( this ).hide();
            var process = '<img style="margin-top: 8px;" src="' + turitop_object_admin.ajax_loader_bar + '" alt="processing">';
            $( this ).closest( 'td' ).append( process );

            var ajax_data = {
              action      : 'turitop_booking_system_synchronize_services_upload',
              service_id  : service_id,
              order       : order,
              page_id     : page_id,
              security    : turitop_object_admin.admin_nonce,
            }

            $.ajax({
                data      : ajax_data,
                url       : turitop_object_admin.ajax_url,
                type      : 'post',
                error     : function ( response ) {
                    console.log( response );
                },
                success   : function ( response ) {
                  location.reload();
                }
            });

        });

    };

    /** CHECKING TURITOP INUPTS **/

    turitop_bs_admin.prototype.check_turitop_selected = function( element, trigger, element_dom_affected ){

        switch( trigger ) {

          case 'checked':

              if ( $( element ).is( ':checked' ) )
                  $( element_dom_affected ).show();
              else
                  $( element_dom_affected ).hide();

            break;

          case 'unchecked':

              if ( $( element ).is( ':checked' ) )
                  $( element_dom_affected ).hide();
              else
                  $( element_dom_affected ).show();

            break;

          default:

              if ( $( element ).val() == trigger )
                  $( element_dom_affected ).hide();
              else
                  $( element_dom_affected ).show();
        }

    };

    turitop_bs_admin.prototype.check_turitop_selected_event = function( element_dom, trigger, element_dom_affected ){

        $( "body" ).on( "change", element_dom, function ( e ) {
            turitop_bs_admin_instance.check_turitop_selected( this, trigger, element_dom_affected );
        });

    };

    turitop_bs_admin.prototype.advanced_save_button = function(){

      var element_dom;
      element_dom = 'input[name=turitop_booking_system_settings_advanced_redirect_url]';
      if ( $( element_dom ).length && $( element_dom ).val() != '0' ){
          window.location.href = $( element_dom ).val();
      }

      $( "body" ).on( "click", '.turitop_booking_system_save_VIP_settings', function ( event ) {

        event.preventDefault();

        var element_dom;
        element_dom = 'input[name=turitop_booking_system_settings_activate_VIP]';
        if ( $( element_dom ).length && $( element_dom ).attr( 'checked' ) ){
            $( 'input[name=turitop_booking_system_settings_advanced_redirect]' ).val( 'yes' )
        }

        $( this ).closest( 'form' ).submit();

      });

    };

    turitop_bs_admin.prototype.init = function(){

      this.events();

    };

    turitop_bs_admin.prototype.events = function(){

      var $this = this;

      this.upload_service_event();

      this.advanced_save_button();

      var element_dom;

      /** CHECKING ACTIVATE TURITOP ON WC PRODUCT CHECKBOX **/
      element_dom = 'input[name=_turitop_booking_system_activated]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'checked', '.turitop_bs_admin_wrap' );
          this.check_turitop_selected( element_dom, 'checked', '.turitop_bs_admin_wrap' );
      }

      /** CHECKING EMBED AS BUTTON ON WC PRODUCT SELECT **/
      element_dom = 'select[name=_turitop_booking_system_embed]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'button', '.turitop_bs_blank_brightness_button_wrap' );
          this.check_turitop_selected( element_dom, 'button', '.turitop_bs_blank_brightness_button_wrap' );
      }

      /** CHECKING EMBED AS BUTTON ON GENERAL SETTINGS SELECT **/
      /*element_dom = 'input[name=turitop_booking_system_settings_embed]';
      if ( $( element_dom ).length ){
          turitop_bs_admin_instance.check_turitop_selected_event( element_dom, 'box', '.turitop_bs_blank_brightness_button_whole_wrap' );
          turitop_bs_admin_instance.check_turitop_selected( element_dom + ':checked', 'box', '.turitop_bs_blank_brightness_button_whole_wrap' );
      }*/

      var element_dom = 'select[name=turitop_booking_system_styles_buttoncolor]';
      if ( $( element_dom ).length ){

        $( "body" ).on( "change", element_dom, function ( e ) {
            var check_to_activate = 'input[name=turitop_booking_system_styles_box_button_custom_activate]';

            if ( $( this ).val() == 'custom' ){
              $( check_to_activate ).prop( 'checked', true );
            }
            else {
              $( check_to_activate ).prop( 'checked', false );
            }

            $this.check_turitop_selected( check_to_activate, 'unchecked', '.turitop_bs_blank_brightness_button_wrap' );

        });

      }

      /** CHECKING CUSTOM BUTTON ON GENERAL SETTINGS SELECT **/
      var element_dom = 'input[name=turitop_booking_system_styles_box_button_custom_activate]';
      if ( $( element_dom ).length ){

          $( "body" ).on( "change", element_dom, function ( e ) {

              var select_to_change = 'select[name=turitop_booking_system_styles_buttoncolor]';

              var check_to_activate = 'input[name=turitop_booking_system_styles_box_button_custom_activate]';

              if ( $( this ).is( ':checked' ) ){
                $( select_to_change ).val( 'custom' ).trigger('change');
                $( '.turitop_bs_admin_button_wrap' ).show();
              }
              else{
                $( select_to_change ).val( 'green' ).trigger('change');
                $( '.turitop_bs_admin_button_wrap' ).hide();
              }

          });

          if ( $( element_dom ).is( ':checked' ) ){
            $( '.turitop_bs_admin_button_wrap' ).show();
          }
          else{
            $( '.turitop_bs_admin_button_wrap' ).hide();
          }

          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_button_wrap' );
      }

      /** CHECKING SERVICE CUSTOM URL **/
      var element_dom = 'select[name=turitop_booking_system_service_page_id]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'custom', '.turitop_bs_blank_brightness_service_custom_url_wrap' );
          this.check_turitop_selected( element_dom, 'custom', '.turitop_bs_blank_brightness_service_custom_url_wrap' );
      }

      /** CHECKING BUTTOM IMAGE ACTIVATE ON GENERAL SETTINGS SELECT **/
      var element_dom = 'input[name=turitop_booking_system_styles_button_image_activate]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'unchecked', '.turitop_bs_blank_brightness_button_image_wrap' );
          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_button_image_wrap' );
      }

      /** CHECKING CART ON MENU ON GENERAL SETTINGS SELECT **/
      var element_dom = 'input[name=turitop_booking_system_settings_cart_on_menu]';
      if ( $( element_dom ).length ){

          this.check_turitop_selected_event( element_dom, 'unchecked', '.turitop_bs_blank_brightness_cart_on_menu_wrap' );
          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_cart_on_menu_wrap' );

          $( "body" ).on( "change", element_dom, function ( e ) {

              var check_to_activate = 'input[name=turitop_booking_system_settings_cart_custom_activate]';

              if ( $( this ).is( ':checked' ) ){
                $( check_to_activate ).prop( 'checked', true );
                $( '.turitop_bs_blank_brightness_cart_custom_wrap' ).hide();
                $( '.turitop_bs_admin_custom_cart_wrap' ).show();
              }
              else{
                $( '.turitop_bs_admin_custom_cart_wrap' ).hide();
              }

          });

          if ( $( element_dom ).is( ':checked' ) ){
            console.log( 'IS CHECKED' );
            $( '.turitop_bs_admin_custom_cart_wrap' ).show();
          }
          else{
            console.log( 'IS NOT CHECKED' );
            $( 'input[name=turitop_booking_system_settings_cart_custom_activate]' ).prop( 'checked', false );
            $( '.turitop_bs_admin_custom_cart_wrap' ).hide();
          }

      }

      /** CHECKING CART ON MENU ON GENERAL SETTINGS SELECT **/
      var element_dom = 'input[name=turitop_booking_system_settings_cart_checkbox_text]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'unchecked', '.turitop_bs_blank_brightness_cart_custom_text_wrap' );
          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_cart_custom_text_wrap' );
      }

      /** CHECKING CUSTOM CART ON GENERAL SETTINGS SELECT **/
      var element_dom = 'input[name=turitop_booking_system_settings_cart_custom_activate]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'unchecked', '.turitop_bs_blank_brightness_cart_custom_wrap' );
          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_cart_custom_wrap' );

          $( "body" ).on( "change", element_dom, function ( e ) {

              if ( $( this ).is( ':checked' ) ){
                $( '.turitop_bs_admin_custom_cart_wrap' ).show();
              }
              else{
                $( '.turitop_bs_admin_custom_cart_wrap' ).hide();
              }

          });

          if ( $( element_dom ).is( ':checked' ) ){
            $( '.turitop_bs_admin_custom_cart_wrap' ).show();
          }
          else{
            $( '.turitop_bs_admin_custom_cart_wrap' ).hide();
          }

      }

      /** CHECKING ROUND TRIP BOOKING **/
      var element_dom = 'input[name=turitop_booking_system_settings_round_trip_activate]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'unchecked', '.turitop_bs_blank_brightness_round_trip_data_wrap' );
          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_round_trip_data_wrap' );
      }

      /** CHECKING ADVANCED ON GENERAL SETTINGS SELECT **/
      var element_dom = 'input[name=turitop_booking_system_settings_advanced_activate]';
      if ( $( element_dom ).length ){
          this.check_turitop_selected_event( element_dom, 'unchecked', '.turitop_bs_blank_brightness_advanced_wrap' );
          this.check_turitop_selected( element_dom, 'unchecked', '.turitop_bs_blank_brightness_advanced_wrap' );
      }

      // Add Color Picker to all inputs that have 'color-field' class
      $( function() {
          $( '.turitop_booking_system_color_picker' ).wpColorPicker();
      });

    };

    var turitop_bs_admin_instance = new turitop_bs_admin();

    turitop_bs_admin_instance.init();

});
