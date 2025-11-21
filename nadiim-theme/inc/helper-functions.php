<?php
/**
 * دوال مساعدة عامة - منفصل من functions.php
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * دالة مساعدة لاستخراج قيمة string آمنة من مصفوفة المشارك
 *
 * تمنع خطأ "Array to string conversion" بالتحقق من نوع البيانات
 *
 * @param array  $participant مصفوفة بيانات المشارك
 * @param string $key مفتاح الحقل المطلوب
 * @param string $default القيمة الافتراضية
 * @return string
 */
function nadiim_get_participant_field( $participant, $key, $default = '' ) {
    if ( ! is_array( $participant ) || ! isset( $participant[ $key ] ) ) {
        return $default;
    }

    $value = $participant[ $key ];

    // إذا كانت القيمة string، نعيدها مباشرة
    if ( is_string( $value ) ) {
        return $value;
    }

    // إذا كانت array، نحولها إلى string بدمج العناصر
    if ( is_array( $value ) ) {
        return implode( ' ', array_filter( $value, 'is_string' ) );
    }

    // إذا كانت رقم، نحولها إلى string
    if ( is_numeric( $value ) ) {
        return (string) $value;
    }

    // في أي حالة أخرى، نعيد القيمة الافتراضية
    return $default;
}

/**
 * دالة مساعدة للحصول على مقتطف مخصص
 *
 * @param int $length طول المقتطف بعدد الكلمات
 * @param int|null $post_id معرف المنشور
 * @return string
 */
function nadiim_get_excerpt( $length = 30, $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $excerpt = get_the_excerpt( $post_id );

    if ( empty( $excerpt ) ) {
        $excerpt = get_the_content( null, false, $post_id );
        $excerpt = strip_shortcodes( $excerpt );
        $excerpt = wp_strip_all_tags( $excerpt );
    }

    if ( str_word_count( $excerpt ) > $length ) {
        $words = str_word_count( $excerpt, 2, 'ءآأؤإئابةتثجحخدذرزسشصضطظعغفقكلمنهوىيٱٲٳٴٵٶٷٸٹٺٻټٽپٿڀځڂڃڄڅچڇڈډڊڋڌڍڎڏڐڑڒړڔڕږڗژڙښڛڜڝڞڟڠڡڢڣڤڥڦڧڨکڪګڬڭڮگڰڱڲڳڴڵڶڷڸڹںڻڼڽھڿۀہۂۃۄۅۆۇۈۉۊۋیۍێۏېۑےۓ۔ەۖۗۘۙۚۛۜ۝۞ۣ۟۠ۡۢۤۥۦۧۨ۩۪ۭ۫۬ۮۯ۰۱۲۳۴۵۶۷۸۹ۺۻۼ۽۾ۿ' );
        $words = array_slice( $words, 0, $length, true );
        end( $words );
        $position = key( $words ) + strlen( current( $words ) );
        $excerpt = substr( $excerpt, 0, $position ) . '...';
    }

    return $excerpt;
}

/**
 * دالة مساعدة لعرض أيقونة SVG
 *
 * @param string $icon_name اسم الأيقونة
 * @return string SVG markup
 */
function nadiim_get_icon( $icon_name ) {
    $icons = array(
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'book' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
        'video' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>',
        'audio' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>',
        'document' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
        'download' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>',
    );

    return isset( $icons[ $icon_name ] ) ? $icons[ $icon_name ] : '';
}

/**
 * دالة مساعدة لعرض قائمة التصنيفات
 *
 * @param int    $post_id معرف المنشور
 * @param string $taxonomy التصنيف
 * @param string $separator الفاصل
 * @return string
 */
function nadiim_get_post_terms( $post_id, $taxonomy, $separator = ', ' ) {
    $terms = get_the_terms( $post_id, $taxonomy );

    if ( ! $terms || is_wp_error( $terms ) ) {
        return '';
    }

    $term_links = array();
    foreach ( $terms as $term ) {
        $term_links[] = sprintf(
            '<a href="%s" class="badge badge-outline">%s</a>',
            esc_url( get_term_link( $term ) ),
            esc_html( $term->name )
        );
    }

    return implode( $separator, $term_links );
}

/**
 * إضافة دعم Lazy Loading للصور
 *
 * @param string $content المحتوى
 * @return string
 */
function nadiim_add_lazy_loading( $content ) {
    if ( is_admin() ) {
        return $content;
    }

    $content = str_replace( '<img ', '<img loading="lazy" ', $content );
    return $content;
}
add_filter( 'the_content', 'nadiim_add_lazy_loading' );
add_filter( 'post_thumbnail_html', 'nadiim_add_lazy_loading' );

/**
 * AJAX Handler للنشرة البريدية
 */
function nadiim_subscribe_newsletter() {
    // التحقق من الأمان
    check_ajax_referer( 'nadiim-front-page-nonce', 'nonce' );

    // الحصول على البريد الإلكتروني
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

    // التحقق من صحة البريد الإلكتروني
    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( array(
            'message' => __( 'يرجى إدخال بريد إلكتروني صحيح', 'nadiim' ),
        ) );
    }

    // حفظ البريد الإلكتروني في قاعدة البيانات
    $subscribers = get_option( 'nadiim_newsletter_subscribers', array() );

    // التحقق من عدم وجود البريد مسبقاً
    if ( in_array( $email, $subscribers ) ) {
        wp_send_json_error( array(
            'message' => __( 'هذا البريد الإلكتروني مشترك بالفعل', 'nadiim' ),
        ) );
    }

    // إضافة البريد إلى القائمة
    $subscribers[] = $email;
    update_option( 'nadiim_newsletter_subscribers', $subscribers );

    // إرسال إشعار للمدير (اختياري)
    $admin_email = get_option( 'admin_email' );
    $subject     = __( 'اشتراك جديد في النشرة البريدية', 'nadiim' );
    $message     = sprintf( __( 'اشترك %s في النشرة البريدية', 'nadiim' ), $email );
    wp_mail( $admin_email, $subject, $message );

    // إرسال استجابة النجاح
    wp_send_json_success( array(
        'message' => __( 'تم الاشتراك بنجاح! شكراً لك.', 'nadiim' ),
    ) );
}
add_action( 'wp_ajax_nadiim_subscribe_newsletter', 'nadiim_subscribe_newsletter' );
add_action( 'wp_ajax_nopriv_nadiim_subscribe_newsletter', 'nadiim_subscribe_newsletter' );
