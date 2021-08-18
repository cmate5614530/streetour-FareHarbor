jQuery( document ).ready(function( $ ) {

    function turitop_bs_ckecker() {

    }

    turitop_bs_ckecker.prototype.testing = function(){

      $( "body" ).on( "change", '.turitop_embed_attribute', function ( e ) {
          //$( this ).closest( '.components-base-control' ).hide();
      });

    };

    var turitop_bs_checker_instance = new turitop_bs_ckecker();

    //turitop_bs_checker_instance.testing();

});
