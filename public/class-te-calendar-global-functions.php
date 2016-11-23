<?php
class Te_Calendar_Global_Functions {
	/**
	 * Get the weekday of the event.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_day() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'l', $begin->format('U') );
	}

	// static function the_event_day() {
	// 	echo $this->get_event_day();
	// }

	/**
	 * Get the short form date of the event.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_date() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'j.n.', $begin->format('U') );
	}

	// static function the_event_date() {
	// 	echo $this->get_event_date();
	// }

	/**
	 * Get the year of the event.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_year() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'Y', $begin->format('U') );
	}

	// static function the_event_year() {
	// 	echo $this->get_event_year();
	// }

	/**
	 * Get the time of the event.
	 *
	 * @since 		1.0.0
	 */
	static function get_event_time() {
		global $post;

		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		return date_i18n( 'H.i', $begin->format('U') ) . _x( " Uhr", 'te-calendar', "Wie in 12.00 Uhr" );
	}

	// static function the_event_time() {
	// 	echo $this->get_event_time();
	// }

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

	// static function the_event_location() {
	// 	echo $this->get_event_location();
	// }
}

// $te_calendar_global_functions = new Te_Calendar_Global_Functions();

if( !function_exists('get_event_day') ) { function get_event_day() { return Te_Calendar_Global_Functions::get_event_day(); } }
if( !function_exists('the_event_day') ) { function the_event_day() { echo Te_Calendar_Global_Functions::get_event_day(); } }

if( !function_exists('get_event_date') ) { function get_event_date() { return Te_Calendar_Global_Functions::get_event_date(); } }
if( !function_exists('the_event_date') ) { function the_event_date() { echo Te_Calendar_Global_Functions::get_event_date(); } }

if( !function_exists('get_event_year') ) { function get_event_year() { return Te_Calendar_Global_Functions::get_event_year(); } }
if( !function_exists('the_event_year') ) { function the_event_year() { echo Te_Calendar_Global_Functions::get_event_year(); } }

if( !function_exists('get_event_time') ) { function get_event_time() { return Te_Calendar_Global_Functions::get_event_time(); } }
if( !function_exists('the_event_time') ) { function the_event_time() { echo Te_Calendar_Global_Functions::get_event_time(); } }

if( !function_exists('get_event_location') ) { function get_event_location() { return Te_Calendar_Global_Functions::get_event_location(); } }
if( !function_exists('the_event_location') ) { function the_event_location() { echo Te_Calendar_Global_Functions::get_event_location(); } }

?>