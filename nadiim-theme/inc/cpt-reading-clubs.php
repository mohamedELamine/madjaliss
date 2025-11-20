<?php
/**
 * Custom Post Type: Reading Clubs
 *
 * @package Madjaliss
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

/**
 * تسجيل Custom Post Type لنوادي القراءة
 */
function madjaliss_register_reading_clubs_cpt() {
    $labels = array(
        'name'                  => _x('نوادي القراءة', 'Post Type General Name', 'madjaliss'),
        'singular_name'         => _x('نادي قراءة', 'Post Type Singular Name', 'madjaliss'),
        'menu_name'             => __('نوادي القراءة', 'madjaliss'),
        'name_admin_bar'        => __('نادي قراءة', 'madjaliss'),
        'archives'              => __('أرشيف النوادي', 'madjaliss'),
        'attributes'            => __('خصائص النادي', 'madjaliss'),
        'parent_item_colon'     => __('النادي الأب:', 'madjaliss'),
        'all_items'             => __('كل النوادي', 'madjaliss'),
        'add_new_item'          => __('إضافة نادي جديد', 'madjaliss'),
        'add_new'               => __('إضافة جديد', 'madjaliss'),
        'new_item'              => __('نادي جديد', 'madjaliss'),
        'edit_item'             => __('تحرير النادي', 'madjaliss'),
        'update_item'           => __('تحديث النادي', 'madjaliss'),
        'view_item'             => __('عرض النادي', 'madjaliss'),
        'view_items'            => __('عرض النوادي', 'madjaliss'),
        'search_items'          => __('بحث في النوادي', 'madjaliss'),
        'not_found'             => __('لم يتم العثور على نوادي', 'madjaliss'),
        'not_found_in_trash'    => __('لا توجد نوادي في سلة المهملات', 'madjaliss'),
        'featured_image'        => __('صورة النادي البارزة', 'madjaliss'),
        'set_featured_image'    => __('تعيين صورة بارزة', 'madjaliss'),
        'remove_featured_image' => __('إزالة الصورة البارزة', 'madjaliss'),
        'use_featured_image'    => __('استخدام كصورة بارزة', 'madjaliss'),
        'insert_into_item'      => __('إدراج في النادي', 'madjaliss'),
        'uploaded_to_this_item' => __('رفع إلى هذا النادي', 'madjaliss'),
        'items_list'            => __('قائمة النوادي', 'madjaliss'),
        'items_list_navigation' => __('التنقل في قائمة النوادي', 'madjaliss'),
        'filter_items_list'     => __('تصفية قائمة النوادي', 'madjaliss'),
    );

    $args = array(
        'label'                 => __('نادي قراءة', 'madjaliss'),
        'description'           => __('نوادي القراءة والمناقشة', 'madjaliss'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'author'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug'              => 'reading-clubs',
            'with_front'        => false,
        ),
    );

    register_post_type('reading_clubs', $args);
}
add_action('init', 'madjaliss_register_reading_clubs_cpt', 0);

/**
 * إضافة أعمدة مخصصة لجدول النوادي في الإدارة
 */
function madjaliss_reading_clubs_custom_columns($columns) {
    $new_columns = array();

    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumbnail'] = __('الصورة', 'madjaliss');
    $new_columns['title'] = $columns['title'];
    $new_columns['location'] = __('موقع الاجتماع', 'madjaliss');
    $new_columns['social_links'] = __('الروابط الاجتماعية', 'madjaliss');
    $new_columns['visibility'] = __('الظهور', 'madjaliss');
    $new_columns['author'] = $columns['author'];
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter('manage_reading_clubs_posts_columns', 'madjaliss_reading_clubs_custom_columns');

/**
 * ملء الأعمدة المخصصة بالبيانات
 */
function madjaliss_reading_clubs_custom_columns_content($column, $post_id) {
    $club_meta = get_post_meta($post_id, 'club_meta', true);

    if (!is_array($club_meta)) {
        $club_meta = array();
    }

    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '<span class="dashicons dashicons-format-image" style="font-size:50px;color:#ddd;"></span>';
            }
            break;

        case 'location':
            $address = isset($club_meta['meeting_location']['address']) ? $club_meta['meeting_location']['address'] : '';
            if ($address) {
                echo '<span class="dashicons dashicons-location"></span> ' . esc_html($address);
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;

        case 'social_links':
            $links = array();
            if (!empty($club_meta['facebook_page'])) {
                $links[] = '<a href="' . esc_url($club_meta['facebook_page']) . '" target="_blank" title="فيسبوك"><span class="dashicons dashicons-facebook"></span></a>';
            }
            if (!empty($club_meta['telegram_channel'])) {
                $links[] = '<a href="' . esc_url($club_meta['telegram_channel']) . '" target="_blank" title="تيليجرام"><span class="dashicons dashicons-phone"></span></a>';
            }
            if (!empty($club_meta['website'])) {
                $links[] = '<a href="' . esc_url($club_meta['website']) . '" target="_blank" title="موقع"><span class="dashicons dashicons-admin-site"></span></a>';
            }

            if (count($links) > 0) {
                echo implode(' ', $links);
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;

        case 'visibility':
            $visibility = isset($club_meta['visibility']) ? $club_meta['visibility'] : 'public';
            if ($visibility === 'public') {
                echo '<span style="color:#46b450;">● عام</span>';
            } else {
                echo '<span style="color:#999;">● خاص</span>';
            }
            break;
    }
}
add_action('manage_reading_clubs_posts_custom_column', 'madjaliss_reading_clubs_custom_columns_content', 10, 2);

/**
 * جعل عمود الموقع قابل للفرز
 */
function madjaliss_reading_clubs_sortable_columns($columns) {
    $columns['location'] = 'location';
    $columns['visibility'] = 'visibility';
    return $columns;
}
add_filter('manage_edit-reading_clubs_sortable_columns', 'madjaliss_reading_clubs_sortable_columns');

/**
 * تحديث رسائل النشر المخصصة
 */
function madjaliss_reading_clubs_updated_messages($messages) {
    $post = get_post();

    $messages['reading_clubs'] = array(
        0  => '',
        1  => __('تم تحديث النادي.', 'madjaliss') . ' <a href="' . esc_url(get_permalink($post->ID)) . '">' . __('عرض النادي', 'madjaliss') . '</a>',
        2  => __('تم تحديث الحقل المخصص.', 'madjaliss'),
        3  => __('تم حذف الحقل المخصص.', 'madjaliss'),
        4  => __('تم تحديث النادي.', 'madjaliss'),
        5  => isset($_GET['revision']) ? sprintf(__('تم استعادة النادي من المراجعة بتاريخ %s', 'madjaliss'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => __('تم نشر النادي.', 'madjaliss') . ' <a href="' . esc_url(get_permalink($post->ID)) . '">' . __('عرض النادي', 'madjaliss') . '</a>',
        7  => __('تم حفظ النادي.', 'madjaliss'),
        8  => __('تم إرسال النادي.', 'madjaliss') . ' <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">' . __('معاينة النادي', 'madjaliss') . '</a>',
        9  => sprintf(
            __('تم جدولة النادي لـ: <strong>%1$s</strong>.', 'madjaliss'),
            date_i18n(__('M j, Y @ G:i', 'madjaliss'), strtotime($post->post_date))
        ) . ' <a target="_blank" href="' . esc_url(get_permalink($post->ID)) . '">' . __('معاينة النادي', 'madjaliss') . '</a>',
        10 => __('تم تحديث مسودة النادي.', 'madjaliss') . ' <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">' . __('معاينة النادي', 'madjaliss') . '</a>',
    );

    return $messages;
}
add_filter('post_updated_messages', 'madjaliss_reading_clubs_updated_messages');

/**
 * مسح القواعد عند التفعيل
 */
function madjaliss_reading_clubs_rewrite_flush() {
    madjaliss_register_reading_clubs_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'madjaliss_reading_clubs_rewrite_flush');
