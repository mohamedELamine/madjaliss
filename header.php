<?php
/**
 * Header Template
 *
 * @package Madjaliss
 * @version 2.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

    <?php
    /**
     * Top Bar Section
     * يتم عرضه فقط إذا كان مفعلاً من Customizer
     */
    if (get_theme_mod('topbar_enable', true)) {
        get_template_part('template-parts/layout/topbar');
    }

    /**
     * Main Header Section
     * الهيدر الرئيسي يحتوي على اللوجو والقائمة والبحث
     */
    get_template_part('template-parts/layout/main-header');
    ?>

    <div id="content" class="site-content">
