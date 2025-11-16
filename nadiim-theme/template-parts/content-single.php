<?php
/**
 * قالب عرض المقال المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */

// حساب وقت القراءة
$word_count = str_word_count( strip_tags( get_the_content() ) );
$reading_time = ceil( $word_count / 200 );

// الحصول على الملف الصوتي
$audio_file = get_post_meta( get_the_ID(), 'article_audio_url', true );

// عدد المشاهدات (إذا كان متوفراً)
$post_views = get_post_meta( get_the_ID(), 'post_views_count', true ) ?: 0;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-single' ); ?>>

    <!-- Hero بعرض كامل مع الصورة كخلفية -->
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-hero-fullwidth" style="position: relative; min-height: 70vh; display: flex; align-items: center; margin-bottom: 0; overflow: hidden;">

            <!-- الصورة كخلفية -->
            <div style="position: absolute; inset: 0; z-index: 0;">
                <?php the_post_thumbnail( 'full', array(
                    'style' => 'width: 100%; height: 100%; object-fit: cover;'
                ) ); ?>
                <!-- تدرج لوني -->
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 60%, rgba(0,0,0,0.85) 100%);"></div>
            </div>

            <!-- المحتوى -->
            <div class="container" style="position: relative; z-index: 1; padding: var(--spacing-4xl) var(--spacing-lg);">
                <div style="max-width: 900px; margin: 0 auto; text-align: center;">

                    <!-- التصنيفات -->
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) : ?>
                        <div class="entry-categories" style="margin-bottom: var(--spacing-lg);">
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                                   style="display: inline-block; background: rgba(51, 144, 99, 0.9); backdrop-filter: blur(10px); color: #fff; padding: 10px 24px; border-radius: var(--radius-full); font-size: 14px; font-weight: 700; margin: 0 6px; text-decoration: none; box-shadow: 0 4px 20px rgba(51, 144, 99, 0.5); transition: all 0.3s ease;">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- العنوان في المنتصف -->
                    <h1 class="entry-title" style="font-size: clamp(2rem, 5vw, 4rem); line-height: 1.2; margin-bottom: 0; color: #fff; font-weight: 900; text-shadow: 0 4px 30px rgba(0,0,0,0.7);">
                        <?php the_title(); ?>
                    </h1>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- شريط معلومات المقال -->
    <div style="background: #fff; padding: var(--spacing-xl) 0; border-bottom: 1px solid var(--color-border); margin-bottom: var(--spacing-2xl);">
        <div class="container">
            <div style="max-width: 900px; margin: 0 auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-2xl); align-items: center;">

                    <!-- العمود الأيمن: الكاتب -->
                    <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" style="flex-shrink: 0;">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array(
                                'style' => 'border-radius: 50%; border: 3px solid var(--color-primary); box-shadow: 0 4px 15px rgba(0,0,0,0.1);'
                            ) ); ?>
                        </a>
                        <div>
                            <div style="font-size: 13px; color: var(--color-text-secondary); margin-bottom: 4px; font-weight: 600;">
                                <?php esc_html_e( 'كتبه:', 'nadiim' ); ?>
                            </div>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                               style="color: var(--color-text); text-decoration: none; font-weight: 800; font-size: 18px; transition: color 0.3s;">
                                <?php the_author(); ?>
                            </a>
                        </div>
                    </div>

                    <!-- العمود الأيسر: التاريخ والمشاهدات -->
                    <div style="text-align: left;">
                        <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                            <!-- تاريخ النشر -->
                            <div style="display: flex; align-items: center; gap: 8px; color: var(--color-text); font-size: 15px;">
                                <span style="color: var(--color-primary); font-size: 18px;">📅</span>
                                <span style="font-weight: 600; color: var(--color-text-secondary);">تاريخ النشر |</span>
                                <time style="font-weight: 700;"><?php echo get_the_date( 'j - F - Y' ); ?></time>
                            </div>
                            <!-- عدد المشاهدات -->
                            <div style="display: flex; align-items: center; gap: 8px; color: var(--color-text); font-size: 15px;">
                                <span style="color: var(--color-primary); font-size: 18px;">👁️</span>
                                <span style="font-weight: 600; color: var(--color-text-secondary);">عدد المشاهدات</span>
                                <span style="font-weight: 700;"><?php echo number_format_i18n( $post_views ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فاصل -->
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto var(--spacing-3xl);">
            <div style="height: 1px; background: linear-gradient(to left, transparent, var(--color-border), transparent);"></div>
        </div>
    </div>

    <!-- مشغل الصوت إذا كان موجوداً -->
    <?php if ( $audio_file ) : ?>
        <div class="container">
            <div class="audio-player-section" style="max-width: 800px; margin: 0 auto var(--spacing-3xl); padding: var(--spacing-xl); background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: var(--radius-2xl); box-shadow: 0 8px 30px rgba(0,0,0,0.08); border: 2px solid var(--color-border);">
                <div style="display: flex; align-items: center; gap: var(--spacing-md); margin-bottom: var(--spacing-md);">
                    <span style="font-size: 32px;">🎧</span>
                    <div>
                        <h3 style="font-size: var(--font-size-lg); font-weight: 800; margin: 0 0 4px 0; color: var(--color-text);">
                            <?php esc_html_e( 'استمع للمقال', 'nadiim' ); ?>
                        </h3>
                        <p style="margin: 0; font-size: 14px; color: var(--color-text-secondary);">
                            <?php esc_html_e( 'يمكنك الاستماع للمقال بدلاً من القراءة', 'nadiim' ); ?>
                        </p>
                    </div>
                </div>
                <audio controls style="width: 100%; border-radius: var(--radius-lg); outline: none;">
                    <source src="<?php echo esc_url( $audio_file ); ?>" type="audio/mpeg">
                    <?php esc_html_e( 'متصفحك لا يدعم تشغيل الملفات الصوتية.', 'nadiim' ); ?>
                </audio>
            </div>
        </div>
    <?php endif; ?>

    <!-- المحتوى -->
    <div class="container">
        <div class="entry-content" style="font-size: 19px; line-height: 2; color: var(--color-text); max-width: 800px; margin: 0 auto var(--spacing-4xl);">
            <?php
            the_content();

            wp_link_pages( array(
                'before' => '<div class="page-links" style="margin-top: var(--spacing-3xl); padding: var(--spacing-xl); background: var(--color-bg-section); border-radius: var(--radius-xl); text-align: center; font-weight: 700;">' . esc_html__( 'الصفحات:', 'nadiim' ),
                'after'  => '</div>',
            ) );
            ?>
        </div>
    </div>

    <!-- فاصل -->
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto var(--spacing-2xl);">
            <div style="height: 2px; background: linear-gradient(to left, transparent, var(--color-primary), transparent);"></div>
        </div>
    </div>

    <!-- أزرار المشاركة -->
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto var(--spacing-3xl);">
            <div style="display: flex; align-items: center; justify-content: center; gap: var(--spacing-lg); flex-wrap: wrap;">
                <?php
                $post_url = urlencode( get_permalink() );
                $post_title = urlencode( get_the_title() );
                ?>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>"
                   target="_blank" rel="noopener"
                   style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: #1877F2; color: #fff; border-radius: var(--radius-2xl); text-decoration: none; transition: all 0.3s ease; font-size: 24px; box-shadow: 0 4px 15px rgba(24, 119, 242, 0.3);"
                   title="<?php esc_attr_e( 'مشاركة على فيسبوك', 'nadiim' ); ?>">
                    f
                </a>
                <a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>"
                   target="_blank" rel="noopener"
                   style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: #25D366; color: #fff; border-radius: var(--radius-2xl); text-decoration: none; transition: all 0.3s ease; font-size: 24px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);"
                   title="<?php esc_attr_e( 'مشاركة على واتساب', 'nadiim' ); ?>">
                    💬
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                   target="_blank" rel="noopener"
                   style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: #1DA1F2; color: #fff; border-radius: var(--radius-2xl); text-decoration: none; transition: all 0.3s ease; font-size: 24px; box-shadow: 0 4px 15px rgba(29, 161, 242, 0.3);"
                   title="<?php esc_attr_e( 'مشاركة على تويتر', 'nadiim' ); ?>">
                    𝕏
                </a>
                <a href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>"
                   style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: #EA4335; color: #fff; border-radius: var(--radius-2xl); text-decoration: none; transition: all 0.3s ease; font-size: 24px; box-shadow: 0 4px 15px rgba(234, 67, 53, 0.3);"
                   title="<?php esc_attr_e( 'مشاركة عبر البريد', 'nadiim' ); ?>">
                    ✉️
                </a>
            </div>
        </div>
    </div>

    <!-- فاصل -->
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto var(--spacing-3xl);">
            <div style="height: 2px; background: linear-gradient(to left, transparent, var(--color-primary), transparent);"></div>
        </div>
    </div>

    <!-- المزيد من المقالات -->
    <?php
    $related_posts = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'orderby'        => 'rand',
    ) );

    if ( $related_posts->have_posts() ) : ?>
        <div class="container">
            <div style="max-width: 1200px; margin: 0 auto var(--spacing-4xl);">
                <h2 style="text-align: center; font-size: clamp(2rem, 4vw, 2.5rem); font-weight: 900; margin-bottom: var(--spacing-2xl); color: var(--color-text);">
                    <?php esc_html_e( 'المزيد من المقالات', 'nadiim' ); ?>
                </h2>

                <div style="display: flex; flex-direction: column; gap: var(--spacing-xl);">
                    <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
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
                            <?php endif; ?>

                            <!-- المحتوى -->
                            <div class="card-content" style="flex: 1; padding: var(--spacing-lg); display: flex; flex-direction: column; justify-content: center; min-width: 0;">

                                <!-- العنوان -->
                                <h3 class="card-title" style="margin-bottom: var(--spacing-md);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-xl); line-height: 1.3; display: block; transition: color 0.3s ease; font-weight: 700;">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <!-- المقتطف -->
                                <div class="card-excerpt" style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-md); font-size: var(--font-size-base);">
                                    <?php echo nadiim_get_excerpt( 25 ); ?>
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
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- قسم التعليقات -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
        <div class="container">
            <div class="comments-section" style="max-width: 900px; margin: 0 auto var(--spacing-3xl);">
                <?php comments_template(); ?>
            </div>
        </div>
    <?php endif; ?>

</article>

<!-- GLightbox للصور -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة lightbox للصور داخل المحتوى
    const contentImages = document.querySelectorAll('.entry-content img');
    contentImages.forEach(img => {
        if (!img.closest('a')) {
            const link = document.createElement('a');
            link.href = img.src;
            link.classList.add('glightbox');
            link.setAttribute('data-gallery', 'article-gallery');
            img.parentNode.insertBefore(link, img);
            link.appendChild(img);
        }
    });

    // تفعيل GLightbox
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });
});
</script>

<style>
/* تأثيرات التحويم */
.entry-categories a:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(51, 144, 99, 0.6);
}

.entry-hero-fullwidth a:hover {
    opacity: 0.9;
}

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

/* أزرار المشاركة */
.container a[href*="facebook"]:hover,
.container a[href*="wa.me"]:hover,
.container a[href*="twitter"]:hover,
.container a[href*="mailto"]:hover {
    transform: translateY(-6px) scale(1.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* تنسيقات المحتوى */
.entry-content h2,
.entry-content h3,
.entry-content h4 {
    margin-top: 2.5em;
    margin-bottom: 1em;
    font-weight: 800;
    color: var(--color-text);
    line-height: 1.3;
}

.entry-content h2 {
    font-size: 2.25rem;
    border-right: 6px solid var(--color-primary);
    padding-right: var(--spacing-lg);
}

.entry-content h3 {
    font-size: 1.75rem;
    position: relative;
    padding-right: var(--spacing-md);
}

.entry-content h3::before {
    content: '';
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 70%;
    background: var(--color-primary);
    border-radius: var(--radius-full);
}

.entry-content p {
    margin-bottom: 1.75em;
}

.entry-content a {
    color: var(--color-primary);
    text-decoration: none;
    border-bottom: 2px solid var(--color-primary);
    transition: all 0.3s ease;
    font-weight: 600;
}

.entry-content a:hover {
    color: var(--color-primary-light);
    border-bottom-color: var(--color-primary-light);
    background: rgba(51, 144, 99, 0.05);
}

.entry-content img {
    border-radius: var(--radius-2xl);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    margin: 3em 0;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.entry-content img:hover {
    transform: scale(1.02);
}

.entry-content blockquote {
    margin: 3em 0;
    padding: var(--spacing-xl) var(--spacing-2xl);
    background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%);
    border-right: 6px solid var(--color-primary);
    border-radius: var(--radius-xl);
    font-size: 1.2em;
    font-style: italic;
    color: var(--color-text-secondary);
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.entry-content ul,
.entry-content ol {
    margin: 2em 0;
    padding-right: 2.5em;
}

.entry-content li {
    margin-bottom: 0.75em;
    line-height: 1.8;
}

.entry-content li::marker {
    color: var(--color-primary);
    font-weight: 700;
}

/* تجاوب */
@media (max-width: 992px) {
    .post-card-horizontal {
        flex-direction: column !important;
    }

    .post-card-horizontal a[style*="width: 320px"] {
        width: 100% !important;
        min-height: 250px;
    }

    .entry-hero-fullwidth > div > div {
        grid-template-columns: 1fr;
        text-align: center;
        gap: var(--spacing-lg);
    }
}

@media (max-width: 768px) {
    .entry-hero-fullwidth {
        min-height: 60vh !important;
    }

    .entry-content h2 {
        font-size: 1.75rem;
    }
}

/* تحسين مشغل الصوت */
audio::-webkit-media-controls-panel {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
}
</style>
