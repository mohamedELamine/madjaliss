<?php
/**
 * إعدادات Customizer لقسم المقالات
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات قسم المقالات في Customizer
 */
function nadiim_register_articles_section_customizer( $wp_customize ) {

	// ========================================
	// Panel: الصفحة الرئيسية
	// ========================================
	// التحقق من وجود Panel الصفحة الرئيسية، وإنشاؤه إذا لم يكن موجوداً
	if ( ! $wp_customize->get_panel( 'nadiim_front_page' ) ) {
		$wp_customize->add_panel( 'nadiim_front_page', array(
			'title'       => __( 'الصفحة الرئيسية', 'nadiim' ),
			'description' => __( 'إعدادات أقسام الصفحة الرئيسية', 'nadiim' ),
			'priority'    => 30,
		) );
	}

	// ========================================
	// Section: قسم المقالات
	// ========================================
	$wp_customize->add_section( 'nadiim_articles_section', array(
		'title'    => __( 'قسم المقالات', 'nadiim' ),
		'panel'    => 'nadiim_front_page',
		'priority' => 60,
	) );

	// ========================================
	// Setting: تفعيل/إخفاء القسم
	// ========================================
	$wp_customize->add_setting( 'articles_section_enable', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'articles_section_enable', array(
		'label'    => __( 'تفعيل قسم المقالات', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'checkbox',
		'priority' => 10,
	) );

	// ========================================
	// Setting: عنوان القسم
	// ========================================
	$wp_customize->add_setting( 'articles_section_title', array(
		'default'           => __( 'المقالات', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'articles_section_title', array(
		'label'    => __( 'عنوان القسم', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'text',
		'priority' => 20,
	) );

	// ========================================
	// Setting: وصف القسم
	// ========================================
	$wp_customize->add_setting( 'articles_section_description', array(
		'default'           => __( 'استكشف مقالاتنا حول القراءة والكتب والثقافة', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'articles_section_description', array(
		'label'    => __( 'وصف القسم', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'textarea',
		'priority' => 30,
	) );

	// ========================================
	// Setting: عدد المقالات
	// ========================================
	$wp_customize->add_setting( 'articles_section_count', array(
		'default'           => 6,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'articles_section_count', array(
		'label'       => __( 'عدد المقالات المعروضة', 'nadiim' ),
		'description' => __( 'عدد المقالات التي سيتم عرضها في القسم', 'nadiim' ),
		'section'     => 'nadiim_articles_section',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 12,
			'step' => 1,
		),
		'priority'    => 40,
	) );

	// ========================================
	// Setting: نوع التخطيط (List/Grid)
	// ========================================
	$wp_customize->add_setting( 'articles_section_layout', array(
		'default'           => 'grid',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'articles_section_layout', array(
		'label'    => __( 'نوع التخطيط', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'select',
		'choices'  => array(
			'grid' => __( 'شبكة - عمودين (Grid)', 'nadiim' ),
			'list' => __( 'قائمة عمودية (List)', 'nadiim' ),
		),
		'priority' => 50,
	) );

	// ========================================
	// Setting: نوع الفلترة
	// ========================================
	$wp_customize->add_setting( 'articles_section_filter', array(
		'default'           => 'latest',
		'sanitize_callback' => 'nadiim_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'articles_section_filter', array(
		'label'    => __( 'نوع الفلترة', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'select',
		'choices'  => array(
			'latest'   => __( 'أحدث المقالات', 'nadiim' ),
			'category' => __( 'حسب التصنيف', 'nadiim' ),
			'tag'      => __( 'حسب الوسم', 'nadiim' ),
			'author'   => __( 'حسب الكاتب', 'nadiim' ),
		),
		'priority' => 60,
	) );

	// ========================================
	// Setting: التصنيف
	// ========================================
	$wp_customize->add_setting( 'articles_section_category', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$categories_choices = array( '' => __( 'اختر تصنيفاً', 'nadiim' ) );
	$categories = get_categories( array( 'hide_empty' => false ) );
	foreach ( $categories as $category ) {
		$categories_choices[ $category->term_id ] = $category->name;
	}

	$wp_customize->add_control( 'articles_section_category', array(
		'label'           => __( 'التصنيف', 'nadiim' ),
		'section'         => 'nadiim_articles_section',
		'type'            => 'select',
		'choices'         => $categories_choices,
		'priority'        => 70,
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'articles_section_filter' )->value() === 'category';
		},
	) );

	// ========================================
	// Setting: الوسم
	// ========================================
	$wp_customize->add_setting( 'articles_section_tag', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'articles_section_tag', array(
		'label'           => __( 'الوسم (Slug)', 'nadiim' ),
		'description'     => __( 'أدخل slug الوسم (مثال: featured)', 'nadiim' ),
		'section'         => 'nadiim_articles_section',
		'type'            => 'text',
		'priority'        => 80,
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'articles_section_filter' )->value() === 'tag';
		},
	) );

	// ========================================
	// Setting: الكاتب
	// ========================================
	$wp_customize->add_setting( 'articles_section_author', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$authors_choices = array( '' => __( 'اختر كاتباً', 'nadiim' ) );
	$authors = get_users( array( 'who' => 'authors' ) );
	foreach ( $authors as $author ) {
		$authors_choices[ $author->ID ] = $author->display_name;
	}

	$wp_customize->add_control( 'articles_section_author', array(
		'label'           => __( 'الكاتب', 'nadiim' ),
		'section'         => 'nadiim_articles_section',
		'type'            => 'select',
		'choices'         => $authors_choices,
		'priority'        => 90,
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'articles_section_filter' )->value() === 'author';
		},
	) );

	// ========================================
	// Setting: نص زر CTA
	// ========================================
	$wp_customize->add_setting( 'articles_section_cta_text', array(
		'default'           => __( 'اقرأ المزيد', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'articles_section_cta_text', array(
		'label'    => __( 'نص زر القراءة', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'text',
		'priority' => 100,
	) );

	// ========================================
	// Setting: إظهار زر "جميع المقالات"
	// ========================================
	$wp_customize->add_setting( 'articles_section_show_more', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'articles_section_show_more', array(
		'label'    => __( 'إظهار زر "جميع المقالات"', 'nadiim' ),
		'section'  => 'nadiim_articles_section',
		'type'     => 'checkbox',
		'priority' => 110,
	) );

	// ========================================
	// Setting: نص زر "جميع المقالات"
	// ========================================
	$wp_customize->add_setting( 'articles_section_more_text', array(
		'default'           => __( 'جميع المقالات', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'articles_section_more_text', array(
		'label'           => __( 'نص زر "جميع المقالات"', 'nadiim' ),
		'section'         => 'nadiim_articles_section',
		'type'            => 'text',
		'priority'        => 120,
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'articles_section_show_more' )->value() === true;
		},
	) );
}
add_action( 'customize_register', 'nadiim_register_articles_section_customizer' );
