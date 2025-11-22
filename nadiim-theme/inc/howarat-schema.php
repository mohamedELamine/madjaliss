<?php
/**
 * Schema.org JSON-LD for Howarat (Dialogues)
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
 * إخراج Schema.org JSON-LD للحوار
 */
function majalis_output_howarat_schema() {
    if ( ! is_singular( 'howarat' ) ) {
        return;
    }

    global $post;

    $howarat_meta = get_post_meta( get_the_ID(), '_howarat_meta', true );
    $author_id = $post->post_author;
    $categories = get_the_terms( get_the_ID(), 'howarat_cat' );

    // تحديد النوع حسب وسيط الحوار
    $media_type = isset( $howarat_meta['media_type'] ) ? $howarat_meta['media_type'] : 'text';
    $schema_type = 'Article'; // افتراضي

    if ( $media_type === 'video' ) {
        $schema_type = 'VideoObject';
    } elseif ( $media_type === 'audio' ) {
        $schema_type = 'AudioObject';
    }

    // بناء بيانات الحوار
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $schema_type,
        'headline' => get_the_title(),
        'name' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' ),
        'datePublished' => get_the_date( 'c' ),
        'dateModified' => get_the_modified_date( 'c' ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ),
    );

    // المشاركون في الحوار
    if ( ! empty( $howarat_meta['participants'] ) && is_array( $howarat_meta['participants'] ) ) {
        $participants = array();
        foreach ( $howarat_meta['participants'] as $participant ) {
            if ( ! empty( $participant['name'] ) ) {
                $participants[] = array(
                    '@type' => 'Person',
                    'name' => $participant['name'],
                    'jobTitle' => ! empty( $participant['role'] ) ? $participant['role'] : '',
                );
            }
        }
        if ( ! empty( $participants ) ) {
            $schema['participant'] = $participants;
        }
    }

    // الكاتب/المحرر
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

        if ( $schema_type === 'VideoObject' ) {
            $schema['thumbnailUrl'] = $image_url;
        }
    }

    // رابط الوسيط (فيديو أو صوت)
    if ( ! empty( $howarat_meta['media_url'] ) ) {
        $media_url = $howarat_meta['media_url'];

        if ( $schema_type === 'VideoObject' ) {
            $schema['contentUrl'] = $media_url;
            $schema['embedUrl'] = $media_url;
            $schema['uploadDate'] = get_the_date( 'c' );

            // المدة إن وُجدت
            if ( ! empty( $howarat_meta['duration'] ) ) {
                // تحويل من MM:SS إلى ISO 8601
                $parts = explode( ':', $howarat_meta['duration'] );
                if ( count( $parts ) === 2 ) {
                    $schema['duration'] = 'PT' . intval( $parts[0] ) . 'M' . intval( $parts[1] ) . 'S';
                }
            }
        } elseif ( $schema_type === 'AudioObject' ) {
            $schema['contentUrl'] = $media_url;
            $schema['encodingFormat'] = 'audio/mpeg';

            // المدة إن وُجدت
            if ( ! empty( $howarat_meta['duration'] ) ) {
                $parts = explode( ':', $howarat_meta['duration'] );
                if ( count( $parts ) === 2 ) {
                    $schema['duration'] = 'PT' . intval( $parts[0] ) . 'M' . intval( $parts[1] ) . 'S';
                }
            }
        }
    }

    // التصنيفات
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        $schema['genre'] = array();
        foreach ( $categories as $category ) {
            $schema['genre'][] = $category->name;
        }
    }

    // تاريخ ومكان الحوار
    if ( ! empty( $howarat_meta['date'] ) ) {
        $schema['dateCreated'] = $howarat_meta['date'];
    }

    if ( ! empty( $howarat_meta['location'] ) ) {
        $schema['locationCreated'] = array(
            '@type' => 'Place',
            'name' => $howarat_meta['location'],
        );
    }

    // إخراج JSON-LD
    echo "\n" . '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'majalis_output_howarat_schema' );

/**
 * إضافة Open Graph Meta Tags للحوارات
 */
function majalis_add_howarat_og_tags() {
    if ( ! is_singular( 'howarat' ) ) {
        return;
    }

    $howarat_meta = get_post_meta( get_the_ID(), '_howarat_meta', true );
    $media_type = isset( $howarat_meta['media_type'] ) ? $howarat_meta['media_type'] : 'text';

    $og_type = 'article';
    if ( $media_type === 'video' ) {
        $og_type = 'video.other';
    } elseif ( $media_type === 'audio' ) {
        $og_type = 'music.song';
    }

    $og_tags = array(
        'og:type' => $og_type,
        'og:title' => get_the_title(),
        'og:description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' ),
        'og:url' => get_permalink(),
        'og:site_name' => get_bloginfo( 'name' ),
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

    // رابط الفيديو أو الصوت
    if ( ! empty( $howarat_meta['media_url'] ) ) {
        if ( $media_type === 'video' ) {
            $og_tags['og:video'] = $howarat_meta['media_url'];
        } elseif ( $media_type === 'audio' ) {
            $og_tags['og:audio'] = $howarat_meta['media_url'];
        }
    }

    // المدة
    if ( ! empty( $howarat_meta['duration'] ) ) {
        $og_tags['video:duration'] = $howarat_meta['duration'];
    }

    // إخراج Meta Tags
    foreach ( $og_tags as $property => $content ) {
        if ( ! empty( $content ) ) {
            echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '">' . "\n";
        }
    }

    // Twitter Card
    $twitter_card = 'summary_large_image';
    if ( $media_type === 'video' ) {
        $twitter_card = 'player';
    }

    echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";

    $description = get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' );
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";

    if ( has_post_thumbnail() ) {
        echo '<meta name="twitter:image" content="' . esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'majalis_add_howarat_og_tags' );
