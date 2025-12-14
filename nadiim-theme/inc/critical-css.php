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
            --font-primary:'Cairo','Cairo Fallback',system-ui,-apple-system,sans-serif;
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

        /* Hero Section - Above the fold مع dimensions ثابتة لمنع CLS */
        .hero-slider,.hero-section{
            position:relative;
            width:100%;
            height:450px;
            max-height:60vh;
            background:#f5f5f5;
            contain:layout style;
            overflow:hidden;
        }

        /* تثبيت أبعاد الصور لمنع CLS */
        .hero-slider img,
        .featured-image img{
            width:100%;
            height:100%;
            object-fit:cover;
            object-position:center;
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
            .hero-slider,.hero-section{height:350px;max-height:50vh}
            .container{padding:0 0.75rem}
        }

        /* منع FOUT - استخدام font fallback بدلاً من إخفاء النص */
        body{
            font-family:var(--font-primary);
        }

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php
    // Preload لصورة Hero (LCP element) في الصفحة الرئيسية
    if ( is_front_page() ) {
        // الحصول على صورة Hero من Customizer
        $hero_image = get_theme_mod( 'hero_slider_slide_1_image' );
        if ( $hero_image ) {
            echo '<link rel="preload" href="' . esc_url( $hero_image ) . '" as="image" fetchpriority="high">';
        }
    }

    // Preload للصورة المميزة في المقالات
    if ( is_singular() && has_post_thumbnail() ) {
        $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        if ( $thumbnail_url ) {
            echo '<link rel="preload" href="' . esc_url( $thumbnail_url ) . '" as="image" fetchpriority="high">';
        }
    }
    ?>

    <!-- Preload الخط الرئيسي woff2 -->
    <link rel="preload" href="https://fonts.gstatic.com/s/cairo/v28/SLXgc1nY6HkvangtZmpcWmhzfH5lkSs2.woff2" as="font" type="font/woff2" crossorigin>
    <?php
}
add_action( 'wp_head', 'nadiim_performance_resource_hints', 0 );

/**
 * تحميل الخطوط بطريقة محسّنة - استخدام font-display: optional
 */
function nadiim_optimized_fonts_loading() {
    ?>
    <style>
    /* Fallback font لمنع Layout Shift */
    @font-face {
        font-family: 'Cairo Fallback';
        src: local('Arial'), local('Helvetica');
        size-adjust: 105%;
        ascent-override: 95%;
        descent-override: 25%;
        line-gap-override: 0%;
    }
    </style>
    <!-- تحميل الخطوط مع font-display: optional لأقصى سرعة -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400&display=optional" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400&display=optional">
    </noscript>
    <?php
}
add_action( 'wp_head', 'nadiim_optimized_fonts_loading', 2 );

/**
 * تحميل أوزان الخطوط الإضافية بشكل مؤجل جداً
 */
function nadiim_load_additional_font_weights() {
    ?>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700&display=optional" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700&display=optional">
    </noscript>
    <?php
}
add_action( 'wp_footer', 'nadiim_load_additional_font_weights', 1 ); // نقلناها للفوتر!

/**
 * تأجيل تحميل CSS غير الحرج فقط
 * لا نؤجل header.css لمنع Layout Shift
 */
function nadiim_defer_non_critical_css() {
    // قائمة ملفات CSS التي سيتم تأجيل تحميلها (غير حرجة فقط)
    $deferred_styles = array(
        'nadiim-enhancements',
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
    (function() {
        function loadDeferredStyles() {
            var styles = [
                '<?php echo esc_url( NADIIM_THEME_URI . '/assets/css/enhancements.css' ); ?>',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'
            ];

            styles.forEach(function(href) {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                link.media = 'all';
                document.head.appendChild(link);
            });
        }

        // استخدام requestIdleCallback أو load event
        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadDeferredStyles, { timeout: 2000 });
        } else {
            if (document.readyState === 'complete') {
                loadDeferredStyles();
            } else {
                window.addEventListener('load', loadDeferredStyles);
            }
        }
    })();
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
 * تحسين الحركات - تقليل عدواني للحركات المركبة
 */
function nadiim_optimize_animations_css() {
    ?>
    <style id="nadiim-animations-optimized">
        /* استخدام GPU acceleration فقط للـ slider */
        .hero-slider .slide {
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* إزالة will-change من جميع العناصر */
        /* نضيفها فقط عند الحاجة الحقيقية */

        /* استخدام transitions خفيفة جداً */
        .card,
        .modal,
        .dropdown-menu,
        .mobile-menu {
            transition: opacity 0.2s ease;
            /* لا transform ولا will-change */
        }

        /* منع Forced Synchronous Layout */
        .hero-slider,
        .card-grid {
            contain: layout;
            /* إزالة style من contain */
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
