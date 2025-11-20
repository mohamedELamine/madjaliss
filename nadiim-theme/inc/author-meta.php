<?php
/**
 * نظام إدارة حقول بيانات الكاتب (Author Meta)
 *
 * يوفر حقول إضافية لملف تعريف الكاتب:
 * - صورة الملف الشخصي المخصصة
 * - السيرة الذاتية الكاملة والمختصرة
 * - روابط وسائل التواصل الاجتماعي
 * - إعدادات الخصوصية والاتصال
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // منع الوصول المباشر
}

/**
 * إضافة حقول الكاتب إلى صفحة الملف الشخصي
 */
function nadiim_add_author_profile_fields( $user ) {
    // الحصول على البيانات المحفوظة
    $user_bio = get_user_meta( $user->ID, 'user_bio', true );
    $user_excerpt = get_user_meta( $user->ID, 'user_excerpt', true );
    $profile_picture_id = get_user_meta( $user->ID, 'profile_picture_id', true );
    $author_website = get_user_meta( $user->ID, 'author_website', true );
    $show_email_contact = get_user_meta( $user->ID, 'show_email_contact', true );

    // روابط التواصل الاجتماعي
    $social_facebook = get_user_meta( $user->ID, 'social_facebook', true );
    $social_twitter = get_user_meta( $user->ID, 'social_twitter', true );
    $social_telegram = get_user_meta( $user->ID, 'social_telegram', true );
    $social_linkedin = get_user_meta( $user->ID, 'social_linkedin', true );
    ?>

    <style>
        .nadiim-author-meta-section {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            margin-top: 20px;
        }
        .nadiim-author-meta-section h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
            color: #1d2327;
        }
        .nadiim-profile-picture-preview {
            margin-top: 10px;
            max-width: 200px;
            border-radius: 50%;
            overflow: hidden;
        }
        .nadiim-profile-picture-preview img {
            width: 100%;
            height: auto;
            display: block;
        }
        .nadiim-meta-row {
            margin-bottom: 20px;
        }
        .nadiim-meta-row label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #1d2327;
        }
        .nadiim-meta-row input[type="text"],
        .nadiim-meta-row input[type="url"],
        .nadiim-meta-row textarea {
            width: 100%;
            max-width: 600px;
        }
        .nadiim-meta-row textarea {
            min-height: 120px;
        }
        .nadiim-meta-row .description {
            margin-top: 5px;
            color: #646970;
            font-size: 13px;
        }
        .nadiim-social-fields {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            max-width: 800px;
        }
        @media (max-width: 782px) {
            .nadiim-social-fields {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="nadiim-author-meta-section">
        <h2><?php esc_html_e( 'معلومات الكاتب الإضافية', 'nadiim' ); ?></h2>

        <!-- صورة الملف الشخصي المخصصة -->
        <div class="nadiim-meta-row">
            <label for="profile_picture_id">
                <?php esc_html_e( 'صورة الملف الشخصي المخصصة', 'nadiim' ); ?>
            </label>
            <input type="hidden" name="profile_picture_id" id="profile_picture_id"
                   value="<?php echo esc_attr( $profile_picture_id ); ?>" />
            <button type="button" class="button" id="nadiim_upload_profile_picture">
                <?php esc_html_e( 'اختيار صورة', 'nadiim' ); ?>
            </button>
            <button type="button" class="button" id="nadiim_remove_profile_picture"
                    style="<?php echo empty( $profile_picture_id ) ? 'display:none;' : ''; ?>">
                <?php esc_html_e( 'إزالة الصورة', 'nadiim' ); ?>
            </button>

            <div class="nadiim-profile-picture-preview" id="nadiim_profile_picture_preview"
                 style="<?php echo empty( $profile_picture_id ) ? 'display:none;' : ''; ?>">
                <?php
                if ( $profile_picture_id ) {
                    echo wp_get_attachment_image( $profile_picture_id, 'thumbnail' );
                }
                ?>
            </div>
            <p class="description">
                <?php esc_html_e( 'صورة كبيرة للملف الشخصي تظهر في صفحة الكاتب (يُفضل 400×400 بكسل أو أكبر)', 'nadiim' ); ?>
            </p>
        </div>

        <!-- المقتطف القصير -->
        <div class="nadiim-meta-row">
            <label for="user_excerpt">
                <?php esc_html_e( 'المقتطف القصير', 'nadiim' ); ?>
            </label>
            <textarea name="user_excerpt" id="user_excerpt" rows="3"
                      class="regular-text"><?php echo esc_textarea( $user_excerpt ); ?></textarea>
            <p class="description">
                <?php esc_html_e( 'نبذة مختصرة (2-3 أسطر) تظهر في بطاقة الكاتب', 'nadiim' ); ?>
            </p>
        </div>

        <!-- السيرة الذاتية الكاملة -->
        <div class="nadiim-meta-row">
            <label for="user_bio">
                <?php esc_html_e( 'السيرة الذاتية الكاملة', 'nadiim' ); ?>
            </label>
            <?php
            wp_editor( $user_bio, 'user_bio', array(
                'textarea_name' => 'user_bio',
                'textarea_rows' => 10,
                'media_buttons' => false,
                'teeny' => true,
                'quicktags' => true,
            ) );
            ?>
            <p class="description">
                <?php esc_html_e( 'السيرة الذاتية التفصيلية للكاتب (يمكن استخدام HTML الأساسي)', 'nadiim' ); ?>
            </p>
        </div>

        <!-- الموقع الإلكتروني -->
        <div class="nadiim-meta-row">
            <label for="author_website">
                <?php esc_html_e( 'الموقع الإلكتروني الشخصي', 'nadiim' ); ?>
            </label>
            <input type="url" name="author_website" id="author_website"
                   value="<?php echo esc_attr( $author_website ); ?>"
                   class="regular-text" placeholder="https://" />
            <p class="description">
                <?php esc_html_e( 'رابط الموقع الشخصي أو المدونة الخاصة بالكاتب', 'nadiim' ); ?>
            </p>
        </div>

        <!-- إعدادات الاتصال -->
        <div class="nadiim-meta-row">
            <label>
                <input type="checkbox" name="show_email_contact" value="1"
                       <?php checked( $show_email_contact, '1' ); ?> />
                <?php esc_html_e( 'السماح للزوار بإرسال رسائل عبر نموذج الاتصال', 'nadiim' ); ?>
            </label>
            <p class="description">
                <?php esc_html_e( 'عند التفعيل، سيظهر زر "راسل الكاتب" في صفحة الملف الشخصي', 'nadiim' ); ?>
            </p>
        </div>
    </div>

    <!-- روابط وسائل التواصل الاجتماعي -->
    <div class="nadiim-author-meta-section">
        <h2><?php esc_html_e( 'روابط التواصل الاجتماعي', 'nadiim' ); ?></h2>

        <div class="nadiim-social-fields">
            <!-- فيسبوك -->
            <div class="nadiim-meta-row">
                <label for="social_facebook">
                    <span class="dashicons dashicons-facebook"></span>
                    <?php esc_html_e( 'فيسبوك', 'nadiim' ); ?>
                </label>
                <input type="url" name="social_facebook" id="social_facebook"
                       value="<?php echo esc_attr( $social_facebook ); ?>"
                       class="regular-text" placeholder="https://facebook.com/username" />
            </div>

            <!-- تويتر/X -->
            <div class="nadiim-meta-row">
                <label for="social_twitter">
                    <span class="dashicons dashicons-twitter"></span>
                    <?php esc_html_e( 'تويتر / X', 'nadiim' ); ?>
                </label>
                <input type="url" name="social_twitter" id="social_twitter"
                       value="<?php echo esc_attr( $social_twitter ); ?>"
                       class="regular-text" placeholder="https://twitter.com/username" />
            </div>

            <!-- تيليجرام -->
            <div class="nadiim-meta-row">
                <label for="social_telegram">
                    <span class="dashicons dashicons-share-alt2"></span>
                    <?php esc_html_e( 'تيليجرام', 'nadiim' ); ?>
                </label>
                <input type="url" name="social_telegram" id="social_telegram"
                       value="<?php echo esc_attr( $social_telegram ); ?>"
                       class="regular-text" placeholder="https://t.me/username" />
            </div>

            <!-- لينكد إن -->
            <div class="nadiim-meta-row">
                <label for="social_linkedin">
                    <span class="dashicons dashicons-linkedin"></span>
                    <?php esc_html_e( 'لينكد إن', 'nadiim' ); ?>
                </label>
                <input type="url" name="social_linkedin" id="social_linkedin"
                       value="<?php echo esc_attr( $social_linkedin ); ?>"
                       class="regular-text" placeholder="https://linkedin.com/in/username" />
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Media Uploader لصورة الملف الشخصي
        var mediaUploader;

        $('#nadiim_upload_profile_picture').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: '<?php esc_html_e( 'اختر صورة الملف الشخصي', 'nadiim' ); ?>',
                button: {
                    text: '<?php esc_html_e( 'استخدام هذه الصورة', 'nadiim' ); ?>'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#profile_picture_id').val(attachment.id);
                $('#nadiim_profile_picture_preview').html('<img src="' + attachment.url + '" />').show();
                $('#nadiim_remove_profile_picture').show();
            });

            mediaUploader.open();
        });

        // إزالة الصورة
        $('#nadiim_remove_profile_picture').on('click', function(e) {
            e.preventDefault();
            $('#profile_picture_id').val('');
            $('#nadiim_profile_picture_preview').hide().html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}
add_action( 'show_user_profile', 'nadiim_add_author_profile_fields' );
add_action( 'edit_user_profile', 'nadiim_add_author_profile_fields' );

/**
 * حفظ حقول الكاتب
 */
function nadiim_save_author_profile_fields( $user_id ) {
    // التحقق من الصلاحيات
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // حفظ صورة الملف الشخصي
    if ( isset( $_POST['profile_picture_id'] ) ) {
        update_user_meta( $user_id, 'profile_picture_id', sanitize_text_field( $_POST['profile_picture_id'] ) );
    }

    // حفظ المقتطف القصير
    if ( isset( $_POST['user_excerpt'] ) ) {
        update_user_meta( $user_id, 'user_excerpt', sanitize_textarea_field( $_POST['user_excerpt'] ) );
    }

    // حفظ السيرة الذاتية الكاملة (مع السماح بـ HTML الأساسي)
    if ( isset( $_POST['user_bio'] ) ) {
        update_user_meta( $user_id, 'user_bio', wp_kses_post( $_POST['user_bio'] ) );
    }

    // حفظ الموقع الإلكتروني
    if ( isset( $_POST['author_website'] ) ) {
        update_user_meta( $user_id, 'author_website', esc_url_raw( $_POST['author_website'] ) );
    }

    // حفظ إعدادات الاتصال
    $show_email_contact = isset( $_POST['show_email_contact'] ) ? '1' : '0';
    update_user_meta( $user_id, 'show_email_contact', $show_email_contact );

    // حفظ روابط التواصل الاجتماعي
    $social_fields = array( 'social_facebook', 'social_twitter', 'social_telegram', 'social_linkedin' );
    foreach ( $social_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_user_meta( $user_id, $field, esc_url_raw( $_POST[ $field ] ) );
        }
    }
}
add_action( 'personal_options_update', 'nadiim_save_author_profile_fields' );
add_action( 'edit_user_profile_update', 'nadiim_save_author_profile_fields' );

/**
 * تحميل Media Uploader في صفحة الملف الشخصي
 */
function nadiim_author_meta_enqueue_scripts( $hook ) {
    if ( 'profile.php' !== $hook && 'user-edit.php' !== $hook ) {
        return;
    }

    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'nadiim_author_meta_enqueue_scripts' );

// ==================================================================
// دوال مساعدة للواجهة الأمامية (Frontend Helper Functions)
// ==================================================================

/**
 * الحصول على صورة الملف الشخصي للكاتب
 *
 * @param int $user_id معرف المستخدم
 * @param string $size حجم الصورة
 * @return string|false URL الصورة أو false
 */
function nadiim_get_author_profile_picture( $user_id, $size = 'medium' ) {
    $profile_picture_id = get_user_meta( $user_id, 'profile_picture_id', true );

    if ( $profile_picture_id ) {
        $image = wp_get_attachment_image_url( $profile_picture_id, $size );
        if ( $image ) {
            return $image;
        }
    }

    return false;
}

/**
 * الحصول على المقتطف القصير للكاتب
 *
 * @param int $user_id معرف المستخدم
 * @param int $length الطول الأقصى بالأحرف
 * @return string
 */
function nadiim_get_author_excerpt( $user_id, $length = 200 ) {
    $excerpt = get_user_meta( $user_id, 'user_excerpt', true );

    if ( empty( $excerpt ) ) {
        // استخدام الـ bio كبديل
        $bio = get_user_meta( $user_id, 'user_bio', true );
        if ( $bio ) {
            $excerpt = wp_strip_all_tags( $bio );
        } else {
            // استخدام الـ description الافتراضي في WordPress
            $excerpt = get_user_meta( $user_id, 'description', true );
        }
    }

    if ( $length && mb_strlen( $excerpt ) > $length ) {
        $excerpt = mb_substr( $excerpt, 0, $length ) . '...';
    }

    return $excerpt;
}

/**
 * الحصول على السيرة الذاتية الكاملة للكاتب
 *
 * @param int $user_id معرف المستخدم
 * @return string
 */
function nadiim_get_author_bio( $user_id ) {
    $bio = get_user_meta( $user_id, 'user_bio', true );

    if ( empty( $bio ) ) {
        $bio = get_user_meta( $user_id, 'description', true );
    }

    return wp_kses_post( $bio );
}

/**
 * الحصول على روابط التواصل الاجتماعي للكاتب
 *
 * @param int $user_id معرف المستخدم
 * @return array مصفوفة الروابط
 */
function nadiim_get_author_social_links( $user_id ) {
    $social_links = array();

    $social_fields = array(
        'facebook' => get_user_meta( $user_id, 'social_facebook', true ),
        'twitter' => get_user_meta( $user_id, 'social_twitter', true ),
        'telegram' => get_user_meta( $user_id, 'social_telegram', true ),
        'linkedin' => get_user_meta( $user_id, 'social_linkedin', true ),
    );

    foreach ( $social_fields as $network => $url ) {
        if ( ! empty( $url ) ) {
            $social_links[ $network ] = esc_url( $url );
        }
    }

    return $social_links;
}

/**
 * التحقق من السماح بإرسال رسائل للكاتب
 *
 * @param int $user_id معرف المستخدم
 * @return bool
 */
function nadiim_author_allows_contact( $user_id ) {
    return get_user_meta( $user_id, 'show_email_contact', true ) === '1';
}

/**
 * الحصول على عدد منشورات الكاتب حسب النوع
 *
 * @param int $user_id معرف المستخدم
 * @param string $post_type نوع المنشور
 * @return int
 */
function nadiim_get_author_post_count( $user_id, $post_type = 'post' ) {
    $count_key = 'nadiim_author_' . $post_type . '_count_' . $user_id;
    $count = wp_cache_get( $count_key );

    if ( false === $count ) {
        $count = count_user_posts( $user_id, $post_type, true );
        wp_cache_set( $count_key, $count, '', HOUR_IN_SECONDS );
    }

    return intval( $count );
}

/**
 * الحصول على موقع الكاتب الإلكتروني
 *
 * @param int $user_id معرف المستخدم
 * @return string|false
 */
function nadiim_get_author_website( $user_id ) {
    $website = get_user_meta( $user_id, 'author_website', true );

    if ( empty( $website ) ) {
        $website = get_the_author_meta( 'user_url', $user_id );
    }

    return ! empty( $website ) ? esc_url( $website ) : false;
}

/**
 * AJAX Handler لإرسال رسالة للكاتب
 */
function nadiim_ajax_contact_author() {
    // التحقق من الأمان
    check_ajax_referer( 'nadiim-author-contact', 'nonce' );

    // الحصول على البيانات
    $author_id = isset( $_POST['author_id'] ) ? intval( $_POST['author_id'] ) : 0;
    $sender_name = isset( $_POST['sender_name'] ) ? sanitize_text_field( $_POST['sender_name'] ) : '';
    $sender_email = isset( $_POST['sender_email'] ) ? sanitize_email( $_POST['sender_email'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    // التحقق من البيانات
    if ( ! $author_id || ! $sender_name || ! $sender_email || ! $message ) {
        wp_send_json_error( array(
            'message' => __( 'يرجى ملء جميع الحقول المطلوبة', 'nadiim' ),
        ) );
    }

    if ( ! is_email( $sender_email ) ) {
        wp_send_json_error( array(
            'message' => __( 'البريد الإلكتروني غير صحيح', 'nadiim' ),
        ) );
    }

    // التحقق من السماح بالاتصال
    if ( ! nadiim_author_allows_contact( $author_id ) ) {
        wp_send_json_error( array(
            'message' => __( 'لا يمكن إرسال رسائل لهذا الكاتب', 'nadiim' ),
        ) );
    }

    // الحصول على بيانات الكاتب
    $author = get_userdata( $author_id );
    if ( ! $author ) {
        wp_send_json_error( array(
            'message' => __( 'الكاتب غير موجود', 'nadiim' ),
        ) );
    }

    // إرسال البريد الإلكتروني
    $to = $author->user_email;
    $subject = sprintf( __( 'رسالة جديدة من %s عبر موقعك', 'nadiim' ), $sender_name );
    $body = sprintf(
        __( "مرحباً %s،\n\nلقد وصلتك رسالة جديدة عبر صفحتك الشخصية:\n\nالمرسل: %s\nالبريد الإلكتروني: %s\n\nالرسالة:\n%s\n\n---\nيمكنك الرد مباشرة على البريد الإلكتروني: %s", 'nadiim' ),
        $author->display_name,
        $sender_name,
        $sender_email,
        $message,
        $sender_email
    );

    $headers = array(
        'Reply-To: ' . $sender_name . ' <' . $sender_email . '>',
        'Content-Type: text/plain; charset=UTF-8'
    );

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array(
            'message' => __( 'تم إرسال رسالتك بنجاح!', 'nadiim' ),
        ) );
    } else {
        wp_send_json_error( array(
            'message' => __( 'حدث خطأ أثناء إرسال الرسالة، يرجى المحاولة لاحقاً', 'nadiim' ),
        ) );
    }
}
add_action( 'wp_ajax_nadiim_contact_author', 'nadiim_ajax_contact_author' );
add_action( 'wp_ajax_nopriv_nadiim_contact_author', 'nadiim_ajax_contact_author' );
