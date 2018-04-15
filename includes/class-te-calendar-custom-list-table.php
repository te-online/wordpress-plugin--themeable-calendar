<?php

// Load WP_List_Table if not present
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('WP_Posts_List_Table')){
 	require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );
}

class Te_Calendar_Custom_List_Table extends WP_Posts_List_Table {
		public function _construct() {
			parent::_construct();
		}

    // remove search box
    public function search_box( $text, $input_id ) { return false; }

    // Your custom list table is here
    public function display() {
    	// Load calendars.
    	$calendars = get_terms( 'tecal_calendars' );
    	// See if we are in list view or calendar view.
    	$setListView = ('list' === get_query_var('view')) ? true : false;
    	$setCalendarView = ('calendar' === get_query_var('view')) ? true : false;

    	global $current_user;
    	// Get current user preference.
    	$current_view = get_user_meta($current_user->ID, 'tecal_current_view', true);
    	// Save user preference.
    	if($setListView && $current_view !== 'list') {
    		update_user_meta( $current_user->ID, 'tecal_current_view', 'list');
    		$current_view = 'list';
    	} else if($setCalendarView && $current_view !== 'calendar') {
    		update_user_meta( $current_user->ID, 'tecal_current_view', 'calendar');
    		$current_view = 'calendar';
    	}
    	// Set view to display.
    	$listView = ($current_view === 'list') ? true : false;

    	?>
    	<h2 class="nav-tab-wrapper">
		    <a href="?post_type=tecal_events&view=calendar" class="nav-tab<?php echo (!$listView) ? ' nav-tab-active' : ''; ?>">
		    	<?php _e('Calendar view', 'te-calendar'); ?>
		    </a>
		    <a href="?post_type=tecal_events&view=list" class="nav-tab<?php echo ($listView) ? ' nav-tab-active' : ''; ?>">
		    	<?php _e('List view', 'te-calendar'); ?>
		    </a>
			</h2>

			<?php if(!$listView): ?>

	    	<div class="tecal__edit-modal__container">
		    	<div class="tecal__edit-modal__inner">
						<h3 class="tecal__edit-modal__new-title"><?php _e( 'New event', 'te-calendar' ); ?></h3>
						<h3 class="tecal__edit-modal__edit-title"><?php _e( 'Edit event', 'te-calendar' ); ?></h3>
						<p class="tecal__edit-modal__error"></p>
						<form action="" method="post" id="edit-event-form">

							<p><label for="tecal_events_title"><?php _e('Title:', 'te-calendar'); ?></label>
							<input class="widefat" id="tecal_events_title" name="tecal_events_title" type="text" value="" /></p>

							<p><label for="tecal_events_allday"><?php _e('All day:', 'te-calendar'); ?></label>
							<input class="widefat" id="tecal_events_allday" name="tecal_events_allday" type="checkbox" /></p>

					    <p class="tecal_multi-input"><label for="tecal_events_begin"><?php _e('Begin:', 'te-calendar'); ?>&nbsp;</label>
							<input class="threethirdfat" id="tecal_events_begin" name="tecal_events_begin" type="text" value="" />
							<input class="thirdfat" id="tecal_events_begin_time" name="tecal_events_begin_time" type="text" value="" /></p>

							<p><label for="tecal_events_has_end"><?php _e('Specify an end:', 'te-calendar'); ?></label>
							<input class="widefat" id="tecal_events_has_end" name="tecal_events_has_end" type="checkbox" /></p>

							<p class="tecal_multi-input"><label for="tecal_events_end"><?php _e('End:', 'te-calendar'); ?>&nbsp;</label>
							<input class="threethirdfat" id="tecal_events_end" name="tecal_events_end" type="text" value="" />
							<input class="thirdfat" id="tecal_events_end_time" name="tecal_events_end_time" type="text" value="" /></p>

					    <p><label for="tecal_events_location"><?php _e('Location:', 'te-calendar'); ?></label>
							<input class="widefat" id="tecal_events_location" name="tecal_events_location" type="text" value="" /></p>

							<p><label for="tecal_events_description"><?php _e('Description:', 'te-calendar'); ?></label>
							<textarea class="widefat tecal_events_description" id="tecal_events_description" name="tecal_events_description"></textarea></p>

							<p><label for="tecal_events_calendar"><?php _e('Calendar:', 'te-calendar'); ?></label>
							<select class="widefat" id="tecal_events_calendar" name="tecal_events_calendar">
								<?php foreach( $calendars as $calendar ) { ?>
									<option value="<?php echo $calendar->slug; ?>"><?php echo $calendar->name; ?></option>
								<?php } ?>
							</select>
							</p>

							<div class="tecal_edit-modal__submit">
								<input type="hidden" name="tecal_events_edit_id" value="" />

								<input type="button" name="tecal_edit-modal_cancel" value="<?php _e( 'Cancel', 'te-calendar' ); ?>" class="button-secondary" />

								<input type="button" name="tecal_edit-modal_delete" value="<?php _e( 'Delete', 'te-calendar' ); ?>" data-defaultcaption="<?php _e( 'Delete', 'te-calendar' ); ?>" data-busycaption="<?php _e( 'Deleting...', 'te-calendar' ); ?>" class="button-secondary" />

								<input type="submit" name="tecal_edit-modal_save" value="<?php _e( 'Save', 'te-calendar' ); ?>" data-defaultcaption="<?php _e( 'Save', 'te-calendar' ); ?>" data-busycaption="<?php _e( 'Saving...', 'te-calendar' ); ?>" class="button-primary" />
							</div>

						</form>
					</div>
				</div>

				<div id="tecal_calendar"></div>

			<?php else: ?>

				<div class="tecal_list_table">
					<?php
						// parent::search_box();
						parent::display();
					?>
				</div>

			<?php endif; ?>
     	<?php
    }
}