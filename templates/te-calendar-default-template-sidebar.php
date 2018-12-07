<?php // Calendar-Template-Name: Default Theme Widget ?>

<style>
	.calendar--default-widget {
		margin-top:  1em;
	}

	.calendar--default-widget .calendar__item {
		margin-bottom: 1em;
	}

	.calendar--default-widget .calendar__item__day,
	.calendar--default-widget .calendar__item__time {
		opacity: 0.75;
	}
</style>

<section class="calendar calendar--default-widget">
	<?php
		if( have_posts() ) {
			while( have_posts() ):
				the_post();
	?>
				<div class="calendar__item">
					<div>
						<p>
							<span class="calendar__item__day">
								<?php the_event_begin_day(); ?> &middot;
							</span>
							<span class="calendar__item__date calendar__item__year">
								<?php the_event_begin_date(); // Example: 24.12. ?><?php the_event_begin_year(); // Example: 2011 ?>
							</span>
							<span class="calendar__item__time">
								&middot; <?php the_event_begin_time(); // Example: 14:00 ?>
							</span>
						</p>
					</div>
					<div>
						<h4 class="calendar__item__title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h4>
					</div>
					<div>
						<p class="calendar__item__location">
							<em><?php the_event_location(); ?></em>
						</p>
					</div>
				</div>
	<?php
			endwhile;
		} else {
	?>
			<div class="calendar__item calendar__item--no-items">
				<p>
					<?php _e( 'Sorry, there are no upcoming calendar entries right now.', 'te-calendar' ); ?>
				</p>
			</div>
	<?php
		}
	?>
</section>