<?php
/**
 * قالب أرشيف الإصدارات
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="page-hero" style="background-color: var(--color-bg-section); padding: var(--spacing-xl) 0;">
        <div class="container">
            <header class="page-header text-center">
                <h1 class="page-title"><?php esc_html_e( 'أرشيف الإصدارات', 'nadiim' ); ?></h1>
                <p class="archive-description"><?php esc_html_e( 'مكتبةٌ هادئة تضمّ إصداراتنا المتنوّعة من الكتب والنشرات والبحوث', 'nadiim' ); ?></p>
            </header>
        </div>
    </div>

    <div class="container section">
        <?php if ( have_posts() ) : ?>
            <div class="esdar-grid grid grid-4">
                <?php while ( have_posts() ) : the_post();
                    $release_author = get_post_meta( get_the_ID(), 'release_author', true );
                    $release_date = get_post_meta( get_the_ID(), 'release_date', true );
                    ?>
                    <article <?php post_class( 'card release-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php if ( $release_author ) : ?>
                                <p class="release-author" style="color: var(--color-text-secondary); margin-bottom: var(--spacing-sm);">
                                    <?php echo esc_html( $release_author ); ?>
                                </p>
                            <?php endif; ?>
                            <?php echo nadiim_read_more_link( __( 'عرض التفاصيل', 'nadiim' ) ); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'لا توجد إصدارات حالياً', 'nadiim' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
