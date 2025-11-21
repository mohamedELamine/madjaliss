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
	   Section 2: Topbar (شريط الأحداث)
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_topbar', array(
		'title'    => __( 'Topbar - شريط الأحداث', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 20,
	) );

	// تفعيل/تعطيل Topbar
	$wp_customize->add_setting( 'home_topbar_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_topbar_enable', array(
		'label'    => __( 'تفعيل شريط الأحداث', 'nadiim' ),
		'section'  => 'nadiim_front_page_topbar',
		'type'     => 'checkbox',
	) );

	// عدد الأحداث
	$wp_customize->add_setting( 'home_topbar_count', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_topbar_count', array(
		'label'       => __( 'عدد الأحداث', 'nadiim' ),
		'section'     => 'nadiim_front_page_topbar',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 10,
			'step' => 1,
		),
	) );

	// لون الخلفية
	$wp_customize->add_setting( 'home_topbar_bg', array(
		'default'           => '#26704A',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'home_topbar_bg', array(
		'label'    => __( 'لون الخلفية', 'nadiim' ),
		'section'  => 'nadiim_front_page_topbar',
	) ) );

	// لون النص
	$wp_customize->add_setting( 'home_topbar_text', array(
		'default'           => '#FFFFFF',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'home_topbar_text', array(
		'label'    => __( 'لون النص', 'nadiim' ),
		'section'  => 'nadiim_front_page_topbar',
	) ) );

	// مصدر الأحداث
	$wp_customize->add_setting( 'home_topbar_source', array(
		'default'           => 'howarat',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_topbar_source', array(
		'label'   => __( 'مصدر الأحداث', 'nadiim' ),
		'section' => 'nadiim_front_page_topbar',
		'type'    => 'select',
		'choices' => array(
			'events'  => __( 'الأحداث (events)', 'nadiim' ),
			'howarat' => __( 'الحوارات (howarat)', 'nadiim' ),
			'custom'  => __( 'مخصص', 'nadiim' ),
		),
	) );

	/* ================================================================
	   Section 3: Hero
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_hero', array(
		'title'    => __( 'Hero - المنطقة البصرية', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 30,
	) );

	// تفعيل Hero
	$wp_customize->add_setting( 'home_hero_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_hero_enable', array(
		'label'    => __( 'تفعيل قسم Hero', 'nadiim' ),
		'section'  => 'nadiim_front_page_hero',
		'type'     => 'checkbox',
	) );

	// عنوان Hero
	$wp_customize->add_setting( 'home_hero_title', array(
		'default'           => 'مرحباً بكم في نديم',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_hero_title', array(
		'label'    => __( 'العنوان الرئيسي', 'nadiim' ),
		'section'  => 'nadiim_front_page_hero',
		'type'     => 'text',
	) );

	// العنوان الفرعي
	$wp_customize->add_setting( 'home_hero_subtitle', array(
		'default'           => 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_hero_subtitle', array(
		'label'    => __( 'العنوان الفرعي', 'nadiim' ),
		'section'  => 'nadiim_front_page_hero',
		'type'     => 'textarea',
	) );

	// نص زر CTA
	$wp_customize->add_setting( 'home_hero_cta_text', array(
		'default'           => 'استكشف المحتوى',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_hero_cta_text', array(
		'label'    => __( 'نص زر الدعوة لإجراء', 'nadiim' ),
		'section'  => 'nadiim_front_page_hero',
		'type'     => 'text',
	) );

	// رابط زر CTA
	$wp_customize->add_setting( 'home_hero_cta_link', array(
		'default'           => '#dialogues',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_hero_cta_link', array(
		'label'    => __( 'رابط زر الدعوة لإجراء', 'nadiim' ),
		'section'  => 'nadiim_front_page_hero',
		'type'     => 'url',
	) );

	// نوع الخلفية
	$wp_customize->add_setting( 'home_hero_bg_type', array(
		'default'           => 'gradient',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_hero_bg_type', array(
		'label'   => __( 'نوع الخلفية', 'nadiim' ),
		'section' => 'nadiim_front_page_hero',
		'type'    => 'select',
		'choices' => array(
			'image'    => __( 'صورة', 'nadiim' ),
			'color'    => __( 'لون', 'nadiim' ),
			'gradient' => __( 'تدرج لوني', 'nadiim' ),
		),
	) );

	// صورة الخلفية
	$wp_customize->add_setting( 'home_hero_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'home_hero_bg_image', array(
		'label'       => __( 'صورة الخلفية', 'nadiim' ),
		'section'     => 'nadiim_front_page_hero',
		'mime_type'   => 'image',
		'description' => __( 'يظهر فقط عند اختيار "صورة" كنوع خلفية', 'nadiim' ),
	) ) );

	// لون الخلفية
	$wp_customize->add_setting( 'home_hero_bg_color', array(
		'default'           => '#F6FFF9',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'home_hero_bg_color', array(
		'label'       => __( 'لون الخلفية', 'nadiim' ),
		'section'     => 'nadiim_front_page_hero',
		'description' => __( 'يظهر فقط عند اختيار "لون" كنوع خلفية', 'nadiim' ),
	) ) );

	// شفافية التراكب
	$wp_customize->add_setting( 'home_hero_overlay_opacity', array(
		'default'           => 0.3,
		'sanitize_callback' => 'nadiim_sanitize_float',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'home_hero_overlay_opacity', array(
		'label'       => __( 'شفافية التراكب', 'nadiim' ),
		'section'     => 'nadiim_front_page_hero',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 0.6,
			'step' => 0.1,
		),
	) );

	/* ================================================================
	   Section 4: Dialogues (الحوارات)
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_dialogues', array(
		'title'    => __( 'Dialogues - الحوارات', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 40,
	) );

	// تفعيل
	$wp_customize->add_setting( 'home_dialogues_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_dialogues_enable', array(
		'label'    => __( 'تفعيل قسم الحوارات', 'nadiim' ),
		'section'  => 'nadiim_front_page_dialogues',
		'type'     => 'checkbox',
	) );

	// عدد الحوارات
	$wp_customize->add_setting( 'home_dialogues_count', array(
		'default'           => 6,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_dialogues_count', array(
		'label'       => __( 'عدد الحوارات', 'nadiim' ),
		'section'     => 'nadiim_front_page_dialogues',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 3,
			'max'  => 12,
			'step' => 3,
		),
	) );

	// تخطيط العرض
	$wp_customize->add_setting( 'home_dialogues_layout', array(
		'default'           => 'grid',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_dialogues_layout', array(
		'label'   => __( 'تخطيط العرض', 'nadiim' ),
		'section' => 'nadiim_front_page_dialogues',
		'type'    => 'select',
		'choices' => array(
			'grid' => __( 'شبكة (Grid)', 'nadiim' ),
			'list' => __( 'قائمة (List)', 'nadiim' ),
		),
	) );

	// مصدر الحوارات
	$wp_customize->add_setting( 'home_dialogues_source', array(
		'default'           => 'recent',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_dialogues_source', array(
		'label'   => __( 'مصدر الحوارات', 'nadiim' ),
		'section' => 'nadiim_front_page_dialogues',
		'type'    => 'select',
		'choices' => array(
			'recent'   => __( 'الأحدث', 'nadiim' ),
			'featured' => __( 'المميزة', 'nadiim' ),
			'tag'      => __( 'وسم معين', 'nadiim' ),
		),
	) );

	/* ================================================================
	   Section 5: Releases (الإصدارات)
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_releases', array(
		'title'    => __( 'Releases - الإصدارات', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 50,
	) );

	// تفعيل
	$wp_customize->add_setting( 'home_releases_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_releases_enable', array(
		'label'    => __( 'تفعيل قسم الإصدارات', 'nadiim' ),
		'section'  => 'nadiim_front_page_releases',
		'type'     => 'checkbox',
	) );

	// عدد الإصدارات
	$wp_customize->add_setting( 'home_releases_count', array(
		'default'           => 8,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_releases_count', array(
		'label'       => __( 'عدد الإصدارات', 'nadiim' ),
		'section'     => 'nadiim_front_page_releases',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 4,
			'max'  => 16,
			'step' => 2,
		),
	) );

	// تخطيط العرض
	$wp_customize->add_setting( 'home_releases_layout', array(
		'default'           => 'carousel',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_releases_layout', array(
		'label'   => __( 'تخطيط العرض', 'nadiim' ),
		'section' => 'nadiim_front_page_releases',
		'type'    => 'select',
		'choices' => array(
			'carousel' => __( 'Carousel - سلايدر', 'nadiim' ),
			'grid'     => __( 'Grid - شبكة', 'nadiim' ),
		),
	) );

	/* ================================================================
	   Section 6: Posts (المقالات)
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_posts', array(
		'title'    => __( 'Posts - المقالات', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 60,
	) );

	// تفعيل
	$wp_customize->add_setting( 'home_posts_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_posts_enable', array(
		'label'    => __( 'تفعيل قسم المقالات', 'nadiim' ),
		'section'  => 'nadiim_front_page_posts',
		'type'     => 'checkbox',
	) );

	// عدد المقالات
	$wp_customize->add_setting( 'home_posts_count', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_posts_count', array(
		'label'       => __( 'عدد المقالات', 'nadiim' ),
		'section'     => 'nadiim_front_page_posts',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 2,
			'max'  => 6,
			'step' => 1,
		),
	) );

	// التصنيف
	$wp_customize->add_setting( 'home_posts_category', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_posts_category', array(
		'label'   => __( 'التصنيف', 'nadiim' ),
		'section' => 'nadiim_front_page_posts',
		'type'    => 'select',
		'choices' => nadiim_get_categories_choices(),
	) );

	/* ================================================================
	   Section 7: Clubs (نوادي القراءة)
	   ================================================================ */

	$wp_customize->add_section( 'nadiim_front_page_clubs', array(
		'title'    => __( 'Clubs - نوادي القراءة', 'nadiim' ),
		'panel'    => 'nadiim_front_page_panel',
		'priority' => 70,
	) );

	// تفعيل
	$wp_customize->add_setting( 'home_clubs_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_clubs_enable', array(
		'label'    => __( 'تفعيل قسم النوادي', 'nadiim' ),
		'section'  => 'nadiim_front_page_clubs',
		'type'     => 'checkbox',
	) );

	// عدد النوادي
	$wp_customize->add_setting( 'home_clubs_count', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'home_clubs_count', array(
		'label'       => __( 'عدد النوادي', 'nadiim' ),
		'section'     => 'nadiim_front_page_clubs',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 2,
			'max'  => 6,
			'step' => 1,
		),
	) );

	/* ================================================================
	   Section 8: Newsletter (النشرة البريدية)
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
