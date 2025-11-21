<?php
/**
 * Home Page Assets Enqueue
 *
 * تحميل ملفات CSS و JavaScript الخاصة بالصفحة الرئيسية
 *
 * @package Madjaliss
 * @version 2.0 - Stage 1B
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * تحميل أصول الصفحة الرئيسية
 */
function nadiim_enqueue_home_assets() {
    // تحميل فقط في الصفحة الرئيسية
    if (!is_front_page() && !is_home()) {
        return;
    }

    $version = '2.0';

    /**
     * ========================================
     * Swiper.js Library
     * ========================================
     */

    // Swiper CSS
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
        array(),
        '8.4.7'
    );

    // Swiper JS
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
        array(),
        '8.4.7',
        true
    );

    /**
     * ========================================
     * Homepage CSS
     * ========================================
     */
    wp_enqueue_style(
        'nadiim-home-css',
        get_template_directory_uri() . '/assets/css/homepage.css',
        array('madjaliss-style'),
        $version
    );

    /**
     * ========================================
     * Homepage JavaScript Files
     * ========================================
     */

    // Home Slider JS
    wp_enqueue_script(
        'nadiim-home-slider-js',
        get_template_directory_uri() . '/assets/js/home-slider.js',
        array('swiper-js'),
        $version,
        true
    );

    // Home Cursor JS
    wp_enqueue_script(
        'nadiim-home-cursor-js',
        get_template_directory_uri() . '/assets/js/home-cursor.js',
        array(),
        $version,
        true
    );

    /**
     * ========================================
     * Localize Scripts
     * ========================================
     */

    // إرسال إعدادات JavaScript
    $home_settings = array(
        'autoplay'    => get_theme_mod('home_slider_autoplay', true),
        'delay'       => get_theme_mod('home_slider_delay', 6000),
        'show_cursor' => get_theme_mod('home_slider_show_cursor', true),
    );

    wp_localize_script(
        'nadiim-home-slider-js',
        'NADIIM_HOME',
        $home_settings
    );
}
add_action('wp_enqueue_scripts', 'nadiim_enqueue_home_assets');

/**
 * ========================================
 * Customizer Settings للصفحة الرئيسية
 * ========================================
 */
function nadiim_home_slider_customizer_register($wp_customize) {

    /**
     * قسم إعدادات السلايدر الرئيسي
     */
    $wp_customize->add_section('nadiim_home_slider_section', array(
        'title'       => __('السلايدر الرئيسي', 'madjaliss'),
        'description' => __('إعدادات السلايدر في الصفحة الرئيسية', 'madjaliss'),
        'priority'    => 50,
    ));

    // عنوان السلايدر
    $wp_customize->add_setting('home_slider_title', array(
        'default'           => 'أحدث المقالات',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('home_slider_title', array(
        'label'       => __('عنوان السلايدر', 'madjaliss'),
        'section'     => 'nadiim_home_slider_section',
        'type'        => 'text',
        'priority'    => 10,
    ));

    // عدد الشرائح
    $wp_customize->add_setting('home_slider_count', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('home_slider_count', array(
        'label'       => __('عدد الشرائح', 'madjaliss'),
        'description' => __('عدد المقالات التي سيتم عرضها في السلايدر (1-10)', 'madjaliss'),
        'section'     => 'nadiim_home_slider_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 10,
            'step' => 1,
        ),
        'priority'    => 20,
    ));

    // تفعيل Autoplay
    $wp_customize->add_setting('home_slider_autoplay', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('home_slider_autoplay', array(
        'label'       => __('تفعيل التشغيل التلقائي', 'madjaliss'),
        'description' => __('التبديل التلقائي بين الشرائح', 'madjaliss'),
        'section'     => 'nadiim_home_slider_section',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    // مدة الانتظار
    $wp_customize->add_setting('home_slider_delay', array(
        'default'           => 6000,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('home_slider_delay', array(
        'label'       => __('مدة عرض كل شريحة (ميلي ثانية)', 'madjaliss'),
        'description' => __('المدة بالميلي ثانية (1000 = 1 ثانية)', 'madjaliss'),
        'section'     => 'nadiim_home_slider_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 2000,
            'max'  => 15000,
            'step' => 1000,
        ),
        'priority'    => 40,
        'active_callback' => function() {
            return get_theme_mod('home_slider_autoplay', true);
        },
    ));

    // تفعيل الكرسر المخصص
    $wp_customize->add_setting('home_slider_show_cursor', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('home_slider_show_cursor', array(
        'label'       => __('تفعيل المؤشر المخصص', 'madjaliss'),
        'description' => __('عرض مؤشر الفأرة المخصص في الصفحة الرئيسية', 'madjaliss'),
        'section'     => 'nadiim_home_slider_section',
        'type'        => 'checkbox',
        'priority'    => 50,
    ));

}
add_action('customize_register', 'nadiim_home_slider_customizer_register');

/**
 * ========================================
 * تعليمات الربط بـ functions.php
 * ========================================
 *
 * لربط هذا الملف بـ functions.php، أضف السطر التالي:
 *
 * require_once get_template_directory() . '/inc/home-enqueue.php';
 *
 * ========================================
 */
