<?php
/**
 * الملف الرئيسي للقالب
 *
 * هذا هو الملف الأكثر عمومية في التسلسل الهرمي للقوالب
 * ويُستخدم لعرض الصفحات عندما لا يتوفر قالب أكثر تحديداً
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="content-area">

            <?php if ( have_posts() ) : ?>

                <?php if ( is_home() && ! is_front_page() ) : ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <div class="posts-grid grid grid-2">
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
                    'prev_text' => __( '&rarr; السابق', 'nadiim' ),
                    'next_text' => __( 'التالي &larr;', 'nadiim' ),
                ) );
                ?>

            <?php else : ?>

                <?php get_template_part( 'template-parts/content', 'none' ); ?>

            <?php endif; ?>

        </div>
    </div>
</main>

<?php
get_footer();
