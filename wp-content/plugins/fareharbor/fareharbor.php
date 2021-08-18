<?php
  /*
    Plugin Name: FareHarbor for WordPress
    Plugin URI: https://fareharbor.com/help/setup/wordpress-plugin/
    Description: Easily add FareHarbor reservation calendars and buttons to your site
    Version: 3.6.2
    Author: FareHarbor
    Author URI: https://fareharbor.com
  */

  // Make sure this file isn't loaded on its own
  // -----------------------------------------------

  defined( 'ABSPATH' ) or die( 'Lost? <a href="/">Return home.</a>' );

  // Add init hook
  // -----------------------------------------------

  add_action( 'init', array( 'fareharbor', 'init' ) );


  // Global functions for potential backwards compatability
  // -----------------------------------------------

  function fh_shortcode( $attrs ) {
    return fareharbor::fareharbor_shortcode( $attrs );
  }

  function lightframe_shortcode( $attrs, $content = '' ) {
    return fareharbor::lightframe_shortcode( $attrs, $content );
  }

  function partners_shortcode( $attrs ) {
    return fareharbor::partners_shortcode( $attrs );
  }


  // The important stuff
  // -----------------------------------------------

  final class fareharbor {

    // This class is just a namespace for static methods & variables
    private function __construct() {}

    public static $version = '3.6.2';

    // Update the saved version number in the wp_options table
    // ===============================================

    public static function init() {

      // In a multisite environment some themes may wish to disable
      // this plugin.
      if ( apply_filters( 'fareharbor/disabled', false ) )
        return;

      // Register the shortcodes
      // -----------------------------------------------

      add_shortcode( 'fareharbor', array( 'fareharbor', 'fareharbor_shortcode' ) );
      add_shortcode( 'lightframe', array( 'fareharbor', 'lightframe_shortcode' ) );
      add_shortcode( 'partners', array( 'fareharbor', 'partners_shortcode' ) );
      add_shortcode( 'itemgrid', array( 'fareharbor', 'itemgrid_shortcode' ) );
      // add_shortcode( 'bookembed', array( 'fareharbor', 'bookembed_shortcode' ) );

      // Register our Settings Page & Settings
      // -----------------------------------------------

      add_action( 'admin_menu', array( 'fareharbor', 'register_options_page' ) );
      add_action( 'admin_init', array( 'fareharbor', 'register_settings' ) );


      // Include the script in the footer
      // -----------------------------------------------

      add_action( 'wp_footer', array( 'fareharbor', 'lightframe_api_footer' ) );


      // Maybe include fh-kit styles in header
      // -----------------------------------------------
      // Depends on option being set in the admin dashboard
      add_action( 'wp_enqueue_scripts', array( 'fareharbor', 'maybe_enqueue_fh_kit_styles' ) );

      $saved_version = get_option( 'fareharbor_version' );
      if ( $saved_version !== self::$version )
        self::upgrade( $saved_version );

    }

    // Upgrade
    // ===============================================

    private static function upgrade( $from ) {

      if ( !$from ) {
        // New install or upgrade from version before 3.0, before we saved
        // version numbers in the database.  No choice but to treat those
        // as a new install.
        // No settings should be saved but let's be careful not to override
        // just in case
        $saved_settings = get_option( 'fareharbor_settings', array() );
        if ( !is_array( $saved_settings ) )
          // Even less likely, but again just in case
          $saved_settings = array();

        $defaults = array(
          'fh_responsive_calendars' => 'on',
          'fh_default_fallback' => 'simple',
          'fh_auto_lightframe_enabled' => 'on',
        );
        update_option( 'fareharbor_settings', $saved_settings + $defaults );

        // All new installs from v3.4 on should use v2 of the stylesheet
        update_option( 'fareharbor_kit_version', 'v2' );
      }
      elseif ( version_compare( $from, '3.4', '<' ) ) {
        // Can't swap version of old sites that might already be using v1
        // of the stylesheet!
        update_option( 'fareharbor_kit_version', 'v1' );
      }

      update_option( 'fareharbor_version', self::$version, true );

    }

    // Script source
    // ===============================================

    public static function lightframe_api_footer() {

      $src = 'https://' . self::url() . '/embeds/api/v1/';

      if ( self::get_option( 'fh_auto_lightframe_enabled' ) )
        $src .= '?autolightframe=yes';

      echo "<!-- FareHarbor plugin activated --><script src=\"$src\"></script>";

    }


    // FH-Kit Styles
    // ===============================================

    public static function maybe_enqueue_fh_kit_styles() {

      if ( self::is_lite() )
        return;

      if( !self::get_option( 'fh_buttons_active' ) )
        return;

      $query_string = self::get_option( 'fh_buttons_query' );

      $query_string = strip_tags( $query_string );
      $query_string = trim( $query_string );
      $query_string = ltrim( $query_string, '?' );

      // Default to v1 for sites that don't have this in the options table in
      // case upgrade procedure didn't run.  All new installs should get
      // `fareharbor_kit_version` saved as v2 upon install.

      $version = get_option( 'fareharbor_kit_version', 'v1' );
      // Make the version filterable
      $version = apply_filters( 'fareharbor/fh_kit_version', $version );
      // Validate.  If we got something funky (as opposed to nothing at all) then
      // we'll default to version 2.
      if ( !in_array( $version, array( 'v1', 'v2', ), true ) )
        $version = 'v2';

      $fh_kit_src = "https://fh-kit.com/buttons/$version/";

      if ( $query_string )
        $fh_kit_src .= '?' . $query_string;

      $fh_kit_src = apply_filters( 'fareharbor/buttons_style_src', $fh_kit_src );

      // the 4th parameter is $version.  Leaving it out causes WP to
      // add ver={current-wp-version} to the url.  passing null stops this.
      wp_enqueue_style( 'fh-buttons', $fh_kit_src, array(), null );

    }


    // Default Arguments
    // ===============================================

    private static $shared_defaults,
    $lightframe_defaults,
    $calendar_defaults,
    $partners_defaults,
    $itemgrid_defaults,
    $bookembed_defaults;

    public static function _clear_options() {
      // This method exists for testing only.
      self::$options = null;
      self::$shared_defaults = null;
      self::$lightframe_defaults = null;
      self::$calendar_defaults = null;
      self::$partners_defaults = null;
      self::$itemgrid_defaults = null;
      self::$bookembed_defaults = null;
    }

    private static function init_defaults() {

      if ( isset( self::$shared_defaults ) )
        return;

      self::$shared_defaults = array(

        'shortname' => self::get_option( 'fh_default_shortname', '' ),
        'items' => '',
        'asn' => self::get_option( 'fh_default_asn', '' ),
        'asn_ref' => self::get_option( 'fh_default_asn_ref', '' ),
        'ref' => self::get_option( 'fh_default_ref', '' ),
        'lightframe' => self::get_option( 'fh_default_lightframe', 'yes' ),
        'full_items' => self::get_option( 'fh_default_full_items', 'no' ),
        'sheet' => self::get_option( 'fh_default_sheet', '' ),
        'schedule' => self::get_option( 'fh_default_schedule', '' ),
        'fallback' => 'simple',
        'flow' => '',
        'language' => self::get_option( 'fh_default_language', '' ),

      );

      self::$bookembed_defaults =
      self::$lightframe_defaults = array(

        // 'class' => '',
        // 'style' => '',
        // 'id' => '',
        'view' => 'items',
        'view_item' => '',
        'view_availability' => '',

      );
      self::$bookembed_defaults[ 'fallback' ] = '';

      self::$calendar_defaults = array();

      if ( self::get_option( 'fh_responsive_calendars', false ) )
        self::$calendar_defaults[ 'type' ] = 'calendar';
      else
        self::$calendar_defaults[ 'type' ] = 'calendar-small';

      self::$partners_defaults = array(

        'include' => '',

      );

      self::$itemgrid_defaults = array();

    }


    // [lightframe][/lightframe] shortcode
    // ===============================================

    public static function lightframe_shortcode( $attrs, $content = '' ) {

      $link_attrs = self::lightframe_link_attrs( $attrs );

      if ( $error = self::maybe_handle_shortcode_error( $link_attrs ) )
        return $error;

      if ( !empty( $attrs[ 'class' ] ) )
        $link_attrs[ 'class' ] = $attrs[ 'class' ];
      elseif ( $default_class = self::get_option( 'fh_default_class' ) )
        $link_attrs[ 'class' ] = $default_class;

      if ( !empty( $attrs[ 'id' ] ) )
        $link_attrs[ 'id' ] = $attrs[ 'id' ];

      if ( !empty( $attrs[ 'style' ] ) )
        $link_attrs[ 'style' ] = $attrs[ 'style' ];

      $link_attrs = array_map( 'strip_tags', $link_attrs );

      $link_attrs_string = '';
      foreach ( $link_attrs as $name => $value )
        $link_attrs_string .= " $name=\"$value\"";

      if ( trim( $content ) )
        $content = do_shortcode( $content );

      return "<a$link_attrs_string>$content</a>";

    }

    public static function lightframe_link_attrs( $args ) {

      if ( !isset( self::$lightframe_defaults ) )
        self::init_defaults();

      $args = self::process_args( $args, self::$lightframe_defaults, 'lightframe' );

      if ( isset( $args[ 'error' ] ) )
        return $args;

      if ( $args[ 'view_availability' ] && !$args[ 'view_item' ] )
        return array( 'error' => 'view_availability but no view_item' );

      $fallback_url = self::lightframe_fallback_url( $args );

      $api_options = self::lightframe_api_options( $args );
      $json = strtr( json_encode( $api_options ), '"', "'" );

      return array(
        'href' => $fallback_url,
        'target' => '_blank',
        'onclick' => "FH.open($json); return false;",
      );

    }

    private static function lightframe_fallback_url( $args, $is_bookembed = false ) {

      // The bookembed url structure is very similar to the simple fallback url structure
      $is_simple_fallback = ( $args[ 'fallback' ] === '' ) || ( $args[ 'fallback' ] === 'simple' ) || $is_bookembed;

      $url = 'https://' . self::url() . '/';

      if ( $is_bookembed )
        $url .= 'embeds/script/book/';
      elseif ( $is_simple_fallback )
        $url .= 'embeds/book/';

      $url .= $args[ 'shortname' ] . '/';

      if ( !$is_simple_fallback
        || $args[ 'view' ] === 'all-availability'
        || $args[ 'view_item' ]
      ) {
        $url .= 'items/';
      }

      if ( $args[ 'view_item' ] ) {

        $url .= $args[ 'view_item' ] . '/';

        if ( $args[ 'view_availability' ] )
          $url .= 'availability/' . $args[ 'view_availability' ] . '/book/';
        elseif ( $args[ 'full_items' ] === 'no' )
          $url .= 'calendar/';

      } elseif ( $args[ 'view' ] === 'all-availability' ) {

        $url .= 'calendar/';

      }

      $query = self::get_shared_args_array( $args, 'dash', $is_simple_fallback, false, true );

      if ( $is_simple_fallback && $args[ 'items' ] )
        $query[ 'selected-items' ] = $args[ 'items' ];

      if ( !$is_bookembed ) {

        $current_url = get_home_url();
        if ( !$current_url ) {
          $current_url = ( is_ssl() ? 'https' : 'http' ) . '://'
            . ( !empty( $_SERVER[ 'HTTP_HOST' ] ) ? $_SERVER[ 'HTTP_HOST' ] : $_SERVER[ 'SERVER_NAME' ] );
        }
        $current_url .= $_SERVER[ 'REQUEST_URI' ];

        if ( $is_simple_fallback )
          $query[ 'referrer' ] = $current_url;

      }

      if ( $query )
        $url .= '?' . http_build_query( $query );

      return $url;

    }

    private static function lightframe_api_options( $args ) {

      $lo = array( 'shortname' => $args[ 'shortname' ] );

      // Filter the visible items
      if ( $args[ 'items' ] )
        // putting it in an array so it gets brackets in json_encode
        $lo[ 'items' ] = explode( ',', $args[ 'items' ] );

      // Set the view
      if ( $args[ 'view_item' ] ) {

        $lo[ 'view' ] = array( 'item' => $args[ 'view_item' ] );

        if ( $args[ 'view_availability' ] )
          $lo[ 'view' ][ 'availability' ] = $args[ 'view_availability' ];

      } elseif ( in_array( $args[ 'view' ], array( 'items', 'all-availability' ), true ) ) {

        $lo[ 'view' ] = $args[ 'view' ];

      } else {

        $lo[ 'view' ] = 'items';

      }

      // Modifiers: shared args like fallback, asn, asn_ref, etc.
      $lo += self::get_shared_args_array( $args, 'camel', true, true, true );

      return $lo;

    }


    // [bookembed] shortcode
    // ===============================================

    public static function bookembed_shortcode( $attrs ) {

      $script_src = self::bookembed_script_src( $attrs );

      $maybe_error = self::maybe_handle_shortcode_error( $script_src );
      return $maybe_error ? $maybe_error : "<script src=\"$script_src\"></script>";

    }

    public static function bookembed_script_src( $args ) {

      if ( !isset( self::$bookembed_defaults ) )
        self::init_defaults();

      $args = self::process_args( $args, self::$bookembed_defaults, 'bookembed' );

      if ( isset( $args[ 'error' ] ) )
        return $args;

      if ( $args[ 'view_availability' ] && !$args[ 'view_item' ] )
        return array( 'error' => 'view_availability but no view_item' );

      return self::lightframe_fallback_url( $args, true );

    }


    // [fareharbor] shortcode (calendar)
    // ===============================================

    public static function fareharbor_shortcode( $attrs ) {

      $script_src = self::calendar_script_src( $attrs );

      $maybe_error = self::maybe_handle_shortcode_error( $script_src );
      return $maybe_error ? $maybe_error : "<script src=\"$script_src\"></script>";

    }

    public static function calendar_script_src( $args ) {

      if ( !isset( self::$calendar_defaults ) )
        self::init_defaults();

      $args = self::process_args( $args, self::$calendar_defaults, 'fareharbor' );

      if ( isset( $args[ 'error' ] ) )
        return $args;

      $type = $args[ 'type' ];
      if ( $type === 'small' )
        $type = 'calendar-small';
      elseif ( $type === 'large' || $type === 'responsive' )
        $type = 'calendar';

      $url_suffix = '';
      if ( $args[ 'items' ] )
        $url_suffix = 'items/' . $args[ 'items' ] . '/';

      return self::embed_script_src( $type, $args, $url_suffix, array(), false, true );

    }


    // [partners] shortcode
    // ===============================================

    public static function partners_shortcode( $attrs ) {

      $script_src = self::partners_script_src( $attrs );

      $maybe_error = self::maybe_handle_shortcode_error( $script_src );
      return $maybe_error ? $maybe_error : "<script src=\"$script_src\"></script>";

    }

    public static function partners_script_src( $args ) {

      if ( !isset( self::$partners_defaults ) )
        self::init_defaults();

      $args = self::process_args( $args, self::$partners_defaults, 'partners' );

      if ( isset( $args[ 'error' ] ) )
        return $args;

      $query = array();
      if ( $args[ 'include' ] )
        $query[ 'include' ] = $args[ 'include' ];

      return self::embed_script_src( 'partners', $args, '', $query, true, false );

    }


    // [items] shortcode
    // ===============================================

    public static function itemgrid_shortcode( $attrs ) {

      $script_src = self::itemgrid_script_src( $attrs );

      $maybe_error = self::maybe_handle_shortcode_error( $script_src );
      return $maybe_error ? $maybe_error : "<script src=\"$script_src\"></script>";

    }


    public static function itemgrid_script_src( $args ) {

      if ( !isset( self::$itemgrid_defaults ) )
        self::init_defaults();

      $args = self::process_args( $args, self::$itemgrid_defaults, 'itemgrid' );

      if ( isset( $args[ 'error' ] ) )
        return $args;

      $query = array();
      if ( $args[ 'items' ] )
        $query[ 'selected-items' ] = $args[ 'items' ];

      return self::embed_script_src( 'items', $args, '', $query, true, true );

    }


    // Shared Shortcode Functionality
    // ===============================================

    // Handling Argument Input
    // -----------------------------------------------

    private static function process_args( $args, $defaults, $shortcode_name ) {

      if ( !isset( self::$shared_defaults ) )
        self::init_defaults();

      $defaults += self::$shared_defaults;

      if ( !is_array( $args ) ) // Just in case.
        $args = $defaults;

      // Trim whitespace && Strip Tags
      $args = array_map( array( __CLASS__, 'sanitize' ), $args );

       // Strip smart quotes, because WordPress returns them as part of the value if the shortcode was set up using them
      $args = str_replace(
        array( "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", chr(145), chr(146), chr(147), chr(148) ),
        array( '', '', '', '', '', '', '', '' ),
        $args
      );

      // Merge in defaults
      $args = shortcode_atts( $defaults, $args, $shortcode_name );

      // Confirm there's a shortname.  All of our shortcodes require this
      if ( !$args[ 'shortname' ] )
        return array( 'error' => 'no shortname' );

      // Shortnames only work if lowercase
      $args[ 'shortname' ] = strtolower( $args[ 'shortname' ] );

      // Clean up item IDs and included companies because users can't be trusted
      $csv_keys = array( 'items', 'view_item', 'include' );
      foreach ( $csv_keys as $key )
        if ( isset( $args[ $key ] ) )
          $args[ $key ] = self::sanitize_csv( $args[ $key ] );

      if ( $shortcode_name === 'lightframe' || $shortcode_name === 'bookembed' ) {

        if ( !$args[ 'view_item' ] ) {
          // See if we're filtering to just one item
          // in that case we treat it as if it were
          // passed  to view_item instead
          $items_array = explode( ',', $args[ 'items' ] );
          if ( count( $items_array ) === 1 ) {
            $args[ 'view_item' ] = $items_array[0];
            $args[ 'items' ] = '';
          }
        }

      }

      return $args;

    }

    public static function sanitize( $value ) {

      return trim( strip_tags( $value ) );

    }

    private static function sanitize_csv( $value ) {

      return rtrim( str_replace( ' ', '', $value ), ',' );

    }


    // Handling Errors
    // -----------------------------------------------

    private static $error_messages = array(

      'no shortname'
        => '<p>Please provide a FareHarbor shortname. (Format: <code>shortname="yourshortname"</code>)</p>',
      'view_availability but no view_item'
        => '<p>Please provide <code>view_item</code> if using <code>view_availability</code>.</p>',

    );

    private static function maybe_handle_shortcode_error( $output ) {

      if ( is_array( $output ) && !empty( $output[ 'error' ] ) ) {

        return isset( self::$error_messages[ $output[ 'error' ] ] )
          ? self::$error_messages[ $output[ 'error' ] ]
          : '<p>' . $output[ 'error' ] . '</p>';

      }

      return false;

    }


    // Scripts for embeds (in use by [partners] and [fareharbor])
    // -----------------------------------------------

    private static function embed_script_src( $type, $args, $url_suffix, $query, $include_full_items, $include_flow ) {

      $script_src = 'https://' . self::url() . '/embeds/script/'
        . $type
        . '/'
        . $args[ 'shortname' ]
        . '/'
        . $url_suffix;

      $args[ 'lightframe' ] = strtolower( trim( $args[ 'lightframe' ] ) );
      if ( $args[ 'lightframe' ] === 'no' )
        $query += array( 'lightframe' => $args[ 'lightframe' ] );

      $query += self::get_shared_args_array( $args, 'dash', $include_full_items, true, $include_flow );

      $query_string = http_build_query( $query );

      return $script_src . ( $query_string ? "?$query_string" : '' );

    }


    // Shared arguments
    // -----------------------------------------------

    private static function get_shared_args_array( $args, $casing, $include_full_items, $include_fallback, $include_flow ) {

      $out = array();

      if ( $include_fallback && in_array( $args[ 'fallback' ], array( 'simple', 'classic' ) ) )
        $out[ 'fallback' ] = $args[ 'fallback' ];

      if ( $include_full_items && $args[ 'full_items' ] !== 'no' )
        $out[ 'full_items' ] = $args[ 'full_items' ];

      if ( $args[ 'asn' ] ) {

        $out[ 'asn' ] = $args[ 'asn' ];

        if ( $args[ 'asn_ref' ] )
          $out[ 'asn_ref' ] = $args[ 'asn_ref' ];

      }

      if ( $args[ 'ref' ] )
        $out[ 'ref' ] = $args[ 'ref' ];

      if ( $args[ 'sheet' ] )
        $out[ 'sheet' ] = $args[ 'sheet' ];

      if ( $args[ 'schedule' ] )
        $out[ 'schedule' ] = $args[ 'schedule' ];

      if ( $args[ 'language' ] )
        $out[ 'language' ] = $args[ 'language' ];

      if ( $include_flow && $args[ 'flow' ] )
        $out[ 'flow' ] = $args[ 'flow' ];

      return self::fix_array_key_casing( $out, $casing );

    }

    private static function fix_array_key_casing( $in, $casing ) {

      $out = array();
      foreach ( $in as $key => $value )
        $out[ self::fix_casing( $key, $casing ) ] = $value;

      return $out;

    }

    private static function fix_casing( $text, $casing ) {
      // Assumes $text is already in snake_case
      switch ( $casing ) {

        case 'camel':

          // ucwords() doesn't support a $delimeters param
          // until php 5.4.32/5.5.16
          $text = strtr( $text, '_', ' ' );
          $text = ucwords( $text );
          $text = str_replace( ' ', '', $text );
          // lcfirst() doesn't exist until php 5.3.0
          $text[0] = strtolower( $text[0] );
          return $text;

        case 'dash':

          return strtr( $text, '_', '-' );

      }

      return $text;

    }


    // FareHarbor URL with optional FH_ENVIRONMENT
    // -----------------------------------------------

    private static function url() {

      $environment = ( defined( 'FH_ENVIRONMENT' ) && FH_ENVIRONMENT ) ? FH_ENVIRONMENT : '';
      $environment = apply_filters( 'fareharbor/environment', $environment );

      return trim( $environment )
        ? trim( $environment ) . '.fareharbor.com'
        : 'fareharbor.com';

    }


    // Settings & Settings Pages
    // ===============================================

    // Settings
    // -----------------------------------------------

    private static function is_lite() {
      // This filter allows fh-kit and the admin page
      // to be turned off with:
      // add_filter( 'fareharbor/lite', '__return_true' );
      return apply_filters( 'fareharbor/lite', false );

    }

    private static $options;

    private static function get_option( $option_name, $default = '', $filtered = true ) {

      $filter_name = str_replace( 'fh_', '', $option_name );
      $filter_name = str_replace( 'default_', 'defaults/', $filter_name );
      $filter_name = str_replace( 'buttons_', 'buttons/', $filter_name );
      $filter_name = "fareharbor/$filter_name";

      if ( self::is_lite() )
        return $filtered ? apply_filters( $filter_name, $default ) : $default;

      if ( !isset( self::$options ) )
        self::$options = get_option( 'fareharbor_settings' );

      $value = isset( self::$options[ $option_name ] )
        ? self::$options[ $option_name ]
        : $default;

      if ( $filtered )
        $value = apply_filters( $filter_name, $value );

      return $value;

    }


    // The settings page
    // -----------------------------------------------

    public static function register_options_page() {

      if ( self::is_lite() )
        return;

      add_options_page(
        'FareHarbor Plugin Settings',
        'FareHarbor',
        'manage_options',
        __FILE__,
        array( __CLASS__, 'render_options_page' )
      );

    }

    public static function register_settings() {

      if ( self::is_lite() )
        return;

      register_setting(
        'fareharbor_settings',
        'fareharbor_settings',
        array( __CLASS__, 'validate_settings' )
      );

      add_settings_section(
        'fh_about_section',
        'About',
        array( __CLASS__, 'render_fh_about_section_text' ),
        __FILE__
      );

      add_settings_section(
        'fh_shortcode_defaults_section',
        'Shortcode Defaults',
        array( __CLASS__, 'render_fh_shortcode_defaults_section_text' ),
        __FILE__
      );

      add_settings_field(
        'fh_default_shortname',
        'shortname',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_shortname',
          'description' => 'Your company\'s FareHarbor shortname.',
        )
      );

      add_settings_field(
        'fh_responsive_calendars',
        'Responsive Calendars',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_responsive_calendars',
          'type' => 'checkbox',
          'label' => 'Use responsive calendars by default.',
          'description' => 'This is the same as [fareharbor type="responsive"].',
        )
      );

      add_settings_field(
        'fh_default_asn',
        'asn',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_asn',
          'description' => 'The shortname of your partner company. Include if using the ASN network.',
        )
      );

      add_settings_field(
        'fh_default_asn_ref',
        'asn_ref',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_asn_ref',
          'description' => 'The voucher number that should be set for ASN bookings.',
        )
      );

      add_settings_field(
        'fh_default_ref',
        'ref',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_ref',
          'description' => 'The online booking reference that should be set for bookings',
        )
      );

      add_settings_field(
        'fh_default_sheet',
        'sheet',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_sheet',
          'description' => 'The price sheet that should be used while creating bookings.',
        )
      );

      add_settings_field(
        'fh_default_schedule',
        'schedule',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_schedule',
          'description' => 'The price schedule that should be used while creating bookings.',
        )
      );

      add_settings_field(
        'fh_default_language',
        'language',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_language',
          'description' => 'The two letter code for the language that FareHarbor pages should be overridden to, instead of detecting the user\'s language. (Not recommended.)',
        )
      );

      add_settings_field(
        'fh_default_full_items',
        'full_items',
        array( __CLASS__, 'render_select_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_full_items',
          'choices' => array(
            'yes',
            'no',
          ),
          'description' => 'Should the Lightframe that is opened include item descriptions and photos?  Defaults to "no".',
        )
      );

      add_settings_field(
        'fh_default_lightframe',
        'lightframe',
        array( __CLASS__, 'render_select_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_lightframe',
          'choices' => array(
            'yes',
            'no',
          ),
          'description' => 'Choose "yes" to open new bookings inside a Lightframe.  Choose "no" to open them in an external page instead.  Defaults to "yes".'
        )
      );

      add_settings_field(
        'fh_default_class',
        'class',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_shortcode_defaults_section',
        array(
          'option_name' => 'fh_default_class',
          'description' => 'The class option only applies to the [lightframe] shortcode.',
        )
      );

      add_settings_section(
        'fh_auto_lightframe_section',
        'Automatic Lightframing',
        array( __CLASS__, 'render_fh_auto_lightframe_section_text' ),
        __FILE__
      );

      add_settings_field(
        'fh_auto_lightframe_enabled',
        'Auto Lightframing Activation',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_auto_lightframe_section',
        array(
          'option_name' => 'fh_auto_lightframe_enabled',
          'type' => 'checkbox',
          'label' => 'Enable auto Lightframing.',
        )
      );

      add_settings_section(
        'fh_buttons_section',
        'FareHarbor Buttons',
        array( __CLASS__, 'render_fh_buttons_section_text' ),
        __FILE__
      );

      add_settings_field(
        'fh_buttons_active',
        'FH Buttons Activation',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_buttons_section',
        array(
          'option_name' => 'fh_buttons_active',
          'type' => 'checkbox',
          'label' => 'Load the FH Buttons stylesheet on all pages.',
        )
      );

      add_settings_field(
        'fh_buttons_query',
        'Button Colors',
        array( __CLASS__, 'render_input_field' ),
        __FILE__,
        'fh_buttons_section',
        array(
          'option_name' => 'fh_buttons_query',
          'description' => 'Provide colors in the format <code>red=ff0000&green=00ff00</code>',
        )
      );

    }

    public static function validate_settings( $input ) {

      // Strip tags and trim whitespace
      $input = array_map( array( __CLASS__, 'sanitize' ), $input );

      // Let's not pollute the DB with empty settings
      $input = array_filter( $input );

      // Shortnames only work when lowercase
      if ( isset( $input[ 'fh_default_shortname' ] ) )
        $input[ 'fh_default_shortname' ] = strtolower( $input[ 'fh_default_shortname' ] );

      return $input;

    }

    public static function render_options_page() {

      ?>

      <div class="wrap">

        <h2>FareHarbor Plugin Settings</h2>

        <form action="options.php" method="post">

          <?php settings_fields( 'fareharbor_settings' ); ?>

          <?php do_settings_sections( __FILE__ ); ?>

          <p class="submit">
            <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
          </p>

        </form>

      </div>

      <?php

    }

    public static function render_fh_about_section_text() {

      echo '<p><b>This plugin requires a FareHarbor account to work.</b>  FareHarbor provides powerful, intuitive, and highly customizable reservation software for activity and tourism businesses.  We can help you enable online booking on your website using this plugin and a whole range of other features.  Don\'t have an account?  <a href="https://fareharbor.com/join/" target="_blank">Get in touch at fareharbor.com.</a></p><p>If you\'re already with FareHarbor, check out our <a href="https://fareharbor.com/help/setup/wordpress-plugin/" target="_blank">help center article</a> on how to get started with this plugin.</p>';

    }

    public static function render_fh_shortcode_defaults_section_text() {

      echo '<p>Set default options for the [lightframe], [fareharbor], [itemgrid], and [partners] shortcodes here.  The options written in when including a shortcode on a page will always take precedence over the default values entered below. <a href="https://fareharbor.com/help/setup/wordpress-plugin/#setting-defaults" target="_blank">Learn how default options work.</a></p>';

    }

    public static function render_fh_auto_lightframe_section_text() {

      echo '<p>Open normal links to FareHarbor as Lightframe overlays, even if the [lightframe] shortcode isn&apos;t used.</p>';

    }

    public static function render_fh_buttons_section_text() {

      echo '<p>FareHarbor can automatically load in a CSS stylesheet which includes classes to make your Lightframe links look like beautifully designed buttons.</p>';

    }

    public static function render_input_field( $args ) {
      // We use this function to render all of our options fields
      // $args is supplied as the last parameter to each
      // add_settings_field() call in fareharbor::register_settings()
      // This function is only run on our settings page in the admin

      $type = isset( $args[ 'type' ] ) ? $args[ 'type' ] : 'text';

      $option_name = $args[ 'option_name' ];
      $value = self::get_option( $option_name, '', false );

      // Attributes all input tags need
      $attrs = array(
        'name' => "fareharbor_settings[$option_name]",
        'id' => $option_name,
        'type' => $type,
      );

      // Type-specific attributes
      switch ( $type ) {

        case 'text':
          $attrs[ 'size' ] = '40';
          $attrs[ 'value' ] = (string) $value;
          break;

        case 'checkbox':
          if ( $value )
            $attrs[ 'checked' ] = 'checked';
          break;

      }

      // Merge in attributes supplied in the arguments
      if ( isset( $args[ 'attrs' ] ) )
        // attributes from the arguments will override the defaults
        $attrs = $args[ 'attrs' ] + $attrs;

      // Actually generate the input tag
      $out = '<input';
      foreach ( $attrs as $name => $value )
        $out .= " $name=\"$value\"";
      $out .= ' />';

      if ( !empty( $args[ 'label' ] ) )
        $out .= "<label for=\"$option_name\">{$args[ 'label' ]}</label>";

      if ( !empty( $args[ 'description' ] ) )
        $out .= "<p class=\"description\">{$args[ 'description' ]}</p>";

      // And print it onto the page
      echo $out;

    }

    public static function render_select_field( $args ) {

      $option_name = $args[ 'option_name' ];
      $value = self::get_option( $option_name, '', false );

      ?>

      <select name="<?php echo "fareharbor_settings[$option_name]" ?>" id="<?php echo $option_name ?>">

        <option value="" <?php selected( '', $value ); ?>></option>

        <?php foreach ( $args[ 'choices' ] as $choice ) { ?>
          <option value="<?php echo $choice ?>" <?php selected( $choice, $value ) ?>><?php echo $choice ?></option>
        <?php } ?>

      </select>

      <?php

      if ( !empty( $args[ 'description' ] ) )
        echo "<p class=\"description\">{$args[ 'description' ]}</p>";

    }

  }
