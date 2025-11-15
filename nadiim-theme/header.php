<?php
/**
 * ملف الهيدر (الترويسة)
 *
 * يعرض قسم <head> وكل شيء حتى <div id="content">
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'انتقل إلى المحتوى', 'nadiim' ); ?>
    </a>

    <?php if ( get_theme_mod( 'nadiim_topbar_enable', false ) ) : ?>
        <!-- الشريط العلوي للأحداث القادمة -->
        <div class="topbar" id="topbar">
            <div class="container">
                <div class="topbar-content">
                    <span class="topbar-label">
                        <?php echo nadiim_get_icon( 'calendar' ); ?>
                        <?php echo esc_html( get_theme_mod( 'nadiim_topbar_label', __( 'الأحداث القادمة:', 'nadiim' ) ) ); ?>
                    </span>
                    <div class="topbar-events">
                        <?php
                        // عرض الفعاليات القادمة
                        $upcoming_events = new WP_Query( array(
                            'post_type'      => 'events',
                            'posts_per_page' => get_theme_mod( 'nadiim_topbar_count', 3 ),
                            'meta_key'       => 'event_start_date',
                            'orderby'        => 'meta_value',
                            'order'          => 'ASC',
                            'meta_query'     => array(
                                array(
                                    'key'     => 'event_start_date',
                                    'value'   => current_time( 'Y-m-d H:i' ),
                                    'compare' => '>=',
                                    'type'    => 'DATETIME',
                                ),
                            ),
                        ) );

                        if ( $upcoming_events->have_posts() ) :
                            while ( $upcoming_events->have_posts() ) :
                                $upcoming_events->the_post();
                                $event_start_date = get_post_meta( get_the_ID(), 'event_start_date', true );
                                $event_location = get_post_meta( get_the_ID(), 'event_location', true );
                                ?>
                                <a href="<?php the_permalink(); ?>" class="topbar-event">
                                    <?php if ( $event_start_date ) : ?>
                                        <span class="event-date"><?php echo date_i18n( 'j F', strtotime( $event_start_date ) ); ?></span>
                                    <?php endif; ?>
                                    <span class="event-title"><?php the_title(); ?></span>
                                    <?php if ( $event_location ) : ?>
                                        <span class="event-location">📍 <?php echo esc_html( $event_location ); ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<span class="no-events">' . esc_html__( 'لا توجد فعاليات قادمة حالياً', 'nadiim' ) . '</span>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- الهيدر الرئيسي -->
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-inner">

                <!-- الشعار -->
                <div class="site-branding">
                    <?php
                    if ( has_custom_logo() ) {
                        the_custom_logo();
                    } else {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title-link">
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
                <nav id="site-navigation" class="main-navigation">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="menu-toggle-icon"></span>
                        <span class="screen-reader-text"><?php esc_html_e( 'القائمة', 'nadiim' ); ?></span>
                    </button>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => 'ul',
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </nav>

                <!-- أدوات الهيدر -->
                <div class="header-tools">
                    <!-- زر البحث -->
                    <button class="search-toggle" aria-label="<?php esc_attr_e( 'فتح البحث', 'nadiim' ); ?>">
                        <?php echo nadiim_get_icon( 'search' ); ?>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <!-- نموذج البحث المنبثق -->
    <div class="search-modal" id="search-modal">
        <div class="search-modal-content">
            <button class="search-close" aria-label="<?php esc_attr_e( 'إغلاق البحث', 'nadiim' ); ?>">
                <span>&times;</span>
            </button>
            <div class="container">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-form-inner">
                        <input type="search"
                               class="search-field"
                               placeholder="<?php esc_attr_e( 'ابحث عن حوارات، إصدارات، مقالات...', 'nadiim' ); ?>"
                               value="<?php echo get_search_query(); ?>"
                               name="s"
                               aria-label="<?php esc_attr_e( 'البحث', 'nadiim' ); ?>" />
                        <button type="submit" class="search-submit">
                            <?php echo nadiim_get_icon( 'search' ); ?>
                            <span class="screen-reader-text"><?php esc_html_e( 'بحث', 'nadiim' ); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="content" class="site-content">
