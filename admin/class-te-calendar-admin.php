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

		wp_enqueue_script( $this->plugin_name . "moment", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/moment.min.js', array( 'jquery' ), $this->version, false );

		// wp_enqueue_script( $this->plugin_name . "fullcalendar_locale", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/locale-all.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . "fullcalendar", plugin_dir_url( __FILE__ ) . 'lib/fullcalendar/fullcalendar.min.js', array( 'jquery' ), $this->version, false );

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
					'name' => __( 'Events', $this->plugin_name ),
					'singular_name' => __( 'Event', $this->plugin_name ),
					'add_new' => __('Add', $this->plugin_name),
					'menu_name' => __('Events', $this->plugin_name),
					'add_new_item' => __('Add event', $this->plugin_name),
					'edit_item' => __('Edit event', $this->plugin_name),
					'new_item' => __('New event', $this->plugin_name),
					'all_items' => __('Events', $this->plugin_name),
					'view_item' => __('View event', $this->plugin_name),
					'search_items' => __('Search events', $this->plugin_name),
					'not_found' =>  __('Looks like there are no events, yet.', $this->plugin_name),
					'not_found_in_trash' => __('There are no trashed events.', $this->plugin_name),
					'parent_item_colon' => ''
				),
			'public' => true,
			'has_archive' => false,
			'rewrite' => array(
				'slug' => __('events')
				),
			'supports' => array('title', 'editor', 'taxonomy'),
			'menu_position' => 100
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
			'name'                       => _x( 'Calendars', 'taxonomy general name', $this->plugin_name ),
			'singular_name'              => _x( 'Calendar', 'taxonomy singular name', $this->plugin_name ),
			'search_items'               => __( 'Search Calendars', $this->plugin_name ),
			'popular_items'              => __( 'Popular Calendars', $this->plugin_name ),
			'all_items'                  => __( 'All Calendars', $this->plugin_name ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Calendar', $this->plugin_name ),
			'update_item'                => __( 'Update Calendar', $this->plugin_name ),
			'add_new_item'               => __( 'Add New Calendar', $this->plugin_name ),
			'new_item_name'              => __( 'New Calendar Name', $this->plugin_name ),
			'separate_items_with_commas' => __( 'Separate calendars with commas', $this->plugin_name ),
			'add_or_remove_items'        => __( 'Add or remove calendars', $this->plugin_name ),
			'choose_from_most_used'      => __( 'Choose from the most used calendars', $this->plugin_name ),
			'not_found'                  => __( 'No calendars found.', $this->plugin_name ),
			'menu_name'                  => __( 'Calendars', $this->plugin_name ),
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
			  __( 'Default Calendar', $this->plugin_name ), // term name
			  'tecal_calendars', // taxonomy
			  array(
			    'description'=> __( 'Your default calendar that stores all the events.', $this->plugin_name ),
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

    $custom_list_table = new Te_Calendar_Custom_List_Table();
    $wp_list_table = $custom_list_table ;
	}

	/**
		* Answer the AJAX request for a list of calendars.
		*
		* @since 		0.1.0
		*/
	public function ajax_answer_fetch_calendars() {
		$calendars = get_terms( 'tecal_calendars' );

		$calendar_slugs = array();

		if( count( $calendars ) > 0 ) {
			foreach( $calendars as $calendar ) {
				$calendar_slugs[] = $calendar->slug;
			}
		}

		echo json_encode( $calendar_slugs );

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
		$calendar = ( isset( $_POST['calendar'] ) ) ? sanitize_sql_orderby( $_POST['calendar'] ) : 'calendar';

		$events = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'tecal_events',
				'tax_query' => array(
			    array(
			      'taxonomy' => 'tecal_calendars',
			      'field' => 'slug',
			      'terms' => $calendar,
			      'operator' => 'IN',
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
				$prep_event = array(
					'id' => $event->ID,
					'title' => $event->post_title,
					'start' => date( 'c', get_post_meta( $event->ID, 'tecal_events_begin', true ) ),
					'end' => date( 'c', get_post_meta( $event->ID, 'tecal_events_end', true ) ),
					'allDay' => ( get_post_meta( $event->ID, 'tecal_events_allday', true ) ) ? true : false,
					'location' => get_post_meta( $event->ID, 'tecal_events_location', true ),
					'description' => $event->post_content,
					'hasEnd' => get_post_meta( $event->ID, 'tecal_events_has_end', true ),
					'calendar' => $calendar
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
    // if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
    //   return $post_id;
    // }

    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
    	echo "Current user can't edit this kind of posts.";
      return;
    }

    // OK, we're authenticated: we need to find and save the data
    $post = get_post( $post_id );
    if ( $post->post_type == 'tecal_events' ) {
    	echo esc_attr( $_POST['tecal_events_begin'] ) . " " . esc_attr( $_POST['tecal_events_begin_time'] );

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

      update_post_meta( $post_id, 'tecal_events_allday', ( $_POST['tecal_events_allday'] == "true" ) ? "1" : "0" );

      echo "allday is " . $_POST['tecal_events_allday'];

      update_post_meta( $post_id, 'tecal_events_has_end', ( $_POST['tecal_events_has_end'] == "true" ) ? "1" : "0" );

      $post->post_title = esc_attr( $_POST['tecal_events_title'] );
      $post->post_content = esc_attr( $_POST['tecal_events_description'] );

      wp_update_post( $post );

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
    		$begin_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_begin'] ) . " " . esc_attr( $_POST['tecal_events_begin_time'] ) ) );
    		$begin_date = ( $begin_date == false ) ? date_create() : $begin_date;
    		update_post_meta( $post_id, 'tecal_events_begin', $begin_date->format('U') );
    	}

    	if( isset( $_POST['tecal_events_end'] ) && isset( $_POST['tecal_events_end_time'] ) ) {
	    	$end_date = date_create_from_format( "Y-m-d H:i", ( esc_attr( $_POST['tecal_events_end'] ) . " " . esc_attr( $_POST['tecal_events_end_time'] ) ) );
	    	$end_date = ( $end_date == false ) ? date_create() : $end_date;
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

      update_post_meta( $post_id, 'tecal_events_allday', ( $_POST['tecal_events_allday'] == "true" ) ? "1" : "0" );

      update_post_meta( $post_id, 'tecal_events_has_end', ( $_POST['tecal_events_has_end'] == "true" ) ? "1" : "0" );

      return 1;
    }

    echo "Post is" . $post->ID;
    print_r($post);
    wp_die();
	}

	/**
		* Save a new event.
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
    echo "Deleted";

    wp_die();
	}

}
