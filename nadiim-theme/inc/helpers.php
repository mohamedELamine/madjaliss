<?php
/**
 * دوال مساعدة عامة
 *
 * يحتوي هذا الملف على دوال مساعدة للموضوع
 *
 * @package Nadiim
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

// ==================================================================
// دوال مساعدة للإصدارات (Releases Helper Functions)
// ==================================================================

/**
 * الحصول على بيانات إصدار
 *
 * @param int $post_id معرف المنشور
 * @return array|null بيانات الإصدار أو null
 */
function nadiim_get_esdar_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta_json = get_post_meta($post_id, 'esdar_meta', true);

    if (!$meta_json) {
        return null;
    }

    $data = json_decode($meta_json, true);

    // إضافة قيم افتراضية
    $defaults = array(
        'release_date' => '',
        'release_type' => 'book',
        'release_authors' => array(),
        'release_file_id' => '',
        'release_file_url' => '',
        'release_preview_embed' => '',
        'release_isbn' => '',
        'release_pages' => '',
        'release_language' => 'ar',
        'release_excerpt' => '',
        'release_preview_images' => array(),
        'release_download_count' => 0,
        'release_price' => '',
        'release_format' => array('pdf'),
    );

    return wp_parse_args($data, $defaults);
}

/**
 * الحصول على رابط تحميل الإصدار
 *
 * @param int $post_id معرف المنشور
 * @return string|false رابط التحميل أو false
 */
function nadiim_get_esdar_download_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta = nadiim_get_esdar_meta($post_id);

    if (!$meta) {
        return false;
    }

    // إذا كان هناك attachment ID، استخدمه
    if (!empty($meta['release_file_id'])) {
        return wp_get_attachment_url($meta['release_file_id']);
    }

    // وإلا استخدم الرابط المباشر
    if (!empty($meta['release_file_url'])) {
        return $meta['release_file_url'];
    }

    return false;
}

/**
 * زيادة عداد التحميل للإصدار
 *
 * @param int $post_id معرف المنشور
 * @return bool نجاح العملية
 */
function nadiim_increment_esdar_download_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // التحقق من أن المنشور من نوع esdar
    if (get_post_type($post_id) !== 'esdar') {
        return false;
    }

    // منع تكرار العد للمستخدم نفسه (باستخدام transient)
    $transient_key = 'esdar_download_' . $post_id . '_' . nadiim_get_user_ip_hash();

    if (get_transient($transient_key)) {
        return false; // تم العد مسبقاً خلال آخر ساعة
    }

    // الحصول على البيانات الحالية
    $meta = nadiim_get_esdar_meta($post_id);

    if (!$meta) {
        return false;
    }

    // زيادة العداد
    $meta['release_download_count'] = intval($meta['release_download_count']) + 1;

    // حفظ البيانات المحدثة
    update_post_meta($post_id, 'esdar_meta', wp_json_encode($meta, JSON_UNESCAPED_UNICODE));

    // تعيين transient لمدة ساعة
    set_transient($transient_key, true, HOUR_IN_SECONDS);

    return true;
}

/**
 * الحصول على hash لـ IP المستخدم (للخصوصية)
 *
 * @return string
 */
function nadiim_get_user_ip_hash() {
    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return md5($ip . wp_salt());
}

/**
 * الحصول على أحدث الإصدارات
 *
 * @param int $count عدد الإصدارات المطلوبة
 * @param array $args معاملات إضافية لـ WP_Query
 * @return WP_Query
 */
function nadiim_get_recent_esdar($count = 6, $args = array()) {
    $defaults = array(
        'post_type' => 'esdar',
        'posts_per_page' => $count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );

    $query_args = wp_parse_args($args, $defaults);

    return new WP_Query($query_args);
}

/**
 * الحصول على إصدارات بنوع معين
 *
 * @param string $type نوع الإصدار (book, magazine, brochure, report, issue)
 * @param int $count عدد الإصدارات
 * @return array
 */
function nadiim_get_esdar_by_type($type, $count = -1) {
    $query = new WP_Query(array(
        'post_type' => 'esdar',
        'posts_per_page' => $count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $meta = nadiim_get_esdar_meta(get_the_ID());

            if ($meta && $meta['release_type'] === $type) {
                $results[] = get_the_ID();
            }
        }
        wp_reset_postdata();
    }

    return $results;
}

/**
 * عرض قائمة المؤلفين للإصدار
 *
 * @param int $post_id معرف المنشور
 * @param string $separator فاصل بين الأسماء
 * @param bool $links عرض روابط للمؤلفين
 * @return string
 */
function nadiim_get_esdar_authors($post_id = null, $separator = '، ', $links = true) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta = nadiim_get_esdar_meta($post_id);

    if (!$meta || empty($meta['release_authors'])) {
        return '';
    }

    $authors = array();

    foreach ($meta['release_authors'] as $author) {
        $name = '';

        if ($author['type'] === 'user' && !empty($author['id'])) {
            $user = get_userdata($author['id']);
            if ($user) {
                $name = $user->display_name;
                if ($links) {
                    $name = '<a href="' . esc_url(get_author_posts_url($user->ID)) . '">' . esc_html($name) . '</a>';
                }
            }
        } elseif ($author['type'] === 'free' && !empty($author['name'])) {
            $name = esc_html($author['name']);
            if ($links && !empty($author['link'])) {
                $name = '<a href="' . esc_url($author['link']) . '" target="_blank" rel="noopener">' . $name . '</a>';
            }
        }

        if ($name) {
            $authors[] = $name;
        }
    }

    return implode($separator, $authors);
}

/**
 * الحصول على نوع الإصدار بصيغة قابلة للقراءة
 *
 * @param string $type نوع الإصدار
 * @return string
 */
function nadiim_get_esdar_type_label($type) {
    $types = array(
        'book' => __('كتاب', 'nadiim'),
        'magazine' => __('مجلة', 'nadiim'),
        'brochure' => __('كتيب', 'nadiim'),
        'report' => __('تقرير', 'nadiim'),
        'issue' => __('عدد', 'nadiim'),
    );

    return isset($types[$type]) ? $types[$type] : $type;
}

/**
 * الحصول على أيقونة نوع الإصدار
 *
 * @param string $type نوع الإصدار
 * @return string
 */
function nadiim_get_esdar_type_icon($type) {
    $icons = array(
        'book' => '<svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
        'magazine' => '<svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 7h10M7 12h10M7 17h10"/></svg>',
        'brochure' => '<svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
        'report' => '<svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>',
        'issue' => '<svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="18" rx="2"/><path d="M8 3v18M16 3v18"/></svg>',
    );

    return isset($icons[$type]) ? $icons[$type] : $icons['book'];
}

/**
 * عرض شارة تحميل مع صيغ متعددة
 *
 * @param int $post_id معرف المنشور
 * @return string
 */
function nadiim_get_esdar_format_badges($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta = nadiim_get_esdar_meta($post_id);

    if (!$meta || empty($meta['release_format'])) {
        return '';
    }

    $badges = array();

    foreach ($meta['release_format'] as $format) {
        $badges[] = '<span class="esdar-format-badge esdar-format-' . esc_attr($format) . '">' . esc_html(strtoupper($format)) . '</span>';
    }

    return '<div class="esdar-format-badges">' . implode('', $badges) . '</div>';
}

/**
 * التحقق من وجود معاينة للإصدار
 *
 * @param int $post_id معرف المنشور
 * @return bool
 */
function nadiim_esdar_has_preview($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta = nadiim_get_esdar_meta($post_id);

    if (!$meta) {
        return false;
    }

    return !empty($meta['release_preview_embed']) || !empty($meta['release_preview_images']);
}

/**
 * عرض صور معاينة الإصدار
 *
 * @param int $post_id معرف المنشور
 * @param string $size حجم الصورة
 * @return string
 */
function nadiim_get_esdar_preview_gallery($post_id = null, $size = 'large') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta = nadiim_get_esdar_meta($post_id);

    if (!$meta || empty($meta['release_preview_images'])) {
        return '';
    }

    $output = '<div class="esdar-preview-gallery">';

    foreach ($meta['release_preview_images'] as $image_id) {
        $image = wp_get_attachment_image($image_id, $size, false, array(
            'class' => 'esdar-preview-image',
            'loading' => 'lazy',
        ));

        if ($image) {
            $full_url = wp_get_attachment_image_url($image_id, 'full');
            $output .= '<a href="' . esc_url($full_url) . '" class="esdar-preview-link" data-lightbox="esdar-preview">';
            $output .= $image;
            $output .= '</a>';
        }
    }

    $output .= '</div>';

    return $output;
}

/**
 * AJAX handler لزيادة عداد التحميل
 */
function nadiim_ajax_increment_download_count() {
    check_ajax_referer('esdar_download_nonce', 'nonce');

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$post_id) {
        wp_send_json_error(array('message' => 'Invalid post ID'));
    }

    $result = nadiim_increment_esdar_download_count($post_id);

    if ($result) {
        wp_send_json_success(array('message' => 'Count incremented'));
    } else {
        wp_send_json_error(array('message' => 'Failed to increment count'));
    }
}
add_action('wp_ajax_increment_download_count', 'nadiim_ajax_increment_download_count');
add_action('wp_ajax_nopriv_increment_download_count', 'nadiim_ajax_increment_download_count');

/**
 * الحصول على إحصائيات الإصدارات حسب النوع
 *
 * @return array مصفوفة تحتوي على عدد الإصدارات لكل نوع
 */
function nadiim_get_esdar_type_counts() {
    // محاولة جلب البيانات من cache
    $cache_key = 'esdar_type_counts';
    $counts = wp_cache_get($cache_key);

    if (false !== $counts) {
        return $counts;
    }

    // تهيئة المصفوفة بالأنواع المعروفة
    $counts = array(
        'book' => 0,
        'magazine' => 0,
        'brochure' => 0,
        'report' => 0,
        'issue' => 0,
    );

    // جلب جميع الإصدارات وحساب الأنواع
    $query = new WP_Query(array(
        'post_type' => 'esdar',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'no_found_rows' => true,
    ));

    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            $meta = nadiim_get_esdar_meta($post_id);
            if ($meta && !empty($meta['release_type'])) {
                $type = $meta['release_type'];
                if (isset($counts[$type])) {
                    $counts[$type]++;
                }
            }
        }
    }

    // حفظ في cache لمدة ساعة
    wp_cache_set($cache_key, $counts, '', HOUR_IN_SECONDS);

    return $counts;
}

/**
 * الحصول على إحصائيات الإصدارات حسب السنة
 *
 * @return array مصفوفة تحتوي على عدد الإصدارات لكل سنة
 */
function nadiim_get_esdar_year_counts() {
    // محاولة جلب البيانات من cache
    $cache_key = 'esdar_year_counts';
    $counts = wp_cache_get($cache_key);

    if (false !== $counts) {
        return $counts;
    }

    $counts = array();

    // جلب جميع الإصدارات وحساب السنوات
    $query = new WP_Query(array(
        'post_type' => 'esdar',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'no_found_rows' => true,
    ));

    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            $meta = nadiim_get_esdar_meta($post_id);
            if ($meta && !empty($meta['release_date'])) {
                $year = date('Y', strtotime($meta['release_date']));
                if ($year) {
                    if (!isset($counts[$year])) {
                        $counts[$year] = 0;
                    }
                    $counts[$year]++;
                }
            }
        }
    }

    // حفظ في cache لمدة ساعة
    wp_cache_set($cache_key, $counts, '', HOUR_IN_SECONDS);

    return $counts;
}
