<?php
/**
 * Template Name: من نحن
 * Template Post Type: page
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main page-about" dir="rtl">

    <?php
    // Hero Section
    get_template_part('template-parts/about/hero-about');

    // Mission Section
    get_template_part('template-parts/about/section-mission');

    // Timeline Section
    get_template_part('template-parts/about/section-timeline');

    // Members Section
    get_template_part('template-parts/about/section-members');

    // CTA Section
    get_template_part('template-parts/about/section-cta');
    ?>

</main>

<?php
// Output Schema JSON-LD
$schema_data = nadiim_get_about_schema();
if ($schema_data) {
    echo "\n" . '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}

get_footer();
