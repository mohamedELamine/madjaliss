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
        get_template_part( 'template-parts/post/post', 'navigation' );

    endwhile;
    ?>

</main>

<?php
get_footer();
