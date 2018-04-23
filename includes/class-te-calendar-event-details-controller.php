<?php
class Te_Calendar_Event_Details_Controller {
	/**
	 * Register a new metabox for the event details.
	 *
	 * @since 		0.1.0
	 */
	public function event_metaboxes_register() {
		add_meta_box( 'event-details', __('Eventdetails', 'te-calendar'), array( $this, 'event_details_metabox'), 'tecal_events', 'normal', 'high' );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages_filter' ) );
	}

	/**
	 * Show the new metabox for event details.
	 *
	 * @since 		0.1.0
	 */
	public function event_details_metabox( $post ) {
		if( Te_Calendar_Static_Helpers::is_event_external( $post->ID ) ) {
			echo '<div class="message error"><p><em>' . __( 'This event is read-only, because it comes from an external calendar. Please edit the event inside the external calendar.</em>', 'te-calendar' ) . '</em></p></div>';
		}

		$today = date_create();
		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		if( ( date_create_from_format( 'U', $begin ) instanceof DateTime ) != true ) {
			$begin = $today->format('U');
		}
		$begin_string = locale_date_i18n( 'Y-m-d', $begin );
		$begin_time = ( $begin == $today->format('U') ) ? locale_date_i18n( 'H:00', $begin ) : locale_date_i18n( 'H:i', $begin );
		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		if( ( date_create_from_format( 'U', $end ) instanceof DateTime ) != true ) {
			$end = $today->format('U');
		}
		$end_string = locale_date_i18n( 'Y-m-d', $end );
		$end_time = ( $end == $today->format('U') ) ? locale_date_i18n( 'H:00', ( $end + 60 * 60 ) ) : locale_date_i18n( 'H:i', $end );
		$allday = get_post_meta( $post->ID, 'tecal_events_allday', true );
		$has_end = get_post_meta( $post->ID, 'tecal_events_has_end', true );
		$location = get_post_meta( $post->ID, 'tecal_events_location', true );
		$repeat_mode = get_post_meta( $post->ID, 'tecal_events_repeat_mode', true );

		// Add nonce for security and authentication.
    wp_nonce_field( 'tecal_events_legacy_edit_nonce_action', 'tecal_events_legacy_edit_nonce' );
    ?>

    <input type="hidden" id="tecal_legacy_event_edit" name="tecal_legacy_event_edit" />

    <p><label for="tecal_events_location"><?php _e('Location:', 'te-calendar'); ?></label>
		<input class="widefat" id="tecal_events_location" name="tecal_events_location" type="text" value="<?php echo esc_attr($location); ?>" /></p>

    <p><label for="tecal_events_allday"><?php _e('All day:', 'te-calendar'); ?></label>
		<input class="widefat" id="tecal_events_allday" name="tecal_events_allday" type="checkbox" <?php echo ($allday !== "1") ? "" : "checked='checked'" ; ?> /></p>

    <p><label for="tecal_events_begin"><?php _e('Begin:', 'te-calendar'); ?></label>
		<input class="widefat" id="tecal_events_begin" name="tecal_events_begin" type="date" value="<?php echo esc_attr($begin_string); ?>" />
		<input class="widefat" id="tecal_events_begin_time" name="tecal_events_begin_time" type="time" value="<?php echo esc_attr($begin_time); ?>" /></p>

		<p><label for="tecal_events_has_end"><?php _e('Specify an end:', 'te-calendar'); ?></label>
		<input class="widefat" id="tecal_events_has_end" name="tecal_events_has_end" type="checkbox" <?php echo ($has_end === "1") ? "checked='checked'" : "" ; ?> /></p>

		<p><label for="tecal_events_end"><?php _e('End:', 'te-calendar'); ?></label>
		<input class="widefat" id="tecal_events_end" name="tecal_events_end" type="date" value="<?php echo esc_attr($end_string); ?>" />
		<input class="widefat" id="tecal_events_end_time" name="tecal_events_end_time" type="time" value="<?php echo esc_attr($end_time); ?>" /></p>

    <?php
	}

	/**
	 * Save the event details.
	 *
	 * @since 		0.1.0
	 */
	public function event_details_save( $post_id ) {
    // Add nonce for security and authentication.
    $nonce_name   = isset( $_POST['tecal_events_legacy_edit_nonce'] ) ? $_POST['tecal_events_legacy_edit_nonce'] : '';
    $nonce_action = 'tecal_events_legacy_edit_nonce_action';

    // Check if nonce is set.
    if ( ! isset( $nonce_name ) ) {
      return;
    }

    // Check if nonce is valid.
    if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
      return;
    }

    // Check if user has permissions to save data.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }

    // Check if not an autosave.
    if ( wp_is_post_autosave( $post_id ) ) {
      return;
    }

    // Check if not a revision.
    if ( wp_is_post_revision( $post_id ) ) {
      return;
    }

    if( Te_Calendar_Static_Helpers::is_event_external( $post_id ) ) {
    	add_filter( 'redirect_post_location', array( $this, 'redirect_post_location_filter' ), 99 );
    	return;
    }

    // OK, we're authenticated: we need to find and save the data
    $post = get_post( $post_id );
    if ( $post->post_type == 'tecal_events' ) {
    	if( isset( $_POST['tecal_events_begin'] ) && isset( $_POST['tecal_events_begin_time'] ) ) {
    		$begin_date = date_create_from_format( "Y-m-d H:i", ( sanitize_text_field( $_POST['tecal_events_begin'] ) . " " . sanitize_text_field( $_POST['tecal_events_begin_time'] ) ) );
    		$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
    		$begin_date->setTimezone( new DateTimeZone( 'UTC' ) );
    		update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
    	} else if( isset( $_POST['tecal_events_begin'] ) ) {
    		// If we don't have a time (all day), save anyways with arbitrary time.
    		$begin_date = date_create_from_format( "Y-m-d", ( sanitize_text_field( $_POST['tecal_events_begin'] ) ) );
    		$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
    		$begin_date->setTimezone( new DateTimeZone( 'UTC' ) );
    		update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
    	}

    	if( isset( $_POST['tecal_events_end'] ) && isset( $_POST['tecal_events_end_time'] ) ) {
	    	$end_date = date_create_from_format( "Y-m-d H:i", ( sanitize_text_field( $_POST['tecal_events_end'] ) . " " . sanitize_text_field( $_POST['tecal_events_end_time'] ) ) );
	    	$end_date = ( $end_date == false ) ? date_create() : $end_date;
	    	$end_date->setTimezone( new DateTimeZone( 'UTC' ) );
	    	update_post_meta( $post_id, 'tecal_events_end', $end_date->format('U') );
	    } else if( isset( $_POST['tecal_events_end'] ) ) {
    		// If we don't have a time (all day), save anyways with arbitrary time.
    		$end_date = date_create_from_format( "Y-m-d", ( sanitize_text_field( $_POST['tecal_events_end'] ) ) );
    		$end_date = ( $end_date == false ) ? date_create() : $end_date;
    		$end_date->setTimezone( new DateTimeZone( 'UTC' ) );
    		update_post_meta( $post_id, 'tecal_events_end', $end_date->format('U') );
    	}

	    if( isset( $_POST['tecal_events_location'] ) ) {
	    	update_post_meta( $post_id, 'tecal_events_location', sanitize_text_field( $_POST['tecal_events_location'] ) );
	    }

      update_post_meta( $post_id, 'tecal_events_allday', ( isset( $_POST['tecal_events_allday'] ) ) ? "1" : "0" );
      update_post_meta( $post_id, 'tecal_events_has_end', ( isset( $_POST['tecal_events_has_end'] ) ) ? "1" : "0" );
    }
    return $post_id;
	}

	/**
	 * Adds a message after a post redirect, if the event is external
	 * and cannot be edited.
	 *
	 * @since 		0.3.0
	 */
	public function redirect_post_location_filter( $location ) {
		remove_filter( 'redirect_post_location', __FUNCTION__, 99 );
    $location = add_query_arg( 'message', 99, $location );
    return $location;
	}

	/**
	 * Add a message text for failed edits on read-only (external) events.
	 *
	 * @since 		0.3.0
	 */
	public function post_updated_messages_filter( $messages ) {
		$messages['post'][99] = __( 'Editing of an external read-only event is not possible.', 'te-calendar' );
    return $messages;
	}
}

?>