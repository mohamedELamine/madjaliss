<?php
/**
 * User Meta Fields for About Page
 *
 * @package Nadiim
 * @since 1.0.0
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

/**
 * إضافة حقول مخصصة في صفحة تحرير المستخدم
 */
function nadiim_add_about_page_user_fields($user) {
    // التحقق من الصلاحيات
    if (!current_user_can('edit_user', $user->ID)) {
        return false;
    }

    $show_in_about = get_user_meta($user->ID, 'show_in_about_page', true);
    $about_role = get_user_meta($user->ID, 'about_page_role', true);
    ?>

    <h3><?php _e('إعدادات صفحة من نحن', 'nadiim'); ?></h3>

    <table class="form-table" role="presentation">
        <tr>
            <th><label for="show_in_about_page"><?php _e('الظهور في صفحة من نحن', 'nadiim'); ?></label></th>
            <td>
                <label for="show_in_about_page">
                    <input type="checkbox"
                           name="show_in_about_page"
                           id="show_in_about_page"
                           value="1"
                           <?php checked($show_in_about, '1'); ?>>
                    <?php _e('عرض هذا العضو في صفحة من نحن', 'nadiim'); ?>
                </label>
                <p class="description">
                    <?php _e('إذا تم تفعيل هذا الخيار، سيظهر العضو في قسم "فريق العمل" في صفحة من نحن.', 'nadiim'); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th><label for="about_page_role"><?php _e('الدور في صفحة من نحن', 'nadiim'); ?></label></th>
            <td>
                <input type="text"
                       name="about_page_role"
                       id="about_page_role"
                       value="<?php echo esc_attr($about_role); ?>"
                       class="regular-text">
                <p class="description">
                    <?php _e('اختياري: دور خاص يظهر في صفحة من نحن (مثل: "المؤسس والمدير التنفيذي"). إذا ترك فارغاً، سيتم استخدام الدور الافتراضي.', 'nadiim'); ?>
                </p>
            </td>
        </tr>
    </table>

    <style>
        .user-about-page-settings-wrap h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
    <?php
}
add_action('show_user_profile', 'nadiim_add_about_page_user_fields');
add_action('edit_user_profile', 'nadiim_add_about_page_user_fields');

/**
 * حفظ حقول المستخدم المخصصة
 */
function nadiim_save_about_page_user_fields($user_id) {
    // التحقق من الصلاحيات
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // حفظ خيار الظهور في صفحة من نحن
    if (isset($_POST['show_in_about_page'])) {
        update_user_meta($user_id, 'show_in_about_page', '1');
    } else {
        update_user_meta($user_id, 'show_in_about_page', '0');
    }

    // حفظ الدور في صفحة من نحن
    if (isset($_POST['about_page_role'])) {
        update_user_meta($user_id, 'about_page_role', sanitize_text_field($_POST['about_page_role']));
    }
}
add_action('personal_options_update', 'nadiim_save_about_page_user_fields');
add_action('edit_user_profile_update', 'nadiim_save_about_page_user_fields');

/**
 * إضافة عمود في قائمة المستخدمين يوضح من يظهر في صفحة من نحن
 */
function nadiim_add_about_page_column($columns) {
    $columns['show_in_about'] = __('صفحة من نحن', 'nadiim');
    return $columns;
}
add_filter('manage_users_columns', 'nadiim_add_about_page_column');

/**
 * ملء محتوى عمود صفحة من نحن في قائمة المستخدمين
 */
function nadiim_show_about_page_column_content($value, $column_name, $user_id) {
    if ('show_in_about' === $column_name) {
        $show_in_about = get_user_meta($user_id, 'show_in_about_page', true);
        if ($show_in_about === '1') {
            return '<span style="color: #339063; font-weight: 600;">✓ معروض</span>';
        } else {
            return '<span style="color: #999;">—</span>';
        }
    }
    return $value;
}
add_filter('manage_users_custom_column', 'nadiim_show_about_page_column_content', 10, 3);

/**
 * جعل العمود قابلاً للترتيب
 */
function nadiim_make_about_column_sortable($columns) {
    $columns['show_in_about'] = 'show_in_about';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'nadiim_make_about_column_sortable');

/**
 * ترتيب المستخدمين حسب الظهور في صفحة من نحن
 */
function nadiim_sort_users_by_about_page($query) {
    if (!is_admin()) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'users' !== $screen->id) {
        return;
    }

    if (isset($_GET['orderby']) && 'show_in_about' === $_GET['orderby']) {
        $query->query_vars['meta_key'] = 'show_in_about_page';
        $query->query_vars['orderby'] = 'meta_value';
    }
}
add_action('pre_get_users', 'nadiim_sort_users_by_about_page');
