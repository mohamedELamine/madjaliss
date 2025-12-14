<?php
/**
 * تحسين الصور للأداء
 *
 * نظام شامل لتحسين الصور وضغطها لتحسين أداء الموقع
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة أحجام صور محسّنة للجوال
 */
function nadiim_add_responsive_image_sizes() {
    // أحجام للجوال
    add_image_size( 'nadiim-mobile-small', 480, 320, true );
    add_image_size( 'nadiim-mobile-medium', 768, 512, true );

    // أحجام للتابلت
    add_image_size( 'nadiim-tablet', 1024, 683, true );

    // أحجام للديسكتوب
    add_image_size( 'nadiim-desktop', 1920, 1080, true );

    // Hero images محسّنة
    add_image_size( 'nadiim-hero-mobile', 768, 400, true );
    add_image_size( 'nadiim-hero-desktop', 1920, 600, true );
}
add_action( 'after_setup_theme', 'nadiim_add_responsive_image_sizes' );

/**
 * تحسين جودة الصور (ضغط أفضل)
 */
function nadiim_optimize_image_quality( $quality, $mime_type ) {
    // تقليل الجودة قليلاً لتقليل حجم الملف مع الحفاظ على الجودة
    switch ( $mime_type ) {
        case 'image/jpeg':
            return 82; // بدلاً من 90 الافتراضية
        case 'image/webp':
            return 85;
        default:
            return $quality;
    }
}
add_filter( 'wp_editor_set_quality', 'nadiim_optimize_image_quality', 10, 2 );
add_filter( 'jpeg_quality', 'nadiim_optimize_image_quality', 10, 2 );

/**
 * إضافة srcset للصور responsive
 */
function nadiim_add_responsive_images( $html, $post_id, $post_thumbnail_id ) {
    if ( empty( $html ) ) {
        return $html;
    }

    $image_sizes = array(
        '(max-width: 480px)' => 'nadiim-mobile-small',
        '(max-width: 768px)' => 'nadiim-mobile-medium',
        '(max-width: 1024px)' => 'nadiim-tablet',
        '(min-width: 1025px)' => 'nadiim-desktop'
    );

    // إضافة srcset
    $srcset = wp_get_attachment_image_srcset( $post_thumbnail_id, 'full' );
    if ( $srcset && strpos( $html, 'srcset=' ) === false ) {
        $html = str_replace( '<img ', '<img srcset="' . esc_attr( $srcset ) . '" ', $html );
    }

    // إضافة sizes
    $sizes = wp_get_attachment_image_sizes( $post_thumbnail_id, 'full' );
    if ( $sizes && strpos( $html, 'sizes=' ) === false ) {
        $html = str_replace( '<img ', '<img sizes="' . esc_attr( $sizes ) . '" ', $html );
    }

    return $html;
}
add_filter( 'post_thumbnail_html', 'nadiim_add_responsive_images', 10, 3 );

/**
 * تحسين الصور للـ LCP (Largest Contentful Paint)
 */
function nadiim_optimize_lcp_images( $attr, $attachment, $size ) {
    // تحديد ما إذا كانت الصورة في Above the Fold
    static $first_image_loaded = false;

    if ( ! $first_image_loaded && ( is_front_page() || is_singular() ) ) {
        // أول صورة: eager loading + fetchpriority high
        $attr['loading'] = 'eager';
        $attr['fetchpriority'] = 'high';
        $attr['decoding'] = 'sync';
        $first_image_loaded = true;
    } else {
        // باقي الصور: lazy loading
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
    }

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'nadiim_optimize_lcp_images', 10, 3 );

/**
 * إضافة WebP support تلقائياً
 */
function nadiim_enable_webp_support( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter( 'mime_types', 'nadiim_enable_webp_support' );

/**
 * منع تحميل الصور الكبيرة جداً
 */
function nadiim_limit_upload_image_size( $file ) {
    $max_size = 2 * 1024 * 1024; // 2MB

    if ( $file['size'] > $max_size && strpos( $file['type'], 'image/' ) === 0 ) {
        $file['error'] = 'حجم الصورة كبير جداً. الحد الأقصى هو 2 ميجابايت.';
    }

    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'nadiim_limit_upload_image_size' );

/**
 * تحسين thumbnail regeneration
 */
function nadiim_optimize_thumbnail_generation( $metadata, $attachment_id ) {
    if ( ! isset( $metadata['sizes'] ) ) {
        return $metadata;
    }

    // إزالة الأحجام غير المستخدمة
    $used_sizes = array(
        'thumbnail',
        'medium',
        'large',
        'nadiim-mobile-small',
        'nadiim-mobile-medium',
        'nadiim-tablet',
        'nadiim-hero-mobile',
        'nadiim-hero-desktop'
    );

    foreach ( $metadata['sizes'] as $size => $data ) {
        if ( ! in_array( $size, $used_sizes ) ) {
            unset( $metadata['sizes'][ $size ] );
        }
    }

    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'nadiim_optimize_thumbnail_generation', 10, 2 );

/**
 * إضافة blur placeholder للصور (progressive loading)
 */
function nadiim_add_blur_placeholder( $html, $post_id, $post_thumbnail_id ) {
    // إضافة blur-up effect
    $style = 'style="background: linear-gradient(to bottom, #f0f0f0, #e0e0e0); backdrop-filter: blur(10px);"';

    if ( strpos( $html, 'style=' ) === false ) {
        $html = str_replace( '<img ', '<img ' . $style . ' ', $html );
    }

    return $html;
}
// يتم تعطيل هذا افتراضياً لتجنب التأثير على التصميم
// add_filter( 'post_thumbnail_html', 'nadiim_add_blur_placeholder', 10, 3 );

/**
 * تحسين alt text للصور (SEO + Accessibility)
 */
function nadiim_auto_add_alt_text( $attr, $attachment ) {
    if ( empty( $attr['alt'] ) ) {
        $post = get_post( $attachment->ID );

        // استخدام عنوان الصورة كـ alt
        $attr['alt'] = $post->post_title;

        // إذا كان فارغاً، استخدم اسم الملف
        if ( empty( $attr['alt'] ) ) {
            $attr['alt'] = basename( get_attached_file( $attachment->ID ), '.' . pathinfo( get_attached_file( $attachment->ID ), PATHINFO_EXTENSION ) );
        }
    }

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'nadiim_auto_add_alt_text', 10, 2 );
