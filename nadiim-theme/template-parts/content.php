<?php
/**
 * قالب عرض المقال في صفحات الأرشيف
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card post-card' ); ?> style="border-radius: var(--radius-xl); overflow: hidden; background: var(--color-bg-lighter); box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: all 0.4s ease; display: flex; flex-direction: column; height: 100%;">

    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" style="display: block; position: relative; overflow: hidden; aspect-ratio: 16/9; flex-shrink: 0;">
            <?php the_post_thumbnail( 'nadiim-card', array(
                'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;',
                'class' => 'card-image-hover'
            ) ); ?>

            <!-- تدرج لوني مع بادج التصنيف -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 50%, rgba(0,0,0,0.2) 100%);"></div>

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

        <h3 class="card-title" style="margin-bottom: var(--spacing-md);">
            <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-xl); line-height: 1.4; display: block; transition: color 0.3s ease; font-weight: 700;">
                <?php the_title(); ?>
            </a>
        </h3>

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
                    ⏱️ <?php printf( esc_html__( '%d دقائق', 'nadiim' ), $reading_time ); ?>
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

<style>
/* تأثيرات البطاقة */
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
</style>
