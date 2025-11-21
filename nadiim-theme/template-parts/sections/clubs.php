<?php
/**
 * Template part لقسم النوادي
 *
 * عرض نوادي القراءة بتصميم Grid أو Carousel
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'clubs_section_enable', true ) ) {
	return;
}

// الحصول على الإعدادات من Customizer
$section_title   = get_theme_mod( 'clubs_section_title', __( 'نوادي القراءة', 'nadiim' ) );
$section_subtitle = get_theme_mod( 'clubs_section_subtitle', __( 'مجتمعاتٌ هادئة للقراءة والنقاش، نجتمع فيها على حب الكتب وتبادل الأفكار', 'nadiim' ) );
$clubs_count     = get_theme_mod( 'clubs_section_count', 6 );
$layout          = get_theme_mod( 'clubs_section_layout', 'grid' ); // grid or carousel
$bg_enable       = get_theme_mod( 'clubs_section_bg_enable', false );
$bg_image        = get_theme_mod( 'clubs_section_bg_image', '' );
$bg_embed        = get_theme_mod( 'clubs_section_bg_embed', '' );
$show_more_button = get_theme_mod( 'clubs_section_show_more_button', true );
$more_button_text = get_theme_mod( 'clubs_section_more_button_text', __( 'جميع النوادي', 'nadiim' ) );

// Query النوادي
$clubs_args = array(
	'post_type'      => 'reading_clubs',
	'posts_per_page' => $clubs_count,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
);

$clubs_query = new WP_Query( $clubs_args );

// إذا لم توجد نوادي، لا تعرض القسم
if ( ! $clubs_query->have_posts() ) {
	return;
}

// تحديد classes للقسم
$section_classes = array( 'clubs-section' );
if ( $bg_enable ) {
	$section_classes[] = 'section-with-bg';
}
if ( $layout === 'carousel' ) {
	$section_classes[] = 'layout-carousel';
} else {
	$section_classes[] = 'layout-grid';
}

// inline style للخلفية
$section_style = '';
if ( $bg_enable && ! empty( $bg_image ) ) {
	$section_style = sprintf( 'background-image: url(%s);', esc_url( $bg_image ) );
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>"
         <?php if ( ! empty( $section_style ) ) : ?>style="<?php echo esc_attr( $section_style ); ?>"<?php endif; ?>
         aria-labelledby="clubs-section-title">

	<?php if ( $bg_enable ) : ?>
		<!-- طبقة التعتيم فوق الخلفية -->
		<div class="section-bg-overlay" aria-hidden="true"></div>

		<?php if ( ! empty( $bg_embed ) ) : ?>
			<!-- Embed الخلفية (فيديو مثلاً) -->
			<div class="section-bg-embed" aria-hidden="true">
				<?php echo wp_kses_post( $bg_embed ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="container">

		<!-- رأس القسم -->
		<div class="section-header">
			<?php if ( ! empty( $section_title ) ) : ?>
				<h2 id="clubs-section-title" class="section-title">
					<?php echo esc_html( $section_title ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( ! empty( $section_subtitle ) ) : ?>
				<p class="section-subtitle">
					<?php echo esc_html( $section_subtitle ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .section-header -->

		<?php if ( $layout === 'carousel' ) : ?>

			<!-- Carousel Layout -->
			<div class="swiper clubs-swiper">
				<div class="swiper-wrapper">

					<?php
					// عرض كل بطاقة
					while ( $clubs_query->have_posts() ) :
						$clubs_query->the_post();
						set_query_var( 'club_id', get_the_ID() );
						echo '<div class="swiper-slide">';
						get_template_part( 'template-parts/components/club-card' );
						echo '</div>';
					endwhile;
					?>

				</div><!-- .swiper-wrapper -->

				<!-- أزرار التنقل -->
				<button class="swiper-button-prev" aria-label="<?php esc_attr_e( 'السابق', 'nadiim' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
					</svg>
				</button>
				<button class="swiper-button-next" aria-label="<?php esc_attr_e( 'التالي', 'nadiim' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
					</svg>
				</button>

				<!-- Pagination -->
				<div class="swiper-pagination"></div>

			</div><!-- .swiper -->

		<?php else : ?>

			<!-- Grid Layout -->
			<div class="clubs-grid">

				<?php
				// عرض كل بطاقة
				while ( $clubs_query->have_posts() ) :
					$clubs_query->the_post();
					set_query_var( 'club_id', get_the_ID() );
					get_template_part( 'template-parts/components/club-card' );
				endwhile;
				?>

			</div><!-- .clubs-grid -->

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

		<!-- زر "جميع النوادي" -->
		<?php if ( $show_more_button ) : ?>
			<div class="section-more-button">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'reading_clubs' ) ); ?>" class="more-button">
					<?php echo esc_html( $more_button_text ); ?>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
					</svg>
				</a>
			</div><!-- .section-more-button -->
		<?php endif; ?>

	</div><!-- .container -->

</section><!-- .clubs-section -->
