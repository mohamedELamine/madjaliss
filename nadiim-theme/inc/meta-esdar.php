<?php
/**
 * ميتا بوكس للإصدارات (Releases Meta Box)
 *
 * يدير حقول البيانات المخصصة للإصدارات:
 * - تاريخ الإصدار
 * - نوع الإصدار
 * - المؤلفون (repeatable)
 * - ملف الإصدار
 * - ISBN، الصفحات، اللغة، السعر
 * - صور المعاينة
 *
 * @package Nadiim
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

/**
 * إضافة ميتا بوكس بيانات الإصدار
 */
function nadiim_add_esdar_meta_box() {
    add_meta_box(
        'esdar_meta_box',
        __('بيانات الإصدار', 'nadiim'),
        'nadiim_render_esdar_meta_box',
        'esdar',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nadiim_add_esdar_meta_box');

/**
 * عرض محتوى ميتا بوكس الإصدار
 */
function nadiim_render_esdar_meta_box($post) {
    // Nonce للأمان
    wp_nonce_field('nadiim_esdar_meta_nonce', 'esdar_meta_nonce');

    // جلب البيانات الحالية
    $meta_json = get_post_meta($post->ID, 'esdar_meta', true);
    $meta = $meta_json ? json_decode($meta_json, true) : array();

    // القيم الافتراضية
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

    $data = wp_parse_args($meta, $defaults);
    ?>

    <div class="esdar-meta-box-wrapper">

        <!-- القسم الأول: المعلومات الأساسية -->
        <div class="esdar-section esdar-section-basic">
            <h3 class="esdar-section-title"><?php _e('المعلومات الأساسية', 'nadiim'); ?></h3>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-half">
                    <label for="esdar_release_date">
                        <?php _e('تاريخ الإصدار', 'nadiim'); ?>
                        <span class="required">*</span>
                    </label>
                    <input
                        type="date"
                        id="esdar_release_date"
                        name="esdar_release_date"
                        value="<?php echo esc_attr($data['release_date']); ?>"
                        class="esdar-input"
                    />
                </div>

                <div class="esdar-field esdar-field-half">
                    <label for="esdar_release_type">
                        <?php _e('نوع الإصدار', 'nadiim'); ?>
                        <span class="required">*</span>
                    </label>
                    <select id="esdar_release_type" name="esdar_release_type" class="esdar-select">
                        <option value="book" <?php selected($data['release_type'], 'book'); ?>><?php _e('كتاب', 'nadiim'); ?></option>
                        <option value="magazine" <?php selected($data['release_type'], 'magazine'); ?>><?php _e('مجلة', 'nadiim'); ?></option>
                        <option value="brochure" <?php selected($data['release_type'], 'brochure'); ?>><?php _e('كتيب', 'nadiim'); ?></option>
                        <option value="report" <?php selected($data['release_type'], 'report'); ?>><?php _e('تقرير', 'nadiim'); ?></option>
                        <option value="issue" <?php selected($data['release_type'], 'issue'); ?>><?php _e('عدد', 'nadiim'); ?></option>
                    </select>
                </div>
            </div>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-half">
                    <label for="esdar_release_language">
                        <?php _e('اللغة', 'nadiim'); ?>
                    </label>
                    <select id="esdar_release_language" name="esdar_release_language" class="esdar-select">
                        <option value="ar" <?php selected($data['release_language'], 'ar'); ?>><?php _e('العربية', 'nadiim'); ?></option>
                        <option value="en" <?php selected($data['release_language'], 'en'); ?>><?php _e('الإنجليزية', 'nadiim'); ?></option>
                        <option value="fr" <?php selected($data['release_language'], 'fr'); ?>><?php _e('الفرنسية', 'nadiim'); ?></option>
                        <option value="other" <?php selected($data['release_language'], 'other'); ?>><?php _e('أخرى', 'nadiim'); ?></option>
                    </select>
                </div>

                <div class="esdar-field esdar-field-half">
                    <label for="esdar_release_pages">
                        <?php _e('عدد الصفحات', 'nadiim'); ?>
                    </label>
                    <input
                        type="number"
                        id="esdar_release_pages"
                        name="esdar_release_pages"
                        value="<?php echo esc_attr($data['release_pages']); ?>"
                        min="1"
                        class="esdar-input"
                    />
                </div>
            </div>
        </div>

        <!-- القسم الثاني: المؤلفون -->
        <div class="esdar-section esdar-section-authors">
            <h3 class="esdar-section-title"><?php _e('المؤلفون', 'nadiim'); ?></h3>

            <div id="esdar-authors-container">
                <?php
                if (!empty($data['release_authors']) && is_array($data['release_authors'])) {
                    foreach ($data['release_authors'] as $index => $author) {
                        nadiim_render_author_row($index, $author);
                    }
                } else {
                    // صف افتراضي واحد
                    nadiim_render_author_row(0, array());
                }
                ?>
            </div>

            <button type="button" id="esdar-add-author" class="button button-secondary">
                <span class="dashicons dashicons-plus-alt"></span>
                <?php _e('إضافة مؤلف', 'nadiim'); ?>
            </button>
        </div>

        <!-- القسم الثالث: ملفات الإصدار -->
        <div class="esdar-section esdar-section-files">
            <h3 class="esdar-section-title"><?php _e('ملف الإصدار', 'nadiim'); ?></h3>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-full">
                    <label for="esdar_release_file">
                        <?php _e('ملف التحميل (PDF, EPUB, MOBI)', 'nadiim'); ?>
                    </label>

                    <div class="esdar-file-upload">
                        <input
                            type="hidden"
                            id="esdar_release_file_id"
                            name="esdar_release_file_id"
                            value="<?php echo esc_attr($data['release_file_id']); ?>"
                        />
                        <input
                            type="text"
                            id="esdar_release_file_url"
                            name="esdar_release_file_url"
                            value="<?php echo esc_url($data['release_file_url']); ?>"
                            placeholder="<?php _e('رابط الملف...', 'nadiim'); ?>"
                            class="esdar-input"
                            readonly
                        />
                        <button type="button" class="button button-primary esdar-upload-file" data-target="release_file">
                            <span class="dashicons dashicons-upload"></span>
                            <?php _e('رفع ملف', 'nadiim'); ?>
                        </button>
                        <button type="button" class="button button-secondary esdar-remove-file" data-target="release_file">
                            <span class="dashicons dashicons-no"></span>
                            <?php _e('إزالة', 'nadiim'); ?>
                        </button>
                    </div>

                    <?php if (!empty($data['release_file_id'])):
                        $file = get_attached_file($data['release_file_id']);
                        if ($file):
                    ?>
                        <div class="esdar-file-info">
                            <span class="dashicons dashicons-media-document"></span>
                            <span><?php echo esc_html(basename($file)); ?></span>
                            <span class="file-size">(<?php echo size_format(filesize($file)); ?>)</span>
                        </div>
                    <?php
                        endif;
                    endif;
                    ?>
                </div>
            </div>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-full">
                    <label><?php _e('صيغ الملف المتوفرة', 'nadiim'); ?></label>
                    <div class="esdar-checkbox-group">
                        <?php
                        $formats = array('pdf' => 'PDF', 'epub' => 'EPUB', 'mobi' => 'MOBI', 'azw' => 'AZW');
                        foreach ($formats as $key => $label) {
                            $checked = in_array($key, (array)$data['release_format']) ? 'checked' : '';
                            ?>
                            <label class="esdar-checkbox-label">
                                <input
                                    type="checkbox"
                                    name="esdar_release_format[]"
                                    value="<?php echo esc_attr($key); ?>"
                                    <?php echo $checked; ?>
                                />
                                <span><?php echo esc_html($label); ?></span>
                            </label>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- القسم الرابع: معلومات إضافية -->
        <div class="esdar-section esdar-section-additional">
            <h3 class="esdar-section-title"><?php _e('معلومات إضافية', 'nadiim'); ?></h3>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-half">
                    <label for="esdar_release_isbn">
                        <?php _e('ISBN', 'nadiim'); ?>
                    </label>
                    <input
                        type="text"
                        id="esdar_release_isbn"
                        name="esdar_release_isbn"
                        value="<?php echo esc_attr($data['release_isbn']); ?>"
                        placeholder="978-1-234-56789-0"
                        class="esdar-input"
                    />
                </div>

                <div class="esdar-field esdar-field-half">
                    <label for="esdar_release_price">
                        <?php _e('السعر (اختياري)', 'nadiim'); ?>
                    </label>
                    <input
                        type="text"
                        id="esdar_release_price"
                        name="esdar_release_price"
                        value="<?php echo esc_attr($data['release_price']); ?>"
                        placeholder="<?php _e('مجاني', 'nadiim'); ?>"
                        class="esdar-input"
                    />
                </div>
            </div>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-full">
                    <label for="esdar_release_excerpt">
                        <?php _e('مقتطف قصير', 'nadiim'); ?>
                    </label>
                    <textarea
                        id="esdar_release_excerpt"
                        name="esdar_release_excerpt"
                        rows="3"
                        class="esdar-textarea"
                        placeholder="<?php _e('نص قصير يصف الإصدار...', 'nadiim'); ?>"
                    ><?php echo esc_textarea($data['release_excerpt']); ?></textarea>
                </div>
            </div>

            <div class="esdar-row">
                <div class="esdar-field esdar-field-full">
                    <label for="esdar_release_preview_embed">
                        <?php _e('رابط المعاينة المضمنة (Embed)', 'nadiim'); ?>
                    </label>
                    <input
                        type="url"
                        id="esdar_release_preview_embed"
                        name="esdar_release_preview_embed"
                        value="<?php echo esc_url($data['release_preview_embed']); ?>"
                        placeholder="https://issuu.com/.../embed أو Google Viewer"
                        class="esdar-input"
                    />
                    <p class="description"><?php _e('رابط لمعاينة الإصدار (Google Viewer, Issuu, PDF.js, إلخ)', 'nadiim'); ?></p>
                </div>
            </div>
        </div>

        <!-- القسم الخامس: صور المعاينة -->
        <div class="esdar-section esdar-section-preview">
            <h3 class="esdar-section-title"><?php _e('صور المعاينة', 'nadiim'); ?></h3>

            <div id="esdar-preview-images-container" class="esdar-gallery-container">
                <?php
                if (!empty($data['release_preview_images']) && is_array($data['release_preview_images'])) {
                    foreach ($data['release_preview_images'] as $img_id) {
                        $img_url = wp_get_attachment_image_url($img_id, 'thumbnail');
                        if ($img_url) {
                            ?>
                            <div class="esdar-gallery-item" data-id="<?php echo esc_attr($img_id); ?>">
                                <img src="<?php echo esc_url($img_url); ?>" alt="" />
                                <button type="button" class="esdar-remove-image" data-id="<?php echo esc_attr($img_id); ?>">
                                    <span class="dashicons dashicons-no-alt"></span>
                                </button>
                                <input type="hidden" name="esdar_release_preview_images[]" value="<?php echo esc_attr($img_id); ?>" />
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>

            <button type="button" id="esdar-add-preview-images" class="button button-secondary">
                <span class="dashicons dashicons-format-gallery"></span>
                <?php _e('إضافة صور معاينة', 'nadiim'); ?>
            </button>
        </div>

        <!-- القسم السادس: الإحصائيات -->
        <div class="esdar-section esdar-section-stats">
            <h3 class="esdar-section-title"><?php _e('الإحصائيات', 'nadiim'); ?></h3>

            <div class="esdar-stats-box">
                <div class="esdar-stat-item">
                    <span class="esdar-stat-label"><?php _e('عدد التحميلات:', 'nadiim'); ?></span>
                    <span class="esdar-stat-value"><?php echo number_format_i18n($data['release_download_count']); ?></span>
                </div>
            </div>

            <input type="hidden" name="esdar_release_download_count" value="<?php echo esc_attr($data['release_download_count']); ?>" />
        </div>

    </div>

    <style>
        .esdar-meta-box-wrapper {
            padding: 10px 0;
        }
        .esdar-section {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .esdar-section-title {
            margin: 0 0 15px 0;
            padding: 0 0 10px 0;
            border-bottom: 2px solid #339063;
            color: #1C2D27;
            font-size: 16px;
            font-weight: 600;
        }
        .esdar-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .esdar-field {
            display: flex;
            flex-direction: column;
        }
        .esdar-field-full {
            flex: 1;
        }
        .esdar-field-half {
            flex: 1;
        }
        .esdar-field label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #1C2D27;
        }
        .esdar-field .required {
            color: #d63638;
        }
        .esdar-input,
        .esdar-select,
        .esdar-textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .esdar-input:focus,
        .esdar-select:focus,
        .esdar-textarea:focus {
            border-color: #339063;
            outline: none;
            box-shadow: 0 0 0 1px #339063;
        }

        /* Authors Section */
        .esdar-author-row {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
        }
        .esdar-author-row .esdar-row {
            margin-bottom: 10px;
        }
        .esdar-author-row .esdar-row:last-child {
            margin-bottom: 0;
        }
        .esdar-remove-author {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 8px;
            font-size: 12px;
        }

        /* File Upload */
        .esdar-file-upload {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .esdar-file-upload input[type="text"] {
            flex: 1;
        }
        .esdar-file-info {
            margin-top: 8px;
            padding: 8px 12px;
            background: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        .esdar-file-info .file-size {
            color: #666;
        }

        /* Checkbox Group */
        .esdar-checkbox-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .esdar-checkbox-label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        /* Gallery */
        .esdar-gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        .esdar-gallery-item {
            position: relative;
            aspect-ratio: 1;
            border: 2px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        .esdar-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .esdar-remove-image {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(214, 54, 56, 0.9);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .esdar-remove-image:hover {
            background: #d63638;
        }

        /* Stats */
        .esdar-stats-box {
            background: #f7f7f7;
            border-radius: 6px;
            padding: 15px;
        }
        .esdar-stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .esdar-stat-label {
            font-weight: 600;
            color: #666;
        }
        .esdar-stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #339063;
        }
    </style>
    <?php
}

/**
 * عرض صف مؤلف واحد
 */
function nadiim_render_author_row($index, $author = array()) {
    $defaults = array(
        'type' => 'free',
        'id' => '',
        'name' => '',
        'link' => '',
    );
    $author = wp_parse_args($author, $defaults);
    ?>
    <div class="esdar-author-row" data-index="<?php echo esc_attr($index); ?>">
        <?php if ($index > 0): ?>
            <button type="button" class="button button-small esdar-remove-author">
                <span class="dashicons dashicons-trash"></span>
            </button>
        <?php endif; ?>

        <div class="esdar-row">
            <div class="esdar-field esdar-field-half">
                <label><?php _e('نوع المؤلف', 'nadiim'); ?></label>
                <select name="esdar_authors[<?php echo esc_attr($index); ?>][type]" class="esdar-select esdar-author-type">
                    <option value="free" <?php selected($author['type'], 'free'); ?>><?php _e('اسم حر', 'nadiim'); ?></option>
                    <option value="user" <?php selected($author['type'], 'user'); ?>><?php _e('مستخدم مسجل', 'nadiim'); ?></option>
                </select>
            </div>

            <div class="esdar-field esdar-field-half esdar-author-user-field" style="<?php echo $author['type'] === 'user' ? '' : 'display:none;'; ?>">
                <label><?php _e('المستخدم', 'nadiim'); ?></label>
                <select name="esdar_authors[<?php echo esc_attr($index); ?>][id]" class="esdar-select esdar-user-select">
                    <option value=""><?php _e('-- اختر مستخدم --', 'nadiim'); ?></option>
                    <?php
                    $users = get_users(array('orderby' => 'display_name'));
                    foreach ($users as $user) {
                        $selected = ($author['type'] === 'user' && $author['id'] == $user->ID) ? 'selected' : '';
                        echo '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($user->display_name) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="esdar-field esdar-field-half esdar-author-free-field" style="<?php echo $author['type'] === 'free' ? '' : 'display:none;'; ?>">
                <label><?php _e('اسم المؤلف', 'nadiim'); ?></label>
                <input
                    type="text"
                    name="esdar_authors[<?php echo esc_attr($index); ?>][name]"
                    value="<?php echo esc_attr($author['name']); ?>"
                    class="esdar-input"
                    placeholder="<?php _e('اسم المؤلف...', 'nadiim'); ?>"
                />
            </div>
        </div>

        <div class="esdar-row esdar-author-link-field" style="<?php echo $author['type'] === 'free' ? '' : 'display:none;'; ?>">
            <div class="esdar-field esdar-field-full">
                <label><?php _e('رابط المؤلف (اختياري)', 'nadiim'); ?></label>
                <input
                    type="url"
                    name="esdar_authors[<?php echo esc_attr($index); ?>][link]"
                    value="<?php echo esc_url($author['link']); ?>"
                    class="esdar-input"
                    placeholder="https://..."
                />
            </div>
        </div>
    </div>
    <?php
}

/**
 * حفظ بيانات ميتا بوكس الإصدار
 */
function nadiim_save_esdar_meta($post_id) {
    // التحقق من nonce
    if (!isset($_POST['esdar_meta_nonce']) || !wp_verify_nonce($_POST['esdar_meta_nonce'], 'nadiim_esdar_meta_nonce')) {
        return;
    }

    // التحقق من الحفظ التلقائي
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // التحقق من الصلاحيات
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // جمع البيانات وتنظيفها
    $meta_data = array();

    // تاريخ الإصدار
    $meta_data['release_date'] = isset($_POST['esdar_release_date']) ? sanitize_text_field($_POST['esdar_release_date']) : '';

    // نوع الإصدار
    $meta_data['release_type'] = isset($_POST['esdar_release_type']) ? sanitize_text_field($_POST['esdar_release_type']) : 'book';

    // المؤلفون
    $authors = array();
    if (isset($_POST['esdar_authors']) && is_array($_POST['esdar_authors'])) {
        foreach ($_POST['esdar_authors'] as $author) {
            $clean_author = array(
                'type' => sanitize_text_field($author['type']),
                'id' => isset($author['id']) ? intval($author['id']) : 0,
                'name' => isset($author['name']) ? sanitize_text_field($author['name']) : '',
                'link' => isset($author['link']) ? esc_url_raw($author['link']) : '',
            );

            // التأكد من وجود اسم أو ID
            if (!empty($clean_author['name']) || !empty($clean_author['id'])) {
                $authors[] = $clean_author;
            }
        }
    }
    $meta_data['release_authors'] = $authors;

    // ملف الإصدار
    $meta_data['release_file_id'] = isset($_POST['esdar_release_file_id']) ? intval($_POST['esdar_release_file_id']) : '';
    $meta_data['release_file_url'] = isset($_POST['esdar_release_file_url']) ? esc_url_raw($_POST['esdar_release_file_url']) : '';

    // صيغ الملف
    $meta_data['release_format'] = isset($_POST['esdar_release_format']) && is_array($_POST['esdar_release_format'])
        ? array_map('sanitize_text_field', $_POST['esdar_release_format'])
        : array('pdf');

    // معلومات إضافية
    $meta_data['release_isbn'] = isset($_POST['esdar_release_isbn']) ? sanitize_text_field($_POST['esdar_release_isbn']) : '';
    $meta_data['release_pages'] = isset($_POST['esdar_release_pages']) ? intval($_POST['esdar_release_pages']) : '';
    $meta_data['release_language'] = isset($_POST['esdar_release_language']) ? sanitize_text_field($_POST['esdar_release_language']) : 'ar';
    $meta_data['release_price'] = isset($_POST['esdar_release_price']) ? sanitize_text_field($_POST['esdar_release_price']) : '';
    $meta_data['release_excerpt'] = isset($_POST['esdar_release_excerpt']) ? sanitize_textarea_field($_POST['esdar_release_excerpt']) : '';
    $meta_data['release_preview_embed'] = isset($_POST['esdar_release_preview_embed']) ? esc_url_raw($_POST['esdar_release_preview_embed']) : '';

    // صور المعاينة
    $preview_images = array();
    if (isset($_POST['esdar_release_preview_images']) && is_array($_POST['esdar_release_preview_images'])) {
        $preview_images = array_map('intval', $_POST['esdar_release_preview_images']);
    }
    $meta_data['release_preview_images'] = $preview_images;

    // عداد التحميلات (نحافظ على القيمة الحالية)
    $existing_meta = get_post_meta($post_id, 'esdar_meta', true);
    $existing_data = $existing_meta ? json_decode($existing_meta, true) : array();
    $meta_data['release_download_count'] = isset($existing_data['release_download_count']) ? intval($existing_data['release_download_count']) : 0;

    // حفظ البيانات كـ JSON
    update_post_meta($post_id, 'esdar_meta', wp_json_encode($meta_data, JSON_UNESCAPED_UNICODE));
}
add_action('save_post_esdar', 'nadiim_save_esdar_meta');

/**
 * تحميل سكربتات وأنماط الإدارة
 */
function nadiim_esdar_admin_enqueue_scripts($hook) {
    global $post_type;

    if (('post.php' === $hook || 'post-new.php' === $hook) && 'esdar' === $post_type) {
        // تحميل wp.media
        wp_enqueue_media();

        // تحميل سكربت الإدارة
        wp_enqueue_script(
            'esdar-admin',
            get_template_directory_uri() . '/assets/js/esdar-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // تمرير بيانات للـ JS
        wp_localize_script('esdar-admin', 'esdarAdmin', array(
            'authorRowTemplate' => nadiim_get_author_row_template(),
            'strings' => array(
                'selectFile' => __('اختر ملف', 'nadiim'),
                'useFile' => __('استخدام هذا الملف', 'nadiim'),
                'selectImages' => __('اختر صور', 'nadiim'),
                'addImages' => __('إضافة الصور المختارة', 'nadiim'),
            ),
        ));
    }
}
add_action('admin_enqueue_scripts', 'nadiim_esdar_admin_enqueue_scripts');

/**
 * الحصول على قالب صف المؤلف للـ JS
 */
function nadiim_get_author_row_template() {
    ob_start();
    nadiim_render_author_row('{{INDEX}}', array());
    return ob_get_clean();
}
