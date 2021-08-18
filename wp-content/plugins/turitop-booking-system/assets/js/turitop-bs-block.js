( function( blocks, components, i18n, element ) {

    // License: GPLv2+

    var IconButton = components.IconButton;
    var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    ServerSideRender = wp.components.ServerSideRender,
    TextControl = wp.components.TextControl,
    SelectControl = wp.components.SelectControl;
    InspectorControls = wp.editor.InspectorControls;

    /*
    * Here's where we register the block in JavaScript.
    *
    * It's not yet possible to register a block entirely without JavaScript.
    */
    registerBlockType( 'turitop/turitop-booking-system', {
    title: 'Turitop Booking System',
    icon: 'chart-bar',
    category: 'widgets',

    /*
     * In most other blocks, you'd see an 'attributes' property being defined here.
     * We've defined attributes in the PHP, that information is automatically sent
     * to the block editor, so we don't need to redefine it here.
     */

    edit: function( props ) {

        var company = props.attributes.company;
        if ( ! company || 0 === company.length || typeof company == 'undefined' )
            company = tbs_object.tbs_data.company;
            
        var embed = props.attributes.embed;
        if ( ! embed || 0 === embed.length || typeof embed == 'undefined' )
            embed = tbs_object.tbs_data.embed;

        var button_classes = 'turitop_layout_attribute turitop_visible_attribute';
        if ( embed == 'box' || embed == 'details_and_box' )
            button_classes = 'turitop_layout_attribute turitop_hidden_attribute';

        var layout_classes = 'turitop_layout_attribute turitop_hidden_attribute';
        var content_service_classes = 'turitop_content_attribute turitop_hidden_attribute';
        if ( embed == 'details_and_button' || embed == 'details_and_box' ){
          layout_classes = 'turitop_layout_attribute turitop_visible_attribute';
          content_service_classes = 'turitop_content_attribute turitop_visible_attribute';
        }

        var layout = props.attributes.layout;
        if ( ! layout || 0 === layout.length || typeof layout == 'undefined' )
            layout = tbs_object.tbs_data.layout;

        var content_service = props.attributes.content_service;
        if ( ! content_service || 0 === content_service.length || typeof content_service == 'undefined' )
            content_service = tbs_object.tbs_data.content_service;

        var button_text = props.attributes.button_text;
        if ( ! button_text || 0 === button_text.length || typeof button_text == 'undefined' )
            button_text = tbs_object.tbs_data.button_text;

        var buttoncolor = props.attributes.buttoncolor;
        if ( ! buttoncolor || 0 === buttoncolor.length || typeof buttoncolor == 'undefined' )
            buttoncolor = 'default';

        var button_custom_class = props.attributes.button_custom_class;
        if ( ! button_custom_class || 0 === button_custom_class.length || typeof button_custom_class == 'undefined' )
            button_custom_class = tbs_object.tbs_data.button_custom_class;

        var button_image_id = props.attributes.button_image_id;
        if ( ! button_image_id || 0 === button_image_id.length || typeof button_image_id == 'undefined' )
            button_image_id = tbs_object.tbs_data.button_image_id;

        var button_image_activate = props.attributes.button_image_activate;
        if ( ! button_image_activate || 0 === button_image_activate.length || typeof button_image_activate == 'undefined' )
            button_image_activate = 'default';

        var button_image_default = props.attributes.button_image_default;
        if ( ! button_image_default || 0 === button_image_default.length || typeof button_image_default == 'undefined' )
            button_image_default = 'default';

        var button_image_url = props.attributes.button_image_url;
        if ( ! button_image_url || 0 === button_image_url.length || typeof button_image_url == 'undefined' )
            button_image_url = tbs_object.tbs_data.button_image_url;

        function onChange_ga( new_ga ) {
				      props.setAttributes( { ga: new_ga } );
        }

        function onChange_embed( new_embed ) {
				      props.setAttributes( { embed: new_embed } );
        }

        function onChange_layout( new_layout ) {
				      props.setAttributes( { layout: new_layout } );
        }

        function onChange_content_service( new_content_service ) {
				      props.setAttributes( { content_service: new_content_service } );
        }

        function onChange_buttoncolor( new_buttoncolor ) {
				      props.setAttributes( { buttoncolor: new_buttoncolor } );
        }

        function onChange_button_image_activate( new_button_image_activate ) {
				      props.setAttributes( { button_image_activate: new_button_image_activate } );
        }

        function onChange_button_image_default( new_button_image_default ) {
				      props.setAttributes( { button_image_default: new_button_image_default } );
        }

        var retval = [];

        /*
         * The ServerSideRender element uses the REST API to automatically call
         * php_block_render() in your PHP code whenever it needs to get an updated
         * view of the block.
         */

        retval.push( el( ServerSideRender, {
                    block: 'turitop/turitop-booking-system',
                    attributes: props.attributes,
                } ) );

        var embed_options = [];

        tbs_object.embed_options.forEach( function( element ) {

          embed_options.push( { value: element.value, label: element.text } );

        });

        retval.push( el( InspectorControls, {},
                    el( SelectControl, {
                        label: tbs_object.common_translations.embed,
                        value: embed,
                        class: 'turitop_embed_attribute',
                        onChange: onChange_embed,
                                options: embed_options,
                    } ),
                ) );

        retval.push( el( InspectorControls, {},
                    el( TextControl, {
                        label: tbs_object.common_translations.company,
                        value: company,
                        onChange: ( value ) => { props.setAttributes( { company: value } ); },
                    } ),
                ) );

        switch( embed ) {

          case 'gift':
          case 'redeemgv':
          case 'button':

            if ( embed != 'redeemgv' )
              retval.push( el( InspectorControls, {},
                        el( TextControl, {
                            label: tbs_object.common_translations.product_id,
                            value: props.attributes.product_id,
                            onChange: ( value ) => { props.setAttributes( { product_id: value } ); },
                        } ),
                    ) );

            if ( tbs_object.tbs_data.wc_exist == 'yes' && embed != 'redeemgv' )
                retval.push( el( InspectorControls, {},
                            el( TextControl, {
                                label: tbs_object.common_translations.wc_product_id,
                                value: props.attributes.wc_product_id,
                                onChange: ( value ) => { props.setAttributes( { wc_product_id: value } ); },
                            } ),
                        ) );

            retval.push( el( InspectorControls, {},
                          el( 'div', { class: button_classes, },
                            el( TextControl, {
                                label: tbs_object.common_translations.button_text,
                                value: button_text,
                                onChange: ( value ) => { props.setAttributes( { button_text: value } ); },
                            } ),
                            el( SelectControl, {
                                label: tbs_object.common_translations.buttoncolor,
                                value: buttoncolor,
                                onChange: onChange_buttoncolor,
                                        options: [
                                          { value: 'default', label: tbs_object.common_translations.default },
                                          { value: 'green', label: tbs_object.common_translations.green },
                                          { value: 'orange', label: tbs_object.common_translations.orange },
                                          { value: 'blue', label: tbs_object.common_translations.blue },
                                          { value: 'red', label: tbs_object.common_translations.red },
                                          { value: 'yellow', label: tbs_object.common_translations.yellow },
                                          { value: 'black', label: tbs_object.common_translations.black },
                                          { value: 'white', label: tbs_object.common_translations.white },
                                        ],
                            } ),
                            el( TextControl, {
                                label: tbs_object.common_translations.button_custom_class,
                                value: button_custom_class,
                                onChange: ( value ) => { props.setAttributes( { button_custom_class: value } ); },
                            } ),
                            el( SelectControl, {
                                label: tbs_object.common_translations.button_image_activate,
                                value: button_image_activate,
                                onChange: onChange_button_image_activate,
                                        options: [
                                          { value: 'default', label: tbs_object.common_translations.default },
                                          { value: 'yes', label: tbs_object.common_translations.yes },
                                          { value: 'no', label: tbs_object.common_translations.no },
                                        ],
                            } ),
                            el( SelectControl, {
                                label: tbs_object.common_translations.button_image_default,
                                value: button_image_default,
                                onChange: onChange_button_image_default,
                                        options: [
                                          { value: 'default', label: tbs_object.common_translations.default },
                                          { value: 'custom', label: tbs_object.common_translations.custom },
                                        ],
                            } ),
                          ),
                    ) );

              if ( button_image_default == 'custom' ){

                retval.push( el( InspectorControls, {},
                  el( TextControl, {
                                    label: tbs_object.common_translations.button_image_url,
                                    value: button_image_url,
                                    onChange: ( value ) => { props.setAttributes( { button_image_url: value } ); },
                                } ),
                      ) );

              }

            break;

          case 'box':

            retval.push( el( InspectorControls, {},
                      el( TextControl, {
                          label: tbs_object.common_translations.product_id,
                          value: props.attributes.product_id,
                          onChange: ( value ) => { props.setAttributes( { product_id: value } ); },
                      } ),
                  ) );

            if ( tbs_object.tbs_data.wc_exist == 'yes' )
                retval.push( el( InspectorControls, {},
                            el( TextControl, {
                                label: tbs_object.common_translations.wc_product_id,
                                value: props.attributes.wc_product_id,
                                onChange: ( value ) => { props.setAttributes( { wc_product_id: value } ); },
                            } ),
                        ) );

            if ( tbs_object.version_services == 'yes' ){

              retval.push( el( InspectorControls, {},
                            el( 'div', { class: layout_classes, },
                              el( SelectControl, {
                                  label: tbs_object.common_translations.layout,
                                  value: layout,
                                  onChange: onChange_layout,
                                          options: [
                                            { value: 'image_left', label: tbs_object.common_translations.image_left },
                                            { value: 'image_rigth', label: tbs_object.common_translations.image_rigth },
                                            { value: 'image_top_center', label: tbs_object.common_translations.image_top_center },
                                          ],
                              } ),
                            ),
                          ) );

              retval.push( el( InspectorControls, {},
                            el( 'div', { class: content_service_classes, },
                              el( SelectControl, {
                                  label: tbs_object.common_translations.content_service,
                                  value: content_service,
                                  onChange: onChange_content_service,
                                          options: [
                                            { value: 'whole_content', label: tbs_object.common_translations.whole_content },
                                            { value: 'summary_content', label: tbs_object.common_translations.summary_content },
                                          ],
                              } ),
                            ),
                          ) );

            }

            retval.push( el(
              'p',
              {},
              'TuriTop Box'
              ) );

          break;

          case 'round_trip':

          break;

        }

        /*
         * InspectorControls lets you add controls to the Block sidebar.
         */
    	return retval;

    },

    // We're going to be rendering in PHP, so save() can just return null.
    save: function() {
    	return null;
    },
    } );

} )(
   window.wp.blocks,
   window.wp.i18n,
   window.wp.components,
   window.wp.element,
);
