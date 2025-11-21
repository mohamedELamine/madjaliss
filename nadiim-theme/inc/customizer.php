<?php
/**
 * إعدادات Theme Customizer
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تسجيل إعدادات Customizer
 */
function nadiim_customize_register( $wp_customize ) {

    // ============================================
    // قسم الإعدادات العامة
    // ============================================

    $wp_customize->add_section( 'nadiim_general_settings', array(
        'title'    => __( 'الإعدادات العامة', 'nadiim' ),
        'priority' => 30,
    ) );

    // طول المقتطف
    $wp_customize->add_setting( 'nadiim_excerpt_length', array(
        'default'           => 30,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'nadiim_excerpt_length', array(
        'label'    => __( 'طول المقتطف (عدد الكلمات)', 'nadiim' ),
        'section'  => 'nadiim_general_settings',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 5,
        ),
    ) );

    // ============================================
    // قسم الفوتر
    // ============================================

    $wp_customize->add_section( 'nadiim_footer_settings', array(
        'title'    => __( 'إعدادات الفوتر', 'nadiim' ),
        'priority' => 45,
    ) );

    // نص حقوق النشر
    $wp_customize->add_setting( 'nadiim_copyright_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'nadiim_copyright_text', array(
        'label'    => __( 'نص حقوق النشر', 'nadiim' ),
        'section'  => 'nadiim_footer_settings',
        'type'     => 'textarea',
        'description' => __( 'اتركه فارغاً لاستخدام النص الافتراضي', 'nadiim' ),
    ) );

    // ============================================
    // قسم الصفحة الرئيسية - Hero
    // ============================================

    $wp_customize->add_section( 'nadiim_hero_settings', array(
        'title'    => __( 'قسم Hero - الصفحة الرئيسية', 'nadiim' ),
        'priority' => 50,
    ) );

    // تفعيل قسم Hero
    $wp_customize->add_setting( 'nadiim_hero_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'nadiim_hero_enable', array(
        'label'    => __( 'تفعيل قسم Hero', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
        'type'     => 'checkbox',
    ) );

    // عنوان Hero
    $wp_customize->add_setting( 'nadiim_hero_title', array(
        'default'           => __( 'مرحباً بكم في نديم', 'nadiim' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'nadiim_hero_title', array(
        'label'    => __( 'العنوان', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
        'type'     => 'text',
    ) );

    // وصف Hero
    $wp_customize->add_setting( 'nadiim_hero_description', array(
        'default'           => __( 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة', 'nadiim' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'nadiim_hero_description', array(
        'label'    => __( 'الوصف', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
        'type'     => 'textarea',
    ) );

    // نص الزر
    $wp_customize->add_setting( 'nadiim_hero_button_text', array(
        'default'           => __( 'استكشف المحتوى', 'nadiim' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'nadiim_hero_button_text', array(
        'label'    => __( 'نص الزر', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
        'type'     => 'text',
    ) );

    // رابط الزر
    $wp_customize->add_setting( 'nadiim_hero_button_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'nadiim_hero_button_url', array(
        'label'    => __( 'رابط الزر', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
        'type'     => 'url',
    ) );

    // صورة الخلفية
    $wp_customize->add_setting( 'nadiim_hero_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'nadiim_hero_bg_image', array(
        'label'    => __( 'صورة الخلفية', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
        'mime_type' => 'image',
    ) ) );

    // لون الخلفية
    $wp_customize->add_setting( 'nadiim_hero_bg_color', array(
        'default'           => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nadiim_hero_bg_color', array(
        'label'    => __( 'لون الخلفية (إذا لم توجد صورة)', 'nadiim' ),
        'section'  => 'nadiim_hero_settings',
    ) ) );

    // سيتم إضافة المزيد من الأقسام في المراحل القادمة
}
add_action( 'customize_register', 'nadiim_customize_register' );

/**
 * إضافة CSS مخصص للـ Customizer
 */
function nadiim_customizer_css() {
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo get_theme_mod( 'nadiim_primary_color', '#339063' ); ?>;
        }

        <?php if ( get_theme_mod( 'nadiim_topbar_enable' ) ) : ?>
        .topbar {
            background-color: <?php echo get_theme_mod( 'nadiim_topbar_bg_color', '#339063' ); ?>;
            color: <?php echo get_theme_mod( 'nadiim_topbar_text_color', '#ffffff' ); ?>;
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_customizer_css' );
