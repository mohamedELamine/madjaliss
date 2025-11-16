<?php
/**
 * صفحة المدونة - قالب عرض المقالات
 *
 * يُستخدم لعرض قائمة المقالات في صفحة المدونة
 * منفصل تماماً عن الصفحة الرئيسية
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main blog-page">

    <?php if ( is_home() && ! is_front_page() ) :
        $blog_title = get_theme_mod( 'nadiim_blog_title', __( 'المدونة', 'nadiim' ) );
        $blog_desc = get_theme_mod( 'nadiim_blog_description', __( 'مقالات ومواضيع متنوعة في الفكر والثقافة والأدب', 'nadiim' ) );
        $blog_bg_image = get_theme_mod( 'nadiim_blog_bg_image' );
        ?>
        <!-- عنوان صفحة المدونة -->
        <section class="blog-header" style="<?php if ( $blog_bg_image ) : ?>background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $blog_bg_image, 'full' ) ); ?>); background-size: cover; background-position: center;<?php else : ?>background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-light) 100%);<?php endif; ?> padding: calc(var(--spacing-xxl)) 0; position: relative; overflow: hidden; min-height: 350px; display: flex; align-items: center; justify-content: center;">
            <!-- طبقة شفافة -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(250, 250, 250, 0.85); z-index: 1;"></div>

            <div class="container text-center" style="position: relative; z-index: 2;">
                <h1 class="page-title" style="font-size: clamp(36px, 5vw, 48px); font-weight: 800; margin-bottom: var(--spacing-md); color: var(--color-text-primary); text-shadow: 0 2px 4px rgba(255,255,255,0.8);">
                    <?php echo esc_html( $blog_title ); ?>
                </h1>
                <p class="blog-description" style="font-size: clamp(18px, 2.5vw, 22px); color: var(--color-text-secondary); max-width: 700px; margin: 0 auto; line-height: 1.8; font-weight: 500;">
                    <?php echo esc_html( $blog_desc ); ?>
                </p>
            </div>
        </section>
    <?php endif; ?>

    <div class="container">
        <div class="blog-content section">

            <?php if ( have_posts() ) : ?>

                <div class="posts-list blog-posts-list">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', 'blog' );
                    endwhile;
                    ?>
                </div>

                <?php
                // الترقيم
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '← السابق', 'nadiim' ),
                    'next_text' => __( 'التالي →', 'nadiim' ),
                    'class'     => 'pagination',
                    'before_page_number' => '<span class="screen-reader-text">' . __( 'صفحة', 'nadiim' ) . ' </span>',
                ) );
                ?>

            <?php else : ?>

                <div class="no-posts-found" style="text-align: center; padding: var(--spacing-xxl) 0;">
                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
                </div>

            <?php endif; ?>

        </div>
    </div>

</main>

<?php
get_footer();
