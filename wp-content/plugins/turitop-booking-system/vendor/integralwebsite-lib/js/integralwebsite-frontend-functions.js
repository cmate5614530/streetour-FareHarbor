function integralwebsite_frontend_functions() {}

jQuery( document ).ready(function( $ ) {

  integralwebsite_frontend_functions.prototype.init = function(){


  };

  integralwebsite_frontend_functions.prototype.events = function(){


  };

  integralwebsite_frontend_functions.prototype.responsive_font_size = function( args ){

    if ( ! args.maximum_size || 0 === args.maximum_size || typeof args.maximum_size == 'undefined' )
      var font_size = lh;
    else
      var font_size = args.maximum_size;

    args.text_element.css( 'font-size',  font_size + 'px' );

    if ( ! args.line_height || 0 === args.line_height || typeof args.line_height == 'undefined' )
      args.text_element.css( 'line-height', font_size + 'px' );
    else
      args.text_element.css( 'line-height', args.line_height + 'px' );

    var h = args.text_element.height();
    console.log( 'responsive_font_size height ->'  + h );

    var lh = parseInt( args.text_element.css( 'line-height' ) );
    console.log( 'responsive_font_size line-height ->'  + lh );

    while ( h >= 2 * lh ){
      /*console.log( '' );
      console.log( 'LOOOOOOOP' );
      console.log( '' );

      console.log( 'Adjust font_size -> ' + font_size );*/

      font_size = font_size - 1;
      args.text_element.css( 'font-size',  font_size + 'px' );

      if ( ! args.line_height || 0 === args.line_height || typeof args.line_height == 'undefined' )
        args.text_element.css( 'line-height', font_size + 'px' );
      else
        args.text_element.css( 'line-height', args.line_height + 'px' );

      h = args.text_element.height();
      lh = parseInt( args.text_element.css( 'line-height' ) );

      /*console.log( 'then height -> ' + h );
      console.log( 'then line-height -> ' + lh );

      console.log( '' );
      console.log( 'LOOOOOOOP END' );
      console.log( '' );*/

    }

  };

  var integralwebsite_frontend_functions_instance = new integralwebsite_frontend_functions();

  /** INIT **/
  integralwebsite_frontend_functions_instance.init();

});
