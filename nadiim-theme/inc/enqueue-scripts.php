<?php
/**
 * تحميل الأصول (CSS & JS) - منفصل من functions.php
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تحميل الأصول (CSS & JS) للواجهة الأمامية
 */
function nadiim_enqueue_scripts() {
    // تحميل خط Cairo من Google Fonts
    wp_enqueue_style(
        'nadiim-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap',
        array(),
        null
    );

    // تحميل ملف CSS variables المركزي
    wp_enqueue_style(
        'nadiim-variables',
        NADIIM_THEME_URI . '/assets/css/variables.css',
        array(),
        NADIIM_VERSION
    );

    // تحميل ملف CSS الرئيسي
    wp_enqueue_style(
        'nadiim-style',
        get_stylesheet_uri(),
        array( 'nadiim-variables' ),
        NADIIM_VERSION
    );

    // تحميل ملف CSS الإضافي
    wp_enqueue_style(
        'nadiim-main',
        NADIIM_THEME_URI . '/assets/css/main.css',
        array( 'nadiim-variables' ),
        NADIIM_VERSION
    );

    // تحميل ملف التحسينات الجمالية
    wp_enqueue_style(
        'nadiim-enhancements',
        NADIIM_THEME_URI . '/assets/css/enhancements.css',
        array( 'nadiim-main' ),
        NADIIM_VERSION
    );

    // تحميل ملف CSS للهيدر
    wp_enqueue_style(
        'nadiim-header',
        NADIIM_THEME_URI . '/assets/css/header.css',
        array( 'nadiim-main' ),
        NADIIM_VERSION
    );

    // تحميل ملف CSS المتجاوب (Responsive)
    wp_enqueue_style(
        'nadiim-responsive',
        NADIIM_THEME_URI . '/assets/css/responsive.css',
        array( 'nadiim-main' ),
        NADIIM_VERSION
    );

    // تحميل JavaScript الرئيسي
    wp_enqueue_script(
        'nadiim-main',
        NADIIM_THEME_URI . '/assets/js/main.js',
        array( 'jquery' ),
        NADIIM_VERSION,
        true
    );

    // تحميل JavaScript للهيدر
    wp_enqueue_script(
        'nadiim-header',
        NADIIM_THEME_URI . '/assets/js/header.js',
        array(),
        NADIIM_VERSION,
        true
    );

    // تمرير متغيرات إلى JavaScript
    wp_localize_script( 'nadiim-main', 'nadiimVars', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'nadiim-nonce' ),
        'strings' => array(
            'loading' => __( 'جارٍ التحميل...', 'nadiim' ),
            'error'   => __( 'حدث خطأ، يُرجى المحاولة مرة أخرى', 'nadiim' ),
        ),
    ) );

    // تحميل ملف التعليقات
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_enqueue_scripts' );

/**
 * تحميل أصول الإصدارات (CSS & JS)
 */
function nadiim_esdar_enqueue_assets() {
    // تحميل CSS للإصدارات
    wp_enqueue_style(
        'nadiim-esdar',
        NADIIM_THEME_URI . '/assets/css/esdar.css',
        array( 'nadiim-main' ),
        NADIIM_VERSION
    );

    // تحميل JS للواجهة الأمامية
    if ( is_singular('esdar') || is_post_type_archive('esdar') ) {
        wp_enqueue_script(
            'nadiim-esdar-frontend',
            NADIIM_THEME_URI . '/assets/js/esdar-frontend.js',
            array(),
            NADIIM_VERSION,
            true
        );

        // تمرير nonce للتحميل
        wp_localize_script( 'nadiim-esdar-frontend', 'esdarFrontend', array(
            'downloadNonce' => wp_create_nonce( 'esdar_download_nonce' ),
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_esdar_enqueue_assets' );

/**
 * تحميل أصول نوادي القراءة (CSS & JS & Leaflet)
 */
function nadiim_reading_clubs_enqueue_assets() {
    // تحميل Leaflet CSS فقط في صفحات النوادي
    if ( is_singular('reading_clubs') ) {
        wp_enqueue_style(
            'leaflet-css',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );
    }

    // تحميل CSS لنوادي القراءة
    wp_enqueue_style(
        'nadiim-reading-clubs',
        NADIIM_THEME_URI . '/assets/css/clubs.css',
        array( 'nadiim-main' ),
        NADIIM_VERSION
    );

    // تحميل JS للواجهة الأمامية
    if ( is_singular('reading_clubs') || is_post_type_archive('reading_clubs') ) {
        // تحميل Leaflet JS
        if ( is_singular('reading_clubs') ) {
            wp_enqueue_script(
                'leaflet-js',
                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                array(),
                '1.9.4',
                true
            );
        }

        // تحميل JS المخصص
        wp_enqueue_script(
            'nadiim-clubs-frontend',
            NADIIM_THEME_URI . '/assets/js/clubs-frontend.js',
            array( 'jquery' ),
            NADIIM_VERSION,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_reading_clubs_enqueue_assets' );

/**
 * تحميل ملفات CSS و JS للمحرر
 */
function nadiim_editor_styles() {
    add_editor_style( array(
        'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap',
        'assets/css/variables.css',
        'assets/css/editor-style.css',
    ) );
}
add_action( 'admin_init', 'nadiim_editor_styles' );
