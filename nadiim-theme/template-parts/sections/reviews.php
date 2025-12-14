<?php
/**
 * Template part لقسم المراجعات
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'reviews_enable', true ) ) {
	return;
}

// الحصول على الإعدادات
$section_title       = get_theme_mod( 'reviews_title', __( 'المراجعات', 'nadiim' ) );
$section_description = get_theme_mod( 'reviews_description', __( 'آراء صادقة ومراجعات متعمقة للكتب والأفلام والأعمال الفنية', 'nadiim' ) );
$source              = get_theme_mod( 'reviews_source', 'latest' );
$count               = get_theme_mod( 'reviews_count', 4 );
$layout              = get_theme_mod( 'reviews_layout', 'grid' );
$show_rating         = get_theme_mod( 'reviews_show_rating', true );
$show_more_button    = get_theme_mod( 'reviews_show_more_button', true );
$more_button_text    = get_theme_mod( 'reviews_more_button_text', __( 'اطلع على المزيد من المراجعات', 'nadiim' ) );
$more_button_link    = get_theme_mod( 'reviews_more_button_link', get_post_type_archive_link( 'reviews' ) ?: '#' );
$bg_enable           = get_theme_mod( 'reviews_bg_enable', false );
$bg_image            = get_theme_mod( 'reviews_bg_image', '' );
$bg_embed            = get_theme_mod( 'reviews_bg_embed', '' );
$title_color         = get_theme_mod( 'reviews_title_color', '#1c2d27' );
$description_color   = get_theme_mod( 'reviews_description_color', '#5a6c64' );

// إعداد الاستعلام
$args = array(
	'post_type'      => 'reviews',
	'posts_per_page' => $count,
	'post_status'    => 'publish',
);

// تعديل الاستعلام حسب المصدر
switch ( $source ) {
	case 'high_rating':
		$args['meta_key'] = '_review_rating';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
		break;

	case 'category':
		$category = get_theme_mod( 'reviews_category', '' );
		if ( $category ) {
			$args['cat'] = absint( $category );
		}
		break;

	case 'latest':
	default:
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
		break;
}

// تنفيذ الاستعلام
$reviews_query = new WP_Query( $args );

// إذا لم توجد مراجعات، لا تعرض القسم
if ( ! $reviews_query->have_posts() ) {
	return;
}

// تحديد classes للقسم
$section_classes = array( 'reviews-section' );
if ( $bg_enable ) {
	$section_classes[] = 'section-with-bg';
}
if ( $layout === 'carousel' ) {
	$section_classes[] = 'layout-carousel';
} else {
	$section_classes[] = 'layout-grid';
}

// inline style للخلفية والألوان
$section_style = sprintf(
	'--reviews-title-color: %s; --reviews-description-color: %s;',
	esc_attr( $title_color ),
	esc_attr( $description_color )
);

if ( $bg_enable && ! empty( $bg_image ) ) {
	$section_style .= sprintf( ' background-image: url(%s);', esc_url( $bg_image ) );
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>"
         style="<?php echo esc_attr( $section_style ); ?>"
         aria-labelledby="reviews-title">

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
				<h2 id="reviews-title" class="section-title">
					<?php echo esc_html( $section_title ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( ! empty( $section_description ) ) : ?>
				<p class="section-description">
					<?php echo esc_html( $section_description ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .section-header -->

		<?php if ( $layout === 'carousel' ) : ?>

			<!-- Carousel Layout -->
			<div class="swiper reviews-swiper">
				<div class="swiper-wrapper">

					<?php
					while ( $reviews_query->have_posts() ) :
						$reviews_query->the_post();
						?>
						<div class="swiper-slide">
							<?php
							// تمرير البيانات إلى البطاقة
							set_query_var( 'show_rating', $show_rating );
							get_template_part( 'template-parts/components/review-card' );
							?>
						</div>
					<?php endwhile; ?>

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
			<div class="reviews-grid-home">

				<?php
				while ( $reviews_query->have_posts() ) :
					$reviews_query->the_post();

					// تمرير البيانات إلى البطاقة
					set_query_var( 'show_rating', $show_rating );
					get_template_part( 'template-parts/components/review-card' );

				endwhile;
				?>

			</div><!-- .reviews-grid-home -->

		<?php endif; ?>

		<?php
		// إعادة تعيين post data
		wp_reset_postdata();
		?>

		<!-- زر "اطلع على المزيد" -->
		<?php if ( $show_more_button && ! empty( $more_button_text ) ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( $more_button_link ); ?>" class="btn btn-primary btn-section-more">
					<?php echo esc_html( $more_button_text ); ?>
					<svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<line x1="5" y1="12" x2="19" y2="12"/>
						<polyline points="12 5 19 12 12 19"/>
					</svg>
				</a>
			</div>
		<?php endif; ?>

	</div><!-- .container -->

</section><!-- .reviews-section -->

<style>
.reviews-grid-home {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
	gap: 32px;
	margin-top: 48px;
}

.reviews-section {
	padding: 64px 0;
}

.reviews-section .section-header {
	text-align: center;
	margin-bottom: 48px;
}

.reviews-section .section-title {
	font-size: 36px;
	font-weight: 700;
	color: #1c2d27;
	margin-bottom: 16px;
}

.reviews-section .section-description {
	font-size: 18px;
	color: #5a6c64;
	max-width: 600px;
	margin: 0 auto;
	line-height: 1.7;
}

.reviews-section .section-footer {
	text-align: center;
	margin-top: 48px;
}

.btn-section-more {
	display: inline-flex;
	align-items: center;
	gap: 10px;
	padding: 14px 32px;
	background-color: #339063;
	color: #ffffff;
	font-size: 16px;
	font-weight: 700;
	border-radius: 12px;
	text-decoration: none;
	transition: all 0.3s ease;
	box-shadow: 0 4px 12px rgba(51, 144, 99, 0.2);
}

.btn-section-more:hover {
	background-color: #2a7851;
	transform: translateY(-3px);
	box-shadow: 0 6px 20px rgba(51, 144, 99, 0.3);
	color: #ffffff;
}

.btn-section-more .btn-icon {
	transition: transform 0.3s ease;
}

.btn-section-more:hover .btn-icon {
	transform: translateX(-4px);
}

/* Swiper styles */
.reviews-swiper {
	position: relative;
	padding: 20px 0 60px;
}

.reviews-swiper .swiper-button-prev,
.reviews-swiper .swiper-button-next {
	width: 44px;
	height: 44px;
	background: #ffffff;
	border-radius: 50%;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	color: #339063;
	transition: all 0.3s ease;
}

.reviews-swiper .swiper-button-prev:hover,
.reviews-swiper .swiper-button-next:hover {
	background: #339063;
	color: #ffffff;
	box-shadow: 0 6px 20px rgba(51, 144, 99, 0.3);
}

.reviews-swiper .swiper-button-prev::after,
.reviews-swiper .swiper-button-next::after {
	font-size: 16px;
	font-weight: bold;
}

.reviews-swiper .swiper-pagination {
	bottom: 0;
}

.reviews-swiper .swiper-pagination-bullet {
	background: #339063;
	opacity: 0.3;
	width: 10px;
	height: 10px;
}

.reviews-swiper .swiper-pagination-bullet-active {
	opacity: 1;
	width: 24px;
	border-radius: 5px;
}

@media (max-width: 768px) {
	.reviews-grid-home {
		grid-template-columns: 1fr;
		gap: 24px;
	}

	.reviews-section {
		padding: 48px 0;
	}

	.reviews-section .section-title {
		font-size: 28px;
	}

	.reviews-section .section-description {
		font-size: 16px;
	}
}
</style>
