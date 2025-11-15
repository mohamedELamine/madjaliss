<?php
/**
 * دوال القوالب المساعدة (Template Tags)
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * عرض معلومات التاريخ والكاتب
 */
function nadiim_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() )
    );

    printf(
        '<span class="posted-on">%s %s</span>',
        nadiim_get_icon( 'calendar' ),
        $time_string
    );
}

/**
 * عرض اسم الكاتب
 */
function nadiim_posted_by() {
    printf(
        '<span class="byline">%s <a class="url fn n" href="%s">%s</a></span>',
        nadiim_get_icon( 'user' ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_html( get_the_author() )
    );
}

/**
 * عرض التصنيفات
 */
function nadiim_entry_categories() {
    $categories_list = get_the_category_list( ', ' );
    if ( $categories_list ) {
        printf(
            '<span class="cat-links">%s</span>',
            $categories_list
        );
    }
}

/**
 * عرض الوسوم
 */
function nadiim_entry_tags() {
    $tags_list = get_the_tag_list( '', ', ' );
    if ( $tags_list ) {
        printf(
            '<div class="tags-links"><span class="tags-label">%s:</span> %s</div>',
            esc_html__( 'الوسوم', 'nadiim' ),
            $tags_list
        );
    }
}

/**
 * عرض زر القراءة المزيد
 */
function nadiim_read_more_link( $text = '' ) {
    if ( empty( $text ) ) {
        $text = __( 'اقرأ المزيد', 'nadiim' );
    }

    return sprintf(
        '<a href="%s" class="btn btn-outline">%s</a>',
        esc_url( get_permalink() ),
        esc_html( $text )
    );
}

/**
 * عرض عدد التعليقات
 */
function nadiim_comments_link() {
    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    __( 'لا توجد تعليقات<span class="screen-reader-text"> على %s</span>', 'nadiim' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            ),
            __( 'تعليق واحد', 'nadiim' ),
            __( '% تعليقات', 'nadiim' )
        );
        echo '</span>';
    }
}

/**
 * عرض ميتا المقال
 */
function nadiim_entry_meta() {
    echo '<div class="entry-meta">';
    nadiim_posted_on();
    echo '<span class="meta-separator">•</span>';
    nadiim_posted_by();
    echo '</div>';
}

/**
 * عرض فوتر المقال
 */
function nadiim_entry_footer() {
    echo '<footer class="entry-footer">';
    nadiim_entry_categories();
    nadiim_entry_tags();
    nadiim_comments_link();
    edit_post_link(
        sprintf(
            wp_kses(
                __( 'تحرير <span class="screen-reader-text">%s</span>', 'nadiim' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post( get_the_title() )
        ),
        '<span class="edit-link">',
        '</span>'
    );
    echo '</footer>';
}
