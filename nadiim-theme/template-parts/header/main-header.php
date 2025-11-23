<?php
/**
 * Template part للهيدر الرئيسي (Main Header)
 * تصميم جديد متجاوب بالكامل
 *
 * @package Nadiim
 * @since 1.0.0
 */

$sticky_enabled        = get_theme_mod( 'header_sticky_enable', true );
$sticky_shadow_enabled = get_theme_mod( 'header_sticky_shadow_enable', true );
$search_enabled        = get_theme_mod( 'header_search_enable', true );
$search_placeholder    = get_theme_mod( 'header_search_placeholder', __( 'ابحث عن حوارات، إصدارات، مقالات...', 'nadiim' ) );

$header_classes = array( 'site-header' );
if ( $sticky_enabled ) {
    $header_classes[] = 'header-sticky';
}
if ( $sticky_shadow_enabled ) {
    $header_classes[] = 'header-has-shadow';
}
?>

<header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>" role="banner">
    <div class="header-container">
        <div class="header-inner">

            <!-- زر القائمة للموبايل (يظهر فقط في الشاشات الصغيرة على اليمين) -->
            <button class="mobile-menu-toggle"
                    aria-controls="primary-menu"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'القائمة', 'nadiim' ); ?>">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- الشعار (على اليمين في الشاشات الكبيرة، في المنتصف في الموبايل) -->
            <div class="site-branding">
                <?php
                $header_logo = get_theme_mod( 'header_logo' );

                if ( $header_logo ) {
                    $logo_url = wp_get_attachment_image_url( $header_logo, 'full' );
                    if ( $logo_url ) {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
                            <img src="<?php echo esc_url( $logo_url ); ?>" class="custom-logo" alt="<?php bloginfo( 'name' ); ?>" />
                        </a>
                        <?php
                    }
                } elseif ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title-link" rel="home">
                        <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                    </a>
                    <?php
                }
                ?>
            </div>

            <!-- القائمة الرئيسية (في الوسط في الشاشات الكبيرة، overlay كامل في الموبايل) -->
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'القائمة الرئيسية', 'nadiim' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>

            <!-- أيقونة البحث (على اليسار) -->
            <?php if ( $search_enabled ) : ?>
                <div class="header-search">
                    <button class="search-toggle"
                            aria-label="<?php esc_attr_e( 'فتح البحث', 'nadiim' ); ?>"
                            aria-expanded="false"
                            aria-controls="search-modal">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            <?php endif; ?>

        </div>
    </div>
</header>

<?php
// نافذة البحث المنبثقة (Modal)
if ( $search_enabled ) :
    ?>
    <div class="search-modal" id="search-modal" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
        <div class="search-modal-overlay"></div>
        <div class="search-modal-content">
            <div class="search-modal-header">
                <h2 id="search-modal-title" class="screen-reader-text"><?php esc_html_e( 'البحث', 'nadiim' ); ?></h2>
                <button class="search-close"
                        aria-label="<?php esc_attr_e( 'إغلاق البحث', 'nadiim' ); ?>">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="search-form-container">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-form-inner">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="search"
                               class="search-field"
                               placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
                               value="<?php echo get_search_query(); ?>"
                               name="s"
                               aria-label="<?php esc_attr_e( 'البحث', 'nadiim' ); ?>"
                               autofocus />
                        <button type="submit" class="search-submit">
                            <?php esc_html_e( 'بحث', 'nadiim' ); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
