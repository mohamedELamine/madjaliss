<?php
/**
 * دوال القوالب العامة
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة classes مخصصة لـ body
 */
function nadiim_body_classes( $classes ) {
    // إضافة class إذا لم يكن هناك شريط جانبي
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    // إضافة class للصفحة الرئيسية
    if ( is_front_page() && is_home() ) {
        $classes[] = 'home-page';
    }

    // إضافة class إذا كان الموقع يحتوي على شعار مخصص
    if ( has_custom_logo() ) {
        $classes[] = 'has-custom-logo';
    }

    return $classes;
}
add_filter( 'body_class', 'nadiim_body_classes' );

/**
 * إضافة فئة pingback إلى header
 */
function nadiim_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'nadiim_pingback_header' );

/**
 * تخصيص طول المقتطف
 */
function nadiim_excerpt_length( $length ) {
    if ( is_admin() ) {
        return $length;
    }

    return get_theme_mod( 'nadiim_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'nadiim_excerpt_length' );

/**
 * تخصيص علامة نهاية المقتطف
 */
function nadiim_excerpt_more( $more ) {
    if ( is_admin() ) {
        return $more;
    }

    return '...';
}
add_filter( 'excerpt_more', 'nadiim_excerpt_more' );

/**
 * تحسين عناوين الأرشيف
 */
function nadiim_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}
add_filter( 'get_the_archive_title', 'nadiim_archive_title' );

/**
 * إضافة wrapper للصور المضمنة في المحتوى
 */
function nadiim_wrap_embed_with_div( $html, $url, $attr ) {
    return '<div class="entry-content-embed">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'nadiim_wrap_embed_with_div', 10, 3 );

/**
 * إضافة class responsive للصور
 */
function nadiim_add_image_class( $class ) {
    $class .= ' img-responsive';
    return $class;
}
add_filter( 'get_image_tag_class', 'nadiim_add_image_class' );

/**
 * تحسين القائمة الرئيسية للهواتف
 */
function nadiim_nav_menu_args( $args ) {
    if ( 'primary' === $args['theme_location'] ) {
        $args['menu_class'] .= ' responsive-menu';
    }

    return $args;
}
add_filter( 'wp_nav_menu_args', 'nadiim_nav_menu_args' );

/**
 * إضافة schema.org microdata
 */
function nadiim_article_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => get_the_title(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified'  => get_the_modified_date( 'c' ),
        'author'        => array(
            '@type' => 'Person',
            'name'  => get_the_author(),
        ),
        'publisher'     => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo( 'name' ),
            'logo'  => array(
                '@type' => 'ImageObject',
                'url'   => get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
            ),
        ),
    );

    if ( has_post_thumbnail() ) {
        $schema['image'] = get_the_post_thumbnail_url( null, 'full' );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'nadiim_article_schema' );

/**
 * تحسين قائمة الصفحات
 */
function nadiim_link_pages_args( $args ) {
    $args['before'] = '<div class="page-links"><span class="page-links-title">' . __( 'الصفحات:', 'nadiim' ) . '</span>';
    $args['after']  = '</div>';

    return $args;
}
add_filter( 'wp_link_pages_args', 'nadiim_link_pages_args' );

/**
 * إضافة فئة للقوائم الفرعية في القائمة الرئيسية
 */
function nadiim_add_menu_parent_class( $items ) {
    $parents = array();

    foreach ( $items as $item ) {
        if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
            $parents[] = $item->menu_item_parent;
        }
    }

    foreach ( $items as $item ) {
        if ( in_array( $item->ID, $parents, true ) ) {
            $item->classes[] = 'menu-item-has-children';
        }
    }

    return $items;
}
add_filter( 'wp_nav_menu_objects', 'nadiim_add_menu_parent_class' );
