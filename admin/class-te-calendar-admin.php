<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thomas-ebert.design
 * @since      1.0.0
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
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
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

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
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

	}

	/**
	 * Register a new custom post type for the events to be stored in.
	 *
	 * @since 		1.0.0
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
	 * @since 		1.0.0
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

		register_taxonomy( 'calendars', 'tecal_events', $args );
	}

	/**
	 * Register a new widget.
	 *
	 * @since 		1.0.0
	 */
	public function widget_register() {
		register_widget( 'Te_Calendar_Widget' );
	}

	/**
		* Register a new shortcode.
		*
		* @since 		1.0.0
		*/
	public function shortcode_register() {
		$shortcode = new Te_Calendar_Shortcode();
		add_shortcode( 'calendar', array( $shortcode, 'shortcode' ) );
	}

	/**
		* Override WP_List_Table for a custom view.
		*
		* @since 		1.0.0
		*/
	public function custom_list_table_register() {
		global $wp_list_table;

    $custom_list_table = new Te_Calendar_Custom_List_Table();
    $wp_list_table = $custom_list_table ;
	}

	public function ajax_answer_fetch_events() {
		$start = ( isset( $_POST['start'] ) ) ? date_create_from_format( 'Y-m-d', $_POST['start'] ) : date_create();
		$end = ( isset( $_POST['end'] ) ) ? date_create_from_format( 'Y-m-d', $_POST['end'] ) : date_create();

		$events = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'tecal_events',
				'meta_query' => array(
					array(
						'key' => 'tecal_events_begin',
						'value' => $start->format('U'),
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

		// print_r($events);
		// wp_die();

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
					'hasEnd' => get_post_meta( $event->ID, 'tecal_events_has_end', true )
				);

				$response_events[] = $prep_event;
			}

			echo json_encode( $response_events );
		} else {
			echo "[]";
		}

		wp_die();
	}

}
