<?php
/**
 * قالب عرض التدوينة المفردة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main single-post">

    <?php
    while ( have_posts() ) :
        the_post();

        // استخدام قالب المقال الجديد مع كل المميزات
        get_template_part( 'template-parts/post/single', 'article' );

        // التنقل بين المقالات
        ?>
        <div class="post-navigation-wrapper" style="max-width: 900px; margin: 2rem auto; padding: 0 1.5rem;">
            <?php
            the_post_navigation( array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'السابق:', 'nadiim' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'التالي:', 'nadiim' ) . '</span> <span class="nav-title">%title</span>',
            ) );
            ?>
        </div>
        <?php

    endwhile;
    ?>

</main>

<?php
get_footer();
