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
    <div class="archive-hero" style="background: linear-gradient(135deg, #339063 0%, #4db080 100%); padding: var(--spacing-3xl) 0; margin-bottom: var(--spacing-4xl); position: relative; overflow: hidden;">
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
            </header>
        </div>
    </div>

    <div class="container">
        <?php if ( have_posts() ) : ?>

            <div class="archive-content">
                <div class="posts-list" style="display: flex; flex-direction: column; gap: var(--spacing-xl); max-width: 1200px; margin: 0 auto var(--spacing-3xl);">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article <?php post_class( 'post-card-horizontal' ); ?> style="display: flex; gap: var(--spacing-lg); background: var(--color-bg-lighter); border-radius: var(--radius-xl); overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: all 0.4s ease;">

                            <!-- الصورة البارزة -->
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="flex-shrink: 0; width: 320px; position: relative; overflow: hidden; display: block;">
                                    <?php the_post_thumbnail( 'medium_large', array(
                                        'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;',
                                        'class' => 'card-image-hover'
                                    ) ); ?>

                                    <!-- بادج التصنيف -->
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) : ?>
                                        <span style="position: absolute; top: var(--spacing-md); left: var(--spacing-md); background: var(--color-primary); color: #fff; padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 1;">
                                            <?php echo esc_html( $categories[0]->name ); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php else : ?>
                                <!-- صورة افتراضية -->
                                <div style="flex-shrink: 0; width: 320px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; position: relative; overflow: hidden;">
                                    <div style="position: absolute; inset: 0; opacity: 0.1; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                                    <span style="position: relative; z-index: 1;">📝</span>
                                </div>
                            <?php endif; ?>

                            <!-- المحتوى -->
                            <div class="card-content" style="flex: 1; padding: var(--spacing-lg); display: flex; flex-direction: column; justify-content: center; min-width: 0;">

                                <!-- العنوان -->
                                <h2 class="card-title" style="margin-bottom: var(--spacing-md);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-2xl); line-height: 1.3; display: block; transition: color 0.3s ease; font-weight: 700;">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- الميتا -->
                                <div class="card-meta" style="display: flex; flex-wrap: wrap; gap: var(--spacing-sm); align-items: center; color: var(--color-text-secondary); font-size: 14px; margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-sm); border-bottom: 1px solid var(--color-border);">
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

                                <!-- المقتطف -->
                                <div class="card-excerpt" style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-lg); font-size: var(--font-size-base);">
                                    <?php echo nadiim_get_excerpt( 30 ); ?>
                                </div>

                                <!-- زر اقرأ المزيد -->
                                <div>
                                    <a href="<?php the_permalink(); ?>" class="read-more-link" style="display: inline-flex; align-items: center; gap: var(--spacing-xs); color: var(--color-primary); font-weight: 600; text-decoration: none; font-size: 15px; transition: gap 0.3s ease;">
                                        <span><?php esc_html_e( 'قراءة المزيد', 'nadiim' ); ?></span>
                                        <span style="transition: transform 0.3s ease;">←</span>
                                    </a>
                                </div>
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
/* تأثيرات البطاقات الأفقية */
.post-card-horizontal:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.post-card-horizontal:hover .card-image-hover {
    transform: scale(1.08);
}

.post-card-horizontal:hover .card-title a {
    color: var(--color-primary);
}

.post-card-horizontal:hover .read-more-link {
    gap: var(--spacing-sm);
}

.post-card-horizontal:hover .read-more-link span:last-child {
    transform: translateX(-4px);
}

/* تجاوب مع الشاشات المتوسطة والصغيرة */
@media (max-width: 992px) {
    .post-card-horizontal {
        flex-direction: column !important;
    }

    .post-card-horizontal a[style*="width: 320px"],
    .post-card-horizontal div[style*="width: 320px"] {
        width: 100% !important;
        min-height: 250px;
    }
}

@media (max-width: 640px) {
    .archive-hero {
        padding: var(--spacing-2xl) 0 !important;
    }

    .card-content {
        padding: var(--spacing-md) !important;
    }

    .card-title a {
        font-size: var(--font-size-xl) !important;
    }
}
</style>

<?php
get_footer();
