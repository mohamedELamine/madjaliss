<?php
/**
 * قالب عرض التدوينة المفردة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container container-narrow">

        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'single' );

            // التنقل بين المقالات
            the_post_navigation( array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'السابق:', 'nadiim' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'التالي:', 'nadiim' ) . '</span> <span class="nav-title">%title</span>',
            ) );

            // التعليقات
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
        ?>

    </div>
</main>

<?php
get_footer();
