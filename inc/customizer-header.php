<?php
/**
 * Header Customizer Settings
 *
 * @package Madjaliss
 * @version 2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Header Customizer Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function madjaliss_header_customizer_register($wp_customize) {

    /**
     * ========================================
     * Header Section
     * ========================================
     */
    $wp_customize->add_section('madjaliss_header_section', array(
        'title'       => __('الهيدر (Header)', 'madjaliss'),
        'description' => __('إعدادات الهيدر الرئيسي للموقع', 'madjaliss'),
        'priority'    => 40,
    ));

    /**
     * ----------------------------------------
     * الشعار (Logo)
     * ----------------------------------------
     */
    $wp_customize->add_setting('header_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_logo', array(
        'label'       => __('شعار الموقع', 'madjaliss'),
        'description' => __('ارفع شعار الموقع (يفضل PNG بخلفية شفافة)', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'priority'    => 10,
    )));

    // عرض الشعار
    $wp_customize->add_setting('header_logo_width', array(
        'default'           => 180,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_logo_width', array(
        'label'       => __('عرض الشعار (px)', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 80,
            'max'  => 300,
            'step' => 10,
        ),
        'priority'    => 20,
    ));

    // هامش الشعار
    $wp_customize->add_setting('header_logo_margin', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_logo_margin', array(
        'label'       => __('هامش الشعار (px)', 'madjaliss'),
        'description' => __('المسافة حول الشعار', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 40,
            'step' => 5,
        ),
        'priority'    => 30,
    ));

    /**
     * ----------------------------------------
     * ألوان الهيدر
     * ----------------------------------------
     */
    $wp_customize->add_setting('header_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', array(
        'label'       => __('لون خلفية الهيدر', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'priority'    => 40,
    )));

    $wp_customize->add_setting('header_text_color', array(
        'default'           => '#1c2d27',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_text_color', array(
        'label'       => __('لون نص الهيدر', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'priority'    => 50,
    )));

    $wp_customize->add_setting('header_link_color', array(
        'default'           => '#1c2d27',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_link_color', array(
        'label'       => __('لون روابط القائمة', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'priority'    => 60,
    )));

    $wp_customize->add_setting('header_link_hover_color', array(
        'default'           => '#d4af37',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_link_hover_color', array(
        'label'       => __('لون الروابط عند التمرير', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'priority'    => 70,
    )));

    $wp_customize->add_setting('header_border_bottom_color', array(
        'default'           => 'rgba(0,0,0,0.06)',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_border_bottom_color', array(
        'label'       => __('لون الحد السفلي', 'madjaliss'),
        'description' => __('يمكنك استخدام rgba للشفافية', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'text',
        'priority'    => 80,
    ));

    /**
     * ----------------------------------------
     * السلوك (Sticky & Shadow)
     * ----------------------------------------
     */
    $wp_customize->add_setting('header_sticky_enable', array(
        'default'           => true,
        'sanitize_callback' => 'madjaliss_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_sticky_enable', array(
        'label'       => __('تفعيل Sticky Header', 'madjaliss'),
        'description' => __('يبقى الهيدر ثابتاً عند التمرير', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'checkbox',
        'priority'    => 90,
    ));

    $wp_customize->add_setting('header_sticky_shadow_enable', array(
        'default'           => true,
        'sanitize_callback' => 'madjaliss_sanitize_checkbox',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_sticky_shadow_enable', array(
        'label'       => __('تفعيل الظل عند التثبيت', 'madjaliss'),
        'description' => __('إضافة ظل للهيدر عند التمرير', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'checkbox',
        'priority'    => 100,
        'active_callback' => function() {
            return get_theme_mod('header_sticky_enable', true);
        },
    ));

    /**
     * ----------------------------------------
     * البحث (Search)
     * ----------------------------------------
     */
    $wp_customize->add_setting('header_search_enable', array(
        'default'           => true,
        'sanitize_callback' => 'madjaliss_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_search_enable', array(
        'label'       => __('تفعيل زر البحث', 'madjaliss'),
        'description' => __('إظهار زر البحث في الهيدر', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'checkbox',
        'priority'    => 110,
    ));

    $wp_customize->add_setting('header_search_placeholder', array(
        'default'           => 'ابحث في مجالس...',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_search_placeholder', array(
        'label'       => __('نص البحث التوضيحي', 'madjaliss'),
        'description' => __('النص الذي يظهر في حقل البحث', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'text',
        'priority'    => 120,
        'active_callback' => function() {
            return get_theme_mod('header_search_enable', true);
        },
    ));

    $wp_customize->add_setting('header_search_position', array(
        'default'           => 'overlay',
        'sanitize_callback' => 'madjaliss_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_search_position', array(
        'label'       => __('موضع البحث', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'select',
        'choices'     => array(
            'overlay' => __('نافذة منبثقة (Overlay)', 'madjaliss'),
            'inline'  => __('داخل الهيدر', 'madjaliss'),
        ),
        'priority'    => 130,
        'active_callback' => function() {
            return get_theme_mod('header_search_enable', true);
        },
    ));

    /**
     * ----------------------------------------
     * التحكم في القائمة
     * ----------------------------------------
     */
    $wp_customize->add_setting('header_menu_alignment', array(
        'default'           => 'center',
        'sanitize_callback' => 'madjaliss_sanitize_select',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_menu_alignment', array(
        'label'       => __('محاذاة القائمة', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'select',
        'choices'     => array(
            'right'  => __('يمين', 'madjaliss'),
            'center' => __('وسط', 'madjaliss'),
            'left'   => __('يسار', 'madjaliss'),
        ),
        'priority'    => 140,
    ));

    $wp_customize->add_setting('header_menu_item_spacing', array(
        'default'           => 8,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_menu_item_spacing', array(
        'label'       => __('المسافة بين عناصر القائمة (px)', 'madjaliss'),
        'section'     => 'madjaliss_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 30,
            'step' => 2,
        ),
        'priority'    => 150,
    ));

}
add_action('customize_register', 'madjaliss_header_customizer_register');

/**
 * ========================================
 * Live Preview (JavaScript)
 * ========================================
 */
function madjaliss_header_customizer_live_preview() {
    wp_enqueue_script(
        'madjaliss-header-customizer',
        get_template_directory_uri() . '/assets/js/customizer-header.js',
        array('jquery', 'customize-preview'),
        '2.0',
        true
    );
}
add_action('customize_preview_init', 'madjaliss_header_customizer_live_preview');

/**
 * ========================================
 * Output Custom CSS
 * ========================================
 */
function madjaliss_header_customizer_css() {
    $logo_width = get_theme_mod('header_logo_width', 180);
    $logo_margin = get_theme_mod('header_logo_margin', 0);
    $bg_color = get_theme_mod('header_bg_color', '#ffffff');
    $text_color = get_theme_mod('header_text_color', '#1c2d27');
    $link_color = get_theme_mod('header_link_color', '#1c2d27');
    $link_hover_color = get_theme_mod('header_link_hover_color', '#d4af37');
    $border_color = get_theme_mod('header_border_bottom_color', 'rgba(0,0,0,0.06)');
    $menu_spacing = get_theme_mod('header_menu_item_spacing', 8);

    $custom_css = "
    :root {
        --header-bg: {$bg_color};
        --header-color: {$text_color};
        --header-border: {$border_color};
    }
    .site-header {
        background-color: {$bg_color};
        color: {$text_color};
        border-bottom-color: {$border_color};
    }
    .custom-logo {
        width: {$logo_width}px;
        margin: {$logo_margin}px;
    }
    .primary-menu > li > a {
        color: {$link_color};
    }
    .primary-menu > li > a:hover,
    .primary-menu > li.current-menu-item > a {
        color: {$link_hover_color};
    }
    .primary-menu {
        gap: {$menu_spacing}px;
    }
    ";

    wp_add_inline_style('madjaliss-header', $custom_css);
}
add_action('wp_enqueue_scripts', 'madjaliss_header_customizer_css', 20);
