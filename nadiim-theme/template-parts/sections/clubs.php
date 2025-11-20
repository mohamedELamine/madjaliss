<?php
/**
 * قسم Clubs - نوادي القراءة
 *
 * عرض بطاقات النوادي مع Avatar دائري (80px)، عنوان، وصف قصير
 * يعمل مع النظام الجديد club_meta
 *
 * @package Nadiim
 * @since 3.0.0
 */

// إعدادات النوادي من Customizer
$clubs_count = get_theme_mod( 'home_clubs_count', 3 );

// Query النوادي
$clubs_args = array(
	'post_type'      => 'reading_clubs',
	'posts_per_page' => $clubs_count,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => array(
		array(
			'key'     => 'club_meta',
			'value'   => '"visibility":"public"',
			'compare' => 'LIKE',
		),
	),
);

$clubs_query = new WP_Query( $clubs_args );

if ( ! $clubs_query->have_posts() ) {
	return;
}
?>

<section class="clubs-section section-padding section-alt-bg">
	<div class="section-container">
		<div class="section-header">
			<h2 class="section-title">نوادي القراءة</h2>
			<p class="section-description">
				مجتمعاتٌ هادئة للقراءة والنقاش، نجتمع فيها على حب الكتب وتبادل الأفكار
			</p>
		</div>

		<div class="clubs-grid">
			<?php while ( $clubs_query->have_posts() ) : $clubs_query->the_post(); ?>
				<?php
				// الحصول على بيانات النادي الجديدة
				$club_meta = get_post_meta( get_the_ID(), 'club_meta', true );

				if ( ! is_array( $club_meta ) ) {
					$club_meta = array();
				}

				$short_description = isset( $club_meta['short_description'] ) ? $club_meta['short_description'] : '';
				$meeting_location  = isset( $club_meta['meeting_location']['address'] ) ? $club_meta['meeting_location']['address'] : '';
				$meeting_schedule  = isset( $club_meta['meeting_schedule_note'] ) ? $club_meta['meeting_schedule_note'] : '';
				$facebook_page     = isset( $club_meta['facebook_page'] ) ? $club_meta['facebook_page'] : '';
				$telegram_channel  = isset( $club_meta['telegram_channel'] ) ? $club_meta['telegram_channel'] : '';
				?>

				<article class="club-card">
					<div class="club-avatar">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'club-avatar-img' ) ); ?>
						<?php else : ?>
							<div class="club-avatar-placeholder">
								<span class="club-icon">📚</span>
							</div>
						<?php endif; ?>
					</div>

					<div class="club-content">
						<h3 class="club-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<div class="club-description">
							<?php
							if ( $short_description ) {
								echo wp_trim_words( esc_html( $short_description ), 15, '...' );
							} else {
								echo wp_trim_words( get_the_excerpt(), 15, '...' );
							}
							?>
						</div>

						<?php if ( $meeting_location ) : ?>
							<div class="club-meta">
								<span class="club-location">
									📍 <?php echo esc_html( $meeting_location ); ?>
								</span>
							</div>
						<?php endif; ?>

						<?php if ( $meeting_schedule ) : ?>
							<div class="club-schedule-note">
								<span class="schedule-label">مواعيد اللقاءات:</span>
								<span class="schedule-text"><?php echo esc_html( wp_trim_words( $meeting_schedule, 10, '...' ) ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $facebook_page || $telegram_channel ) : ?>
							<div class="club-social-links">
								<?php if ( $facebook_page ) : ?>
									<a href="<?php echo esc_url( $facebook_page ); ?>" target="_blank" rel="noopener" class="club-social-link" title="فيسبوك">
										<svg width="16" height="16" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
									</a>
								<?php endif; ?>
								<?php if ( $telegram_channel ) : ?>
									<a href="<?php echo esc_url( $telegram_channel ); ?>" target="_blank" rel="noopener" class="club-social-link" title="تيليجرام">
										<svg width="16" height="16" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/></svg>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<a href="<?php the_permalink(); ?>" class="club-join-btn">
							عرض النادي
						</a>
					</div>
				</article>

			<?php endwhile; wp_reset_postdata(); ?>
		</div>

		<div class="section-footer">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'reading_clubs' ) ); ?>" class="btn btn-outline">
				جميع النوادي
				<span class="btn-arrow">←</span>
			</a>
		</div>
	</div>
</section>
