<?php
class Te_Calendar_Event_Details_Controller {
	/**
	 * Register a new metabox for the event details.
	 *
	 * @since 		1.0.0
	 */
	public function event_metaboxes_register() {
		add_meta_box( 'event-details', __('Eventdetails'), array( $this, 'event_details_metabox'), 'tecal_events', 'side', 'default' );
	}

	/**
	 * Show the new metabox for event details.
	 *
	 * @since 		1.0.0
	 */
	public function event_details_metabox( $post ) {
		$today = date_create();
		$begin = get_post_meta( $post->ID, 'tecal_events_begin', true );
		if( ( date_create_from_format( 'U', $begin ) instanceof DateTime ) != true ) {
			$begin = $today->format('U');
		}
		$begin_string = date_i18n( 'Y-m-d', $begin );
		$begin_time = ( $begin == $today->format('U') ) ? date_i18n( 'H:00', $begin ) : date_i18n( 'H:i', $begin );
		$end = get_post_meta( $post->ID, 'tecal_events_end', true );
		if( ( date_create_from_format( 'U', $end ) instanceof DateTime ) != true ) {
			$end = $today->format('U');
		}
		$end_string = date_i18n( 'Y-m-d', $end );
		$end_time = ( $end == $today->format('U') ) ? date_i18n( 'H:00', ( $end + 60 * 60 ) ) : date_i18n( 'H:i', $end );
		$allday = get_post_meta( $post->ID, 'tecal_events_allday', true );
		$location = get_post_meta( $post->ID, 'tecal_events_location', true );
		$repeat_mode = get_post_meta( $post->ID, 'tecal_events_repeat_mode', true );

		$disabled = ( $allday == 1 ) ? 'disabled="disabled"' : '';
	    ?>

	    <input type="hidden" name="tecal_events_noncename" id="tecal_events_noncename" value="<?php echo wp_create_nonce( 'tecal_events'.$post->ID );?>" />

	    <p><label for="tecal_events_location"><?php _e('Location:'); ?></label>
			<input class="widefat" id="tecal_events_location" name="tecal_events_location" type="text" value="<?php echo esc_attr($location); ?>" /></p>

	    <p><label for="tecal_events_allday"><?php _e('All day:'); ?></label>
			<input class="widefat" id="tecal_events_allday" name="tecal_events_allday" type="checkbox" <?php echo ($allday == "0") ? "" : "checked='checked'" ; ?> /></p>

	    <p><label for="tecal_events_begin"><?php _e('Begin:'); ?></label>
			<input class="widefat" id="tecal_events_begin" name="tecal_events_begin" type="date" value="<?php echo esc_attr($begin_string); ?>" <?php echo $disabled; ?> />
			<input class="widefat" id="tecal_events_begin_time" name="tecal_events_begin_time" type="time" value="<?php echo esc_attr($begin_time); ?>" <?php echo $disabled; ?> /></p>

			<p><label for="tecal_events_end"><?php _e('End:'); ?></label>
			<input class="widefat" id="tecal_events_end" name="tecal_events_end" type="date" value="<?php echo esc_attr($end_string); ?>" <?php echo $disabled; ?> />
			<input class="widefat" id="tecal_events_end_time" name="tecal_events_end_time" type="time" value="<?php echo esc_attr($end_time); ?>" <?php echo $disabled; ?> /></p>

	    <?php
	}

	/**
	 * Save the event details.
	 *
	 * @since 		1.0.0
	 */
	public function event_details_save( $post_id ) {
		// if( empty( $post_id ) ) {
			// return;
		// }

    // verify this came from the our screen and with proper authorization.
    if ( !isset($_POST['tecal_events_noncename']) || !wp_verify_nonce( $_POST['tecal_events_noncename'], 'tecal_events'.$post_id )) {
      return $post_id;
    }

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
      return $post_id;
    }

    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
    }

    // OK, we're authenticated: we need to find and save the data
    $post = get_post( $post_id );
    if ( $post->post_type == 'tecal_events' ) {
    	if( isset( $_POST['tecal_events_begin'] ) && isset( $_POST['tecal_events_begin_time'] ) ) {
    		$begin_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_begin'] ) . " " . esc_attr( $_POST['tecal_events_begin_time'] ) ) );
    		$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
    		update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
    	}

    	if( isset( $_POST['tecal_events_end'] ) && isset( $_POST['tecal_events_end_time'] ) ) {
	    	$end_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_end'] ) . " " . esc_attr( $_POST['tecal_events_end_time'] ) ) );
	    	$end_date = ( $end_date == false ) ? date_create() : $end_date;
	    	update_post_meta( $post_id, 'tecal_events_end', $end_date->format('U') );
	    }

	    if( isset( $_POST['tecal_events_location'] ) ) {
	    	update_post_meta( $post_id, 'tecal_events_location', esc_attr( $_POST['tecal_events_location'] ) );
	    }

      update_post_meta( $post_id, 'tecal_events_allday', ( isset( $_POST['tecal_events_allday'] ) ) ? "1" : "0" );
    }
    return $post_id;
	}
}

?>