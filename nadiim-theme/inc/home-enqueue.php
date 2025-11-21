<?php
/**
 * تحميل أصول الصفحة الرئيسية (Hero Slider)
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles للصفحة الرئيسية
 */
function nadiim_home_enqueue_assets() {
    // تحميل فقط في الصفحة الرئيسية
    if ( ! is_front_page() ) {
        return;
    }

    // ─────────────────────────────────────
    // Swiper Library (CDN)
    // ─────────────────────────────────────

    // Swiper CSS
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );

    // Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );

    // ─────────────────────────────────────
    // Hero Slider Assets
    // ─────────────────────────────────────

    // Hero CSS
    wp_enqueue_style(
        'nadiim-hero',
        get_template_directory_uri() . '/assets/css/hero.css',
        array( 'swiper' ),
        NADIIM_VERSION
    );

    // Hero JS
    wp_enqueue_script(
        'nadiim-hero',
        get_template_directory_uri() . '/assets/js/hero-slider.js',
        array( 'swiper' ),
        NADIIM_VERSION,
        true
    );

    // تمرير إعدادات Hero Slider إلى JavaScript
    wp_localize_script(
        'nadiim-hero',
        'NADIIM_HERO',
        array(
            'autoplay' => get_theme_mod( 'hero_autoplay', true ),
            'delay'    => get_theme_mod( 'hero_delay', 6000 ),
        )
    );

    // ─────────────────────────────────────
    // Customizer Live Preview
    // ─────────────────────────────────────

    if ( is_customize_preview() ) {
        wp_enqueue_script(
            'nadiim-hero-customizer-live',
            get_template_directory_uri() . '/assets/js/hero-customizer-live.js',
            array( 'jquery', 'customize-preview' ),
            NADIIM_VERSION,
            true
        );
    }

    // ─────────────────────────────────────
    // Featured Howarat Section Assets
    // ─────────────────────────────────────

    // Featured Howarat CSS
    wp_enqueue_style(
        'nadiim-featured-howarat',
        get_template_directory_uri() . '/assets/css/featured-howarat.css',
        array( 'swiper' ),
        NADIIM_VERSION
    );

    // Featured Howarat JS
    wp_enqueue_script(
        'nadiim-featured-howarat',
        get_template_directory_uri() . '/assets/js/featured-howarat.js',
        array( 'swiper' ),
        NADIIM_VERSION,
        true
    );

    // تمرير إعدادات Featured Howarat إلى JavaScript
    wp_localize_script(
        'nadiim-featured-howarat',
        'NADIIM_FEATURED_HOWARAT',
        array(
            'layout' => get_theme_mod( 'featured_howarat_layout', 'grid' ),
        )
    );

    // Featured Howarat Customizer Live Preview
    if ( is_customize_preview() ) {
        wp_enqueue_script(
            'nadiim-featured-howarat-customizer-live',
            get_template_directory_uri() . '/assets/js/featured-howarat-customizer-live.js',
            array( 'jquery', 'customize-preview' ),
            NADIIM_VERSION,
            true
        );
    }

    // ─────────────────────────────────────
    // Esdar (Releases) Section Assets
    // ─────────────────────────────────────

    // Esdar CSS
    wp_enqueue_style(
        'nadiim-esdar',
        get_template_directory_uri() . '/assets/css/esdar.css',
        array( 'swiper' ),
        NADIIM_VERSION
    );

    // Esdar JS
    wp_enqueue_script(
        'nadiim-esdar',
        get_template_directory_uri() . '/assets/js/esdar.js',
        array( 'swiper' ),
        NADIIM_VERSION,
        true
    );

    // تمرير إعدادات Esdar إلى JavaScript
    wp_localize_script(
        'nadiim-esdar',
        'NADIIM_ESDAR',
        array(
            'layout' => get_theme_mod( 'esdar_section_layout', 'grid' ),
        )
    );

    // Esdar Customizer Live Preview
    if ( is_customize_preview() ) {
        wp_enqueue_script(
            'nadiim-esdar-customizer-live',
            get_template_directory_uri() . '/assets/js/esdar-customizer-live.js',
            array( 'jquery', 'customize-preview' ),
            NADIIM_VERSION,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_home_enqueue_assets' );
