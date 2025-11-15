<?php
/**
 * قالب عرض المقال المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <div class="entry-meta">
            <?php nadiim_posted_on(); ?>
            <span class="meta-separator">•</span>
            <?php nadiim_posted_by(); ?>
        </div>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-featured-image">
            <?php the_post_thumbnail( 'nadiim-featured' ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content( sprintf(
            wp_kses(
                __( 'تابع القراءة <span class="meta-nav">&larr;</span>', 'nadiim' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            the_title( '<span class="screen-reader-text">"', '"</span>', false )
        ) );

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'الصفحات:', 'nadiim' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <?php nadiim_entry_footer(); ?>

</article>
