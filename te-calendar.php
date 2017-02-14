<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://thomas-ebert.design
 * @since             1.0.0
 * @package           Te_Calendar
 *
 * @wordpress-plugin
 * Plugin Name:       Calendar with Templates
 * Plugin URI:        https://thomas-ebert.design/te-calendar
 * Description:       Another calendar plugin, but with a simplistic default template and theme-ability.
 * Version:           0.1.1
 * Author:            Thomas Ebert, te-online.net
 * Author URI:        https://thomas-ebert.design
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       te-calendar
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/te-online/wordpress-plugin--themeable-calendar
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-te-calendar-activator.php
 */
function activate_te_calendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-te-calendar-activator.php';
	Te_Calendar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-te-calendar-deactivator.php
 */
function deactivate_te_calendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-te-calendar-deactivator.php';
	Te_Calendar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_te_calendar' );
register_deactivation_hook( __FILE__, 'deactivate_te_calendar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-te-calendar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_te_calendar() {

	$plugin = new Te_Calendar();
	$plugin->run();

}
run_te_calendar();
