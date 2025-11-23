<?php
/**
 * ملف الدوال الرئيسية لقالب مجالس
 *
 * يحمل هذا الملف جميع الإعدادات والدوال الأساسية للقالب
 *
 * @package Majalis
 * @author محمد الأمين
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // منع الوصول المباشر
}

// تعريف الثوابت الأساسية
define( 'NADIIM_VERSION', '1.0.0' );
define( 'NADIIM_THEME_DIR', get_template_directory() );
define( 'NADIIM_THEME_URI', get_template_directory_uri() );

/**
 * إعداد القالب الأساسي
 */
function nadiim_setup() {
    // دعم اللغات والترجمة
    load_theme_textdomain( 'nadiim', NADIIM_THEME_DIR . '/languages' );

    // دعم عنوان الصفحة التلقائي
    add_theme_support( 'title-tag' );

    // دعم الصور المميزة
    add_theme_support( 'post-thumbnails' );

    // أحجام الصور المخصصة
    add_image_size( 'nadiim-featured', 800, 500, true );
    add_image_size( 'nadiim-card', 400, 300, true );
    add_image_size( 'nadiim-thumbnail', 200, 200, true );
    add_image_size( 'nadiim-hero', 1920, 800, true );

    // دعم HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // دعم الخلاصات التلقائية
    add_theme_support( 'automatic-feed-links' );

    // دعم تحديث القالب
    add_theme_support( 'customize-selective-refresh-widgets' );

    // دعم شعار القالب المخصص
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // دعم ألوان المحرر
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => __( 'اللون الأساسي', 'nadiim' ),
            'slug'  => 'primary',
            'color' => '#339063',
        ),
        array(
            'name'  => __( 'اللون الأساسي الفاتح', 'nadiim' ),
            'slug'  => 'primary-light',
            'color' => '#4db080',
        ),
        array(
            'name'  => __( 'النص الأساسي', 'nadiim' ),
            'slug'  => 'text-primary',
            'color' => '#2c3e50',
        ),
    ) );

    // تسجيل مواقع القوائم
    register_nav_menus( array(
        'primary'   => __( 'القائمة الرئيسية', 'nadiim' ),
        'footer'    => __( 'قائمة الفوتر', 'nadiim' ),
        'social'    => __( 'قائمة التواصل الاجتماعي', 'nadiim' ),
    ) );
}
add_action( 'after_setup_theme', 'nadiim_setup' );

/**
 * تعيين عرض المحتوى
 */
function nadiim_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'nadiim_content_width', 1200 );
}
add_action( 'after_setup_theme', 'nadiim_content_width', 0 );

/**
 * تسجيل مناطق الودجات
 */
function nadiim_widgets_init() {
    // منطقة الشريط الجانبي
    register_sidebar( array(
        'name'          => __( 'الشريط الجانبي', 'nadiim' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'يظهر في صفحات المدونة والمقالات', 'nadiim' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // مناطق الفوتر
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( __( 'الفوتر - العمود %d', 'nadiim' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => sprintf( __( 'العمود %d في منطقة الفوتر', 'nadiim' ), $i ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );
    }

    // منطقة الصفحة الرئيسية - Hero
    register_sidebar( array(
        'name'          => __( 'الصفحة الرئيسية - Hero', 'nadiim' ),
        'id'            => 'home-hero',
        'description'   => __( 'قسم Hero في الصفحة الرئيسية', 'nadiim' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    // منطقة الصفحة الرئيسية - الحوارات
    register_sidebar( array(
        'name'          => __( 'الصفحة الرئيسية - الحوارات', 'nadiim' ),
        'id'            => 'home-dialogues',
        'description'   => __( 'قسم الحوارات في الصفحة الرئيسية', 'nadiim' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ) );

    // منطقة الصفحة الرئيسية - الإصدارات
    register_sidebar( array(
        'name'          => __( 'الصفحة الرئيسية - الإصدارات', 'nadiim' ),
        'id'            => 'home-releases',
        'description'   => __( 'قسم الإصدارات في الصفحة الرئيسية', 'nadiim' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'nadiim_widgets_init' );

/**
 * تحميل الأصول (CSS & JS)
 */
function nadiim_enqueue_scripts() {
    // تحميل خط Cairo من Google Fonts
    wp_enqueue_style(
        'nadiim-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap',
        array(),
        null
    );

    // تحميل ملف CSS الرئيسي
    wp_enqueue_style(
        'nadiim-style',
        get_stylesheet_uri(),
        array(),
        NADIIM_VERSION
    );

    // تحميل ملف CSS الإضافي
    wp_enqueue_style(
        'nadiim-main',
        NADIIM_THEME_URI . '/assets/css/main.css',
        array( 'nadiim-style' ),
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
 * تحميل ملفات CSS و JS للمحرر
 */
function nadiim_editor_styles() {
    add_editor_style( array(
        'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap',
        'assets/css/editor-style.css',
    ) );
}
add_action( 'admin_init', 'nadiim_editor_styles' );

/**
 * تضمين الملفات المطلوبة
 */

// تضمين ملف Custom Post Types
require_once NADIIM_THEME_DIR . '/inc/custom-post-types.php';

// تضمين ملف CPT الحوارات
require_once NADIIM_THEME_DIR . '/inc/cpt-howarat.php';

// تضمين ملف Meta Boxes
require_once NADIIM_THEME_DIR . '/inc/meta-boxes.php';

// تضمين ميتا بوكس الحوارات
require_once NADIIM_THEME_DIR . '/inc/howarat-meta-box.php';

/**
 * دالة مساعدة لاستخراج قيمة string آمنة من مصفوفة المشارك
 *
 * تمنع خطأ "Array to string conversion" بالتحقق من نوع البيانات
 *
 * @param array  $participant مصفوفة بيانات المشارك
 * @param string $key مفتاح الحقل المطلوب
 * @param string $default القيمة الافتراضية
 * @return string
 */
function nadiim_get_participant_field( $participant, $key, $default = '' ) {
	if ( ! is_array( $participant ) || ! isset( $participant[ $key ] ) ) {
		return $default;
	}

	$value = $participant[ $key ];

	// إذا كانت القيمة string، نعيدها مباشرة
	if ( is_string( $value ) ) {
		return $value;
	}

	// إذا كانت array، نحولها إلى string بدمج العناصر
	if ( is_array( $value ) ) {
		return implode( ' ', array_filter( $value, 'is_string' ) );
	}

	// إذا كانت رقم، نحولها إلى string
	if ( is_numeric( $value ) ) {
		return (string) $value;
	}

	// في أي حالة أخرى، نعيد القيمة الافتراضية
	return $default;
}

// تضمين ملف Customizer
require_once NADIIM_THEME_DIR . '/inc/customizer.php';

// تضمين ملف Customizer للهيدر والشريط العلوي
require_once NADIIM_THEME_DIR . '/inc/customizer-header.php';

// تضمين ملف Customizer للـ Hero Slider
require_once NADIIM_THEME_DIR . '/inc/customizer-hero.php';

// تضمين ملف Customizer لقسم من نحن المصغر
require_once NADIIM_THEME_DIR . '/inc/customizer-about-mini.php';

// تضمين ملف Customizer للحوارات المميزة
require_once NADIIM_THEME_DIR . '/inc/customizer-featured-howarat.php';

// تضمين ملف Customizer لقسم المقالات
require_once NADIIM_THEME_DIR . '/inc/customizer-articles.php';

// تضمين ملف Customizer لقسم الفعاليات
require_once NADIIM_THEME_DIR . '/inc/customizer-events.php';

// تضمين ملف نظام التسجيل في الفعاليات
require_once NADIIM_THEME_DIR . '/inc/event-registration.php';

// تضمين ملف Customizer لإيميلات الفعاليات
require_once NADIIM_THEME_DIR . '/inc/customizer-event-email.php';

// تضمين ملف Customizer للصفحة الرئيسية
require_once NADIIM_THEME_DIR . '/inc/front-page-customizer.php';

// تضمين ملف Template Functions
require_once NADIIM_THEME_DIR . '/inc/template-functions.php';

// تضمين ملف Template Tags
require_once NADIIM_THEME_DIR . '/inc/template-tags.php';

// تضمين ملف Home Enqueue (Hero Slider assets)
require_once NADIIM_THEME_DIR . '/inc/home-enqueue.php';

// تضمين ملف Demo Content (معطّل للإنتاج)
// require_once NADIIM_THEME_DIR . '/inc/demo-content.php';

/**
 * دالة مساعدة للحصول على مقتطف مخصص
 */
function nadiim_get_excerpt( $length = 30, $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $excerpt = get_the_excerpt( $post_id );

    if ( empty( $excerpt ) ) {
        $excerpt = get_the_content( null, false, $post_id );
        $excerpt = strip_shortcodes( $excerpt );
        $excerpt = wp_strip_all_tags( $excerpt );
    }

    if ( str_word_count( $excerpt ) > $length ) {
        $words = str_word_count( $excerpt, 2, 'ءآأؤإئابةتثجحخدذرزسشصضطظعغفقكلمنهوىيٱٲٳٴٵٶٷٸٹٺٻټٽپٿڀځڂڃڄڅچڇڈډڊڋڌڍڎڏڐڑڒړڔڕږڗژڙښڛڜڝڞڟڠڡڢڣڤڥڦڧڨکڪګڬڭڮگڰڱڲڳڴڵڶڷڸڹںڻڼڽھڿۀہۂۃۄۅۆۇۈۉۊۋیۍێۏېۑےۓ۔ەۖۗۘۙۚۛۜ۝۞ۣ۟۠ۡۢۤۥۦۧۨ۩۪ۭ۫۬ۮۯ۰۱۲۳۴۵۶۷۸۹ۺۻۼ۽۾ۿ' );
        $words = array_slice( $words, 0, $length, true );
        end( $words );
        $position = key( $words ) + strlen( current( $words ) );
        $excerpt = substr( $excerpt, 0, $position ) . '...';
    }

    return $excerpt;
}

/**
 * دالة مساعدة لعرض أيقونة SVG
 */
function nadiim_get_icon( $icon_name ) {
    $icons = array(
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'book' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
        'video' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>',
        'audio' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>',
        'document' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
        'download' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>',
    );

    return isset( $icons[ $icon_name ] ) ? $icons[ $icon_name ] : '';
}

/**
 * دالة مساعدة لعرض قائمة التصنيفات
 */
function nadiim_get_post_terms( $post_id, $taxonomy, $separator = ', ' ) {
    $terms = get_the_terms( $post_id, $taxonomy );

    if ( ! $terms || is_wp_error( $terms ) ) {
        return '';
    }

    $term_links = array();
    foreach ( $terms as $term ) {
        $term_links[] = sprintf(
            '<a href="%s" class="badge badge-outline">%s</a>',
            esc_url( get_term_link( $term ) ),
            esc_html( $term->name )
        );
    }

    return implode( $separator, $term_links );
}

/**
 * إضافة دعم Lazy Loading للصور
 */
function nadiim_add_lazy_loading( $content ) {
    if ( is_admin() ) {
        return $content;
    }

    $content = str_replace( '<img ', '<img loading="lazy" ', $content );
    return $content;
}
add_filter( 'the_content', 'nadiim_add_lazy_loading' );
add_filter( 'post_thumbnail_html', 'nadiim_add_lazy_loading' );

// تم نقل معالج النشرة البريدية إلى inc/newsletter-handler.php

/**
 * ==========================================
 * نظام الإصدارات (Releases System)
 * ==========================================
 */

// تضمين ملف CPT الإصدارات
require_once NADIIM_THEME_DIR . '/inc/cpt-esdar.php';

// تضمين ميتا بوكس الإصدارات
require_once NADIIM_THEME_DIR . '/inc/meta-esdar.php';

// تضمين دوال مساعدة للإصدارات
require_once NADIIM_THEME_DIR . '/inc/helpers.php';

// تضمين إعدادات Customizer للإصدارات
require_once NADIIM_THEME_DIR . '/inc/customizer-esdar.php';

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
 * تطبيق إعدادات Customizer على query الإصدارات
 */
function nadiim_esdar_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'esdar' ) ) {
        // عدد الإصدارات في الصفحة
        $per_page = get_theme_mod( 'esdar_archive_per_page', 12 );
        $query->set( 'posts_per_page', $per_page );

        // ترتيب الإصدارات
        $orderby = get_theme_mod( 'esdar_archive_orderby', 'date' );
        $order = get_theme_mod( 'esdar_archive_order', 'DESC' );

        $query->set( 'orderby', $orderby );
        $query->set( 'order', $order );
    }
}
add_action( 'pre_get_posts', 'nadiim_esdar_archive_query' );

/**
 * ==========================================
 * نظام نوادي القراءة (Reading Clubs System)
 * ==========================================
 */

// تضمين ملف CPT نوادي القراءة
require_once NADIIM_THEME_DIR . '/inc/cpt-reading-clubs.php';

// تضمين ميتا بوكس نوادي القراءة
require_once NADIIM_THEME_DIR . '/inc/meta-reading-clubs.php';

// تضمين إعدادات Customizer لقسم النوادي
require_once NADIIM_THEME_DIR . '/inc/customizer-clubs.php';

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
 * تطبيق إعدادات query على أرشيف النوادي
 */
function nadiim_reading_clubs_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'reading_clubs' ) ) {
        // فلترة النوادي العامة فقط
        $query->set( 'posts_per_page', 12 );
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'nadiim_reading_clubs_archive_query' );

/**
 * ==========================================
 * نظام المقالات المحسّن (Enhanced Articles System)
 * ==========================================
 */

// تضمين نظام Meta Box للمقالات
require_once NADIIM_THEME_DIR . '/inc/article-meta.php';

// تضمين نظام الفلترة للأرشيف
require_once NADIIM_THEME_DIR . '/inc/articles-filters.php';

// تضمين Schema.org للمقالات
require_once NADIIM_THEME_DIR . '/inc/articles-schema.php';

/**
 * تحميل أصول المقالات (CSS & JS)
 */
function nadiim_articles_enqueue_assets() {
    // تحميل CSS للمقالات
    wp_enqueue_style(
        'nadiim-articles',
        NADIIM_THEME_URI . '/assets/css/articles.css',
        array( 'nadiim-main' ),
        NADIIM_VERSION
    );

    // تحميل JS للواجهة الأمامية
    if ( is_singular( 'post' ) || is_home() || is_archive() ) {
        wp_enqueue_script(
            'nadiim-articles-frontend',
            NADIIM_THEME_URI . '/assets/js/articles-frontend.js',
            array( 'jquery' ),
            NADIIM_VERSION,
            true
        );
    }

    // تحميل JS للـ Admin (في صفحة تحرير المقال)
    if ( is_admin() ) {
        global $post_type;
        if ( $post_type === 'post' ) {
            wp_enqueue_media(); // تفعيل Media Uploader

            wp_enqueue_script(
                'nadiim-articles-admin',
                NADIIM_THEME_URI . '/assets/js/articles-admin.js',
                array( 'jquery', 'wp-util' ),
                NADIIM_VERSION,
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_articles_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'nadiim_articles_enqueue_assets' );

/**
 * ==========================================
 * نظام صفحة الكاتب (Author Profile System)
 * ==========================================
 */

// تضمين ملف إدارة بيانات الكاتب
require_once NADIIM_THEME_DIR . '/inc/author-meta.php';

/**
 * تحميل أصول صفحة الكاتب (CSS & JS)
 */
function nadiim_author_page_enqueue_assets() {
    // تحميل CSS و JS فقط في صفحة الكاتب
    if ( is_author() ) {
        // تحميل CSS لصفحة الكاتب
        wp_enqueue_style(
            'nadiim-author',
            NADIIM_THEME_URI . '/assets/css/author.css',
            array( 'nadiim-main' ),
            NADIIM_VERSION
        );

        // تحميل JavaScript للواجهة الأمامية
        wp_enqueue_script(
            'nadiim-author-frontend',
            NADIIM_THEME_URI . '/assets/js/author-frontend.js',
            array(),
            NADIIM_VERSION,
            true
        );

        // تمرير متغيرات لـ JavaScript
        wp_localize_script( 'nadiim-author-frontend', 'nadiimAuthorVars', array(
            'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
            'contactNonce' => wp_create_nonce( 'nadiim-author-contact' ),
            'debug'        => WP_DEBUG ? '1' : '0',
            'strings'      => array(
                'loading'      => __( 'جارٍ التحميل...', 'nadiim' ),
                'sending'      => __( 'جارٍ الإرسال...', 'nadiim' ),
                'success'      => __( 'تم الإرسال بنجاح!', 'nadiim' ),
                'error'        => __( 'حدث خطأ، يُرجى المحاولة مرة أخرى', 'nadiim' ),
            ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_author_page_enqueue_assets' );

/**
 * ==========================================
 * نظام صفحة اتصل بنا (Contact Page System)
 * ==========================================
 */

// تضمين ملفات صفحة الاتصال
require_once NADIIM_THEME_DIR . '/inc/cpt-inquiries.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-contact.php';
require_once NADIIM_THEME_DIR . '/inc/contact-handler.php';

// تضمين ملفات صفحة من نحن
require_once NADIIM_THEME_DIR . '/inc/customizer-about.php';
require_once NADIIM_THEME_DIR . '/inc/user-about-meta.php';

// تضمين معالج النشرة البريدية
require_once NADIIM_THEME_DIR . '/inc/newsletter-handler.php';

// تضمين ملفات SEO Schema
require_once NADIIM_THEME_DIR . '/inc/howarat-schema.php';
require_once NADIIM_THEME_DIR . '/inc/esdar-schema.php';

/**
 * تحميل أصول صفحة الاتصال (CSS & JS)
 */
function nadiim_contact_page_enqueue_assets() {
	// في Customizer، نحمل الأصول دائماً
	if ( is_customize_preview() ) {
		wp_enqueue_style(
			'nadiim-contact',
			NADIIM_THEME_URI . '/assets/css/contact.css',
			array( 'nadiim-main' ),
			NADIIM_VERSION
		);

		wp_enqueue_script(
			'nadiim-contact-frontend',
			NADIIM_THEME_URI . '/assets/js/contact-frontend.js',
			array(),
			NADIIM_VERSION,
			true
		);
		return;
	}

	// في الصفحات العادية، نتحقق من القالب أو Shortcode
	$load_assets = false;

	// التحقق من قالب الصفحة
	if ( is_page_template( 'page-contact.php' ) ) {
		$load_assets = true;
	}

	// التحقق من Shortcode
	if ( ! $load_assets && get_the_ID() ) {
		$post_content = get_post_field( 'post_content', get_the_ID() );
		if ( $post_content && has_shortcode( $post_content, 'nadiim_contact_form' ) ) {
			$load_assets = true;
		}
	}

	if ( $load_assets ) {
		// تحميل CSS
		wp_enqueue_style(
			'nadiim-contact',
			NADIIM_THEME_URI . '/assets/css/contact.css',
			array( 'nadiim-main' ),
			NADIIM_VERSION
		);

		// تحميل JavaScript للواجهة الأمامية
		wp_enqueue_script(
			'nadiim-contact-frontend',
			NADIIM_THEME_URI . '/assets/js/contact-frontend.js',
			array(),
			NADIIM_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nadiim_contact_page_enqueue_assets' );

/**
 * تحميل أصول صفحة من نحن (CSS)
 */
function nadiim_about_page_enqueue_assets() {
	// تحميل فقط في صفحة من نحن أو في Customizer
	if ( is_page_template( 'page-templates/about.php' ) || is_customize_preview() ) {
		wp_enqueue_style(
			'nadiim-about',
			NADIIM_THEME_URI . '/assets/css/about.css',
			array( 'nadiim-main' ),
			NADIIM_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nadiim_about_page_enqueue_assets' );

/**
 * تحميل JavaScript للوحة الإدارة (صفحة الاستفسارات)
 */
function nadiim_contact_admin_enqueue_assets( $hook ) {
	global $post_type;

	// تحميل فقط في صفحات الاستفسارات
	if ( $post_type === 'inquiries' ) {
		wp_enqueue_script(
			'nadiim-contact-admin',
			NADIIM_THEME_URI . '/assets/js/contact-admin.js',
			array( 'jquery' ),
			NADIIM_VERSION,
			true
		);
	}
}
add_action( 'admin_enqueue_scripts', 'nadiim_contact_admin_enqueue_assets' );

/**
 * AJAX Handler لتحديث حالة الاستفسار
 */
function nadiim_update_inquiry_status_ajax() {
	check_ajax_referer( 'nadiim-nonce', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( array( 'message' => 'Unauthorized' ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	$status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';

	if ( ! $post_id || ! in_array( $status, array( 'new', 'seen', 'responded' ) ) ) {
		wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
	}

	update_post_meta( $post_id, '_inquiry_status', $status );

	wp_send_json_success( array( 'message' => 'Status updated', 'status' => $status ) );
}
add_action( 'wp_ajax_nadiim_update_inquiry_status', 'nadiim_update_inquiry_status_ajax' );

/**
 * دالة مساعدة للحصول على أيقونات مواقع التواصل الاجتماعي
 */
function nadiim_get_social_icon( $platform ) {
	$icons = array(
		'facebook' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		'twitter' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
		'instagram' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
		'linkedin' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
		'youtube' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
		'whatsapp' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
	);

	return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}
