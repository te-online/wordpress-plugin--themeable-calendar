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

	public function answer_ajax_events_fetch() {
		echo '[{"id":"534","title":"Frauenhilfe Ochtrup","start":"2016-11-09T15:00:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-09 15:00:00","wichtig":"0"},{"id":"540","title":"Frauenhilfe Metelen","start":"2016-11-02T15:00:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-02 15:00:00","wichtig":"0"},{"id":"541","title":"Frauenhilfe Metelen","start":"2016-12-07T15:00:00","allDay":false,"ort":"","info":"Adventsfeier\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-07 15:00:00","wichtig":"0"},{"id":"567","title":"11-Uhr-Gottesdienst-Vorbereitung","start":"2016-11-23T20:00:00","allDay":false,"ort":"Ev. Gemeindehaus","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-23 20:00:00","wichtig":"0"},{"id":"546","title":"Vorbereitung 11-Uhr-Gottesdienst","start":"2016-11-02T20:00:00","allDay":false,"ort":"Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-02 20:00:00","wichtig":"0"},{"id":"547","title":"Konzert","start":"2016-11-13T17:00:00","allDay":false,"ort":"Ev. Kirche Ochtrup","info":"Motetten von J.S. Bach, Capella Enschede\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-13 17:00:00","wichtig":"0"},{"id":"549","title":"Konfi-Kids","start":"2016-11-03T15:30:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-03 15:30:00","wichtig":"0"},{"id":"550","title":"Konfi-Kids","start":"2016-11-10T15:30:00","allDay":false,"ort":"Ev. Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-10 15:30:00","wichtig":"0"},{"id":"554","title":"Presbyterium","start":"2016-11-03T19:30:00","allDay":false,"ort":"Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-03 19:30:00","wichtig":"0"},{"id":"557","title":"Presbyterium","start":"2016-12-05T19:30:00","allDay":false,"ort":"Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-05 19:30:00","wichtig":"0"},{"id":"564","title":"Trauercaf\u00e9 Hoffnungs-schimmer","start":"2016-12-07T15:00:00","allDay":false,"ort":"Villa Winkel","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-07 15:00:00","wichtig":"0"},{"id":"570","title":"Offener Trauertreff \"Innehalten\"","start":"2016-11-12T10:30:00","allDay":false,"ort":"Kommunalfriedhof","info":"Mitglieder des Hospizvereins laden auf dem Kommunalfriedhof bei einem Stehkaffee zu Begegnung, Innehalten, Verweilen ein. \n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-12 10:30:00","wichtig":"0"},{"id":"569","title":"Offener Trauertreff \"Innehalten\"","start":"2016-11-05T10:30:00","allDay":false,"ort":"Kommunalfriedhof","info":"Mitglieder des Hospizvereins laden auf dem Kommunalfriedhof bei einem Stehkaffee zu Begegnung, Innehalten, Verweilen ein. \n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-05 10:30:00","wichtig":"0"},{"id":"571","title":"\u00d6ffentlicher Hospizstammtisch","start":"2016-11-14T19:30:00","allDay":false,"ort":"Paddy\'s Irish Pub","info":"Markus B\u00fcnseler von der Buchhandlung Steffers stellt Buchneuheiten vor.\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-14 19:30:00","wichtig":"0"},{"id":"572","title":"Probe Junger Chor","start":"2016-11-12T11:00:00","allDay":false,"ort":"Ev. Kirche","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-12 11:00:00","wichtig":"0"},{"id":"573","title":"Vortragsabend \"Der Islam und die Muslime in Deutschland\"","start":"2016-11-08T19:30:00","allDay":false,"ort":"Clemens-August-Heim","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-08 19:30:00","wichtig":"0"},{"id":"574","title":"Adventsbasar Eine Welt Gruppe","start":"2016-11-27T11:00:00","allDay":false,"ort":"Ev. Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-27 11:00:00","wichtig":"0"}]';
		wp_die();
	}

}
