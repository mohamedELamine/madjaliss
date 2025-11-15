<?php
/**
 * قالب صفحة الكاتب
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
$author = get_queried_object();
?>

<main id="primary" class="site-main author-page">

    <!-- Hero مع معلومات الكاتب -->
    <div class="author-hero" style="background: linear-gradient(135deg, #339063 0%, #4db080 100%); padding: var(--spacing-3xl) 0; margin-bottom: var(--spacing-3xl); position: relative; overflow: hidden;">
        <!-- نمط خلفية -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="container" style="position: relative; z-index: 1;">
            <div class="author-card" style="max-width: 800px; margin: 0 auto; text-align: center;">
                <!-- صورة الكاتب -->
                <div style="margin-bottom: var(--spacing-lg);">
                    <?php echo get_avatar( $author->ID, 150, '', '', array(
                        'style' => 'border-radius: 50%; border: 6px solid rgba(255,255,255,0.3); box-shadow: 0 8px 30px rgba(0,0,0,0.3); backdrop-filter: blur(10px);'
                    ) ); ?>
                </div>

                <!-- الاسم -->
                <h1 class="author-name" style="font-size: clamp(2rem, 4vw, 3rem); margin-bottom: var(--spacing-sm); color: #fff; font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    <?php echo esc_html( $author->display_name ); ?>
                </h1>

                <!-- الوصف -->
                <?php if ( $author->description ) : ?>
                    <div class="author-bio" style="max-width: 650px; margin: 0 auto var(--spacing-lg); color: rgba(255,255,255,0.95); font-size: var(--font-size-lg); line-height: 1.8; font-weight: 400;">
                        <?php echo wpautop( esc_html( $author->description ) ); ?>
                    </div>
                <?php else : ?>
                    <p style="color: rgba(255,255,255,0.85); font-size: var(--font-size-lg); margin-bottom: var(--spacing-lg); font-style: italic;">
                        <?php esc_html_e( 'كاتب ومساهم في نديم', 'nadiim' ); ?>
                    </p>
                <?php endif; ?>

                <!-- الإحصائيات -->
                <div class="author-stats" style="display: flex; gap: var(--spacing-lg); justify-content: center; flex-wrap: wrap; margin-top: var(--spacing-xl);">
                    <?php
                    $posts_count = count_user_posts( $author->ID, 'post' );
                    $howarat_count = count_user_posts( $author->ID, 'howarat' );
                    $esdar_count = count_user_posts( $author->ID, 'esdar' );
                    ?>
                    <?php if ( $posts_count > 0 ) : ?>
                        <div class="stat-item" style="background: rgba(255,255,255,0.15); padding: var(--spacing-md) var(--spacing-lg); border-radius: var(--radius-lg); backdrop-filter: blur(10px); min-width: 120px;">
                            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: var(--spacing-xs);"><?php echo number_format_i18n( $posts_count ); ?></div>
                            <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600;"><?php esc_html_e( 'مقالة', 'nadiim' ); ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $howarat_count > 0 ) : ?>
                        <div class="stat-item" style="background: rgba(255,255,255,0.15); padding: var(--spacing-md) var(--spacing-lg); border-radius: var(--radius-lg); backdrop-filter: blur(10px); min-width: 120px;">
                            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: var(--spacing-xs);"><?php echo number_format_i18n( $howarat_count ); ?></div>
                            <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600;"><?php esc_html_e( 'حوار', 'nadiim' ); ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $esdar_count > 0 ) : ?>
                        <div class="stat-item" style="background: rgba(255,255,255,0.15); padding: var(--spacing-md) var(--spacing-lg); border-radius: var(--radius-lg); backdrop-filter: blur(10px); min-width: 120px;">
                            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: var(--spacing-xs);"><?php echo number_format_i18n( $esdar_count ); ?></div>
                            <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600;"><?php esc_html_e( 'إصدار', 'nadiim' ); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- رابط الموقع الشخصي -->
                <?php if ( $author->user_url ) : ?>
                    <div style="margin-top: var(--spacing-lg);">
                        <a href="<?php echo esc_url( $author->user_url ); ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; gap: var(--spacing-sm); background: rgba(255,255,255,0.2); color: #fff; padding: var(--spacing-sm) var(--spacing-lg); border-radius: var(--radius-full); text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s ease;">
                            🌐 <?php esc_html_e( 'الموقع الشخصي', 'nadiim' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- عرض المقالات -->
        <?php
        $posts_query = new WP_Query( array(
            'author'         => $author->ID,
            'post_type'      => 'post',
            'posts_per_page' => 12,
            'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
        ) );

        if ( $posts_query->have_posts() ) : ?>
            <div class="author-posts-section" style="margin-bottom: var(--spacing-3xl);">
                <h2 style="font-size: var(--font-size-2xl); font-weight: 800; margin-bottom: var(--spacing-xl); text-align: center; position: relative; padding-bottom: var(--spacing-md);">
                    <span style="background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        <?php esc_html_e( '📝 المقالات', 'nadiim' ); ?>
                    </span>
                    <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 4px; background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light)); border-radius: var(--radius-full);"></div>
                </h2>

                <div class="posts-grid grid grid-3" style="gap: var(--spacing-xl);">
                    <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                        <article <?php post_class( 'card post-card' ); ?> style="border-radius: var(--radius-xl); overflow: hidden; background: var(--color-bg-lighter); box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: all 0.4s ease; display: flex; flex-direction: column;">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="display: block; position: relative; overflow: hidden; aspect-ratio: 16/9;">
                                    <?php the_post_thumbnail( 'nadiim-card', array(
                                        'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;'
                                    ) ); ?>
                                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.3) 100%);"></div>
                                </a>
                            <?php endif; ?>

                            <div class="card-content" style="padding: var(--spacing-lg); flex-grow: 1; display: flex; flex-direction: column;">
                                <h3 class="card-title" style="margin-bottom: var(--spacing-sm);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-xl); line-height: 1.4; font-weight: 700; transition: color 0.3s ease;">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <div style="color: var(--color-text-secondary); line-height: 1.6; margin-bottom: var(--spacing-md); flex-grow: 1;">
                                    <?php echo nadiim_get_excerpt( 20 ); ?>
                                </div>

                                <div style="display: flex; align-items: center; justify-content: space-between; padding-top: var(--spacing-md); border-top: 1px solid var(--color-border); font-size: 14px; color: var(--color-text-secondary);">
                                    <span>📅 <?php echo get_the_date(); ?></span>
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-primary); font-weight: 600; text-decoration: none; transition: gap 0.3s ease; display: inline-flex; align-items: center; gap: 4px;">
                                        <?php esc_html_e( 'اقرأ المزيد', 'nadiim' ); ?> <span>←</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<span style="display: inline-flex; align-items: center; gap: 8px;"><span>→</span>' . __( 'السابق', 'nadiim' ) . '</span>',
                    'next_text' => '<span style="display: inline-flex; align-items: center; gap: 8px;">' . __( 'التالي', 'nadiim' ) . '<span>←</span></span>',
                ) );
                ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: var(--spacing-3xl); background: var(--color-bg-section); border-radius: var(--radius-xl);">
                <span style="font-size: 64px; display: block; margin-bottom: var(--spacing-lg);">📝</span>
                <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg);">
                    <?php esc_html_e( 'لا توجد مقالات حالياً', 'nadiim' ); ?>
                </p>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</main>

<style>
/* تأثيرات التحويم */
.stat-item {
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.25) !important;
}

.post-card {
    transition: all 0.4s ease;
}

.post-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.post-card:hover img {
    transform: scale(1.05);
}

.post-card:hover .card-title a {
    color: var(--color-primary);
}

/* تجاوب */
@media (max-width: 992px) {
    .posts-grid.grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }

    .author-stats {
        gap: var(--spacing-md);
    }
}

@media (max-width: 640px) {
    .posts-grid.grid-3 {
        grid-template-columns: 1fr;
    }

    .author-hero {
        padding: var(--spacing-2xl) 0;
    }

    .author-stats {
        flex-direction: column;
        align-items: center;
    }

    .stat-item {
        width: 100%;
        max-width: 200px;
    }
}
</style>

<?php
get_footer();
