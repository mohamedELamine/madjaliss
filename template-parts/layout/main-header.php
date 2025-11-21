<?php
/**
 * Main Header Template
 *
 * @package Madjaliss
 * @version 2.0
 */

// الحصول على إعدادات Customizer
$logo = get_theme_mod('header_logo', '');
$logo_width = get_theme_mod('header_logo_width', 180);
$header_sticky = get_theme_mod('header_sticky_enable', true);
$search_enable = get_theme_mod('header_search_enable', true);
$search_placeholder = get_theme_mod('header_search_placeholder', 'ابحث في مجالس...');
$menu_alignment = get_theme_mod('header_menu_alignment', 'center');

// إضافة class للـ sticky
$header_classes = array('site-header');
if ($header_sticky) {
    $header_classes[] = 'header-sticky-enabled';
}
?>

<header class="<?php echo esc_attr(implode(' ', $header_classes)); ?>" id="masthead">
    <div class="container">
        <div class="header-inner">

            <!-- Logo -->
            <div class="site-branding">
                <?php if (!empty($logo)) : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                        <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>" class="custom-logo" style="width: <?php echo esc_attr($logo_width); ?>px; height: auto;">
                    </a>
                <?php else : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) :
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Primary Navigation -->
            <nav class="main-navigation" style="text-align: <?php echo esc_attr($menu_alignment); ?>;" role="navigation" aria-label="<?php esc_attr_e('القائمة الرئيسية', 'madjaliss'); ?>">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                } else {
                    // رسالة للمدير لإنشاء قائمة
                    if (current_user_can('manage_options')) {
                        echo '<div class="no-menu-message">';
                        echo '<a href="' . esc_url(admin_url('nav-menus.php')) . '">إنشاء قائمة رئيسية</a>';
                        echo '</div>';
                    }
                }
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">

                <?php if ($search_enable) : ?>
                    <!-- Search Button -->
                    <button class="search-toggle" aria-label="<?php esc_attr_e('فتح البحث', 'madjaliss'); ?>" aria-expanded="false">
                        <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </button>
                <?php endif; ?>

            </div>

        </div><!-- .header-inner -->
    </div><!-- .container -->

    <?php if ($search_enable) : ?>
        <!-- Search Overlay -->
        <div class="search-overlay" id="search-overlay">
            <div class="search-overlay-inner">
                <button class="search-close" aria-label="<?php esc_attr_e('إغلاق البحث', 'madjaliss'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

                <div class="search-container">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <label for="search-field">
                            <span class="screen-reader-text"><?php esc_html_e('البحث عن:', 'madjaliss'); ?></span>
                        </label>
                        <input type="search" id="search-field" class="search-field" placeholder="<?php echo esc_attr($search_placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off">
                        <button type="submit" class="search-submit">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                            <span class="screen-reader-text"><?php esc_html_e('بحث', 'madjaliss'); ?></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

</header><!-- #masthead -->
