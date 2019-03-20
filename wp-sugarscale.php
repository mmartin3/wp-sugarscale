<?php
/*
 * Plugin Name:       WP-Sugarscale
 * Plugin URI:        http://blog.sugarscale.co/wordpress-plugin/
 * Description:       Add a dropdown with 50+ sweeteners to your recipes so users can substitute their sweetener of choice.
 * Version:           1.0.0
 * Author:            Matt Martin
 * Author URI:        http://sugarscale.co/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-sugarscale
 * Domain Path:       /languages
 */

include_once plugin_dir_path( __FILE__ )."includes/WPSugarscale.php";

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-sugarscale-activator.php
 */
function activate_wp_sugarscale() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-sugarscale-activator.php';
	Wp_Sugarscale_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-sugarscale-deactivator.php
 */
function deactivate_wp_sugarscale() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-sugarscale-deactivator.php';
	Wp_Sugarscale_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_sugarscale' );
register_deactivation_hook( __FILE__, 'deactivate_wp_sugarscale' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-sugarscale.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_sugarscale() {

	$plugin = new Wp_Sugarscale();
	$plugin->run();

}
run_wp_sugarscale();

function sugarscale_handler($atts)
{
	$wp_sugarscale = new WPSugarscale($atts);
	return $wp_sugarscale->widget();
}

add_shortcode("sugarscale", "sugarscale_handler");

function enqueue_plugin_scripts($plugin_array)
{
    $plugin_array["wp_sugarscale_button"] =  plugin_dir_url(__FILE__)."includes/wp-sugarscale_button.js";
    return $plugin_array;
}

add_filter("mce_external_plugins", "enqueue_plugin_scripts");

function register_buttons_editor($buttons)
{
    array_push($buttons, "sugarscale");
    return $buttons;
}

add_filter("mce_buttons", "register_buttons_editor");

function sugarscale_dialog_contents()
{
	$wp_sugarscale = new WPSugarscale();
	echo $wp_sugarscale->dialog();
}

add_action("after_wp_tiny_mce", "sugarscale_dialog_contents");
?>