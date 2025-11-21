<?php
/**
 * Template part لقسم الإصدارات
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'esdar_section_enable', true ) ) {
	return;
}

// الحصول على الإعدادات
$section_title      = get_theme_mod( 'esdar_section_title', __( 'إصدارات نديم', 'nadiim' ) );
$section_subtitle   = get_theme_mod( 'esdar_section_subtitle', __( 'اكتشف أحدث إصداراتنا من الكتب والتقارير والمجلات', 'nadiim' ) );
$source             = get_theme_mod( 'esdar_section_source', 'latest' );
$count              = get_theme_mod( 'esdar_section_count', 6 );
$layout             = get_theme_mod( 'esdar_section_layout', 'grid' );
$bg_enable          = get_theme_mod( 'esdar_section_bg_enable', false );
$bg_image           = get_theme_mod( 'esdar_section_bg_image', '' );

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

// Fallback إلى demo data إذا لم توجد إصدارات
if ( empty( $esdar_items ) ) {
	$esdar_items = nadiim_get_esdar_demo();
}

// إذا ما زالت فارغة، لا تعرض شيئاً
if ( empty( $esdar_items ) ) {
	return;
}

// تحديد classes للقسم
$section_classes = array( 'esdar-section' );
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
         aria-labelledby="esdar-section-title">

	<?php if ( $bg_enable ) : ?>
		<!-- طبقة التعتيم فوق الخلفية -->
		<div class="section-bg-overlay" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="container">

		<!-- رأس القسم -->
		<div class="section-header">
			<?php if ( ! empty( $section_title ) ) : ?>
				<h2 id="esdar-section-title" class="section-title">
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
			<div class="swiper esdar-swiper">
				<div class="swiper-wrapper">

					<?php
					// عرض كل بطاقة
					foreach ( $esdar_items as $index => $item ) :
						set_query_var( 'esdar_item', $item );
						echo '<div class="swiper-slide">';
						get_template_part( 'template-parts/components/esdar-card' );
						echo '</div>';
					endforeach;
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
			<div class="esdar-grid">

				<?php
				// عرض كل بطاقة
				foreach ( $esdar_items as $index => $item ) :
					set_query_var( 'esdar_item', $item );
					get_template_part( 'template-parts/components/esdar-card' );
				endforeach;
				?>

			</div><!-- .esdar-grid -->

		<?php endif; ?>

		<?php
		// زر "اطلع على المزيد"
		$show_more_button = get_theme_mod( 'esdar_section_show_more_button', true );
		if ( $show_more_button ) :
			$button_text = get_theme_mod( 'esdar_section_more_button_text', __( 'استكشف جميع الإصدارات', 'nadiim' ) );
			$button_link = get_theme_mod( 'esdar_section_more_button_link', '#' );
		?>
			<!-- زر المزيد -->
			<div class="section-more-button">
				<a href="<?php echo esc_url( $button_link ); ?>" class="more-button">
					<?php echo esc_html( $button_text ); ?>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
					</svg>
				</a>
			</div><!-- .section-more-button -->
		<?php endif; ?>

	</div><!-- .container -->

</section><!-- .esdar-section -->

<?php
/**
 * دالة للحصول على إصدارات من CPT
 */
function nadiim_get_esdar_from_cpt( $count = 6, $tag = '' ) {
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
				'excerpt'      => wp_trim_words( get_the_excerpt(), 15, '...' ),
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

/**
 * دالة للحصول على demo data
 */
function nadiim_get_esdar_demo() {
	$demo_file = get_template_directory() . '/demo/esdar-demo.json';

	if ( ! file_exists( $demo_file ) ) {
		return array();
	}

	$json_string = file_get_contents( $demo_file );
	$items = json_decode( $json_string, true );

	if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $items ) ) {
		return array();
	}

	return $items;
}
