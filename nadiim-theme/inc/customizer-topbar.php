<?php
/**
 * Top Bar Customizer Settings
 *
 * @package Madjaliss
 * @version 2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Top Bar Customizer Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function madjaliss_topbar_customizer_register($wp_customize) {

    /**
     * ========================================
     * Top Bar Section
     * ========================================
     */
    $wp_customize->add_section('madjaliss_topbar_section', array(
        'title'       => __('الشريط العلوي (Top Bar)', 'madjaliss'),
        'description' => __('إعدادات الشريط العلوي في أعلى الموقع', 'madjaliss'),
        'priority'    => 30,
    ));

    /**
     * ----------------------------------------
     * تفعيل/إلغاء الشريط العلوي
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_enable', array(
        'default'           => true,
        'sanitize_callback' => 'madjaliss_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_enable', array(
        'label'       => __('تفعيل الشريط العلوي', 'madjaliss'),
        'description' => __('إظهار أو إخفاء الشريط العلوي', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'checkbox',
        'priority'    => 10,
    ));

    /**
     * ----------------------------------------
     * النص الثابت
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_text', array(
        'default'           => 'مرحباً بكم في مجالس - منصة الحوار والفكر',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_text', array(
        'label'       => __('نص الشريط العلوي', 'madjaliss'),
        'description' => __('النص الذي سيظهر في الشريط العلوي', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'textarea',
        'priority'    => 20,
    ));

    /**
     * ----------------------------------------
     * المحتوى الديناميكي
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_dynamic_enable', array(
        'default'           => false,
        'sanitize_callback' => 'madjaliss_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_dynamic_enable', array(
        'label'       => __('تفعيل المحتوى الديناميكي', 'madjaliss'),
        'description' => __('عرض أحدث مقال أو حوار بدلاً من النص الثابت', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'checkbox',
        'priority'    => 30,
    ));

    // نوع المحتوى الديناميكي
    $wp_customize->add_setting('topbar_dynamic_post_type', array(
        'default'           => 'post',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_dynamic_post_type', array(
        'label'       => __('نوع المحتوى', 'madjaliss'),
        'description' => __('اختر نوع المحتوى الذي تريد عرضه', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'select',
        'choices'     => array(
            'post'     => __('مقالات', 'madjaliss'),
            'dialogue' => __('حوارات', 'madjaliss'),
            'event'    => __('أحداث', 'madjaliss'),
        ),
        'priority'    => 40,
        'active_callback' => function() {
            return get_theme_mod('topbar_dynamic_enable', false);
        },
    ));

    // وسم المحتوى الديناميكي
    $wp_customize->add_setting('topbar_dynamic_tag', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_dynamic_tag', array(
        'label'       => __('الوسم (Tag)', 'madjaliss'),
        'description' => __('اختياري: تصفية حسب وسم معين', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'text',
        'priority'    => 50,
        'active_callback' => function() {
            return get_theme_mod('topbar_dynamic_enable', false);
        },
    ));

    // عدد المقالات
    $wp_customize->add_setting('topbar_dynamic_limit', array(
        'default'           => 1,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_dynamic_limit', array(
        'label'       => __('عدد المقالات', 'madjaliss'),
        'description' => __('عدد المقالات التي سيتم عرضها (1-5)', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'priority'    => 60,
        'active_callback' => function() {
            return get_theme_mod('topbar_dynamic_enable', false);
        },
    ));

    /**
     * ----------------------------------------
     * ألوان الشريط العلوي
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_bg_color', array(
        'default'           => '#1c2d27',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'topbar_bg_color', array(
        'label'       => __('لون الخلفية', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'priority'    => 70,
    )));

    $wp_customize->add_setting('topbar_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'topbar_text_color', array(
        'label'       => __('لون النص', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'priority'    => 80,
    )));

    /**
     * ----------------------------------------
     * حجم الخط
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_font_size', array(
        'default'           => 14,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('topbar_font_size', array(
        'label'       => __('حجم الخط (px)', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 18,
            'step' => 1,
        ),
        'priority'    => 90,
    ));

    /**
     * ----------------------------------------
     * محاذاة النص
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_alignment', array(
        'default'           => 'right',
        'sanitize_callback' => 'madjaliss_sanitize_select',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('topbar_alignment', array(
        'label'       => __('محاذاة النص', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'select',
        'choices'     => array(
            'right'  => __('يمين', 'madjaliss'),
            'center' => __('وسط', 'madjaliss'),
            'left'   => __('يسار', 'madjaliss'),
        ),
        'priority'    => 100,
    ));

    /**
     * ----------------------------------------
     * الأيقونة
     * ----------------------------------------
     */
    $wp_customize->add_setting('topbar_icon', array(
        'default'           => 'megaphone',
        'sanitize_callback' => 'madjaliss_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('topbar_icon', array(
        'label'       => __('أيقونة الشريط العلوي', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'type'        => 'select',
        'choices'     => array(
            'megaphone' => __('📢 مكبر صوت', 'madjaliss'),
            'bell'      => __('🔔 جرس', 'madjaliss'),
            'calendar'  => __('📅 تقويم', 'madjaliss'),
            'info'      => __('ℹ️ معلومات', 'madjaliss'),
            'star'      => __('⭐ نجمة', 'madjaliss'),
        ),
        'priority'    => 110,
    ));

    // رفع أيقونة مخصصة
    $wp_customize->add_setting('topbar_custom_icon', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'topbar_custom_icon', array(
        'label'       => __('أو ارفع أيقونة مخصصة', 'madjaliss'),
        'description' => __('إذا رفعت أيقونة، ستحل محل الأيقونة المحددة أعلاه', 'madjaliss'),
        'section'     => 'madjaliss_topbar_section',
        'priority'    => 120,
    )));

}
add_action('customize_register', 'madjaliss_topbar_customizer_register');

/**
 * ========================================
 * Sanitization Functions
 * ========================================
 */

/**
 * Sanitize checkbox
 */
function madjaliss_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select
 */
function madjaliss_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * ========================================
 * Live Preview (JavaScript)
 * ========================================
 */
function madjaliss_topbar_customizer_live_preview() {
    wp_enqueue_script(
        'madjaliss-topbar-customizer',
        get_template_directory_uri() . '/assets/js/customizer-topbar.js',
        array('jquery', 'customize-preview'),
        '2.0',
        true
    );
}
add_action('customize_preview_init', 'madjaliss_topbar_customizer_live_preview');
