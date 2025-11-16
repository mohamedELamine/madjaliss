<?php
/**
 * قالب عرض المقال في صفحة المدونة - تصميم أفقي
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<article <?php post_class( 'blog-card' ); ?>>
    <div class="blog-card-wrapper">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="blog-card-image">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'blog-thumbnail' ) ); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="blog-card-content">
            <div class="blog-card-meta">
                <span class="blog-date">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php echo get_the_date(); ?>
                </span>
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                    ?>
                    <span class="blog-category">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <?php echo esc_html( $categories[0]->name ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <h2 class="blog-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <div class="blog-card-excerpt">
                <?php echo nadiim_get_excerpt( 25 ); ?>
            </div>

            <div class="blog-card-footer">
                <a href="<?php the_permalink(); ?>" class="blog-read-more">
                    <?php esc_html_e( 'اقرأ المزيد', 'nadiim' ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</article>
