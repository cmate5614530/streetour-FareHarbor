jQuery( document ).ready(function( $ ) {

    if ( $( '.simpled_input_select2' ).length )
        $( '.simpled_input_select2' ).select2();

    /*************** RESPONSIVE ****************/

    function simpled_menu_settings_size(){

        if ( $( '.simpled_main_whole_wrap_block' ).width() > 690 ){

            /*var simpled_input_wrap_width = parseInt( $( '.simpled_main_whole_wrap_block' ).width() ) - parseInt( $( '.simpled_title_wrap' ).width() ) - 40;

            $( '.simpled_input_wrap' ).width( simpled_input_wrap_width );*/
            $( '.simpled_input_wrap input[type=radio]' ).closest( 'label' ).css( 'display', 'inline-block' );
            $( '.simpled_input_wrap' ).css( 'margin', '0' );
            $( '.simpled_input_wrap input[type=radio]' ).closest( 'label' ).css( 'margin-top', '0' );

        }
        else{
            $( '.simpled_input_wrap' ).width( parseInt( $( '#simpledevel_wcpos_add_meta_box_settings_id' ).width() ) - 10 );
            $( '.simpled_input_wrap' ).css( 'margin', '10px 0 0 5px' );

            if ( $( '.simpled_main_whole_wrap_block' ).width() < 540 ){

                $( '.simpled_input_wrap input[type=radio]' ).closest( 'label' ).css( 'display', 'block' );
                $( '.simpled_input_wrap input[type=radio]' ).closest( 'label' ).css( 'margin-top', '5px' );

            }
        }

    }

    $( window ).resize( function () {
        simpled_menu_settings_size();
    });

    simpled_menu_settings_size();

});
