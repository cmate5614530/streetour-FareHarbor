function integralwebsite_admin_functions() {}

jQuery( document ).ready(function( $ ) {

  integralwebsite_admin_functions.prototype.init = function(){

    this.events();

  };

  integralwebsite_admin_functions.prototype.events = function(){

    var $this = this;

    /** SELECT2 **/
    this.select2();

    /** COLOR PICKER **/
    this.color_picker();

    /** CODE MIRROR **/
    this.code_mirror();

    /** RESIZE WINDOW EVENTS **/
    $( window ).resize( function () {
        $this.responsive();
    });

    /** RESPONSIVE **/
    this.responsive();

    /** BUTTON MEDIA EVENT **/
    this.button_media_event();

    /** SAVE BUTTON **/
    this.save_button();

  };

  integralwebsite_admin_functions.prototype.select2 = function(){

    if ( $( '.integralwebsite_input_select2' ).length )
      $( '.integralwebsite_input_select2' ).select2();

  };

  integralwebsite_admin_functions.prototype.color_picker = function(){

    // Add Color Picker to all inputs that have 'integralwebsite_input_color_picker' class
    $( function() {
        $( '.integralwebsite_input_color_picker' ).wpColorPicker();
    });

  };

  integralwebsite_admin_functions.prototype.code_mirror = function(){

    if( $( '.integralwebsite_functions_code_mirror_css' ).length ) {

      $( '.integralwebsite_functions_code_mirror_css' ).each( function(){

            var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            editorSettings.codemirror = _.extend(
                {},
                editorSettings.codemirror,
                {
                    indentUnit: 2,
                    tabSize: 2,
                    lineNumbers: true,
                    lineWrapping : false,
                    autoRefresh: true,
                    theme: 'paraiso-light',
                    styleActiveLine: true,
                    fixedGutter: true,
                    lint: true,
                    coverGutterNextToScrollbar: false,
                    gutters: ['CodeMirror-lint-markers'],
                    mode: 'css',
                }
            );
            var editor = wp.codeEditor.initialize( $( this ), editorSettings );

        });

     }

  };

  integralwebsite_admin_functions.prototype.responsive = function(){

    /*************** RESPONSIVE ****************/

    if ( $( '.integralwebsite_main_whole_wrap_block' ).width() > 690 ){

        /*var integralwebsite_input_wrap_width = parseInt( $( '.integralwebsite_main_whole_wrap_block' ).width() ) - parseInt( $( '.integralwebsite_title_wrap' ).width() ) - 40;

        $( '.integralwebsite_input_wrap' ).width( integralwebsite_input_wrap_width );*/
        $( '.integralwebsite_input_wrap input[type=radio]' ).closest( 'label' ).css( 'display', 'inline-block' );
        $( '.integralwebsite_input_wrap' ).css( 'margin', '0' );
        $( '.integralwebsite_input_wrap input[type=radio]' ).closest( 'label' ).css( 'margin-top', '0' );

    }
    else{
        $( '.integralwebsite_input_wrap' ).width( parseInt( $( '#integralwebsite_wcpos_add_meta_box_settings_id' ).width() ) - 10 );
        $( '.integralwebsite_input_wrap' ).css( 'margin', '10px 0 0 5px' );

        if ( $( '.integralwebsite_main_whole_wrap_block' ).width() < 540 ){

            $( '.integralwebsite_input_wrap input[type=radio]' ).closest( 'label' ).css( 'display', 'block' );
            $( '.integralwebsite_input_wrap input[type=radio]' ).closest( 'label' ).css( 'margin-top', '5px' );

        }
    }

  };

  integralwebsite_admin_functions.prototype.button_media_event = function(){

    if ( $( '.integralwebsite_functions_choose_media_system' ).length > 0 ) {

      if ( typeof wp !== 'undefined' && wp.media && wp.media.editor ) {

          $( document ).on( 'click', '.integralwebsite_functions_choose_media_system .integralwebsite_functions_choose_media_button', function( e ) {

              e.preventDefault();

              var button = $( this );
              var media_id = $( this ).closest( '.integralwebsite_functions_choose_media_system' ).find( '.integralwebsite_functions_choose_media_id' );
              var media_url = $( this ).closest( '.integralwebsite_functions_choose_media_system' ).find( '.integralwebsite_functions_choose_media_url' );
              var img_media_url = $( this ).closest( '.integralwebsite_functions_choose_media_system' ).find( '.integralwebsite_functions_choose_img_media_url' );

              wp.media.editor.send.attachment = function( props, attachment ) {

                if ( media_id.length )
                  media_id.val( attachment.id );

                if ( media_url.length )
                  media_url.val( attachment.url );

                if ( img_media_url.length )
                  img_media_url.attr( 'src', attachment.url );

              };

              wp.media.editor.open( button );

              return false;

          });

      }

    }

  };

  integralwebsite_admin_functions.prototype.save_button = function(){

    $( "body" ).on( "click", '.integralwebsite_save_button', function ( event ) {

      event.preventDefault();

      $( this ).closest( 'form' ).submit();

    });

  };

  var integralwebsite_admin_functions_instance = new integralwebsite_admin_functions();

  /** INIT **/
  integralwebsite_admin_functions_instance.init();

});
