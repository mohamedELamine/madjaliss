<?php
/**
 * قسم Releases - الإصدارات المختارة
 *
 * عرض carousel للإصدارات مع أغلفة بنسبة 1:1.4 (140×200px)
 * يمكن التبديل بين Carousel و Grid
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات الإصدارات من Customizer
$releases_count  = get_theme_mod( 'home_releases_count', 8 );
$releases_layout = get_theme_mod( 'home_releases_layout', 'carousel' ); // carousel/grid

// Query الإصدارات
$releases_args = array(
	'post_type'      => 'esdar',
	'posts_per_page' => $releases_count,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$releases_query = new WP_Query( $releases_args );

if ( ! $releases_query->have_posts() ) {
	return;
}
?>

<section class="releases-section section-padding section-alt-bg">
	<div class="section-container">
		<div class="section-header">
			<h2 class="section-title">الإصدارات المختارة</h2>
			<p class="section-description">
				كتبٌ ونشراتٌ وبحوث منتقاة بعناية، تثري العقل وتغذي الروح
			</p>
		</div>

		<?php if ( $releases_layout === 'carousel' ) : ?>
			<!-- Carousel Layout -->
			<div class="releases-carousel-wrapper">
				<div class="releases-carousel swiper">
					<div class="swiper-wrapper">
						<?php while ( $releases_query->have_posts() ) : $releases_query->the_post(); ?>
							<div class="swiper-slide">
								<?php get_template_part( 'template-parts/components/card', 'release' ); ?>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>

					<!-- Navigation -->
					<div class="swiper-button-prev releases-prev"></div>
					<div class="swiper-button-next releases-next"></div>

					<!-- Pagination -->
					<div class="swiper-pagination releases-pagination"></div>
				</div>
			</div>

		<?php else : ?>
			<!-- Grid Layout -->
			<div class="releases-grid">
				<?php while ( $releases_query->have_posts() ) : $releases_query->the_post(); ?>
					<?php get_template_part( 'template-parts/components/card', 'release' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

		<?php endif; ?>

		<div class="section-footer">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>" class="btn btn-outline">
				جميع الإصدارات
				<span class="btn-arrow">←</span>
			</a>
		</div>
	</div>
</section>
