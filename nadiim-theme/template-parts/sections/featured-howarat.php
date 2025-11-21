<?php
/**
 * Template part لقسم الحوارات المميزة
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'featured_howarat_enable', true ) ) {
	return;
}

// الحصول على الإعدادات
$section_title       = get_theme_mod( 'featured_howarat_title', __( 'الحوارات المميزة', 'nadiim' ) );
$section_description = get_theme_mod( 'featured_howarat_description', __( 'تعرف على أهم الحوارات والنقاشات الثقافية', 'nadiim' ) );
$source              = get_theme_mod( 'featured_howarat_source', 'latest_howarat' );
$count               = get_theme_mod( 'featured_howarat_count', 4 );
$layout              = get_theme_mod( 'featured_howarat_layout', 'grid' );
$bg_enable           = get_theme_mod( 'featured_howarat_bg_enable', false );
$bg_image            = get_theme_mod( 'featured_howarat_bg_image', '' );
$bg_embed            = get_theme_mod( 'featured_howarat_bg_embed', '' );

// مصفوفة البطاقات
$cards = array();

// جلب البيانات حسب المصدر
switch ( $source ) {
	case 'latest_howarat':
		$cards = nadiim_get_featured_howarat_from_cpt( $count );
		break;

	case 'tag':
		$tag = get_theme_mod( 'featured_howarat_tag', 'featured' );
		$cards = nadiim_get_featured_howarat_from_cpt( $count, $tag );
		break;

	case 'manual':
		$cards = nadiim_get_featured_howarat_manual();
		break;

	default:
		$cards = nadiim_get_featured_howarat_from_cpt( $count );
}

// Fallback إلى demo data إذا لم توجد بطاقات
if ( empty( $cards ) ) {
	$cards = nadiim_get_featured_howarat_demo();
}

// إذا ما زالت فارغة، لا تعرض شيئاً
if ( empty( $cards ) ) {
	return;
}

// تحديد classes للقسم
$section_classes = array( 'featured-howarat-section' );
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
         aria-labelledby="featured-howarat-title">

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
				<h2 id="featured-howarat-title" class="section-title">
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
			<div class="swiper featured-howarat-swiper">
				<div class="swiper-wrapper">

					<?php
					// عرض كل بطاقة
					foreach ( $cards as $index => $card ) :
						set_query_var( 'howarat_card', $card );
						echo '<div class="swiper-slide">';
						get_template_part( 'template-parts/components/howarat-card' );
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
			<div class="howarat-grid">

				<?php
				// عرض كل بطاقة
				foreach ( $cards as $index => $card ) :
					set_query_var( 'howarat_card', $card );
					get_template_part( 'template-parts/components/howarat-card' );
				endforeach;
				?>

			</div><!-- .howarat-grid -->

		<?php endif; ?>

	</div><!-- .container -->

</section><!-- .featured-howarat-section -->

<?php
/**
 * دالة للحصول على بطاقات من CPT الحوارات
 */
function nadiim_get_featured_howarat_from_cpt( $count = 4, $tag = '' ) {
	$args = array(
		'post_type'      => 'howarat',
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
	$cards = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			// الحصول على الصورة المميزة
			$image_url = get_the_post_thumbnail_url( $post_id, 'large' );
			if ( ! $image_url ) {
				$image_url = get_template_directory_uri() . '/assets/images/placeholder.jpg';
			}

			// نوع الوسائط
			$media_type = get_post_meta( $post_id, '_howarat_media_type', true );
			if ( empty( $media_type ) ) {
				// التحقق من وجود ملفات صوت أو فيديو
				$audio_file = get_post_meta( $post_id, '_howarat_audio_file', true );
				$video_file = get_post_meta( $post_id, '_howarat_video_file', true );

				if ( ! empty( $video_file ) ) {
					$media_type = 'video';
				} elseif ( ! empty( $audio_file ) ) {
					$media_type = 'audio';
				} else {
					$media_type = 'text';
				}
			}

			// تاريخ النشر
			$date = get_post_meta( $post_id, '_howarat_date', true );
			if ( empty( $date ) ) {
				$date = get_the_date( 'Y-m-d', $post_id );
			}

			// بناء مصفوفة البطاقة
			$cards[] = array(
				'title'      => get_the_title(),
				'excerpt'    => wp_trim_words( get_the_excerpt(), 20, '...' ),
				'image'      => $image_url,
				'type'       => $media_type,
				'cta_text'   => nadiim_get_cta_text_by_type( $media_type ),
				'cta_link'   => get_permalink(),
				'date'       => $date,
			);
		}
		wp_reset_postdata();
	}

	return $cards;
}

/**
 * دالة للحصول على بطاقات يدوية من JSON
 */
function nadiim_get_featured_howarat_manual() {
	$json_string = get_theme_mod( 'featured_howarat_cards_json', '' );

	if ( empty( $json_string ) ) {
		return array();
	}

	$cards = json_decode( $json_string, true );

	if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $cards ) ) {
		return array();
	}

	return $cards;
}

/**
 * دالة للحصول على بطاقات demo
 */
function nadiim_get_featured_howarat_demo() {
	$demo_file = get_template_directory() . '/demo/featured-howarat-demo.json';

	if ( ! file_exists( $demo_file ) ) {
		return array();
	}

	$json_string = file_get_contents( $demo_file );
	$cards = json_decode( $json_string, true );

	if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $cards ) ) {
		return array();
	}

	return $cards;
}

/**
 * دالة مساعدة للحصول على نص CTA حسب نوع الوسائط
 */
function nadiim_get_cta_text_by_type( $type ) {
	$cta_texts = array(
		'audio' => __( 'استمع للحوار', 'nadiim' ),
		'video' => __( 'شاهد الحوار', 'nadiim' ),
		'text'  => __( 'اقرأ الحوار', 'nadiim' ),
	);

	return isset( $cta_texts[ $type ] ) ? $cta_texts[ $type ] : __( 'اقرأ المزيد', 'nadiim' );
}
