<?php

/**
 * Static helpers used all over the plugin.
 *
 * @link       https://thomas-ebert.design
 * @since      0.3.0
 *
 * @package    Te_Calendar
 * @subpackage Te_Calendar/admin
 */

class Te_Calendar_Static_Helpers {
	/**
	 * Checks if an event is in an external calendar
	 * Returns boolean.
	 *
	 * @since 		0.3.0
	 */
	public static function is_event_external( $event_id ) {
		// Get calendar of event
		$calendars = wp_get_post_terms( $event_id, 'tecal_calendars' );
		// Get calendars slugs.
		$calendar_slugs = [];
		foreach( $calendars as $calendar ) {
			$calendar_slugs[] = $calendar->slug;
		}
		// Get all external calendars
		$external_calendars = get_terms(array(
	    'taxonomy' => 'tecal_calendars',
	    'hide_empty' => false,
		));
		// Find out if this event has one or more external calendars
		$is_external = false;
		foreach( $external_calendars as $calendar ) {
	    $ical_feed_url = get_term_meta( $calendar->term_id, 'tecal_calendar_ical', true );
	    $calendar_is_updating = get_term_meta( $calendar->term_id, 'tecal_calendar_is_updating', true );
	    if( $ical_feed_url && !empty( $ical_feed_url ) && !$calendar_is_updating === true ) {
	    	if( array_search( $calendar->slug, $calendar_slugs ) !== false ) {
	    		$is_external = true;
	    		break;
	    	}
	    }
		}

		return $is_external;
	}
}