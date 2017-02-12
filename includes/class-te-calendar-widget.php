<?php
/**
 * Description
 *
 * By Thomas Ebert, te-online.net
 * 26.10.2016
 *
 *
 */
class Te_Calendar_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'te_calendar_widget',
			'description' => 'Widget for display of calendar events.',
		);
		parent::__construct( 'te_calendar_widget', 'Calendar Events', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance = array( 'title' => '', 'num_events' => 5, 'template' => 'default', 'calendar' => 'calendar' ) ) {
		// outputs the content of the widget
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$num_events = intval( $instance['num_events'] );

		$template = stripslashes( empty( $instance['template'] ) ? '' : $instance['template'] );

		$calendars = sanitize_text_field( $instance['calendar'] );
		$calendars = explode( ',', $calendars );

		$today = strtotime( 'today midnight' );

		query_posts( array(
				'posts_per_page' => $num_events,
				'post_type' => 'tecal_events',
				'orderby' => 'meta_value',
				'meta_key' => 'tecal_events_begin',
				'order' => 'ASC',
				'tax_query' => array(
			    array(
			      'taxonomy' => 'tecal_calendars',
			      'field' => 'slug',
			      'terms' => $calendars,
			      'operator' => 'IN'
			    )
			  ),
				'meta_query' => array(
					array(
						'key' => 'tecal_events_begin',
						'value' => $today,
						'type' => 'numeric',
						'compare' => '>='
					)
				),
			)
		);

		if( !empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if( $template != 'default' && !empty( $template ) ) {
			require get_template_directory() . '/' . $template;
		} else {
			require plugin_dir_path( dirname( __FILE__ ) ) . 'templates/te-calendar-default-template-sidebar.php';
		}

		wp_reset_query();
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'num_events' => 3, 'template' => '', 'calendar' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		$template = sanitize_text_field( $instance['template'] );
		$num_events = intval( $instance['num_events'] );
		$calendar = sanitize_text_field( $instance['calendar'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('num_events'); ?>"><?php _e('Number of events to display:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('num_events'); ?>" name="<?php echo $this->get_field_name('num_events'); ?>" type="text" value="<?php echo esc_attr($num_events); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('template'); ?>"><?php _e('Name of template file in theme directory <em>(optional)</em>:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template'); ?>" type="text" value="<?php echo esc_attr($template); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('calendar'); ?>"><?php _e('Slug of calendar to show <em>(omitting shows default, multiple comma-separated)</em>:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('calendar'); ?>" name="<?php echo $this->get_field_name('calendar'); ?>" type="text" value="<?php echo esc_attr($calendar); ?>" /></p>

		<!-- <p>Here goes a setting for future/past events</p>

		<p>Here goes a setting for the direction to go in (order)</p>

		<p>Here goes a select field to select a template.</p> -->
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['num_events'] = intval( sanitize_text_field( $new_instance['num_events'] ) );
		$instance['template'] = stripslashes( sanitize_text_field( $new_instance['template'] ) );
		$instance['calendar'] = sanitize_text_field( $new_instance['calendar'] );
		return $instance;
	}
}