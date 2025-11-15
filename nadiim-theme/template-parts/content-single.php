<?php
/**
 * قالب عرض المقال المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-single' ); ?>>

    <!-- صورة مميزة كبيرة -->
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-featured-image-hero" style="margin: 0 calc(-1 * var(--spacing-lg)) var(--spacing-xl); border-radius: var(--radius-xl); overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); position: relative; max-height: 500px;">
            <?php the_post_thumbnail( 'full', array(
                'style' => 'width: 100%; height: 100%; object-fit: cover;',
                'class' => 'article-hero-image'
            ) ); ?>
            <!-- تدرج لوني للنص -->
            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%); padding: var(--spacing-xl);">
                <div style="max-width: 900px; margin: 0 auto;">
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) : ?>
                        <div class="entry-categories" style="margin-bottom: var(--spacing-sm);">
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                                   style="display: inline-block; background: var(--color-primary); color: #fff; padding: 6px 16px; border-radius: var(--radius-full); font-size: 14px; font-weight: 600; margin-left: 8px; text-decoration: none;">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <header class="entry-header" style="text-align: center; margin-bottom: var(--spacing-xl); <?php echo has_post_thumbnail() ? '' : 'padding-top: var(--spacing-lg);'; ?>">
        <?php
        // عرض التصنيفات إذا لم تكن هناك صورة مميزة
        if ( ! has_post_thumbnail() ) :
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
            <?php endif;
        endif; ?>

        <?php the_title( '<h1 class="entry-title" style="font-size: var(--font-size-3xl); line-height: 1.2; margin-bottom: var(--spacing-md); max-width: 800px; margin-left: auto; margin-right: auto;">', '</h1>' ); ?>

        <div class="entry-meta" style="display: flex; align-items: center; justify-content: center; gap: var(--spacing-md); flex-wrap: wrap; color: var(--color-text-secondary); font-size: var(--font-size-base); padding: var(--spacing-md) var(--spacing-lg); background: var(--color-bg-section); border-radius: var(--radius-full); display: inline-flex;">
            <div class="meta-author" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', array( 'style' => 'border-radius: 50%;' ) ); ?>
                <span><?php nadiim_posted_by(); ?></span>
            </div>
            <span class="meta-separator" style="color: var(--color-border);">•</span>
            <div class="meta-date">
                <?php echo nadiim_get_icon( 'calendar' ); ?>
                <?php nadiim_posted_on(); ?>
            </div>
            <span class="meta-separator" style="color: var(--color-border);">•</span>
            <div class="meta-reading-time">
                <?php echo nadiim_get_icon( 'clock' ); ?>
                <?php
                $word_count = str_word_count( strip_tags( get_the_content() ) );
                $reading_time = ceil( $word_count / 200 );
                printf( esc_html__( '%d دقائق قراءة', 'nadiim' ), $reading_time );
                ?>
            </div>
        </div>
    </header>

    <!-- المقتطف إذا كان موجوداً -->
    <?php if ( has_excerpt() ) : ?>
        <div class="entry-excerpt" style="font-size: var(--font-size-xl); line-height: 1.8; color: var(--color-text-secondary); text-align: center; max-width: 700px; margin: 0 auto var(--spacing-xl); padding: var(--spacing-lg); background: var(--color-bg-section); border-radius: var(--radius-lg); border-right: 4px solid var(--color-primary);">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content" style="font-size: var(--font-size-lg); line-height: 2; color: var(--color-text); max-width: 750px; margin: 0 auto;">
        <?php
        the_content( sprintf(
            wp_kses(
                __( 'تابع القراءة <span class="meta-nav">&larr;</span>', 'nadiim' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            the_title( '<span class="screen-reader-text">"', '"</span>', false )
        ) );

        wp_link_pages( array(
            'before' => '<div class="page-links" style="margin-top: var(--spacing-xl); padding: var(--spacing-md); background: var(--color-bg-section); border-radius: var(--radius-md);">' . esc_html__( 'الصفحات:', 'nadiim' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <!-- الوسوم -->
    <?php
    $tags = get_the_tags();
    if ( $tags ) : ?>
        <div class="entry-tags" style="margin-top: var(--spacing-xl); padding-top: var(--spacing-lg); border-top: 2px solid var(--color-border); text-align: center;">
            <div style="display: flex; align-items: center; justify-content: center; gap: var(--spacing-sm); flex-wrap: wrap;">
                <span style="font-weight: 600; color: var(--color-text-secondary);">
                    <?php echo nadiim_get_icon( 'tag' ); ?>
                    <?php esc_html_e( 'الوسوم:', 'nadiim' ); ?>
                </span>
                <?php foreach ( $tags as $tag ) : ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                       style="display: inline-block; padding: 8px 16px; background: var(--color-bg-section); border: 1px solid var(--color-border); border-radius: var(--radius-full); font-size: 14px; color: var(--color-text); text-decoration: none; transition: all 0.3s ease;">
                        <?php echo esc_html( $tag->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- معلومات الكاتب -->
    <div class="author-bio" style="margin-top: var(--spacing-xl); padding: var(--spacing-xl); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); border-radius: var(--radius-xl); border-right: 4px solid var(--color-primary);">
        <div style="display: grid; grid-template-columns: 100px 1fr; gap: var(--spacing-lg); align-items: start;">
            <div>
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 100, '', '', array( 'style' => 'border-radius: 50%; border: 4px solid #fff; box-shadow: var(--shadow-medium);' ) ); ?>
            </div>
            <div>
                <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-xs);">
                    <?php echo esc_html( get_the_author() ); ?>
                </h3>
                <?php if ( get_the_author_meta( 'description' ) ) : ?>
                    <p style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-md);">
                        <?php echo esc_html( get_the_author_meta( 'description' ) ); ?>
                    </p>
                <?php endif; ?>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                   class="btn btn-sm btn-outline">
                    <?php esc_html_e( 'جميع مقالات الكاتب', 'nadiim' ); ?>
                </a>
            </div>
        </div>
    </div>

</article>
