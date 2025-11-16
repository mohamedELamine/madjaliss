<?php
/**
 * قالب عرض المقال المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */

// حساب وقت القراءة مرة واحدة في البداية
$word_count = str_word_count( strip_tags( get_the_content() ) );
$reading_time = ceil( $word_count / 200 );

// الحصول على الملف الصوتي
$audio_file = get_post_meta( get_the_ID(), 'article_audio_url', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-single' ); ?>>

    <!-- Hero مع الصورة كخلفية شفافة -->
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-hero" style="margin: calc(-1 * var(--spacing-xl)) calc(-1 * var(--spacing-xl)) var(--spacing-3xl); position: relative; min-height: 600px; display: flex; align-items: center; overflow: hidden;">

            <!-- الصورة كخلفية -->
            <div style="position: absolute; inset: 0; z-index: 0;">
                <?php the_post_thumbnail( 'full', array(
                    'style' => 'width: 100%; height: 100%; object-fit: cover; filter: brightness(0.7);',
                    'class' => 'article-hero-bg'
                ) ); ?>
                <!-- تدرج لوني شفاف -->
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.75) 60%, rgba(0,0,0,0.9) 100%);"></div>
                <!-- طبقة شفافية إضافية -->
                <div style="position: absolute; inset: 0; background: rgba(45, 95, 74, 0.15);"></div>
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
                                   style="display: inline-block; background: rgba(51, 144, 99, 0.9); backdrop-filter: blur(10px); color: #fff; padding: 10px 24px; border-radius: var(--radius-full); font-size: 14px; font-weight: 700; margin: 0 6px; text-decoration: none; box-shadow: 0 4px 20px rgba(51, 144, 99, 0.5); transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; border: 2px solid rgba(255,255,255,0.2);">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- العنوان -->
                    <h1 class="entry-title" style="font-size: clamp(2.5rem, 6vw, 4.5rem); line-height: 1.15; margin-bottom: var(--spacing-xl); color: #fff; font-weight: 900; text-shadow: 0 4px 30px rgba(0,0,0,0.5); letter-spacing: -0.02em;">
                        <?php the_title(); ?>
                    </h1>

                    <!-- شريط المعلومات -->
                    <div style="display: inline-flex; align-items: center; gap: var(--spacing-lg); flex-wrap: wrap; background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full); border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 8px 32px rgba(0,0,0,0.3);">

                        <!-- الكاتب -->
                        <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array( 'style' => 'border-radius: 50%; border: 3px solid rgba(255,255,255,0.3); box-shadow: 0 4px 15px rgba(0,0,0,0.3);' ) ); ?>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" style="color: #fff; text-decoration: none; font-weight: 700; font-size: 16px; transition: opacity 0.3s ease;">
                                <?php the_author(); ?>
                            </a>
                        </div>

                        <span style="color: rgba(255,255,255,0.4); font-size: 20px;">•</span>

                        <!-- التاريخ -->
                        <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.95); font-size: 15px; font-weight: 600;">
                            <span style="font-size: 20px;">📅</span>
                            <span><?php echo get_the_date( 'j F، Y' ); ?></span>
                        </div>

                        <span style="color: rgba(255,255,255,0.4); font-size: 20px;">•</span>

                        <!-- وقت القراءة -->
                        <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.95); font-size: 15px; font-weight: 600;">
                            <span style="font-size: 20px;">⏱️</span>
                            <span><?php printf( esc_html__( '%d دقائق', 'nadiim' ), $reading_time ); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- عنوان بدون صورة -->
        <header class="entry-header" style="text-align: center; margin-bottom: var(--spacing-3xl); padding: var(--spacing-3xl) 0; background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%);">
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) : ?>
                <div class="entry-categories" style="margin-bottom: var(--spacing-lg);">
                    <?php foreach ( $categories as $category ) : ?>
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                           style="display: inline-block; background: var(--color-primary); color: #fff; padding: 10px 24px; border-radius: var(--radius-full); font-size: 14px; font-weight: 700; margin: 0 6px; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px;">
                            <?php echo esc_html( $category->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="entry-title" style="font-size: clamp(2.5rem, 6vw, 4.5rem); line-height: 1.15; margin-bottom: var(--spacing-xl); max-width: 900px; margin-left: auto; margin-right: auto; font-weight: 900;">
                <?php the_title(); ?>
            </h1>

            <div style="display: inline-flex; align-items: center; gap: var(--spacing-lg); flex-wrap: wrap; background: var(--color-bg-lighter); padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full); border: 2px solid var(--color-border);">
                <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array( 'style' => 'border-radius: 50%;' ) ); ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" style="color: var(--color-text); text-decoration: none; font-weight: 700; font-size: 16px;">
                        <?php the_author(); ?>
                    </a>
                </div>
                <span style="color: var(--color-border);">•</span>
                <div style="color: var(--color-text-secondary); font-size: 15px; font-weight: 600;">
                    📅 <?php echo get_the_date( 'j F، Y' ); ?>
                </div>
                <span style="color: var(--color-border);">•</span>
                <div style="color: var(--color-text-secondary); font-size: 15px; font-weight: 600;">
                    ⏱️ <?php printf( esc_html__( '%d دقائق', 'nadiim' ), $reading_time ); ?>
                </div>
            </div>
        </header>
    <?php endif; ?>

    <!-- مشغل الصوت إذا كان موجوداً -->
    <?php if ( $audio_file ) : ?>
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
    <?php endif; ?>

    <!-- المقتطف إذا كان موجوداً -->
    <?php if ( has_excerpt() ) : ?>
        <div class="entry-excerpt" style="font-size: var(--font-size-xl); line-height: 2; color: var(--color-text-secondary); max-width: 800px; margin: 0 auto var(--spacing-3xl); padding: var(--spacing-2xl); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); border-radius: var(--radius-2xl); border-right: 6px solid var(--color-primary); font-weight: 500; box-shadow: 0 5px 20px rgba(0,0,0,0.06); font-style: italic;">
            <span style="font-size: 48px; color: var(--color-primary); line-height: 1; opacity: 0.3; display: block; margin-bottom: var(--spacing-sm);">"</span>
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <!-- المحتوى -->
    <div class="entry-content" style="font-size: 19px; line-height: 2; color: var(--color-text); max-width: 800px; margin: 0 auto var(--spacing-4xl);">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links" style="margin-top: var(--spacing-3xl); padding: var(--spacing-xl); background: var(--color-bg-section); border-radius: var(--radius-xl); text-align: center; font-weight: 700;">' . esc_html__( 'الصفحات:', 'nadiim' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <!-- الوسوم ومشاركة المقال -->
    <div style="max-width: 800px; margin: 0 auto var(--spacing-4xl); padding-top: var(--spacing-2xl); border-top: 3px solid var(--color-border);">
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-2xl);">

            <!-- الوسوم -->
            <?php
            $tags = get_the_tags();
            if ( $tags ) : ?>
                <div class="entry-tags">
                    <div style="display: flex; align-items: center; gap: var(--spacing-lg); flex-wrap: wrap;">
                        <span style="font-weight: 800; color: var(--color-text); font-size: 18px; flex-shrink: 0;">
                            🏷️ <?php esc_html_e( 'الوسوم:', 'nadiim' ); ?>
                        </span>
                        <div style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                                   style="display: inline-block; padding: 10px 20px; background: var(--color-bg-section); border: 2px solid var(--color-border); border-radius: var(--radius-full); font-size: 14px; color: var(--color-text); text-decoration: none; transition: all 0.3s ease; font-weight: 600;">
                                    <?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- أزرار المشاركة -->
            <div class="entry-share" style="padding: var(--spacing-xl); background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: var(--radius-xl); box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 2px solid var(--color-border);">
                <div style="display: flex; align-items: center; gap: var(--spacing-lg); flex-wrap: wrap; justify-content: center;">
                    <span style="font-weight: 800; color: var(--color-text); font-size: 18px;">
                        📢 <?php esc_html_e( 'شارك المقال:', 'nadiim' ); ?>
                    </span>
                    <?php
                    $post_url = urlencode( get_permalink() );
                    $post_title = urlencode( get_the_title() );
                    ?>
                    <div style="display: flex; gap: var(--spacing-md);">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #1DA1F2; color: #fff; border-radius: var(--radius-lg); text-decoration: none; transition: all 0.3s ease; font-size: 20px; box-shadow: 0 4px 15px rgba(29, 161, 242, 0.3);">
                            𝕏
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #1877F2; color: #fff; border-radius: var(--radius-lg); text-decoration: none; transition: all 0.3s ease; font-size: 20px; box-shadow: 0 4px 15px rgba(24, 119, 242, 0.3);">
                            f
                        </a>
                        <a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #25D366; color: #fff; border-radius: var(--radius-lg); text-decoration: none; transition: all 0.3s ease; font-size: 20px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);">
                            💬
                        </a>
                        <a href="https://t.me/share/url?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #0088cc; color: #fff; border-radius: var(--radius-lg); text-decoration: none; transition: all 0.3s ease; font-size: 20px; box-shadow: 0 4px 15px rgba(0, 136, 204, 0.3);">
                            ✈️
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقة الكاتب المحسّنة -->
    <div class="author-bio" style="max-width: 900px; margin: 0 auto var(--spacing-4xl); padding: var(--spacing-3xl); background: linear-gradient(135deg, #2d5f4a 0%, #339063 100%); border-radius: var(--radius-3xl); box-shadow: 0 10px 40px rgba(51, 144, 99, 0.2); position: relative; overflow: hidden;">

        <!-- نمط خلفية -->
        <div style="position: absolute; inset: 0; opacity: 0.05; background-image: radial-gradient(circle, #fff 1.5px, transparent 1.5px); background-size: 25px 25px;"></div>

        <div style="position: relative; z-index: 1; display: grid; grid-template-columns: 140px 1fr; gap: var(--spacing-2xl); align-items: start;">

            <!-- صورة الكاتب -->
            <div style="text-align: center;">
                <div style="position: relative; display: inline-block;">
                    <div style="position: absolute; inset: -8px; background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1)); border-radius: 50%; filter: blur(15px);"></div>
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 140, '', '', array( 'style' => 'border-radius: 50%; border: 6px solid rgba(255,255,255,0.3); box-shadow: 0 8px 30px rgba(0,0,0,0.3); position: relative; z-index: 1;' ) ); ?>
                    <!-- بادج -->
                    <div style="position: absolute; bottom: 5px; right: 5px; width: 36px; height: 36px; background: linear-gradient(135deg, #4db080, #339063); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid rgba(255,255,255,0.3); box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 2;">
                        <span style="font-size: 18px;">✓</span>
                    </div>
                </div>
            </div>

            <!-- معلومات الكاتب -->
            <div>
                <div style="margin-bottom: var(--spacing-sm);">
                    <span style="display: inline-block; background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); padding: 6px 16px; border-radius: var(--radius-full); font-size: 12px; font-weight: 700; margin-bottom: var(--spacing-sm); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); text-transform: uppercase; letter-spacing: 0.5px;">
                        <?php esc_html_e( 'الكاتب', 'nadiim' ); ?>
                    </span>
                    <h3 style="font-size: clamp(1.5rem, 3vw, 2rem); margin: 0; font-weight: 900; color: #fff; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                        <?php echo esc_html( get_the_author() ); ?>
                    </h3>
                </div>

                <?php if ( get_the_author_meta( 'description' ) ) : ?>
                    <p style="color: rgba(255,255,255,0.95); line-height: 1.8; margin-bottom: var(--spacing-lg); font-size: 16px;">
                        <?php echo esc_html( get_the_author_meta( 'description' ) ); ?>
                    </p>
                <?php else : ?>
                    <p style="color: rgba(255,255,255,0.85); line-height: 1.8; margin-bottom: var(--spacing-lg); font-size: 16px; font-style: italic;">
                        <?php esc_html_e( 'كاتب ومساهم في منصة نديم الثقافية.', 'nadiim' ); ?>
                    </p>
                <?php endif; ?>

                <div style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                       style="display: inline-flex; align-items: center; gap: var(--spacing-sm); background: #fff; color: var(--color-primary); padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full); text-decoration: none; font-weight: 700; font-size: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); transition: all 0.3s ease; border: 2px solid transparent;">
                        <span><?php esc_html_e( 'جميع مقالات الكاتب', 'nadiim' ); ?></span>
                        <span style="transition: transform 0.3s ease;">←</span>
                    </a>

                    <?php if ( get_the_author_meta( 'user_url' ) ) : ?>
                        <a href="<?php echo esc_url( get_the_author_meta( 'user_url' ) ); ?>"
                           target="_blank"
                           rel="noopener"
                           style="display: inline-flex; align-items: center; gap: var(--spacing-sm); background: rgba(255,255,255,0.15); color: #fff; padding: var(--spacing-md) var(--spacing-lg); border-radius: var(--radius-full); text-decoration: none; font-weight: 600; font-size: 15px; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.2); transition: all 0.3s ease;">
                            🌐 <span><?php esc_html_e( 'الموقع الشخصي', 'nadiim' ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- قسم التعليقات -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
        <div class="comments-section" style="max-width: 900px; margin: 0 auto var(--spacing-3xl);">
            <?php comments_template(); ?>
        </div>
    <?php endif; ?>

</article>

<style>
/* تأثيرات التحويم */
.entry-categories a:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(51, 144, 99, 0.6);
}

.entry-tags a:hover {
    background: var(--color-primary);
    color: #fff;
    border-color: var(--color-primary);
    transform: translateY(-3px);
}

.entry-share a:hover {
    transform: translateY(-4px) scale(1.1);
}

.author-bio a:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}

.author-bio a:first-of-type:hover {
    background: var(--color-primary);
    color: #fff;
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
    border-radius: var(--radius-xl);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    margin: 3em 0;
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
@media (max-width: 768px) {
    .author-bio > div {
        grid-template-columns: 1fr;
        text-align: center;
        gap: var(--spacing-lg);
    }

    .entry-hero {
        min-height: 500px !important;
    }

    .entry-share > div {
        flex-direction: column;
        text-align: center;
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
