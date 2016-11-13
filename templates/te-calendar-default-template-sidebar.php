<?php // Calendar-Template-Name: Default Theme Widget ?>

<section class="calendar calendar--default-widget">
	<?php
		if( have_posts() ) {
			while( have_posts() ):
				the_post();
	?>
				<div class="calendar__item">
					<div>
						<span class="calendar__item__day">
							<a href="<?php the_permalink(); ?>">
								<?php the_event_day(); ?> &middot;
							</a>
						</span>
						<span class="calendar__item__date calendar__item__year">
							<a href="<?php the_permalink(); ?>">
								<?php the_event_date(); // date_format($datum,'j.n.') ?><?php the_event_year(); // date_format($datum,'Y') ?> &middot;
							</a>
						</span>
						<span class="calendar__item__time">
							<a href="<?php the_permalink(); ?>">
								<?php the_event_time(); // date_format($datum,'Y') ?>
							</a>
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
							<?php the_event_location(); ?>
						</span>
					</div>
				</div>
	<?php
			endwhile;
		} else {
	?>
			<div class="calendar__item calendar__item--no-items">
				Uhm, there are no upcoming calendar entries, I think.
			</div>
	<?php
		}
	?>
</section>