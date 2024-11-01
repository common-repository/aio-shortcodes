<?php
 
 /**
 * Plugin Name: AIO Shortcodes - 100+ WordPress Shortcodes List For SEO
 * Plugin URI: https://aioshortcodes.com/
 * Description: Elevate your WordPress experience with AIO Shortcodes - the powerhouse plugin offering 100+ shortcodes list. Seamlessly automate your website's SEO, all without touching a single line of code. Explore dynamic possibilities for posts, pages, widgets etc.
 * Version: 1.2.11.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: AIO Shortcodes
 * Author URI: https://aioshortcodes.com/
 * License: GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: aio-shortcodes
 * Domain Path: /languages
 *
 * @package   AIO Shortcodes
 * @link      https://aioshortcodes.com/
 * @since     1.0
 */

if (!defined("WPINC")) {
    die();
}

define("AIO_SHORTCODES_VERSION", "1.2.11.2");



/**
 * Custom shortcode to display shortcode name along with its output.
 * Example usage: [sn name="aio_date" go="x"] (Output: [date go="x"])
 */
function display_aio_sn_shortcode($atts, $content = null) {
    if (empty($atts['name'])) {
        // If the 'name' attribute is missing, display an error message.
        return '<span style="color: red;">Error: Missing "name" attribute in the shortcode.</span>';
    }

    $shortcode = '[' . $atts['name'];

    // Add any attributes to the shortcode
    foreach ($atts as $attribute => $value) {
        if ($attribute !== 'name') {
            $shortcode .= ' ' . $attribute . '="' . $value . '"';
        }
    }

    $shortcode .= ']';
   
    if ($content) {
        // If the shortcode has inner content, display it.
        $output = $content;
    } else {
        // Otherwise, display the actual shortcode output with the 'aio-sn' class.
        $output = '<span class="aio-sn">' . $shortcode . '</span>';
    }

    return $output;
}

add_shortcode('sn', 'display_aio_sn_shortcode');




/**
 * Add CSS class 'aio-style' to all shortcodes.
 */
function aiosc_add_shortcode_css_class($content) {
    // Add the 'dateupdater-style' class to all shortcodes
    $pattern = '/\[([^\]]+)\]/';
    $replacement = '<span class="aio-style">[$1]</span>';
    return preg_replace($pattern, $replacement, $content);
}

add_filter('the_content', 'aiosc_add_shortcode_css_class');



// Include all PHP files in a given folder and its subdirectories
function include_files_recursively($folder) {
    $files = glob($folder . '/*.php');
    foreach ($files as $file) {
        require_once $file;
    }

    $subdirectories = glob($folder . '/*', GLOB_ONLYDIR);
    foreach ($subdirectories as $subdirectory) {
        include_files_recursively($subdirectory);
    }
}

// Specify the root folder
$shortcode_root_folder = plugin_dir_path(__FILE__) . "includes/shortcodes/";

// Include all PHP files from the root folder and its subdirectories
include_files_recursively($shortcode_root_folder);





require_once plugin_dir_path(__FILE__) . "includes/custom-plugins/rankmath.php";


// Include shortcode support files
require_once plugin_dir_path(__FILE__) . "admin/aiosc-support.php";
require_once plugin_dir_path(__FILE__) . "admin/main-settings.php";
require_once plugin_dir_path(__FILE__) . "includes/options/download-zip.php";
require_once plugin_dir_path(__FILE__) . "includes/options/duplicator.php";
require_once plugin_dir_path(__FILE__) .'/includes/options/custom-css.php';
require_once plugin_dir_path(__FILE__) .'/includes/options/login-url.php';
require_once plugin_dir_path(__FILE__) .'/includes/options/search-excluder.php';

// Shortcodes Settings In Plugins
add_filter("plugin_action_links_aio-shortcodes/aio-shortcodes.php", "aiosc_settings_link");

function aiosc_settings_link($links)
{
    // Create the "Shortcode List" link in bold.
    $shortcode_list_link =
        '<a style="font-weight: bold;" href="https://aioshortcodes.com/shortcodes/" target="_blank">' .
        __("Shortcodes List") .
        "</a>";

    // Create the "Settings" link.
    $settings_link =
        '<a style="font-weight: 500;" href="' .
        admin_url('admin.php?page=aio_shortcodes_settings') . // Adjust the slug to the actual settings page URL
        '">' .
        __("Settings") .
        "</a>";

    // Find the position of the "Deactivate" link in the $links array.
    $deactivate_index = array_search("deactivate", array_keys($links));

    // Insert the "Shortcode List" link right before the "Deactivate" link.
    if (false !== $deactivate_index) {
        array_splice($links, $deactivate_index, 0, $settings_link);
        array_unshift($links, $shortcode_list_link);
    }

    return $links;
}


function enqueue_admin_styles() {
    wp_enqueue_script('aio-admin-script', plugin_dir_url(__FILE__). 'assets/js/aio-admin-js.js', array('jquery'), null);
    wp_enqueue_style('aio-admin-style', plugin_dir_url(__FILE__) . 'assets/css/aio-admin-style.css', array(), 'all');
   
}
add_action('admin_enqueue_scripts', 'enqueue_admin_styles');


/**
 * Activate the plugin.
 */
function aio_plugin_activate() { 
	add_option('aio_login_url_slug');
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'aio_plugin_activate' );


/**
 * Deactivation hook.
 */
function aio_plugin_deactivate() {
	delete_option('aio_login_url_slug');
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'aio_plugin_deactivate' );

add_action( 'customize_preview_init', 'aiosc_css_live_preview' );
/**
 * Add our live preview.
 *
 * @since 1.0
 */
function aiosc_css_live_preview() {
	wp_enqueue_script( 'aio-css-live-preview', trailingslashit( plugin_dir_url(__FILE__) ) . 'assets/js/live-preview.js', array( 'customize-preview' ), null, true );
}