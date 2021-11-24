<?php
/**
 * streetours-fareharbor.php
 *
 * Plugin Name: Fareharbor Booking
 * Plugin URI: http://streetours.com
 * Description: Easily add FareHarbor booking on StreeTours
 * Version: 1.0.0
 * Author: StreeTours
 * Author URI: http://streetours.com
 * Text Domain: streetours-fareharbor
 * Domain Path: /languages
 */

function fareharbor_settings_link($links)
{

    $settings_link = '<a href="admin.php?page=streetours-fareharbor">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'fareharbor_settings_link');


if (!defined('STREETOURS_FAREHARBOR_CORE_DIR')) {
    define('STREETOURS_FAREHARBOR_CORE_DIR', WP_PLUGIN_DIR . '/streetours-fareharbor');
}
define('STREETOURS_FAREHARBOR_FILE', __FILE__);

define('STREETOURS_FAREHARBOR_PLUGIN_URL', plugin_dir_url(STREETOURS_FAREHARBOR_FILE));

class StreetoursFareharbor_Plugin
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
        register_activation_hook(STREETOURS_FAREHARBOR_FILE, array(__CLASS__, 'activate'));
        add_shortcode('st_fh_book', array(__CLASS__, 'st_fh_companies'));
//<div class="load-turitop" data-service="P1" data-lang="en"  data-embed="box"></div>

        require_once ('includes/class.streetours-fareharbor-ajax.php');
        streetours_fareharbor_ajax::instance();
//        $admin_ajax = streetours_fareharbor_ajax::instance();
//
//        add_filter( 'script_loader_tag',
//            'add_attributes_to_script',
//            99, 3 );

//        add_action( 'wp_enqueue_scripts', array( $thiss, 'enqueue_scripts' ), 10 );


    }

    public function add_attributes_to_script( $tag, $handle, $src ) {


        return $tag;

    }

    public static function st_fh_companies($atts)
    {
//        $type = $atts['type'] ?? "list";
        $turitop_product_short_id = $atts['turitop_product_short_id'];
        $fareharbor_item = $atts['fareharbor_item'];
        $partner_company = $atts['partner_company'];
        $pageUrl = plugin_dir_url(__FILE__) . "generator/fh-entire.php?partner_company=$partner_company&fareharbor_item=$fareharbor_item&turitop_product_short_id=$turitop_product_short_id";
        return '<iframe src="' . $pageUrl . '" frameborder="0" width="100%" scrolling="no" style="overflow:hidden;"></iframe>';
//        return '<iframe src="https://app.turitop.com/booking/box/S1114/P1/en/0/0/0/0?ts=1629787648636&returnUrl=aHR0cHM6Ly93d3cuc3RyZWV0b3Vycy5jb20vcHJvZHVjdC93aGlza3ktYW5kLWZvbGtsb3JlLWVkaW5idXJnaC10b3VyLw==&loading=1"></iframe>';
    }

    public static function wp_init()
    {
        load_plugin_textdomain('streetours-fareharbor', null, 'streetours-fareharbor/languages');

        add_action('admin_menu', array(
            __CLASS__,
            'admin_menu'
        ), 40);

        require_once 'core/class-streetours-fareharbor.php';

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
        wp_enqueue_style('aeonsemi-variations-admin-style', STREETOURS_FAREHARBOR_PLUGIN_URL . '/css/admin-style.css', array(), '1.0.0');
        wp_enqueue_style('gp-admin-style');

        // javascript
        wp_register_script('aeonsemi-variations-admin-scripts', STREETOURS_FAREHARBOR_PLUGIN_URL . '/js/admin-scripts.js', array('jquery'), '1.0.0', true);
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
            __('Fareharbor', 'streetours-fareharbor'),
            __('Fareharbor', 'streetours-fareharbor'),
            'manage_options', 'streetours-fareharbor',
            array(
                __CLASS__,
                'streetours_fareharbor_menu_settings'
            ),
            STREETOURS_FAREHARBOR_PLUGIN_URL . '/images/fareharbor.png');
    }

    public static function streetours_fareharbor_menu_settings()
    {
        // if submit
        if (isset($_POST ["streetours_fareharbor_settings"]) && wp_verify_nonce($_POST ["streetours_fareharbor_settings"], "streetours_fareharbor_settings")) {
            update_option("streetours-fareharbor-app-key", sanitize_text_field($_POST ["streetours_fareharbor_app_key"]));
            update_option("streetours-fareharbor-user-key", sanitize_text_field($_POST ["streetours_fareharbor_user_key"]));
//            update_option("streetours_fareharbor_company", sanitize_text_field($_POST ["streetours_fareharbor_company"]));
            update_option("fareharbor_root_url", sanitize_text_field($_POST ["fareharbor_root_url"]));
        }

        ?>
        <div class="st-head">
            <h2><?php echo __('StreeTours', 'streetours-fareharbor'); ?><small class="st-opacity">&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo __('Fareharbor', 'streetours-fareharbor'); ?></small>
            </h2>
        </div>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="st-key-form">
                <h3><?php echo __('Authentication', 'streetours-fareharbor'); ?></h3>
                <p>
                    <label><?php echo __("APP-KEY", 'streetours-fareharbor'); ?>&nbsp;&nbsp;:</label>
                    <input class="st-key-in" name="streetours_fareharbor_app_key"
                           value="<?php echo get_option('streetours-fareharbor-app-key'); ?>" size="100"/>
                </p>
                <p>
                    <label><?php echo __("USER-KEY", 'aeonsemi-variations'); ?>:</label>
                    <input class="st-key-in" name="streetours_fareharbor_user_key"
                           value="<?php echo get_option('streetours-fareharbor-user-key'); ?>" size="100"/>
                </p>
                <h3><?php echo __('Settings', 'streetours-fareharbor'); ?></h3>

                <p>
                    <label><?php echo __("Root URL", 'aeonsemi-variations'); ?>:</label>
                    <input class="st-key-in" name="fareharbor_root_url"
                           value="<?php echo get_option('fareharbor_root_url'); ?>" size="100"/>
                </p>
            </div>
            <?php wp_nonce_field('streetours_fareharbor_settings', 'streetours_fareharbor_settings') ?>
            <div class="st-m-3">
                <button type="submit"
                        class="button button-primary button-large"><?php echo __('Save', 'streetours-fareharbor'); ?></button>
            </div>
        </form>
        <?php
    }
}

StreetoursFareharbor_Plugin::init();
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordpress-custom-plugin-activator.php
 */
function activate_streetours_fareharbor()
{
    require_once plugin_dir_path(__FILE__) . 'core/class-streetours-fareharbor-activator.php';
    Streetours_Fareharbor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordpress-custom-plugin-deactivator.php
 */

function deactivate_streetours_fareharbor()
{
    require_once plugin_dir_path(__FILE__) . 'core/class-streetours-fareharbor-deactivator.php';
    Streetours_Fareharbor_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_streetours_fareharbor');
register_deactivation_hook(__FILE__, 'deactivate_streetours_fareharbor');
