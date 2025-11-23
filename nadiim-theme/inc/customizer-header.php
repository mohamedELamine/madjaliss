<?php
/**
 * إعدادات Theme Customizer للهيدر والشريط العلوي
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تسجيل إعدادات Customizer للهيدر والتوب بار
 */
function nadiim_header_customizer_register( $wp_customize ) {

    // ============================================
    // قسم الهيدر (Header)
    // ============================================

    $wp_customize->add_section( 'nadiim_header_section', array(
        'title'       => __( 'الهيدر الرئيسي (Header)', 'nadiim' ),
        'description' => __( 'إعدادات الهيدر والقائمة الرئيسية', 'nadiim' ),
        'panel'       => 'nadiim_front_page_panel',
        'priority'    => 24,
    ) );

    // ─────────────────────────────────────
    // إعدادات الشعار
    // ─────────────────────────────────────

    // رفع الشعار
    $wp_customize->add_setting( 'header_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'header_logo', array(
        'label'       => __( 'رفع الشعار', 'nadiim' ),
        'description' => __( 'اختر صورة الشعار من مكتبة الوسائط', 'nadiim' ),
        'section'     => 'nadiim_header_section',
        'mime_type'   => 'image',
    ) ) );

    $wp_customize->add_setting( 'header_logo_width', array(
        'default'           => 180,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'header_logo_width', array(
        'label'       => __( 'عرض الشعار (px)', 'nadiim' ),
        'section'     => 'nadiim_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 80,
            'max'  => 300,
            'step' => 10,
        ),
    ) );

    $wp_customize->add_setting( 'header_logo_margin', array(
        'default'           => 15,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'header_logo_margin', array(
        'label'       => __( 'المسافة حول الشعار (px)', 'nadiim' ),
        'section'     => 'nadiim_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 40,
            'step' => 5,
        ),
    ) );

    // ─────────────────────────────────────
    // ألوان الهيدر
    // ─────────────────────────────────────

    $wp_customize->add_setting( 'header_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_bg_color', array(
        'label'   => __( 'لون خلفية الهيدر', 'nadiim' ),
        'section' => 'nadiim_header_section',
    ) ) );

    $wp_customize->add_setting( 'header_text_color', array(
        'default'           => '#1c2d27',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_text_color', array(
        'label'   => __( 'لون النص الأساسي', 'nadiim' ),
        'section' => 'nadiim_header_section',
    ) ) );

    $wp_customize->add_setting( 'header_link_color', array(
        'default'           => '#1c2d27',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_link_color', array(
        'label'   => __( 'لون روابط القائمة', 'nadiim' ),
        'section' => 'nadiim_header_section',
    ) ) );

    $wp_customize->add_setting( 'header_link_hover_color', array(
        'default'           => '#339063',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_link_hover_color', array(
        'label'   => __( 'لون الروابط عند التمرير', 'nadiim' ),
        'section' => 'nadiim_header_section',
    ) ) );

    $wp_customize->add_setting( 'header_border_bottom_color', array(
        'default'           => 'rgba(0,0,0,0.06)',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'header_border_bottom_color', array(
        'label'       => __( 'لون الحد السفلي', 'nadiim' ),
        'description' => __( 'مثال: rgba(0,0,0,0.1) أو #eeeeee', 'nadiim' ),
        'section'     => 'nadiim_header_section',
        'type'        => 'text',
    ) );

    // ─────────────────────────────────────
    // السلوك
    // ─────────────────────────────────────

    $wp_customize->add_setting( 'header_sticky_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'header_sticky_enable', array(
        'label'   => __( 'تفعيل الهيدر الثابت (Sticky)', 'nadiim' ),
        'section' => 'nadiim_header_section',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'header_sticky_shadow_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'header_sticky_shadow_enable', array(
        'label'           => __( 'إظهار ظل عند التثبيت', 'nadiim' ),
        'section'         => 'nadiim_header_section',
        'type'            => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod( 'header_sticky_enable', true );
        },
    ) );

    // ─────────────────────────────────────
    // البحث
    // ─────────────────────────────────────

    $wp_customize->add_setting( 'header_search_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'header_search_enable', array(
        'label'   => __( 'تفعيل زر البحث', 'nadiim' ),
        'section' => 'nadiim_header_section',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'header_search_placeholder', array(
        'default'           => __( 'ابحث عن حوارات، إصدارات، مقالات...', 'nadiim' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_search_placeholder', array(
        'label'           => __( 'نص البحث التوضيحي', 'nadiim' ),
        'section'         => 'nadiim_header_section',
        'type'            => 'text',
        'active_callback' => function() {
            return get_theme_mod( 'header_search_enable', true );
        },
    ) );

    $wp_customize->add_setting( 'header_search_position', array(
        'default'           => 'modal',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'header_search_position', array(
        'label'           => __( 'طريقة عرض البحث', 'nadiim' ),
        'section'         => 'nadiim_header_section',
        'type'            => 'select',
        'choices'         => array(
            'modal'  => __( 'نافذة منبثقة (Modal)', 'nadiim' ),
            'inline' => __( 'داخل الهيدر', 'nadiim' ),
        ),
        'active_callback' => function() {
            return get_theme_mod( 'header_search_enable', true );
        },
    ) );

    // ─────────────────────────────────────
    // القائمة
    // ─────────────────────────────────────

    $wp_customize->add_setting( 'header_menu_alignment', array(
        'default'           => 'center',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'header_menu_alignment', array(
        'label'   => __( 'محاذاة القائمة', 'nadiim' ),
        'section' => 'nadiim_header_section',
        'type'    => 'select',
        'choices' => array(
            'right'  => __( 'يمين', 'nadiim' ),
            'center' => __( 'وسط', 'nadiim' ),
            'left'   => __( 'يسار', 'nadiim' ),
        ),
    ) );

    $wp_customize->add_setting( 'header_menu_item_spacing', array(
        'default'           => 15,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'header_menu_item_spacing', array(
        'label'       => __( 'المسافة بين عناصر القائمة (px)', 'nadiim' ),
        'section'     => 'nadiim_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 5,
            'max'  => 50,
            'step' => 5,
        ),
    ) );
}
add_action( 'customize_register', 'nadiim_header_customizer_register' );

/**
 * إضافة CSS ديناميكي للهيدر والتوب بار
 */
function nadiim_header_customizer_css() {
    ?>
    <style type="text/css" id="nadiim-header-custom-css">
        :root {
            --header-bg: <?php echo esc_attr( get_theme_mod( 'header_bg_color', '#ffffff' ) ); ?>;
            --header-color: <?php echo esc_attr( get_theme_mod( 'header_text_color', '#1c2d27' ) ); ?>;
            --header-link-color: <?php echo esc_attr( get_theme_mod( 'header_link_color', '#1c2d27' ) ); ?>;
            --header-link-hover: <?php echo esc_attr( get_theme_mod( 'header_link_hover_color', '#339063' ) ); ?>;
            --header-border: <?php echo esc_attr( get_theme_mod( 'header_border_bottom_color', 'rgba(0,0,0,0.06)' ) ); ?>;
            --header-logo-width: <?php echo absint( get_theme_mod( 'header_logo_width', 180 ) ); ?>px;
            --header-logo-margin: <?php echo absint( get_theme_mod( 'header_logo_margin', 15 ) ); ?>px;
            --header-menu-spacing: <?php echo absint( get_theme_mod( 'header_menu_item_spacing', 15 ) ); ?>px;
            --header-menu-align: <?php echo esc_attr( get_theme_mod( 'header_menu_alignment', 'center' ) ); ?>;
        }

        <?php if ( ! get_theme_mod( 'header_search_enable', true ) ) : ?>
        .header-tools .search-toggle {
            display: none !important;
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_header_customizer_css', 999 );
