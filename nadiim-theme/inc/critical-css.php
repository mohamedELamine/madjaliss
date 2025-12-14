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
 * محسّن للأداء على الجوال وسرعة التحميل
 */
function nadiim_add_critical_css() {
    ?>
    <style id="nadiim-critical-css">
        /* Critical CSS للتحميل السريع - محسّن للجوال */

        /* إعادة التعيين الأساسية */
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}

        /* الخطوط والألوان الأساسية */
        :root{
            --color-primary:#339063;
            --color-primary-light:#4db080;
            --color-text-primary:#2c3e50;
            --color-bg-light:#fafafa;
            --color-bg-lighter:#fff;
            --font-primary:'Cairo',system-ui,-apple-system,sans-serif;
            --transition-speed:0.2s;
        }

        /* تحسين العرض الأولي */
        html{font-size:16px;-webkit-text-size-adjust:100%}
        body{
            font-family:var(--font-primary);
            font-size:1rem;
            line-height:1.8;
            color:var(--color-text-primary);
            background-color:var(--color-bg-light);
            direction:rtl;
            text-align:right;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            overflow-x:hidden;
            min-height:100vh;
        }

        /* الهيدر الأساسي - محسّن */
        .site-header{
            background:#fff;
            border-bottom:1px solid rgba(0,0,0,0.06);
            position:sticky;
            top:0;
            z-index:999;
            will-change:transform;
        }

        .header-container{
            max-width:1200px;
            margin:0 auto;
            padding:0 1rem;
        }

        .header-inner{
            display:flex;
            align-items:center;
            justify-content:space-between;
            min-height:70px;
        }

        /* الشعار - محسّن */
        .site-branding{line-height:0}
        .site-branding img{
            max-width:160px;
            height:auto;
            width:auto;
        }

        /* القائمة الرئيسية */
        .primary-menu{
            display:flex;
            list-style:none;
            gap:0.5rem;
            margin:0;
        }

        .primary-menu a{
            color:#1c2d27;
            text-decoration:none;
            padding:0.5rem 0.75rem;
            display:block;
            font-size:0.95rem;
            transition:color var(--transition-speed);
        }

        /* Hero Section - Above the fold */
        .hero-slider,.hero-section{
            position:relative;
            min-height:400px;
            background:#f5f5f5;
            contain:layout style paint;
        }

        /* المحتوى الأساسي */
        .site-content{min-height:50vh}

        .container{
            width:100%;
            max-width:1200px;
            margin:0 auto;
            padding:0 1rem;
        }

        /* تحسين الصور */
        img{
            max-width:100%;
            height:auto;
            display:block;
            border:0;
        }

        /* تحسين الروابط */
        a{color:inherit;transition:color var(--transition-speed)}
        a:hover{color:var(--color-primary)}

        /* تحسين الأزرار */
        button,.btn{
            cursor:pointer;
            border:0;
            background:none;
            font-family:inherit;
            font-size:inherit;
        }

        /* Skeleton للتحميل - مصغّر */
        .loading-skeleton{
            background:linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%);
            background-size:200% 100%;
            animation:loading 1.5s infinite;
        }

        @keyframes loading{
            0%{background-position:200% 0}
            100%{background-position:-200% 0}
        }

        /* تحسين للجوال */
        @media (max-width:768px){
            html{font-size:14px}
            .header-inner{min-height:60px}
            .site-branding img{max-width:140px}
            .primary-menu{display:none}
            .mobile-menu-toggle{display:block}
            .hero-slider,.hero-section{min-height:300px}
            .container{padding:0 0.75rem}
        }

        /* منع FOUT (Flash of Unstyled Text) */
        .wf-loading body{opacity:0}
        .wf-active body,.wf-inactive body{opacity:1;transition:opacity 0.2s}

        /* تحسين CLS للعناصر الديناميكية */
        .topbar-marquee,.search-modal{
            content-visibility:auto;
            contain-intrinsic-size:0 50px;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_add_critical_css', 1 );

/**
 * إضافة Resource Hints لتحسين الأداء
 */
function nadiim_performance_resource_hints() {
    ?>
    <!-- DNS Prefetch للموارد الخارجية -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <!-- Preconnect للموارد الحرجة -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Preload للخطوط الحرجة -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" as="style">
    <link rel="preload" href="https://fonts.gstatic.com/s/cairo/v28/SLXgc1nY6HkvangtZmpcWmhzfH5lkSs.woff2" as="font" type="font/woff2" crossorigin>
    <?php
}
add_action( 'wp_head', 'nadiim_performance_resource_hints', 0 );

/**
 * تحميل الخطوط بطريقة محسّنة للأداء
 */
function nadiim_optimized_fonts_loading() {
    ?>
    <!-- تحميل الخطوط المحسّن -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" media="print" onload="this.media='all';this.onload=null;">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap">
    </noscript>
    <?php
}
add_action( 'wp_head', 'nadiim_optimized_fonts_loading', 2 );

/**
 * تأجيل تحميل CSS غير الحرج
 */
function nadiim_defer_non_critical_css() {
    // قائمة ملفات CSS التي سيتم تأجيل تحميلها
    $deferred_styles = array(
        'nadiim-enhancements',
        'nadiim-header',
        'nadiim-performance',
        'font-awesome'
    );

    foreach ( $deferred_styles as $handle ) {
        wp_dequeue_style( $handle );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_defer_non_critical_css', 100 );

/**
 * إضافة ملفات CSS المؤجلة في الفوتر
 */
function nadiim_load_deferred_css() {
    ?>
    <script>
    // تحميل CSS المؤجلة بعد التحميل الكامل
    if ('requestIdleCallback' in window) {
        requestIdleCallback(function() {
            loadDeferredStyles();
        });
    } else {
        window.addEventListener('load', loadDeferredStyles);
    }

    function loadDeferredStyles() {
        var styles = [
            '<?php echo esc_url( NADIIM_THEME_URI . '/assets/css/enhancements.css' ); ?>',
            '<?php echo esc_url( NADIIM_THEME_URI . '/assets/css/header.css' ); ?>',
            '<?php echo esc_url( NADIIM_THEME_URI . '/assets/css/performance.css' ); ?>',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'
        ];

        styles.forEach(function(href) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            document.head.appendChild(link);
        });
    }
    </script>
    <?php
}
add_action( 'wp_footer', 'nadiim_load_deferred_css', 1 );

/**
 * إزالة CSS و JS غير الضرورية
 */
function nadiim_remove_unused_assets() {
    // إزالة Emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

    // إزالة WordPress embed script
    wp_dequeue_script( 'wp-embed' );

    // إزالة jQuery migrate
    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.min.js' ), false, null, true );
        wp_enqueue_script( 'jquery' );
    }
}
add_action( 'init', 'nadiim_remove_unused_assets' );

/**
 * تحسين الحركات لتجنب Layout Shift
 */
function nadiim_optimize_animations_css() {
    ?>
    <style id="nadiim-animations-optimized">
        /* استخدام GPU acceleration للحركات */
        .hero-slider .slide,
        .card,
        .modal,
        .mobile-menu,
        .search-modal,
        .dropdown-menu {
            will-change: transform, opacity;
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* استخدام transform بدلاً من top/left/margin */
        .slide-animation {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* تحسين الحركات للعناصر المتحركة */
        @media (prefers-reduced-motion: reduce) {
            *,*::before,*::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'nadiim_optimize_animations_css', 3 );
