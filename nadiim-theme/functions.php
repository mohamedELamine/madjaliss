<?php
/**
 * Madjaliss Functions and Definitions
 *
 * @package Madjaliss
 * @version 2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ========================================
 * Theme Setup
 * ========================================
 */
function madjaliss_setup() {
    // إضافة دعم RTL
    add_theme_support('rtl');

    // إضافة دعم العنوان الديناميكي
    add_theme_support('title-tag');

    // إضافة دعم الشعار المخصص
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // إضافة دعم صورة مميزة للمقالات
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 630, true);

    // إضافة دعم HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ));

    // إضافة دعم تنسيقات المقالات
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat',
    ));

    // إضافة دعم شريط الألوان في المتصفح
    add_theme_support('custom-background');
    add_theme_support('custom-header');

    // تسجيل القوائم
    register_nav_menus(array(
        'primary' => __('القائمة الرئيسية', 'madjaliss'),
        'footer'  => __('قائمة الفوتر', 'madjaliss'),
    ));

    // إضافة دعم المحرر الحديث
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'madjaliss_setup');

/**
 * ========================================
 * تسجيل الأصول (CSS & JavaScript)
 * ========================================
 */
function madjaliss_enqueue_scripts() {
    // الإصدار
    $version = '2.0';

    // CSS الرئيسي
    wp_enqueue_style('madjaliss-style', get_stylesheet_uri(), array(), $version);

    // Header CSS
    wp_enqueue_style('madjaliss-header', get_template_directory_uri() . '/assets/css/header.css', array(), $version);

    // Header JavaScript
    wp_enqueue_script('madjaliss-header-js', get_template_directory_uri() . '/assets/js/header.js', array(), $version, true);

    // إضافة RTL support
    if (is_rtl()) {
        wp_enqueue_style('madjaliss-rtl', get_template_directory_uri() . '/rtl.css', array('madjaliss-style'), $version);
    }

    // تحميل التعليقات
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'madjaliss_enqueue_scripts');

/**
 * ========================================
 * تضمين ملفات Customizer
 * ========================================
 */
require_once get_template_directory() . '/inc/customizer-topbar.php';
require_once get_template_directory() . '/inc/customizer-header.php';

/**
 * ========================================
 * تضمين ملف الصفحة الرئيسية (Home Page)
 * ========================================
 */
require_once get_template_directory() . '/inc/home-enqueue.php';

/**
 * ========================================
 * تسجيل Sidebars
 * ========================================
 */
function madjaliss_widgets_init() {
    register_sidebar(array(
        'name'          => __('الشريط الجانبي الرئيسي', 'madjaliss'),
        'id'            => 'sidebar-1',
        'description'   => __('الشريط الجانبي الرئيسي للموقع', 'madjaliss'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('الفوتر - العمود 1', 'madjaliss'),
        'id'            => 'footer-1',
        'description'   => __('العمود الأول في الفوتر', 'madjaliss'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('الفوتر - العمود 2', 'madjaliss'),
        'id'            => 'footer-2',
        'description'   => __('العمود الثاني في الفوتر', 'madjaliss'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('الفوتر - العمود 3', 'madjaliss'),
        'id'            => 'footer-3',
        'description'   => __('العمود الثالث في الفوتر', 'madjaliss'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('الفوتر - العمود 4', 'madjaliss'),
        'id'            => 'footer-4',
        'description'   => __('العمود الرابع في الفوتر', 'madjaliss'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'madjaliss_widgets_init');

/**
 * ========================================
 * Content Width
 * ========================================
 */
if (!isset($content_width)) {
    $content_width = 1200;
}

/**
 * ========================================
 * Custom Post Types
 * ========================================
 */

/**
 * تسجيل نوع المحتوى: حوارات (Dialogues)
 */
function madjaliss_register_dialogue_post_type() {
    $labels = array(
        'name'                  => __('الحوارات', 'madjaliss'),
        'singular_name'         => __('حوار', 'madjaliss'),
        'menu_name'             => __('الحوارات', 'madjaliss'),
        'name_admin_bar'        => __('حوار', 'madjaliss'),
        'add_new'               => __('إضافة حوار جديد', 'madjaliss'),
        'add_new_item'          => __('إضافة حوار جديد', 'madjaliss'),
        'new_item'              => __('حوار جديد', 'madjaliss'),
        'edit_item'             => __('تحرير حوار', 'madjaliss'),
        'view_item'             => __('عرض الحوار', 'madjaliss'),
        'all_items'             => __('جميع الحوارات', 'madjaliss'),
        'search_items'          => __('بحث في الحوارات', 'madjaliss'),
        'not_found'             => __('لم يتم العثور على حوارات', 'madjaliss'),
        'not_found_in_trash'    => __('لم يتم العثور على حوارات في سلة المهملات', 'madjaliss'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'dialogue'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-chat',
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author', 'custom-fields'),
        'show_in_rest'          => true,
    );

    register_post_type('dialogue', $args);
}
add_action('init', 'madjaliss_register_dialogue_post_type');

/**
 * تسجيل نوع المحتوى: أحداث (Events)
 */
function madjaliss_register_event_post_type() {
    $labels = array(
        'name'                  => __('الأحداث', 'madjaliss'),
        'singular_name'         => __('حدث', 'madjaliss'),
        'menu_name'             => __('الأحداث', 'madjaliss'),
        'name_admin_bar'        => __('حدث', 'madjaliss'),
        'add_new'               => __('إضافة حدث جديد', 'madjaliss'),
        'add_new_item'          => __('إضافة حدث جديد', 'madjaliss'),
        'new_item'              => __('حدث جديد', 'madjaliss'),
        'edit_item'             => __('تحرير حدث', 'madjaliss'),
        'view_item'             => __('عرض الحدث', 'madjaliss'),
        'all_items'             => __('جميع الأحداث', 'madjaliss'),
        'search_items'          => __('بحث في الأحداث', 'madjaliss'),
        'not_found'             => __('لم يتم العثور على أحداث', 'madjaliss'),
        'not_found_in_trash'    => __('لم يتم العثور على أحداث في سلة المهملات', 'madjaliss'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'event'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-calendar-alt',
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author', 'custom-fields'),
        'show_in_rest'          => true,
    );

    register_post_type('event', $args);
}
add_action('init', 'madjaliss_register_event_post_type');

/**
 * ========================================
 * Helper Functions
 * ========================================
 */

/**
 * احصل على وقت القراءة المقدر
 */
function madjaliss_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 كلمة في الدقيقة

    return $reading_time;
}

/**
 * احصل على رابط المشاركة على تويتر
 */
function madjaliss_get_twitter_share_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $url = urlencode(get_permalink($post_id));
    $title = urlencode(get_the_title($post_id));

    return "https://twitter.com/intent/tweet?url={$url}&text={$title}";
}

/**
 * احصل على رابط المشاركة على فيسبوك
 */
function madjaliss_get_facebook_share_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $url = urlencode(get_permalink($post_id));
    return "https://www.facebook.com/sharer/sharer.php?u={$url}";
}

/**
 * احصل على رابط المشاركة على واتساب
 */
function madjaliss_get_whatsapp_share_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $url = urlencode(get_permalink($post_id));
    $title = urlencode(get_the_title($post_id));

    return "https://api.whatsapp.com/send?text={$title}%20{$url}";
}

/**
 * ========================================
 * Security & Performance
 * ========================================
 */

// إزالة معلومات الإصدار من الرأس
remove_action('wp_head', 'wp_generator');

// تعطيل XML-RPC للأمان
add_filter('xmlrpc_enabled', '__return_false');

// إزالة emoji scripts للأداء
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * ========================================
 * Custom Excerpt Length
 * ========================================
 */
function madjaliss_custom_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'madjaliss_custom_excerpt_length', 999);

/**
 * تخصيص نهاية المقتطف
 */
function madjaliss_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'madjaliss_excerpt_more');
