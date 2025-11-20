<?php
/**
 * Article Card Component for Archive Page
 *
 * @package Nadiim
 * @since 1.0.0
 */

$article_meta = get_post_meta( get_the_ID(), 'article_meta', true );
$reading_time = nadiim_get_reading_time( get_the_ID() );
$has_audio = nadiim_has_article_audio( get_the_ID() );
$author_id = get_the_author_meta( 'ID' );
$categories = get_the_category();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="article-card-image">
            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                <?php the_post_thumbnail( 'medium_large' ); ?>
            </a>

            <?php if ( $has_audio ) : ?>
                <div class="article-card-audio-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                    <span><?php esc_html_e( 'صوتي', 'nadiim' ); ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="article-card-content">
        <div class="article-card-meta">
            <?php if ( ! empty( $categories ) ) : ?>
                <div class="article-card-meta-item category-item">
                    <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                        <?php echo esc_html( $categories[0]->name ); ?>
                    </a>
                </div>
                <span class="meta-separator">•</span>
            <?php endif; ?>

            <div class="article-card-meta-item date-item">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
            </div>

            <?php if ( $reading_time > 0 ) : ?>
                <span class="meta-separator">•</span>
                <div class="article-card-meta-item reading-time-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span><?php printf( esc_html__( '%d دقيقة', 'nadiim' ), $reading_time ); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <h2 class="article-card-title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>

        <?php if ( has_excerpt() ) : ?>
            <div class="article-card-excerpt">
                <?php
                // الحصول على المقتطف وتقليمه إلى 25 كلمة
                $excerpt = get_the_excerpt();
                $words = explode( ' ', $excerpt );
                if ( count( $words ) > 25 ) {
                    $excerpt = implode( ' ', array_slice( $words, 0, 25 ) ) . '...';
                }
                echo esc_html( $excerpt );
                ?>
            </div>
        <?php endif; ?>

        <div class="article-card-footer">
            <div class="article-card-author">
                <?php echo get_avatar( $author_id, 24 ); ?>
                <span>
                    <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
            </div>

            <a href="<?php the_permalink(); ?>" class="article-card-read-more">
                <?php esc_html_e( 'اقرأ المزيد', 'nadiim' ); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>
</article>

<style>
.meta-separator {
    color: var(--color-text-light, #9ca3a0);
    font-size: 12px;
}

.article-card-meta-item.category-item a {
    color: var(--color-primary, #339063);
    font-weight: 600;
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.article-card-meta-item.category-item a:hover {
    opacity: 0.8;
}

.article-card-read-more svg {
    width: 14px;
    height: 14px;
    transition: transform 0.3s ease;
}

.article-card-read-more:hover svg {
    transform: translateX(-4px);
}

[dir="rtl"] .article-card-read-more svg {
    transform: scaleX(-1);
}

[dir="rtl"] .article-card-read-more:hover svg {
    transform: scaleX(-1) translateX(4px);
}
</style>
