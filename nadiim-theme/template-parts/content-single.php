<?php
/**
 * قالب عرض المقال المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-single' ); ?>>

    <!-- Hero مع الصورة المميزة -->
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-hero" style="margin: calc(-1 * var(--spacing-xl)) calc(-1 * var(--spacing-xl)) var(--spacing-3xl); position: relative; min-height: 500px; display: flex; align-items: flex-end; overflow: hidden;">
            <!-- الخلفية -->
            <div style="position: absolute; inset: 0; z-index: 0;">
                <?php the_post_thumbnail( 'full', array(
                    'style' => 'width: 100%; height: 100%; object-fit: cover;',
                    'class' => 'article-hero-image'
                ) ); ?>
                <!-- تدرج لوني -->
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 70%, rgba(0,0,0,0.85) 100%);"></div>
            </div>

            <!-- المحتوى -->
            <div class="container" style="position: relative; z-index: 1; padding: var(--spacing-3xl) var(--spacing-lg);">
                <div style="max-width: 850px; margin: 0 auto;">
                    <!-- التصنيفات -->
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) : ?>
                        <div class="entry-categories" style="margin-bottom: var(--spacing-md);">
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                                   style="display: inline-block; background: var(--color-primary); color: #fff; padding: 8px 20px; border-radius: var(--radius-full); font-size: 14px; font-weight: 600; margin-left: 8px; text-decoration: none; box-shadow: 0 4px 15px rgba(51, 144, 99, 0.4); transition: all 0.3s ease;">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- العنوان -->
                    <h1 class="entry-title" style="font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.2; margin-bottom: var(--spacing-lg); color: #fff; font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                        <?php the_title(); ?>
                    </h1>

                    <!-- المعلومات الأساسية -->
                    <div class="entry-meta" style="display: flex; align-items: center; gap: var(--spacing-lg); flex-wrap: wrap; color: rgba(255,255,255,0.9); font-size: 15px;">
                        <div class="meta-author" style="display: flex; align-items: center; gap: var(--spacing-sm);">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'style' => 'border-radius: 50%; border: 2px solid rgba(255,255,255,0.3);' ) ); ?>
                            <span style="font-weight: 600;"><?php the_author(); ?></span>
                        </div>
                        <span style="opacity: 0.5;">•</span>
                        <div class="meta-date" style="display: flex; align-items: center; gap: 6px;">
                            📅 <?php echo get_the_date(); ?>
                        </div>
                        <span style="opacity: 0.5;">•</span>
                        <div class="meta-reading-time" style="display: flex; align-items: center; gap: 6px;">
                            ⏱️
                            <?php
                            $word_count = str_word_count( strip_tags( get_the_content() ) );
                            $reading_time = ceil( $word_count / 200 );
                            printf( esc_html__( '%d دقائق قراءة', 'nadiim' ), $reading_time );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- عنوان بدون صورة -->
        <header class="entry-header" style="text-align: center; margin-bottom: var(--spacing-3xl); padding-top: var(--spacing-xl);">
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) : ?>
                <div class="entry-categories" style="margin-bottom: var(--spacing-md);">
                    <?php foreach ( $categories as $category ) : ?>
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                           style="display: inline-block; background: var(--color-primary); color: #fff; padding: 8px 20px; border-radius: var(--radius-full); font-size: 14px; font-weight: 600; margin: 0 4px; text-decoration: none;">
                            <?php echo esc_html( $category->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="entry-title" style="font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.2; margin-bottom: var(--spacing-lg); max-width: 850px; margin-left: auto; margin-right: auto; font-weight: 800;">
                <?php the_title(); ?>
            </h1>

            <div class="entry-meta" style="display: flex; align-items: center; justify-content: center; gap: var(--spacing-md); flex-wrap: wrap; color: var(--color-text-secondary); font-size: 15px;">
                <div class="meta-author" style="display: flex; align-items: center; gap: var(--spacing-sm);">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'style' => 'border-radius: 50%;' ) ); ?>
                    <span><?php the_author(); ?></span>
                </div>
                <span>•</span>
                <div class="meta-date">
                    📅 <?php echo get_the_date(); ?>
                </div>
                <span>•</span>
                <div class="meta-reading-time">
                    ⏱️ <?php printf( esc_html__( '%d دقائق قراءة', 'nadiim' ), $reading_time ); ?>
                </div>
            </div>
        </header>
    <?php endif; ?>

    <!-- المقتطف إذا كان موجوداً -->
    <?php if ( has_excerpt() ) : ?>
        <div class="entry-excerpt" style="font-size: var(--font-size-xl); line-height: 1.9; color: var(--color-text-secondary); max-width: 750px; margin: 0 auto var(--spacing-3xl); padding: var(--spacing-xl); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); border-radius: var(--radius-xl); border-right: 5px solid var(--color-primary); font-weight: 500; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <!-- المحتوى -->
    <div class="entry-content" style="font-size: 18px; line-height: 2; color: var(--color-text); max-width: 750px; margin: 0 auto var(--spacing-3xl);">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links" style="margin-top: var(--spacing-2xl); padding: var(--spacing-lg); background: var(--color-bg-section); border-radius: var(--radius-lg); text-align: center;">' . esc_html__( 'الصفحات:', 'nadiim' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <!-- الوسوم ومشاركة المقال -->
    <div style="max-width: 750px; margin: 0 auto var(--spacing-3xl); padding-top: var(--spacing-xl); border-top: 2px solid var(--color-border);">
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-xl);">

            <!-- الوسوم -->
            <?php
            $tags = get_the_tags();
            if ( $tags ) : ?>
                <div class="entry-tags">
                    <div style="display: flex; align-items: center; gap: var(--spacing-md); flex-wrap: wrap;">
                        <span style="font-weight: 700; color: var(--color-text); font-size: 16px; flex-shrink: 0;">
                            🏷️ <?php esc_html_e( 'الوسوم:', 'nadiim' ); ?>
                        </span>
                        <div style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                                   style="display: inline-block; padding: 8px 18px; background: var(--color-bg-section); border: 2px solid var(--color-border); border-radius: var(--radius-full); font-size: 14px; color: var(--color-text); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                                    <?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- أزرار المشاركة -->
            <div class="entry-share" style="padding: var(--spacing-lg); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); border-radius: var(--radius-lg);">
                <div style="display: flex; align-items: center; gap: var(--spacing-md); flex-wrap: wrap; justify-content: center;">
                    <span style="font-weight: 700; color: var(--color-text); font-size: 16px;">
                        📢 <?php esc_html_e( 'شارك المقال:', 'nadiim' ); ?>
                    </span>
                    <?php
                    $post_url = urlencode( get_permalink() );
                    $post_title = urlencode( get_the_title() );
                    ?>
                    <div style="display: flex; gap: var(--spacing-sm);">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: #1DA1F2; color: #fff; border-radius: var(--radius-md); text-decoration: none; transition: transform 0.3s ease; font-size: 18px;">
                            𝕏
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: #1877F2; color: #fff; border-radius: var(--radius-md); text-decoration: none; transition: transform 0.3s ease; font-size: 18px;">
                            f
                        </a>
                        <a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: #25D366; color: #fff; border-radius: var(--radius-md); text-decoration: none; transition: transform 0.3s ease; font-size: 18px;">
                            💬
                        </a>
                        <a href="https://t.me/share/url?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: #0088cc; color: #fff; border-radius: var(--radius-md); text-decoration: none; transition: transform 0.3s ease; font-size: 18px;">
                            ✈️
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات الكاتب -->
    <div class="author-bio" style="max-width: 900px; margin: 0 auto var(--spacing-2xl); padding: var(--spacing-2xl); background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: var(--radius-2xl); box-shadow: 0 5px 25px rgba(0,0,0,0.08); border-right: 6px solid var(--color-primary);">
        <div style="display: grid; grid-template-columns: 120px 1fr; gap: var(--spacing-xl); align-items: start;">
            <div style="text-align: center;">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 120, '', '', array( 'style' => 'border-radius: 50%; border: 5px solid #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.12);' ) ); ?>
            </div>
            <div>
                <div style="margin-bottom: var(--spacing-sm);">
                    <span style="display: inline-block; background: var(--color-primary); color: #fff; padding: 4px 12px; border-radius: var(--radius-full); font-size: 12px; font-weight: 600; margin-bottom: var(--spacing-xs);">
                        <?php esc_html_e( 'الكاتب', 'nadiim' ); ?>
                    </span>
                    <h3 style="font-size: var(--font-size-2xl); margin: 0; font-weight: 800; color: var(--color-text);">
                        <?php echo esc_html( get_the_author() ); ?>
                    </h3>
                </div>
                <?php if ( get_the_author_meta( 'description' ) ) : ?>
                    <p style="color: var(--color-text-secondary); line-height: 1.8; margin-bottom: var(--spacing-lg); font-size: 16px;">
                        <?php echo esc_html( get_the_author_meta( 'description' ) ); ?>
                    </p>
                <?php else : ?>
                    <p style="color: var(--color-text-secondary); line-height: 1.8; margin-bottom: var(--spacing-lg); font-size: 16px; font-style: italic;">
                        <?php esc_html_e( 'كاتب ومساهم في الموقع.', 'nadiim' ); ?>
                    </p>
                <?php endif; ?>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                   class="btn btn-primary"
                   style="display: inline-flex; align-items: center; gap: var(--spacing-xs); padding: var(--spacing-sm) var(--spacing-lg); font-size: 15px; font-weight: 600;">
                    <?php esc_html_e( 'جميع مقالات الكاتب', 'nadiim' ); ?>
                    <span>←</span>
                </a>
            </div>
        </div>
    </div>

</article>

<style>
/* تأثيرات التحويم */
.entry-categories a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(51, 144, 99, 0.5);
}

.entry-tags a:hover {
    background: var(--color-primary);
    color: #fff;
    border-color: var(--color-primary);
    transform: translateY(-2px);
}

.entry-share a:hover {
    transform: translateY(-3px) scale(1.05);
}

.author-bio:hover {
    box-shadow: 0 8px 35px rgba(0,0,0,0.12);
}

/* تنسيقات المحتوى */
.entry-content h2,
.entry-content h3,
.entry-content h4 {
    margin-top: 2em;
    margin-bottom: 1em;
    font-weight: 700;
    color: var(--color-text);
    line-height: 1.3;
}

.entry-content h2 {
    font-size: 2rem;
    border-right: 5px solid var(--color-primary);
    padding-right: var(--spacing-md);
}

.entry-content h3 {
    font-size: 1.5rem;
}

.entry-content p {
    margin-bottom: 1.5em;
}

.entry-content a {
    color: var(--color-primary);
    text-decoration: none;
    border-bottom: 2px solid var(--color-primary);
    transition: all 0.3s ease;
}

.entry-content a:hover {
    color: var(--color-primary-light);
    border-bottom-color: var(--color-primary-light);
}

.entry-content img {
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin: 2em 0;
}

.entry-content blockquote {
    margin: 2em 0;
    padding: var(--spacing-lg) var(--spacing-xl);
    background: var(--color-bg-section);
    border-right: 5px solid var(--color-primary);
    border-radius: var(--radius-lg);
    font-size: 1.1em;
    font-style: italic;
    color: var(--color-text-secondary);
}

.entry-content ul,
.entry-content ol {
    margin: 1.5em 0;
    padding-right: 2em;
}

.entry-content li {
    margin-bottom: 0.5em;
}

@media (max-width: 768px) {
    .author-bio > div {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .entry-hero {
        min-height: 400px;
    }

    .entry-share > div {
        flex-direction: column;
        text-align: center;
    }
}
</style>
