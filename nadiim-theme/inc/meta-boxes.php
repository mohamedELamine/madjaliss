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
// Meta Boxes لنوادي القراءة (reading_clubs)
// ============================================

/**
 * إضافة Meta Box لبيانات النادي
 */
function nadiim_add_reading_clubs_meta_boxes() {
    add_meta_box(
        'nadiim_club_details',
        __( 'بيانات النادي', 'nadiim' ),
        'nadiim_render_club_details_meta_box',
        'reading_clubs',
        'normal',
        'high'
    );

    add_meta_box(
        'nadiim_club_schedule',
        __( 'الجدول الزمني والاجتماعات', 'nadiim' ),
        'nadiim_render_club_schedule_meta_box',
        'reading_clubs',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nadiim_add_reading_clubs_meta_boxes' );

/**
 * عرض Meta Box: بيانات النادي
 */
function nadiim_render_club_details_meta_box( $post ) {
    wp_nonce_field( 'nadiim_save_meta', 'nadiim_meta_nonce' );

    // المشرف
    $supervisor_id = get_post_meta( $post->ID, 'club_supervisor', true );
    $all_users = get_users( array( 'orderby' => 'display_name' ) );
    ?>
    <p>
        <label for="club_supervisor"><strong><?php esc_html_e( 'مشرف النادي', 'nadiim' ); ?></strong></label>
        <br>
        <select id="club_supervisor" name="club_supervisor" style="width: 100%;">
            <option value=""><?php esc_html_e( '-- اختر المشرف --', 'nadiim' ); ?></option>
            <?php foreach ( $all_users as $user ) : ?>
                <option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $supervisor_id, $user->ID ); ?>>
                    <?php echo esc_html( $user->display_name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php

    // الأعضاء
    $members = get_post_meta( $post->ID, 'club_members', true );
    if ( ! is_array( $members ) ) {
        $members = array();
    }
    ?>
    <p>
        <label for="club_members"><strong><?php esc_html_e( 'أعضاء النادي', 'nadiim' ); ?></strong></label>
        <br>
        <select id="club_members" name="club_members[]" multiple style="width: 100%; height: 150px;">
            <?php foreach ( $all_users as $user ) : ?>
                <option value="<?php echo esc_attr( $user->ID ); ?>" <?php echo in_array( $user->ID, $members, true ) ? 'selected' : ''; ?>>
                    <?php echo esc_html( $user->display_name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small style="display: block; margin-top: 5px; color: #666;">
            <?php esc_html_e( 'اضغط Ctrl/Cmd لاختيار أكثر من عضو', 'nadiim' ); ?>
        </small>
    </p>
    <?php

    // الكتاب الحالي (ربط مع CPT الإصدارات)
    $current_book = get_post_meta( $post->ID, 'club_current_book', true );
    $books = get_posts( array(
        'post_type'      => 'esdar',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );
    ?>
    <p>
        <label for="club_current_book"><strong><?php esc_html_e( 'الكتاب الجاري قراءته', 'nadiim' ); ?></strong></label>
        <br>
        <select id="club_current_book" name="club_current_book" style="width: 100%;">
            <option value=""><?php esc_html_e( '-- اختر الكتاب --', 'nadiim' ); ?></option>
            <?php foreach ( $books as $book ) : ?>
                <option value="<?php echo esc_attr( $book->ID ); ?>" <?php selected( $current_book, $book->ID ); ?>>
                    <?php echo esc_html( $book->post_title ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php

    // رابط الانضمام
    nadiim_render_input_field(
        $post->ID,
        'club_join_url',
        __( 'رابط الانضمام للنادي', 'nadiim' ),
        'url',
        __( 'https://example.com/join', 'nadiim' )
    );

    // عدد الأعضاء الأقصى
    nadiim_render_input_field(
        $post->ID,
        'club_max_members',
        __( 'الحد الأقصى لعدد الأعضاء', 'nadiim' ),
        'number',
        __( 'مثال: 20', 'nadiim' )
    );
}

/**
 * عرض Meta Box: الجدول الزمني
 */
function nadiim_render_club_schedule_meta_box( $post ) {
    // الجدول الزمني
    $schedule = get_post_meta( $post->ID, 'club_schedule', true );
    ?>
    <p>
        <label for="club_schedule"><strong><?php esc_html_e( 'الجدول الزمني للاجتماعات', 'nadiim' ); ?></strong></label>
        <br>
        <textarea id="club_schedule" name="club_schedule" rows="5" style="width: 100%;" placeholder="<?php esc_attr_e( 'مثال: كل يوم أحد الساعة 7 مساءً', 'nadiim' ); ?>"><?php echo esc_textarea( $schedule ); ?></textarea>
        <small style="display: block; margin-top: 5px; color: #666;">
            <?php esc_html_e( 'وصف موجز لتوقيت الاجتماعات الدورية', 'nadiim' ); ?>
        </small>
    </p>
    <?php

    // رابط الاجتماع
    nadiim_render_input_field(
        $post->ID,
        'club_meeting_url',
        __( 'رابط الاجتماع الافتراضي', 'nadiim' ),
        'url',
        __( 'https://zoom.us/j/...', 'nadiim' )
    );
}

/**
 * حفظ بيانات النادي
 */
function nadiim_save_reading_clubs_meta( $post_id ) {
    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_meta_nonce'] ) || ! wp_verify_nonce( $_POST['nadiim_meta_nonce'], 'nadiim_save_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'reading_clubs' !== get_post_type( $post_id ) ) {
        return;
    }

    // حفظ المشرف
    if ( isset( $_POST['club_supervisor'] ) ) {
        update_post_meta( $post_id, 'club_supervisor', absint( $_POST['club_supervisor'] ) );
    }

    // حفظ الأعضاء
    if ( isset( $_POST['club_members'] ) && is_array( $_POST['club_members'] ) ) {
        $members = array_map( 'absint', $_POST['club_members'] );
        update_post_meta( $post_id, 'club_members', $members );
    } else {
        delete_post_meta( $post_id, 'club_members' );
    }

    // حفظ الكتاب الحالي
    if ( isset( $_POST['club_current_book'] ) ) {
        update_post_meta( $post_id, 'club_current_book', absint( $_POST['club_current_book'] ) );
    }

    // حفظ الحقول الأخرى
    $fields = array(
        'club_join_url'     => 'esc_url_raw',
        'club_max_members'  => 'absint',
        'club_schedule'     => 'sanitize_textarea_field',
        'club_meeting_url'  => 'esc_url_raw',
    );

    foreach ( $fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[ $field ] );
            update_post_meta( $post_id, $field, $value );
        }
    }
}
add_action( 'save_post', 'nadiim_save_reading_clubs_meta' );

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

// ============================================
// Meta Boxes: المقالات (Posts) - ملف صوتي
// ============================================

/**
 * إضافة Meta Box لملف الصوت في المقالات
 */
function nadiim_add_post_audio_meta_box() {
    add_meta_box(
        'post_audio_meta',
        __( 'ملف صوتي للمقال', 'nadiim' ),
        'nadiim_render_post_audio_meta_box',
        'post',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'nadiim_add_post_audio_meta_box' );

/**
 * عرض Meta Box: ملف الصوت
 */
function nadiim_render_post_audio_meta_box( $post ) {
    wp_nonce_field( 'nadiim_save_meta', 'nadiim_meta_nonce' );

    // رابط الملف الصوتي
    nadiim_render_input_field(
        $post->ID,
        'article_audio_url',
        __( 'رابط الملف الصوتي', 'nadiim' ),
        'url',
        __( 'https://example.com/audio.mp3', 'nadiim' )
    );

    echo '<p><small style="color: #666;">' . esc_html__( 'أضف رابط ملف MP3 للاستماع للمقال. سيظهر مشغل الصوت في صفحة المقال.', 'nadiim' ) . '</small></p>';
}

/**
 * حفظ Meta Data للملف الصوتي
 */
function nadiim_save_post_audio_meta( $post_id ) {
    // التحقق من نوع المنشور
    if ( get_post_type( $post_id ) !== 'post' ) {
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

    // حفظ رابط الملف الصوتي
    if ( isset( $_POST['article_audio_url'] ) ) {
        update_post_meta( $post_id, 'article_audio_url', esc_url_raw( $_POST['article_audio_url'] ) );
    } else {
        delete_post_meta( $post_id, 'article_audio_url' );
    }
}
add_action( 'save_post', 'nadiim_save_post_audio_meta' );
