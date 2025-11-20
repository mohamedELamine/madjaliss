<?php
/**
 * تسجيل نوع المحتوى المخصص: الإصدارات (Releases)
 *
 * هذا الملف يسجل CPT للإصدارات (كتب، مجلات، كتيبات، تقارير)
 * مع دعم كامل للحقول المخصصة بدون ACF
 *
 * @package Nadiim
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

/**
 * تسجيل نوع المحتوى المخصص: الإصدارات
 */
function nadiim_register_esdar_post_type() {
    $labels = array(
        'name'                  => _x('الإصدارات', 'Post Type General Name', 'nadiim'),
        'singular_name'         => _x('إصدار', 'Post Type Singular Name', 'nadiim'),
        'menu_name'             => __('الإصدارات', 'nadiim'),
        'name_admin_bar'        => __('إصدار', 'nadiim'),
        'archives'              => __('أرشيف الإصدارات', 'nadiim'),
        'attributes'            => __('خصائص الإصدار', 'nadiim'),
        'parent_item_colon'     => __('الإصدار الرئيسي:', 'nadiim'),
        'all_items'             => __('كل الإصدارات', 'nadiim'),
        'add_new_item'          => __('إضافة إصدار جديد', 'nadiim'),
        'add_new'               => __('إضافة جديد', 'nadiim'),
        'new_item'              => __('إصدار جديد', 'nadiim'),
        'edit_item'             => __('تحرير الإصدار', 'nadiim'),
        'update_item'           => __('تحديث الإصدار', 'nadiim'),
        'view_item'             => __('عرض الإصدار', 'nadiim'),
        'view_items'            => __('عرض الإصدارات', 'nadiim'),
        'search_items'          => __('البحث في الإصدارات', 'nadiim'),
        'not_found'             => __('لم يتم العثور على إصدارات', 'nadiim'),
        'not_found_in_trash'    => __('لم يتم العثور على إصدارات في المهملات', 'nadiim'),
        'featured_image'        => __('صورة الغلاف', 'nadiim'),
        'set_featured_image'    => __('تعيين صورة الغلاف', 'nadiim'),
        'remove_featured_image' => __('إزالة صورة الغلاف', 'nadiim'),
        'use_featured_image'    => __('استخدام كصورة غلاف', 'nadiim'),
        'insert_into_item'      => __('إدراج في الإصدار', 'nadiim'),
        'uploaded_to_this_item' => __('تم الرفع لهذا الإصدار', 'nadiim'),
        'items_list'            => __('قائمة الإصدارات', 'nadiim'),
        'items_list_navigation' => __('التنقل في قائمة الإصدارات', 'nadiim'),
        'filter_items_list'     => __('تصفية قائمة الإصدارات', 'nadiim'),
    );

    $args = array(
        'label'                 => __('إصدار', 'nadiim'),
        'description'           => __('الكتب والمجلات والنشرات والتقارير', 'nadiim'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions'),
        'taxonomies'            => array('category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug'       => 'esdar',
            'with_front' => false,
        ),
    );

    register_post_type('esdar', $args);
}
add_action('init', 'nadiim_register_esdar_post_type', 0);

/**
 * تعديل الأعمدة في صفحة قائمة الإصدارات
 */
function nadiim_esdar_custom_columns($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // إضافة عمود نوع الإصدار بعد العنوان
        if ($key === 'title') {
            $new_columns['release_type'] = __('نوع الإصدار', 'nadiim');
            $new_columns['release_date'] = __('تاريخ الإصدار', 'nadiim');
            $new_columns['authors'] = __('المؤلفون', 'nadiim');
            $new_columns['downloads'] = __('التحميلات', 'nadiim');
        }
    }

    return $new_columns;
}
add_filter('manage_esdar_posts_columns', 'nadiim_esdar_custom_columns');

/**
 * ملء الأعمدة المخصصة بالبيانات
 */
function nadiim_esdar_custom_column_content($column, $post_id) {
    $meta = get_post_meta($post_id, 'esdar_meta', true);
    $data = $meta ? json_decode($meta, true) : array();

    switch ($column) {
        case 'release_type':
            $types = array(
                'book' => 'كتاب',
                'magazine' => 'مجلة',
                'brochure' => 'كتيب',
                'report' => 'تقرير',
                'issue' => 'عدد',
            );
            $type = isset($data['release_type']) ? $data['release_type'] : '';
            echo isset($types[$type]) ? '<span class="esdar-type-badge esdar-type-' . esc_attr($type) . '">' . esc_html($types[$type]) . '</span>' : '—';
            break;

        case 'release_date':
            if (!empty($data['release_date'])) {
                $date = date_i18n(get_option('date_format'), strtotime($data['release_date']));
                echo '<time datetime="' . esc_attr($data['release_date']) . '">' . esc_html($date) . '</time>';
            } else {
                echo '—';
            }
            break;

        case 'authors':
            if (!empty($data['release_authors']) && is_array($data['release_authors'])) {
                $author_names = array();
                foreach ($data['release_authors'] as $author) {
                    if (isset($author['name'])) {
                        $author_names[] = $author['name'];
                    }
                }
                echo !empty($author_names) ? esc_html(implode('، ', $author_names)) : '—';
            } else {
                echo '—';
            }
            break;

        case 'downloads':
            $count = isset($data['release_download_count']) ? intval($data['release_download_count']) : 0;
            echo '<span class="esdar-downloads">' . number_format_i18n($count) . '</span>';
            break;
    }
}
add_action('manage_esdar_posts_custom_column', 'nadiim_esdar_custom_column_content', 10, 2);

/**
 * جعل الأعمدة قابلة للترتيب
 */
function nadiim_esdar_sortable_columns($columns) {
    $columns['release_date'] = 'release_date';
    $columns['release_type'] = 'release_type';
    $columns['downloads'] = 'downloads';
    return $columns;
}
add_filter('manage_edit-esdar_sortable_columns', 'nadiim_esdar_sortable_columns');

/**
 * معالجة الترتيب حسب الأعمدة المخصصة
 */
function nadiim_esdar_orderby_custom_column($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('release_date' === $orderby) {
        $query->set('meta_key', 'esdar_meta');
        $query->set('orderby', 'meta_value');
    } elseif ('downloads' === $orderby) {
        $query->set('meta_key', 'esdar_meta');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'nadiim_esdar_orderby_custom_column');

/**
 * إضافة أنماط CSS للأعمدة في الإدارة
 */
function nadiim_esdar_admin_styles() {
    global $post_type;

    if ('esdar' === $post_type) {
        ?>
        <style>
            .esdar-type-badge {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 500;
            }
            .esdar-type-book { background: #e3f2fd; color: #1976d2; }
            .esdar-type-magazine { background: #f3e5f5; color: #7b1fa2; }
            .esdar-type-brochure { background: #e8f5e9; color: #388e3c; }
            .esdar-type-report { background: #fff3e0; color: #f57c00; }
            .esdar-type-issue { background: #fce4ec; color: #c2185b; }
            .esdar-downloads {
                font-weight: 600;
                color: #339063;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'nadiim_esdar_admin_styles');

/**
 * تحديث رسائل الإشعارات عند حفظ/تحديث الإصدار
 */
function nadiim_esdar_updated_messages($messages) {
    $post             = get_post();
    $post_type        = get_post_type($post);
    $post_type_object = get_post_type_object($post_type);

    $messages['esdar'] = array(
        0  => '', // غير مستخدم
        1  => __('تم تحديث الإصدار.', 'nadiim'),
        2  => __('تم تحديث الحقل المخصص.', 'nadiim'),
        3  => __('تم حذف الحقل المخصص.', 'nadiim'),
        4  => __('تم تحديث الإصدار.', 'nadiim'),
        5  => isset($_GET['revision']) ? sprintf(__('تم استعادة الإصدار من المراجعة من %s', 'nadiim'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => __('تم نشر الإصدار.', 'nadiim'),
        7  => __('تم حفظ الإصدار.', 'nadiim'),
        8  => __('تم إرسال الإصدار.', 'nadiim'),
        9  => sprintf(
            __('تم جدولة الإصدار لـ: <strong>%1$s</strong>.', 'nadiim'),
            date_i18n(__('M j, Y @ G:i', 'nadiim'), strtotime($post->post_date))
        ),
        10 => __('تم حفظ مسودة الإصدار.', 'nadiim'),
    );

    return $messages;
}
add_filter('post_updated_messages', 'nadiim_esdar_updated_messages');

/**
 * تحديث رسائل النشر المجمع
 */
function nadiim_esdar_bulk_messages($bulk_messages, $bulk_counts) {
    $bulk_messages['esdar'] = array(
        'updated'   => _n('%s إصدار تم تحديثه.', '%s إصدارات تم تحديثها.', $bulk_counts['updated'], 'nadiim'),
        'locked'    => _n('%s إصدار لم يتم تحديثه، شخص آخر يقوم بتحريره.', '%s إصدارات لم يتم تحديثها، شخص آخر يقوم بتحريرها.', $bulk_counts['locked'], 'nadiim'),
        'deleted'   => _n('%s إصدار تم حذفه بشكل دائم.', '%s إصدارات تم حذفها بشكل دائم.', $bulk_counts['deleted'], 'nadiim'),
        'trashed'   => _n('%s إصدار تم نقله إلى المهملات.', '%s إصدارات تم نقلها إلى المهملات.', $bulk_counts['trashed'], 'nadiim'),
        'untrashed' => _n('%s إصدار تم استعادته من المهملات.', '%s إصدارات تم استعادتها من المهملات.', $bulk_counts['untrashed'], 'nadiim'),
    );

    return $bulk_messages;
}
add_filter('bulk_post_updated_messages', 'nadiim_esdar_bulk_messages', 10, 2);
