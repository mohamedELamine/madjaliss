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

    // سيتم إضافة المزيد من الأقسام في المراحل القادمة
    // Hero Slider settings moved to inc/customizer-hero.php
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
