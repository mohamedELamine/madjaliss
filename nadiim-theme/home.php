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

<main id="primary" class="site-main home-page">

    <?php
    // قسم Hero
    if ( get_theme_mod( 'nadiim_hero_enable', true ) ) :
        $hero_title = get_theme_mod( 'nadiim_hero_title', __( 'مرحباً بكم في نديم', 'nadiim' ) );
        $hero_desc = get_theme_mod( 'nadiim_hero_description', __( 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة', 'nadiim' ) );
        $hero_button_text = get_theme_mod( 'nadiim_hero_button_text', __( 'استكشف المحتوى', 'nadiim' ) );
        $hero_button_url = get_theme_mod( 'nadiim_hero_button_url', '#' );
        $hero_bg_color = get_theme_mod( 'nadiim_hero_bg_color', '#f5f5f5' );
        $hero_bg_image = get_theme_mod( 'nadiim_hero_bg_image' );
        ?>
        <section class="hero-section" style="background: linear-gradient(135deg, <?php echo esc_attr( $hero_bg_color ); ?> 0%, #ffffff 100%); <?php if ( $hero_bg_image ) : ?>background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $hero_bg_image, 'full' ) ); ?>); background-size: cover; background-position: center; background-blend-mode: overlay;<?php endif; ?> padding: calc(var(--spacing-xxl) + 40px) 0 var(--spacing-xxl); position: relative; overflow: hidden;">
            <div class="hero-decoration" style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(51, 144, 99, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
            <div class="hero-decoration" style="position: absolute; bottom: -150px; right: -150px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(51, 144, 99, 0.08) 0%, transparent 70%); border-radius: 50%;"></div>
            <div class="container text-center" style="position: relative; z-index: 2;">
                <h1 class="hero-title" style="font-size: clamp(32px, 5vw, var(--font-size-3xl)); margin-bottom: var(--spacing-md); font-weight: 800; line-height: 1.3;">
                    <?php echo esc_html( $hero_title ); ?>
                </h1>
                <p class="hero-description" style="font-size: clamp(18px, 3vw, var(--font-size-xl)); color: var(--color-text-secondary); max-width: 700px; margin: 0 auto var(--spacing-lg); line-height: 1.8;">
                    <?php echo esc_html( $hero_desc ); ?>
                </p>
                <?php if ( $hero_button_url && $hero_button_text ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary" style="padding: 14px 40px; font-size: 18px; box-shadow: 0 4px 20px rgba(51, 144, 99, 0.2);">
                        <?php echo esc_html( $hero_button_text ); ?>
                    </a>
                <?php endif; ?>
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
            </section>
        <?php endif;
    endif; ?>

    <?php
    // قسم النشرة البريدية
    if ( get_theme_mod( 'nadiim_newsletter_enable', true ) ) :
        $newsletter_title = get_theme_mod( 'nadiim_newsletter_title', __( 'اشترك في نشرتنا البريدية', 'nadiim' ) );
        $newsletter_desc = get_theme_mod( 'nadiim_newsletter_description', __( 'تلقَّ آخر الأخبار والإصدارات والفعاليات مباشرة في بريدك', 'nadiim' ) );
        ?>
        <section class="newsletter-section section" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); color: #fff; position: relative; overflow: hidden; padding: calc(var(--spacing-xl) + 40px) 0;">
            <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
            <div style="position: absolute; bottom: -80px; right: -80px; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; filter: blur(50px);"></div>
            <div class="container text-center" style="position: relative; z-index: 2;">
                <h2 style="color: #fff; margin-bottom: var(--spacing-sm); font-size: var(--font-size-2xl); font-weight: 800;"><?php echo esc_html( $newsletter_title ); ?></h2>
                <p style="color: rgba(255,255,255,0.95); font-size: var(--font-size-lg); margin-bottom: var(--spacing-lg); max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.8;">
                    <?php echo esc_html( $newsletter_desc ); ?>
                </p>
                <form class="newsletter-form" style="max-width: 500px; margin: 0 auto; display: flex; gap: var(--spacing-sm); flex-wrap: wrap; justify-content: center;">
                    <input type="email" placeholder="<?php esc_attr_e( 'بريدك الإلكتروني', 'nadiim' ); ?>" required style="flex: 1; min-width: 250px; padding: 14px 24px; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: var(--radius-md); font-size: var(--font-size-base); background: rgba(255, 255, 255, 0.15); color: #fff; backdrop-filter: blur(10px);" onfocus="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.borderColor='rgba(255, 255, 255, 0.5)';" onblur="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.borderColor='rgba(255, 255, 255, 0.3)';">
                    <button type="submit" class="btn" style="background: #fff; color: var(--color-primary); border: none; padding: 14px 36px; font-weight: 700; border-radius: var(--radius-md); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.2)';">
                        <?php esc_html_e( 'اشترك', 'nadiim' ); ?>
                    </button>
                </form>
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
