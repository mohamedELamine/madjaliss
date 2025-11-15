<?php
/**
 * قالب الإصدار المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) : the_post();
    $release_author = get_post_meta( get_the_ID(), 'release_author', true );
    $release_date = get_post_meta( get_the_ID(), 'release_date', true );
    $release_isbn = get_post_meta( get_the_ID(), 'release_isbn', true );
    $release_pages = get_post_meta( get_the_ID(), 'release_pages', true );
    $release_publisher = get_post_meta( get_the_ID(), 'release_publisher', true );
    $release_pdf_url = get_post_meta( get_the_ID(), 'release_pdf_url', true );
    $release_purchase_url = get_post_meta( get_the_ID(), 'release_purchase_url', true );
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container container-narrow">
            <div class="release-hero" style="display: grid; grid-template-columns: 300px 1fr; gap: var(--spacing-xl); margin-bottom: var(--spacing-xl); align-items: start;">
                <div class="release-cover">
                    <?php the_post_thumbnail( 'medium_large', array( 'style' => 'width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-medium);' ) ); ?>
                </div>

                <div class="release-info">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php if ( $release_author ) : ?>
                        <p class="release-author" style="font-size: var(--font-size-xl); color: var(--color-text-secondary); margin-bottom: var(--spacing-md);">
                            <?php echo esc_html( $release_author ); ?>
                        </p>
                    <?php endif; ?>

                    <div class="release-meta" style="background: var(--color-bg-section); padding: var(--spacing-md); border-radius: var(--radius-md); margin-bottom: var(--spacing-md);">
                        <?php if ( $release_publisher ) : ?>
                            <p><strong><?php esc_html_e( 'الناشر:', 'nadiim' ); ?></strong> <?php echo esc_html( $release_publisher ); ?></p>
                        <?php endif; ?>
                        <?php if ( $release_date ) : ?>
                            <p><strong><?php esc_html_e( 'تاريخ النشر:', 'nadiim' ); ?></strong> <?php echo esc_html( date_i18n( 'Y', strtotime( $release_date ) ) ); ?></p>
                        <?php endif; ?>
                        <?php if ( $release_pages ) : ?>
                            <p><strong><?php esc_html_e( 'عدد الصفحات:', 'nadiim' ); ?></strong> <?php echo esc_html( $release_pages ); ?></p>
                        <?php endif; ?>
                        <?php if ( $release_isbn ) : ?>
                            <p><strong><?php esc_html_e( 'ISBN:', 'nadiim' ); ?></strong> <?php echo esc_html( $release_isbn ); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="release-actions" style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                        <?php if ( $release_pdf_url ) : ?>
                            <a href="<?php echo esc_url( $release_pdf_url ); ?>" class="btn btn-primary" target="_blank">
                                <?php echo nadiim_get_icon( 'download' ); ?>
                                <?php esc_html_e( 'تحميل PDF', 'nadiim' ); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ( $release_purchase_url ) : ?>
                            <a href="<?php echo esc_url( $release_purchase_url ); ?>" class="btn btn-outline">
                                <?php esc_html_e( 'الحصول على نسخة', 'nadiim' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <?php
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>
        </div>
    </article>

<?php
endwhile;
get_footer();
?>
