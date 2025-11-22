<?php
/**
 * صناديق الميتا (Meta Boxes) للحقول المخصصة
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * حفظ Meta Field
 */
function nadiim_save_meta_field( $post_id, $field_name, $sanitize_callback = 'sanitize_text_field' ) {
    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_meta_nonce'] ) || ! wp_verify_nonce( $_POST['nadiim_meta_nonce'], 'nadiim_save_meta' ) ) {
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

    // حفظ القيمة
    if ( isset( $_POST[ $field_name ] ) ) {
        $value = call_user_func( $sanitize_callback, $_POST[ $field_name ] );
        update_post_meta( $post_id, $field_name, $value );
    } else {
        delete_post_meta( $post_id, $field_name );
    }
}

/**
 * دالة مساعدة لعرض حقل input
 */
function nadiim_render_input_field( $post_id, $field_name, $label, $type = 'text', $placeholder = '' ) {
    $value = get_post_meta( $post_id, $field_name, true );
    ?>
    <p>
        <label for="<?php echo esc_attr( $field_name ); ?>">
            <strong><?php echo esc_html( $label ); ?></strong>
        </label>
        <br>
        <input
            type="<?php echo esc_attr( $type ); ?>"
            id="<?php echo esc_attr( $field_name ); ?>"
            name="<?php echo esc_attr( $field_name ); ?>"
            value="<?php echo esc_attr( $value ); ?>"
            placeholder="<?php echo esc_attr( $placeholder ); ?>"
            style="width: 100%;">
    </p>
    <?php
}

/**
 * دالة مساعدة لعرض حقل textarea
 */
function nadiim_render_textarea_field( $post_id, $field_name, $label, $rows = 5, $placeholder = '' ) {
    $value = get_post_meta( $post_id, $field_name, true );
    ?>
    <p>
        <label for="<?php echo esc_attr( $field_name ); ?>">
            <strong><?php echo esc_html( $label ); ?></strong>
        </label>
        <br>
        <textarea
            id="<?php echo esc_attr( $field_name ); ?>"
            name="<?php echo esc_attr( $field_name ); ?>"
            rows="<?php echo esc_attr( $rows ); ?>"
            placeholder="<?php echo esc_attr( $placeholder ); ?>"
            style="width: 100%;"><?php echo esc_textarea( $value ); ?></textarea>
    </p>
    <?php
}

/**
 * دالة مساعدة لعرض حقل select
 */
function nadiim_render_select_field( $post_id, $field_name, $label, $options = array() ) {
    $value = get_post_meta( $post_id, $field_name, true );
    ?>
    <p>
        <label for="<?php echo esc_attr( $field_name ); ?>">
            <strong><?php echo esc_html( $label ); ?></strong>
        </label>
        <br>
        <select id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" style="width: 100%;">
            <option value=""><?php esc_html_e( '-- اختر --', 'nadiim' ); ?></option>
            <?php foreach ( $options as $key => $option_label ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ); ?>>
                    <?php echo esc_html( $option_label ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

/**
 * دالة مساعدة لعرض حقل صورة
 */
function nadiim_render_image_field( $post_id, $field_name, $label ) {
    $image_id = get_post_meta( $post_id, $field_name, true );
    $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
    ?>
    <p>
        <label><strong><?php echo esc_html( $label ); ?></strong></label>
        <br>
        <div class="nadiim-image-field">
            <input type="hidden" id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $image_id ); ?>">
            <div class="nadiim-image-preview">
                <?php if ( $image_url ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="" style="max-width: 200px; display: block; margin-bottom: 10px;">
                <?php endif; ?>
            </div>
            <button type="button" class="button nadiim-upload-image-button"><?php esc_html_e( 'اختيار صورة', 'nadiim' ); ?></button>
            <button type="button" class="button nadiim-remove-image-button" <?php echo empty( $image_id ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'إزالة', 'nadiim' ); ?></button>
        </div>
    </p>
    <?php
}

/**
 * تحميل سكريبت Media Uploader للحقول المخصصة
 */
function nadiim_admin_scripts( $hook ) {
    if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script( 'jquery', "
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('.nadiim-upload-image-button').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                var field = button.closest('.nadiim-image-field');
                var input = field.find('input[type=\"hidden\"]');
                var preview = field.find('.nadiim-image-preview');
                var removeBtn = field.find('.nadiim-remove-image-button');

                mediaUploader = wp.media({
                    title: 'اختيار صورة',
                    button: { text: 'اختيار' },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    input.val(attachment.id);
                    preview.html('<img src=\"' + attachment.url + '\" style=\"max-width: 200px; display: block; margin-bottom: 10px;\">');
                    removeBtn.show();
                });

                mediaUploader.open();
            });

            $('.nadiim-remove-image-button').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                var field = button.closest('.nadiim-image-field');
                var input = field.find('input[type=\"hidden\"]');
                var preview = field.find('.nadiim-image-preview');

                input.val('');
                preview.html('');
                button.hide();
            });
        });
    " );
}
add_action( 'admin_enqueue_scripts', 'nadiim_admin_scripts' );

// ============================================
// Meta Boxes للحوارات (howarat)
// ============================================

/**
 * إضافة Meta Box لبيانات الحوار
 */
function nadiim_add_howarat_meta_boxes() {
    add_meta_box(
        'nadiim_howarat_details',
        __( 'بيانات الحوار', 'nadiim' ),
        'nadiim_render_howarat_details_meta_box',
        'howarat',
        'normal',
        'high'
    );

    add_meta_box(
        'nadiim_howarat_media',
        __( 'الوسائط', 'nadiim' ),
        'nadiim_render_howarat_media_meta_box',
        'howarat',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nadiim_add_howarat_meta_boxes' );

/**
 * عرض Meta Box: بيانات الحوار
 */
function nadiim_render_howarat_details_meta_box( $post ) {
    wp_nonce_field( 'nadiim_save_meta', 'nadiim_meta_nonce' );

    // التاريخ
    nadiim_render_input_field( $post->ID, 'dialogue_date', __( 'تاريخ الحوار', 'nadiim' ), 'date' );

    // الموقع
    nadiim_render_input_field( $post->ID, 'dialogue_location', __( 'موقع الحوار', 'nadiim' ), 'text', __( 'مثال: الرياض، المملكة العربية السعودية', 'nadiim' ) );
}

/**
 * عرض Meta Box: الوسائط
 */
function nadiim_render_howarat_media_meta_box( $post ) {
    // نوع الوسائط
    nadiim_render_select_field( $post->ID, 'dialogue_media_type', __( 'نوع الحوار', 'nadiim' ), array(
        'video'    => __( 'فيديو', 'nadiim' ),
        'audio'    => __( 'صوتي', 'nadiim' ),
        'written'  => __( 'مكتوب', 'nadiim' ),
    ) );

    // رابط الفيديو/الصوت
    nadiim_render_input_field(
        $post->ID,
        'dialogue_media_url',
        __( 'رابط الفيديو أو الصوت', 'nadiim' ),
        'url',
        __( 'https://www.youtube.com/watch?v=...', 'nadiim' )
    );

    echo '<p><small style="color: #666;">' . esc_html__( 'يدعم روابط YouTube, Vimeo, SoundCloud وغيرها', 'nadiim' ) . '</small></p>';
}

/**
 * حفظ بيانات الحوار
 */
function nadiim_save_howarat_meta( $post_id ) {
    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_meta_nonce'] ) || ! wp_verify_nonce( $_POST['nadiim_meta_nonce'], 'nadiim_save_meta' ) ) {
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
    if ( 'howarat' !== get_post_type( $post_id ) ) {
        return;
    }

    // حفظ التاريخ
    if ( isset( $_POST['dialogue_date'] ) ) {
        update_post_meta( $post_id, 'dialogue_date', sanitize_text_field( $_POST['dialogue_date'] ) );
    }

    // حفظ الموقع
    if ( isset( $_POST['dialogue_location'] ) ) {
        update_post_meta( $post_id, 'dialogue_location', sanitize_text_field( $_POST['dialogue_location'] ) );
    }

    // حفظ نوع الوسائط
    if ( isset( $_POST['dialogue_media_type'] ) ) {
        update_post_meta( $post_id, 'dialogue_media_type', sanitize_text_field( $_POST['dialogue_media_type'] ) );
    }

    // حفظ رابط الوسائط
    if ( isset( $_POST['dialogue_media_url'] ) ) {
        update_post_meta( $post_id, 'dialogue_media_url', esc_url_raw( $_POST['dialogue_media_url'] ) );
    }
}
add_action( 'save_post', 'nadiim_save_howarat_meta' );

// ============================================
// ملاحظة: تم نقل Meta Boxes للإصدارات إلى ملف منفصل
// راجع: inc/meta-esdar.php
// ============================================
// ملاحظة: تم نقل Meta Boxes لنوادي القراءة إلى ملف منفصل
// راجع: inc/meta-reading-clubs.php
// ============================================

// ============================================
// Meta Boxes: الفعاليات (Events)
// ============================================

/**
 * إضافة Meta Boxes للفعاليات
 */
function nadiim_add_events_meta_boxes() {
    add_meta_box(
        'event_details',
        __( 'تفاصيل الفعالية', 'nadiim' ),
        'nadiim_render_event_details_meta_box',
        'events',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nadiim_add_events_meta_boxes' );

/**
 * عرض Meta Box: تفاصيل الفعالية
 */
function nadiim_render_event_details_meta_box( $post ) {
    wp_nonce_field( 'nadiim_save_meta', 'nadiim_meta_nonce' );

    // تاريخ البداية
    nadiim_render_input_field( $post->ID, 'event_start_date', __( 'تاريخ بداية الفعالية', 'nadiim' ), 'datetime-local' );

    // تاريخ النهاية
    nadiim_render_input_field( $post->ID, 'event_end_date', __( 'تاريخ نهاية الفعالية', 'nadiim' ), 'datetime-local' );

    // الموقع
    nadiim_render_input_field( $post->ID, 'event_location', __( 'موقع الفعالية', 'nadiim' ), 'text', __( 'مثال: الرياض، المملكة العربية السعودية', 'nadiim' ) );

    // رابط التسجيل أو المشاركة
    nadiim_render_input_field( $post->ID, 'event_register_url', __( 'رابط التسجيل', 'nadiim' ), 'url', __( 'https://example.com/register', 'nadiim' ) );

    // نص زر التسجيل
    nadiim_render_input_field( $post->ID, 'event_register_text', __( 'نص زر التسجيل', 'nadiim' ), 'text', __( 'سجل الآن', 'nadiim' ) );

    // الحد الأقصى للمشاركين
    nadiim_render_input_field( $post->ID, 'event_max_attendees', __( 'الحد الأقصى للمشاركين', 'nadiim' ), 'number' );

    // ملاحظات إضافية
    nadiim_render_textarea_field( $post->ID, 'event_notes', __( 'ملاحظات إضافية', 'nadiim' ), 4, __( 'أي معلومات إضافية عن الفعالية', 'nadiim' ) );
}

/**
 * حفظ Meta Data للفعاليات
 */
function nadiim_save_events_meta( $post_id ) {
    // التحقق من نوع المنشور
    if ( get_post_type( $post_id ) !== 'events' ) {
        return;
    }

    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_meta_nonce'] ) || ! wp_verify_nonce( $_POST['nadiim_meta_nonce'], 'nadiim_save_meta' ) ) {
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

    // حفظ الحقول
    $fields = array(
        'event_start_date'    => 'sanitize_text_field',
        'event_end_date'      => 'sanitize_text_field',
        'event_location'      => 'sanitize_text_field',
        'event_register_url'  => 'esc_url_raw',
        'event_register_text' => 'sanitize_text_field',
        'event_max_attendees' => 'absint',
        'event_notes'         => 'sanitize_textarea_field',
    );

    foreach ( $fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[ $field ] );
            update_post_meta( $post_id, $field, $value );
        }
    }
}
add_action( 'save_post', 'nadiim_save_events_meta' );
