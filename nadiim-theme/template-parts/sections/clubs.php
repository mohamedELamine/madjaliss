<?php
/**
 * قسم Clubs - نوادي القراءة
 *
 * عرض بطاقات النوادي مع Avatar دائري (80px)، عنوان، وصف قصير
 * عدد افتراضي: 3 نوادي
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات النوادي من Customizer
$clubs_count = get_theme_mod( 'home_clubs_count', 3 );

// Query النوادي
$clubs_args = array(
	'post_type'      => 'reading_clubs',
	'posts_per_page' => $clubs_count,
	'orderby'        => 'date',
	'order'          => 'DESC',
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
				$club_leader    = get_post_meta( get_the_ID(), 'club_leader', true );
				$club_schedule  = get_post_meta( get_the_ID(), 'club_schedule', true );
				$current_book   = get_post_meta( get_the_ID(), 'current_book', true );
				$join_link      = get_post_meta( get_the_ID(), 'join_link', true );
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
							<?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
						</div>

						<?php if ( $club_schedule ) : ?>
							<div class="club-meta">
								<span class="club-schedule">
									📅 <?php echo esc_html( date_i18n( 'j F Y', strtotime( $club_schedule ) ) ); ?>
								</span>
							</div>
						<?php endif; ?>

						<?php if ( $current_book ) : ?>
							<div class="club-current-book">
								<span class="book-label">الكتاب الحالي:</span>
								<span class="book-title"><?php echo esc_html( get_the_title( $current_book ) ); ?></span>
							</div>
						<?php endif; ?>

						<a href="<?php echo $join_link ? esc_url( $join_link ) : get_permalink(); ?>" class="club-join-btn">
							انضم للنادي
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
