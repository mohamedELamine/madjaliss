<?php
/**
 * Template part for displaying article meta information
 *
 * @package Nadiim
 * @since 1.0.0
 */

$article_meta = get_post_meta( get_the_ID(), 'article_meta', true );
$reading_time = nadiim_get_reading_time( get_the_ID() );
$word_count = nadiim_get_post_word_count( get_the_ID() );
$show_reading_time = ! empty( $article_meta['show_reading_time'] );
?>

<div class="article-meta">
    <!-- الكاتب -->
    <div class="article-meta-item author-meta">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span>
            <?php
            printf(
                '<a href="%s" rel="author">%s</a>',
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                esc_html( get_the_author() )
            );
            ?>
        </span>
    </div>

    <!-- التاريخ -->
    <div class="article-meta-item date-meta">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
            <?php echo esc_html( get_the_date() ); ?>
        </time>
    </div>

    <?php if ( $show_reading_time && $reading_time > 0 ) : ?>
        <!-- وقت القراءة -->
        <div class="article-meta-item reading-time-meta">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span><?php printf( esc_html__( '%d دقيقة قراءة', 'nadiim' ), $reading_time ); ?></span>
        </div>
    <?php endif; ?>

    <?php if ( $word_count > 0 ) : ?>
        <!-- عدد الكلمات -->
        <div class="article-meta-item word-count-meta">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span><?php printf( esc_html__( '%s كلمة', 'nadiim' ), number_format_i18n( $word_count ) ); ?></span>
        </div>
    <?php endif; ?>

    <!-- التصنيف -->
    <?php
    $categories = get_the_category();
    if ( ! empty( $categories ) ) :
        ?>
        <div class="article-meta-item category-meta">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
            </svg>
            <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                <?php echo esc_html( $categories[0]->name ); ?>
            </a>
        </div>
        <?php
    endif;
    ?>

    <!-- عدد التعليقات -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
        <div class="article-meta-item comments-meta">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <a href="<?php comments_link(); ?>">
                <?php
                printf(
                    /* translators: %s: number of comments */
                    esc_html( _n( 'تعليق واحد', '%s تعليقات', get_comments_number(), 'nadiim' ) ),
                    number_format_i18n( get_comments_number() )
                );
                ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if ( nadiim_has_article_audio() ) : ?>
        <!-- مؤشر وجود صوت -->
        <div class="article-meta-item audio-meta">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
            </svg>
            <span><?php esc_html_e( 'استمع للمقال', 'nadiim' ); ?></span>
        </div>
    <?php endif; ?>
</div>

<style>
.article-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1rem 0;
    margin: 1.5rem 0;
    border-top: 1px solid var(--color-border-light, #f0f2f1);
    border-bottom: 1px solid var(--color-border-light, #f0f2f1);
}

.article-meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 14px;
    color: var(--color-text-muted, #6b7a72);
}

.article-meta-item svg {
    flex-shrink: 0;
    color: var(--color-primary, #339063);
}

.article-meta-item a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.article-meta-item a:hover {
    color: var(--color-primary, #339063);
}

@media (max-width: 768px) {
    .article-meta {
        gap: 0.75rem;
        font-size: 13px;
    }

    .article-meta-item svg {
        width: 16px;
        height: 16px;
    }
}
</style>
