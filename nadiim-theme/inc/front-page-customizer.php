<?php
/**
 * إعدادات Customizer للصفحة الرئيسية
 *
 * يحتوي على جميع إعدادات التحكم في أقسام الصفحة الرئيسية
 *
 * @package Nadiim
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات الصفحة الرئيسية في Customizer
 *
 * @param WP_Customize_Manager $wp_customize كائن Customizer
 */
function nadiim_front_page_customizer_register( $wp_customize ) {

	/* ================================================================
	   Panel: الصفحة الرئيسية
	   ================================================================ */

	$wp_customize->add_panel( 'nadiim_front_page_panel', array(
		'title'       => __( 'الصفحة الرئيسية', 'nadiim' ),
		'description' => __( 'تخصيص جميع أقسام الصفحة الرئيسية', 'nadiim' ),
		'priority'    => 20,
	) );

	/* ================================================================
	   Section 1: إعدادات عامة
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_general', array(
		'title'    => __( 'إعدادات عامة', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 10,
	) );

	// ترتيب الأقسام (سيتم التعامل معها لاحقاً - يمكن إضافة control مخصص)
	// TODO: إضافة sortable control لترتيب الأقسام

	/* ================================================================
	   Section 2: Newsletter (النشرة البريدية)
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_newsletter', array(
		'title'    => __( 'Newsletter - النشرة البريدية', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 80,
	) );

	// تفعيل
	$wp_customize->add_setting( 'home_newsletter_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_newsletter_enable', array(
		'label'    => __( 'تفعيل قسم النشرة البريدية', 'nadiim' ),
		'section'  => 'nadiim_front_page_newsletter',
		'type'     => 'checkbox',
	) );

	// العنوان
	$wp_customize->add_setting( 'home_newsletter_title', array(
		'default'           => 'اشترك في نشرتنا البريدية',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_newsletter_title', array(
		'label'    => __( 'العنوان', 'nadiim' ),
		'section'  => 'nadiim_front_page_newsletter',
		'type'     => 'text',
	) );

	// الوصف
	$wp_customize->add_setting( 'home_newsletter_desc', array(
		'default'           => 'تلقَّ آخر الأخبار والإصدارات والفعاليات مباشرة في بريدك',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_newsletter_desc', array(
		'label'    => __( 'الوصف', 'nadiim' ),
		'section'  => 'nadiim_front_page_newsletter',
		'type'     => 'textarea',
	) );

	// مزود الخدمة
	$wp_customize->add_setting( 'home_newsletter_provider', array(
		'default'           => 'mailchimp',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_newsletter_provider', array(
		'label'   => __( 'مزود الخدمة', 'nadiim' ),
		'section' => 'nadiim_front_page_newsletter',
		'type'    => 'select',
		'choices' => array(
			'mailchimp'      => __( 'Mailchimp', 'nadiim' ),
			'convertkit'     => __( 'ConvertKit', 'nadiim' ),
			'custom'         => __( 'مخصص', 'nadiim' ),
			'none'           => __( 'لا شيء (فقط العرض)', 'nadiim' ),
		),
	) );

}
add_action( 'customize_register', 'nadiim_front_page_customizer_register' );

/* ================================================================
   دوال المساعدة (Helper Functions)
   ================================================================ */

/**
 * التحقق من صحة checkbox
 */
function nadiim_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * التحقق من صحة select
 */
function nadiim_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * التحقق من صحة float
 */
if ( ! function_exists( 'nadiim_sanitize_float' ) ) {
	function nadiim_sanitize_float( $input ) {
		return floatval( $input );
	}
}

/**
 * الحصول على قائمة التصنيفات
 */
function nadiim_get_categories_choices() {
	$choices = array( '' => __( 'جميع التصنيفات', 'nadiim' ) );

	$categories = get_categories( array(
		'hide_empty' => true,
	) );

	foreach ( $categories as $category ) {
		$choices[ $category->term_id ] = $category->name;
	}

	return $choices;
}

/* ================================================================
   PostMessage Support للمعاينة المباشرة
   ================================================================ */

/**
 * تسجيل PostMessage support للإعدادات
 */
function nadiim_front_page_customize_preview_init() {
	wp_enqueue_script(
		'nadiim-front-page-customizer-preview',
		get_template_directory_uri() . '/assets/js/customizer-preview.js',
		array( 'jquery', 'customize-preview' ),
		'2.0.0',
		true
	);
}
add_action( 'customize_preview_init', 'nadiim_front_page_customize_preview_init' );
