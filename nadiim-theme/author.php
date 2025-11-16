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

    <!-- Hero Section مع تصميم فاخر -->
    <div class="author-hero" style="background: linear-gradient(135deg, #2d5f4a 0%, #339063 50%, #4db080 100%); padding: var(--spacing-4xl) 0; margin-bottom: var(--spacing-4xl); position: relative; overflow: hidden;">

        <!-- خلفية متحركة -->
        <div style="position: absolute; inset: 0; opacity: 0.08;">
            <div style="position: absolute; width: 500px; height: 500px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); top: -250px; right: -250px; border-radius: 50%;"></div>
            <div style="position: absolute; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); bottom: -200px; left: -200px; border-radius: 50%;"></div>
        </div>

        <!-- نمط نقاط -->
        <div style="position: absolute; inset: 0; opacity: 0.05; background-image: radial-gradient(circle, #fff 1.5px, transparent 1.5px); background-size: 30px 30px;"></div>

        <div class="container" style="position: relative; z-index: 1;">
            <div class="author-profile" style="max-width: 900px; margin: 0 auto;">

                <!-- بطاقة المعلومات الرئيسية -->
                <div style="text-align: center; margin-bottom: var(--spacing-2xl);">

                    <!-- الصورة الشخصية -->
                    <div style="position: relative; display: inline-block; margin-bottom: var(--spacing-xl);">
                        <div style="position: absolute; inset: -10px; background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1)); border-radius: 50%; filter: blur(20px);"></div>
                        <?php echo get_avatar( $author->ID, 180, '', '', array(
                            'style' => 'border-radius: 50%; border: 8px solid rgba(255,255,255,0.2); box-shadow: 0 15px 50px rgba(0,0,0,0.4); position: relative; z-index: 1;'
                        ) ); ?>
                        <!-- بادج التحقق (اختياري) -->
                        <div style="position: absolute; bottom: 10px; right: 10px; width: 40px; height: 40px; background: linear-gradient(135deg, #4db080, #339063); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid rgba(255,255,255,0.3); box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 2;">
                            <span style="font-size: 20px;">✓</span>
                        </div>
                    </div>

                    <!-- الاسم -->
                    <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: var(--spacing-md); color: #fff; font-weight: 900; text-shadow: 0 4px 20px rgba(0,0,0,0.3); letter-spacing: -0.02em;">
                        <?php echo esc_html( $author->display_name ); ?>
                    </h1>

                    <!-- الوصف -->
                    <?php if ( $author->description ) : ?>
                        <div style="max-width: 700px; margin: 0 auto var(--spacing-xl); color: rgba(255,255,255,0.95); font-size: clamp(1rem, 2vw, 1.25rem); line-height: 1.8; font-weight: 400;">
                            <?php echo wpautop( esc_html( $author->description ) ); ?>
                        </div>
                    <?php else : ?>
                        <p style="color: rgba(255,255,255,0.85); font-size: var(--font-size-xl); margin-bottom: var(--spacing-xl); font-style: italic;">
                            ✍️ <?php esc_html_e( 'كاتب ومساهم في منصة نديم', 'nadiim' ); ?>
                        </p>
                    <?php endif; ?>

                    <!-- الإحصائيات -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: var(--spacing-lg); max-width: 600px; margin: 0 auto var(--spacing-xl);">
                        <?php
                        $posts_count = count_user_posts( $author->ID, 'post' );
                        $howarat_count = count_user_posts( $author->ID, 'howarat' );
                        $esdar_count = count_user_posts( $author->ID, 'esdar' );
                        $total = $posts_count + $howarat_count + $esdar_count;

                        $stats = array();
                        if ( $total > 0 ) {
                            $stats[] = array( 'icon' => '📊', 'count' => $total, 'label' => 'إجمالي المساهمات' );
                        }
                        if ( $posts_count > 0 ) {
                            $stats[] = array( 'icon' => '📝', 'count' => $posts_count, 'label' => 'مقالة' );
                        }
                        if ( $howarat_count > 0 ) {
                            $stats[] = array( 'icon' => '🎙️', 'count' => $howarat_count, 'label' => 'حوار' );
                        }
                        if ( $esdar_count > 0 ) {
                            $stats[] = array( 'icon' => '📚', 'count' => $esdar_count, 'label' => 'إصدار' );
                        }

                        foreach ( $stats as $stat ) : ?>
                            <div class="stat-card" style="background: rgba(255,255,255,0.12); padding: var(--spacing-lg); border-radius: var(--radius-xl); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.15); transition: all 0.3s ease; cursor: default;">
                                <div style="font-size: 2rem; margin-bottom: var(--spacing-xs);"><?php echo $stat['icon']; ?></div>
                                <div style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: var(--spacing-xs); line-height: 1;">
                                    <?php echo number_format_i18n( $stat['count'] ); ?>
                                </div>
                                <div style="color: rgba(255,255,255,0.85); font-size: 14px; font-weight: 600;">
                                    <?php echo esc_html( $stat['label'] ); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- روابط إضافية -->
                    <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                        <?php if ( $author->user_url ) : ?>
                            <a href="<?php echo esc_url( $author->user_url ); ?>"
                               target="_blank"
                               rel="noopener"
                               class="author-link"
                               style="display: inline-flex; align-items: center; gap: var(--spacing-sm); background: rgba(255,255,255,0.15); color: #fff; padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full); text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.2); transition: all 0.3s ease; font-size: 15px;">
                                🌐 <span><?php esc_html_e( 'الموقع الشخصي', 'nadiim' ); ?></span>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>#content"
                           class="author-link"
                           style="display: inline-flex; align-items: center; gap: var(--spacing-sm); background: #fff; color: var(--color-primary); padding: var(--spacing-md) var(--spacing-xl); border-radius: var(--radius-full); text-decoration: none; font-weight: 700; border: 2px solid transparent; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.2); font-size: 15px;">
                            <span><?php esc_html_e( 'عرض المساهمات', 'nadiim' ); ?></span>
                            <span style="transition: transform 0.3s ease;">↓</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="content">
        <?php
        $posts_query = new WP_Query( array(
            'author'         => $author->ID,
            'post_type'      => 'post',
            'posts_per_page' => 12,
            'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
        ) );

        if ( $posts_query->have_posts() ) : ?>
            <div class="author-content-section" style="margin-bottom: var(--spacing-4xl);">

                <!-- عنوان القسم -->
                <div style="text-align: center; margin-bottom: var(--spacing-3xl);">
                    <div style="display: inline-block; position: relative; padding-bottom: var(--spacing-md);">
                        <h2 style="font-size: clamp(1.75rem, 3vw, 2.5rem); font-weight: 900; margin: 0; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            📝 <?php esc_html_e( 'المقالات المنشورة', 'nadiim' ); ?>
                        </h2>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, transparent, var(--color-primary), transparent); border-radius: var(--radius-full); opacity: 0.3;"></div>
                    </div>
                    <p style="color: var(--color-text-secondary); margin-top: var(--spacing-md); font-size: var(--font-size-lg);">
                        <?php printf( esc_html__( '%s مقالة منشورة', 'nadiim' ), number_format_i18n( $posts_count ) ); ?>
                    </p>
                </div>

                <!-- شبكة المقالات -->
                <div class="posts-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--spacing-2xl); margin-bottom: var(--spacing-3xl);">
                    <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                        <article <?php post_class( 'post-card' ); ?> style="border-radius: var(--radius-2xl); overflow: hidden; background: var(--color-bg-lighter); box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; height: 100%; border: 1px solid var(--color-border);">

                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="display: block; position: relative; overflow: hidden; aspect-ratio: 16/9; flex-shrink: 0;">
                                    <?php the_post_thumbnail( 'nadiim-card', array(
                                        'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);',
                                        'class' => 'post-image'
                                    ) ); ?>

                                    <!-- تدرج -->
                                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.5) 100%); opacity: 0; transition: opacity 0.3s ease;" class="image-overlay"></div>

                                    <!-- التصنيف -->
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) : ?>
                                        <span style="position: absolute; top: var(--spacing-md); left: var(--spacing-md); background: var(--color-primary); color: #fff; padding: 8px 18px; border-radius: var(--radius-full); font-size: 12px; font-weight: 700; box-shadow: 0 4px 15px rgba(51, 144, 99, 0.4); letter-spacing: 0.5px; text-transform: uppercase;">
                                            <?php echo esc_html( $categories[0]->name ); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php else : ?>
                                <div style="aspect-ratio: 16/9; background: linear-gradient(135deg, #e0e7e9 0%, #f8f9fa 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                    <div style="position: absolute; inset: 0; opacity: 0.05; background-image: radial-gradient(circle, #333 1px, transparent 1px); background-size: 20px 20px;"></div>
                                    <span style="font-size: 64px; opacity: 0.3; position: relative; z-index: 1;">📝</span>
                                </div>
                            <?php endif; ?>

                            <div style="padding: var(--spacing-xl); display: flex; flex-direction: column; flex-grow: 1;">

                                <!-- العنوان -->
                                <h3 style="margin-bottom: var(--spacing-md);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: clamp(1.1rem, 2vw, 1.35rem); line-height: 1.3; font-weight: 800; display: block; transition: color 0.3s ease;">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <!-- المعلومات -->
                                <div style="display: flex; flex-wrap: wrap; gap: var(--spacing-sm) var(--spacing-md); align-items: center; color: var(--color-text-secondary); font-size: 13px; margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-md); border-bottom: 2px solid var(--color-bg-section);">
                                    <span style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                                        📅 <?php echo get_the_date( 'j F، Y' ); ?>
                                    </span>
                                    <?php
                                    $word_count = str_word_count( strip_tags( get_the_content() ) );
                                    $reading_time = ceil( $word_count / 200 );
                                    if ( $reading_time > 0 ) :
                                    ?>
                                        <span>•</span>
                                        <span style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                                            ⏱️ <?php printf( esc_html__( '%d دقائق', 'nadiim' ), $reading_time ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- المقتطف -->
                                <div style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-lg); flex-grow: 1; font-size: 15px;">
                                    <?php echo nadiim_get_excerpt( 22 ); ?>
                                </div>

                                <!-- رابط القراءة -->
                                <a href="<?php the_permalink(); ?>" class="read-more" style="display: inline-flex; align-items: center; gap: var(--spacing-sm); color: var(--color-primary); font-weight: 700; text-decoration: none; font-size: 15px; transition: all 0.3s ease; margin-top: auto; align-self: flex-start;">
                                    <span><?php esc_html_e( 'قراءة المقال', 'nadiim' ); ?></span>
                                    <span class="arrow" style="transition: transform 0.3s ease; font-size: 18px;">←</span>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- الترقيم -->
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<span style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 18px;">→</span>' . __( 'السابق', 'nadiim' ) . '</span>',
                    'next_text' => '<span style="display: inline-flex; align-items: center; gap: 8px;">' . __( 'التالي', 'nadiim' ) . '<span style="font-size: 18px;">←</span></span>',
                ) );
                ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: var(--spacing-4xl) var(--spacing-xl); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); border-radius: var(--radius-2xl); box-shadow: 0 5px 25px rgba(0,0,0,0.05); max-width: 600px; margin: var(--spacing-4xl) auto; border: 2px dashed var(--color-border);">
                <span style="font-size: 96px; display: block; margin-bottom: var(--spacing-lg); opacity: 0.5;">📝</span>
                <h3 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-md); font-weight: 800;">
                    <?php esc_html_e( 'لا توجد مقالات حتى الآن', 'nadiim' ); ?>
                </h3>
                <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg); line-height: 1.7;">
                    <?php esc_html_e( 'لم ينشر هذا الكاتب أي مقالات حالياً. تابع لاحقاً للاطلاع على مساهماته.', 'nadiim' ); ?>
                </p>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</main>

<style>
/* تأثيرات البطاقات */
.post-card {
    position: relative;
}

.post-card::before {
    content: '';
    position: absolute;
    inset: -2px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
    border-radius: var(--radius-2xl);
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: -1;
}

.post-card:hover::before {
    opacity: 0.1;
}

.post-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    border-color: transparent;
}

.post-card:hover .post-image {
    transform: scale(1.08);
}

.post-card:hover .image-overlay {
    opacity: 1;
}

.post-card:hover h3 a {
    color: var(--color-primary);
}

.post-card:hover .read-more {
    gap: var(--spacing-md);
}

.post-card:hover .read-more .arrow {
    transform: translateX(-6px);
}

/* تأثيرات الإحصائيات */
.stat-card:hover {
    transform: translateY(-8px) scale(1.05);
    background: rgba(255,255,255,0.2) !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

/* تأثيرات الروابط */
.author-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    background: rgba(255,255,255,0.25) !important;
}

.author-link:hover span:last-child {
    transform: translateY(4px);
}

/* الخلفية المتحركة */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.author-hero > div:first-child > div {
    animation: float 6s ease-in-out infinite;
}

.author-hero > div:first-child > div:last-child {
    animation-delay: 3s;
}

/* تجاوب */
@media (max-width: 768px) {
    .posts-grid {
        grid-template-columns: 1fr;
    }

    .author-hero {
        padding: var(--spacing-3xl) 0;
    }
}
</style>

<?php
get_footer();
