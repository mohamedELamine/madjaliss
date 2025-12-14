<?php
/**
 * تسجيل Custom Post Type للمراجعات
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تسجيل نوع منشور المراجعات
 */
function nadiim_register_reviews_post_type() {
    $labels = array(
        'name'                  => _x( 'المراجعات', 'Post type general name', 'nadiim' ),
        'singular_name'         => _x( 'مراجعة', 'Post type singular name', 'nadiim' ),
        'menu_name'             => _x( 'المراجعات', 'Admin Menu text', 'nadiim' ),
        'name_admin_bar'        => _x( 'مراجعة', 'Add New on Toolbar', 'nadiim' ),
        'add_new'               => __( 'إضافة مراجعة', 'nadiim' ),
        'add_new_item'          => __( 'إضافة مراجعة جديدة', 'nadiim' ),
        'new_item'              => __( 'مراجعة جديدة', 'nadiim' ),
        'edit_item'             => __( 'تحرير المراجعة', 'nadiim' ),
        'view_item'             => __( 'عرض المراجعة', 'nadiim' ),
        'all_items'             => __( 'كل المراجعات', 'nadiim' ),
        'search_items'          => __( 'البحث في المراجعات', 'nadiim' ),
        'parent_item_colon'     => __( 'المراجعة الأساسية:', 'nadiim' ),
        'not_found'             => __( 'لم يتم العثور على مراجعات', 'nadiim' ),
        'not_found_in_trash'    => __( 'لم يتم العثور على مراجعات في سلة المهملات', 'nadiim' ),
        'featured_image'        => _x( 'صورة الغلاف', 'Overrides the "Featured Image" phrase', 'nadiim' ),
        'set_featured_image'    => _x( 'تعيين صورة الغلاف', 'Overrides the "Set featured image" phrase', 'nadiim' ),
        'remove_featured_image' => _x( 'إزالة صورة الغلاف', 'Overrides the "Remove featured image" phrase', 'nadiim' ),
        'use_featured_image'    => _x( 'استخدم كصورة غلاف', 'Overrides the "Use as featured image" phrase', 'nadiim' ),
        'archives'              => _x( 'أرشيف المراجعات', 'The post type archive label used in nav menus', 'nadiim' ),
        'insert_into_item'      => _x( 'إدراج في المراجعة', 'Overrides the "Insert into post"/"Insert into page" phrase', 'nadiim' ),
        'uploaded_to_this_item' => _x( 'مرفوع لهذه المراجعة', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'nadiim' ),
        'filter_items_list'     => _x( 'تصفية قائمة المراجعات', 'Screen reader text for the filter links heading on the post type listing screen', 'nadiim' ),
        'items_list_navigation' => _x( 'التنقل في قائمة المراجعات', 'Screen reader text for the pagination heading on the post type listing screen', 'nadiim' ),
        'items_list'            => _x( 'قائمة المراجعات', 'Screen reader text for the items list heading on the post type listing screen', 'nadiim' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'reviews' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
        'show_in_rest'       => true, // دعم Gutenberg
        'taxonomies'         => array( 'category', 'post_tag' ),
    );

    register_post_type( 'reviews', $args );
}
add_action( 'init', 'nadiim_register_reviews_post_type' );

/**
 * تسجيل تصنيفات مخصصة للمراجعات
 */
function nadiim_register_reviews_taxonomies() {
    // تصنيف نوع المراجعة (كتاب، فيلم، مسرحية، إلخ)
    $type_labels = array(
        'name'              => _x( 'أنواع المراجعات', 'taxonomy general name', 'nadiim' ),
        'singular_name'     => _x( 'نوع المراجعة', 'taxonomy singular name', 'nadiim' ),
        'search_items'      => __( 'البحث في الأنواع', 'nadiim' ),
        'all_items'         => __( 'كل الأنواع', 'nadiim' ),
        'parent_item'       => __( 'النوع الأساسي', 'nadiim' ),
        'parent_item_colon' => __( 'النوع الأساسي:', 'nadiim' ),
        'edit_item'         => __( 'تحرير النوع', 'nadiim' ),
        'update_item'       => __( 'تحديث النوع', 'nadiim' ),
        'add_new_item'      => __( 'إضافة نوع جديد', 'nadiim' ),
        'new_item_name'     => __( 'اسم النوع الجديد', 'nadiim' ),
        'menu_name'         => __( 'أنواع المراجعات', 'nadiim' ),
    );

    $type_args = array(
        'hierarchical'      => true,
        'labels'            => $type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'review-type' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'review_type', array( 'reviews' ), $type_args );
}
add_action( 'init', 'nadiim_register_reviews_taxonomies' );

/**
 * إضافة أعمدة مخصصة لجدول المراجعات في الإدارة
 */
function nadiim_reviews_custom_columns( $columns ) {
    $new_columns = array();

    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;

        if ( 'title' === $key ) {
            $new_columns['rating']      = __( 'التقييم', 'nadiim' );
            $new_columns['review_type'] = __( 'النوع', 'nadiim' );
        }
    }

    return $new_columns;
}
add_filter( 'manage_reviews_posts_columns', 'nadiim_reviews_custom_columns' );

/**
 * ملء الأعمدة المخصصة بالبيانات
 */
function nadiim_reviews_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'rating':
            $rating = get_post_meta( $post_id, '_review_rating', true );
            if ( $rating ) {
                echo '<span style="color: #f39c12; font-size: 16px;">';
                for ( $i = 0; $i < 5; $i++ ) {
                    echo $i < $rating ? '★' : '☆';
                }
                echo '</span> (' . esc_html( $rating ) . '/5)';
            } else {
                echo '—';
            }
            break;

        case 'review_type':
            $types = get_the_terms( $post_id, 'review_type' );
            if ( $types && ! is_wp_error( $types ) ) {
                $type_names = array();
                foreach ( $types as $type ) {
                    $type_names[] = esc_html( $type->name );
                }
                echo implode( ', ', $type_names );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_reviews_posts_custom_column', 'nadiim_reviews_custom_column_content', 10, 2 );

/**
 * جعل أعمدة التقييم والنوع قابلة للفرز
 */
function nadiim_reviews_sortable_columns( $columns ) {
    $columns['rating'] = 'rating';
    return $columns;
}
add_filter( 'manage_edit-reviews_sortable_columns', 'nadiim_reviews_sortable_columns' );

/**
 * تعديل query للفرز حسب التقييم
 */
function nadiim_reviews_orderby_rating( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( 'rating' === $query->get( 'orderby' ) ) {
        $query->set( 'meta_key', '_review_rating' );
        $query->set( 'orderby', 'meta_value_num' );
    }
}
add_action( 'pre_get_posts', 'nadiim_reviews_orderby_rating' );

/**
 * إعادة تحديث permalinks عند تفعيل الثيم
 */
function nadiim_reviews_rewrite_flush() {
	// تسجيل CPT
	nadiim_register_reviews_post_type();
	nadiim_register_reviews_taxonomies();

	// إعادة تحديث rewrite rules
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'nadiim_reviews_rewrite_flush' );

// أيضاً، عند تفعيل الثيم
add_action( 'after_switch_theme', 'nadiim_reviews_rewrite_flush' );
