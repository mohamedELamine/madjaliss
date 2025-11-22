<?php
/**
 * Template part لقسم الإصدارات - تصميم عصري محدّث
 *
 * @package Nadiim
 * @since 2.1.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'esdar_section_enable', true ) ) {
	return;
}

// الحصول على الإعدادات
$section_title      = get_theme_mod( 'esdar_section_title', __( 'مكتبة الإصدارات', 'nadiim' ) );
$section_subtitle   = get_theme_mod( 'esdar_section_subtitle', __( 'اكتشف أحدث إصداراتنا من الكتب والتقارير والمجلات الرقمية', 'nadiim' ) );
$source             = get_theme_mod( 'esdar_section_source', 'latest' );
$count              = get_theme_mod( 'esdar_section_count', 8 );
$layout             = get_theme_mod( 'esdar_section_layout', 'grid' );

// مصفوفة الإصدارات
$esdar_items = array();

// جلب البيانات حسب المصدر
switch ( $source ) {
	case 'latest':
		$esdar_items = nadiim_get_esdar_from_cpt( $count );
		break;

	case 'tag':
		$tag = get_theme_mod( 'esdar_section_tag', 'featured' );
		$esdar_items = nadiim_get_esdar_from_cpt( $count, $tag );
		break;

	case 'manual':
		$esdar_items = nadiim_get_esdar_manual();
		break;

	default:
		$esdar_items = nadiim_get_esdar_from_cpt( $count );
}

// إذا لم توجد إصدارات حقيقية، لا تعرض القسم
if ( empty( $esdar_items ) ) {
	return;
}

// إعدادات الخلفية
$bg_enable = get_theme_mod( 'esdar_section_bg_enable', false );
$bg_image  = get_theme_mod( 'esdar_section_bg_image', '' );

// تحميل CSS الخاص بالقسم
wp_enqueue_style( 'nadiim-esdar', get_template_directory_uri() . '/assets/css/esdar.css', array(), '2.1.0' );

// بناء class للقسم
$section_class = 'esdar-section-modern esdar-section section-padding';
if ( $bg_enable && ! empty( $bg_image ) ) {
	$section_class .= ' section-with-bg';
}

// بناء style للخلفية
$section_style = '';
if ( $bg_enable && ! empty( $bg_image ) ) {
	$section_style = sprintf( 'background-image: url(%s);', esc_url( $bg_image ) );
}
?>

<section class="<?php echo esc_attr( $section_class ); ?>" aria-labelledby="esdar-section-title" <?php if ( $section_style ) echo 'style="' . esc_attr( $section_style ) . '"'; ?>>

	<?php if ( $bg_enable && ! empty( $bg_image ) ) : ?>
		<!-- طبقة التعتيم -->
		<div class="section-bg-overlay"></div>
	<?php endif; ?>

	<!-- خلفية متحركة -->
	<div class="esdar-bg-pattern" aria-hidden="true">
		<div class="pattern-circle pattern-circle-1"></div>
		<div class="pattern-circle pattern-circle-2"></div>
		<div class="pattern-circle pattern-circle-3"></div>
	</div>

	<div class="section-container">

		<!-- رأس القسم -->
		<div class="section-header text-center">
			<?php if ( ! empty( $section_title ) ) : ?>
				<div class="section-badge" data-aos="fade-up">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 4h2v5l-1-.75L9 9V4zm9 16H6V4h1v9l3-2.25L13 13V4h5v16z"/>
					</svg>
					<span><?php esc_html_e( 'إصداراتنا', 'nadiim' ); ?></span>
				</div>
				<h2 id="esdar-section-title" class="section-title" data-aos="fade-up" data-aos-delay="100">
					<?php echo esc_html( $section_title ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( ! empty( $section_subtitle ) ) : ?>
				<p class="section-description" data-aos="fade-up" data-aos-delay="200">
					<?php echo esc_html( $section_subtitle ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .section-header -->

		<?php if ( $layout === 'carousel' ) : ?>

			<!-- Carousel Layout -->
			<div class="swiper esdar-modern-swiper" data-aos="fade-up" data-aos-delay="300">
				<div class="swiper-wrapper">

					<?php
					// عرض كل بطاقة
					foreach ( $esdar_items as $index => $item ) :
						set_query_var( 'esdar_item', $item );
						set_query_var( 'esdar_index', $index );
						echo '<div class="swiper-slide">';
						get_template_part( 'template-parts/components/esdar-card-modern' );
						echo '</div>';
					endforeach;
					?>

				</div><!-- .swiper-wrapper -->

				<!-- أزرار التنقل -->
				<div class="swiper-nav-buttons">
					<button class="swiper-btn-prev" aria-label="<?php esc_attr_e( 'السابق', 'nadiim' ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
							<path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
						</svg>
					</button>
					<button class="swiper-btn-next" aria-label="<?php esc_attr_e( 'التالي', 'nadiim' ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
							<path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
						</svg>
					</button>
				</div>

				<!-- Pagination -->
				<div class="swiper-pagination-modern"></div>

			</div><!-- .swiper -->

		<?php else : ?>

			<!-- Grid Layout المُحدث -->
			<div class="esdar-modern-grid" data-aos="fade-up" data-aos-delay="300">

				<?php
				// عرض كل بطاقة
				foreach ( $esdar_items as $index => $item ) :
					set_query_var( 'esdar_item', $item );
					set_query_var( 'esdar_index', $index );
					get_template_part( 'template-parts/components/esdar-card-modern' );
				endforeach;
				?>

			</div><!-- .esdar-modern-grid -->

		<?php endif; ?>

		<?php
		// زر "اطلع على المزيد"
		$show_more_button = get_theme_mod( 'esdar_section_show_more_button', true );
		if ( $show_more_button ) :
			$button_text = get_theme_mod( 'esdar_section_more_button_text', __( 'استكشف جميع الإصدارات', 'nadiim' ) );
			$button_link = get_theme_mod( 'esdar_section_more_button_link', get_post_type_archive_link( 'esdar' ) );
		?>
			<!-- زر المزيد -->
			<div class="section-cta" data-aos="fade-up" data-aos-delay="400">
				<a href="<?php echo esc_url( $button_link ); ?>" class="btn-modern btn-primary">
					<span><?php echo esc_html( $button_text ); ?></span>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
					</svg>
				</a>
			</div><!-- .section-cta -->
		<?php endif; ?>

	</div><!-- .section-container -->

</section><!-- .esdar-section-modern -->

<?php
/**
 * دالة للحصول على إصدارات من CPT
 */
function nadiim_get_esdar_from_cpt( $count = 8, $tag = '' ) {
	$args = array(
		'post_type'      => 'esdar',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	// إذا كان هناك تاج
	if ( ! empty( $tag ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $tag,
			),
		);
	}

	$query = new WP_Query( $args );
	$items = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			// الحصول على الصورة المميزة
			$image_url = get_the_post_thumbnail_url( $post_id, 'large' );
			if ( ! $image_url ) {
				$image_url = get_template_directory_uri() . '/assets/images/placeholder-book.jpg';
			}

			// نوع الإصدار
			$type = get_post_meta( $post_id, '_esdar_type', true );
			if ( empty( $type ) ) {
				$type = 'book';
			}

			// تاريخ النشر
			$date = get_post_meta( $post_id, '_esdar_release_date', true );
			if ( empty( $date ) ) {
				$date = get_the_date( 'Y-m-d', $post_id );
			}

			// عدد الصفحات
			$pages = get_post_meta( $post_id, '_esdar_pages', true );

			// رابط التحميل
			$download_url = get_post_meta( $post_id, '_esdar_download_url', true );

			// صيغة الملف
			$format = get_post_meta( $post_id, '_esdar_format', true );

			// بناء مصفوفة العنصر
			$items[] = array(
				'title'        => get_the_title(),
				'excerpt'      => wp_trim_words( get_the_excerpt(), 12, '...' ),
				'image'        => $image_url,
				'type'         => $type,
				'type_label'   => nadiim_get_esdar_type_label( $type ),
				'date'         => $date,
				'pages'        => $pages,
				'format'       => $format,
				'download_url' => $download_url,
				'view_link'    => get_permalink(),
			);
		}
		wp_reset_postdata();
	}

	return $items;
}

/**
 * دالة للحصول على إصدارات يدوية من JSON
 */
function nadiim_get_esdar_manual() {
	$json_string = get_theme_mod( 'esdar_section_manual_json', '' );

	if ( empty( $json_string ) ) {
		return array();
	}

	$items = json_decode( $json_string, true );

	if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $items ) ) {
		return array();
	}

	return $items;
}

