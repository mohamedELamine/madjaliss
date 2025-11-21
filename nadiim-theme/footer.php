<?php
/**
 * Footer Template
 *
 * @package Madjaliss
 * @version 2.0
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">

            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                <div class="footer-widgets">
                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <div class="footer-widget">
                                <?php dynamic_sidebar('footer-4'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="site-info">
                <div class="copyright">
                    <p>
                        <?php
                        printf(
                            esc_html__('&copy; %1$s %2$s. جميع الحقوق محفوظة.', 'madjaliss'),
                            date('Y'),
                            get_bloginfo('name')
                        );
                        ?>
                    </p>
                </div>

                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'menu_class'     => 'footer-menu',
                        'container'      => 'nav',
                        'container_class' => 'footer-navigation',
                        'depth'          => 1,
                    ));
                }
                ?>
            </div>

        </div>
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
