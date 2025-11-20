<?php
/**
 * Schema.org JSON-LD for Articles
 * Adds structured data for better SEO
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إخراج Schema.org JSON-LD للمقال
 */
function nadiim_output_article_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    global $post;

    $article_meta = get_post_meta( get_the_ID(), 'article_meta', true );
    $reading_time = nadiim_get_reading_time( get_the_ID() );
    $word_count = nadiim_get_post_word_count( get_the_ID() );
    $author_id = $post->post_author;
    $categories = get_the_category();

    // بناء بيانات المقال
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' ),
        'datePublished' => get_the_date( 'c' ),
        'dateModified' => get_the_modified_date( 'c' ),
        'wordCount' => $word_count,
        'articleBody' => wp_strip_all_tags( get_the_content() ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ),
    );

    // وقت القراءة بصيغة ISO 8601
    if ( $reading_time > 0 ) {
        $schema['timeRequired'] = 'PT' . $reading_time . 'M';
    }

    // الكاتب
    $schema['author'] = array(
        '@type' => 'Person',
        'name' => get_the_author_meta( 'display_name', $author_id ),
        'url' => get_author_posts_url( $author_id ),
    );

    // الناشر (معلومات الموقع)
    $schema['publisher'] = array(
        '@type' => 'Organization',
        'name' => get_bloginfo( 'name' ),
        'url' => home_url( '/' ),
    );

    // إضافة شعار الموقع إن وُجد
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        $schema['publisher']['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $logo_url,
        );
    }

    // الصورة المميزة
    if ( has_post_thumbnail() ) {
        $image_id = get_post_thumbnail_id();
        $image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $image_data = wp_get_attachment_metadata( $image_id );

        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => $image_data['width'] ?? 1200,
            'height' => $image_data['height'] ?? 630,
        );
    }

    // التصنيفات
    if ( ! empty( $categories ) ) {
        $schema['articleSection'] = array();
        foreach ( $categories as $category ) {
            $schema['articleSection'][] = $category->name;
        }
    }

    // الوسوم
    $tags = get_the_tags();
    if ( ! empty( $tags ) ) {
        $schema['keywords'] = array();
        foreach ( $tags as $tag ) {
            $schema['keywords'][] = $tag->name;
        }
        $schema['keywords'] = implode( ', ', $schema['keywords'] );
    }

    // الملف الصوتي
    if ( nadiim_has_article_audio() ) {
        $audio = nadiim_get_article_audio();
        $schema['audio'] = array(
            '@type' => 'AudioObject',
            'contentUrl' => $audio['url'],
            'encodingFormat' => 'audio/mpeg',
        );

        if ( ! empty( $audio['duration'] ) ) {
            // تحويل الصيغة من MM:SS إلى ISO 8601 (PTxMxS)
            $parts = explode( ':', $audio['duration'] );
            if ( count( $parts ) === 2 ) {
                $schema['audio']['duration'] = 'PT' . intval( $parts[0] ) . 'M' . intval( $parts[1] ) . 'S';
            }
        }

        if ( ! empty( $audio['caption'] ) ) {
            $schema['audio']['description'] = $audio['caption'];
        }
    }

    // عدد التعليقات
    $comments_count = get_comments_number();
    if ( $comments_count > 0 ) {
        $schema['commentCount'] = $comments_count;
        $schema['interactionStatistic'] = array(
            '@type' => 'InteractionCounter',
            'interactionType' => 'https://schema.org/CommentAction',
            'userInteractionCount' => $comments_count,
        );
    }

    // إخراج JSON-LD
    echo "\n" . '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}

/**
 * إضافة Open Graph Meta Tags
 */
function nadiim_add_article_og_tags() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $og_tags = array(
        'og:type' => 'article',
        'og:title' => get_the_title(),
        'og:description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' ),
        'og:url' => get_permalink(),
        'og:site_name' => get_bloginfo( 'name' ),
        'article:published_time' => get_the_date( 'c' ),
        'article:modified_time' => get_the_modified_date( 'c' ),
        'article:author' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
    );

    // الصورة المميزة
    if ( has_post_thumbnail() ) {
        $og_tags['og:image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $image_id = get_post_thumbnail_id();
        $image_data = wp_get_attachment_metadata( $image_id );

        if ( ! empty( $image_data ) ) {
            $og_tags['og:image:width'] = $image_data['width'] ?? 1200;
            $og_tags['og:image:height'] = $image_data['height'] ?? 630;
        }
    }

    // التصنيفات
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $og_tags['article:section'] = $category->name;
            break; // فقط التصنيف الأول
        }
    }

    // الوسوم
    $tags = get_the_tags();
    if ( ! empty( $tags ) ) {
        $tag_names = array();
        foreach ( $tags as $tag ) {
            $tag_names[] = $tag->name;
        }
        // إضافة الوسوم كـ article:tag (متعدد)
        foreach ( $tag_names as $tag_name ) {
            echo '<meta property="article:tag" content="' . esc_attr( $tag_name ) . '">' . "\n";
        }
    }

    // إخراج Meta Tags
    foreach ( $og_tags as $property => $content ) {
        if ( ! empty( $content ) ) {
            echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '">' . "\n";
        }
    }

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";

    $description = get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' );
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";

    if ( has_post_thumbnail() ) {
        echo '<meta name="twitter:image" content="' . esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'nadiim_add_article_og_tags' );

/**
 * إضافة breadcrumbs schema
 */
function nadiim_add_breadcrumb_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $breadcrumbs = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(),
    );

    // الرئيسية
    $breadcrumbs['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'الرئيسية',
        'item' => home_url( '/' ),
    );

    // المدونة
    $breadcrumbs['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'المدونة',
        'item' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ),
    );

    // التصنيف (إن وُجد)
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        $breadcrumbs['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $categories[0]->name,
            'item' => get_category_link( $categories[0]->term_id ),
        );
    }

    // المقال الحالي
    $breadcrumbs['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => count( $breadcrumbs['itemListElement'] ) + 1,
        'name' => get_the_title(),
        'item' => get_permalink(),
    );

    echo "\n" . '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'nadiim_add_breadcrumb_schema' );
