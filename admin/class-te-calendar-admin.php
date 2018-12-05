<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thomas-ebert.design
 * @since      0.1.0
 *
 * @package    Te_Calendar
 * @subpackage Te_Calendar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Te_Calendar
 * @subpackage Te_Calendar/admin
 * @author     Thomas Ebert, te-online.net <thomas.ebert@te-online.net>
 */
class Te_Calendar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Te_Calendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Te_Calendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/te-calendar-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . "fullcalendar", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/fullcalendar.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . "fullcalendar_print", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/fullcalendar.print.css', array(), $this->version, 'print' );

		wp_enqueue_style( $this->plugin_name . "rome", plugin_dir_url( __FILE__ ) . 'lib/rome/rome.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Te_Calendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Te_Calendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/te-calendar-admin.js', array( 'jquery' ), $this->version, false );

		// Make variables available globally.
		wp_localize_script( $this->plugin_name, 'WPURLS', array( 'siteurl' => get_option('siteurl') ) );
		wp_localize_script( $this->plugin_name, 'TE_CAL', array(
			'locale' => get_user_locale(),
			'timezone' => get_option( 'timezone_string' )
		) );

		wp_enqueue_script( $this->plugin_name . "moment", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/lib/moment-with-locales.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . "fullcalendar", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/fullcalendar.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . "fullcalendar_locale", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/locale-all.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . "rome", plugin_dir_url( __FILE__ ) . 'lib/rome/rome.min.js', $this->version, false );

	}

	/**
	 * Register a new custom post type for the events to be stored in.
	 *
	 * @since 		0.1.0
	 */
	public function events_custom_post_type() {
		register_post_type( 'tecal_events',
			array(
				'labels' => array(
					'name' => __( 'Events', 'te-calendar' ),
					'singular_name' => __( 'Event', 'te-calendar' ),
					'add_new' => __('Add', 'te-calendar'),
					'menu_name' => __('Events', 'te-calendar'),
					'add_new_item' => __('Add event', 'te-calendar'),
					'edit_item' => __('Edit event', 'te-calendar'),
					'new_item' => __('New event', 'te-calendar'),
					'all_items' => __('Events', 'te-calendar'),
					'view_item' => __('View event', 'te-calendar'),
					'search_items' => __('Search events', 'te-calendar'),
					'not_found' =>  __('Looks like there are no events, yet.', 'te-calendar'),
					'not_found_in_trash' => __('There are no trashed events.', 'te-calendar'),
					'parent_item_colon' => ''
				),
				'public' => true,
				'has_archive' => false,
				'rewrite' => array(
					'slug' => __('events', 'te-calendar')
				),
				'supports' => array('title', 'editor', 'taxonomy'),
				'menu_position' => 100,
				'menu_icon' => 'dashicons-calendar'
			)
		);
	}

	/**
	 * Register a new custom taxonomy for the calendars to be stored in.
	 *
	 * @since 		0.1.0
	 */
	public function calendars_custom_taxonomy() {
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => _x( 'Calendars', 'taxonomy general name', 'te-calendar' ),
			'singular_name'              => _x( 'Calendar', 'taxonomy singular name', 'te-calendar' ),
			'search_items'               => __( 'Search Calendars', 'te-calendar' ),
			'popular_items'              => __( 'Popular Calendars', 'te-calendar' ),
			'all_items'                  => __( 'All Calendars', 'te-calendar' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Calendar', 'te-calendar' ),
			'update_item'                => __( 'Update Calendar', 'te-calendar' ),
			'add_new_item'               => __( 'Add New Calendar', 'te-calendar' ),
			'new_item_name'              => __( 'New Calendar Name', 'te-calendar' ),
			'separate_items_with_commas' => __( 'Separate calendars with commas', 'te-calendar' ),
			'add_or_remove_items'        => __( 'Add or remove calendars', 'te-calendar' ),
			'choose_from_most_used'      => __( 'Choose from the most used calendars', 'te-calendar' ),
			'not_found'                  => __( 'No calendars found.', 'te-calendar' ),
			'menu_name'                  => __( 'Calendars', 'te-calendar' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'calendar' ),
		);

		register_taxonomy( 'tecal_calendars', 'tecal_events', $args );

		// Check if default calendar exists
		if( !is_array( term_exists( 'calendar', 'tecal_calendars' ) ) ) {
			// Create a default calendar
			wp_insert_term(
				__( 'Default Calendar', 'te-calendar' ), // term name
				'tecal_calendars', // taxonomy
				array(
					'description'=> __( 'Your default calendar that stores all the events.', 'te-calendar' ),
					'slug' => 'calendar'
				)
			);
		}
	}

	/**
	 * Make sure the default calendar is assigned to all new posts.
	 *
	 * @since 		0.1.0
	 */
	public function post_status_transition_add_calendar( $new_status, $old_status, $post ) {
		// Only applies when the status has changed.
		if ( $new_status != $old_status ) {
			// Get the current terms of the event in terms of calendars (pun intended).
			$post_terms = wp_get_post_terms( $post->ID, 'tecal_calendars' );
			// If doesn't have terms, then add the default one.
			if( count( $post_terms ) < 1 ) {
				wp_set_post_terms( $post->ID, 'calendar', 'tecal_calendars', false );
			}
		}
	}

	/**
	 * Register a new widget.
	 *
	 * @since 		0.1.0
	 */
	public function widget_register() {
		register_widget( 'Te_Calendar_Widget' );
	}

	/**
		* Register a new shortcode.
		*
		* @since 		0.1.0
		*/
	public function shortcode_register() {
		$shortcode = new Te_Calendar_Shortcode();
		add_shortcode( 'calendar', array( $shortcode, 'shortcode' ) );
	}

	/**
		* Override WP_List_Table for a custom view.
		*
		* @since 		0.1.0
		*/
	public function custom_list_table_register() {
		global $wp_list_table;

		// Instantiate our own list table
		$custom_list_table = new Te_Calendar_Custom_List_Table();
		// Replace common list table with ours
		$wp_list_table = $custom_list_table;
		// Do this to prepare items and enable pagination
		$wp_list_table->prepare_items();

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
		$wp_list_table->is_list_view = $listView;

		// Return views, so that we see them (but only for list view)
		return $listView ? $wp_list_table->get_views() : [];
	}

	/**
		* Answer the AJAX request for a list of calendars.
		*
		* @since 		0.1.0
		*/
	public function ajax_answer_fetch_calendars() {
		$calendars = get_terms( array(
	    'taxonomy' => 'tecal_calendars',
	    'hide_empty' => false,
		) );

		$calendarlist = [];

		if( count( $calendars ) > 0 ) {
			foreach( $calendars as $calendar ) {
				$calendarlist[] = array(
					'slug' => $calendar->slug,
					'color' => get_term_meta( $calendar->term_id, 'tecal_calendar_color' ),
					'external' => !empty( get_term_meta( $calendar->term_id, 'tecal_calendar_ical', true ) )
				);
			}
		}

		echo json_encode( $calendarlist );

		wp_die();
	}

	/**
		* Answer the AJAX request for a list of events.
		*
		* @since 		0.1.0
		*/
	public function ajax_answer_fetch_events() {
		$start = ( isset( $_POST['start'] ) ) ? date_create_from_format( 'Y-m-d', $_POST['start'] ) : date_create();
		$end = ( isset( $_POST['end'] ) ) ? date_create_from_format( 'Y-m-d', $_POST['end'] ) : date_create();
		$calendar = ( isset( $_POST['calendar'] ) ) ? sanitize_text_field( $_POST['calendar'] ) : 'calendar';
		$term = get_term_by( 'slug', $calendar, 'tecal_calendars' );
		$color = get_term_meta( $term->term_id, 'tecal_calendar_color', true );

		$events = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'tecal_events',
				'tax_query' => array(
					array(
						'taxonomy' => 'tecal_calendars',
						'field' => 'slug',
						'terms' => array( $calendar ),
						'operator' => 'IN'
					)
				),
				'meta_query' => array(
					array(
						'key' => 'tecal_events_begin',
						'value' => $start->format('U') - 60 * 60 * 24, // minus 1 day
						'type' => 'numeric',
						'compare' => '>='
					),
					array(
						'key' => 'tecal_events_end',
						'value' => $end->format('U'),
						'type' => 'numeric',
						'compare' => '<='
					)
				)
			)
		);

		$response_events = [];

		if( count( $events ) > 0 ) {
			foreach( $events as $event ) {
				$event_start = date_create_from_format( 'U', get_post_meta( $event->ID, 'tecal_events_begin', true ) );
				$event_start->setTimezone( new DateTimeZone( get_option( 'timezone_string' ) ) );
				$event_end = date_create_from_format( 'U', get_post_meta( $event->ID, 'tecal_events_end', true ) );
				$event_end->setTimezone( new DateTimeZone( get_option( 'timezone_string' ) ) );
				$prep_event = array(
					'id' => $event->ID,
					'title' => $event->post_title,
					'start' => $event_start->format( 'Y-m-d H:i:s' ), //+ 'F' + $event_start->format( 'H:i:s' ),
					'timezone' => $event_start->getTimezone(),
					'end' => $event_end->format( 'Y-m-d H:i:s' ), //+ 'T' + $event_end->format( 'H:i:s' ),
					'allDay' => ( get_post_meta( $event->ID, 'tecal_events_allday', true ) ) ? true : false,
					'location' => get_post_meta( $event->ID, 'tecal_events_location', true ),
					'description' => $event->post_content,
					'hasEnd' => ( get_post_meta( $event->ID, 'tecal_events_has_end', true ) ) ? true : false,
					'calendar' => $calendar,
					'color' => $color,
					'editable' => !Te_Calendar_Static_Helpers::is_event_external( $event->ID )
				);

				$response_events[] = $prep_event;
			}

			echo json_encode( $response_events );
		} else {
			echo "[]";
		}

		wp_die();
	}

	/**
		* Save an edit event.
		*
		* @since 		0.1.0
		*/
	public function ajax_save_edit_event() {
		$post_id = $_POST['tecal_events_post_id'];

		// verify this came from the our screen and with proper authorization.
		// if ( !isset($_POST['tecal_events_noncename']) || !wp_verify_nonce( $_POST['tecal_events_noncename'], 'tecal_events'.$post_id )) {
		//   return $post_id;
		// }

		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		  return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			echo "Current user can't edit this kind of posts.";
			return;
		}

		// OK, we're authenticated: we need to find and save the data
		$post = get_post( $post_id );
		if ( $post->post_type == 'tecal_events' ) {

			if( isset( $_POST['tecal_events_begin'] ) && isset( $_POST['tecal_events_begin_time'] ) ) {
				$begin_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_begin'] ) . " " . esc_attr( $_POST['tecal_events_begin_time'] ) ), new DateTimeZone( get_option( 'timezone_string' ) ) );
				$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
				$begin_date->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
			}

			if( isset( $_POST['tecal_events_end'] ) && isset( $_POST['tecal_events_end_time'] ) ) {
				$end_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_end'] ) . " " . esc_attr( $_POST['tecal_events_end_time'] ) ), new DateTimeZone( get_option( 'timezone_string' ) ) );
				$end_date = ( $end_date == false ) ? date_create() : $end_date;
				$end_date->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $post_id, 'tecal_events_end', $end_date->format('U') );
			}

			if( isset( $_POST['tecal_events_location'] ) ) {
				update_post_meta( $post_id, 'tecal_events_location', esc_attr( $_POST['tecal_events_location'] ) );
			}

			update_post_meta( $post_id, 'tecal_events_allday', ( $_POST['tecal_events_allday'] == "true" ) ? "1" : "0" );

			update_post_meta( $post_id, 'tecal_events_has_end', ( $_POST['tecal_events_has_end'] == "true" ) ? "1" : "0" );

			$post->post_title = esc_attr( $_POST['tecal_events_title'] );
			$post->post_content = esc_attr( $_POST['tecal_events_description'] );

			wp_update_post( $post );

			if( isset( $_POST['tecal_events_calendar'] ) ) {
				wp_set_post_terms( $post_id, esc_attr( $_POST['tecal_events_calendar'] ), 'tecal_calendars', false );
			}

			return 1;
		}

		return 0;
	}

	/**
		* Move an event.
		*
		* @since 		0.1.0
		*/
	public function ajax_save_move_event() {
		$post_id = $_POST['tecal_events_post_id'];

		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			echo "Current user can't edit this kind of posts.";
			return;
		}

		// OK, we're authenticated: we need to find and save the data
		$post = get_post( $post_id );
		if ( $post && $post->post_type == 'tecal_events' ) {

			if( isset( $_POST['tecal_events_begin'] ) && isset( $_POST['tecal_events_begin_time'] ) ) {
				$begin_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_begin'] ) . " " . esc_attr( $_POST['tecal_events_begin_time'] ) ), new DateTimeZone( get_option( 'timezone_string' ) ) );
				$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
				$begin_date->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
			}

			if( isset( $_POST['tecal_events_end'] ) && isset( $_POST['tecal_events_end_time'] ) ) {
				$end_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_end'] ) . " " . esc_attr( $_POST['tecal_events_end_time'] ) ), new DateTimeZone( get_option( 'timezone_string' ) ) );
				$end_date = ( $end_date == false ) ? date_create() : $end_date;
				$end_date->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $post_id, 'tecal_events_end', $end_date->format('U') );
			}

			return 1;
		}

		return 0;
	}

	/**
		* Save a new event.
		*
		* @since 		0.1.0
		*/
	public function ajax_save_new_event() {
		if ( !current_user_can( 'edit_posts' ) ) {
			echo "Error, user can't edit posts";
			return;
		}

		// OK, we're authenticated: we need to find and save the data
		$post_id = wp_insert_post(
			array(
				'post_title' => esc_attr( $_POST['tecal_events_title'] ),
				'post_content' => esc_attr( $_POST['tecal_events_description'] ),
				'post_type' => 'tecal_events',
				'post_status' => 'publish'
			)
		);

		$post = get_post( $post_id );
		if ( $post ) {
			if( isset( $_POST['tecal_events_begin'] ) && isset( $_POST['tecal_events_begin_time'] ) ) {
				$begin_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_begin'] ) . " " . esc_attr( $_POST['tecal_events_begin_time'] ) ), new DateTimeZone( get_option( 'timezone_string' ) ) );
				$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
				$begin_date->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
			}

			if( isset( $_POST['tecal_events_end'] ) && isset( $_POST['tecal_events_end_time'] ) ) {
				$end_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_end'] ) . " " . esc_attr( $_POST['tecal_events_end_time'] ) ), new DateTimeZone( get_option( 'timezone_string' ) ) );
				$end_date = ( $end_date == false ) ? date_create() : $end_date;
				$end_date->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $post_id, 'tecal_events_end', $end_date->format('U') );
			}

			if( isset( $_POST['tecal_events_location'] ) ) {
				update_post_meta( $post_id, 'tecal_events_location', esc_attr( $_POST['tecal_events_location'] ) );
			}

			update_post_meta( $post_id, 'tecal_events_allday', ( $_POST['tecal_events_allday'] == "true" ) ? "1" : "0" );

			update_post_meta( $post_id, 'tecal_events_has_end', ( $_POST['tecal_events_has_end'] == "true" ) ? "1" : "0" );

			return 1;
		}

		wp_die();
	}

	/**
		* Delete an event.
		*
		* @since 		0.1.0
		*/
	public function ajax_delete_event() {
		$post_id = $_POST['tecal_events_post_id'];

		if ( !current_user_can( 'delete_post', $post_id ) ) {
			echo "Error, user can't delete this post.";
			return;
		}

		wp_delete_post( $post_id );

		wp_die();
	}

	/**
		* Add color field to new calendar page.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_add_color_field() {

		?>
			<div class="form-field">
				<label for="term_meta[tecal_calendar_color]"><?php _e( 'Color', 'te-calendar' ); ?></label>
				<input type="color" name="term_meta[tecal_calendar_color]" id="term_meta[tecal_calendar_color]" value="<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>">
				<p class="description"><?php _e( 'Choose a color for your calendar', 'te-calendar' ); ?></p>
			</div>
		<?php
	}

	/**
		* Add color field to edit calendar page.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_edit_color_field( $term ) {
		// put the term ID into a variable
		$t_id = $term->term_id;

		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta = get_term_meta( $t_id, 'tecal_calendar_color', true ); ?>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[tecal_calendar_color]"><?php _e( 'Color', 'te-calendar' ); ?></label></th>
			<td>
				<input type="color" name="term_meta[tecal_calendar_color]" id="term_meta[tecal_calendar_color]" value="<?php echo esc_attr( $term_meta ) ? esc_attr( $term_meta ) : ''; ?>">
				<p class="description"><?php _e( 'Choose a color for your calendar', 'te-calendar' ); ?></p>
			</td>
		</tr>
	<?php
	}

	/**
		* Add saving of color field.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_save_color_field( $term_id ) {
		if ( isset( $_POST['term_meta'] ) && isset( $_POST['term_meta']['tecal_calendar_color'] ) ) {
			$t_id = $term_id;
			update_term_meta( $t_id, 'tecal_calendar_color', $_POST['term_meta']['tecal_calendar_color'] );
		}
	}

	/**
		* Add ical feed field to new calendar page.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_add_ical_field() {

		?>
			<div class="form-field">
				<label for="term_meta[tecal_calendar_ical]"><?php _e( 'iCal-feed address', 'te-calendar' ); ?></label>
				<input type="text" name="term_meta[tecal_calendar_ical]" id="term_meta[tecal_calendar_ical]" value="">
				<p class="description"><?php _e( 'Events can be imported from a remote iCal-Feed. When you use this option, this calendar will become read-only.', 'te-calendar' ); ?></p>
			</div>
		<?php
	}

	/**
		* Add color field to edit calendar page.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_edit_ical_field( $term ) {
		// put the term ID into a variable
		$t_id = $term->term_id;

		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta = get_term_meta( $t_id, 'tecal_calendar_ical', true ); ?>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[tecal_calendar_ical]"><?php _e( 'ICal-Feed address', 'te-calendar' ); ?></label></th>
			<td>
				<?php if( $term_meta && !empty( esc_attr( $term_meta ) ) ) { ?>
					<input type="text" name="term_meta[tecal_calendar_ical]" id="term_meta[tecal_calendar_ical]" value="<?php echo esc_attr( $term_meta ) ? esc_attr( $term_meta ) : ''; ?>">
					<p class="description"><?php _e( 'Events can be imported from a remote iCal-Feed. When you use this option, this calendar will become read-only.', 'te-calendar' ); ?></p>
				<?php } else { ?>
					<em>Please create a new calendar to add an iCal-feed. Feeds cannot be added to existing calendars.</em>
				<?php } ?>
			</td>
		</tr>
	<?php
	}

	/**
		* Add saving of ical field on calendar creation.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_save_ical_field_create( $term_id ) {
		if ( isset( $_POST['term_meta'] ) && isset( $_POST['term_meta']['tecal_calendar_ical'] ) ) {
			$t_id = $term_id;
			update_term_meta( $t_id, 'tecal_calendar_ical', $_POST['term_meta']['tecal_calendar_ical'] );
			wp_schedule_single_event( time(), 'tecal_fetch_from_external_feeds' );
		}
	}

	/**
		* Add updating of ical field.
		*
		* @since 		0.1.0
		*/
	public function tecal_calendars_save_ical_field_edit( $term_id ) {
		$term_meta = get_term_meta( $term_id, 'tecal_calendar_ical', true );
		// Only save if calendar was created with feed url.
		if( $term_meta && !empty($term_meta) ) {
			if ( isset( $_POST['term_meta'] ) && isset( $_POST['term_meta']['tecal_calendar_ical'] ) ) {
				$t_id = $term_id;
				update_term_meta( $t_id, 'tecal_calendar_ical', $_POST['term_meta']['tecal_calendar_ical'] );
				$this->fetch_from_external_feeds();
			}
		}
	}

	/**
		* Add a query var for switching views.
		*
		* @since 		0.1.2
		*/
	public function add_query_vars( $vars ) {
		$vars[] = "view";
		return $vars;
	}

	/**
		* Add more columns to list view in admin.
		*
		* @since 		0.2.0
		*/
	public function add_event_columns( $columns ) {
		unset( $columns['date'] );
	  return array_merge(
	  	$columns,
	    array(
    		'begin' => __( 'Begin', 'te-calendar' ),
    		'end' => __( 'End', 'te-calendar' ),
    		'allday' => __( 'All day', 'te-calendar' )
	    )
	  );
	}

	/**
		* Add content to the custom columns of list view in admin.
		*
		* @since 		0.2.0
		*/
	public function display_event_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'begin':
				$begin_date = get_post_meta( $post_id, 'tecal_events_begin', true );
				if(!empty($begin_date)) {
					echo locale_date_i18n( _x( 'l, F j, Y h:i a', 'Full date format', 'te-calendar' ), $begin_date );
				}
				break;

			case 'end':
				$end_date = get_post_meta( $post_id, 'tecal_events_end', true );
				$has_end = ( get_post_meta( $post_id, 'tecal_events_has_end', true ) ) ? true : false;
				if(!empty($end_date) && $has_end) {
					echo locale_date_i18n( _x( 'l, F j, Y h:i a', 'Full date format', 'te-calendar' ), $end_date );
				} else if(!$has_end) {
					_e( 'No end specified', 'te-calendar' );
				}
				break;

			case 'allday':
				$allday = ( get_post_meta( $post_id, 'tecal_events_allday', true ) ) ? true : false;
				echo ($allday) ? __( 'all day', 'te-calendar' ) : _x( '-', 'List: all day n/a', 'te-calendar' );
		}
	}

	/**
		* Add content to the custom columns of list view in admin.
		*
		* @since 		0.2.0
		*/

	/**
	 * Order by the correct meta field value and not the parsed date
	 * in list view in admin, when ordering by begin and end fields.
	 *
	 * Also order by `begin` by default.
	 *
	 * @since 0.3.9
	 */
	public function event_column_order_adjustments( $query ) {
		$orderby = $query->get( 'orderby' );
		$post_type = $query->get( 'post_type' );

		// Order by begin, if no other order is set
		// Correctly order by begin, if selected
		if( 'tecal_events' === $post_type && ( '' === $orderby || 'begin' === $orderby ) ) {
			$query->set( 'meta_key', 'tecal_events_begin' );
			$query->set( 'orderby', 'meta_value_num' );
    }

		// Correctly order by end, if selected
		if( 'tecal_events' === $post_type && 'end' === $orderby ) {
			$query->set( 'meta_key', 'tecal_events_end' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	/**
		* Fetches events from external iCal feeds.
		*
		* @since 		0.3.0
		*/
	public function fetch_from_external_feeds() {
		// Get a list of all calendars
		$calendars = get_terms(array(
	    'taxonomy' => 'tecal_calendars',
	    'hide_empty' => false,
		));

		$fetchable_calendars = [];

		foreach ( $calendars as $calendar ) {
	    $ical_feed_url = get_field( 'tecal_calendar_ical', $calendar );
	    if( $ical_feed_url && !empty( $ical_feed_url ) ) {
	    	$calendar->ical_feed_url = $ical_feed_url;
	    	$fetchable_calendars[] = $calendar;
	    }
		}

		if( count( $fetchable_calendars ) < 1 ) {
			return;
		}

		// Fetch events for each calendar with a feed url
		foreach ( $fetchable_calendars as $calendar ) {
			// Get ICS file from remote location
			$ical = new ICal\ICal( array(
				'defaultTimeZone' => get_option( 'timezone_string' )
			) );
			$ical->initUrl( $calendar->ical_feed_url );
			// Set timezone.
			$timezone = new DateTimeZone( get_option( 'timezone_string' ) );
			// Get events from ICS file from now - 1 year until now + 1 year

			try {
				$events = $ical->eventsFromRange(
					date_create_from_format( 'U', strtotime( "-1 year" ) )->format('Y-m-d'),
					date_create_from_format( 'U', strtotime( "+1 year" ) )->format('Y-m-d')
				);
			} catch(Exception $e) {
				continue;
			}

			if( count( $events ) < 1 ) {
				continue;
			}

			// Save that the calendar is updating to temporarily allow editing
			// of read-only events.
			update_term_meta( $calendar->term_id, 'tecal_calendar_is_updating', true );

			$legit_events = [];

			// Foreach event
			foreach( $events as $index => $event ) {
				// 	Read the UID and last-modified props of the event
				if(isset($event->rrule) || isset($event->recurrence_id)) {
					// Repeating events cannot be identified by their uid, because it
					// is always the same for every repetition. So let's just create
					// a custom uid and have all old repeating events deleted.
					$event->internal_uid = $event->uid . '-' . $event->dtstart_array[3];
				} else {
					$event->internal_uid = $event->uid;
				}
				// Collect this event as legit and not be deleted.
				$legit_events[] = $event->internal_uid;
				// 	Look up the local event with uid and compare last-modified
				$local_events = get_posts( array(
					'posts_per_page' => -1,
					'post_type' => 'tecal_events',
					'tax_query' => array(
						array(
							'taxonomy' => 'tecal_calendars',
							'field' => 'slug',
							'terms' => array( $calendar->slug ),
							'operator' => 'IN'
						)
					),
					'meta_query' => array(
						array(
							'key' => 'tecal_ical_uid',
							'value' => $event->internal_uid
						)
					)
				) );
				// 	If event exists
				if( $local_events && count( $local_events ) > 0 ) {
					$local_event = $local_events[0];
					$last_modified = get_post_meta( $local_event->ID, 'tecal_ical_last_modified', true );

					// Check if last_modified date matches the local copy
					if( $last_modified && !empty( $last_modified ) && $last_modified === $event->last_modified ) {
						// nothing to do
						// return / goto next event
						continue;
					}

					// Update summary
					$local_event->post_title = esc_attr( $event->summary );

					// Update description
					$local_event->post_content = esc_attr( $event->description );
				} else {
					// Insert event
					$event_id = wp_insert_post(
						array(
							'post_title' => esc_attr( $event->summary ),
							'post_content' => esc_attr( $event->description ),
							'post_type' => 'tecal_events',
							'post_status' => 'publish'
						)
					);

					$local_event = get_post( $event_id );

					// Set calendar
					wp_set_post_terms( $local_event->ID, $calendar->slug, 'tecal_calendars', false );

					// Set UID
					update_post_meta( $local_event->ID, 'tecal_ical_uid', $event->internal_uid );
				}

				if( !$local_event ) {
					continue;
				}

				// DTSTART
				$start = $ical->iCalDateToDateTime( $event->dtstart_array[3], true );
				$start->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $local_event->ID, 'tecal_events_begin', $start->format( 'U' ) );
				// DTEND
				$end = $ical->iCalDateToDateTime( $event->dtend_array[3], true );
				$end->setTimezone( new DateTimeZone( 'UTC' ) );
				update_post_meta( $local_event->ID, 'tecal_events_end', $end->format( 'U' ) );

				// LOCATION
				update_post_meta( $local_event->ID, 'tecal_events_location', $event->location );
				// Allday events will have only Date not DateTime, hence only 8 digits
				if( strlen( $event->dtstart ) === 8 ) {
					// ALLDAY
					update_post_meta( $local_event->ID, 'tecal_events_allday', true );
				} else {
					// Not allday
					update_post_meta( $local_event->ID, 'tecal_events_allday', false );
				}

				// These events always have an end, because in a normal calendar you can't omit the end.
				update_post_meta( $local_event->ID, 'tecal_events_has_end', true );

				// Last modified
				update_post_meta( $local_event->ID, 'tecal_ical_last_modified', $event->last_modified );

				wp_update_post( $local_event );
			}

			// Remove all other events.
			$old_events = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'tecal_events',
				'tax_query' => array(
					array(
						'taxonomy' => 'tecal_calendars',
						'field' => 'slug',
						'terms' => array( $calendar->slug ),
						'operator' => 'IN'
					)
				),
				'meta_query' => array(
					array(
						'key' => 'tecal_ical_uid',
						'value' => $legit_events,
						'compare' => 'NOT IN'
					)
				)
			) );

			if( count( $old_events ) > 0 ) {
				foreach( $old_events as $event ) {
					wp_delete_post( $event->ID );
				}
			}

			update_term_meta( $calendar->term_id, 'tecal_calendar_is_updating', false );
		}
	}
}
