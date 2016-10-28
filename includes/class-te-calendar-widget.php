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
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$num_posts = ( empty( $instance['num_posts'] ) || intval( $instance['num_posts']) < 1 ) ? 3 : intval( $instance['num_posts'] );

		// Get to know if this is a single page and the post id we are viewing right now.
		$exclude_self = 0;

		if( is_single() ) {
			global $post;
			$exclude_self = $post->ID;
		}

		// Get the last three posts with an offset of the post per page setting.
		$posts = get_posts( array(
			'posts_per_page' => $num_posts,
			'post__not_in' => array( $exclude_self )
			)
		);

		if( count($posts) > 0 ) {
			echo '
				<h2 class="section-title section-title--dark">' . $args['before_title'] . $title . $args['after_title'] . '</h2>
				<div class="news-list">';
			foreach( $posts as $post ) {
				echo '
					<div class="news-item">
						<h3 class="news-item__title">
							<a href="' . get_permalink($post) . '">' . $post->post_title . '</a>
						</h3>
					</div>';
			}
			echo '
				</div>';
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'num_events' => 3 ) );
		$title = sanitize_text_field( $instance['title'] );
		$num_events = intval( $instance['num_events'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Number of events to display:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('num_events'); ?>" name="<?php echo $this->get_field_name('num_events'); ?>" type="text" value="<?php echo esc_attr($num_events); ?>" /></p>

		<p>Here goes a setting for future/past events</p>

		<p>Here goes a setting for the direction to go in (order)</p>

		<p>Here goes a select field to select a template.</p>
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
		return $instance;
	}
}