<?php
/**
 * قالب صفحة الأرشيف - المدونة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main archive-page">
    <div class="container">

        <!-- عنوان الأرشيف -->
        <header class="page-header" style="text-align: center; padding: var(--spacing-xxl) 0 var(--spacing-xl); background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); margin: 0 calc(-1 * var(--spacing-md)) var(--spacing-xl); border-radius: var(--radius-xl);">
            <?php
            the_archive_title( '<h1 class="page-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-sm);">', '</h1>' );
            the_archive_description( '<div class="archive-description" style="color: var(--color-text-secondary); font-size: var(--font-size-lg); max-width: 600px; margin: 0 auto; line-height: 1.7;">', '</div>' );
            ?>

            <?php if ( is_category() || is_tag() ) : ?>
                <div class="archive-meta" style="margin-top: var(--spacing-md); color: var(--color-text-secondary);">
                    <?php
                    $count = $wp_query->found_posts;
                    printf(
                        esc_html( _n( '%s مقال', '%s مقالات', $count, 'nadiim' ) ),
                        number_format_i18n( $count )
                    );
                    ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="archive-content">
                <div class="posts-grid grid grid-3" style="gap: var(--spacing-xl);">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article <?php post_class( 'card post-card' ); ?> style="border-radius: var(--radius-lg); overflow: hidden; background: var(--color-bg-lighter); box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="display: block; position: relative; overflow: hidden; aspect-ratio: 16/10;">
                                    <?php the_post_thumbnail( 'nadiim-card', array(
                                        'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;',
                                        'class' => 'card-image-hover'
                                    ) ); ?>
                                    <!-- تدرج لوني -->
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.3) 100%);"></div>
                                </a>
                            <?php endif; ?>

                            <div class="card-content" style="padding: var(--spacing-lg);">
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) : ?>
                                    <div style="margin-bottom: var(--spacing-sm);">
                                        <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
                                           style="display: inline-block; background: var(--color-primary); color: #fff; padding: 4px 12px; border-radius: var(--radius-full); font-size: 12px; font-weight: 600; text-decoration: none;">
                                            <?php echo esc_html( $categories[0]->name ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <h2 class="card-title" style="margin-bottom: var(--spacing-sm);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-xl); line-height: 1.3; display: block; transition: color 0.3s ease;">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="card-meta" style="display: flex; gap: var(--spacing-sm); align-items: center; color: var(--color-text-secondary); font-size: 14px; margin-bottom: var(--spacing-sm);">
                                    <span><?php echo get_the_date(); ?></span>
                                    <span>•</span>
                                    <span><?php echo nadiim_get_icon( 'user' ); ?> <?php the_author(); ?></span>
                                </div>

                                <div class="card-excerpt" style="color: var(--color-text-secondary); line-height: 1.6; margin-bottom: var(--spacing-md);">
                                    <?php echo nadiim_get_excerpt( 20 ); ?>
                                </div>

                                <?php echo nadiim_read_more_link( __( 'قراءة المزيد', 'nadiim' ) ); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                // الترقيم
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '&rarr; السابق', 'nadiim' ),
                    'next_text' => __( 'التالي &larr;', 'nadiim' ),
                    'before_page_number' => '<span class="screen-reader-text">' . __( 'صفحة', 'nadiim' ) . ' </span>',
                ) );
                ?>
            </div>

        <?php else : ?>

            <div class="no-results" style="text-align: center; padding: var(--spacing-xxl); background: var(--color-bg-section); border-radius: var(--radius-xl);">
                <span style="font-size: 64px; display: block; margin-bottom: var(--spacing-lg);">📄</span>
                <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-md);">
                    <?php esc_html_e( 'لا توجد مقالات', 'nadiim' ); ?>
                </h2>
                <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg); margin-bottom: var(--spacing-lg);">
                    <?php esc_html_e( 'لم يتم العثور على أي مقالات في هذا القسم.', 'nadiim' ); ?>
                </p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <?php esc_html_e( 'العودة للرئيسية', 'nadiim' ); ?>
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
