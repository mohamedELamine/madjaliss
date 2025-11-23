<?php
/**
 * إعدادات Customizer لقسم الفعاليات
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات قسم الفعاليات في Customizer
 */
function nadiim_register_events_section_customizer( $wp_customize ) {

	// ========================================
	// Section: قسم الفعاليات
	// ========================================
	$wp_customize->add_section( 'nadiim_events_section', array(
		'title'    => __( 'قسم الفعاليات', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 65,
	) );

	// ========================================
	// Setting: تفعيل/إخفاء القسم
	// ========================================
	$wp_customize->add_setting( 'events_section_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_enable', array(
		'label'    => __( 'تفعيل قسم الفعاليات', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'type'     => 'checkbox',
		'priority' => 10,
	) );

	// ========================================
	// Setting: عنوان القسم
	// ========================================
	$wp_customize->add_setting( 'events_section_title', array(
		'default'           => __( 'الفعاليات القادمة', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'events_section_title', array(
		'label'    => __( 'عنوان القسم', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'type'     => 'text',
		'priority' => 20,
	) );

	// ========================================
	// Setting: وصف القسم
	// ========================================
	$wp_customize->add_setting( 'events_section_description', array(
		'default'           => __( 'انضم إلينا في فعالياتنا الثقافية والأدبية القادمة', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'events_section_description', array(
		'label'    => __( 'وصف القسم', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'type'     => 'textarea',
		'priority' => 30,
	) );

	// ========================================
	// Setting: عدد الفعاليات
	// ========================================
	$wp_customize->add_setting( 'events_section_count', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_count', array(
		'label'       => __( 'عدد الفعاليات المعروضة', 'nadiim' ),
		'description' => __( 'عدد الفعاليات التي سيتم عرضها في القسم', 'nadiim' ),
		'section'     => 'nadiim_events_section',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 6,
			'step' => 1,
		),
		'priority'    => 40,
	) );

	// ========================================
	// Setting: إظهار الفعاليات المنتهية
	// ========================================
	$wp_customize->add_setting( 'events_section_show_past', array(
		'default'           => false,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_show_past', array(
		'label'       => __( 'إظهار الفعاليات المنتهية', 'nadiim' ),
		'description' => __( 'إذا كانت غير مفعّلة، سيتم عرض الفعاليات القادمة فقط', 'nadiim' ),
		'section'     => 'nadiim_events_section',
		'type'        => 'checkbox',
		'priority'    => 50,
	) );

	// ========================================
	// Setting: إظهار العداد التنازلي
	// ========================================
	$wp_customize->add_setting( 'events_section_show_countdown', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_show_countdown', array(
		'label'       => __( 'إظهار العداد التنازلي', 'nadiim' ),
		'description' => __( 'عرض عداد تنازلي للفعاليات القادمة', 'nadiim' ),
		'section'     => 'nadiim_events_section',
		'type'        => 'checkbox',
		'priority'    => 60,
	) );

	// ========================================
	// Setting: إظهار زر "جميع الفعاليات"
	// ========================================
	$wp_customize->add_setting( 'events_section_show_more', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_show_more', array(
		'label'    => __( 'إظهار زر "جميع الفعاليات"', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'type'     => 'checkbox',
		'priority' => 70,
	) );

	// ========================================
	// Setting: نص زر "جميع الفعاليات"
	// ========================================
	$wp_customize->add_setting( 'events_section_more_text', array(
		'default'           => __( 'جميع الفعاليات', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'events_section_more_text', array(
		'label'           => __( 'نص زر "جميع الفعاليات"', 'nadiim' ),
		'section'         => 'nadiim_events_section',
		'type'            => 'text',
		'priority'        => 80,
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'events_section_show_more' )->value() === true;
		},
	) );

	// ========================================
	// Setting: لون خلفية القسم
	// ========================================
	$wp_customize->add_setting( 'events_section_bg_color', array(
		'default'           => '#F8F9F8',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'events_section_bg_color', array(
		'label'    => __( 'لون خلفية القسم', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'priority' => 90,
	) ) );

	// ========================================
	// Setting: لون العنوان
	// ========================================
	$wp_customize->add_setting( 'events_section_title_color', array(
		'default'           => '#1C2D27',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'events_section_title_color', array(
		'label'    => __( 'لون العنوان', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'priority' => 100,
	) ) );

	// ========================================
	// Setting: لون الوصف
	// ========================================
	$wp_customize->add_setting( 'events_section_description_color', array(
		'default'           => '#425F54',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'events_section_description_color', array(
		'label'    => __( 'لون الوصف', 'nadiim' ),
		'section'  => 'nadiim_events_section',
		'priority' => 110,
	) ) );

	// ========================================
	// Setting: صورة الخلفية
	// ========================================
	$wp_customize->add_setting( 'events_section_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'events_section_bg_image', array(
		'label'       => __( 'صورة الخلفية', 'nadiim' ),
		'description' => __( 'اختر صورة خلفية لقسم الفعاليات', 'nadiim' ),
		'section'     => 'nadiim_events_section',
		'priority'    => 120,
	) ) );

	// ========================================
	// Setting: موضع الخلفية
	// ========================================
	$wp_customize->add_setting( 'events_section_bg_position', array(
		'default'           => 'center',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_bg_position', array(
		'label'           => __( 'موضع الخلفية', 'nadiim' ),
		'section'         => 'nadiim_events_section',
		'type'            => 'select',
		'choices'         => array(
			'top'    => __( 'أعلى', 'nadiim' ),
			'center' => __( 'وسط', 'nadiim' ),
			'bottom' => __( 'أسفل', 'nadiim' ),
		),
		'priority'        => 130,
		'active_callback' => function() use ( $wp_customize ) {
			return ! empty( $wp_customize->get_setting( 'events_section_bg_image' )->value() );
		},
	) );

	// ========================================
	// Setting: شفافية الطبقة
	// ========================================
	$wp_customize->add_setting( 'events_section_overlay_opacity', array(
		'default'           => 0.5,
		'sanitize_callback' => 'nadiim_sanitize_float',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'events_section_overlay_opacity', array(
		'label'           => __( 'شفافية الطبقة الداكنة', 'nadiim' ),
		'description'     => __( 'من 0 (شفاف تماماً) إلى 1 (معتم تماماً)', 'nadiim' ),
		'section'         => 'nadiim_events_section',
		'type'            => 'number',
		'input_attrs'     => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.05,
		),
		'priority'        => 140,
		'active_callback' => function() use ( $wp_customize ) {
			return ! empty( $wp_customize->get_setting( 'events_section_bg_image' )->value() );
		},
	) );

	// ========================================
	// Setting: لون الطبقة
	// ========================================
	$wp_customize->add_setting( 'events_section_overlay_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'events_section_overlay_color', array(
		'label'           => __( 'لون الطبقة', 'nadiim' ),
		'section'         => 'nadiim_events_section',
		'priority'        => 150,
		'active_callback' => function() use ( $wp_customize ) {
			return ! empty( $wp_customize->get_setting( 'events_section_bg_image' )->value() );
		},
	) ) );
}
add_action( 'customize_register', 'nadiim_register_events_section_customizer' );
