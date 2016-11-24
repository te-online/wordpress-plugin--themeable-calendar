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
						<span class="calendar__item__day">
							<?php the_event_begin_day(); ?> &middot;
						</span>
						<span class="calendar__item__date calendar__item__year">
							<?php the_event_begin_date(); // date_format($datum,'j.n.') ?><?php the_event_begin_year(); // date_format($datum,'Y') ?>
						</span>
						<span class="calendar__item__time">
							&middot; <?php the_event_begin_time(); // date_format($datum,'Y') ?>
						</span>
					</div>
					<div>
						<h4 class="calendar__item__title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h4>
					</div>
					<div>
						<span class="calendar__item__location">
							<em><?php the_event_location(); ?></em>
						</span>
					</div>
				</div>
	<?php
			endwhile;
		} else {
	?>
			<div class="calendar__item calendar__item--no-items">
				Sorry, there are no upcoming calendar entries right now.
			</div>
	<?php
		}
	?>
</section>