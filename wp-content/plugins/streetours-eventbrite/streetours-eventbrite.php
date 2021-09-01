<?php
/**
 * streetours-eventbrite.php
 *
 * Plugin Name: Eventbrite Booking
 * Plugin URI: http://streetours.com
 * Description: Easily add Eventbrite booking on StreeTours
 * Version: 1.0.0
 * Author: StreeTours
 * Author URI: http://streetours.com
 * Text Domain: streetours-eventbrite
 * Domain Path: /languages
 */

function eventbrite_settings_link($links)
{

    $settings_link = '<a href="admin.php?page=streetours-eventbrite">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'eventbrite_settings_link');


if (!defined('STREETOURS_EVENTBRITE_CORE_DIR')) {
    define('STREETOURS_EVENTBRITE_CORE_DIR', WP_PLUGIN_DIR . '/streetours-eventbrite');
}
define('STREETOURS_EVENTBRITE_FILE', __FILE__);

define('STREETOURS_EVENTBRITE_PLUGIN_URL', plugin_dir_url(STREETOURS_EVENTBRITE_FILE));

class StreetoursEventbrite_Plugin
{

    public static $notices = array();

    public static function init()
    {
        add_action('init', array(
            __CLASS__,
            'wp_init'
        ));
        add_action('admin_notices', array(
            __CLASS__,
            'admin_notices'
        ));
        add_action('admin_init', array(__CLASS__, 'admin_init'));
        register_activation_hook(STREETOURS_EVENTBRITE_FILE, array(__CLASS__, 'activate'));
        add_shortcode('st_eb_book', array(__CLASS__, 'st_eb_companies'));
//<div class="load-turitop" data-service="P1" data-lang="en"  data-embed="box"></div>

        require_once ('includes/class.streetours-eventbrite-ajax.php');
        streetours_eventbrite_ajax::instance();
//        $admin_ajax = streetours_eventbrite_ajax::instance();
//
//        add_filter( 'script_loader_tag',
//            'add_attributes_to_script',
//            99, 3 );

//        add_action( 'wp_enqueue_scripts', array( $thiss, 'enqueue_scripts' ), 10 );


    }

    public function add_attributes_to_script( $tag, $handle, $src ) {


        return $tag;

    }

    public static function st_eb_companies($atts)
    {
        $type = $atts['type'] ?? "list";
        $turitop_product_short_id = $atts['turitop_product_short_id'] ?? '';
        $eventbrite_item = $attrs['eventbrite_item'] ?? '183';
        $pageUrl = plugin_dir_url(__FILE__) . "generator/fh-entire.php?type=$type&eventbrite_item=$eventbrite_item&turitop_product_short_id=$turitop_product_short_id";
        return '<iframe src="' . $pageUrl . '" frameborder="0" width="100%" scrolling="no" style="overflow:hidden;"></iframe>';
//        return '<iframe src="https://app.turitop.com/booking/box/S1114/P1/en/0/0/0/0?ts=1629787648636&returnUrl=aHR0cHM6Ly93d3cuc3RyZWV0b3Vycy5jb20vcHJvZHVjdC93aGlza3ktYW5kLWZvbGtsb3JlLWVkaW5idXJnaC10b3VyLw==&loading=1"></iframe>';
    }

    public static function wp_init()
    {
        load_plugin_textdomain('streetours-eventbrite', null, 'streetours-eventbrite/languages');

        add_action('admin_menu', array(
            __CLASS__,
            'admin_menu'
        ), 40);

        require_once 'core/class-streetours-eventbrite.php';

        // styles & javascript
        add_action('admin_enqueue_scripts', array(
            __CLASS__,
            'admin_enqueue_scripts'
        ));
    }

    public static function admin_init()
    {

    }

    public static function admin_enqueue_scripts($page)
    {
        // css
        wp_enqueue_style('aeonsemi-variations-admin-style', STREETOURS_EVENTBRITE_PLUGIN_URL . '/css/admin-style.css', array(), '1.0.0');
        wp_enqueue_style('gp-admin-style');

        // javascript
        wp_register_script('aeonsemi-variations-admin-scripts', STREETOURS_EVENTBRITE_PLUGIN_URL . '/js/admin-scripts.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('aeonsemi-variations-admin-scripts');
    }

    public static function admin_notices()
    {
        if (!empty (self::$notices)) {
            foreach (self::$notices as $notice) {
                echo $notice;
            }
        }
    }

    /**
     * Adds the admin section.
     */
    public static function admin_menu()
    {
        $admin_page = add_menu_page(
            __('Eventbrite', 'streetours-eventbrite'),
            __('Eventbrite', 'streetours-eventbrite'),
            'manage_options', 'streetours-eventbrite',
            array(
                __CLASS__,
                'streetours_eventbrite_menu_settings'
            ),
            STREETOURS_EVENTBRITE_PLUGIN_URL . '/images/eventbrite.png');
    }

    public static function streetours_eventbrite_menu_settings()
    {
        // if submit
        if (isset($_POST ["streetours_eventbrite_settings"]) && wp_verify_nonce($_POST ["streetours_eventbrite_settings"], "streetours_eventbrite_settings")) {
            update_option("streetours-eventbrite-app-key", sanitize_text_field($_POST ["streetours_eventbrite_app_key"]));
            update_option("streetours-eventbrite-user-key", sanitize_text_field($_POST ["streetours_eventbrite_user_key"]));
            update_option("streetours_eventbrite_company", sanitize_text_field($_POST ["streetours_eventbrite_company"]));
            update_option('eventbrite_private_token', sanitize_text_field($_POST ["eventbrite_private_token"]));
        }

        ?>
        <div class="st-head">
            <h2><?php echo __('StreeTours', 'streetours-eventbrite'); ?><small class="st-opacity">&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo __('Eventbrite', 'streetours-eventbrite'); ?></small>
            </h2>
        </div>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="st-key-form">
                <h3><?php echo __('Authentication', 'streetours-eventbrite'); ?></h3>
                <p>
                    <label><?php echo __("Private Token", 'streetours-eventbrite'); ?>&nbsp;&nbsp;:</label>
                    <input class="st-key-in" name="eventbrite_private_token"
                           value="<?php echo get_option('eventbrite_private_token'); ?>" size="100"/>
                </p>

<!--                <p>-->
<!--                    <label>--><?php //echo __("APP-KEY", 'streetours-eventbrite'); ?><!--&nbsp;&nbsp;:</label>-->
<!--                    <input class="st-key-in" name="streetours_eventbrite_app_key"-->
<!--                           value="--><?php //echo get_option('streetours-eventbrite-app-key'); ?><!--" size="100"/>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label>--><?php //echo __("USER-KEY", 'aeonsemi-variations'); ?><!--:</label>-->
<!--                    <input class="st-key-in" name="streetours_eventbrite_user_key"-->
<!--                           value="--><?php //echo get_option('streetours-eventbrite-user-key'); ?><!--" size="100"/>-->
<!--                </p>-->
<!--                <h3>--><?php //echo __('Settings', 'streetours-eventbrite'); ?><!--</h3>-->
<!--                <p>-->
<!--                    <label>--><?php //echo __("Company", 'aeonsemi-variations'); ?><!--:</label>-->
<!--                    <select class="st-key-in" name="streetours_eventbrite_company" size="100">-->
<!--                        <option value="bodyglove">Bodyg Glove (SandBox)</option>-->
<!--                    </select>-->
<!--                </p>-->
            </div>
            <?php wp_nonce_field('streetours_eventbrite_settings', 'streetours_eventbrite_settings') ?>
            <div class="st-m-3">
                <button type="submit"
                        class="button button-primary button-large"><?php echo __('Save', 'streetours-eventbrite'); ?></button>
            </div>
        </form>
        <?php
    }
}

StreetoursEventbrite_Plugin::init();
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordpress-custom-plugin-activator.php
 */
function activate_streetours_eventbrite()
{
    require_once plugin_dir_path(__FILE__) . 'core/class-streetours-eventbrite-activator.php';
    Streetours_Eventbrite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordpress-custom-plugin-deactivator.php
 */

function deactivate_streetours_eventbrite()
{
    require_once plugin_dir_path(__FILE__) . 'core/class-streetours-eventbrite-deactivator.php';
    Streetours_Eventbrite_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_streetours_eventbrite');
register_deactivation_hook(__FILE__, 'deactivate_streetours_eventbrite');
