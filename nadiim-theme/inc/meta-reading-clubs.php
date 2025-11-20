<?php
/**
 * Meta Boxes for Reading Clubs
 *
 * @package Madjaliss
 */

// منع الوصول المباشر
if (!defined('ABSPATH')) {
    exit;
}

/**
 * إضافة Meta Box لنوادي القراءة
 */
function madjaliss_reading_clubs_add_meta_boxes() {
    add_meta_box(
        'reading_clubs_details',
        __('تفاصيل نادي القراءة', 'madjaliss'),
        'madjaliss_reading_clubs_meta_box_callback',
        'reading_clubs',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'madjaliss_reading_clubs_add_meta_boxes');

/**
 * عرض محتوى Meta Box
 */
function madjaliss_reading_clubs_meta_box_callback($post) {
    // إضافة nonce للأمان
    wp_nonce_field('madjaliss_reading_clubs_meta_box', 'madjaliss_reading_clubs_meta_box_nonce');

    // الحصول على البيانات المحفوظة
    $club_meta = get_post_meta($post->ID, 'club_meta', true);

    // إذا كانت البيانات فارغة، تهيئة المصفوفة
    if (!is_array($club_meta)) {
        $club_meta = array();
    }

    // القيم الافتراضية
    $defaults = array(
        'short_description' => '',
        'full_description' => '',
        'meeting_location' => array(
            'address' => '',
            'lat' => 0,
            'lng' => 0,
        ),
        'meeting_schedule_note' => '',
        'facebook_page' => '',
        'telegram_channel' => '',
        'website' => '',
        'contact_email' => '',
        'map_embed' => '',
        'visibility' => 'public',
    );

    $club_meta = wp_parse_args($club_meta, $defaults);

    // CSS مخصص للنموذج
    ?>
    <style>
        .club-meta-field {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            border-right: 3px solid #339063;
        }
        .club-meta-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #23282d;
            font-size: 14px;
        }
        .club-meta-field input[type="text"],
        .club-meta-field input[type="email"],
        .club-meta-field input[type="url"],
        .club-meta-field input[type="number"],
        .club-meta-field textarea,
        .club-meta-field select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .club-meta-field textarea {
            min-height: 100px;
            font-family: inherit;
        }
        .club-meta-field .description {
            margin-top: 5px;
            color: #666;
            font-size: 13px;
            font-style: italic;
        }
        .club-meta-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #339063;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #339063;
        }
        .club-meta-field-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media (max-width: 768px) {
            .club-meta-field-group {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="reading-clubs-meta-container">

        <!-- القسم الأول: الوصف -->
        <h3 class="club-meta-section-title">
            <span class="dashicons dashicons-edit"></span>
            <?php _e('الوصف والمحتوى', 'madjaliss'); ?>
        </h3>

        <div class="club-meta-field">
            <label for="club_short_description">
                <?php _e('وصف مختصر', 'madjaliss'); ?>
                <span style="color: red;">*</span>
            </label>
            <textarea
                id="club_short_description"
                name="club_meta[short_description]"
                rows="3"
                placeholder="<?php _e('وصف قصير يظهر في بطاقات النادي وصفحة الأرشيف', 'madjaliss'); ?>"
            ><?php echo esc_textarea($club_meta['short_description']); ?></textarea>
            <p class="description">
                <?php _e('وصف مختصر للنادي (2-3 أسطر) يظهر في صفحة الأرشيف', 'madjaliss'); ?>
            </p>
        </div>

        <div class="club-meta-field">
            <label for="club_full_description">
                <?php _e('وصف كامل ومفصل', 'madjaliss'); ?>
            </label>
            <?php
            wp_editor(
                $club_meta['full_description'],
                'club_full_description',
                array(
                    'textarea_name' => 'club_meta[full_description]',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                    'teeny' => false,
                    'tinymce' => array(
                        'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,|,undo,redo',
                    ),
                )
            );
            ?>
            <p class="description">
                <?php _e('وصف مفصل عن النادي، أهدافه، أنشطته، ونوع الكتب التي يناقشها', 'madjaliss'); ?>
            </p>
        </div>

        <!-- القسم الثاني: الموقع والخريطة -->
        <h3 class="club-meta-section-title">
            <span class="dashicons dashicons-location"></span>
            <?php _e('موقع الاجتماع', 'madjaliss'); ?>
        </h3>

        <div class="club-meta-field">
            <label for="club_meeting_address">
                <?php _e('عنوان موقع الاجتماع', 'madjaliss'); ?>
                <span style="color: red;">*</span>
            </label>
            <input
                type="text"
                id="club_meeting_address"
                name="club_meta[meeting_location][address]"
                value="<?php echo esc_attr($club_meta['meeting_location']['address']); ?>"
                placeholder="<?php _e('مثال: مكتبة المدينة – وسط الجزائر', 'madjaliss'); ?>"
            />
            <p class="description">
                <?php _e('العنوان الكامل لمكان اجتماع النادي', 'madjaliss'); ?>
            </p>
        </div>

        <div class="club-meta-field-group">
            <div class="club-meta-field">
                <label for="club_meeting_lat">
                    <?php _e('خط العرض (Latitude)', 'madjaliss'); ?>
                </label>
                <input
                    type="number"
                    step="any"
                    id="club_meeting_lat"
                    name="club_meta[meeting_location][lat]"
                    value="<?php echo esc_attr($club_meta['meeting_location']['lat']); ?>"
                    placeholder="36.7538"
                />
                <p class="description">
                    <?php _e('مثال: 36.7538', 'madjaliss'); ?>
                </p>
            </div>

            <div class="club-meta-field">
                <label for="club_meeting_lng">
                    <?php _e('خط الطول (Longitude)', 'madjaliss'); ?>
                </label>
                <input
                    type="number"
                    step="any"
                    id="club_meeting_lng"
                    name="club_meta[meeting_location][lng]"
                    value="<?php echo esc_attr($club_meta['meeting_location']['lng']); ?>"
                    placeholder="3.0588"
                />
                <p class="description">
                    <?php _e('مثال: 3.0588', 'madjaliss'); ?>
                </p>
            </div>
        </div>

        <div class="club-meta-field">
            <label for="club_map_embed">
                <?php _e('كود iframe للخريطة (اختياري)', 'madjaliss'); ?>
            </label>
            <textarea
                id="club_map_embed"
                name="club_meta[map_embed]"
                rows="4"
                placeholder="<?php _e('الصق هنا كود iframe الكامل من Google Maps أو خدمة خرائط أخرى', 'madjaliss'); ?>"
            ><?php echo esc_textarea($club_meta['map_embed']); ?></textarea>
            <p class="description">
                <?php _e('إذا أردت استخدام خريطة جاهزة من Google Maps أو غيرها، الصق الكود هنا', 'madjaliss'); ?>
            </p>
        </div>

        <!-- القسم الثالث: جدول اللقاءات -->
        <h3 class="club-meta-section-title">
            <span class="dashicons dashicons-calendar-alt"></span>
            <?php _e('مواعيد الاجتماعات', 'madjaliss'); ?>
        </h3>

        <div class="club-meta-field">
            <label for="club_meeting_schedule">
                <?php _e('ملاحظة جدول اللقاءات', 'madjaliss'); ?>
            </label>
            <textarea
                id="club_meeting_schedule"
                name="club_meta[meeting_schedule_note]"
                rows="3"
                placeholder="<?php _e('مثال: نلتقي كل سبت على الساعة 18:00 مساءً', 'madjaliss'); ?>"
            ><?php echo esc_textarea($club_meta['meeting_schedule_note']); ?></textarea>
            <p class="description">
                <?php _e('وصف مواعيد وتكرار اجتماعات النادي', 'madjaliss'); ?>
            </p>
        </div>

        <!-- القسم الرابع: روابط التواصل -->
        <h3 class="club-meta-section-title">
            <span class="dashicons dashicons-share"></span>
            <?php _e('روابط التواصل الاجتماعي', 'madjaliss'); ?>
        </h3>

        <div class="club-meta-field">
            <label for="club_facebook_page">
                <span class="dashicons dashicons-facebook"></span>
                <?php _e('رابط صفحة فيسبوك', 'madjaliss'); ?>
            </label>
            <input
                type="url"
                id="club_facebook_page"
                name="club_meta[facebook_page]"
                value="<?php echo esc_url($club_meta['facebook_page']); ?>"
                placeholder="https://facebook.com/your-club"
            />
        </div>

        <div class="club-meta-field">
            <label for="club_telegram_channel">
                <span class="dashicons dashicons-phone"></span>
                <?php _e('رابط قناة تيليجرام', 'madjaliss'); ?>
            </label>
            <input
                type="url"
                id="club_telegram_channel"
                name="club_meta[telegram_channel]"
                value="<?php echo esc_url($club_meta['telegram_channel']); ?>"
                placeholder="https://t.me/your-club"
            />
        </div>

        <div class="club-meta-field">
            <label for="club_website">
                <span class="dashicons dashicons-admin-site"></span>
                <?php _e('الموقع الإلكتروني', 'madjaliss'); ?>
            </label>
            <input
                type="url"
                id="club_website"
                name="club_meta[website]"
                value="<?php echo esc_url($club_meta['website']); ?>"
                placeholder="https://example.com"
            />
        </div>

        <div class="club-meta-field">
            <label for="club_contact_email">
                <span class="dashicons dashicons-email"></span>
                <?php _e('البريد الإلكتروني للتواصل', 'madjaliss'); ?>
            </label>
            <input
                type="email"
                id="club_contact_email"
                name="club_meta[contact_email]"
                value="<?php echo esc_attr($club_meta['contact_email']); ?>"
                placeholder="info@example.com"
            />
        </div>

        <!-- القسم الخامس: الإعدادات -->
        <h3 class="club-meta-section-title">
            <span class="dashicons dashicons-admin-settings"></span>
            <?php _e('إعدادات الظهور', 'madjaliss'); ?>
        </h3>

        <div class="club-meta-field">
            <label for="club_visibility">
                <?php _e('حالة الظهور', 'madjaliss'); ?>
            </label>
            <select id="club_visibility" name="club_meta[visibility]">
                <option value="public" <?php selected($club_meta['visibility'], 'public'); ?>>
                    <?php _e('عام - يظهر للجميع', 'madjaliss'); ?>
                </option>
                <option value="private" <?php selected($club_meta['visibility'], 'private'); ?>>
                    <?php _e('خاص - مخفي', 'madjaliss'); ?>
                </option>
            </select>
            <p class="description">
                <?php _e('هل يظهر هذا النادي في صفحة الأرشيف؟', 'madjaliss'); ?>
            </p>
        </div>

    </div>
    <?php
}

/**
 * حفظ البيانات
 */
function madjaliss_reading_clubs_save_meta_box($post_id) {
    // التحقق من nonce
    if (!isset($_POST['madjaliss_reading_clubs_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['madjaliss_reading_clubs_meta_box_nonce'], 'madjaliss_reading_clubs_meta_box')) {
        return;
    }

    // التحقق من autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // التحقق من الصلاحيات
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // التحقق من نوع المنشور
    if (get_post_type($post_id) !== 'reading_clubs') {
        return;
    }

    // الحصول على البيانات وتنظيفها
    if (isset($_POST['club_meta']) && is_array($_POST['club_meta'])) {
        $club_meta = $_POST['club_meta'];

        // تنظيف البيانات
        $sanitized_data = array(
            'short_description' => sanitize_textarea_field($club_meta['short_description']),
            'full_description' => wp_kses_post($club_meta['full_description']),
            'meeting_location' => array(
                'address' => sanitize_text_field($club_meta['meeting_location']['address']),
                'lat' => floatval($club_meta['meeting_location']['lat']),
                'lng' => floatval($club_meta['meeting_location']['lng']),
            ),
            'meeting_schedule_note' => sanitize_textarea_field($club_meta['meeting_schedule_note']),
            'facebook_page' => esc_url_raw($club_meta['facebook_page']),
            'telegram_channel' => esc_url_raw($club_meta['telegram_channel']),
            'website' => esc_url_raw($club_meta['website']),
            'contact_email' => sanitize_email($club_meta['contact_email']),
            'map_embed' => wp_kses($club_meta['map_embed'], array(
                'iframe' => array(
                    'src' => true,
                    'width' => true,
                    'height' => true,
                    'frameborder' => true,
                    'allowfullscreen' => true,
                    'style' => true,
                    'loading' => true,
                ),
            )),
            'visibility' => sanitize_text_field($club_meta['visibility']),
        );

        // حفظ البيانات
        update_post_meta($post_id, 'club_meta', $sanitized_data);
    }
}
add_action('save_post', 'madjaliss_reading_clubs_save_meta_box');

/**
 * إضافة أنماط مخصصة للإدارة
 */
function madjaliss_reading_clubs_admin_styles() {
    $screen = get_current_screen();

    if ($screen && $screen->post_type === 'reading_clubs') {
        ?>
        <style>
            /* تحسين شكل جدول النوادي */
            .column-thumbnail {
                width: 60px;
            }
            .column-location {
                width: 25%;
            }
            .column-social_links {
                width: 100px;
                text-align: center;
            }
            .column-visibility {
                width: 100px;
            }

            /* تحسين شكل الأيقونات */
            .column-social_links a {
                display: inline-block;
                margin: 0 3px;
                color: #339063;
                text-decoration: none;
            }
            .column-social_links a:hover {
                color: #2d7a56;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'madjaliss_reading_clubs_admin_styles');
