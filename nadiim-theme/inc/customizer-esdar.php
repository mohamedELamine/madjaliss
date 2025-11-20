<?php
/**
 * إعدادات Customizer للإصدارات
 *
 * @package Nadiim
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

/**
 * تسجيل إعدادات الإصدارات في Customizer
 *
 * @param WP_Customize_Manager $wp_customize مدير التخصيص
 */
function nadiim_esdar_customize_register($wp_customize) {

    // إضافة Panel للإصدارات
    $wp_customize->add_panel('esdar_panel', array(
        'title' => __('إعدادات الإصدارات', 'nadiim'),
        'description' => __('تخصيص عرض وسلوك الإصدارات', 'nadiim'),
        'priority' => 160,
    ));

    // ===================================
    // Section: إعدادات الأرشيف
    // ===================================
    $wp_customize->add_section('esdar_archive_section', array(
        'title' => __('إعدادات الأرشيف', 'nadiim'),
        'panel' => 'esdar_panel',
        'priority' => 10,
    ));

    // عدد الإصدارات في الأرشيف
    $wp_customize->add_setting('esdar_archive_per_page', array(
        'default' => 12,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('esdar_archive_per_page', array(
        'label' => __('عدد الإصدارات في الصفحة', 'nadiim'),
        'description' => __('عدد الإصدارات المعروضة في صفحة الأرشيف', 'nadiim'),
        'section' => 'esdar_archive_section',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
    ));

    // ترتيب الإصدارات
    $wp_customize->add_setting('esdar_archive_orderby', array(
        'default' => 'date',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('esdar_archive_orderby', array(
        'label' => __('ترتيب الإصدارات حسب', 'nadiim'),
        'section' => 'esdar_archive_section',
        'type' => 'select',
        'choices' => array(
            'date' => __('التاريخ', 'nadiim'),
            'title' => __('العنوان', 'nadiim'),
            'modified' => __('آخر تحديث', 'nadiim'),
            'rand' => __('عشوائي', 'nadiim'),
        ),
    ));

    // اتجاه الترتيب
    $wp_customize->add_setting('esdar_archive_order', array(
        'default' => 'DESC',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('esdar_archive_order', array(
        'label' => __('اتجاه الترتيب', 'nadiim'),
        'section' => 'esdar_archive_section',
        'type' => 'select',
        'choices' => array(
            'DESC' => __('تنازلي (الأحدث أولاً)', 'nadiim'),
            'ASC' => __('تصاعدي (الأقدم أولاً)', 'nadiim'),
        ),
    ));

    // ===================================
    // Section: قسم الإصدارات في الصفحة الرئيسية
    // ===================================
    $wp_customize->add_section('esdar_home_section', array(
        'title' => __('قسم الإصدارات في الصفحة الرئيسية', 'nadiim'),
        'panel' => 'esdar_panel',
        'priority' => 20,
    ));

    // إظهار قسم الإصدارات في الصفحة الرئيسية
    $wp_customize->add_setting('esdar_show_on_home', array(
        'default' => false,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('esdar_show_on_home', array(
        'label' => __('إظهار قسم الإصدارات', 'nadiim'),
        'description' => __('عرض قسم للإصدارات المختارة في الصفحة الرئيسية', 'nadiim'),
        'section' => 'esdar_home_section',
        'type' => 'checkbox',
    ));

    // عنوان القسم
    $wp_customize->add_setting('esdar_section_title', array(
        'default' => __('أحدث الإصدارات', 'nadiim'),
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('esdar_section_title', array(
        'label' => __('عنوان القسم', 'nadiim'),
        'section' => 'esdar_home_section',
        'type' => 'text',
        'active_callback' => function() {
            return get_theme_mod('esdar_show_on_home', false);
        },
    ));

    // وصف القسم
    $wp_customize->add_setting('esdar_section_description', array(
        'default' => __('استعرض أحدث الكتب والمجلات والإصدارات', 'nadiim'),
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('esdar_section_description', array(
        'label' => __('وصف القسم', 'nadiim'),
        'section' => 'esdar_home_section',
        'type' => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('esdar_show_on_home', false);
        },
    ));

    // عدد الإصدارات في الصفحة الرئيسية
    $wp_customize->add_setting('esdar_section_count', array(
        'default' => 6,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('esdar_section_count', array(
        'label' => __('عدد الإصدارات المعروضة', 'nadiim'),
        'section' => 'esdar_home_section',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('esdar_show_on_home', false);
        },
    ));

    // تخطيط القسم
    $wp_customize->add_setting('esdar_section_layout', array(
        'default' => 'grid',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('esdar_section_layout', array(
        'label' => __('تخطيط العرض', 'nadiim'),
        'section' => 'esdar_home_section',
        'type' => 'select',
        'choices' => array(
            'grid' => __('شبكة (Grid)', 'nadiim'),
            'carousel' => __('شريط منزلق (Carousel)', 'nadiim'),
        ),
        'active_callback' => function() {
            return get_theme_mod('esdar_show_on_home', false);
        },
    ));

    // ===================================
    // Section: إعدادات العرض
    // ===================================
    $wp_customize->add_section('esdar_display_section', array(
        'title' => __('إعدادات العرض', 'nadiim'),
        'panel' => 'esdar_panel',
        'priority' => 30,
    ));

    // عرض صور المعاينة
    $wp_customize->add_setting('esdar_show_preview_images', array(
        'default' => true,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('esdar_show_preview_images', array(
        'label' => __('عرض صور المعاينة', 'nadiim'),
        'description' => __('عرض معرض صور المعاينة في صفحة الإصدار', 'nadiim'),
        'section' => 'esdar_display_section',
        'type' => 'checkbox',
    ));

    // عرض الإصدارات ذات الصلة
    $wp_customize->add_setting('esdar_show_related', array(
        'default' => true,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('esdar_show_related', array(
        'label' => __('عرض الإصدارات ذات الصلة', 'nadiim'),
        'description' => __('عرض قسم الإصدارات المشابهة أسفل الإصدار', 'nadiim'),
        'section' => 'esdar_display_section',
        'type' => 'checkbox',
    ));

    // عدد الإصدارات ذات الصلة
    $wp_customize->add_setting('esdar_related_count', array(
        'default' => 6,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('esdar_related_count', array(
        'label' => __('عدد الإصدارات ذات الصلة', 'nadiim'),
        'section' => 'esdar_display_section',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('esdar_show_related', true);
        },
    ));

    // عرض أزرار المشاركة
    $wp_customize->add_setting('esdar_show_share_buttons', array(
        'default' => true,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('esdar_show_share_buttons', array(
        'label' => __('عرض أزرار المشاركة', 'nadiim'),
        'description' => __('عرض أزرار المشاركة على وسائل التواصل الاجتماعي', 'nadiim'),
        'section' => 'esdar_display_section',
        'type' => 'checkbox',
    ));

    // ===================================
    // Section: إعدادات التحميل
    // ===================================
    $wp_customize->add_section('esdar_download_section', array(
        'title' => __('إعدادات التحميل', 'nadiim'),
        'panel' => 'esdar_panel',
        'priority' => 40,
    ));

    // تتبع التحميلات
    $wp_customize->add_setting('esdar_track_downloads', array(
        'default' => true,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('esdar_track_downloads', array(
        'label' => __('تتبع عدد التحميلات', 'nadiim'),
        'description' => __('تسجيل عدد مرات تحميل كل إصدار', 'nadiim'),
        'section' => 'esdar_download_section',
        'type' => 'checkbox',
    ));

    // عرض عداد التحميلات
    $wp_customize->add_setting('esdar_show_download_count', array(
        'default' => true,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('esdar_show_download_count', array(
        'label' => __('عرض عداد التحميلات', 'nadiim'),
        'description' => __('إظهار عدد التحميلات للزوار', 'nadiim'),
        'section' => 'esdar_download_section',
        'type' => 'checkbox',
    ));

}
add_action('customize_register', 'nadiim_esdar_customize_register');
