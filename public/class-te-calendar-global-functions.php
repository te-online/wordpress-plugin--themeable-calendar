<?php
class Te_Calendar_Global_Functions {
	/**
	 * Get the event begin in a given format.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin( $format ) {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return locale_date_i18n( $format, $begin );
	}

	/**
	 * Get the weekday of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_day() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return locale_date_i18n( 'l', $begin );
	}

	/**
	 * Get the short form date of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_date() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return locale_date_i18n( 'j.n.', $begin );
	}

	/**
	 * Get the year of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_year() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return locale_date_i18n( 'Y', $begin );
	}

	/**
	 * Get the time of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_time() {
		global $post;

		$allday = get_post_meta( $post->ID, 'tecal_events_allday', true );
		if( $allday ) {
			return __( "all day", 'te-calendar' );
		}

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return sprintf( _x( '%s', 'Time. Other languages use some kind of unit for time like "12.00 Uhr"', 'te-calendar' ), locale_date_i18n( 'H.i', $begin ) );
	}

	/**
	 * Get the event end in a given format.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end( $format ) {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		$end = Te_Calendar_Global_Functions::maybe_allday_subtract_end( $post->ID, $end );

		return locale_date_i18n( $format, $end );
	}

	/**
	 * Get the weekday of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_day() {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		$end = Te_Calendar_Global_Functions::maybe_allday_subtract_end( $post->ID, $end );

		return locale_date_i18n( 'l', $end );
	}

	/**
	 * Get the short form date of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_date() {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		$end = Te_Calendar_Global_Functions::maybe_allday_subtract_end( $post->ID, $end );

		return locale_date_i18n( 'j.n.', $end );
	}

	/**
	 * Get the year of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_year() {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		$end = Te_Calendar_Global_Functions::maybe_allday_subtract_end( $post->ID, $end );

		return locale_date_i18n( 'Y', $end );
	}

	/**
	 * Get the time of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_time() {
		global $post;

		$allday = get_post_meta( $post->ID, 'tecal_events_allday', true );
		if( $allday ) {
			return "";
		}

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		return sprintf( _x( '%s', 'Time. Other languages use some kind of unit for time like "12.00 Uhr"', 'te-calendar' ), locale_date_i18n( 'H.i', $end ) );
	}

	/**
	 * Get the location of the event.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_location() {
		global $post;

		$location = get_post_meta( $post->ID, 'tecal_events_location', true );
		return $location;
	}

	/**
	 * Know if the event is an allday-event.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_is_allday() {
		global $post;

		$allday = get_post_meta( $post->ID, 'tecal_events_allday', true );
		if( $allday ) {
			return true;
		}

		return false;
	}

	/**
	 * Know if the event has an end specified.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_has_end() {
		global $post;

		$has_end = get_post_meta( $post->ID, 'tecal_events_has_end', true );
		if( $has_end ) {
			return true;
		}

		return false;
	}

	/**
	 * Get locale AND timezone specific date string.
	 * see https://wordpress.stackexchange.com/a/135049/113600
	 *
	 * @since 		0.3.0
	 */
	static function locale_date_i18n( $format, $timestamp ) {
    $timezone_str = get_option( 'timezone_string' ) ?: 'UTC';
    $timezone = new \DateTimeZone( $timezone_str );

    // The date in the local timezone.
    $date = new \DateTime( null, $timezone );
    $date->setTimestamp( $timestamp );
    $date_str = $date->format( 'Y-m-d H:i:s' );

    // Pretend the local date is UTC to get the timestamp
    // to pass to date_i18n().
    $utc_timezone = new \DateTimeZone( 'UTC' );
    $utc_date = new \DateTime( $date_str, $utc_timezone );
    $timestamp = $utc_date->getTimestamp();

    return date_i18n( $format, $timestamp, true );
	}

	/**
	 * Get the actual end of an allday event
	 *
	 * @since			0.3.7
	 */
	static function maybe_allday_subtract_end( $post_id, $end ) {
		// Subtract one day for allday events, because they are saved as ending after 24 hours.
		// But only do this if the time of the start and end date are the same.
		$allday = get_post_meta( $post_id, 'tecal_events_allday', true );
		$begin = get_post_meta( $post_id, 'tecal_events_begin', true );
		if( $allday && locale_date_i18n( 'H:i', $begin ) === locale_date_i18n( 'H:i', $end ) ) {
			$end = strtotime( '-1 day', $end );
		}

		return $end;
	}
}

// Event begin functions.

if( !function_exists('get_event_begin') ) { function get_event_begin( $format = "d-m-Y" ) { return Te_Calendar_Global_Functions::get_event_begin( $format ); } }
if( !function_exists('the_event_begin') ) { function the_event_begin( $format = "d-m-Y") { echo Te_Calendar_Global_Functions::get_event_begin( $format ); } }

if( !function_exists('get_event_begin_day') ) { function get_event_begin_day() { return Te_Calendar_Global_Functions::get_event_begin_day(); } }
if( !function_exists('the_event_begin_day') ) { function the_event_begin_day() { echo Te_Calendar_Global_Functions::get_event_begin_day(); } }

if( !function_exists('get_event_begin_date') ) { function get_event_begin_date() { return Te_Calendar_Global_Functions::get_event_begin_date(); } }
if( !function_exists('the_event_begin_date') ) { function the_event_begin_date() { echo Te_Calendar_Global_Functions::get_event_begin_date(); } }

if( !function_exists('get_event_begin_year') ) { function get_event_begin_year() { return Te_Calendar_Global_Functions::get_event_begin_year(); } }
if( !function_exists('the_event_begin_year') ) { function the_event_begin_year() { echo Te_Calendar_Global_Functions::get_event_begin_year(); } }

if( !function_exists('get_event_begin_time') ) { function get_event_begin_time() { return Te_Calendar_Global_Functions::get_event_begin_time(); } }
if( !function_exists('the_event_begin_time') ) { function the_event_begin_time() { echo Te_Calendar_Global_Functions::get_event_begin_time(); } }

// Event end functions.

if( !function_exists('get_event_end') ) { function get_event_end( $format = "d-m-Y" ) { return Te_Calendar_Global_Functions::get_event_end( $format ); } }
if( !function_exists('the_event_end') ) { function the_event_end( $format = "d-m-Y") { echo Te_Calendar_Global_Functions::get_event_end( $format ); } }

if( !function_exists('get_event_end_day') ) { function get_event_end_day() { return Te_Calendar_Global_Functions::get_event_end_day(); } }
if( !function_exists('the_event_end_day') ) { function the_event_end_day() { echo Te_Calendar_Global_Functions::get_event_end_day(); } }

if( !function_exists('get_event_end_date') ) { function get_event_end_date() { return Te_Calendar_Global_Functions::get_event_end_date(); } }
if( !function_exists('the_event_end_date') ) { function the_event_end_date() { echo Te_Calendar_Global_Functions::get_event_end_date(); } }

if( !function_exists('get_event_end_year') ) { function get_event_end_year() { return Te_Calendar_Global_Functions::get_event_end_year(); } }
if( !function_exists('the_event_end_year') ) { function the_event_end_year() { echo Te_Calendar_Global_Functions::get_event_end_year(); } }

if( !function_exists('get_event_end_time') ) { function get_event_end_time() { return Te_Calendar_Global_Functions::get_event_end_time(); } }
if( !function_exists('the_event_end_time') ) { function the_event_end_time() { echo Te_Calendar_Global_Functions::get_event_end_time(); } }

// Event details function.

if( !function_exists('get_event_location') ) { function get_event_location() { return Te_Calendar_Global_Functions::get_event_location(); } }
if( !function_exists('the_event_location') ) { function the_event_location() { echo Te_Calendar_Global_Functions::get_event_location(); } }

if( !function_exists('get_event_is_allday') ) { function get_event_is_allday() { return Te_Calendar_Global_Functions::get_event_is_allday(); } }

if( !function_exists('get_event_has_end') ) { function get_event_has_end() { return Te_Calendar_Global_Functions::get_event_has_end(); } }

// Helper functions

if( !function_exists('locale_date_i18n') ) { function locale_date_i18n($format, $timestamp) { return Te_Calendar_Global_Functions::locale_date_i18n($format, $timestamp); } }

?>