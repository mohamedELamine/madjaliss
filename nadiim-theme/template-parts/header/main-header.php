<?php
/**
 * Template part للهيدر الرئيسي (Main Header)
 *
 * @package Nadiim
 * @since 1.0.0
 */

$sticky_enabled        = get_theme_mod( 'header_sticky_enable', true );
$sticky_shadow_enabled = get_theme_mod( 'header_sticky_shadow_enable', true );
$search_enabled        = get_theme_mod( 'header_search_enable', true );
$search_position       = get_theme_mod( 'header_search_position', 'modal' );
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
    <div class="container">
        <div class="header-inner">

            <!-- الشعار -->
            <div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title-link" rel="home">
                        <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                    </a>
                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) :
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php } ?>
            </div>

            <!-- القائمة الرئيسية -->
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'القائمة الرئيسية', 'nadiim' ); ?>">
                <button class="menu-toggle"
                        aria-controls="primary-menu"
                        aria-expanded="false"
                        aria-label="<?php esc_attr_e( 'القائمة', 'nadiim' ); ?>">
                    <span class="menu-toggle-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="menu-toggle-text"><?php esc_html_e( 'القائمة', 'nadiim' ); ?></span>
                </button>

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

            <!-- أدوات الهيدر -->
            <?php if ( $search_enabled ) : ?>
                <div class="header-tools">
                    <?php if ( $search_position === 'inline' ) : ?>
                        <!-- بحث inline -->
                        <form role="search" method="get" class="search-form search-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="search"
                                   class="search-field"
                                   placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
                                   value="<?php echo get_search_query(); ?>"
                                   name="s"
                                   aria-label="<?php esc_attr_e( 'البحث', 'nadiim' ); ?>" />
                            <button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'بحث', 'nadiim' ); ?>">
                                <?php echo nadiim_get_icon( 'search' ); ?>
                            </button>
                        </form>
                    <?php else : ?>
                        <!-- زر فتح نافذة البحث -->
                        <button class="search-toggle"
                                aria-label="<?php esc_attr_e( 'فتح البحث', 'nadiim' ); ?>"
                                aria-expanded="false"
                                aria-controls="search-modal">
                            <?php echo nadiim_get_icon( 'search' ); ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</header>

<?php
// نافذة البحث المنبثقة (Modal)
if ( $search_enabled && $search_position === 'modal' ) :
    ?>
    <div class="search-modal" id="search-modal" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
        <div class="search-modal-overlay"></div>
        <div class="search-modal-content">
            <div class="search-modal-header">
                <h2 id="search-modal-title" class="screen-reader-text"><?php esc_html_e( 'البحث', 'nadiim' ); ?></h2>
                <button class="search-close"
                        aria-label="<?php esc_attr_e( 'إغلاق البحث', 'nadiim' ); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="container">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-form-inner">
                        <input type="search"
                               class="search-field"
                               placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
                               value="<?php echo get_search_query(); ?>"
                               name="s"
                               aria-label="<?php esc_attr_e( 'البحث', 'nadiim' ); ?>"
                               autofocus />
                        <button type="submit" class="search-submit">
                            <?php echo nadiim_get_icon( 'search' ); ?>
                            <span class="search-submit-text"><?php esc_html_e( 'بحث', 'nadiim' ); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
