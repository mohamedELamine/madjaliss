<?php
/**
 * Template part for post navigation (prev/next)
 *
 * @package Nadiim
 * @since 1.0.0
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( ! $prev_post && ! $next_post ) {
    return;
}
?>

<nav class="article-navigation">
    <div class="article-navigation-container">
        <?php if ( $prev_post ) : ?>
            <div class="nav-item nav-previous">
                <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" rel="prev">
                    <div class="nav-direction">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>المقال السابق</span>
                    </div>

                    <h3 class="nav-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></h3>

                    <?php if ( has_post_thumbnail( $prev_post ) ) : ?>
                        <div class="nav-thumbnail">
                            <?php echo get_the_post_thumbnail( $prev_post, 'medium' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="nav-meta">
                        <?php
                        $categories = get_the_category( $prev_post->ID );
                        if ( ! empty( $categories ) ) :
                            ?>
                            <span class="nav-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                        <?php endif; ?>
                        <span class="nav-date"><?php echo get_the_date( '', $prev_post ); ?></span>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <?php if ( $next_post ) : ?>
            <div class="nav-item nav-next">
                <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" rel="next">
                    <div class="nav-direction">
                        <span>المقال التالي</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>

                    <h3 class="nav-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></h3>

                    <?php if ( has_post_thumbnail( $next_post ) ) : ?>
                        <div class="nav-thumbnail">
                            <?php echo get_the_post_thumbnail( $next_post, 'medium' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="nav-meta">
                        <?php
                        $categories = get_the_category( $next_post->ID );
                        if ( ! empty( $categories ) ) :
                            ?>
                            <span class="nav-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                        <?php endif; ?>
                        <span class="nav-date"><?php echo get_the_date( '', $next_post ); ?></span>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<style>
.article-navigation {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 1.5rem;
}

.article-navigation-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.nav-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: var(--color-bg-primary, #fff);
    border: 1px solid var(--color-border, #e5e8e6);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.nav-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: var(--color-primary, #339063);
}

.nav-item a {
    display: block;
    text-decoration: none;
    color: inherit;
    padding: 1.5rem;
}

.nav-direction {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 13px;
    font-weight: 600;
    color: var(--color-text-muted, #6b7a72);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.nav-direction svg {
    flex-shrink: 0;
    color: var(--color-primary, #339063);
}

.nav-next .nav-direction {
    justify-content: flex-end;
}

.nav-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--color-text-main, #1C2D27);
    line-height: 1.4;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.nav-item:hover .nav-title {
    color: var(--color-primary, #339063);
}

.nav-thumbnail {
    position: relative;
    width: 100%;
    height: 180px;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.nav-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.nav-item:hover .nav-thumbnail img {
    transform: scale(1.05);
}

.nav-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 13px;
    color: var(--color-text-muted, #6b7a72);
}

.nav-category {
    padding: 4px 10px;
    background: var(--color-bg-secondary, #f9faf9);
    border-radius: 4px;
    font-weight: 600;
}

.nav-date {
    display: flex;
    align-items: center;
    gap: 4px;
}

@media (max-width: 768px) {
    .article-navigation {
        margin: 3rem auto;
    }

    .article-navigation-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .nav-thumbnail {
        height: 150px;
    }

    .nav-title {
        font-size: 18px;
    }
}
</style>
