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
    // قسم الشريط العلوي (Top Bar)
    // ============================================

    $wp_customize->add_section( 'nadiim_topbar_section', array(
        'title'       => __( 'الشريط العلوي (Top Bar)', 'nadiim' ),
        'description' => __( 'إعدادات الشريط العلوي الذي يظهر في أعلى الصفحة', 'nadiim' ),
        'panel'       => 'nadiim_front_page_panel',
        'priority'    => 22,
    ) );

    // ✔ تفعيل/إلغاء الشريط العلوي
    $wp_customize->add_setting( 'topbar_enable', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'topbar_enable', array(
        'label'    => __( 'تفعيل الشريط العلوي', 'nadiim' ),
        'section'  => 'nadiim_topbar_section',
        'type'     => 'checkbox',
    ) );

    // ✔ نوع المحتوى (ثابت أو ديناميكي)
    $wp_customize->add_setting( 'topbar_dynamic_enable', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'topbar_dynamic_enable', array(
        'label'       => __( 'تفعيل المحتوى الديناميكي', 'nadiim' ),
        'description' => __( 'عرض آخر المقالات/الحوارات بدلاً من النص الثابت', 'nadiim' ),
        'section'     => 'nadiim_topbar_section',
        'type'        => 'checkbox',
    ) );

    // ✔ نص ثابت (في حالة المحتوى الثابت)
    $wp_customize->add_setting( 'topbar_text', array(
        'default'           => __( 'مرحباً بكم في منصة نديم - فضاء للحوارات الرصينة', 'nadiim' ),
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'topbar_text', array(
        'label'           => __( 'النص الثابت', 'nadiim' ),
        'section'         => 'nadiim_topbar_section',
        'type'            => 'textarea',
        'active_callback' => function() {
            return ! get_theme_mod( 'topbar_dynamic_enable', false );
        },
    ) );

    // ✔ نوع المنشور الديناميكي
    $wp_customize->add_setting( 'topbar_dynamic_post_type', array(
        'default'           => 'post',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'topbar_dynamic_post_type', array(
        'label'           => __( 'نوع المحتوى الديناميكي', 'nadiim' ),
        'section'         => 'nadiim_topbar_section',
        'type'            => 'select',
        'choices'         => array(
            'post'    => __( 'مقالات', 'nadiim' ),
            'howarat' => __( 'حوارات', 'nadiim' ),
            'esdar'   => __( 'إصدارات', 'nadiim' ),
        ),
        'active_callback' => function() {
            return get_theme_mod( 'topbar_dynamic_enable', false );
        },
    ) );

    // ✔ تاج الفلترة
    $wp_customize->add_setting( 'topbar_dynamic_tag', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'topbar_dynamic_tag', array(
        'label'           => __( 'تاج الفلترة (اختياري)', 'nadiim' ),
        'description'     => __( 'اكتب اسم التاج لفلترة المحتوى', 'nadiim' ),
        'section'         => 'nadiim_topbar_section',
        'type'            => 'text',
        'active_callback' => function() {
            return get_theme_mod( 'topbar_dynamic_enable', false );
        },
    ) );

    // ✔ عدد العناصر الديناميكية
    $wp_customize->add_setting( 'topbar_dynamic_limit', array(
        'default'           => 1,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'topbar_dynamic_limit', array(
        'label'           => __( 'عدد العناصر المعروضة', 'nadiim' ),
        'section'         => 'nadiim_topbar_section',
        'type'            => 'number',
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod( 'topbar_dynamic_enable', false );
        },
    ) );

    // ✔ لون الخلفية
    $wp_customize->add_setting( 'topbar_bg_color', array(
        'default'           => '#1c2d27',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_bg_color', array(
        'label'   => __( 'لون الخلفية', 'nadiim' ),
        'section' => 'nadiim_topbar_section',
    ) ) );

    // ✔ لون النص
    $wp_customize->add_setting( 'topbar_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_text_color', array(
        'label'   => __( 'لون النص', 'nadiim' ),
        'section' => 'nadiim_topbar_section',
    ) ) );

    // ✔ حجم الخط
    $wp_customize->add_setting( 'topbar_font_size', array(
        'default'           => 14,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'topbar_font_size', array(
        'label'       => __( 'حجم الخط (px)', 'nadiim' ),
        'section'     => 'nadiim_topbar_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 18,
            'step' => 1,
        ),
    ) );

    // ✔ اختيار الأيقونة
    $wp_customize->add_setting( 'topbar_icon', array(
        'default'           => 'megaphone',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'topbar_icon', array(
        'label'   => __( 'الأيقونة', 'nadiim' ),
        'section' => 'nadiim_topbar_section',
        'type'    => 'select',
        'choices' => array(
            'megaphone'   => __( '📢 مكبر الصوت', 'nadiim' ),
            'bell'        => __( '🔔 جرس', 'nadiim' ),
            'info'        => __( 'ℹ️ معلومات', 'nadiim' ),
            'star'        => __( '⭐ نجمة', 'nadiim' ),
            'calendar'    => __( '📅 تقويم', 'nadiim' ),
            'none'        => __( 'بدون أيقونة', 'nadiim' ),
        ),
    ) );

    // ✔ محاذاة النص
    $wp_customize->add_setting( 'topbar_text_align', array(
        'default'           => 'center',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'topbar_text_align', array(
        'label'   => __( 'محاذاة النص', 'nadiim' ),
        'section' => 'nadiim_topbar_section',
        'type'    => 'select',
        'choices' => array(
            'right'  => __( 'يمين', 'nadiim' ),
            'center' => __( 'وسط', 'nadiim' ),
            'left'   => __( 'يسار', 'nadiim' ),
        ),
    ) );

    // ✔ تفعيل حركة المرور (Marquee)
    $wp_customize->add_setting( 'topbar_marquee_enable', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'topbar_marquee_enable', array(
        'label'   => __( 'تفعيل حركة التمرير', 'nadiim' ),
        'section' => 'nadiim_topbar_section',
        'type'    => 'checkbox',
    ) );

    // ✔ سرعة التمرير
    $wp_customize->add_setting( 'topbar_marquee_speed', array(
        'default'           => 50,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'topbar_marquee_speed', array(
        'label'           => __( 'سرعة التمرير', 'nadiim' ),
        'description'     => __( 'قيمة أعلى = حركة أبطأ', 'nadiim' ),
        'section'         => 'nadiim_topbar_section',
        'type'            => 'range',
        'input_attrs'     => array(
            'min'  => 20,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => function() {
            return get_theme_mod( 'topbar_marquee_enable', false );
        },
    ) );

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
            --topbar-bg: <?php echo esc_attr( get_theme_mod( 'topbar_bg_color', '#1c2d27' ) ); ?>;
            --topbar-color: <?php echo esc_attr( get_theme_mod( 'topbar_text_color', '#ffffff' ) ); ?>;
            --topbar-font-size: <?php echo absint( get_theme_mod( 'topbar_font_size', 14 ) ); ?>px;
            --topbar-align: <?php echo esc_attr( get_theme_mod( 'topbar_text_align', 'center' ) ); ?>;

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

        <?php if ( ! get_theme_mod( 'topbar_enable', true ) ) : ?>
        .site-topbar {
            display: none !important;
        }
        <?php endif; ?>

        <?php if ( ! get_theme_mod( 'header_search_enable', true ) ) : ?>
        .header-tools .search-toggle {
            display: none !important;
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_header_customizer_css', 999 );

/**
 * دالة مساعدة للحصول على محتوى التوب بار الديناميكي
 */
function nadiim_get_topbar_dynamic_content() {
    if ( ! get_theme_mod( 'topbar_dynamic_enable', false ) ) {
        return '';
    }

    $post_type = get_theme_mod( 'topbar_dynamic_post_type', 'post' );
    $tag       = get_theme_mod( 'topbar_dynamic_tag', '' );
    $limit     = get_theme_mod( 'topbar_dynamic_limit', 1 );

    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // إضافة فلتر التاج إن وجد
    if ( ! empty( $tag ) ) {
        $args['tag'] = $tag;
    }

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return __( 'لا توجد عناصر للعرض', 'nadiim' );
    }

    $output = '';
    while ( $query->have_posts() ) {
        $query->the_post();
        $output .= '<a href="' . esc_url( get_permalink() ) . '" class="topbar-dynamic-item">';
        $output .= esc_html( get_the_title() );
        $output .= '</a>';
        if ( $limit > 1 && $query->current_post < $query->post_count - 1 ) {
            $output .= ' <span class="topbar-separator">•</span> ';
        }
    }
    wp_reset_postdata();

    return $output;
}

/**
 * دالة مساعدة للحصول على أيقونة التوب بار
 */
function nadiim_get_topbar_icon() {
    $icon = get_theme_mod( 'topbar_icon', 'megaphone' );

    $icons = array(
        'megaphone' => '📢',
        'bell'      => '🔔',
        'info'      => 'ℹ️',
        'star'      => '⭐',
        'calendar'  => '📅',
        'none'      => '',
    );

    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}
