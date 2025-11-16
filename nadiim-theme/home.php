<?php
/**
 * قالب أرشيف المقالات - المدونة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main blog-archive">

    <!-- Hero Section -->
    <div class="archive-hero" style="background: linear-gradient(135deg, #339063 0%, #4db080 100%); padding: var(--spacing-3xl) 0; margin-bottom: var(--spacing-3xl); position: relative; overflow: hidden;">
        <!-- نمط خلفية -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="container" style="position: relative; z-index: 1;">
            <header class="page-header" style="text-align: center; color: #fff;">
                <h1 class="page-title" style="font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom: var(--spacing-md); font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <?php esc_html_e( 'المدونة', 'nadiim' ); ?>
                </h1>
                <div class="archive-description" style="font-size: var(--font-size-lg); max-width: 700px; margin: 0 auto; line-height: 1.8; opacity: 0.95;">
                    <p><?php esc_html_e( 'مقالات ومواضيع متنوعة في الفكر والثقافة والأدب', 'nadiim' ); ?></p>
                </div>

                <div class="archive-meta" style="margin-top: var(--spacing-lg); display: inline-flex; align-items: center; gap: var(--spacing-sm); background: rgba(255,255,255,0.2); padding: var(--spacing-sm) var(--spacing-lg); border-radius: var(--radius-full); backdrop-filter: blur(10px);">
                    <span style="font-size: 20px;">📚</span>
                    <span style="font-weight: 600;">
                        <?php
                        $count = wp_count_posts()->publish;
                        printf(
                            esc_html( _n( '%s مقال', '%s مقالات', $count, 'nadiim' ) ),
                            number_format_i18n( $count )
                        );
                        ?>
                    </span>
                </div>
            </header>
        </div>
    </div>

    <div class="container">
        <?php if ( have_posts() ) : ?>

            <div class="archive-content">
                <div class="posts-grid grid grid-3" style="gap: var(--spacing-xl); margin-bottom: var(--spacing-3xl);">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article <?php post_class( 'card post-card' ); ?> style="border-radius: var(--radius-xl); overflow: hidden; background: var(--color-bg-lighter); box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: all 0.4s ease; display: flex; flex-direction: column; height: 100%;">

                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="display: block; position: relative; overflow: hidden; aspect-ratio: 16/9; flex-shrink: 0;">
                                    <?php the_post_thumbnail( 'nadiim-card', array(
                                        'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;',
                                        'class' => 'card-image-hover'
                                    ) ); ?>

                                    <!-- تدرج لوني مع بادج التصنيف -->
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, transparent 50%, rgba(0,0,0,0.3) 100%);"></div>

                                    <!-- بادج التصنيف -->
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) : ?>
                                        <span style="position: absolute; top: var(--spacing-md); left: var(--spacing-md); background: var(--color-primary); color: #fff; padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                                            <?php echo esc_html( $categories[0]->name ); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php else : ?>
                                <!-- صورة افتراضية إذا لم تكن هناك صورة بارزة -->
                                <div style="aspect-ratio: 16/9; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; position: relative; overflow: hidden;">
                                    <div style="position: absolute; inset: 0; opacity: 0.1; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                                    <span style="position: relative; z-index: 1;">📝</span>
                                </div>
                            <?php endif; ?>

                            <div class="card-content" style="padding: var(--spacing-lg); display: flex; flex-direction: column; flex-grow: 1;">

                                <h2 class="card-title" style="margin-bottom: var(--spacing-md);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-xl); line-height: 1.4; display: block; transition: color 0.3s ease; font-weight: 700;">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="card-meta" style="display: flex; flex-wrap: wrap; gap: var(--spacing-sm); align-items: center; color: var(--color-text-secondary); font-size: 13px; margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-md); border-bottom: 1px solid var(--color-border);">
                                    <span style="display: flex; align-items: center; gap: 4px;">
                                        📅 <?php echo get_the_date(); ?>
                                    </span>
                                    <span>•</span>
                                    <span style="display: flex; align-items: center; gap: 4px;">
                                        ✍️ <?php the_author(); ?>
                                    </span>
                                    <?php
                                    // حساب وقت القراءة
                                    $word_count = str_word_count( strip_tags( get_the_content() ) );
                                    $reading_time = ceil( $word_count / 200 );
                                    if ( $reading_time > 0 ) :
                                    ?>
                                        <span>•</span>
                                        <span style="display: flex; align-items: center; gap: 4px;">
                                            ⏱️ <?php printf( esc_html__( '%d دقائق قراءة', 'nadiim' ), $reading_time ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-excerpt" style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-lg); flex-grow: 1; font-size: 15px;">
                                    <?php echo nadiim_get_excerpt( 25 ); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="read-more-link" style="display: inline-flex; align-items: center; gap: var(--spacing-xs); color: var(--color-primary); font-weight: 600; text-decoration: none; font-size: 15px; transition: gap 0.3s ease; margin-top: auto;">
                                    <span><?php esc_html_e( 'قراءة المزيد', 'nadiim' ); ?></span>
                                    <span style="transition: transform 0.3s ease;">←</span>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                // الترقيم
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<span style="display: inline-flex; align-items: center; gap: 8px;"><span>→</span>' . __( 'السابق', 'nadiim' ) . '</span>',
                    'next_text' => '<span style="display: inline-flex; align-items: center; gap: 8px;">' . __( 'التالي', 'nadiim' ) . '<span>←</span></span>',
                    'before_page_number' => '<span class="screen-reader-text">' . __( 'صفحة', 'nadiim' ) . ' </span>',
                ) );
                ?>
            </div>

        <?php else : ?>

            <div class="no-results" style="text-align: center; padding: var(--spacing-3xl); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); border-radius: var(--radius-2xl); box-shadow: 0 4px 20px rgba(0,0,0,0.05); max-width: 600px; margin: var(--spacing-3xl) auto;">
                <span style="font-size: 80px; display: block; margin-bottom: var(--spacing-lg); filter: grayscale(50%);">📄</span>
                <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-md); font-weight: 700;">
                    <?php esc_html_e( 'لا توجد مقالات', 'nadiim' ); ?>
                </h2>
                <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg); margin-bottom: var(--spacing-xl); line-height: 1.7;">
                    <?php esc_html_e( 'لم يتم العثور على أي مقالات بعد.', 'nadiim' ); ?>
                </p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary" style="padding: var(--spacing-md) var(--spacing-xl); font-size: 16px;">
                    <?php esc_html_e( 'العودة للرئيسية', 'nadiim' ); ?>
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<style>
/* تأثيرات إضافية للبطاقات */
.post-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.post-card:hover .card-image-hover {
    transform: scale(1.05);
}

.post-card:hover .card-title a {
    color: var(--color-primary);
}

.post-card:hover .read-more-link {
    gap: var(--spacing-sm);
}

.post-card:hover .read-more-link span:last-child {
    transform: translateX(-4px);
}

/* تجاوب الشبكة */
@media (max-width: 992px) {
    .posts-grid.grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .posts-grid.grid-3 {
        grid-template-columns: 1fr;
    }

    .archive-hero {
        padding: var(--spacing-2xl) 0;
    }
}
</style>

<?php
get_footer();
