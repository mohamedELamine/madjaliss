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
    // قسم الهيدر
    // ============================================

    $wp_customize->add_section( 'nadiim_header_settings', array(
        'title'    => __( 'إعدادات الهيدر', 'nadiim' ),
        'priority' => 35,
    ) );

    // إظهار زر تسجيل الدخول
    $wp_customize->add_setting( 'nadiim_header_login_button', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'nadiim_header_login_button', array(
        'label'    => __( 'إظهار زر تسجيل الدخول', 'nadiim' ),
        'section'  => 'nadiim_header_settings',
        'type'     => 'checkbox',
    ) );

    // ============================================
    // قسم الشريط العلوي (Topbar)
    // ============================================

    $wp_customize->add_section( 'nadiim_topbar_settings', array(
        'title'       => __( 'الشريط العلوي للأحداث', 'nadiim' ),
        'description' => __( 'إعدادات الشريط العلوي الذي يعرض الأحداث القادمة', 'nadiim' ),
        'priority'    => 40,
    ) );

    // تفعيل الشريط العلوي
    $wp_customize->add_setting( 'nadiim_topbar_enable', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'nadiim_topbar_enable', array(
        'label'    => __( 'تفعيل الشريط العلوي', 'nadiim' ),
        'section'  => 'nadiim_topbar_settings',
        'type'     => 'checkbox',
    ) );

    // نص التسمية
    $wp_customize->add_setting( 'nadiim_topbar_label', array(
        'default'           => __( 'الأحداث القادمة:', 'nadiim' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'nadiim_topbar_label', array(
        'label'    => __( 'نص التسمية', 'nadiim' ),
        'section'  => 'nadiim_topbar_settings',
        'type'     => 'text',
    ) );

    // عدد الأحداث
    $wp_customize->add_setting( 'nadiim_topbar_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'nadiim_topbar_count', array(
        'label'    => __( 'عدد الأحداث المعروضة', 'nadiim' ),
        'section'  => 'nadiim_topbar_settings',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 10,
            'step' => 1,
        ),
    ) );

    // لون خلفية الشريط
    $wp_customize->add_setting( 'nadiim_topbar_bg_color', array(
        'default'           => '#339063',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nadiim_topbar_bg_color', array(
        'label'    => __( 'لون الخلفية', 'nadiim' ),
        'section'  => 'nadiim_topbar_settings',
    ) ) );

    // لون النص
    $wp_customize->add_setting( 'nadiim_topbar_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nadiim_topbar_text_color', array(
        'label'    => __( 'لون النص', 'nadiim' ),
        'section'  => 'nadiim_topbar_settings',
    ) ) );

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

    // ============================================
    // قسم صفحة المدونة
    // ============================================

    $wp_customize->add_section( 'nadiim_blog_settings', array(
        'title'       => __( 'صفحة المدونة', 'nadiim' ),
        'description' => __( 'إعدادات صفحة المدونة المنفصلة عن الصفحة الرئيسية', 'nadiim' ),
        'priority'    => 55,
    ) );

    // عنوان صفحة المدونة
    $wp_customize->add_setting( 'nadiim_blog_title', array(
        'default'           => __( 'المدونة', 'nadiim' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'nadiim_blog_title', array(
        'label'    => __( 'عنوان صفحة المدونة', 'nadiim' ),
        'section'  => 'nadiim_blog_settings',
        'type'     => 'text',
    ) );

    // وصف صفحة المدونة
    $wp_customize->add_setting( 'nadiim_blog_description', array(
        'default'           => __( 'مقالات ومحتوى متنوع حول الثقافة والأدب والفكر', 'nadiim' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'nadiim_blog_description', array(
        'label'    => __( 'وصف صفحة المدونة', 'nadiim' ),
        'section'  => 'nadiim_blog_settings',
        'type'     => 'textarea',
    ) );

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
