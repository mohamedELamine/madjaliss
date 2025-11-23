<?php
/**
 * تسجيل أنواع المنشورات المخصصة (Custom Post Types)
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تسجيل جميع أنواع المنشورات المخصصة
 */
function nadiim_register_post_types() {

    // ============================================
    // CPT: الحوارات (howarat)
    // ============================================

    $howarat_labels = array(
        'name'                  => _x( 'الحوارات', 'Post type general name', 'nadiim' ),
        'singular_name'         => _x( 'حوار', 'Post type singular name', 'nadiim' ),
        'menu_name'             => _x( 'الحوارات', 'Admin Menu text', 'nadiim' ),
        'name_admin_bar'        => _x( 'حوار', 'Add New on Toolbar', 'nadiim' ),
        'add_new'               => __( 'إضافة حوار جديد', 'nadiim' ),
        'add_new_item'          => __( 'إضافة حوار جديد', 'nadiim' ),
        'new_item'              => __( 'حوار جديد', 'nadiim' ),
        'edit_item'             => __( 'تحرير الحوار', 'nadiim' ),
        'view_item'             => __( 'عرض الحوار', 'nadiim' ),
        'all_items'             => __( 'جميع الحوارات', 'nadiim' ),
        'search_items'          => __( 'بحث في الحوارات', 'nadiim' ),
        'parent_item_colon'     => __( 'الحوارات الأصل:', 'nadiim' ),
        'not_found'             => __( 'لم يُعثر على حوارات', 'nadiim' ),
        'not_found_in_trash'    => __( 'لم يُعثر على حوارات في سلة المهملات', 'nadiim' ),
        'featured_image'        => _x( 'غلاف الحوار', 'Overrides the "Featured Image" phrase', 'nadiim' ),
        'set_featured_image'    => _x( 'تعيين غلاف الحوار', 'Overrides the "Set featured image" phrase', 'nadiim' ),
        'remove_featured_image' => _x( 'إزالة غلاف الحوار', 'Overrides the "Remove featured image" phrase', 'nadiim' ),
        'use_featured_image'    => _x( 'استخدام كغلاف', 'Overrides the "Use as featured image" phrase', 'nadiim' ),
        'archives'              => _x( 'أرشيف الحوارات', 'The post type archive label', 'nadiim' ),
        'insert_into_item'      => _x( 'إدراج في الحوار', 'Overrides the "Insert into post"', 'nadiim' ),
        'uploaded_to_this_item' => _x( 'رُفع إلى هذا الحوار', 'Overrides the "Uploaded to this post"', 'nadiim' ),
        'filter_items_list'     => _x( 'تصفية قائمة الحوارات', 'Screen reader text', 'nadiim' ),
        'items_list_navigation' => _x( 'التنقل في قائمة الحوارات', 'Screen reader text', 'nadiim' ),
        'items_list'            => _x( 'قائمة الحوارات', 'Screen reader text', 'nadiim' ),
    );

    $howarat_args = array(
        'labels'             => $howarat_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'howarat', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-microphone',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'howarat', $howarat_args );

    // ============================================
    // CPT: الإصدارات (esdar)
    // ============================================

    $esdar_labels = array(
        'name'                  => _x( 'الإصدارات', 'Post type general name', 'nadiim' ),
        'singular_name'         => _x( 'إصدار', 'Post type singular name', 'nadiim' ),
        'menu_name'             => _x( 'الإصدارات', 'Admin Menu text', 'nadiim' ),
        'name_admin_bar'        => _x( 'إصدار', 'Add New on Toolbar', 'nadiim' ),
        'add_new'               => __( 'إضافة إصدار جديد', 'nadiim' ),
        'add_new_item'          => __( 'إضافة إصدار جديد', 'nadiim' ),
        'new_item'              => __( 'إصدار جديد', 'nadiim' ),
        'edit_item'             => __( 'تحرير الإصدار', 'nadiim' ),
        'view_item'             => __( 'عرض الإصدار', 'nadiim' ),
        'all_items'             => __( 'جميع الإصدارات', 'nadiim' ),
        'search_items'          => __( 'بحث في الإصدارات', 'nadiim' ),
        'not_found'             => __( 'لم يُعثر على إصدارات', 'nadiim' ),
        'not_found_in_trash'    => __( 'لم يُعثر على إصدارات في سلة المهملات', 'nadiim' ),
        'featured_image'        => _x( 'غلاف الإصدار', 'Overrides the "Featured Image" phrase', 'nadiim' ),
        'set_featured_image'    => _x( 'تعيين غلاف الإصدار', 'Overrides the "Set featured image" phrase', 'nadiim' ),
        'remove_featured_image' => _x( 'إزالة غلاف الإصدار', 'Overrides the "Remove featured image" phrase', 'nadiim' ),
        'use_featured_image'    => _x( 'استخدام كغلاف', 'Overrides the "Use as featured image" phrase', 'nadiim' ),
        'archives'              => _x( 'أرشيف الإصدارات', 'The post type archive label', 'nadiim' ),
    );

    $esdar_args = array(
        'labels'             => $esdar_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'esdar', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-book',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'esdar', $esdar_args );

    // ============================================
    // CPT: نوادي القراءة (reading_clubs)
    // ============================================

    $reading_clubs_labels = array(
        'name'                  => _x( 'نوادي القراءة', 'Post type general name', 'nadiim' ),
        'singular_name'         => _x( 'نادي قراءة', 'Post type singular name', 'nadiim' ),
        'menu_name'             => _x( 'نوادي القراءة', 'Admin Menu text', 'nadiim' ),
        'name_admin_bar'        => _x( 'نادي قراءة', 'Add New on Toolbar', 'nadiim' ),
        'add_new'               => __( 'إضافة نادٍ جديد', 'nadiim' ),
        'add_new_item'          => __( 'إضافة نادي قراءة جديد', 'nadiim' ),
        'new_item'              => __( 'نادي جديد', 'nadiim' ),
        'edit_item'             => __( 'تحرير النادي', 'nadiim' ),
        'view_item'             => __( 'عرض النادي', 'nadiim' ),
        'all_items'             => __( 'جميع النوادي', 'nadiim' ),
        'search_items'          => __( 'بحث في النوادي', 'nadiim' ),
        'not_found'             => __( 'لم يُعثر على نوادي', 'nadiim' ),
        'not_found_in_trash'    => __( 'لم يُعثر على نوادي في سلة المهملات', 'nadiim' ),
        'featured_image'        => _x( 'صورة النادي', 'Overrides the "Featured Image" phrase', 'nadiim' ),
        'set_featured_image'    => _x( 'تعيين صورة النادي', 'Overrides the "Set featured image" phrase', 'nadiim' ),
        'archives'              => _x( 'أرشيف نوادي القراءة', 'The post type archive label', 'nadiim' ),
    );

    $reading_clubs_args = array(
        'labels'             => $reading_clubs_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'reading-clubs', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'reading_clubs', $reading_clubs_args );

    // ============================================
    // CPT: الاستفسارات (inquiries) - لصفحة اتصل بنا
    // ============================================

    $inquiries_labels = array(
        'name'               => _x( 'الاستفسارات', 'Post type general name', 'nadiim' ),
        'singular_name'      => _x( 'استفسار', 'Post type singular name', 'nadiim' ),
        'menu_name'          => _x( 'الاستفسارات', 'Admin Menu text', 'nadiim' ),
        'all_items'          => __( 'جميع الاستفسارات', 'nadiim' ),
        'view_item'          => __( 'عرض الاستفسار', 'nadiim' ),
        'search_items'       => __( 'بحث في الاستفسارات', 'nadiim' ),
        'not_found'          => __( 'لم يُعثر على استفسارات', 'nadiim' ),
    );

    $inquiries_args = array(
        'labels'             => $inquiries_labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-email',
        'supports'           => array( 'title', 'editor' ),
        'capabilities'       => array(
            'create_posts' => false,
        ),
        'map_meta_cap'       => true,
    );

    register_post_type( 'inquiries', $inquiries_args );
}
add_action( 'init', 'nadiim_register_post_types' );

/**
 * تسجيل التصنيفات المخصصة (Taxonomies)
 */
function nadiim_register_taxonomies() {

    // ============================================
    // Taxonomy: نوع الحوار (dialogue_type)
    // ============================================

    $dialogue_type_labels = array(
        'name'                       => _x( 'أنواع الحوارات', 'taxonomy general name', 'nadiim' ),
        'singular_name'              => _x( 'نوع الحوار', 'taxonomy singular name', 'nadiim' ),
        'search_items'               => __( 'بحث في الأنواع', 'nadiim' ),
        'popular_items'              => __( 'الأنواع الشائعة', 'nadiim' ),
        'all_items'                  => __( 'جميع الأنواع', 'nadiim' ),
        'edit_item'                  => __( 'تحرير النوع', 'nadiim' ),
        'update_item'                => __( 'تحديث النوع', 'nadiim' ),
        'add_new_item'               => __( 'إضافة نوع جديد', 'nadiim' ),
        'new_item_name'              => __( 'اسم نوع جديد', 'nadiim' ),
        'separate_items_with_commas' => __( 'افصل الأنواع بفواصل', 'nadiim' ),
        'add_or_remove_items'        => __( 'إضافة أو إزالة أنواع', 'nadiim' ),
        'choose_from_most_used'      => __( 'اختر من الأكثر استخداماً', 'nadiim' ),
        'not_found'                  => __( 'لم يُعثر على أنواع', 'nadiim' ),
        'menu_name'                  => __( 'أنواع الحوارات', 'nadiim' ),
    );

    $dialogue_type_args = array(
        'hierarchical'      => true,
        'labels'            => $dialogue_type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'dialogue-type' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'dialogue_type', array( 'howarat' ), $dialogue_type_args );

    // ============================================
    // Taxonomy: المواضيع (dialogue_topic)
    // ============================================

    $dialogue_topic_labels = array(
        'name'                       => _x( 'مواضيع الحوارات', 'taxonomy general name', 'nadiim' ),
        'singular_name'              => _x( 'موضوع', 'taxonomy singular name', 'nadiim' ),
        'search_items'               => __( 'بحث في المواضيع', 'nadiim' ),
        'popular_items'              => __( 'المواضيع الشائعة', 'nadiim' ),
        'all_items'                  => __( 'جميع المواضيع', 'nadiim' ),
        'edit_item'                  => __( 'تحرير الموضوع', 'nadiim' ),
        'update_item'                => __( 'تحديث الموضوع', 'nadiim' ),
        'add_new_item'               => __( 'إضافة موضوع جديد', 'nadiim' ),
        'new_item_name'              => __( 'اسم موضوع جديد', 'nadiim' ),
        'separate_items_with_commas' => __( 'افصل المواضيع بفواصل', 'nadiim' ),
        'add_or_remove_items'        => __( 'إضافة أو إزالة مواضيع', 'nadiim' ),
        'choose_from_most_used'      => __( 'اختر من الأكثر استخداماً', 'nadiim' ),
        'not_found'                  => __( 'لم يُعثر على مواضيع', 'nadiim' ),
        'menu_name'                  => __( 'المواضيع', 'nadiim' ),
    );

    $dialogue_topic_args = array(
        'hierarchical'      => false,
        'labels'            => $dialogue_topic_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'dialogue-topic' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'dialogue_topic', array( 'howarat' ), $dialogue_topic_args );

    // ============================================
    // Taxonomy: نوع الإصدار (release_type)
    // ============================================

    $release_type_labels = array(
        'name'                       => _x( 'أنواع الإصدارات', 'taxonomy general name', 'nadiim' ),
        'singular_name'              => _x( 'نوع الإصدار', 'taxonomy singular name', 'nadiim' ),
        'search_items'               => __( 'بحث في الأنواع', 'nadiim' ),
        'popular_items'              => __( 'الأنواع الشائعة', 'nadiim' ),
        'all_items'                  => __( 'جميع الأنواع', 'nadiim' ),
        'edit_item'                  => __( 'تحرير النوع', 'nadiim' ),
        'update_item'                => __( 'تحديث النوع', 'nadiim' ),
        'add_new_item'               => __( 'إضافة نوع جديد', 'nadiim' ),
        'new_item_name'              => __( 'اسم نوع جديد', 'nadiim' ),
        'menu_name'                  => __( 'أنواع الإصدارات', 'nadiim' ),
    );

    $release_type_args = array(
        'hierarchical'      => true,
        'labels'            => $release_type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'release-type' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'release_type', array( 'esdar' ), $release_type_args );

    // ============================================
    // Taxonomy: نوع النادي (club_type)
    // ============================================

    $club_type_labels = array(
        'name'          => _x( 'أنواع النوادي', 'taxonomy general name', 'nadiim' ),
        'singular_name' => _x( 'نوع النادي', 'taxonomy singular name', 'nadiim' ),
        'search_items'  => __( 'بحث في الأنواع', 'nadiim' ),
        'all_items'     => __( 'جميع الأنواع', 'nadiim' ),
        'edit_item'     => __( 'تحرير النوع', 'nadiim' ),
        'add_new_item'  => __( 'إضافة نوع جديد', 'nadiim' ),
        'menu_name'     => __( 'أنواع النوادي', 'nadiim' ),
    );

    $club_type_args = array(
        'hierarchical'      => true,
        'labels'            => $club_type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'club-type' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'club_type', array( 'reading_clubs' ), $club_type_args );
}
add_action( 'init', 'nadiim_register_taxonomies' );
