<?php
/**
 * إعدادات القالب الأساسية - منفصل من functions.php
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إعداد القالب الأساسي
 */
function nadiim_setup() {
    // دعم اللغات والترجمة
    load_theme_textdomain( 'nadiim', NADIIM_THEME_DIR . '/languages' );

    // دعم عنوان الصفحة التلقائي
    add_theme_support( 'title-tag' );

    // دعم الصور المميزة
    add_theme_support( 'post-thumbnails' );

    // أحجام الصور المخصصة
    add_image_size( 'nadiim-featured', 800, 500, true );
    add_image_size( 'nadiim-card', 400, 300, true );
    add_image_size( 'nadiim-thumbnail', 200, 200, true );
    add_image_size( 'nadiim-hero', 1920, 800, true );

    // دعم HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // دعم الخلاصات التلقائية
    add_theme_support( 'automatic-feed-links' );

    // دعم تحديث القالب
    add_theme_support( 'customize-selective-refresh-widgets' );

    // دعم شعار القالب المخصص
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // دعم ألوان المحرر
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => __( 'اللون الأساسي', 'nadiim' ),
            'slug'  => 'primary',
            'color' => '#339063',
        ),
        array(
            'name'  => __( 'اللون الأساسي الفاتح', 'nadiim' ),
            'slug'  => 'primary-light',
            'color' => '#4db080',
        ),
        array(
            'name'  => __( 'النص الأساسي', 'nadiim' ),
            'slug'  => 'text-primary',
            'color' => '#2c3e50',
        ),
    ) );

    // تسجيل مواقع القوائم
    register_nav_menus( array(
        'primary'   => __( 'القائمة الرئيسية', 'nadiim' ),
        'footer'    => __( 'قائمة الفوتر', 'nadiim' ),
        'social'    => __( 'قائمة التواصل الاجتماعي', 'nadiim' ),
    ) );
}
add_action( 'after_setup_theme', 'nadiim_setup' );

/**
 * تعيين عرض المحتوى
 */
function nadiim_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'nadiim_content_width', 1200 );
}
add_action( 'after_setup_theme', 'nadiim_content_width', 0 );

/**
 * تسجيل مناطق الودجات
 */
function nadiim_widgets_init() {
    // منطقة الشريط الجانبي
    register_sidebar( array(
        'name'          => __( 'الشريط الجانبي', 'nadiim' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'يظهر في صفحات المدونة والمقالات', 'nadiim' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // مناطق الفوتر
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( __( 'الفوتر - العمود %d', 'nadiim' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => sprintf( __( 'العمود %d في منطقة الفوتر', 'nadiim' ), $i ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );
    }

    // منطقة الصفحة الرئيسية - Hero
    register_sidebar( array(
        'name'          => __( 'الصفحة الرئيسية - Hero', 'nadiim' ),
        'id'            => 'home-hero',
        'description'   => __( 'قسم Hero في الصفحة الرئيسية', 'nadiim' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    // منطقة الصفحة الرئيسية - الحوارات
    register_sidebar( array(
        'name'          => __( 'الصفحة الرئيسية - الحوارات', 'nadiim' ),
        'id'            => 'home-dialogues',
        'description'   => __( 'قسم الحوارات في الصفحة الرئيسية', 'nadiim' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ) );

    // منطقة الصفحة الرئيسية - الإصدارات
    register_sidebar( array(
        'name'          => __( 'الصفحة الرئيسية - الإصدارات', 'nadiim' ),
        'id'            => 'home-releases',
        'description'   => __( 'قسم الإصدارات في الصفحة الرئيسية', 'nadiim' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'nadiim_widgets_init' );
