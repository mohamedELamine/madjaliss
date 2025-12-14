<?php
/**
 * إعدادات قسم المراجعات في Customizer
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات المراجعات
 */
function nadiim_reviews_customizer_register( $wp_customize ) {

	// ============================================
	// قسم المراجعات
	// ============================================

	$wp_customize->add_section(
		'nadiim_reviews',
		array(
			'title'       => __( 'المراجعات', 'nadiim' ),
			'description' => __( 'إعدادات قسم المراجعات في الصفحة الرئيسية', 'nadiim' ),
			'panel'       => 'nadiim_front_page_panel',
			'priority'    => 45,
		)
	);

	// ─────────────────────────────────────
	// إعدادات عامة
	// ─────────────────────────────────────

	// تفعيل قسم المراجعات
	$wp_customize->add_setting(
		'reviews_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_enable',
		array(
			'label'       => __( 'تفعيل قسم المراجعات', 'nadiim' ),
			'description' => __( 'عرض/إخفاء قسم المراجعات في الصفحة الرئيسية', 'nadiim' ),
			'section'     => 'nadiim_reviews',
			'type'        => 'checkbox',
		)
	);

	// عنوان القسم
	$wp_customize->add_setting(
		'reviews_title',
		array(
			'default'           => __( 'المراجعات', 'nadiim' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_title',
		array(
			'label'   => __( 'عنوان القسم', 'nadiim' ),
			'section' => 'nadiim_reviews',
			'type'    => 'text',
		)
	);

	// وصف القسم
	$wp_customize->add_setting(
		'reviews_description',
		array(
			'default'           => __( 'آراء صادقة ومراجعات متعمقة للكتب والأفلام والأعمال الفنية', 'nadiim' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_description',
		array(
			'label'   => __( 'وصف القسم', 'nadiim' ),
			'section' => 'nadiim_reviews',
			'type'    => 'textarea',
		)
	);

	// مصدر المحتوى
	$wp_customize->add_setting(
		'reviews_source',
		array(
			'default'           => 'latest',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'reviews_source',
		array(
			'label'   => __( 'مصدر المحتوى', 'nadiim' ),
			'section' => 'nadiim_reviews',
			'type'    => 'select',
			'choices' => array(
				'latest'      => __( 'أحدث المراجعات', 'nadiim' ),
				'high_rating' => __( 'الأعلى تقييماً', 'nadiim' ),
				'category'    => __( 'مراجعات من تصنيف محدد', 'nadiim' ),
			),
		)
	);

	// تصنيف المراجعات
	$wp_customize->add_setting(
		'reviews_category',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	// الحصول على التصنيفات
	$categories = get_categories( array( 'hide_empty' => false ) );
	$category_choices = array( '' => __( 'اختر تصنيفاً', 'nadiim' ) );
	foreach ( $categories as $category ) {
		$category_choices[ $category->term_id ] = $category->name;
	}

	$wp_customize->add_control(
		'reviews_category',
		array(
			'label'           => __( 'التصنيف', 'nadiim' ),
			'description'     => __( 'اختر التصنيف المطلوب', 'nadiim' ),
			'section'         => 'nadiim_reviews',
			'type'            => 'select',
			'choices'         => $category_choices,
			'active_callback' => function () {
				return get_theme_mod( 'reviews_source', 'latest' ) === 'category';
			},
		)
	);

	// عدد المراجعات
	$wp_customize->add_setting(
		'reviews_count',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'reviews_count',
		array(
			'label'       => __( 'عدد المراجعات', 'nadiim' ),
			'section'     => 'nadiim_reviews',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 2,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	// نوع التخطيط
	$wp_customize->add_setting(
		'reviews_layout',
		array(
			'default'           => 'grid',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_layout',
		array(
			'label'   => __( 'نوع التخطيط', 'nadiim' ),
			'section' => 'nadiim_reviews',
			'type'    => 'select',
			'choices' => array(
				'grid'     => __( 'شبكة (Grid)', 'nadiim' ),
				'carousel' => __( 'سلايدر (Carousel)', 'nadiim' ),
			),
		)
	);

	// إظهار التقييم
	$wp_customize->add_setting(
		'reviews_show_rating',
		array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_show_rating',
		array(
			'label'       => __( 'إظهار التقييم بالنجوم', 'nadiim' ),
			'description' => __( 'عرض التقييم بالنجوم على البطاقات', 'nadiim' ),
			'section'     => 'nadiim_reviews',
			'type'        => 'checkbox',
		)
	);

	// ─────────────────────────────────────
	// إعدادات ألوان النص
	// ─────────────────────────────────────

	// لون عنوان القسم
	$wp_customize->add_setting(
		'reviews_title_color',
		array(
			'default'           => '#1c2d27',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'reviews_title_color',
			array(
				'label'       => __( 'لون عنوان القسم', 'nadiim' ),
				'description' => __( 'لون نص "المراجعات"', 'nadiim' ),
				'section'     => 'nadiim_reviews',
			)
		)
	);

	// لون وصف القسم
	$wp_customize->add_setting(
		'reviews_description_color',
		array(
			'default'           => '#5a6c64',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'reviews_description_color',
			array(
				'label'       => __( 'لون وصف القسم', 'nadiim' ),
				'description' => __( 'لون نص الوصف', 'nadiim' ),
				'section'     => 'nadiim_reviews',
			)
		)
	);

	// لون النجوم
	$wp_customize->add_setting(
		'reviews_star_color',
		array(
			'default'           => '#f39c12',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'reviews_star_color',
			array(
				'label'       => __( 'لون النجوم', 'nadiim' ),
				'description' => __( 'لون النجوم في التقييم', 'nadiim' ),
				'section'     => 'nadiim_reviews',
			)
		)
	);

	// ─────────────────────────────────────
	// إعدادات زر "اطلع على المزيد"
	// ─────────────────────────────────────

	// إظهار زر المزيد
	$wp_customize->add_setting(
		'reviews_show_more_button',
		array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_show_more_button',
		array(
			'label'       => __( 'إظهار زر "اطلع على المزيد"', 'nadiim' ),
			'description' => __( 'عرض زر في أسفل القسم للانتقال إلى صفحة جميع المراجعات', 'nadiim' ),
			'section'     => 'nadiim_reviews',
			'type'        => 'checkbox',
		)
	);

	// نص زر المزيد
	$wp_customize->add_setting(
		'reviews_more_button_text',
		array(
			'default'           => __( 'اطلع على المزيد من المراجعات', 'nadiim' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_more_button_text',
		array(
			'label'           => __( 'نص الزر', 'nadiim' ),
			'section'         => 'nadiim_reviews',
			'type'            => 'text',
			'active_callback' => function () {
				return get_theme_mod( 'reviews_show_more_button', true );
			},
		)
	);

	// رابط زر المزيد
	$reviews_archive_url = get_post_type_archive_link( 'reviews' ) ?: '#';
	$wp_customize->add_setting(
		'reviews_more_button_link',
		array(
			'default'           => $reviews_archive_url,
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_more_button_link',
		array(
			'label'           => __( 'رابط الزر', 'nadiim' ),
			'description'     => __( 'الرابط الذي سينتقل إليه المستخدم عند النقر على الزر', 'nadiim' ),
			'section'         => 'nadiim_reviews',
			'type'            => 'url',
			'active_callback' => function () {
				return get_theme_mod( 'reviews_show_more_button', true );
			},
		)
	);

	// ─────────────────────────────────────
	// إعدادات خلفية القسم
	// ─────────────────────────────────────

	// تفعيل خلفية مخصصة
	$wp_customize->add_setting(
		'reviews_bg_enable',
		array(
			'default'           => false,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_bg_enable',
		array(
			'label'       => __( 'تفعيل خلفية مخصصة للقسم', 'nadiim' ),
			'description' => __( 'إضافة صورة أو فيديو خلفية للقسم بأكمله', 'nadiim' ),
			'section'     => 'nadiim_reviews',
			'type'        => 'checkbox',
		)
	);

	// صورة الخلفية
	$wp_customize->add_setting(
		'reviews_bg_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'reviews_bg_image',
			array(
				'label'           => __( 'صورة الخلفية', 'nadiim' ),
				'description'     => __( 'اختر صورة خلفية للقسم', 'nadiim' ),
				'section'         => 'nadiim_reviews',
				'active_callback' => function () {
					return get_theme_mod( 'reviews_bg_enable', false );
				},
			)
		)
	);

	// Embed الخلفية (iframe)
	$wp_customize->add_setting(
		'reviews_bg_embed',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_bg_embed',
		array(
			'label'           => __( 'كود Embed للخلفية (اختياري)', 'nadiim' ),
			'description'     => __( 'يمكنك إضافة iframe لفيديو خلفية مثلاً', 'nadiim' ),
			'section'         => 'nadiim_reviews',
			'type'            => 'textarea',
			'active_callback' => function () {
				return get_theme_mod( 'reviews_bg_enable', false );
			},
		)
	);

	// شفافية الـ Overlay
	$wp_customize->add_setting(
		'reviews_overlay_opacity',
		array(
			'default'           => 0.30,
			'sanitize_callback' => 'nadiim_sanitize_float',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'reviews_overlay_opacity',
		array(
			'label'           => __( 'شفافية طبقة التعتيم', 'nadiim' ),
			'description'     => __( '0 = شفاف تماماً، 1 = معتم تماماً. يُنصح بقيمة 0.3-0.5 لضمان وضوح النص', 'nadiim' ),
			'section'         => 'nadiim_reviews',
			'type'            => 'number',
			'input_attrs'     => array(
				'min'  => 0,
				'max'  => 1,
				'step' => 0.05,
			),
			'active_callback' => function () {
				return get_theme_mod( 'reviews_bg_enable', false );
			},
		)
	);

	// لون الـ Overlay
	$wp_customize->add_setting(
		'reviews_overlay_color',
		array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'reviews_overlay_color',
			array(
				'label'           => __( 'لون طبقة التعتيم', 'nadiim' ),
				'section'         => 'nadiim_reviews',
				'active_callback' => function () {
					return get_theme_mod( 'reviews_bg_enable', false );
				},
			)
		)
	);

}
add_action( 'customize_register', 'nadiim_reviews_customizer_register' );

/**
 * CSS مخصص لقسم المراجعات
 */
function nadiim_reviews_customizer_css() {
	if ( ! is_front_page() ) {
		return;
	}

	$overlay_opacity   = get_theme_mod( 'reviews_overlay_opacity', 0.30 );
	$overlay_color     = get_theme_mod( 'reviews_overlay_color', '#000000' );
	$bg_enable         = get_theme_mod( 'reviews_bg_enable', false );
	$title_color       = get_theme_mod( 'reviews_title_color', '#1c2d27' );
	$description_color = get_theme_mod( 'reviews_description_color', '#5a6c64' );
	$star_color        = get_theme_mod( 'reviews_star_color', '#f39c12' );

	?>
	<style type="text/css" id="nadiim-reviews-custom-css">
		:root {
			--reviews-overlay-opacity: <?php echo floatval( $overlay_opacity ); ?>;
			--reviews-title-color: <?php echo esc_attr( $title_color ); ?>;
			--reviews-description-color: <?php echo esc_attr( $description_color ); ?>;
			--reviews-star-custom-color: <?php echo esc_attr( $star_color ); ?>;
		}

		<?php if ( ! get_theme_mod( 'reviews_enable', true ) ) : ?>
		.reviews-section {
			display: none !important;
		}
		<?php endif; ?>

		<?php if ( $bg_enable ) : ?>
		.reviews-section .section-bg-overlay {
			background-color: <?php echo esc_attr( $overlay_color ); ?>;
			opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}
		<?php endif; ?>

		.reviews-section .section-header h2 {
			color: var(--reviews-title-color);
		}

		.reviews-section .section-header p {
			color: var(--reviews-description-color);
		}

		.reviews-section .star-filled {
			color: var(--reviews-star-custom-color);
		}
	</style>
	<?php
}
add_action( 'wp_head', 'nadiim_reviews_customizer_css', 20 );

/**
 * دالة sanitize لـ float
 */
if ( ! function_exists( 'nadiim_sanitize_float' ) ) {
	function nadiim_sanitize_float( $value ) {
		return floatval( $value );
	}
}
