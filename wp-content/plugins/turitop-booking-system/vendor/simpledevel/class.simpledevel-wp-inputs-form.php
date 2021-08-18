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
  * @class      simpledevel_wp_inputs_form
  * @package    Simpledevel
  * @since      Version 1.0.0
  * @author     Daniel Sanchez Saez
  *
  */

 if ( ! class_exists( 'simpledevel_wp_inputs_form' ) ) {
     /**
      *
      * @author Daniel Sanchez Saez
      */
     class simpledevel_wp_inputs_form {

         /**
          * boolean to enqueue styles and scripts
          *
          * @var boolean
          * @since  1.0.0
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @access public
          */
         public $enqueue = true;

         /**
          * data
          *
          * @var public
          * @since  1.0.0
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @access public
          */
         public $data = array();

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
 		 * args
 		 *
 		 * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
 		 */
 		 public $args = array();

         /**
         * array_errors
         *
         * @var array
         * @since 1.0.0
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @access public
         */
         public $array_errors = array();

         /**
          * __construct
          *
          * @since 1.0.0
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @access public
          */
         public function __construct( $args = array() ) {

             $this->args = $args;

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
         public function init() {

             $this->check_form_submited();

             $this->retrieve_data();

             $this->load_inputs();

             $this->display_errors();

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
         public function get_args() {

             return $this->args;

         }

         /**
          * set args
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function set_args( $args ) {

             return $this->args = $args;

         }

         /**
          * get data
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function get_data() {

             return $this->data;

         }

         /**
          * set data
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function set_data( $data ) {

             return $this->data = $data;

         }

         /**
          * check form submited
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function check_form_submited( $post_id = null ) {

             if ( isset( $_POST[ $this->args[ 'slug' ] . '_field' ] ) && wp_verify_nonce( $_POST[ $this->args[ 'slug' ] . '_field' ], $this->args[ 'slug' ] . '_action' ) && apply_filters( 'simpledevel_inputs_post_check_form', true ) ) {

                 $data = array();

                 foreach ( $this->args[ 'inputs' ] as $key => $value ) {
                     //$key = str_replace( "[]", "", $key );

                     if ( isset( $_POST[ $this->args[ 'slug' ] . '_' . $key ] ) ) {
                         switch ( $value[ 'input_type' ] ) {

                             case 'checkbox':
                                 $data[ $key ] = 'yes';
                                 break;

                             case 'text':
                                 $data[ $key ] = sanitize_text_field( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 break;

                             case 'hidden':
                                 $data[ $key ] = sanitize_text_field( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 break;

                             case 'textarea':
                                 if ( ! isset( $value[ 'sanitize' ] ) || $value[ 'sanitize' ] != 'no' )
                                  $data[ $key ] = sanitize_textarea_field( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 else
                                  $data[ $key ] = $_POST[ $this->args[ 'slug' ] . '_' . $key ];
                                 break;

                             case 'text_hex_color':
                                 $data[ $key ] = sanitize_hex_color( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 break;

                             case 'text_alpha_color':
                                 $data[ $key ] = sanitize_text_field( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 break;

                             case 'select':
                                 if ( ! is_array( $_POST[ $this->args[ 'slug' ] . '_' . $key ] ) )
                                  $data[ $key ] = sanitize_text_field( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 else
                                  $data[ $key ] = $_POST[ $this->args[ 'slug' ] . '_' . $key ];
                                 break;

                             case 'radio':
                                 $data[ $key ] = sanitize_key( $_POST[ $this->args[ 'slug' ] . '_' . $key ] );
                                 break;

                         }

                         $error = apply_filters( 'simpledevel_wp_inputs_single_error' , '', $key, $this->args[ 'slug' ], $data );
                         if ( ! empty( $error ) )
                             $this->array_errors[] = $error;
                     }
                     else if ( isset( $value[ 'input_type' ] ) && $value[ 'input_type' ] == 'checkbox' )
                            $data[ $key ] = 'no';

                 }

                 if ( apply_filters( 'simpledevel_inputs_post_check_form_update', true, $data ) )
                   switch ( $this->args[ 'type' ][ 'value' ] ) {
                       case 'option':
                           $old_data = get_option( $this->args[ 'slug' ] . "_data", true );
                           $old_data = ( empty( $old_data ) ? array() : ( is_array( $old_data ) ? $old_data : array() ) );
                           $data = array_merge( $old_data, $data );
                           update_option( $this->args[ 'slug' ] . "_data", apply_filters( 'simpledevel_wp_inputs_data_to_store', $data ) );
                           break;

                       case 'post_meta':
                           $old_data = get_post_meta( $post_id, $this->args[ 'slug' ] . "_data", true );
                           $old_data = ( empty( $old_data ) ? array() : ( is_array( $old_data ) ? $old_data : array() ) );
                           $data = array_merge( $old_data, $data );
                           update_post_meta( $post_id, $this->args[ 'slug' ] . "_data", apply_filters( 'simpledevel_wp_inputs_data_to_store', $data, $post_id ) );
                           break;
                   }

                 do_action( 'simpledevel_wp_inputs_check_form_submited_after', $data );

             }

         }

         /**
          * display errors
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function display_errors() {

           $this->array_errors = apply_filters( 'simpledevel_wp_inputs_errors' , $this->array_errors );

             if ( ! empty( $this->array_errors ) ):

                 ?>

                     <div class="error settings-error notice is-dismissible">

                         <?php

                             foreach ( $this->array_errors as $value )
                                 echo "<p><strong>" . $value . "</strong></p>";

                         ?>

                     </div>

                 <?php

             endif;

         }

         /**
          * retrieve data
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function retrieve_data( $post_id = null) {

             switch ( $this->args[ 'type' ][ 'value' ] ) {

                 case 'option':

                     $data = get_option( $this->args[ 'slug' ] . "_data" );
                     $this->data = ( is_array( $data ) ? $data : array() );

                     break;

                 case 'post_meta':

                     $data = get_post_meta( $post_id, $this->args[ 'slug' ] . "_data", true );
                     $this->data = ( is_array( $data ) ? $data : array() );

                     break;
             }

             $this->data = ( ! empty( $this->data ) ? $this->data : array() );

             foreach ( $this->args[ 'inputs' ] as $key => $value ) {
                 $this->data[ $key ] = ( isset( $this->data[ $key ] ) ? $this->data[ $key ] : ( isset( $value[ 'default' ] ) ? $value[ 'default' ] : '' ) );
             }

         }

         /**
          * loading inputs
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function load_inputs() {

             foreach ( $this->args[ 'inputs' ] as $key => $single_arg ) {

                 if ( ! isset( $single_arg[ 'title' ] ) && isset( $this->args[ 'common_translations' ][ $key ] ) )
                     $single_arg[ 'title' ] = $this->args[ 'common_translations' ][ $key ];
                 if ( ! isset( $single_arg[ 'description' ] ) && isset( $this->args[ 'common_translations' ][ $key . "_desc" ] ) )
                     $single_arg[ 'description' ] = $this->args[ 'common_translations' ][ $key . "_desc" ];
                 if ( ! isset( $single_arg[ 'tooltip' ] ) && isset( $this->args[ 'common_translations' ][ $key . "_tooltip" ] ) ){
                   $single_arg[ 'tooltip' ] = $this->args[ 'common_translations' ][ $key . "_tooltip" ];
                 }


                 $single_arg[ 'input_name' ] = ( isset( $single_arg[ 'input_name' ] ) ? $single_arg[ 'input_name' ] : $this->args[ 'slug' ] . "_" . $key );

                 $single_arg[ 'input_value' ] = ( isset( $single_arg[ 'input_value' ] ) ? $single_arg[ 'input_value' ] : ( isset( $this->data[ $key ] ) ? $this->data[ $key ] : '' ) );

                 if ( isset( $single_arg[ 'input_type' ] ) && $single_arg[ 'input_type' ] == 'select' ){
                     $options = array();
                     foreach ( $single_arg[ 'options' ] as $option ) {
                         if ( ! isset( $option[ 'text' ] ) )
                             $option[ 'text' ] = ( isset( $this->args[ 'common_translations' ][ $option[ 'value' ] ] ) ? $this->args[ 'common_translations' ][ $option[ 'value' ] ] : '' );
                         $options[] = $option;
                     }
                     $single_arg[ 'options' ] = $options;
                 }

                 if ( isset( $single_arg[ 'input_type' ] ) && $single_arg[ 'input_type' ] == 'radio' ){
                     $radios = array();
                     foreach ( $single_arg[ 'radios' ] as $radio ) {
                         if ( ! isset( $radio[ 'text' ] ) )
                             $radio[ 'text' ] = ( isset( $this->args[ 'common_translations' ][ $radio[ 'value' ] ] ) ? $this->args[ 'common_translations' ][ $radio[ 'value' ] ] : '' );
                         $radios[] = $radio;
                     }
                     $single_arg[ 'radios' ] = $radios;
                 }

                 $this->args[ 'inputs' ][ $key ] = $single_arg;
             }

         }

         /**
          * create nonce
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function create_nonce(){

             wp_nonce_field( $this->args[ 'slug' ] . '_action', $this->args[ 'slug' ] . '_field' );

         }

         /**
          * enqueue scripts
          *
          * @author Daniel Sanchez Saez <dssaez@gmail.com>
          * @since 1.0.0
          * @access public
          * @param
          * @return void
          *
          */
         public function enqueue_scripts() {

             if ( $this->enqueue ){

                 $this->enqueue = false;

                 $min = ( defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min' );

                 /* ====== Style ====== */

                 /**
                 * MENU SETTINGS
                 */

                 wp_register_style( 'simpledevel_admin_functions_css', apply_filters( 'simpledevel_admin_functions_css_filter', $this->args[ 'vendor_url' ] . '/simpledevel/css/simpledevel-admin-functions' . $min . '.css' ), array(), $this->version );
                 wp_enqueue_style( 'simpledevel_admin_functions_css' );

                 /** Jquery Select2 **/
                 wp_register_style( 'simpledevel_select2mincss', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
                 wp_enqueue_style( 'simpledevel_select2mincss' );

                 /** MEDIA LIBRARY **/
                 wp_enqueue_media();

                 //wp_enqueue_style( 'wp-color-picker' );
                 //wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( '/js/wp-color-picker-alpha.min.js', __FILE__ ), array( 'wp-color-picker' ), '1.0.0', true );

                 wp_register_script( 'wp-color-picker-alpha', apply_filters( 'simpledevel_admin_functions_alpha_js_filter',
                 $this->args[ 'vendor_url' ] . '/simpledevel/js/alpha/wp-color-picker-alpha.min.js' ),
                 array(
                     'wp-color-picker',
                 ), $this->version, true );
                 wp_enqueue_script( 'wp-color-picker-alpha' );

                /** CODE MIRROR **/
                if ( function_exists( 'wp_enqueue_code_editor' ) ) {
                    wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
                    wp_enqueue_style( 'wp-codemirror' );
                }

                 /* ====== Admin Script ====== */

                 /**
                 * MENU SETTINGS
                 */

                 /** Jquery Select2 **/
                 wp_register_script( 'simpledevel_select2jquery', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery'), null, true );
                 wp_enqueue_script( 'simpledevel_select2jquery' );

                 wp_register_script( 'simpledevel_admin_functions_js', apply_filters( 'simpledevel_admin_functions_js_filter',
                 $this->args[ 'vendor_url' ] . '/simpledevel/js/simpledevel-admin-functions.js' ),
                 array(
                     'jquery',
                     'jquery-ui-sortable',
                     'simpledevel_select2jquery',
                     'wp-color-picker',
                 ), $this->version, true );

                 wp_enqueue_script( 'simpledevel_admin_functions_js' );

                 /***********************************************************************************/

             }

           }

           /**
            *
            * displying menu settings
            *
            * @author Daniel Sanchez Saez <dssaez@gmail.com>
            * @since  1.0.0
            * @param array
            * @return html
            * @access public
            */
           public function display_inputs( $args = array(), $classes = array() ){

               $this->enqueue_scripts();

               ob_start();

               $main_wrap_class = 'simpled_main_line_wrap_block';
               $input_wrap_class = 'simpled_input_wrap_inline';

               foreach ( $classes as $class ) {

                   if ( $class == 'simpled_main_line_wrap_inline' )
                        $main_wrap_class = 'simpled_main_line_wrap_inline';

                   if ( $class == 'simpled_input_wrap_block' )
                        $input_wrap_class = 'simpled_input_wrap_block';

               }

               foreach ( $args as $key ) {

                  if ( ! isset( $this->args[ 'inputs' ][ $key ] ) )
                    continue;

                   $arg = $this->args[ 'inputs' ][ $key ];
               ?>

                   <div class="<?php echo $main_wrap_class; ?>" <?php echo ( isset( $arg[ 'input_type' ] ) && $arg[ 'input_type' ] == 'hidden' ? ' style="display: none;"' : '' ); ?>>

                       <div class="simpled_title_wrap">

                           <?php echo ( isset( $arg[ 'title' ] ) ?  $arg[ 'title' ] : '' ); ?>

                       </div>

                       <div class="simpled_input_wrap <?php echo $input_wrap_class; ?>">

                           <?php if ( isset( $arg[ 'text' ] ) ): ?>

                                   <?php if ( isset( $arg[ 'text_dashicons' ] ) ): ?>
                                       <span class="dashicons <?php echo $arg[ 'text_dashicons' ]; ?>"></span>
                                   <?php endif; ?>

                                   <span class="<?php echo ( isset( $arg[ 'text_class' ] ) ?  $arg[ 'text_class' ] : '' ); ?>">
                                       <?php echo ( isset( $arg[ 'text' ] ) ?  $arg[ 'text' ] : '' ); ?>
                                   </span>

                           <?php else: ?>

                               <div class="simpled_input">

                                   <?php

                                   if ( isset( $arg[ 'input_type' ] ) )
                                       switch ( $arg[ 'input_type' ] ) {
                                           case 'submit':
                                               ?>

                                               <a href="action" class="simpled_button_link
                                               <?php echo ( isset( $arg[ 'input_class' ] ) ?  $arg[ 'input_class' ] : '' ); ?>">

                                                   <?php if ( isset( $arg[ 'input_dashicons' ] ) ): ?>
                                                       <span class="dashicons <?php echo $arg[ 'input_dashicons' ]; ?>"></span>
                                                   <?php endif; ?>

                                                   <?php echo ( isset( $arg[ 'input_value' ] ) ?  $arg[ 'input_value' ] : '' ); ?>

                                               </a>

                                               <?php
                                               break;

                                           case 'textarea':

                                               ?>

                                               <textarea
                                                   class="<?php echo ( isset( $arg[ 'input_class' ] ) ?  $arg[ 'input_class' ] : '' ); ?>"
                                                   name="<?php echo ( isset( $arg[ 'input_name' ] ) ?  $arg[ 'input_name' ] : '' ); ?>"><?php echo ( isset( $arg[ 'input_value' ] ) ? stripslashes_deep( $arg[ 'input_value' ] ) : '' ); ?></textarea>

                                               <?php
                                               break;

                                           case 'select':
                                               if ( isset( $arg[ 'options' ] ) ){

                                                 $name = ( isset( $arg[ 'input_name' ] ) ? $arg[ 'input_name' ] : '' );
                                                 if ( isset( $arg[ 'attrs' ] ) && $arg[ 'attrs' ] == 'multiple="multiple"' )
                                                    $name = $name . "[]";
                                               ?>

                                                   <select
                                                       class="<?php echo ( isset( $arg[ 'input_class' ] ) ?  $arg[ 'input_class' ] : '' ); ?>"
                                                       name="<?php echo $name; ?>"
                                                       <?php echo ( isset( $arg[ 'attrs' ] ) ? $arg[ 'attrs' ] : '' ); ?>>

                                                       <?php

                                                           if ( isset( $arg[ 'options' ] ) )
                                                               foreach ( $arg[ 'options' ] as $option ) {
                                                                   echo "<option value='" . $option[ 'value' ] . "'";

                                                                   if ( isset( $option[ 'attrs' ] ) )
                                                                       foreach ( $option[ 'attrs' ] as $key => $attr ) {
                                                                           echo ' data-' . $key . '="' . $attr . '"';
                                                                       }

                                                                   if ( isset( $arg[ 'input_value' ] ) )
                                                                      if ( is_array( $arg[ 'input_value' ] ) && in_array( $option[ 'value' ], $arg[ 'input_value' ] ) )
                                                                        echo " selected='selected'";
                                                                      else if ( $arg[ 'input_value' ] == $option[ 'value' ] )
                                                                       echo " selected='selected'";

                                                                   echo ">" . $option[ 'text' ] . "</option>";
                                                               }
                                                       ?>

                               	                   </select>

                                               <?php
                                               }

                                               break;

                                           case 'radio':

                                               if ( isset( $arg[ 'radios' ] ) )
                                                   foreach ( $arg[ 'radios' ] as $radio ) {

                                                       ?>

                                                       <label>
                                                           <input
                                                               type="radio"
                                                               name="<?php echo ( isset( $arg[ 'input_name' ] ) ?  $arg[ 'input_name' ] : '' ); ?>"
                                                               value="<?php echo ( isset( $radio[ 'value' ] ) ?  $radio[ 'value' ] : '' ); ?>"
                                                               <?php echo( isset( $arg[ 'input_value' ] ) && $arg[ 'input_value' ] == $radio[ 'value' ] ? 'checked="checked"' : '' ); ?>
                                                           />

                                                           <span class="simpled_menu_radio_text">
                                                               <?php echo ( isset( $radio[ 'text' ] ) ?  $radio[ 'text' ] : '' ); ?>
                                                           </span>
                                                       </label>

                                                       <?php

                                                   }

                                               if ( isset( $arg[ 'select_multiple_name' ] ) ){
                                                   ?>

                                                   <div style="position: relative;">
                                                       <br>
                                                       <select multiple="multiple"
                                                       name="<?php echo ( isset( $arg[ 'select_multiple_name' ] ) ?  $arg[ 'select_multiple_name' ] : '' ); ?>" class="<?php echo ( isset( $arg[ 'select_multiple_class' ] ) ?  $arg[ 'select_multiple_class' ] : '' ); ?>">

                                                       <?php

                                                           foreach ( $arg[ 'multiple_selects' ] as $multiple_select ) {

                                                               echo '<option';
                                                               echo ' value="' . $multiple_select[ 'value' ] . '"';
                                                               echo ( $multiple_select[ 'selected' ] == 'yes' ? ' selected="selected"' : '' );
                                                               echo '>';
                                                               echo $multiple_select[ 'text' ] . '</option>';

                                                           }

                                                       ?>
                                                       </select>
                                                       <br>
                                                       <div class="simpledevel_menu_blank_brightnes"></div>
                                                   </div>
                                                   <?php
                                               }

                                               break;

                                           default

                                               ?>

                                               <input
                                                   type="<?php echo ( isset( $arg[ 'input_type' ] ) ?  $arg[ 'input_type' ] : '' ); ?>"
                                                   class="<?php echo ( isset( $arg[ 'input_class' ] ) ?  $arg[ 'input_class' ] : '' ); ?>"
                                                   name="<?php echo ( isset( $arg[ 'input_name' ] ) ?  $arg[ 'input_name' ] : '' ); ?>"
                                                   value="<?php echo ( isset( $arg[ 'input_value' ] ) ?  stripslashes_deep( $arg[ 'input_value' ] ) : '' ); ?>"
                                                   <?php
                                                       if ( isset( $arg[ 'input_type' ] ) && isset( $arg[ 'input_value' ] ) && $arg[ 'input_type' ] == 'checkbox' && $arg[ 'input_value' ] == 'yes' )
                                                           echo "checked=checked";
                                                   ?>
                                                   <?php
                                                       if ( isset( $arg[ 'input_type' ] ) && $arg[ 'input_type' ] == 'text_alpha_color' )
                                                           echo 'class="color-picker" data-alpha="true"';
                                                   ?>
                                               >

                                               <?php

                                               if ( isset( $arg[ 'input_description' ] ) ){
                                                   ?>
                                                   <span class="<?php echo ( isset( $arg[ 'input_description_class' ] ) ?  $arg[ 'input_description_class' ] : '' ); ?>"><?php echo ( isset( $arg[ 'input_description' ] ) ?  $arg[ 'input_description' ] : '' ); ?></span>
                                                   <?php
                                               }

                                               break;
                                       }

                                  if ( isset( $arg[ 'tooltip' ] ) ){
                                    ?>
                                      <span class="simpledevel_tooltip_system">

                                        <span class="dashicons dashicons-editor-help">
                                          <span class="simpledevel_tooltip_content"> <?php echo $arg[ 'tooltip' ]; ?> </span>
                                        </span>

                                      </span>
                                    <?php

                                  }

                                  ?>

                               </div>

                               <div class="simpled_description">

                                   <?php if ( isset( $arg[ 'description_dashicons' ] ) ): ?>
                                       <span class="dashicons <?php echo $arg[ 'description_dashicons' ]; ?>"></span>
                                   <?php endif; ?>

                                   <?php echo ( isset( $arg[ 'description' ] ) ?  $arg[ 'description' ] : '' ); ?>

                                   <div class="simpledevel_menu_blank_brightnes"></div>

                               </div>

                           <?php endif; ?>

                       </div>

                   </div>

               <?php

               }

               echo ob_get_clean();

           }

     }

 }
