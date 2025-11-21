<?php
/**
 * Front Page Template
 *
 * الصفحة الرئيسية للموقع - تحتوي على Hero Slider
 *
 * @package Madjaliss
 * @version 2.0 - Stage 1B
 */

get_header();
?>

<main id="primary" class="site-main home-page">

    <?php
    /**
     * Hero Slider Section
     * السلايدر الرئيسي في أعلى الصفحة
     */
    get_template_part('template-parts/home/hero-slider');
    ?>

    <!-- المزيد من الأقسام سيتم إضافتها في المراحل القادمة -->

</main>

<?php
get_footer();
