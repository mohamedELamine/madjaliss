<?php
/**
 * نظام Meta Box للمقالات (Posts)
 * يوفر حقول إضافية: وقت القراءة، ملف صوتي، خيارات العرض
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة Meta Box للمقالات
 */
function nadiim_add_article_meta_box() {
    add_meta_box(
        'nadiim_article_meta',
        'إعدادات المقال الإضافية',
        'nadiim_article_meta_box_callback',
        'post',
        'normal',
        'high'
    );

    // Meta Box لاختيار الكاتب (للأدمن فقط)
    if ( current_user_can( 'edit_others_posts' ) ) {
        add_meta_box(
            'nadiim_article_author',
            'كاتب المقال',
            'nadiim_article_author_box_callback',
            'post',
            'side',
            'default'
        );
    }
}
add_action( 'add_meta_boxes', 'nadiim_add_article_meta_box' );

/**
 * عرض Meta Box لاختيار الكاتب
 */
function nadiim_article_author_box_callback( $post ) {
    // إضافة nonce للأمان
    wp_nonce_field( 'nadiim_save_article_author', 'nadiim_article_author_nonce' );

    $current_author_id = $post->post_author;
    ?>
    <div class="nadiim-author-box">
        <style>
            .nadiim-author-box {
                padding: 10px 0;
            }
            .nadiim-author-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
            }
            .nadiim-author-box select {
                width: 100%;
                padding: 6px 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .nadiim-author-box .description {
                margin-top: 8px;
                color: #646970;
                font-size: 13px;
                font-style: italic;
            }
            .nadiim-current-author {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px;
                background: #f9f9f9;
                border-radius: 4px;
                margin-bottom: 15px;
            }
            .nadiim-current-author img {
                border-radius: 50%;
            }
            .nadiim-author-info {
                flex: 1;
            }
            .nadiim-author-name {
                font-weight: 600;
                color: #1d2327;
            }
            .nadiim-author-email {
                font-size: 12px;
                color: #646970;
            }
        </style>

        <div class="nadiim-current-author">
            <?php echo get_avatar( $current_author_id, 40 ); ?>
            <div class="nadiim-author-info">
                <div class="nadiim-author-name">
                    <?php echo esc_html( get_the_author_meta( 'display_name', $current_author_id ) ); ?>
                </div>
                <div class="nadiim-author-email">
                    <?php echo esc_html( get_the_author_meta( 'user_email', $current_author_id ) ); ?>
                </div>
            </div>
        </div>

        <label for="nadiim_post_author">اختر الكاتب:</label>
        <?php
        wp_dropdown_users( array(
            'name'             => 'nadiim_post_author',
            'id'               => 'nadiim_post_author',
            'selected'         => $current_author_id,
            'include_selected' => true,
            'show_option_none' => '-- اختر كاتب --',
            'capability'       => array( 'edit_posts' ),
        ) );
        ?>

        <p class="description">
            يمكنك تغيير كاتب هذا المقال. سيظهر المقال في صفحة الكاتب المختار.
        </p>
    </div>
    <?php
}

/**
 * عرض محتوى Meta Box
 */
function nadiim_article_meta_box_callback( $post ) {
    // إضافة nonce للأمان
    wp_nonce_field( 'nadiim_save_article_meta', 'nadiim_article_meta_nonce' );

    // الحصول على البيانات المحفوظة
    $article_meta = get_post_meta( $post->ID, 'article_meta', true );

    // تعيين القيم الافتراضية
    $defaults = array(
        'reading_time_manual' => '',
        'reading_time_auto' => '',
        'audio_attachment_id' => '',
        'audio_url' => '',
        'audio_caption' => '',
        'show_reading_time' => true,
        'show_audio_player' => true,
    );

    $article_meta = wp_parse_args( $article_meta, $defaults );

    // حساب وقت القراءة التلقائي
    $word_count = nadiim_get_post_word_count( $post->ID );
    $auto_reading_time = ceil( $word_count / 200 ); // 200 كلمة/دقيقة
    $article_meta['reading_time_auto'] = $auto_reading_time;

    ?>
    <div class="nadiim-article-meta-box">
        <style>
            .nadiim-article-meta-box {
                padding: 10px 0;
            }
            .nadiim-meta-field {
                margin-bottom: 20px;
                padding: 15px;
                background: #f9f9f9;
                border-radius: 4px;
            }
            .nadiim-meta-field label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
                color: #1d2327;
            }
            .nadiim-meta-field input[type="text"],
            .nadiim-meta-field input[type="number"],
            .nadiim-meta-field textarea {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
            }
            .nadiim-meta-field textarea {
                min-height: 60px;
                resize: vertical;
            }
            .nadiim-meta-field .description {
                margin-top: 6px;
                color: #646970;
                font-size: 13px;
                font-style: italic;
            }
            .nadiim-meta-actions {
                display: flex;
                gap: 10px;
                margin-top: 10px;
                flex-wrap: wrap;
            }
            .nadiim-meta-btn {
                padding: 8px 16px;
                background: #2271b1;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 13px;
                transition: background 0.2s;
            }
            .nadiim-meta-btn:hover {
                background: #135e96;
            }
            .nadiim-meta-btn.secondary {
                background: #339063;
            }
            .nadiim-meta-btn.secondary:hover {
                background: #2a7850;
            }
            .nadiim-toggle-field {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .nadiim-toggle-field input[type="checkbox"] {
                width: 20px;
                height: 20px;
                cursor: pointer;
            }
            .nadiim-audio-preview {
                margin-top: 10px;
                padding: 10px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                display: none;
            }
            .nadiim-audio-preview.active {
                display: block;
            }
            .nadiim-audio-info {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-top: 10px;
            }
            .nadiim-audio-info .dashicons {
                color: #339063;
                font-size: 24px;
            }
            .nadiim-reading-stats {
                display: flex;
                gap: 20px;
                padding: 10px;
                background: #fff;
                border-radius: 4px;
                border: 1px solid #ddd;
                margin-bottom: 10px;
            }
            .nadiim-stat-item {
                flex: 1;
            }
            .nadiim-stat-label {
                font-size: 12px;
                color: #646970;
                margin-bottom: 4px;
            }
            .nadiim-stat-value {
                font-size: 18px;
                font-weight: 600;
                color: #339063;
            }
        </style>

        <!-- الملف الصوتي -->
        <div class="nadiim-meta-field">
            <label>الملف الصوتي للمقال (اختياري)</label>

            <input
                type="hidden"
                id="nadiim_audio_attachment_id"
                name="article_meta[audio_attachment_id]"
                value="<?php echo esc_attr( $article_meta['audio_attachment_id'] ); ?>"
            >

            <input
                type="url"
                id="nadiim_audio_url"
                name="article_meta[audio_url]"
                value="<?php echo esc_attr( $article_meta['audio_url'] ); ?>"
                placeholder="https://example.com/audio.mp3"
            >

            <div class="nadiim-meta-actions">
                <button type="button" class="nadiim-meta-btn secondary" id="nadiim-upload-audio">
                    رفع ملف صوتي
                </button>
                <button type="button" class="nadiim-meta-btn" id="nadiim-remove-audio" style="display: none;">
                    إزالة الملف
                </button>
                <button type="button" class="nadiim-meta-btn" id="nadiim-preview-audio" style="display: none;">
                    معاينة
                </button>
            </div>

            <div class="nadiim-audio-preview" id="nadiim-audio-preview">
                <audio controls style="width: 100%;">
                    <source src="<?php echo esc_url( $article_meta['audio_url'] ); ?>" type="audio/mpeg">
                </audio>
            </div>

            <?php if ( ! empty( $article_meta['audio_url'] ) ) : ?>
                <div class="nadiim-audio-info">
                    <span class="dashicons dashicons-controls-volumeon"></span>
                    <span>تم رفع ملف صوتي</span>
                </div>
            <?php endif; ?>

            <div style="margin-top: 15px;">
                <label for="nadiim_audio_caption">وصف للملف الصوتي (اختياري)</label>
                <textarea
                    id="nadiim_audio_caption"
                    name="article_meta[audio_caption]"
                    placeholder="مثال: استمع للمقال بصوت المؤلف"
                ><?php echo esc_textarea( $article_meta['audio_caption'] ); ?></textarea>
            </div>

            <div class="description">
                يمكنك رفع ملف صوتي من جهازك أو لصق رابط مباشر للملف الصوتي في الحقل أعلاه.<br>
                الصيغ المدعومة: MP3, WAV, OGG. الحجم الأقصى للرفع: 50 ميجابايت.
            </div>
        </div>

        <!-- خيارات العرض -->
        <div class="nadiim-meta-field">
            <label>خيارات العرض</label>

            <div class="nadiim-toggle-field">
                <input
                    type="checkbox"
                    id="nadiim_show_audio_player"
                    name="article_meta[show_audio_player]"
                    value="1"
                    <?php checked( $article_meta['show_audio_player'], true ); ?>
                >
                <label for="nadiim_show_audio_player" style="margin: 0; font-weight: normal;">
                    عرض المشغل الصوتي في الواجهة (إن وُجد ملف)
                </label>
            </div>
        </div>
    </div>
    <?php
}

/**
 * حفظ بيانات Meta Box
 */
function nadiim_save_article_meta( $post_id ) {
    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_article_meta_nonce'] ) ||
         ! wp_verify_nonce( $_POST['nadiim_article_meta_nonce'], 'nadiim_save_article_meta' ) ) {
        return;
    }

    // التحقق من autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // التحقق من الصلاحيات
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // التحقق من نوع المنشور
    if ( get_post_type( $post_id ) !== 'post' ) {
        return;
    }

    // تنظيف وحفظ البيانات
    if ( isset( $_POST['article_meta'] ) && is_array( $_POST['article_meta'] ) ) {
        $article_meta = array();

        // وقت القراءة
        $article_meta['reading_time_manual'] = isset( $_POST['article_meta']['reading_time_manual'] )
            ? absint( $_POST['article_meta']['reading_time_manual'] )
            : '';

        $article_meta['reading_time_auto'] = isset( $_POST['article_meta']['reading_time_auto'] )
            ? absint( $_POST['article_meta']['reading_time_auto'] )
            : '';

        // الملف الصوتي
        $article_meta['audio_attachment_id'] = isset( $_POST['article_meta']['audio_attachment_id'] )
            ? absint( $_POST['article_meta']['audio_attachment_id'] )
            : '';

        $article_meta['audio_url'] = isset( $_POST['article_meta']['audio_url'] )
            ? esc_url_raw( $_POST['article_meta']['audio_url'] )
            : '';

        $article_meta['audio_caption'] = isset( $_POST['article_meta']['audio_caption'] )
            ? sanitize_textarea_field( $_POST['article_meta']['audio_caption'] )
            : '';

        // خيارات العرض
        $article_meta['show_reading_time'] = isset( $_POST['article_meta']['show_reading_time'] ) ? true : false;
        $article_meta['show_audio_player'] = isset( $_POST['article_meta']['show_audio_player'] ) ? true : false;

        // حفظ البيانات
        update_post_meta( $post_id, 'article_meta', $article_meta );
    }
}
add_action( 'save_post', 'nadiim_save_article_meta' );

/**
 * حساب عدد الكلمات في المقال
 */
function nadiim_get_post_word_count( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $post = get_post( $post_id );
    if ( ! $post ) {
        return 0;
    }

    // إزالة الـ shortcodes والـ HTML tags
    $content = strip_tags( strip_shortcodes( $post->post_content ) );

    // حساب الكلمات (يدعم العربية والإنجليزية)
    $word_count = count( preg_split( '/[\s,]+/', $content, -1, PREG_SPLIT_NO_EMPTY ) );

    return $word_count;
}

/**
 * الحصول على وقت القراءة للمقال
 */
function nadiim_get_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $article_meta = get_post_meta( $post_id, 'article_meta', true );

    // استخدام الوقت المخصص إن وُجد، وإلا استخدم التلقائي
    if ( ! empty( $article_meta['reading_time_manual'] ) ) {
        return absint( $article_meta['reading_time_manual'] );
    }

    if ( ! empty( $article_meta['reading_time_auto'] ) ) {
        return absint( $article_meta['reading_time_auto'] );
    }

    // حساب تلقائي كـ fallback
    $word_count = nadiim_get_post_word_count( $post_id );
    return ceil( $word_count / 200 );
}

/**
 * الحصول على معلومات الملف الصوتي
 */
function nadiim_get_article_audio( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $article_meta = get_post_meta( $post_id, 'article_meta', true );

    if ( empty( $article_meta['audio_url'] ) ) {
        return false;
    }

    return array(
        'url' => $article_meta['audio_url'],
        'attachment_id' => $article_meta['audio_attachment_id'] ?? '',
        'duration' => $article_meta['audio_duration'] ?? '',
        'caption' => $article_meta['audio_caption'] ?? '',
    );
}

/**
 * التحقق من وجود ملف صوتي
 */
function nadiim_has_article_audio( $post_id = null ) {
    return nadiim_get_article_audio( $post_id ) !== false;
}

/**
 * حفظ الكاتب المختار
 */
function nadiim_save_article_author( $post_id ) {
    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_article_author_nonce'] ) ||
         ! wp_verify_nonce( $_POST['nadiim_article_author_nonce'], 'nadiim_save_article_author' ) ) {
        return;
    }

    // التحقق من autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // التحقق من الصلاحيات (فقط من يستطيع تحرير منشورات الآخرين)
    if ( ! current_user_can( 'edit_others_posts', $post_id ) ) {
        return;
    }

    // التحقق من نوع المنشور
    if ( get_post_type( $post_id ) !== 'post' ) {
        return;
    }

    // حفظ الكاتب الجديد
    if ( isset( $_POST['nadiim_post_author'] ) && ! empty( $_POST['nadiim_post_author'] ) ) {
        $new_author_id = absint( $_POST['nadiim_post_author'] );
        $current_author = get_post_field( 'post_author', $post_id );

        // التحقق من أن الكاتب تغيّر فعلاً
        if ( $new_author_id != $current_author ) {
            // التحقق من أن المستخدم موجود
            $user = get_userdata( $new_author_id );
            if ( $user ) {
                // إزالة hook مؤقتاً لتجنب infinite loop
                remove_action( 'save_post', 'nadiim_save_article_author', 10 );

                // تحديث الكاتب
                wp_update_post( array(
                    'ID'          => $post_id,
                    'post_author' => $new_author_id,
                ) );

                // إعادة hook
                add_action( 'save_post', 'nadiim_save_article_author', 10, 1 );
            }
        }
    }
}
add_action( 'save_post', 'nadiim_save_article_author', 10, 1 );
