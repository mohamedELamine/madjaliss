<?php
/**
 * إعدادات Hero Slider في Customizer
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تسجيل إعدادات Hero Slider
 */
function nadiim_hero_customizer_register( $wp_customize ) {

    // ============================================
    // قسم Hero Slider
    // ============================================

    $wp_customize->add_section( 'nadiim_hero_slider', array(
        'title'       => __( 'Hero Slider - الصفحة الرئيسية', 'nadiim' ),
        'description' => __( 'إعدادات السلايدر الرئيسي في الصفحة الرئيسية', 'nadiim' ),
        'priority'    => 40,
    ) );

    // ─────────────────────────────────────
    // إعدادات عامة
    // ─────────────────────────────────────

    // تفعيل Hero Slider
    $wp_customize->add_setting( 'hero_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'hero_enable', array(
        'label'       => __( 'تفعيل Hero Slider', 'nadiim' ),
        'description' => __( 'عرض/إخفاء السلايدر في الصفحة الرئيسية', 'nadiim' ),
        'section'     => 'nadiim_hero_slider',
        'type'        => 'checkbox',
    ) );

    // مصدر المحتوى
    $wp_customize->add_setting( 'hero_source', array(
        'default'           => 'latest_posts',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'hero_source', array(
        'label'   => __( 'مصدر المحتوى', 'nadiim' ),
        'section' => 'nadiim_hero_slider',
        'type'    => 'select',
        'choices' => array(
            'latest_posts' => __( 'أحدث المقالات', 'nadiim' ),
            'featured_tag' => __( 'مقالات مميزة (بتاج)', 'nadiim' ),
            'howarat'      => __( 'الحوارات', 'nadiim' ),
            'manual'       => __( 'يدوي (JSON)', 'nadiim' ),
        ),
    ) );

    // تاج المحتوى المميز
    $wp_customize->add_setting( 'hero_featured_tag', array(
        'default'           => 'featured',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'hero_featured_tag', array(
        'label'           => __( 'تاج المحتوى المميز', 'nadiim' ),
        'description'     => __( 'اسم التاج للمقالات المميزة (مثل: featured)', 'nadiim' ),
        'section'         => 'nadiim_hero_slider',
        'type'            => 'text',
        'active_callback' => function() {
            return get_theme_mod( 'hero_source', 'latest_posts' ) === 'featured_tag';
        },
    ) );

    // عدد الشرائح
    $wp_customize->add_setting( 'hero_count', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'hero_count', array(
        'label'       => __( 'عدد الشرائح', 'nadiim' ),
        'section'     => 'nadiim_hero_slider',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 10,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod( 'hero_source', 'latest_posts' ) !== 'manual';
        },
    ) );

    // ─────────────────────────────────────
    // إعدادات السلايدر
    // ─────────────────────────────────────

    // تفعيل التشغيل التلقائي
    $wp_customize->add_setting( 'hero_autoplay', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'hero_autoplay', array(
        'label'   => __( 'تفعيل التشغيل التلقائي', 'nadiim' ),
        'section' => 'nadiim_hero_slider',
        'type'    => 'checkbox',
    ) );

    // مدة التأخير (ms)
    $wp_customize->add_setting( 'hero_delay', array(
        'default'           => 6000,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'hero_delay', array(
        'label'       => __( 'مدة التأخير (بالميلي ثانية)', 'nadiim' ),
        'description' => __( '1000 = 1 ثانية', 'nadiim' ),
        'section'     => 'nadiim_hero_slider',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 2000,
            'max'  => 15000,
            'step' => 1000,
        ),
    ) );

    // ─────────────────────────────────────
    // إعدادات التصميم
    // ─────────────────────────────────────

    // زاوية حواف البطاقة
    $wp_customize->add_setting( 'hero_card_radius', array(
        'default'           => 20,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'hero_card_radius', array(
        'label'       => __( 'زاوية حواف البطاقة (px)', 'nadiim' ),
        'section'     => 'nadiim_hero_slider',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 40,
            'step' => 2,
        ),
    ) );

    // شفافية الـ Overlay الافتراضية
    $wp_customize->add_setting( 'hero_overlay_default_opacity', array(
        'default'           => 0.35,
        'sanitize_callback' => 'nadiim_sanitize_float',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'hero_overlay_default_opacity', array(
        'label'       => __( 'شفافية الخلفية الافتراضية', 'nadiim' ),
        'description' => __( '0 = شفاف تماماً، 1 = معتم تماماً', 'nadiim' ),
        'section'     => 'nadiim_hero_slider',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.05,
        ),
    ) );

    // نظام الألوان للنص
    $wp_customize->add_setting( 'hero_text_color_scheme', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'hero_text_color_scheme', array(
        'label'   => __( 'نظام ألوان النص', 'nadiim' ),
        'section' => 'nadiim_hero_slider',
        'type'    => 'select',
        'choices' => array(
            'auto'  => __( 'تلقائي', 'nadiim' ),
            'light' => __( 'فاتح (للخلفيات الداكنة)', 'nadiim' ),
            'dark'  => __( 'داكن (للخلفيات الفاتحة)', 'nadiim' ),
        ),
    ) );

    // ─────────────────────────────────────
    // إعدادات المحتوى اليدوي (Manual Slides)
    // ─────────────────────────────────────

    // JSON للشرائح اليدوية
    $wp_customize->add_setting( 'hero_slides_json', array(
        'default'           => nadiim_get_default_hero_json(),
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'hero_slides_json', array(
        'label'           => __( 'شرائح JSON', 'nadiim' ),
        'description'     => __( 'الصق JSON للشرائح اليدوية (راجع التوثيق)', 'nadiim' ),
        'section'         => 'nadiim_hero_slider',
        'type'            => 'textarea',
        'active_callback' => function() {
            return get_theme_mod( 'hero_source', 'latest_posts' ) === 'manual';
        },
    ) );

    // زر لعرض مثال JSON
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'hero_json_help', array(
        'label'           => __( 'مثال JSON', 'nadiim' ),
        'description'     => nadiim_get_hero_json_example(),
        'section'         => 'nadiim_hero_slider',
        'type'            => 'hidden',
        'active_callback' => function() {
            return get_theme_mod( 'hero_source', 'latest_posts' ) === 'manual';
        },
    ) ) );

}
add_action( 'customize_register', 'nadiim_hero_customizer_register' );

/**
 * Sanitize float value
 */
function nadiim_sanitize_float( $value ) {
    return floatval( $value );
}

/**
 * الحصول على JSON افتراضي للشرائح
 */
function nadiim_get_default_hero_json() {
    $demo_file = get_template_directory() . '/demo/hero-demo.json';
    if ( file_exists( $demo_file ) ) {
        return file_get_contents( $demo_file );
    }
    return '[]';
}

/**
 * مثال JSON للمساعدة
 */
function nadiim_get_hero_json_example() {
    return '<pre style="background:#f5f5f5;padding:10px;font-size:11px;direction:ltr;text-align:left;">[
  {
    "title": "عنوان الشريحة",
    "excerpt": "مقتطف قصير عن المحتوى...",
    "image": "https://example.com/image.jpg",
    "bg_enable": true,
    "overlay_opacity": 0.4,
    "cta_text": "اقرأ المزيد",
    "cta_link": "#",
    "cta_target": "_self",
    "has_audio": false,
    "read_time": "5 دقائق",
    "author_name": "أحمد محمد"
  }
]</pre>';
}

/**
 * CSS مخصص للـ Hero Slider
 */
function nadiim_hero_customizer_css() {
    if ( ! is_front_page() ) {
        return;
    }

    $card_radius  = get_theme_mod( 'hero_card_radius', 20 );
    $overlay_opacity = get_theme_mod( 'hero_overlay_default_opacity', 0.35 );
    $text_scheme  = get_theme_mod( 'hero_text_color_scheme', 'auto' );

    ?>
    <style type="text/css" id="nadiim-hero-custom-css">
        :root {
            --hero-card-radius: <?php echo absint( $card_radius ); ?>px;
            --hero-overlay-opacity: <?php echo floatval( $overlay_opacity ); ?>;
        }

        <?php if ( ! get_theme_mod( 'hero_enable', true ) ) : ?>
        .hero-slider-section {
            display: none !important;
        }
        <?php endif; ?>

        <?php if ( $text_scheme === 'light' ) : ?>
        .hero-slide-card {
            color: #ffffff;
        }
        .hero-slide-card .slide-title {
            color: #ffffff;
        }
        .hero-slide-card .slide-excerpt,
        .hero-slide-card .slide-meta {
            color: rgba(255, 255, 255, 0.9);
        }
        <?php elseif ( $text_scheme === 'dark' ) : ?>
        .hero-slide-card {
            color: #1c2d27;
        }
        .hero-slide-card .slide-title {
            color: #1c2d27;
        }
        .hero-slide-card .slide-excerpt,
        .hero-slide-card .slide-meta {
            color: rgba(28, 45, 39, 0.8);
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_hero_customizer_css', 20 );
