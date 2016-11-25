<?php

// Load WP_List_Table if not present
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('WP_Posts_List_Table')){
 	require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );
}

class Te_Calendar_Custom_List_Table extends WP_Posts_List_Table {
    // remove search box
    public function search_box( $text, $input_id ) { return false; }

    // Your custom list table is here
    public function display() {
    	?>
    	<div class="tecal__edit-modal__container">
	    	<div class="tecal__edit-modal__inner">
	    	<!-- <div class="new"> -->
					<h3 class="tecal__edit-modal__new-title"><?php _e( 'New event', 'te-calendar' ); ?></h3>
					<h3 class="tecal__edit-modal__edit-title"><?php _e( 'Edit event', 'te-calendar' ); ?></h3>
					<form action="" method="post" id="edit-event-form">

						<p><label for="tecal_events_title"><?php _e('Title:', 'te-calendar'); ?></label>
						<input class="widefat" id="tecal_events_title" name="tecal_events_title" type="text" value="" /></p>

						<p><label for="tecal_events_allday"><?php _e('All day:', 'te-calendar'); ?></label>
						<input class="widefat" id="tecal_events_allday" name="tecal_events_allday" type="checkbox" /></p>

				    <p class="tecal_multi-input"><label for="tecal_events_begin"><?php _e('Begin:', 'te-calendar'); ?>&nbsp;</label>
						<input class="threethirdfat" id="tecal_events_begin" name="tecal_events_begin" type="date" value="" />
						<input class="thirdfat" id="tecal_events_begin_time" name="tecal_events_begin_time" type="time" value="" /></p>

						<p><label for="tecal_events_has_end"><?php _e('Specify an end:', 'te-calendar'); ?></label>
						<input class="widefat" id="tecal_events_has_end" name="tecal_events_has_end" type="checkbox" /></p>

						<p class="tecal_multi-input"><label for="tecal_events_end"><?php _e('End:', 'te-calendar'); ?>&nbsp;</label>
						<input class="threethirdfat" id="tecal_events_end" name="tecal_events_end" type="date" value="" />
						<input class="thirdfat" id="tecal_events_end_time" name="tecal_events_end_time" type="time" value="" /></p>

				    <p><label for="tecal_events_location"><?php _e('Location:', 'te-calendar'); ?></label>
						<input class="widefat" id="tecal_events_location" name="tecal_events_location" type="text" value="" /></p>

						<p><label for="tecal_events_description"><?php _e('Description:', 'te-calendar'); ?></label>
						<textarea class="widefat tecal_events_description" id="tecal_events_description" name="tecal_events_description"></textarea></p>

						<div class="tecal_edit-modal__submit">
							<input type="hidden" name="tecal_events_edit_id" value="" />

							<input type="button" name="tecal_edit-modal_cancel" value="<?php _e( 'Cancel', 'te-calendar' ); ?>" class="button-secondary" />

							<input type="button" name="tecal_edit-modal_delete" value="<?php _e( 'Delete', 'te-calendar' ); ?>" data-defaultcaption="<?php _e( 'Delete', 'te-calendar' ); ?>" data-busycaption="<?php _e( 'Deleting...', 'te-calendar' ); ?>" class="button-secondary" />

							<input type="submit" name="tecal_edit-modal_save" value="<?php _e( 'Save', 'te-calendar' ); ?>" data-defaultcaption="<?php _e( 'Save', 'te-calendar' ); ?>" data-busycaption="<?php _e( 'Saving...', 'te-calendar' ); ?>" class="button-primary" />
						</div>

					</form>
				</div>
			</div>

			<div id="calendar"></div>

			<br /><br />
			<h2>Alle Datensätze</h2>
		<?php

			// parent::search_box();
      parent::display();
    }
}