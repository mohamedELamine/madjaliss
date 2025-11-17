<?php
/**
 * قسم Topbar - شريط الأحداث القادمة
 *
 * يعرض شريط أفقي في أعلى الصفحة مع الأحداث القادمة
 * قابل للتمرير أفقياً مع حد أقصى 5 أحداث
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات Topbar من Customizer
$topbar_count  = get_theme_mod( 'home_topbar_count', 5 );
$topbar_bg     = get_theme_mod( 'home_topbar_bg', '#26704A' );
$topbar_text   = get_theme_mod( 'home_topbar_text', '#FFFFFF' );
$topbar_source = get_theme_mod( 'home_topbar_source', 'howarat' ); // howarat/tag/custom

// جلب الأحداث القادمة
$events_args = array(
	'post_type'      => 'events',
	'posts_per_page' => $topbar_count,
	'meta_key'       => 'event_start_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => array(
		array(
			'key'     => 'event_start_date',
			'value'   => current_time( 'Y-m-d H:i' ),
			'compare' => '>=',
			'type'    => 'DATETIME',
		),
	),
);

$events_query = new WP_Query( $events_args );

if ( ! $events_query->have_posts() ) {
	return; // لا توجد أحداث، إخفاء الشريط
}
?>

<section class="topbar-section" style="background-color: <?php echo esc_attr( $topbar_bg ); ?>; color: <?php echo esc_attr( $topbar_text ); ?>;">
	<div class="topbar-wrapper">
		<div class="topbar-scroller">
			<?php while ( $events_query->have_posts() ) : $events_query->the_post(); ?>
				<?php
				$event_date     = get_post_meta( get_the_ID(), 'event_start_date', true );
				$event_location = get_post_meta( get_the_ID(), 'event_location', true );
				$event_link     = get_permalink();

				// تنسيق التاريخ
				$day   = $event_date ? date_i18n( 'd', strtotime( $event_date ) ) : '';
				$month = $event_date ? date_i18n( 'M', strtotime( $event_date ) ) : '';
				?>

				<div class="topbar-event-card">
					<div class="event-date-badge">
						<div class="date-day"><?php echo esc_html( $day ); ?></div>
						<div class="date-month"><?php echo esc_html( $month ); ?></div>
					</div>
					<div class="event-info">
						<h4 class="event-title">
							<a href="<?php echo esc_url( $event_link ); ?>" style="color: inherit; text-decoration: none;">
								<?php the_title(); ?>
							</a>
						</h4>
						<?php if ( $event_location ) : ?>
							<p class="event-location">📍 <?php echo esc_html( $event_location ); ?></p>
						<?php endif; ?>
					</div>
					<a href="<?php echo esc_url( $event_link ); ?>" class="event-cta-btn">
						التفاصيل
					</a>
				</div>

			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
