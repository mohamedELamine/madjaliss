<?php
/**
 * Customizer لقسم النوادي (Clubs Section)
 *
 * @package Nadiim
 * @since 1.0.0
 */

// منع الوصول المباشر
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات Customizer لقسم النوادي
 */
function nadiim_clubs_customizer_register( $wp_customize ) {

	// ═══════════════════════════════════════════════════════════════
	// إضافة قسم النوادي في Customizer
	// ═══════════════════════════════════════════════════════════════

	$wp_customize->add_section( 'nadiim_clubs_section', array(
		'title'       => __( 'نوادي القراءة', 'nadiim' ),
		'description' => __( 'إعدادات قسم نوادي القراءة في الصفحة الرئيسية', 'nadiim' ),
		'priority'    => 45,
		'panel'       => 'nadiim_front_page_panel',
	) );

	// ═══════════════════════════════════════════════════════════════
	// الإعدادات العامة
	// ═══════════════════════════════════════════════════════════════

	// تفعيل/إلغاء تفعيل القسم
	$wp_customize->add_setting( 'clubs_section_enable', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_enable', array(
		'label'       => __( 'تفعيل قسم النوادي', 'nadiim' ),
		'description' => __( 'إظهار أو إخفاء قسم النوادي في الصفحة الرئيسية', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'checkbox',
	) );

	// عنوان القسم
	$wp_customize->add_setting( 'clubs_section_title', array(
		'default'           => __( 'نوادي القراءة', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_title', array(
		'label'       => __( 'عنوان القسم', 'nadiim' ),
		'description' => __( 'العنوان الرئيسي لقسم النوادي', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'text',
	) );

	// وصف القسم
	$wp_customize->add_setting( 'clubs_section_subtitle', array(
		'default'           => __( 'مجتمعاتٌ هادئة للقراءة والنقاش، نجتمع فيها على حب الكتب وتبادل الأفكار', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_subtitle', array(
		'label'       => __( 'وصف القسم', 'nadiim' ),
		'description' => __( 'نص توضيحي قصير أسفل العنوان', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'textarea',
	) );

	// ─────────────────────────────────────
	// إعدادات المحتوى
	// ─────────────────────────────────────

	// عدد النوادي
	$wp_customize->add_setting( 'clubs_section_count', array(
		'default'           => 6,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'clubs_section_count', array(
		'label'       => __( 'عدد النوادي', 'nadiim' ),
		'description' => __( 'عدد النوادي التي سيتم عرضها', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 12,
			'step' => 1,
		),
	) );

	// ─────────────────────────────────────
	// إعدادات التخطيط
	// ─────────────────────────────────────

	// نوع التخطيط
	$wp_customize->add_setting( 'clubs_section_layout', array(
		'default'           => 'grid',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_layout', array(
		'label'       => __( 'نوع التخطيط', 'nadiim' ),
		'description' => __( 'اختر طريقة عرض النوادي', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'select',
		'choices'     => array(
			'grid'     => __( 'شبكة (Grid)', 'nadiim' ),
			'carousel' => __( 'شريط متحرك (Carousel)', 'nadiim' ),
		),
	) );

	// ─────────────────────────────────────
	// إعدادات الخلفية
	// ─────────────────────────────────────

	// تفعيل خلفية القسم
	$wp_customize->add_setting( 'clubs_section_bg_enable', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_bg_enable', array(
		'label'       => __( 'تفعيل خلفية القسم', 'nadiim' ),
		'description' => __( 'إضافة صورة أو فيديو خلفية للقسم', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'checkbox',
	) );

	// صورة الخلفية
	$wp_customize->add_setting( 'clubs_section_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'clubs_section_bg_image', array(
		'label'           => __( 'صورة الخلفية', 'nadiim' ),
		'description'     => __( 'اختر صورة خلفية للقسم', 'nadiim' ),
		'section'         => 'nadiim_clubs_section',
		'active_callback' => function() {
			return get_theme_mod( 'clubs_section_bg_enable', false );
		},
	) ) );

	// Embed الخلفية (iframe fallback)
	$wp_customize->add_setting( 'clubs_section_bg_embed', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'clubs_section_bg_embed', array(
		'label'           => __( 'كود Embed للخلفية', 'nadiim' ),
		'description'     => __( 'أدخل كود iframe لفيديو خلفية (YouTube, Vimeo, etc.)', 'nadiim' ),
		'section'         => 'nadiim_clubs_section',
		'type'            => 'textarea',
		'input_attrs'     => array(
			'placeholder' => '<iframe src="..."></iframe>',
			'rows'        => 4,
		),
		'active_callback' => function() {
			return get_theme_mod( 'clubs_section_bg_enable', false );
		},
	) );

	// شفافية طبقة التعتيم
	$wp_customize->add_setting( 'clubs_section_overlay_opacity', array(
		'default'           => 0.30,
		'sanitize_callback' => 'nadiim_sanitize_float',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_overlay_opacity', array(
		'label'           => __( 'شفافية طبقة التعتيم', 'nadiim' ),
		'description'     => __( 'قيمة من 0.0 (شفاف) إلى 1.0 (معتم)', 'nadiim' ),
		'section'         => 'nadiim_clubs_section',
		'type'            => 'number',
		'input_attrs'     => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.05,
		),
		'active_callback' => function() {
			return get_theme_mod( 'clubs_section_bg_enable', false );
		},
	) );

	// لون طبقة التعتيم
	$wp_customize->add_setting( 'clubs_section_overlay_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'clubs_section_overlay_color', array(
		'label'           => __( 'لون طبقة التعتيم', 'nadiim' ),
		'description'     => __( 'لون الطبقة الشفافة فوق الخلفية', 'nadiim' ),
		'section'         => 'nadiim_clubs_section',
		'active_callback' => function() {
			return get_theme_mod( 'clubs_section_bg_enable', false );
		},
	) ) );

	// ─────────────────────────────────────
	// إعدادات زر "جميع النوادي"
	// ─────────────────────────────────────

	// إظهار زر المزيد
	$wp_customize->add_setting( 'clubs_section_show_more_button', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_show_more_button', array(
		'label'       => __( 'إظهار زر "جميع النوادي"', 'nadiim' ),
		'description' => __( 'عرض زر في أسفل القسم للانتقال إلى صفحة جميع النوادي', 'nadiim' ),
		'section'     => 'nadiim_clubs_section',
		'type'        => 'checkbox',
	) );

	// نص زر المزيد
	$wp_customize->add_setting( 'clubs_section_more_button_text', array(
		'default'           => __( 'جميع النوادي', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'clubs_section_more_button_text', array(
		'label'           => __( 'نص الزر', 'nadiim' ),
		'section'         => 'nadiim_clubs_section',
		'type'            => 'text',
		'active_callback' => function() {
			return get_theme_mod( 'clubs_section_show_more_button', true );
		},
	) );
}
add_action( 'customize_register', 'nadiim_clubs_customizer_register' );

/**
 * دالة sanitize للأرقام العشرية
 */
if ( ! function_exists( 'nadiim_sanitize_float' ) ) {
	function nadiim_sanitize_float( $value ) {
		return floatval( $value );
	}
}

/**
 * CSS مخصص لقسم النوادي
 */
function nadiim_clubs_customizer_css() {
	if ( ! is_front_page() ) {
		return;
	}

	$overlay_opacity = get_theme_mod( 'clubs_section_overlay_opacity', 0.30 );
	$overlay_color   = get_theme_mod( 'clubs_section_overlay_color', '#000000' );
	$bg_enable       = get_theme_mod( 'clubs_section_bg_enable', false );

	?>
	<style type="text/css" id="nadiim-clubs-custom-css">
		:root {
			--clubs-overlay-opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}

		<?php if ( ! get_theme_mod( 'clubs_section_enable', true ) ) : ?>
		.clubs-section { display: none; }
		<?php endif; ?>

		<?php if ( $bg_enable ) : ?>
		.clubs-section .section-bg-overlay {
			background-color: <?php echo esc_attr( $overlay_color ); ?>;
			opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'nadiim_clubs_customizer_css', 20 );
