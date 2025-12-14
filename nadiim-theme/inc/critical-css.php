<?php
/**
 * Critical CSS - تحميل الأنماط الحرجة inline
 *
 * هذا الملف يحتوي على CSS الأساسية التي يتم تحميلها inline
 * لتحسين FCP (First Contentful Paint)
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة Critical CSS في الـ head
 */
function nadiim_add_critical_css() {
    ?>
    <style id="nadiim-critical-css">
        /* Critical CSS للتحميل السريع */

        /* إعادة التعيين الأساسية */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* الخطوط والألوان الأساسية */
        :root {
            --color-primary: #339063;
            --color-text-primary: #2c3e50;
            --color-bg-light: #fafafa;
            --color-bg-lighter: #ffffff;
            --font-primary: 'Cairo', sans-serif;
        }

        body {
            font-family: var(--font-primary);
            font-size: 18px;
            line-height: 1.8;
            color: var(--color-text-primary);
            background-color: var(--color-bg-light);
            direction: rtl;
            text-align: right;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* الهيدر الأساسي */
        .site-header {
            background: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 80px;
        }

        /* الشعار */
        .site-branding img {
            max-width: 180px;
            height: auto;
        }

        /* القائمة الرئيسية - نسخة مبسطة للـ critical CSS */
        .primary-menu {
            display: flex;
            list-style: none;
            gap: 15px;
        }

        .primary-menu a {
            color: #1c2d27;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        /* إخفاء عناصر غير حرجة حتى يتم تحميل CSS الكامل */
        .topbar-marquee,
        .search-modal,
        .mobile-menu-toggle {
            display: none;
        }

        /* المحتوى الأساسي */
        .site-content {
            min-height: 50vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* تحسين الصور */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Skeleton للتحميل */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* تحسين Above the Fold */
        @media (max-width: 768px) {
            .primary-menu {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_add_critical_css', 1 );

/**
 * إضافة preload للـ CSS الكاملة
 */
function nadiim_preload_full_css() {
    ?>
    <link rel="preload" href="<?php echo esc_url( NADIIM_THEME_URI . '/assets/css/main.css' ); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo esc_url( NADIIM_THEME_URI . '/assets/css/main.css' ); ?>">
    </noscript>
    <?php
}
add_action( 'wp_head', 'nadiim_preload_full_css', 2 );
