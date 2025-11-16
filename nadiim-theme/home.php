<?php
/**
 * قالب أرشيف المقالات - المدونة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

// الحصول على صورة الخلفية من تحرير الصفحة
$page_for_posts_id = get_option( 'page_for_posts' );
$hero_image_url = '';
if ( $page_for_posts_id && has_post_thumbnail( $page_for_posts_id ) ) {
    $hero_image_url = get_the_post_thumbnail_url( $page_for_posts_id, 'full' );
}
?>

<main id="primary" class="site-main blog-archive">

    <!-- Hero Section بعرض كامل -->
    <div class="blog-hero-fullwidth" style="position: relative; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin-bottom: var(--spacing-4xl); overflow: hidden;">

        <?php if ( $hero_image_url ) : ?>
            <!-- الصورة كخلفية -->
            <div style="position: absolute; inset: 0; z-index: 0;">
                <img src="<?php echo esc_url( $hero_image_url ); ?>"
                     alt="<?php esc_attr_e( 'خلفية المدونة', 'nadiim' ); ?>"
                     style="width: 100%; height: 100%; object-fit: cover;">
                <!-- تدرج لوني -->
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.7) 100%);"></div>
            </div>
        <?php else : ?>
            <!-- خلفية افتراضية إذا لم توجد صورة -->
            <div style="position: absolute; inset: 0; z-index: 0; background: linear-gradient(135deg, #339063 0%, #4db080 100%);">
                <div style="position: absolute; inset: 0; opacity: 0.1; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>
        <?php endif; ?>

        <!-- المحتوى -->
        <div class="container" style="position: relative; z-index: 1; text-align: center;">
            <header class="page-header">
                <h1 class="page-title" style="font-size: clamp(3rem, 7vw, 5rem); margin-bottom: var(--spacing-lg); color: #fff; font-weight: 900; text-shadow: 0 4px 30px rgba(0,0,0,0.7);">
                    <?php esc_html_e( 'المدونة', 'nadiim' ); ?>
                </h1>
                <div class="archive-description" style="font-size: clamp(1rem, 2vw, 1.5rem); max-width: 800px; margin: 0 auto; line-height: 1.8; color: rgba(255,255,255,0.95); text-shadow: 0 2px 15px rgba(0,0,0,0.5);">
                    <p><?php esc_html_e( 'مقالات ومواضيع متنوعة في الفكر والثقافة والأدب', 'nadiim' ); ?></p>
                </div>
            </header>
        </div>
    </div>

    <div class="container">
        <?php if ( have_posts() ) : ?>

            <div class="archive-content">
                <!-- شبكة عمودين -->
                <div class="posts-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-xl); margin-bottom: var(--spacing-3xl);">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article <?php post_class( 'post-card-compact' ); ?>>
                            <a href="<?php the_permalink(); ?>" style="display: block; background: var(--color-bg-lighter); border-radius: var(--radius-xl); overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: all 0.4s ease; text-decoration: none; height: 100%;">

                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div style="position: relative; overflow: hidden; aspect-ratio: 16/9;">
                                        <?php the_post_thumbnail( 'medium_large', array(
                                            'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;',
                                            'class' => 'card-image-hover'
                                        ) ); ?>

                                        <!-- بادج التصنيف -->
                                        <?php
                                        $categories = get_the_category();
                                        if ( ! empty( $categories ) ) : ?>
                                            <span style="position: absolute; top: var(--spacing-md); left: var(--spacing-md); background: var(--color-primary); color: #fff; padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                                                <?php echo esc_html( $categories[0]->name ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else : ?>
                                    <!-- صورة افتراضية -->
                                    <div style="aspect-ratio: 16/9; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; position: relative; overflow: hidden;">
                                        <div style="position: absolute; inset: 0; opacity: 0.1; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                                        <span style="position: relative; z-index: 1;">📝</span>
                                    </div>
                                <?php endif; ?>

                                <!-- المحتوى -->
                                <div class="card-content" style="padding: var(--spacing-lg);">
                                    <!-- العنوان -->
                                    <h2 class="card-title" style="margin-bottom: var(--spacing-md); color: var(--color-text); font-size: var(--font-size-xl); line-height: 1.4; font-weight: 700; transition: color 0.3s ease;">
                                        <?php the_title(); ?>
                                    </h2>

                                    <!-- المقتطف -->
                                    <div class="card-excerpt" style="color: var(--color-text-secondary); line-height: 1.7; font-size: 15px;">
                                        <?php echo nadiim_get_excerpt( 20 ); ?>
                                    </div>
                                </div>
                            </a>
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
/* تأثيرات البطاقات المدمجة */
.post-card-compact a:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.12);
}

.post-card-compact:hover .card-image-hover {
    transform: scale(1.08);
}

.post-card-compact:hover .card-title {
    color: var(--color-primary);
}

/* تجاوب مع الشاشات المتوسطة والصغيرة */
@media (max-width: 992px) {
    .posts-grid {
        grid-template-columns: 1fr !important;
    }
}

@media (max-width: 768px) {
    .blog-hero-fullwidth {
        min-height: 70vh !important;
    }
}

@media (max-width: 640px) {
    .blog-hero-fullwidth {
        min-height: 60vh !important;
    }

    .card-content {
        padding: var(--spacing-md) !important;
    }

    .card-title {
        font-size: var(--font-size-lg) !important;
    }
}
</style>

<?php
get_footer();
