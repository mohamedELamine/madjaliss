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

    add_meta_box(
        'nadiim_howarat_transcript',
        __( 'نص الترانسكريبت', 'nadiim' ),
        'nadiim_render_howarat_transcript_meta_box',
        'howarat',
        'normal',
        'default'
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

    // المشاركون (اختيار متعدد من المستخدمين)
    $participants = get_post_meta( $post->ID, 'dialogue_participants', true );
    if ( ! is_array( $participants ) ) {
        $participants = array();
    }

    $all_users = get_users( array(
        'orderby' => 'display_name',
        'order'   => 'ASC',
    ) );
    ?>
    <p>
        <label for="dialogue_participants">
            <strong><?php esc_html_e( 'المشاركون في الحوار', 'nadiim' ); ?></strong>
        </label>
        <br>
        <select id="dialogue_participants" name="dialogue_participants[]" multiple style="width: 100%; height: 150px;">
            <?php foreach ( $all_users as $user ) : ?>
                <option value="<?php echo esc_attr( $user->ID ); ?>" <?php echo in_array( $user->ID, $participants, true ) ? 'selected' : ''; ?>>
                    <?php echo esc_html( $user->display_name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small style="display: block; margin-top: 5px; color: #666;">
            <?php esc_html_e( 'اضغط Ctrl/Cmd لاختيار أكثر من مشارك', 'nadiim' ); ?>
        </small>
    </p>
    <?php
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
 * عرض Meta Box: الترانسكريبت
 */
function nadiim_render_howarat_transcript_meta_box( $post ) {
    // نص الترانسكريبت
    $transcript = get_post_meta( $post->ID, 'dialogue_transcript', true );
    ?>
    <p>
        <label for="dialogue_transcript">
            <strong><?php esc_html_e( 'نص الترانسكريبت الكامل', 'nadiim' ); ?></strong>
        </label>
        <br>
        <?php
        wp_editor( $transcript, 'dialogue_transcript', array(
            'textarea_name' => 'dialogue_transcript',
            'textarea_rows' => 15,
            'media_buttons' => false,
            'teeny'         => false,
            'tinymce'       => array(
                'toolbar1' => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,link,unlink',
            ),
        ) );
        ?>
    </p>
    <p><small style="color: #666;">
        <?php esc_html_e( 'يمكنك إدخال النص الكامل للحوار هنا. سيُعرض في صفحة الحوار المفرد.', 'nadiim' ); ?>
    </small></p>
    <?php
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

    // حفظ المشاركون
    if ( isset( $_POST['dialogue_participants'] ) && is_array( $_POST['dialogue_participants'] ) ) {
        $participants = array_map( 'absint', $_POST['dialogue_participants'] );
        update_post_meta( $post_id, 'dialogue_participants', $participants );
    } else {
        delete_post_meta( $post_id, 'dialogue_participants' );
    }

    // حفظ نوع الوسائط
    if ( isset( $_POST['dialogue_media_type'] ) ) {
        update_post_meta( $post_id, 'dialogue_media_type', sanitize_text_field( $_POST['dialogue_media_type'] ) );
    }

    // حفظ رابط الوسائط
    if ( isset( $_POST['dialogue_media_url'] ) ) {
        update_post_meta( $post_id, 'dialogue_media_url', esc_url_raw( $_POST['dialogue_media_url'] ) );
    }

    // حفظ الترانسكريبت
    if ( isset( $_POST['dialogue_transcript'] ) ) {
        update_post_meta( $post_id, 'dialogue_transcript', wp_kses_post( $_POST['dialogue_transcript'] ) );
    }
}
add_action( 'save_post', 'nadiim_save_howarat_meta' );

// ============================================
// Meta Boxes للإصدارات (esdar)
// ============================================

/**
 * إضافة Meta Box لبيانات الإصدار
 */
function nadiim_add_esdar_meta_boxes() {
    add_meta_box(
        'nadiim_esdar_details',
        __( 'بيانات الإصدار', 'nadiim' ),
        'nadiim_render_esdar_details_meta_box',
        'esdar',
        'normal',
        'high'
    );

    add_meta_box(
        'nadiim_esdar_files',
        __( 'الملفات والروابط', 'nadiim' ),
        'nadiim_render_esdar_files_meta_box',
        'esdar',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nadiim_add_esdar_meta_boxes' );

/**
 * عرض Meta Box: بيانات الإصدار
 */
function nadiim_render_esdar_details_meta_box( $post ) {
    wp_nonce_field( 'nadiim_save_meta', 'nadiim_meta_nonce' );

    // تاريخ النشر
    nadiim_render_input_field( $post->ID, 'release_date', __( 'تاريخ النشر', 'nadiim' ), 'date' );

    // المؤلف
    nadiim_render_input_field( $post->ID, 'release_author', __( 'المؤلف', 'nadiim' ), 'text', __( 'مثال: أحمد محمود', 'nadiim' ) );

    // ISBN
    nadiim_render_input_field( $post->ID, 'release_isbn', __( 'رقم ISBN', 'nadiim' ), 'text', __( 'مثال: 978-3-16-148410-0', 'nadiim' ) );

    // عدد الصفحات
    nadiim_render_input_field( $post->ID, 'release_pages', __( 'عدد الصفحات', 'nadiim' ), 'number' );

    // الناشر
    nadiim_render_input_field( $post->ID, 'release_publisher', __( 'الناشر', 'nadiim' ), 'text' );
}

/**
 * عرض Meta Box: الملفات والروابط
 */
function nadiim_render_esdar_files_meta_box( $post ) {
    // رابط PDF أو صفحة العرض
    nadiim_render_input_field(
        $post->ID,
        'release_pdf_url',
        __( 'رابط ملف PDF أو صفحة العرض', 'nadiim' ),
        'url',
        __( 'https://example.com/book.pdf', 'nadiim' )
    );

    // رابط الشراء/التحميل
    nadiim_render_input_field(
        $post->ID,
        'release_purchase_url',
        __( 'رابط الشراء أو التحميل', 'nadiim' ),
        'url',
        __( 'https://example.com/buy', 'nadiim' )
    );

    // رابط معاينة
    nadiim_render_input_field(
        $post->ID,
        'release_preview_url',
        __( 'رابط المعاينة', 'nadiim' ),
        'url',
        __( 'رابط للصفحات التجريبية', 'nadiim' )
    );

    echo '<p><small style="color: #666;">' . esc_html__( 'يمكنك إضافة روابط لملف PDF، صفحة الشراء، أو المعاينة', 'nadiim' ) . '</small></p>';
}

/**
 * حفظ بيانات الإصدار
 */
function nadiim_save_esdar_meta( $post_id ) {
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
    if ( 'esdar' !== get_post_type( $post_id ) ) {
        return;
    }

    // حفظ الحقول
    $fields = array(
        'release_date'         => 'sanitize_text_field',
        'release_author'       => 'sanitize_text_field',
        'release_isbn'         => 'sanitize_text_field',
        'release_pages'        => 'absint',
        'release_publisher'    => 'sanitize_text_field',
        'release_pdf_url'      => 'esc_url_raw',
        'release_purchase_url' => 'esc_url_raw',
        'release_preview_url'  => 'esc_url_raw',
    );

    foreach ( $fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[ $field ] );
            update_post_meta( $post_id, $field, $value );
        }
    }
}
add_action( 'save_post', 'nadiim_save_esdar_meta' );
