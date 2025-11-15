<?php
/**
 * ملف الفوتر (التذييل)
 *
 * يحتوي على إغلاق div#content وجميع المحتويات بعده
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

    </div><!-- #content -->

    <!-- الفوتر الرئيسي -->
    <footer id="colophon" class="site-footer">

        <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
            <!-- منطقة الودجات في الفوتر -->
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widgets-inner grid grid-4">
                        <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                            <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                                <div class="footer-widget-column">
                                    <?php dynamic_sidebar( 'footer-' . $i ); ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- معلومات الفوتر السفلية -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">

                    <!-- معلومات الحقوق -->
                    <div class="site-info">
                        <?php
                        $copyright_text = get_theme_mod( 'nadiim_copyright_text' );
                        if ( $copyright_text ) :
                            echo wp_kses_post( $copyright_text );
                        else :
                            printf(
                                /* translators: %s: اسم الموقع */
                                esc_html__( 'حقوق النشر &copy; %1$s %2$s. جميع الحقوق محفوظة.', 'nadiim' ),
                                date( 'Y' ),
                                '<a href="' . esc_url( home_url( '/' ) ) . '">' . get_bloginfo( 'name' ) . '</a>'
                            );
                        endif;
                        ?>
                    </div>

                    <!-- قائمة الفوتر -->
                    <?php if ( has_nav_menu( 'footer' ) ) : ?>
                        <nav class="footer-navigation" aria-label="<?php esc_attr_e( 'قائمة الفوتر', 'nadiim' ); ?>">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu',
                                'depth'          => 1,
                                'container'      => false,
                            ) );
                            ?>
                        </nav>
                    <?php endif; ?>

                    <!-- روابط التواصل الاجتماعي -->
                    <?php if ( has_nav_menu( 'social' ) ) : ?>
                        <nav class="social-navigation" aria-label="<?php esc_attr_e( 'روابط التواصل الاجتماعي', 'nadiim' ); ?>">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'social',
                                'menu_class'     => 'social-menu',
                                'depth'          => 1,
                                'container'      => false,
                                'link_before'    => '<span class="screen-reader-text">',
                                'link_after'     => '</span>',
                            ) );
                            ?>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </footer><!-- #colophon -->

    <!-- زر الرجوع إلى الأعلى -->
    <button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'العودة إلى الأعلى', 'nadiim' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
