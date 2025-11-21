<?php
/**
 * إعدادات Customizer لقسم About Mini
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات قسم About Mini في Customizer
 */
function nadiim_register_about_mini_customizer( $wp_customize ) {

	// ========================================
	// Section: قسم من نحن المصغر
	// ========================================
	$wp_customize->add_section( 'nadiim_about_mini_section', array(
		'title'    => __( 'قسم من نحن المصغر', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 35,
	) );

	// ========================================
	// Setting: تفعيل/إخفاء القسم
	// ========================================
	$wp_customize->add_setting( 'about_mini_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'about_mini_enable', array(
		'label'    => __( 'تفعيل قسم من نحن المصغر', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'checkbox',
		'priority' => 10,
	) );

	// ========================================
	// Setting: عنوان القسم
	// ========================================
	$wp_customize->add_setting( 'about_mini_title', array(
		'default'           => __( 'من نحن؟', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_title', array(
		'label'    => __( 'عنوان القسم', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 20,
	) );

	// ========================================
	// Setting: الوصف
	// ========================================
	$wp_customize->add_setting( 'about_mini_lead', array(
		'default'           => __( 'نديم مبادرة ثقافية تهدف إلى نشر الوعي وتعزيز القراءة والحوارات الهادفة. نؤمن بقوة الكلمة في بناء المجتمعات.', 'nadiim' ),
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_lead', array(
		'label'    => __( 'الوصف المختصر', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'textarea',
		'priority' => 30,
	) );

	// ========================================
	// Setting: نص زر CTA
	// ========================================
	$wp_customize->add_setting( 'about_mini_cta_text', array(
		'default'           => __( 'اقرأ قصتنا', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_cta_text', array(
		'label'    => __( 'نص زر CTA', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 40,
	) );

	// ========================================
	// Setting: رابط زر CTA
	// ========================================
	$wp_customize->add_setting( 'about_mini_cta_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_cta_link', array(
		'label'    => __( 'رابط زر CTA', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'url',
		'priority' => 50,
	) );

	// ========================================
	// Setting: تفعيل الخلفية
	// ========================================
	$wp_customize->add_setting( 'about_mini_bg_enable', array(
		'default'           => false,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'about_mini_bg_enable', array(
		'label'       => __( 'تفعيل صورة الخلفية', 'nadiim' ),
		'description' => __( 'إضافة صورة خلفية للقسم', 'nadiim' ),
		'section'     => 'nadiim_about_mini_section',
		'type'        => 'checkbox',
		'priority'    => 60,
	) );

	// ========================================
	// Setting: صورة الخلفية
	// ========================================
	$wp_customize->add_setting( 'about_mini_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'about_mini_bg_image', array(
		'label'           => __( 'صورة الخلفية', 'nadiim' ),
		'section'         => 'nadiim_about_mini_section',
		'priority'        => 70,
		'active_callback' => function() {
			return get_theme_mod( 'about_mini_bg_enable', false );
		},
	) ) );

	// ========================================
	// Setting: موضع الخلفية
	// ========================================
	$wp_customize->add_setting( 'about_mini_bg_position', array(
		'default'           => 'center',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_bg_position', array(
		'label'           => __( 'موضع الخلفية', 'nadiim' ),
		'section'         => 'nadiim_about_mini_section',
		'type'            => 'select',
		'choices'         => array(
			'center' => __( 'مركز', 'nadiim' ),
			'top'    => __( 'أعلى', 'nadiim' ),
			'bottom' => __( 'أسفل', 'nadiim' ),
			'left'   => __( 'يسار', 'nadiim' ),
			'right'  => __( 'يمين', 'nadiim' ),
		),
		'priority'        => 80,
		'active_callback' => function() {
			return get_theme_mod( 'about_mini_bg_enable', false );
		},
	) );

	// ========================================
	// Setting: لون طبقة التعتيم
	// ========================================
	$wp_customize->add_setting( 'about_mini_overlay_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_mini_overlay_color', array(
		'label'           => __( 'لون طبقة التعتيم', 'nadiim' ),
		'section'         => 'nadiim_about_mini_section',
		'priority'        => 90,
		'active_callback' => function() {
			return get_theme_mod( 'about_mini_bg_enable', false );
		},
	) ) );

	// ========================================
	// Setting: شفافية طبقة التعتيم
	// ========================================
	$wp_customize->add_setting( 'about_mini_overlay_opacity', array(
		'default'           => 0.35,
		'sanitize_callback' => 'nadiim_sanitize_float',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_overlay_opacity', array(
		'label'           => __( 'شفافية طبقة التعتيم', 'nadiim' ),
		'description'     => __( '0 = شفاف تماماً، 1 = معتم تماماً', 'nadiim' ),
		'section'         => 'nadiim_about_mini_section',
		'type'            => 'number',
		'input_attrs'     => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.05,
		),
		'priority'        => 100,
		'active_callback' => function() {
			return get_theme_mod( 'about_mini_bg_enable', false );
		},
	) );

	// ========================================
	// البطاقات الثلاث (المبادئ)
	// ========================================

	// البطاقة 1
	$wp_customize->add_setting( 'about_mini_value_1_icon', array(
		'default'           => '🎯',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_1_icon', array(
		'label'    => __( 'أيقونة المبدأ 1', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 110,
	) );

	$wp_customize->add_setting( 'about_mini_value_1_title', array(
		'default'           => __( 'رؤيتنا', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_1_title', array(
		'label'    => __( 'عنوان المبدأ 1', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 120,
	) );

	$wp_customize->add_setting( 'about_mini_value_1_text', array(
		'default'           => __( 'بناء مجتمع قارئ ومفكّر', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_1_text', array(
		'label'    => __( 'نص المبدأ 1', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 130,
	) );

	// البطاقة 2
	$wp_customize->add_setting( 'about_mini_value_2_icon', array(
		'default'           => '💡',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_2_icon', array(
		'label'    => __( 'أيقونة المبدأ 2', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 140,
	) );

	$wp_customize->add_setting( 'about_mini_value_2_title', array(
		'default'           => __( 'مهمتنا', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_2_title', array(
		'label'    => __( 'عنوان المبدأ 2', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 150,
	) );

	$wp_customize->add_setting( 'about_mini_value_2_text', array(
		'default'           => __( 'نشر المعرفة عبر الحوار الهادف', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_2_text', array(
		'label'    => __( 'نص المبدأ 2', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 160,
	) );

	// البطاقة 3
	$wp_customize->add_setting( 'about_mini_value_3_icon', array(
		'default'           => '🌟',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_3_icon', array(
		'label'    => __( 'أيقونة المبدأ 3', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 170,
	) );

	$wp_customize->add_setting( 'about_mini_value_3_title', array(
		'default'           => __( 'قيمنا', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_3_title', array(
		'label'    => __( 'عنوان المبدأ 3', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 180,
	) );

	$wp_customize->add_setting( 'about_mini_value_3_text', array(
		'default'           => __( 'الجودة والأصالة والاحترام', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'about_mini_value_3_text', array(
		'label'    => __( 'نص المبدأ 3', 'nadiim' ),
		'section'  => 'nadiim_about_mini_section',
		'type'     => 'text',
		'priority' => 190,
	) );
}
add_action( 'customize_register', 'nadiim_register_about_mini_customizer' );

/**
 * CSS مخصص لقسم About Mini
 */
function nadiim_about_mini_customizer_css() {
	if ( ! is_front_page() ) {
		return;
	}

	$overlay_opacity = get_theme_mod( 'about_mini_overlay_opacity', 0.35 );
	$overlay_color   = get_theme_mod( 'about_mini_overlay_color', '#000000' );
	$bg_enable       = get_theme_mod( 'about_mini_bg_enable', false );
	$bg_position     = get_theme_mod( 'about_mini_bg_position', 'center' );

	?>
	<style type="text/css" id="nadiim-about-mini-custom-css">
		:root {
			--about-mini-overlay-opacity: <?php echo floatval( $overlay_opacity ); ?>;
			--about-mini-bg-position: <?php echo esc_attr( $bg_position ); ?>;
		}

		<?php if ( ! get_theme_mod( 'about_mini_enable', true ) ) : ?>
		.about-mini-section {
			display: none;
		}
		<?php endif; ?>

		<?php if ( $bg_enable ) : ?>
		.about-mini-section .section-overlay {
			background-color: <?php echo esc_attr( $overlay_color ); ?>;
			opacity: <?php echo floatval( $overlay_opacity ); ?>;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'nadiim_about_mini_customizer_css', 20 );
