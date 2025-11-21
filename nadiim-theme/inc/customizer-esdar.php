<?php
/**
 * Customizer للإصدارات (Esdar Section)
 *
 * @package Nadiim
 * @since 1.0.0
 */

// منع الوصول المباشر
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات Customizer لقسم الإصدارات
 */
function nadiim_esdar_customizer_register( $wp_customize ) {

	// ═══════════════════════════════════════════════════════════════
	// إضافة قسم الإصدارات في Customizer
	// ═══════════════════════════════════════════════════════════════

	$wp_customize->add_section( 'nadiim_esdar_section', array(
		'title'       => __( 'الإصدارات', 'nadiim' ),
		'description' => __( 'إعدادات قسم الإصدارات في الصفحة الرئيسية', 'nadiim' ),
		'priority'    => 42,
		'panel'       => 'nadiim_home_panel',
	) );

	// ═══════════════════════════════════════════════════════════════
	// الإعدادات العامة
	// ═══════════════════════════════════════════════════════════════

	// تفعيل/إلغاء تفعيل القسم
	$wp_customize->add_setting( 'esdar_section_enable', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_enable', array(
		'label'       => __( 'تفعيل قسم الإصدارات', 'nadiim' ),
		'description' => __( 'إظهار أو إخفاء قسم الإصدارات في الصفحة الرئيسية', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'checkbox',
	) );

	// عنوان القسم
	$wp_customize->add_setting( 'esdar_section_title', array(
		'default'           => __( 'إصدارات نديم', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_title', array(
		'label'       => __( 'عنوان القسم', 'nadiim' ),
		'description' => __( 'العنوان الرئيسي لقسم الإصدارات', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'text',
	) );

	// وصف القسم
	$wp_customize->add_setting( 'esdar_section_subtitle', array(
		'default'           => __( 'اكتشف أحدث إصداراتنا من الكتب والتقارير والمجلات', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_subtitle', array(
		'label'       => __( 'وصف القسم', 'nadiim' ),
		'description' => __( 'نص توضيحي قصير أسفل العنوان', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'textarea',
	) );

	// ─────────────────────────────────────
	// إعدادات المحتوى
	// ─────────────────────────────────────

	// مصدر المحتوى
	$wp_customize->add_setting( 'esdar_section_source', array(
		'default'           => 'latest',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'esdar_section_source', array(
		'label'       => __( 'مصدر المحتوى', 'nadiim' ),
		'description' => __( 'اختر كيفية جلب الإصدارات', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'select',
		'choices'     => array(
			'latest' => __( 'أحدث الإصدارات', 'nadiim' ),
			'tag'    => __( 'حسب وسم معين', 'nadiim' ),
			'manual' => __( 'يدوي (JSON)', 'nadiim' ),
		),
	) );

	// عدد الإصدارات
	$wp_customize->add_setting( 'esdar_section_count', array(
		'default'           => 6,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'esdar_section_count', array(
		'label'           => __( 'عدد الإصدارات', 'nadiim' ),
		'description'     => __( 'عدد الإصدارات التي سيتم عرضها', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'number',
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 12,
			'step' => 1,
		),
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_source', 'latest' ) !== 'manual';
		},
	) );

	// الوسم للفلترة
	$wp_customize->add_setting( 'esdar_section_tag', array(
		'default'           => 'featured',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'esdar_section_tag', array(
		'label'           => __( 'اسم الوسم', 'nadiim' ),
		'description'     => __( 'slug الوسم (مثال: featured)', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'text',
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_source', 'latest' ) === 'tag';
		},
	) );

	// JSON البيانات اليدوية
	$wp_customize->add_setting( 'esdar_section_manual_json', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'esdar_section_manual_json', array(
		'label'           => __( 'بيانات JSON', 'nadiim' ),
		'description'     => __( 'أدخل مصفوفة JSON للإصدارات اليدوية', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'textarea',
		'input_attrs'     => array(
			'placeholder' => '[{"title":"...","image":"..."}]',
			'rows'        => 8,
		),
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_source', 'latest' ) === 'manual';
		},
	) );

	// ─────────────────────────────────────
	// إعدادات التخطيط
	// ─────────────────────────────────────

	// نوع التخطيط
	$wp_customize->add_setting( 'esdar_section_layout', array(
		'default'           => 'grid',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_layout', array(
		'label'       => __( 'نوع التخطيط', 'nadiim' ),
		'description' => __( 'اختر طريقة عرض الإصدارات', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'select',
		'choices'     => array(
			'grid'     => __( 'شبكة (Grid)', 'nadiim' ),
			'carousel' => __( 'شريط متحرك (Carousel)', 'nadiim' ),
		),
	) );

	// ─────────────────────────────────────
	// إعدادات الألوان
	// ─────────────────────────────────────

	// لون عنوان القسم
	$wp_customize->add_setting( 'esdar_section_title_color', array(
		'default'           => '#1c2d27',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'esdar_section_title_color', array(
		'label'       => __( 'لون عنوان القسم', 'nadiim' ),
		'description' => __( 'لون نص عنوان القسم', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
	) ) );

	// لون وصف القسم
	$wp_customize->add_setting( 'esdar_section_subtitle_color', array(
		'default'           => '#5a6c64',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'esdar_section_subtitle_color', array(
		'label'       => __( 'لون وصف القسم', 'nadiim' ),
		'description' => __( 'لون نص الوصف', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
	) ) );

	// ─────────────────────────────────────
	// إعدادات البطاقة
	// ─────────────────────────────────────

	// إظهار زر التحميل
	$wp_customize->add_setting( 'esdar_card_show_download', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_card_show_download', array(
		'label'       => __( 'إظهار زر التحميل', 'nadiim' ),
		'description' => __( 'عرض زر التحميل في البطاقة', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'checkbox',
	) );

	// إظهار Badge النوع
	$wp_customize->add_setting( 'esdar_card_show_badge', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_card_show_badge', array(
		'label'       => __( 'إظهار شارة النوع', 'nadiim' ),
		'description' => __( 'عرض شارة نوع الإصدار (رواية، تقرير، إلخ)', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'checkbox',
	) );

	// ─────────────────────────────────────
	// إعدادات الخلفية
	// ─────────────────────────────────────

	// تفعيل خلفية القسم
	$wp_customize->add_setting( 'esdar_section_bg_enable', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_bg_enable', array(
		'label'       => __( 'تفعيل خلفية القسم', 'nadiim' ),
		'description' => __( 'إضافة صورة خلفية للقسم', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'checkbox',
	) );

	// صورة الخلفية
	$wp_customize->add_setting( 'esdar_section_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'esdar_section_bg_image', array(
		'label'           => __( 'صورة الخلفية', 'nadiim' ),
		'description'     => __( 'اختر صورة خلفية للقسم', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_bg_enable', false );
		},
	) ) );

	// شفافية طبقة التعتيم
	$wp_customize->add_setting( 'esdar_section_overlay_opacity', array(
		'default'           => 0.30,
		'sanitize_callback' => 'nadiim_sanitize_float',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_overlay_opacity', array(
		'label'           => __( 'شفافية طبقة التعتيم', 'nadiim' ),
		'description'     => __( 'قيمة من 0.0 (شفاف) إلى 1.0 (معتم)', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'number',
		'input_attrs'     => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.05,
		),
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_bg_enable', false );
		},
	) );

	// لون طبقة التعتيم
	$wp_customize->add_setting( 'esdar_section_overlay_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'esdar_section_overlay_color', array(
		'label'           => __( 'لون طبقة التعتيم', 'nadiim' ),
		'description'     => __( 'لون الطبقة الشفافة فوق الخلفية', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_bg_enable', false );
		},
	) ) );

	// نظام الألوان للنص
	$wp_customize->add_setting( 'esdar_section_text_color_scheme', array(
		'default'           => 'auto',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_text_color_scheme', array(
		'label'           => __( 'نظام ألوان النص', 'nadiim' ),
		'description'     => __( 'اختر نظام الألوان المناسب للخلفية', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'select',
		'choices'         => array(
			'auto'  => __( 'تلقائي', 'nadiim' ),
			'light' => __( 'فاتح (للخلفيات الداكنة)', 'nadiim' ),
			'dark'  => __( 'داكن (للخلفيات الفاتحة)', 'nadiim' ),
		),
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_bg_enable', false );
		},
	) );

	// ─────────────────────────────────────
	// إعدادات زر "اطلع على المزيد"
	// ─────────────────────────────────────

	// إظهار زر المزيد
	$wp_customize->add_setting( 'esdar_section_show_more_button', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_show_more_button', array(
		'label'       => __( 'إظهار زر "اطلع على المزيد"', 'nadiim' ),
		'description' => __( 'عرض زر في أسفل القسم للانتقال إلى صفحة جميع الإصدارات', 'nadiim' ),
		'section'     => 'nadiim_esdar_section',
		'type'        => 'checkbox',
	) );

	// نص زر المزيد
	$wp_customize->add_setting( 'esdar_section_more_button_text', array(
		'default'           => __( 'استكشف جميع الإصدارات', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_more_button_text', array(
		'label'           => __( 'نص الزر', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'text',
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_show_more_button', true );
		},
	) );

	// رابط زر المزيد
	$wp_customize->add_setting( 'esdar_section_more_button_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'esdar_section_more_button_link', array(
		'label'           => __( 'رابط الزر', 'nadiim' ),
		'description'     => __( 'الرابط الذي سينتقل إليه المستخدم عند النقر', 'nadiim' ),
		'section'         => 'nadiim_esdar_section',
		'type'            => 'url',
		'active_callback' => function() {
			return get_theme_mod( 'esdar_section_show_more_button', true );
		},
	) );
}
add_action( 'customize_register', 'nadiim_esdar_customizer_register' );

/**
 * دالة sanitize للأرقام العشرية
 */
function nadiim_sanitize_float( $value ) {
	return floatval( $value );
}

/**
 * CSS مخصص لقسم الإصدارات
 */
function nadiim_esdar_customizer_css() {
	if ( ! is_front_page() ) {
		return;
	}

	$overlay_opacity     = get_theme_mod( 'esdar_section_overlay_opacity', 0.30 );
	$overlay_color       = get_theme_mod( 'esdar_section_overlay_color', '#000000' );
	$bg_enable           = get_theme_mod( 'esdar_section_bg_enable', false );
	$title_color         = get_theme_mod( 'esdar_section_title_color', '#1c2d27' );
	$subtitle_color      = get_theme_mod( 'esdar_section_subtitle_color', '#5a6c64' );
	$text_color_scheme   = get_theme_mod( 'esdar_section_text_color_scheme', 'auto' );

	?>
	<style type="text/css" id="nadiim-esdar-custom-css">
		:root {
			--esdar-overlay-opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}

		<?php if ( ! get_theme_mod( 'esdar_section_enable', true ) ) : ?>
		.esdar-section { display: none; }
		<?php endif; ?>

		<?php if ( $bg_enable ) : ?>
		.esdar-section .section-bg-overlay {
			background-color: <?php echo esc_attr( $overlay_color ); ?>;
			opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}
		<?php endif; ?>

		/* ألوان النص */
		.esdar-section .section-title {
			color: <?php echo esc_attr( $title_color ); ?>;
		}

		.esdar-section .section-subtitle {
			color: <?php echo esc_attr( $subtitle_color ); ?>;
		}

		<?php if ( $text_color_scheme === 'light' ) : ?>
		.esdar-section.section-with-bg .section-title,
		.esdar-section.section-with-bg .esdar-card-title,
		.esdar-section.section-with-bg .esdar-card-excerpt,
		.esdar-section.section-with-bg .esdar-card-meta {
			color: rgba(255, 255, 255, 0.95);
		}

		.esdar-section.section-with-bg .section-subtitle {
			color: rgba(255, 255, 255, 0.80);
		}
		<?php elseif ( $text_color_scheme === 'dark' ) : ?>
		.esdar-section.section-with-bg .section-title {
			color: #1c2d27;
		}

		.esdar-section.section-with-bg .section-subtitle {
			color: #5a6c64;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'nadiim_esdar_customizer_css', 20 );
