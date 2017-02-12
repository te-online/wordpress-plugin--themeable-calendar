<?php
/**
 * Description
 *
 * By Thomas Ebert, te-online.net
 * 26.10.2016
 *
 *
 */
class Te_Calendar_Shortcode {

	/**
	 *
	 */
	public function __construct() {

	}

	/**
	 * Outputs the content of the shortcode
	 *
	 * @param array $atts
	 */
	public function shortcode( $atts ) {

		// Attributes
		$atts = shortcode_atts(
			array(
				'num_events' => '5',
				'template' => 'default',
				'archive' => 'false',
				'calendar' => 'calendar'
			),
			$atts,
			'calendar'
		);

		$template = stripslashes( $atts['template'] );

		$num_events = intval( $atts['num_events'] );

		$calendars = sanitize_text_field( $atts['calendar'] );
		$calendars = explode( ',', $calendars );

		$today = strtotime( 'today midnight' );

		$compare_operator = ( $atts['archive'] == 'true' ) ? '<' : '>=';
		$order = ( $atts['archive'] == 'true' ) ? 'DESC' : 'ASC';

		query_posts( array(
				'posts_per_page' => $num_events,
				'post_type' => 'tecal_events',
				'orderby' => 'meta_value',
				'meta_key' => 'tecal_events_begin',
				'order' => $order,
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
						'compare' => $compare_operator
					)
				)
			)
		);

		if( $template != 'default' && !empty( $template ) ) {
			require get_template_directory() . '/' . $template;
		} else {
			require plugin_dir_path( dirname( __FILE__ ) ) . 'templates/te-calendar-default-template-archive.php';
		}

		wp_reset_query();
	}
}