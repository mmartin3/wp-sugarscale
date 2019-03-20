<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       placeholder
 * @since      1.0.0
 *
 * @package    Wp_Sugarscale
 * @subpackage Wp_Sugarscale/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Sugarscale
 * @subpackage Wp_Sugarscale/includes
 * @author     Matt Martin <electrovert@ymail.com>
 */
class Wp_Sugarscale_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-sugarscale',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
