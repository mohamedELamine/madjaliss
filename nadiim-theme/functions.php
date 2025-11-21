<?php
/**
 * ملف الدوال الرئيسية لقالب نديم
 *
 * تم تنظيمه وتقسيمه لسهولة الصيانة وتقليل التكرار
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
 * ==========================================
 * تضمين الملفات الأساسية
 * ==========================================
 */

// إعدادات القالب الأساسية
require_once NADIIM_THEME_DIR . '/inc/setup.php';

// تحميل الأصول (CSS & JS)
require_once NADIIM_THEME_DIR . '/inc/enqueue-scripts.php';

// الدوال المساعدة
require_once NADIIM_THEME_DIR . '/inc/helper-functions.php';

// معالجات الاستعلامات
require_once NADIIM_THEME_DIR . '/inc/query-handlers.php';

// دوال القوالب
require_once NADIIM_THEME_DIR . '/inc/template-functions.php';

// وسوم القوالب
require_once NADIIM_THEME_DIR . '/inc/template-tags.php';

/**
 * ==========================================
 * Customizer - التخصيصات
 * ==========================================
 */

// تحميل الفئة الأساسية للـ Customizer
require_once NADIIM_THEME_DIR . '/inc/class-customizer-base.php';

// تحميل ملفات Customizer
require_once NADIIM_THEME_DIR . '/inc/customizer.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-header.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-hero.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-about-mini.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-featured-howarat.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-articles.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-esdar.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-clubs.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-about.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-contact.php';
require_once NADIIM_THEME_DIR . '/inc/front-page-customizer.php';

/**
 * ==========================================
 * Custom Post Types & Taxonomies
 * ==========================================
 */

// أنواع المنشورات المخصصة
require_once NADIIM_THEME_DIR . '/inc/custom-post-types.php';
require_once NADIIM_THEME_DIR . '/inc/cpt-howarat.php';
require_once NADIIM_THEME_DIR . '/inc/cpt-esdar.php';
require_once NADIIM_THEME_DIR . '/inc/cpt-reading-clubs.php';

/**
 * ==========================================
 * Meta Boxes
 * ==========================================
 */

// صناديق البيانات الوصفية
require_once NADIIM_THEME_DIR . '/inc/meta-boxes.php';
require_once NADIIM_THEME_DIR . '/inc/howarat-meta-box.php';
require_once NADIIM_THEME_DIR . '/inc/meta-esdar.php';
require_once NADIIM_THEME_DIR . '/inc/meta-reading-clubs.php';
require_once NADIIM_THEME_DIR . '/inc/article-meta.php';
require_once NADIIM_THEME_DIR . '/inc/author-meta.php';

/**
 * ==========================================
 * ملفات إضافية
 * ==========================================
 */

// دوال مساعدة للإصدارات
require_once NADIIM_THEME_DIR . '/inc/helpers.php';

// تحميل أصول الصفحة الرئيسية
require_once NADIIM_THEME_DIR . '/inc/home-enqueue.php';

// محتوى تجريبي (يمكن حذفه في الإنتاج)
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    require_once NADIIM_THEME_DIR . '/inc/demo-content.php';
}

/**
 * ==========================================
 * Hooks & Filters - خطافات وفلاتر إضافية
 * ==========================================
 */

// يمكن إضافة hooks إضافية هنا حسب الحاجة
