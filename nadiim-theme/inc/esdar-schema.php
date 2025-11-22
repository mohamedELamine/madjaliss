<?php
/**
 * Schema.org JSON-LD for Esdar (Publications/Books)
 * Adds structured data for better SEO
 *
 * @package Majalis
 * @author محمد الأمين
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إخراج Schema.org JSON-LD للإصدار
 */
function majalis_output_esdar_schema() {
    if ( ! is_singular( 'esdar' ) ) {
        return;
    }

    global $post;

    $esdar_meta = get_post_meta( get_the_ID(), '_esdar_meta', true );
    $categories = get_the_terms( get_the_ID(), 'esdar_cat' );

    // بناء بيانات الكتاب/الإصدار
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Book',
        'name' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' ),
        'datePublished' => ! empty( $esdar_meta['publish_year'] ) ? $esdar_meta['publish_year'] . '-01-01' : get_the_date( 'c' ),
        'url' => get_permalink(),
    );

    // المؤلفون
    if ( ! empty( $esdar_meta['authors'] ) && is_array( $esdar_meta['authors'] ) ) {
        $authors = array();
        foreach ( $esdar_meta['authors'] as $author ) {
            if ( ! empty( $author['name'] ) ) {
                $authors[] = array(
                    '@type' => 'Person',
                    'name' => $author['name'],
                );
            }
        }
        if ( ! empty( $authors ) ) {
            $schema['author'] = count( $authors ) === 1 ? $authors[0] : $authors;
        }
    }

    // الناشر
    if ( ! empty( $esdar_meta['publisher'] ) ) {
        $schema['publisher'] = array(
            '@type' => 'Organization',
            'name' => $esdar_meta['publisher'],
        );
    } else {
        // استخدام اسم الموقع كناشر افتراضي
        $schema['publisher'] = array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
        );
    }

    // رقم ISBN
    if ( ! empty( $esdar_meta['isbn'] ) ) {
        $schema['isbn'] = $esdar_meta['isbn'];
    }

    // عدد الصفحات
    if ( ! empty( $esdar_meta['pages'] ) ) {
        $schema['numberOfPages'] = intval( $esdar_meta['pages'] );
    }

    // اللغة
    if ( ! empty( $esdar_meta['language'] ) ) {
        $schema['inLanguage'] = $esdar_meta['language'];
    } else {
        $schema['inLanguage'] = 'ar'; // افتراضي العربية
    }

    // الصورة المميزة (غلاف الكتاب)
    if ( has_post_thumbnail() ) {
        $image_id = get_post_thumbnail_id();
        $image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $image_data = wp_get_attachment_metadata( $image_id );

        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => $image_data['width'] ?? 800,
            'height' => $image_data['height'] ?? 1200,
        );
    }

    // التصنيفات/الأنواع
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        $genres = array();
        foreach ( $categories as $category ) {
            $genres[] = $category->name;
        }
        $schema['genre'] = $genres;
    }

    // التقييم (إن وُجد)
    if ( ! empty( $esdar_meta['rating'] ) ) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => floatval( $esdar_meta['rating'] ),
            'bestRating' => '5',
            'ratingCount' => '1',
        );
    }

    // رابط التحميل أو الشراء
    if ( ! empty( $esdar_meta['download_url'] ) || ! empty( $esdar_meta['buy_url'] ) ) {
        $offers = array(
            '@type' => 'Offer',
            'availability' => 'https://schema.org/InStock',
        );

        if ( ! empty( $esdar_meta['download_url'] ) ) {
            $offers['url'] = $esdar_meta['download_url'];
            $offers['price'] = '0';
            $offers['priceCurrency'] = 'USD';
        } elseif ( ! empty( $esdar_meta['buy_url'] ) ) {
            $offers['url'] = $esdar_meta['buy_url'];
            if ( ! empty( $esdar_meta['price'] ) ) {
                $offers['price'] = $esdar_meta['price'];
                $offers['priceCurrency'] = $esdar_meta['currency'] ?? 'USD';
            }
        }

        $schema['offers'] = $offers;
    }

    // معلومات إضافية
    if ( ! empty( $esdar_meta['edition'] ) ) {
        $schema['bookEdition'] = $esdar_meta['edition'];
    }

    // إخراج JSON-LD
    echo "\n" . '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'majalis_output_esdar_schema' );

/**
 * إضافة Open Graph Meta Tags للإصدارات
 */
function majalis_add_esdar_og_tags() {
    if ( ! is_singular( 'esdar' ) ) {
        return;
    }

    $esdar_meta = get_post_meta( get_the_ID(), '_esdar_meta', true );

    $og_tags = array(
        'og:type' => 'book',
        'og:title' => get_the_title(),
        'og:description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' ),
        'og:url' => get_permalink(),
        'og:site_name' => get_bloginfo( 'name' ),
    );

    // المؤلفون
    if ( ! empty( $esdar_meta['authors'] ) && is_array( $esdar_meta['authors'] ) ) {
        foreach ( $esdar_meta['authors'] as $author ) {
            if ( ! empty( $author['name'] ) ) {
                echo '<meta property="book:author" content="' . esc_attr( $author['name'] ) . '">' . "\n";
            }
        }
    }

    // رقم ISBN
    if ( ! empty( $esdar_meta['isbn'] ) ) {
        $og_tags['book:isbn'] = $esdar_meta['isbn'];
    }

    // تاريخ النشر
    if ( ! empty( $esdar_meta['publish_year'] ) ) {
        $og_tags['book:release_date'] = $esdar_meta['publish_year'] . '-01-01';
    }

    // الصورة المميزة
    if ( has_post_thumbnail() ) {
        $og_tags['og:image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $image_id = get_post_thumbnail_id();
        $image_data = wp_get_attachment_metadata( $image_id );

        if ( ! empty( $image_data ) ) {
            $og_tags['og:image:width'] = $image_data['width'] ?? 800;
            $og_tags['og:image:height'] = $image_data['height'] ?? 1200;
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
add_action( 'wp_head', 'majalis_add_esdar_og_tags' );
