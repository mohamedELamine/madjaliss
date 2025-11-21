<?php
/**
 * ملف الهيدر (الترويسة)
 *
 * يعرض قسم <head> وكل شيء حتى <div id="content">
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'انتقل إلى المحتوى', 'nadiim' ); ?>
    </a>

    <?php
    /**
     * الشريط العلوي (Top Bar)
     * يتم تحميله من template-parts/header/topbar.php
     */
    get_template_part( 'template-parts/header/topbar' );

    /**
     * الهيدر الرئيسي (Main Header)
     * يتم تحميله من template-parts/header/main-header.php
     */
    get_template_part( 'template-parts/header/main-header' );
    ?>

    <div id="content" class="site-content">
