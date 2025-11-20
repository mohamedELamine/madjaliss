<?php
/**
 * Template Name: صفحة المقالات
 * Template for displaying articles archive
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main archive-articles-page">

    <!-- Hero Section -->
    <div class="articles-page-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-container">
                <h1 class="hero-title"><?php the_title(); ?></h1>

                <?php if ( get_the_content() ) : ?>
                    <div class="hero-description">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <div class="hero-stats">
                    <?php
                    $published_posts = wp_count_posts( 'post' )->publish;
                    $categories_count = wp_count_terms( 'category' );
                    $tags_count = wp_count_terms( 'post_tag' );
                    ?>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo number_format_i18n( $published_posts ); ?></div>
                        <div class="stat-label">مقال منشور</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo number_format_i18n( $categories_count ); ?></div>
                        <div class="stat-label">تصنيف</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo number_format_i18n( $tags_count ); ?></div>
                        <div class="stat-label">وسم</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container archive-articles">

        <!-- الفلاتر -->
        <?php nadiim_render_articles_filters(); ?>

        <?php
        // الاستعلام عن المقالات
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'paged' => $paged,
            'posts_per_page' => 12,
        );

        // تطبيق الفلاتر
        if ( ! empty( $_GET['article_category'] ) && $_GET['article_category'] !== 'all' ) {
            $args['cat'] = absint( $_GET['article_category'] );
        }

        if ( ! empty( $_GET['article_tag'] ) && $_GET['article_tag'] !== 'all' ) {
            $args['tag_id'] = absint( $_GET['article_tag'] );
        }

        if ( ! empty( $_GET['article_author'] ) && $_GET['article_author'] !== 'all' ) {
            $args['author'] = absint( $_GET['article_author'] );
        }

        if ( ! empty( $_GET['article_search'] ) ) {
            $args['s'] = sanitize_text_field( $_GET['article_search'] );
        }

        $articles_query = new WP_Query( $args );

        if ( $articles_query->have_posts() ) :
            ?>

            <div class="archive-articles-grid">
                <?php
                while ( $articles_query->have_posts() ) :
                    $articles_query->the_post();
                    get_template_part( 'template-parts/components/card', 'article' );
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="archive-pagination">
                <?php
                echo paginate_links( array(
                    'total' => $articles_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '<span>السابق</span>',
                    'next_text' => '<span>التالي</span>',
                    'type' => 'list',
                ) );
                ?>
            </div>

            <?php
            wp_reset_postdata();
        else :
            ?>

            <div class="no-articles-found">
                <div class="no-articles-icon">
                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <h2>لا توجد مقالات</h2>
                <p>لم يتم العثور على مقالات تطابق معايير البحث. جرّب تعديل الفلاتر.</p>
                <a href="<?php echo esc_url( remove_query_arg( array_keys( $_GET ) ) ); ?>" class="reset-filters-btn">
                    إعادة الضبط
                </a>
            </div>

            <?php
        endif;
        ?>

    </div>
</main>

<style>
/* Hero Section */
.articles-page-hero {
    position: relative;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    margin-bottom: 3rem;
    overflow: hidden;
}

.articles-page-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.3;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 0%, rgba(28, 45, 39, 0.2) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 4rem 1.5rem;
    text-align: center;
}

.hero-container {
    max-width: 900px;
    margin: 0 auto;
}

.hero-title {
    font-family: var(--font-arabic);
    font-size: clamp(32px, 5vw, 48px);
    font-weight: 700;
    color: #fff;
    margin-bottom: 1rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.hero-description {
    font-size: clamp(16px, 2vw, 18px);
    color: rgba(255, 255, 255, 0.95);
    line-height: 1.6;
    margin-bottom: 2rem;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 36px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Articles Grid - 3 columns */
.archive-articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-bottom: 3rem;
}

/* No Articles Found */
.no-articles-found {
    text-align: center;
    padding: 4rem 1.5rem;
    background: var(--color-bg-secondary);
    border-radius: var(--border-radius-md);
    margin: 2rem 0;
}

.no-articles-icon {
    margin-bottom: 1.5rem;
}

.no-articles-icon svg {
    color: var(--color-text-muted);
    opacity: 0.5;
}

.no-articles-found h2 {
    font-size: 24px;
    font-weight: 700;
    color: var(--color-text-main);
    margin-bottom: 0.75rem;
}

.no-articles-found p {
    font-size: 16px;
    color: var(--color-text-muted);
    margin-bottom: 1.5rem;
}

.reset-filters-btn {
    display: inline-block;
    padding: 0.75rem 2rem;
    background: var(--color-primary);
    color: #fff;
    text-decoration: none;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    transition: var(--transition);
}

.reset-filters-btn:hover {
    background: var(--color-primary-dark);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 1024px) {
    .archive-articles-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .articles-page-hero {
        min-height: 350px;
    }

    .hero-stats {
        gap: 2rem;
    }

    .stat-number {
        font-size: 28px;
    }

    .archive-articles-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>

<?php
get_footer();
