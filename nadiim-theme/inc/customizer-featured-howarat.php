<?php
/**
 * إعدادات قسم الحوارات المميزة في Customizer
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات الحوارات المميزة
 */
function nadiim_featured_howarat_customizer_register( $wp_customize ) {

	// ============================================
	// قسم الحوارات المميزة
	// ============================================

	$wp_customize->add_section( 'nadiim_featured_howarat', array(
		'title'       => __( 'الحوارات المميزة - الصفحة الرئيسية', 'nadiim' ),
		'description' => __( 'إعدادات قسم الحوارات المميزة في الصفحة الرئيسية', 'nadiim' ),
		'priority'    => 45,
	) );

	// ─────────────────────────────────────
	// إعدادات عامة
	// ─────────────────────────────────────

	// تفعيل قسم الحوارات المميزة
	$wp_customize->add_setting( 'featured_howarat_enable', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_enable', array(
		'label'       => __( 'تفعيل قسم الحوارات المميزة', 'nadiim' ),
		'description' => __( 'عرض/إخفاء قسم الحوارات المميزة في الصفحة الرئيسية', 'nadiim' ),
		'section'     => 'nadiim_featured_howarat',
		'type'        => 'checkbox',
	) );

	// عنوان القسم
	$wp_customize->add_setting( 'featured_howarat_title', array(
		'default'           => __( 'الحوارات المميزة', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_title', array(
		'label'   => __( 'عنوان القسم', 'nadiim' ),
		'section' => 'nadiim_featured_howarat',
		'type'    => 'text',
	) );

	// وصف القسم
	$wp_customize->add_setting( 'featured_howarat_description', array(
		'default'           => __( 'تعرف على أهم الحوارات والنقاشات الثقافية', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_description', array(
		'label'   => __( 'وصف القسم', 'nadiim' ),
		'section' => 'nadiim_featured_howarat',
		'type'    => 'textarea',
	) );

	// مصدر المحتوى
	$wp_customize->add_setting( 'featured_howarat_source', array(
		'default'           => 'latest_howarat',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'featured_howarat_source', array(
		'label'   => __( 'مصدر المحتوى', 'nadiim' ),
		'section' => 'nadiim_featured_howarat',
		'type'    => 'select',
		'choices' => array(
			'latest_howarat' => __( 'أحدث الحوارات', 'nadiim' ),
			'tag'            => __( 'حوارات بتاج محدد', 'nadiim' ),
			'manual'         => __( 'يدوي (JSON)', 'nadiim' ),
		),
	) );

	// تاج الحوارات المميزة
	$wp_customize->add_setting( 'featured_howarat_tag', array(
		'default'           => 'featured',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'featured_howarat_tag', array(
		'label'           => __( 'تاج الحوارات المميزة', 'nadiim' ),
		'description'     => __( 'اسم التاج للحوارات المميزة (مثل: featured)', 'nadiim' ),
		'section'         => 'nadiim_featured_howarat',
		'type'            => 'text',
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_source', 'latest_howarat' ) === 'tag';
		},
	) );

	// عدد الحوارات
	$wp_customize->add_setting( 'featured_howarat_count', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'featured_howarat_count', array(
		'label'       => __( 'عدد الحوارات', 'nadiim' ),
		'section'     => 'nadiim_featured_howarat',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 2,
			'max'  => 12,
			'step' => 1,
		),
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_source', 'latest_howarat' ) !== 'manual';
		},
	) );

	// نوع التخطيط
	$wp_customize->add_setting( 'featured_howarat_layout', array(
		'default'           => 'grid',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_layout', array(
		'label'   => __( 'نوع التخطيط', 'nadiim' ),
		'section' => 'nadiim_featured_howarat',
		'type'    => 'select',
		'choices' => array(
			'grid'     => __( 'شبكة (Grid)', 'nadiim' ),
			'carousel' => __( 'سلايدر (Carousel)', 'nadiim' ),
		),
	) );

	// إظهار أيقونات الوسائط
	$wp_customize->add_setting( 'featured_howarat_show_media_icon', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_show_media_icon', array(
		'label'       => __( 'إظهار أيقونات الوسائط', 'nadiim' ),
		'description' => __( 'عرض أيقونة الصوت/الفيديو على البطاقات', 'nadiim' ),
		'section'     => 'nadiim_featured_howarat',
		'type'        => 'checkbox',
	) );

	// ─────────────────────────────────────
	// إعدادات خلفية القسم
	// ─────────────────────────────────────

	// تفعيل خلفية القسم
	$wp_customize->add_setting( 'featured_howarat_bg_enable', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_bg_enable', array(
		'label'       => __( 'تفعيل خلفية مخصصة للقسم', 'nadiim' ),
		'description' => __( 'إضافة صورة أو فيديو خلفية للقسم بأكمله', 'nadiim' ),
		'section'     => 'nadiim_featured_howarat',
		'type'        => 'checkbox',
	) );

	// صورة الخلفية
	$wp_customize->add_setting( 'featured_howarat_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'featured_howarat_bg_image', array(
		'label'           => __( 'صورة الخلفية', 'nadiim' ),
		'description'     => __( 'اختر صورة خلفية للقسم', 'nadiim' ),
		'section'         => 'nadiim_featured_howarat',
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_bg_enable', false );
		},
	) ) );

	// Embed الخلفية (iframe)
	$wp_customize->add_setting( 'featured_howarat_bg_embed', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_bg_embed', array(
		'label'           => __( 'كود Embed للخلفية (اختياري)', 'nadiim' ),
		'description'     => __( 'يمكنك إضافة iframe لفيديو خلفية مثلاً', 'nadiim' ),
		'section'         => 'nadiim_featured_howarat',
		'type'            => 'textarea',
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_bg_enable', false );
		},
	) );

	// شفافية الـ Overlay
	$wp_customize->add_setting( 'featured_howarat_overlay_opacity', array(
		'default'           => 0.30,
		'sanitize_callback' => 'nadiim_sanitize_float',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'featured_howarat_overlay_opacity', array(
		'label'           => __( 'شفافية طبقة التعتيم', 'nadiim' ),
		'description'     => __( '0 = شفاف تماماً، 1 = معتم تماماً. يُنصح بقيمة 0.3-0.5 لضمان وضوح النص', 'nadiim' ),
		'section'         => 'nadiim_featured_howarat',
		'type'            => 'number',
		'input_attrs'     => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.05,
		),
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_bg_enable', false );
		},
	) );

	// لون الـ Overlay
	$wp_customize->add_setting( 'featured_howarat_overlay_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'featured_howarat_overlay_color', array(
		'label'           => __( 'لون طبقة التعتيم', 'nadiim' ),
		'section'         => 'nadiim_featured_howarat',
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_bg_enable', false );
		},
	) ) );

	// ─────────────────────────────────────
	// إعدادات المحتوى اليدوي (Manual Content)
	// ─────────────────────────────────────

	// JSON للبطاقات اليدوية
	$wp_customize->add_setting( 'featured_howarat_cards_json', array(
		'default'           => nadiim_get_default_howarat_json(),
		'sanitize_callback' => 'wp_kses_post',
	) );

	$wp_customize->add_control( 'featured_howarat_cards_json', array(
		'label'           => __( 'بطاقات JSON', 'nadiim' ),
		'description'     => __( 'الصق JSON للبطاقات اليدوية (راجع التوثيق)', 'nadiim' ),
		'section'         => 'nadiim_featured_howarat',
		'type'            => 'textarea',
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_source', 'latest_howarat' ) === 'manual';
		},
	) );

	// زر لعرض مثال JSON
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'featured_howarat_json_help', array(
		'label'           => __( 'مثال JSON', 'nadiim' ),
		'description'     => nadiim_get_howarat_json_example(),
		'section'         => 'nadiim_featured_howarat',
		'type'            => 'hidden',
		'active_callback' => function() {
			return get_theme_mod( 'featured_howarat_source', 'latest_howarat' ) === 'manual';
		},
	) ) );

}
add_action( 'customize_register', 'nadiim_featured_howarat_customizer_register' );

/**
 * الحصول على JSON افتراضي للحوارات
 */
function nadiim_get_default_howarat_json() {
	$demo_file = get_template_directory() . '/demo/featured-howarat-demo.json';
	if ( file_exists( $demo_file ) ) {
		return file_get_contents( $demo_file );
	}
	return '[]';
}

/**
 * مثال JSON للمساعدة
 */
function nadiim_get_howarat_json_example() {
	return '<pre style="background:#f5f5f5;padding:10px;font-size:11px;direction:ltr;text-align:left;">[
  {
    "title": "حوار مع الكاتب فلان",
    "excerpt": "نقاش عميق حول الأدب والثقافة المعاصرة",
    "image": "https://example.com/image.jpg",
    "type": "audio",
    "cta_text": "استمع للحوار",
    "cta_link": "#",
    "date": "2024-01-15"
  }
]</pre>';
}

/**
 * CSS مخصص لقسم الحوارات المميزة
 */
function nadiim_featured_howarat_customizer_css() {
	if ( ! is_front_page() ) {
		return;
	}

	$overlay_opacity = get_theme_mod( 'featured_howarat_overlay_opacity', 0.30 );
	$overlay_color   = get_theme_mod( 'featured_howarat_overlay_color', '#000000' );
	$bg_enable       = get_theme_mod( 'featured_howarat_bg_enable', false );

	?>
	<style type="text/css" id="nadiim-featured-howarat-custom-css">
		:root {
			--featured-howarat-overlay-opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}

		<?php if ( ! get_theme_mod( 'featured_howarat_enable', true ) ) : ?>
		.featured-howarat-section {
			display: none !important;
		}
		<?php endif; ?>

		<?php if ( $bg_enable ) : ?>
		.featured-howarat-section .section-bg-overlay {
			background-color: <?php echo esc_attr( $overlay_color ); ?>;
			opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'nadiim_featured_howarat_customizer_css', 20 );
