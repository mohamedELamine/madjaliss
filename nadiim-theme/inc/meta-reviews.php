<?php
/**
 * Meta Boxes للمراجعات
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة Meta Box للمراجعة
 */
function nadiim_add_review_meta_box() {
    add_meta_box(
        'nadiim_review_details',
        __( 'تفاصيل المراجعة', 'nadiim' ),
        'nadiim_review_meta_box_callback',
        'reviews',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nadiim_add_review_meta_box' );

/**
 * عرض محتوى Meta Box
 */
function nadiim_review_meta_box_callback( $post ) {
    // إضافة nonce للأمان
    wp_nonce_field( 'nadiim_save_review_meta', 'nadiim_review_meta_nonce' );

    // الحصول على القيم المحفوظة
    $rating        = get_post_meta( $post->ID, '_review_rating', true );
    $item_title    = get_post_meta( $post->ID, '_review_item_title', true );
    $author_name   = get_post_meta( $post->ID, '_review_author_name', true );
    $publisher     = get_post_meta( $post->ID, '_review_publisher', true );
    $publish_year  = get_post_meta( $post->ID, '_review_publish_year', true );
    $isbn          = get_post_meta( $post->ID, '_review_isbn', true );
    $buy_link      = get_post_meta( $post->ID, '_review_buy_link', true );
    ?>

    <style>
        .review-meta-field {
            margin-bottom: 20px;
        }
        .review-meta-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #1d2327;
        }
        .review-meta-field input[type="text"],
        .review-meta-field input[type="url"],
        .review-meta-field input[type="number"],
        .review-meta-field textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .review-meta-field textarea {
            min-height: 100px;
        }
        .review-meta-field small {
            display: block;
            margin-top: 5px;
            color: #666;
        }
        .rating-stars {
            display: inline-block;
            font-size: 24px;
            margin-right: 10px;
        }
        .rating-stars .star {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .rating-stars .star.active,
        .rating-stars .star:hover,
        .rating-stars .star:hover ~ .star {
            color: #f39c12;
        }
    </style>

    <div class="review-meta-fields">

        <!-- التقييم -->
        <div class="review-meta-field">
            <label for="review_rating"><?php _e( 'التقييم', 'nadiim' ); ?> *</label>
            <div class="rating-stars" id="rating-stars">
                <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                    <span class="star <?php echo $rating >= $i ? 'active' : ''; ?>" data-rating="<?php echo $i; ?>">★</span>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="review_rating" id="review_rating" value="<?php echo esc_attr( $rating ?: 5 ); ?>" />
            <small><?php _e( 'اختر التقييم من 1 إلى 5 نجوم', 'nadiim' ); ?></small>
        </div>

        <!-- عنوان العمل المُراجَع -->
        <div class="review-meta-field">
            <label for="review_item_title"><?php _e( 'عنوان العمل المُراجَع', 'nadiim' ); ?></label>
            <input type="text" name="review_item_title" id="review_item_title"
                   value="<?php echo esc_attr( $item_title ); ?>"
                   placeholder="<?php _e( 'مثال: رواية 1984', 'nadiim' ); ?>" />
            <small><?php _e( 'عنوان الكتاب/الفيلم/العمل الذي تتم مراجعته', 'nadiim' ); ?></small>
        </div>

        <!-- المؤلف/المخرج -->
        <div class="review-meta-field">
            <label for="review_author_name"><?php _e( 'المؤلف/المخرج', 'nadiim' ); ?></label>
            <input type="text" name="review_author_name" id="review_author_name"
                   value="<?php echo esc_attr( $author_name ); ?>"
                   placeholder="<?php _e( 'مثال: جورج أورويل', 'nadiim' ); ?>" />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <!-- الناشر -->
            <div class="review-meta-field">
                <label for="review_publisher"><?php _e( 'الناشر/دار النشر', 'nadiim' ); ?></label>
                <input type="text" name="review_publisher" id="review_publisher"
                       value="<?php echo esc_attr( $publisher ); ?>" />
            </div>

            <!-- سنة النشر -->
            <div class="review-meta-field">
                <label for="review_publish_year"><?php _e( 'سنة النشر', 'nadiim' ); ?></label>
                <input type="number" name="review_publish_year" id="review_publish_year"
                       value="<?php echo esc_attr( $publish_year ); ?>"
                       min="1900" max="<?php echo date( 'Y' ) + 1; ?>" />
            </div>
        </div>

        <!-- ISBN / معرّف -->
        <div class="review-meta-field">
            <label for="review_isbn"><?php _e( 'ISBN أو المعرّف', 'nadiim' ); ?></label>
            <input type="text" name="review_isbn" id="review_isbn"
                   value="<?php echo esc_attr( $isbn ); ?>"
                   placeholder="<?php _e( 'مثال: 978-0-452-28423-4', 'nadiim' ); ?>" />
        </div>

        <!-- رابط الشراء/المشاهدة -->
        <div class="review-meta-field">
            <label for="review_buy_link"><?php _e( 'رابط الشراء/المشاهدة', 'nadiim' ); ?></label>
            <input type="url" name="review_buy_link" id="review_buy_link"
                   value="<?php echo esc_url( $buy_link ); ?>"
                   placeholder="https://..." />
            <small><?php _e( 'رابط لشراء الكتاب أو مشاهدة الفيلم', 'nadiim' ); ?></small>
        </div>

    </div>

    <script>
    jQuery(document).ready(function($) {
        // التعامل مع النجوم
        $('.rating-stars .star').on('click', function() {
            var rating = $(this).data('rating');
            $('#review_rating').val(rating);

            $('.rating-stars .star').removeClass('active');
            $(this).addClass('active');
            $(this).prevAll('.star').addClass('active');
        });

        // hover effect
        $('.rating-stars .star').on('mouseenter', function() {
            var rating = $(this).data('rating');
            $('.rating-stars .star').removeClass('hover');
            $(this).addClass('hover');
            $(this).prevAll('.star').addClass('hover');
        });

        $('.rating-stars').on('mouseleave', function() {
            $('.rating-stars .star').removeClass('hover');
        });
    });
    </script>

    <?php
}

/**
 * حفظ بيانات Meta Box
 */
function nadiim_save_review_meta( $post_id ) {
    // التحقق من nonce
    if ( ! isset( $_POST['nadiim_review_meta_nonce'] ) ||
         ! wp_verify_nonce( $_POST['nadiim_review_meta_nonce'], 'nadiim_save_review_meta' ) ) {
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

    // حفظ البيانات
    $fields = array(
        'review_rating'       => 'absint',
        'review_item_title'   => 'sanitize_text_field',
        'review_author_name'  => 'sanitize_text_field',
        'review_publisher'    => 'sanitize_text_field',
        'review_publish_year' => 'absint',
        'review_isbn'         => 'sanitize_text_field',
        'review_buy_link'     => 'esc_url_raw',
    );

    foreach ( $fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[ $field ] );
            update_post_meta( $post_id, '_' . $field, $value );
        }
    }
}
add_action( 'save_post_reviews', 'nadiim_save_review_meta' );
