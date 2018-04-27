<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://thomasebert.net
 * @since      1.0.0
 *
 * @package    Te_Calendar
 * @subpackage Te_Calendar/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Te_Calendar
 * @subpackage Te_Calendar/includes
 * @author     Thomas Ebert, te-online.net <thomas.ebert@te-online.net>
 */
class Te_Calendar {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Te_Calendar_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'te-calendar';
		$this->version = '0.2.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Te_Calendar_Loader. Orchestrates the hooks of the plugin.
	 * - Te_Calendar_i18n. Defines internationalization functionality.
	 * - Te_Calendar_Admin. Defines all hooks for the admin area.
	 * - Te_Calendar_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-loader.php';

		/**
		 * Load composer dependencies.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-i18n.php';

		/**
		 * The class responsible for static helpers.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-static-helpers.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-te-calendar-admin.php';

		/**
		 * The file responsible for defining the widget settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-widget.php';

		/**
		 * The file responsible for defining the shortcodes settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-shortcode.php';

		/**
		 * The class responsible for defining everything that happens on the edit event page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-event-details-controller.php';

		/**
		 * The class responsible for overriding the WP_List_Table.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-te-calendar-custom-list-table.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-te-calendar-public.php';

		/**
		 * The class responsible for defining all public templating functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-te-calendar-global-functions.php';

		$this->loader = new Te_Calendar_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Te_Calendar_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Te_Calendar_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Te_Calendar_Admin( $this->get_plugin_name(), $this->get_version() );
		$event_controller = new Te_Calendar_Event_Details_Controller();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'events_custom_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'calendars_custom_taxonomy' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'widget_register' );
		$this->loader->add_action( 'init', $plugin_admin, 'shortcode_register' );
		$this->loader->add_action( 'views_edit-tecal_events', $plugin_admin, 'custom_list_table_register' );
		$this->loader->add_action( 'wp_ajax_te_calendar_fetch_events', $plugin_admin, 'ajax_answer_fetch_events' );
		$this->loader->add_action( 'wp_ajax_te_calendar_fetch_calendars', $plugin_admin, 'ajax_answer_fetch_calendars' );
		$this->loader->add_action( 'wp_ajax_te_calendar_save_edited_event', $plugin_admin, 'ajax_save_edit_event' );
		$this->loader->add_action( 'wp_ajax_te_calendar_save_new_event', $plugin_admin, 'ajax_save_new_event' );
		$this->loader->add_action( 'wp_ajax_te_calendar_delete_event', $plugin_admin, 'ajax_delete_event' );
		$this->loader->add_action( 'wp_ajax_te_calendar_move_event', $plugin_admin, 'ajax_save_move_event' );

		$this->loader->add_action( 'admin_init', $event_controller, 'event_metaboxes_register' );
		$this->loader->add_action( 'save_post', $event_controller, 'event_details_save' );
		$this->loader->add_action( 'pre_post_update', $event_controller, 'should_event_save' );

		// Add color picker to calendar new and edit
		$this->loader->add_action( 'tecal_calendars_add_form_fields', $plugin_admin, 'tecal_calendars_add_color_field' );
		$this->loader->add_action( 'tecal_calendars_edit_form_fields', $plugin_admin, 'tecal_calendars_edit_color_field' );
		$this->loader->add_action( 'edited_tecal_calendars', $plugin_admin, 'tecal_calendars_save_color_field', 10, 2 );
		$this->loader->add_action( 'create_tecal_calendars', $plugin_admin, 'tecal_calendars_save_color_field', 10, 2 );

		// Add ical feed input to calendar new and edit
		$this->loader->add_action( 'tecal_calendars_add_form_fields', $plugin_admin, 'tecal_calendars_add_ical_field' );
		$this->loader->add_action( 'tecal_calendars_edit_form_fields', $plugin_admin, 'tecal_calendars_edit_ical_field' );
		$this->loader->add_action( 'edited_tecal_calendars', $plugin_admin, 'tecal_calendars_save_ical_field_edit', 10, 2 );
		$this->loader->add_action( 'create_tecal_calendars', $plugin_admin, 'tecal_calendars_save_ical_field_create', 10, 2 );

		// Add WP cron to get events from external ical feeds
		$this->loader->add_action( 'tecal_fetch_from_external_feeds', $plugin_admin, 'fetch_from_external_feeds' );

		if ( !wp_next_scheduled( 'tecal_fetch_from_external_feeds' ) ) {
	    wp_schedule_event( time(), 'hourly', 'tecal_fetch_from_external_feeds' );
		}

		// Add filter to assign default calendar to all events at least.
		$this->loader->add_action( 'transition_post_status', $plugin_admin, 'post_status_transition_add_calendar', 10, 3 );

		// Add query var to admin view to switch to list view
		$this->loader->add_action( 'query_vars', $plugin_admin, 'add_query_vars' );
		// $this->loader->add_action( 'admin_init', $plugin_admin, 'add_screen_options' );

		// Add custom columns to admin list view
		$this->loader->add_filter( 'manage_tecal_events_posts_columns', $plugin_admin, 'add_event_columns');
		$this->loader->add_action( 'manage_tecal_events_posts_custom_column' , $plugin_admin, 'display_event_columns', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Te_Calendar_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Te_Calendar_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
