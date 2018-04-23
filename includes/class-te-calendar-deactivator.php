<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://thomas-ebert.design
 * @since      0.1.0
 *
 * @package    Te_Calendar
 * @subpackage Te_Calendar/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Te_Calendar
 * @subpackage Te_Calendar/includes
 * @author     Thomas Ebert, te-online.net <thomas.ebert@te-online.net>
 */
class Te_Calendar_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
		// Deactive WP Cron
		$timestamp = wp_next_scheduled( 'tecal_fetch_from_external_feeds' );
		wp_unschedule_event( $timestamp, 'tecal_fetch_from_external_feeds' );
	}

}
