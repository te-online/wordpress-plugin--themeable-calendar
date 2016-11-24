<?php // Calendar-Template-Name: Default Theme Upcoming List ?>

<style>
	.calendar--default-archive .calendar__item__heading {
		font-weight: bold;
	}

	.calendar--default-archive .calendar__item {
		margin-top: 1em;
		margin-bottom: 1em;
		padding-bottom: 1.5em;
	}

	.calendar--default-archive .calendar__item__title {
		font-weight: bold;
	}

	.calendar--default-archive .calendar__item__description {
		padding-bottom: .25em;
		padding-top: .25em;
		margin-top: 1.5em;
		margin-bottom: -1em;
	}

	.calendar--default-archive .calendar__item__description::before,
	.calendar--default-archive .calendar__item__description::after {
		background: #d8d8d8;
    width: 5em;
    margin-left: -.3em;
    height: 0.2em;
    content: "";
    position: absolute;
    margin-top: -.9em;
	}

</style>

<section class="calendar calendar--default-archive">
	<?php
		if( have_posts() ) {
			while( have_posts() ):
				the_post();
	?>
				<div class="calendar__item">
					<div class="calendar__item__heading">
						<span class="calendar__item__day">
							<?php the_event_begin_day(); ?> &middot;
						</span>
						<span class="calendar__item__date calendar__item__year">
							<?php the_event_begin( 'd. F Y'); ?>
						</span>
						<span class="calendar__item__time">
							<?php if( !get_event_is_allday() ) { ?>
								&middot; <?php the_event_begin_time(); ?> – <?php the_event_end_time(); ?>
							<?php } else { ?>
								&middot; <?php the_event_begin_time(); ?>
							<?php } ?>
						</span>
					</div>
					<div>
						<div class="calendar__item__title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</div>
					</div>
					<div>
						<span class="calendar__item__location">
							<em><?php the_event_location(); ?></em>
						</span>
					</div>
					<?php if( !empty( get_the_content() ) ): ?>
						<div class="calendar__item__description">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
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