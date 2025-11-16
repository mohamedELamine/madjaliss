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

    <?php if ( is_home() && ! is_front_page() ) : ?>
        <!-- عنوان صفحة المدونة -->
        <section class="blog-header" style="background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-light) 100%); padding: calc(var(--spacing-xl) + 30px) 0 var(--spacing-xl); position: relative; overflow: hidden; border-bottom: 3px solid var(--color-primary);">
            <div style="position: absolute; top: -80px; left: 10%; width: 250px; height: 250px; background: radial-gradient(circle, rgba(51, 144, 99, 0.08) 0%, transparent 70%); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -60px; right: 15%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(51, 144, 99, 0.06) 0%, transparent 70%); border-radius: 50%;"></div>
            <div class="container text-center" style="position: relative; z-index: 2;">
                <h1 class="page-title" style="font-size: clamp(32px, 4vw, var(--font-size-3xl)); font-weight: 800; margin-bottom: var(--spacing-sm); color: var(--color-text-primary);">
                    <?php
                    $blog_title = get_theme_mod( 'nadiim_blog_title', __( 'المدونة', 'nadiim' ) );
                    echo esc_html( $blog_title );
                    ?>
                </h1>
                <p class="blog-description" style="font-size: var(--font-size-lg); color: var(--color-text-secondary); max-width: 600px; margin: 0 auto; line-height: 1.8;">
                    <?php
                    $blog_desc = get_theme_mod( 'nadiim_blog_description', __( 'مقالات ومحتوى متنوع حول الثقافة والأدب والفكر', 'nadiim' ) );
                    echo esc_html( $blog_desc );
                    ?>
                </p>
            </div>
        </section>
    <?php endif; ?>

    <div class="container">
        <div class="blog-content section">

            <?php if ( have_posts() ) : ?>

                <div class="posts-grid grid grid-3">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', get_post_type() );
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
