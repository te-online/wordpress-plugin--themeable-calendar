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
			'supports' => array('title', 'editor', 'custom-fields', 'taxonomy'),
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
			'search_items'               => __( 'Search Writers', $this->plugin_name ),
			'popular_items'              => __( 'Popular Writers', $this->plugin_name ),
			'all_items'                  => __( 'All Writers', $this->plugin_name ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Writer', $this->plugin_name ),
			'update_item'                => __( 'Update Writer', $this->plugin_name ),
			'add_new_item'               => __( 'Add New Writer', $this->plugin_name ),
			'new_item_name'              => __( 'New Writer Name', $this->plugin_name ),
			'separate_items_with_commas' => __( 'Separate writers with commas', $this->plugin_name ),
			'add_or_remove_items'        => __( 'Add or remove writers', $this->plugin_name ),
			'choose_from_most_used'      => __( 'Choose from the most used writers', $this->plugin_name ),
			'not_found'                  => __( 'No writers found.', $this->plugin_name ),
			'menu_name'                  => __( 'Writers', $this->plugin_name ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'writer' ),
		);

		register_taxonomy( 'calendars', 'tecal_events', $args );
	}

}
