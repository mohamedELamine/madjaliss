<?php
/**
 * Browser Caching - تحسين التخزين المؤقت
 *
 * إضافة headers للتخزين المؤقت الطويل للموارد الثابتة
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة Cache-Control headers للموارد الثابتة
 */
function nadiim_add_cache_headers() {
    // لا نضيف headers في الـ admin
    if ( is_admin() ) {
        return;
    }

    // Headers للموارد الثابتة
    header( 'Cache-Control: public, max-age=31536000', false ); // 1 سنة للموارد الثابتة
}

/**
 * إضافة headers للـ CSS و JS
 */
function nadiim_cache_static_assets( $tag, $handle ) {
    // إضافة معاملات cache busting تلقائياً
    if ( strpos( $tag, '.css' ) !== false || strpos( $tag, '.js' ) !== false ) {
        // الملفات الموجودة في theme directory
        if ( strpos( $tag, NADIIM_THEME_URI ) !== false ) {
            // إضافة version للـ cache busting
            $tag = str_replace( '?ver=', '?v=', $tag );
        }
    }
    return $tag;
}
add_filter( 'style_loader_tag', 'nadiim_cache_static_assets', 10, 2 );
add_filter( 'script_loader_tag', 'nadiim_cache_static_assets', 10, 2 );

/**
 * إنشاء ملف .htaccess لـ Apache (إذا لم يكن موجوداً)
 */
function nadiim_create_htaccess_cache_rules() {
    // التحقق من وجود Apache
    if ( ! function_exists( 'apache_get_modules' ) && ! isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
        return;
    }

    $htaccess_file = NADIIM_THEME_DIR . '/.htaccess';

    // إذا كان الملف موجوداً، لا نفعل شيء
    if ( file_exists( $htaccess_file ) ) {
        return;
    }

    $htaccess_content = <<<'HTACCESS'
# Browser Caching للأداء
<IfModule mod_expires.c>
    ExpiresActive On

    # الصور
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/x-icon "access plus 1 year"

    # CSS و JavaScript
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"

    # الخطوط
    ExpiresByType font/ttf "access plus 1 year"
    ExpiresByType font/otf "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType application/font-woff "access plus 1 year"

    # HTML و XML
    ExpiresByType text/html "access plus 1 hour"
    ExpiresByType text/xml "access plus 1 hour"
    ExpiresByType application/xml "access plus 1 hour"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Brotli Compression (إذا كان متوفراً)
<IfModule mod_brotli.c>
    AddOutputFilterByType BROTLI_COMPRESS text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
HTACCESS;

    // محاولة إنشاء الملف
    @file_put_contents( $htaccess_file, $htaccess_content );
}
add_action( 'after_setup_theme', 'nadiim_create_htaccess_cache_rules' );

/**
 * إضافة headers للصور
 */
function nadiim_image_cache_headers( $image, $attachment_id ) {
    // إضافة cache headers للصور
    if ( ! headers_sent() ) {
        header( 'Cache-Control: public, max-age=31536000, immutable' );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
    }
    return $image;
}
add_filter( 'wp_get_attachment_image_src', 'nadiim_image_cache_headers', 10, 2 );
