<?php
/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

 /**
  *
  *
  * @class      simpledevel_functions
  * @package    Simpledevel
  * @since      Version 1.0.0
  * @author     Daniel Sanchez Saez
  *
  */

 if ( ! class_exists( 'simpledevel_functions' ) ) {
   /**
    *
    * @author Daniel Sanchez Saez
    */
   class simpledevel_functions {

     /**
      * version
      *
      * @var version
      * @since  1.0.0
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @access public
      */
     public $version = '1.0.0';

     /**
      * Functions Instance
      *
      * @var _instance
      * @since  1.0.0
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @access protected
      */
     protected static $_instance = null;

     /**
      * slug
      *
      * @var slug
      * @since  1.0.0
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @access protected
      */
     public $slug = '';

     /**
      * amp_enable
      *
      * @var amp_enable
      * @since  1.0.0
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @access protected
      */
     public $amp_enable = 'no_loaded';

     /**
      * strings tranalatables
      *
      * @var array
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      */
     public $common_translations = array();

     /**
 		 * args
 		 *
 		 * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
 		 */
 		 public $args = array();

     /**
      * __construct
      *
      * @since 1.0.0
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @access public
      */
     public function __construct( $args = array() ) {

         $this->args = $args;

         $this->slug = ( isset( $args[ 'slug' ] ) ? $args[ 'slug' ] : '' );

     }

     /**
      * Functinos Instance
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return simpledevel_functions
      *
      */
     public static function instance( $args ) {
         if ( is_null( self::$_instance ) ) {
             self::$_instance = new self( $args );
         }

         return self::$_instance;
     }

     /**
      * init
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return void
      *
      */
     public function init() {}

       /**
        * is_amp_enable
        *
        * @author Daniel Sanchez Saez <dssaez@gmail.com>
        * @since 1.0.0
        * @access public
        * @param
        * @return void
        *
        */
       public function is_amp_enable( $args = array() ) {

         if ( $this->amp_enable == 'no_loaded' ){

           if ( ! function_exists( 'amp_init' ) )
              return 'no';

           if ( ! isset( $args[ 'post_id' ] ) ){
             global $post;
             $post_id = ( isset( $post->ID ) ? $post->ID : 0 );
           }
           else
              $post_id = $args[ 'post_id' ];

           $amp_status = get_post_meta( $post_id, 'amp_status', true );
           $amp_options = get_option( 'amp-options', true );

           $this->amp_enable = 'no';
           if ( isset( $amp_options[ 'theme_support' ] ) && $amp_options[ 'theme_support' ] == 'standard' && $amp_status != 'disabled' ){

             $this->amp_enable = 'yes';

           }

         }

         return $this->amp_enable;

       }

       /**
        * enqueue_font_awesome
        *
        * @author Daniel Sanchez Saez <dssaez@gmail.com>
        * @since 1.0.0
        * @access public
        * @param
        * @return void
        *
        */
       public function enqueue_font_awesome() {

         $min = ( defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min' );

         wp_register_style( 'simpledevel_functions_font_awesome', apply_filters( 'simpledevel_functions_font_awesome_css_filter', $this->args[ 'vendor_url' ] . '/css/font-awesome' . $min . '.css' ), array(), $this->args[ 'version' ] );
         wp_enqueue_style( 'simpledevel_functions_font_awesome' );

       }

     /**
      * include_inputs_form
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return void
      *
      */
     public function inputs_form( $input_args ) {

       include_once $this->args[ 'path' ] . '/class.simpledevel-wp-inputs-form.php';
       return new simpledevel_wp_inputs_form ( apply_filters( 'simpledevel_inputs_args_filter', $input_args ) );

     }

     /**
      * get args
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return void
      *
      */
     public function translate_id( $translate_args ) {

       $id = ( isset( $translate_args[ 'id' ] ) && is_numeric( $translate_args[ 'id' ] ) ? $translate_args[ 'id' ] : 0 );

       if ( has_filter( 'wpml_object_id' ) ){

         $filter_id = ( isset( $translate_args[ 'filter_id' ] ) ? $translate_args[ 'filter_id' ] : '' );
         $element_type = ( isset( $translate_args[ 'element_type' ] ) ? $translate_args[ 'element_type' ] : '' );
         $return_original = ( isset( $translate_args[ 'return_original' ] ) ? $translate_args[ 'return_original' ] : true );
         $lang_code = ( isset( $translate_args[ 'lang_code' ] ) ? $translate_args[ 'lang_code' ] : '' );

         return apply_filters( $filter_id, $id, $element_type, $return_original, $lang_code );

       }

       return $id;

     }

     /**
      *
      * simpled_wordpress_features_get_pages
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function get_pages( $args = null ) {

       $args = ( empty( $args ) ? array() : $args );
       $type = ( isset( $args[ 'type' ] ) ? $args[ 'type' ] : 'id' );
       $category = ( isset( $args[ 'category' ] ) ? $args[ 'category' ] : false );

       if ( $type == 'id' ){

         $pages = get_pages( array( 'post_status' => array( 'publish', 'draft', 'private' ) ) );
         $select_pages = array(
           array(
             'text'  =>$this->get_common_translations()[ 'choose_option' ],
             'value' => '0',
           ),
         );
         foreach ( $pages as $page ) {
           $select_pages[] = array(
             'text'  => $page->post_title,
             'value' => $page->ID,
           );
         }
         $select_pages[] = array(
           'text'  =>$this->get_common_translations()[ 'custom_url' ],
           'value' => 'custom',
         );
         $this->pages = ( is_array( $select_pages ) ? $select_pages : array() );

         if ( $category && function_exists( 'WC' ) ){

           $orderby = 'name';
           $order = 'asc';
           $hide_empty = false ;
           $cat_args = array(
               'orderby'    => $orderby,
               'order'      => $order,
               'hide_empty' => $hide_empty,
           );
           $product_categories = get_terms( 'product_cat', $cat_args );

           foreach ( $product_categories as $cat ) {

             $this->pages[] = array(
               'text'  => 'Cat - ' . $cat->name,
               'value' => 'Cat-' . $cat->term_id,
             );

           }

         }

         return $this->pages;

       }

       if ( $type == 'url' ){

         $pages = get_pages( array( 'post_status' => array( 'publish', 'draft', 'private' ) ) );
         $select_pages = array(
           array(
             'text'  =>$this->get_common_translations()[ 'choose_option' ],
             'value' => '',
           ),
         );
         foreach ( $pages as $page ) {
           $select_pages[] = array(
             'text'  => $page->post_title,
             'value' => $page->guid,
           );
         }
         $select_pages[] = array(
           'text'  =>$this->get_common_translations()[ 'custom_url' ],
           'value' => 'custom',
         );
         $this->pages_url = ( is_array( $select_pages ) ? $select_pages : array() );

         if ( $category && function_exists( 'WC' ) ){

           $orderby = 'name';
           $order = 'asc';
           $hide_empty = false ;
           $cat_args = array(
               'orderby'    => $orderby,
               'order'      => $order,
               'hide_empty' => $hide_empty,
           );
           $product_categories = get_terms( 'product_cat', $cat_args );

           foreach ( $product_categories as $cat ) {

             $this->pages_url[] = array(
               'text'  => 'Cat - ' . $cat->name,
               'value' => get_term_link( $cat->term_id, 'product_cat' ),
             );

           }

         }

         return $this->pages_url;

       }

     }

     /**
      * generate_image_inputs
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return string
      *
      */
     public function generate_image_inputs( $args ) {

       $base_name = ( isset( $args[ 'base_name' ] ) ? $args[ 'base_name' ] : '' );
       $num = ( isset( $args[ 'num' ] ) ? $args[ 'num' ] : 1 );
       $pages = ( isset( $args[ 'pages' ] ) ? $args[ 'pages' ] : array() );

       $image_inputs = array();

       for ( $i=1; $i <= $num ; $i++ ) {

         $image_inputs[ $base_name . "[id_$i]" ] = array(
           'input_type'  => 'hidden',
           'input_class' => 'simpledevel_functions_choose_media_id',
         );

         $image_inputs[ $base_name . "[url_$i]" ] = array(
           'input_type'  => 'hidden',
           'input_class' => 'simpledevel_functions_choose_media_url',
         );

         $image_inputs[ $base_name . "[page_$i]" ] = array(
           'input_type' => 'select',
           'default'     => 'choose_option',
           'input_class' => 'simpled_input_select simpled_input_select2',
           'options'     => $pages,
           'title'       => _x( 'Choose page', 'simpledevel functions images', $this->slug ),
           'description' => _x( "Select a page to link the image", 'module footer settings', $this->slug ),
         );

         $image_inputs[ $base_name . "[custom_url_$i]" ] = array(
           'input_type'  => 'text',
           'title'       => _x( 'Custom URL', 'simpledevel functions images', $this->slug ),
           'description' => _x( "Introdue a custom URL", 'module footer settings', $this->slug ),
         );

       }

       return $image_inputs;

     }

     /**
      * display_images
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return string
      *
      */
     public function display_images( $args, $inputs_instance, $data ) {

       $title = ( isset( $args[ 'title' ] ) ? $args[ 'title' ] : '' );
       $base_name = ( isset( $args[ 'base_name' ] ) ? $args[ 'base_name' ] : '' );
       $button = ( isset( $args[ 'button' ] ) ? $args[ 'button' ] : '' );
       $num = ( isset( $args[ 'num' ] ) ? $args[ 'num' ] : 1 );

       $images_data = ( isset( $data[ $base_name ] ) && is_array( $data[ $base_name ] ) ? $data[ $base_name ] : array() );

       for ( $i=1; $i <= $num ; $i++ ) {

         ?>

         <h2 style="font-weight: bold; text-decoration: underline;"><?php echo $title . " " . $i; ?></h2>

         <div class='simpled_main_sub_wrap'>

           <div class="simpledevel_functions_choose_media_system">

             <?php

             $args_to_display = array(
               $base_name . "[id_$i]",
               $base_name . "[url_$i]",
               $button,
             );

             $inputs_instance->display_inputs( $args_to_display );

             $image_id = ( isset( $images_data[ $i ][ "id" ] ) ? $images_data[ $i ][ "id" ] : 0 );

             $src = wp_get_attachment_image_src( $image_id, 'thumbnail' );

             ?>

             <img src="<?php echo $src[ 0 ]; ?>" class="simpledevel_functions_choose_img_media_url" />

           </div>

           <?php

           $args_to_display = array(
             $base_name . "[page_$i]",
           );

           $inputs_instance->display_inputs( $args_to_display );

           echo "<div style='position: relative;'>";

               $args_to_display = array(
                   $base_name . "[custom_url_$i]",
               );

               $inputs_instance->display_inputs( $args_to_display );

               echo "<div class='simpledevel_functions_blank_brightness simpledevel_functions_blank_brightness_slider_custom_url_wrap'></div>";

           echo "</div>";

         echo "</div>";

       }

     }

     /**
      * save_images
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return string
      *
      */
     public function save_images( $args, $data ) {

       $base_name = ( isset( $args[ 'base_name' ] ) ? $args[ 'base_name' ] : '' );
       $num = ( isset( $args[ 'num' ] ) ? $args[ 'num' ] : 1 );
       $slug = ( isset( $args[ 'slug' ] ) ? $args[ 'slug' ] : '' );

       $images_data = ( isset( $_POST[ $slug . '_' . $base_name ] ) && is_array( $_POST[ $slug . '_' . $base_name ] ) ? $_POST[ $slug . '_' . $base_name ] : array() );

       $data[ $base_name ] = array();
       for ( $i=1; $i <= $num ; $i++ ) {

         $data[ $base_name ][ $i ] = array(
           'id' => ( isset( $images_data[ "id_$i" ] ) ? $images_data[ "id_$i" ] : 0 ),
           'url' => ( isset( $images_data[ "url_$i" ] ) ? $images_data[ "url_$i" ] : '' ),
           'page' => ( isset( $images_data[ "page_$i" ] ) ? $images_data[ "page_$i" ] : 0 ),
           'custom_url' => ( isset( $images_data[ "custom_url_$i" ] ) ? $images_data[ "custom_url_$i" ] : '' ),
         );

         $data[ $base_name . "[id_$i]" ] = ( isset( $images_data[ "id_$i" ] ) ? $images_data[ "id_$i" ] : 0 );
         $data[ $base_name . "[url_$i]" ] = ( isset( $images_data[ "url_$i" ] ) ? $images_data[ "url_$i" ] : '' );
         $data[ $base_name . "[page_$i]" ] = ( isset( $images_data[ "page_$i" ] ) ? $images_data[ "page_$i" ] : 0 );
         $data[ $base_name . "[custom_url_$i]" ] = ( isset( $images_data[ "custom_url_$i" ] ) ? $images_data[ "custom_url_$i" ] : '' );

       }

       return $data;

     }

     /**
      * generate_css
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return string
      *
      */
     public function generate_css( $args, $data ) {

         $dynamic_css = '';
         foreach ( $args as $key => $value ) {
             if ( isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ){
                 if ( $value == 'border-radius: ' )
                     $dynamic_css .= $value . ( $data[ $key ] == 'radio' ? '5px' : '0' ) . ";" . PHP_EOL;
                 else
                     $dynamic_css .= $value . stripslashes_deep( $data[ $key ] ) . ";" . PHP_EOL;
             }

         }

         return $dynamic_css;

     }

     /**
      * generate_dynamic_css
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since 1.0.0
      * @access public
      * @param
      * @return void
      *
      */
     public function generate_dynamic_css( $elements, $data ) {

       $dynamic_css = "";

       foreach ( $elements as $element ) {

         $args = apply_filters( 'simpledelvel_generate_css_args_filter', array(
           $element[ 'prefix' ] . '_background_color' => 'background: ',
           $element[ 'prefix' ] . '_border_color' => 'border: 1px solid ',
           $element[ 'prefix' ] . '_font_color' => 'color: ',
           $element[ 'prefix' ] . '_font_size' => 'font-size: ',
           $element[ 'prefix' ] . '_radio_square' => 'border-radius: ',
           $element[ 'prefix' ] . '_font_weight' => 'font-weight: ',
           $element[ 'prefix' ] . '_font_family' => 'font-family: ',
           $element[ 'prefix' ] . '_line_height' => 'line-height: ',
           $element[ 'prefix' ] . '_letter_spacing' => 'letter-spacing: ',
           $element[ 'prefix' ] . '_width' => 'width: ',
           $element[ 'prefix' ] . '_min_width' => 'min-width: ',
           $element[ 'prefix' ] . '_max_width' => 'max-width: ',
           $element[ 'prefix' ] . '_height' => 'height: ',
           $element[ 'prefix' ] . '_min_height' => 'min-height: ',
           $element[ 'prefix' ] . '_max_height' => 'max-height: ',
         ), $element );

         $dynamic_css_rules = $this->generate_css( $args, $data );

         $dynamic_css_rules = ( isset( $data[ $element[ 'prefix' ] . '_custom_css' ] ) && ! empty( $data[ $element[ 'prefix' ] . '_custom_css' ] ) ? $dynamic_css_rules . $data[ $element[ 'prefix' ] . '_custom_css' ] : $dynamic_css_rules );

         if ( ! empty( $dynamic_css_rules ) ){

           $dynamic_css .= $element[ 'css_tag' ] . '{' . PHP_EOL;
           $dynamic_css .= $dynamic_css_rules;
           $dynamic_css .= '}' . PHP_EOL;

         }

         if ( isset( $element[ 'hover' ] ) && $element[ 'hover' ] == 'yes' ){

           $args = apply_filters( 'simpledelvel_generate_css_hover_args_filter', array(
             $element[ 'prefix' ] . '_background_color_hover' => 'background: ',
             $element[ 'prefix' ] . '_border_color_hover' => 'border: 1px solid ',
             $element[ 'prefix' ] . '_font_color_hover' => 'color: ',
             $element[ 'prefix' ] . '_font_size_hover' => 'font-size: ',
             $element[ 'prefix' ] . '_radio_square_hover' => 'border-radius: ',
             $element[ 'prefix' ] . '_font_weight_hover' => 'font-weight: ',
             $element[ 'prefix' ] . '_font_family_hover' => 'font-family: ',
             $element[ 'prefix' ] . '_line_height_hover' => 'line-height: ',
             $element[ 'prefix' ] . '_letter_spacing_hover' => 'letter-spacing: ',
             $element[ 'prefix' ] . '_width_hover' => 'width: ',
             $element[ 'prefix' ] . '_min_width_hover' => 'min-width: ',
             $element[ 'prefix' ] . '_max_width_hover' => 'max-width: ',
             $element[ 'prefix' ] . '_height_hover' => 'height: ',
             $element[ 'prefix' ] . '_min_height_hover' => 'min-height: ',
             $element[ 'prefix' ] . '_max_height_hover' => 'max-height: ',
           ), $element );

           $dynamic_css_rules = $this->generate_css( $args, $data );

           $dynamic_css_rules = ( isset( $data[ $element[ 'prefix' ] . '_custom_css_hover' ] ) && ! empty( $data[ $element[ 'prefix' ] . '_custom_css_hover' ] ) ? $dynamic_css_rules . $data[ $element[ 'prefix' ] . '_custom_css_hover' ] : $dynamic_css_rules );

           if ( ! empty( $dynamic_css_rules ) ){

             $css_hover = ( isset( $element[ 'css_hover' ] ) && ! empty( $element[ 'css_hover' ] ) ? $element[ 'css_hover' ] : '' );
             $dynamic_css .= $element[ 'css_tag' ] . ':hover ' . $css_hover . '{' . PHP_EOL;
             $dynamic_css .= $dynamic_css_rules;
             $dynamic_css .= '}' . PHP_EOL;

           }

         }

       }

       return $dynamic_css;

     }

     /**
      *
      * get_options_weight
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function get_options_radio() {

       return array(
           array(
               'value' => '',
               'text' => _x( '-- Choose an option --', 'simpledevel common translations', $this->slug ),
           ),
           array(
               'value' => 'square',
               'text' => 'square',
           ),
           array(
               'value' => 'radio',
               'text' => 'radio',
           ),
         );

      }


     /**
      *
      * get_options_weight
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function get_options_weight() {

       return array(
           array(
               'value' => '',
               'text' => _x( '-- Choose an option --', 'simpledevel common translations', $this->slug ),
           ),
           array(
               'value' => 'normal',
               'text' => 'normal',
           ),
           array(
               'value' => 'bold',
               'text' => 'bold',
           ),
           array(
               'value' => 'bolder',
               'text' => 'bolder',
           ),
           array(
               'value' => 'lighter',
               'text' => 'lighter',
           ),
           array(
               'value' => '100',
               'text' => '100',
           ),
           array(
               'value' => '200',
               'text' => '200',
           ),
           array(
               'value' => '300',
               'text' => '300',
           ),
           array(
               'value' => '400',
               'text' => '400',
           ),
           array(
               'value' => '500',
               'text' => '500',
           ),
           array(
               'value' => '600',
               'text' => '600',
           ),
           array(
               'value' => '700',
               'text' => '700',
           ),
           array(
               'value' => '800',
               'text' => '800',
           ),
           array(
               'value' => '900',
               'text' => '900',
           )
       );

     }

     /**
      *
      * generate_css_inputs
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function generate_css_inputs( $args = array() ) {

       $desc_or_tooltip = ( isset( $args[ 'tooltip' ] ) && $args[ 'tooltip' ] == 'yes' ? 'tooltip' : 'description' );

       $css_inputs = array(
         $args[ 'prefix' ] . '_font_weight' => array(
           'input_type' => 'select',
           'input_class' => 'simpled_input_select simpled_input_select2',
           'options'   => $this->get_options_weight(),
           'title' => ( isset( $args[ 'font_weight' ] ) ? $args[ 'font_weight' ] : $this->get_common_translations()[ 'font_weight' ] ),
           $desc_or_tooltip => ( isset( $args[ 'font_weight_desc' ] ) ? $args[ 'font_weight_desc' ] : $this->get_common_translations()[ 'font_weight_desc' ] ),
         ),
         $args[ 'prefix' ] . '_radio_square' => array(
           'input_type' => 'select',
           'input_class' => 'simpled_input_select simpled_input_select2',
           'options'   => $this->get_options_radio(),
           'title' => ( isset( $args[ 'radio_square' ] ) ? $args[ 'radio_square' ] : $this->get_common_translations()[ 'radio_square' ] ),
           $desc_or_tooltip => ( isset( $args[ 'radio_square_desc' ] ) ? $args[ 'radio_square_desc' ] : $this->get_common_translations()[ 'radio_square_desc' ] ),
         ),
         $args[ 'prefix' ] . '_custom_css' => array(
           'input_type' => 'textarea',
           'input_class' => 'simpledevel_textarea_custom_css',
           'title' => ( isset( $args[ 'custom_css' ] ) ? $args[ 'custom_css' ] : $this->get_common_translations()[ 'custom_css' ] ),
           $desc_or_tooltip => ( isset( $args[ 'custom_css_desc' ] ) ? $args[ 'custom_css_desc' ] : $this->get_common_translations()[ 'custom_css_desc' ] ),
         ),
       );

       $items = array( 'background_color', 'border_color', 'font_color' );
       foreach ( $items as $item ) {

         $css_inputs[ $args[ 'prefix' ] . '_' . $item ] = array(
           'input_class' => 'simpledevel_input_color_picker',
           'input_type' => 'text_alpha_color',
           'title' => ( isset( $args[ $item ] ) ? $args[ $item ] : $this->get_common_translations()[ $item ] ),
           $desc_or_tooltip => ( isset( $args[ $item . '_desc' ] ) ? $args[ $item . '_desc' ] : $this->get_common_translations()[ $item . '_desc' ] ),
         );

       }

       $items = array( 'font_size', 'font_family', 'google_font', 'line_height', 'letter_spacing', 'width', 'min_width', 'max_width', 'height', 'min_height', 'max_height' );
       foreach ( $items as $item ) {

         $css_inputs[ $args[ 'prefix' ] . '_' . $item ] =array(
           'input_type' => 'text',
           'title' => ( isset( $args[ $item ] ) ? $args[ $item ] : $this->get_common_translations()[ $item ] ),
           $desc_or_tooltip => ( isset( $args[ $item . '_desc' ] ) ? $args[ $item . '_desc' ] : $this->get_common_translations()[ $item . '_desc' ] ),
         );

       }

       return apply_filters( 'simpled_functions_generate_css_inputs_filter', $css_inputs, $args );

     }

     /**
      *
      * generate_css_inputs_hover
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function generate_css_inputs_hover( $args = array() ) {

       $desc_or_tooltip = ( isset( $args[ 'tooltip' ] ) && $args[ 'tooltip' ] == 'yes' ? 'tooltip' : 'description' );

       $css_inputs_hover = array(
         $args[ 'prefix' ] . '_font_weight_hover' => array(
           'input_type' => 'select',
           'input_class' => 'simpled_input_select simpled_input_select2',
           'options'   => $this->get_options_weight(),
           'title' => ( isset( $args[ 'font_weight_hover' ] ) ? $args[ 'font_weight_hover' ] : $this->get_common_translations()[ 'font_weight_hover' ] ),
           $desc_or_tooltip => ( isset( $args[ 'font_weight_hover_desc' ] ) ? $args[ 'font_weight_hover_desc' ] : $this->get_common_translations()[ 'font_weight_hover_desc' ] ),
         ),
         $args[ 'prefix' ] . '_radio_square_hover' => array(
             'input_type' => 'select',
             'input_class' => 'simpled_input_select simpled_input_select2',
             'options'   => $this->get_options_radio(),
             'title' => ( isset( $args[ 'radio_square_hover' ] ) ? $args[ 'radio_square_hover' ] : $this->get_common_translations()[ 'radio_square_hover' ] ),
             $desc_or_tooltip => ( isset( $args[ 'radio_square_hover_desc' ] ) ? $args[ 'radio_square_hover_desc' ] : $this->get_common_translations()[ 'radio_square_hover_desc' ] ),
         ),
         $args[ 'prefix' ] . '_custom_css_hover' => array(
             'input_type' => 'textarea',
             'input_class' => 'simpledevel_textarea_custom_css',
             'title' => ( isset( $args[ 'custom_css_hover' ] ) ? $args[ 'custom_css_hover' ] : $this->get_common_translations()[ 'custom_css_hover' ] ),
             $desc_or_tooltip => ( isset( $args[ 'custom_css_hover_desc' ] ) ? $args[ 'custom_css_hover_desc' ] : $this->get_common_translations()[ 'custom_css_hover_desc' ] ),
         ),
       );

       $items = array( 'background_color_hover', 'border_color_hover', 'font_color_hover' );
       foreach ( $items as $item ) {

         $css_inputs_hover[ $args[ 'prefix' ] . '_' . $item ] =array(
           'input_class' => 'simpledevel_input_color_picker',
           'input_type' => 'text_alpha_color',
           'title' => ( isset( $args[ $item ] ) ? $args[ $item ] : $this->get_common_translations()[ $item ] ),
           $desc_or_tooltip => ( isset( $args[ $item . '_desc' ] ) ? $args[ $item . '_desc' ] : $this->get_common_translations()[ $item . '_desc' ] ),
         );

       }

       $items = array( 'font_size_hover', 'font_family_hover', 'google_font_hover', 'line_height_hover', 'letter_spacing_hover', 'width_hover', 'min_width_hover', 'max_width_hover', 'height_hover', 'min_height_hover', 'max_height_hover' );
       foreach ( $items as $item ) {

         $css_inputs_hover[ $args[ 'prefix' ] . '_' . $item ] =array(
           'input_type' => 'text',
           'title' => ( isset( $args[ $item ] ) ? $args[ $item ] : $this->get_common_translations()[ $item ] ),
           $desc_or_tooltip => ( isset( $args[ $item . '_desc' ] ) ? $args[ $item . '_desc' ] : $this->get_common_translations()[ $item . '_desc' ] ),
         );

       }

       return apply_filters( 'simpled_functions_generate_css_inputs_hover_filter', $css_inputs_hover, $args );

     }

     /**
      *
      * display_css_inputs
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function display_css_inputs( $inputs_instance, $args ) {

       do_action( 'simpledevel_functions_display_css_inputs_before' );

       $args_to_display = array(
         $args[ 'prefix' ] . '_background_color',
         $args[ 'prefix' ] . '_border_color',
         $args[ 'prefix' ] . '_radio_square',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_font_color',
           $args[ 'prefix' ] . '_font_size',
           $args[ 'prefix' ] . '_font_weight',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_letter_spacing',
           $args[ 'prefix' ] . '_line_height',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_font_family',
           $args[ 'prefix' ] . '_google_font',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
         $args[ 'prefix' ] . '_width',
         $args[ 'prefix' ] . '_min_width',
         $args[ 'prefix' ] . '_max_width',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
         $args[ 'prefix' ] . '_height',
         $args[ 'prefix' ] . '_min_height',
         $args[ 'prefix' ] . '_max_height',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_custom_css',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       do_action( 'simpledevel_functions_display_css_inputs_after' );

     }

     /**
      *
      * display_css_inputs
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function display_css_inputs_hover( $inputs_instance, $args ) {

       do_action( 'simpledevel_functions_display_css_inputs_hover_before' );

       $args_to_display = array(
         $args[ 'prefix' ] . '_background_color_hover',
         $args[ 'prefix' ] . '_border_color_hover',
         $args[ 'prefix' ] . '_radio_square_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_font_color_hover',
           $args[ 'prefix' ] . '_font_size_hover',
           $args[ 'prefix' ] . '_font_weight_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_letter_spacing_hover',
           $args[ 'prefix' ] . '_line_height_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_font_family_hover',
           $args[ 'prefix' ] . '_google_font_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
         $args[ 'prefix' ] . '_width_hover',
         $args[ 'prefix' ] . '_min_width_hover',
         $args[ 'prefix' ] . '_max_width_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
         $args[ 'prefix' ] . '_height_hover',
         $args[ 'prefix' ] . '_min_height_hover',
         $args[ 'prefix' ] . '_max_height_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       $args_to_display = array(
           $args[ 'prefix' ] . '_custom_css_hover',
       );
       $classes = array(
           'simpled_main_line_wrap_inline',
           'simpled_input_wrap_block',
       );

       $inputs_instance->display_inputs( $args_to_display, $classes );
       echo "<div style='clear: both;'></div>";

       do_action( 'simpledevel_functions_display_css_inputs_hover_after' );

     }

     /**
      *
      * get_common_translations
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function get_common_translations() {

       if ( empty( $this->common_translation ) )
         $this->set_common_translations();

       return $this->common_translation;

     }

     /**
      *
      * set_common_translations
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @access public
      * @param
      * @return array
      *
      */
     public function set_common_translations() {

        $this->common_translation = array(

          // COMMON
          'yes' => _x( 'yes', 'common translations', $this->slug ),
          'no' => _x( 'no', 'common translations', $this->slug ),
          'choose_option' => _x( '-- Choose an option --', 'common translations', $this->slug ),
          'no_option' => _x( '-- No option chosen --', 'common translations', $this->slug ),
          'description' => _x( 'Description', 'common translations', $this->slug ),
          'description_desc' => _x( 'Introduce a description', 'common translations', $this->slug ),
          'custom_url' => _x( 'Custom URL', 'common translations', $this->slug ),
          'custom_url_desc' => _x( 'Introduce a custom URL', 'common translations', $this->slug ),
          'page' => _x( 'Choose page', 'common translations', $this->slug ),
          'page_desc' => _x( 'Select a page', 'common translations', $this->slug ),
          'menu' => _x( 'Menu', 'common translations', $this->slug ),
          'choose_menu' => _x( '-- Choose_menu --', 'common translations', $this->slug ),
          'save_button' => _x( 'Save', 'common translations', $this->slug ),
          'custom_class' => _x( 'Custom classes', 'common translations', $this->slug ),
          'custom_class_desc' => _x( 'Introduce custom classes', 'common translations', $this->slug ),
          'custom_css' => _x( 'Custom css', 'common translations', $this->slug ),
          'custom_css_desc' => _x( 'Introduce custom css', 'common translations', $this->slug ),

           'background_color' => _x( 'Background color', 'simpledevel common translations', $this->slug ),
           'background_color_desc' => _x( 'Introduce the background color', 'simpledevel common translations', $this->slug ),
           'border_color' => _x( 'Border color', 'simpledevel common translations', $this->slug ),
           'border_color_desc' => _x( 'Introduce the border color', 'simpledevel common translations', $this->slug ),
           'radio_square' => _x( 'Border radio', 'simpledevel common translations', $this->slug ),
           'radio_square_desc' => _x( 'Choose how you want to display the corners', 'simpledevel common translations', $this->slug ),

           'font_color' => _x( 'Font color', 'simpledevel common translations', $this->slug ),
           'font_color_desc' => _x( 'Introduce the font color', 'simpledevel common translations', $this->slug ),
           'font_size' => _x( 'Font size', 'simpledevel common translations', $this->slug ),
           'font_size_desc' => _x( 'Introduce the font size ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'font_weight' => _x( 'Font weight', 'simpledevel common translations', $this->slug ),
           'font_weight_desc' => _x( 'Select the font weight', 'simpledevel common translations', $this->slug ),

           'font_family' => _x( 'Font family', 'simpledevel common translations', $this->slug ),
           'font_family_desc' => _x( "Introduce the font family ( example: Times, 'Times New Roman', serif - or - 'Roboto', sans-serif )", 'simpledevel common translations', $this->slug ),
           'google_font' => _x( 'Google font', 'simpledevel common translations', $this->slug ),
           'google_font_desc' => _x( 'Introduce the google font ( examples: Roboto, Montserrat, Alegreya, playfair, Roboto+Condensed or Alegreya:700 ). This google font should also be introduced on the font family in order to work', 'simpledevel common translations', $this->slug ),

           'line_height' => _x( 'Line height', 'simpledevel common translations', $this->slug ),
           'line_height_desc' => _x( 'Introduce the line height of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'letter_spacing' => _x( 'Leter spacing', 'simpledevel common translations', $this->slug ),
           'letter_spacing_desc' => _x( 'Introduce the leter spacing of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),

           'width' => _x( 'Width', 'simpledevel common translations', $this->slug ),
           'width_desc' => _x( 'Introduce the width ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'min_width' => _x( 'Min width', 'simpledevel common translations', $this->slug ),
           'min_width_desc' => _x( 'Introduce the minimum width of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'max_width' => _x( 'Max width', 'simpledevel common translations', $this->slug ),
           'max_width_desc' => _x( 'Introduce the max width of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),

           'height' => _x( 'Height', 'simpledevel common translations', $this->slug ),
           'height_desc' => _x( 'Introduce the height( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'min_height' => _x( 'Min height', 'simpledevel common translations', $this->slug ),
           'min_height_desc' => _x( 'Introduce the minimum height( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'max_height' => _x( 'Max height', 'simpledevel common translations', $this->slug ),
           'max_height_desc' => _x( 'Introduce the maximum height( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),

           'custom_css' => _x( 'Custom css', 'simpledevel common translations', $this->slug ),
           'custom_css_desc' => _x( 'Introduce custom css', 'simpledevel common translations', $this->slug ),

           // HOVER

           'background_color_hover' => _x( 'Background color hover', 'simpledevel common translations', $this->slug ),
           'background_color_hover_desc' => _x( 'Introduce the background color', 'simpledevel common translations', $this->slug ),
           'border_color_hover' => _x( 'Border color hover', 'simpledevel common translations', $this->slug ),
           'border_color_hover_desc' => _x( 'Introduce the border color', 'simpledevel common translations', $this->slug ),
           'radio_square_hover' => _x( 'Border radio hover', 'simpledevel common translations', $this->slug ),
           'radio_square_hover_desc' => _x( 'Choose how you want to display the corners', 'simpledevel common translations', $this->slug ),

           'font_color_hover' => _x( 'Font color hover', 'simpledevel common translations', $this->slug ),
           'font_color_hover_desc' => _x( 'Introduce the font color', 'simpledevel common translations', $this->slug ),
           'font_size_hover' => _x( 'Font size hover', 'simpledevel common translations', $this->slug ),
           'font_size_hover_desc' => _x( 'Introduce the font size ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'font_weight_hover' => _x( 'Font weight hover', 'simpledevel common translations', $this->slug ),
           'font_weight_hover_desc' => _x( 'Select the font weight', 'simpledevel common translations', $this->slug ),

           'font_family_hover' => _x( 'Font family hover', 'simpledevel common translations', $this->slug ),
           'font_family_hover_desc' => _x( "Introduce the font family ( example: Times, 'Times New Roman', serif - or - 'Roboto', sans-serif )", 'simpledevel common translations', $this->slug ),
           'google_font_hover' => _x( 'Google font hover', 'simpledevel common translations', $this->slug ),
           'google_font_hover_desc' => _x( 'Introduce the google font ( examples: Roboto, Montserrat, Alegreya, playfair, Roboto+Condensed or Alegreya:700 ). This google font should also be introduced on the font family in order to work', 'simpledevel common translations', $this->slug ),

           'line_height_hover' => _x( 'Line height hover', 'simpledevel common translations', $this->slug ),
           'line_height_hover_desc' => _x( 'Introduce the line height of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'letter_spacing_hover' => _x( 'Leter spacing hover', 'simpledevel common translations', $this->slug ),
           'letter_spacing_hover_desc' => _x( 'Introduce the leter spacing of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),

           'width_hover' => _x( 'Width hoverh', 'simpledevel common translations', $this->slug ),
           'width_hover_desc' => _x( 'Introduce the width ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'min_width_hover' => _x( 'Min width hover', 'simpledevel common translations', $this->slug ),
           'min_width_hover_desc' => _x( 'Introduce the minimum width of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'max_width_hover' => _x( 'Max width hover', 'simpledevel common translations', $this->slug ),
           'max_width_hover_desc' => _x( 'Introduce the max width of the font ( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),

           'height_hover' => _x( 'Height hover', 'simpledevel common translations', $this->slug ),
           'height_hover_desc' => _x( 'Introduce the height( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'min_height_hover' => _x( 'Min height hover', 'simpledevel common translations', $this->slug ),
           'min_height_hover_desc' => _x( 'Introduce the minimum height( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),
           'max_height_hover' => _x( 'Max height hover', 'simpledevel common translations', $this->slug ),
           'max_height_hover_desc' => _x( 'Introduce the maximum height( examples: 20px, 2rem, 2em )', 'simpledevel common translations', $this->slug ),

           'custom_css_hover' => _x( 'Custom css hover', 'simpledevel common translations', $this->slug ),
           'custom_css_hover_desc' => _x( 'Introduce custom css', 'simpledevel common translations', $this->slug ),
         );

     }

   }

 }
