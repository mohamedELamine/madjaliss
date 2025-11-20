<?php
/**
 * ملف الدوال الرئيسية لقالب نديم
 *
 * يحمل هذا الملف جميع الإعدادات والدوال الأساسية للقالب
 *
 * @package Nadiim
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

    // تحميل JavaScript الرئيسي
    wp_enqueue_script(
        'nadiim-main',
        NADIIM_THEME_URI . '/assets/js/main.js',
        array( 'jquery' ),
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

// تضمين ملف Customizer للصفحة الرئيسية
require_once NADIIM_THEME_DIR . '/inc/front-page-customizer.php';

// تضمين ملف Template Functions
require_once NADIIM_THEME_DIR . '/inc/template-functions.php';

// تضمين ملف Template Tags
require_once NADIIM_THEME_DIR . '/inc/template-tags.php';

// تضمين ملف Demo Content
require_once NADIIM_THEME_DIR . '/inc/demo-content.php';

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

/**
 * AJAX Handler للنشرة البريدية
 */
function nadiim_subscribe_newsletter() {
	// التحقق من الأمان
	check_ajax_referer( 'nadiim-front-page-nonce', 'nonce' );

	// الحصول على البريد الإلكتروني
	$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

	// التحقق من صحة البريد الإلكتروني
	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array(
			'message' => __( 'يرجى إدخال بريد إلكتروني صحيح', 'nadiim' ),
		) );
	}

	// حفظ البريد الإلكتروني في قاعدة البيانات
	// يمكن استخدام جدول مخصص أو post meta أو تكامل مع خدمة خارجية
	$subscribers = get_option( 'nadiim_newsletter_subscribers', array() );

	// التحقق من عدم وجود البريد مسبقاً
	if ( in_array( $email, $subscribers ) ) {
		wp_send_json_error( array(
			'message' => __( 'هذا البريد الإلكتروني مشترك بالفعل', 'nadiim' ),
		) );
	}

	// إضافة البريد إلى القائمة
	$subscribers[] = $email;
	update_option( 'nadiim_newsletter_subscribers', $subscribers );

	// إرسال إشعار للمدير (اختياري)
	$admin_email = get_option( 'admin_email' );
	$subject     = __( 'اشتراك جديد في النشرة البريدية', 'nadiim' );
	$message     = sprintf( __( 'اشترك %s في النشرة البريدية', 'nadiim' ), $email );
	wp_mail( $admin_email, $subject, $message );

	// إرسال استجابة النجاح
	wp_send_json_success( array(
		'message' => __( 'تم الاشتراك بنجاح! شكراً لك.', 'nadiim' ),
	) );
}
add_action( 'wp_ajax_nadiim_subscribe_newsletter', 'nadiim_subscribe_newsletter' );
add_action( 'wp_ajax_nopriv_nadiim_subscribe_newsletter', 'nadiim_subscribe_newsletter' );

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
