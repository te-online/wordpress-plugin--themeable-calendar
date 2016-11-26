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
		return date_i18n( $format, $begin );
	}

	/**
	 * Get the weekday of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_day() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'l', $begin );
	}

	/**
	 * Get the short form date of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_date() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'j.n.', $begin );
	}

	/**
	 * Get the year of the event begin.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_begin_year() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'Y', $begin );
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
			return __( "ganztägig", 'te-calendar' );
		}

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'H.i', $begin ) . _x( " Uhr", 'te-calendar', "Wie in 12.00 Uhr" );
	}

	/**
	 * Get the event end in a given format.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end( $format ) {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		return date_i18n( $format, $end );
	}

	/**
	 * Get the weekday of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_day() {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		return date_i18n( 'l', $end );
	}

	/**
	 * Get the short form date of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_date() {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		return date_i18n( 'j.n.', $end );
	}

	/**
	 * Get the year of the event end.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_end_year() {
		global $post;

		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		return date_i18n( 'Y', $end );
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
		return date_i18n( 'H.i', $end ) . _x( " Uhr", 'te-calendar', "Wie in 12.00 Uhr" );
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

?>