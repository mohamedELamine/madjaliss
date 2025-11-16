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
    $release_pdf_embed = get_post_meta( get_the_ID(), 'release_pdf_embed', true );
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'release-single' ); ?>>
        <div class="container container-narrow">

            <!-- قسم معلومات الكتاب -->
            <div class="release-hero" style="display: grid; grid-template-columns: 280px 1fr; gap: var(--spacing-xl); margin-bottom: var(--spacing-xl); padding: var(--spacing-xl); background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: var(--radius-xl); align-items: start;">
                <div class="release-cover" style="position: sticky; top: 20px;">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium_large', array(
                            'style' => 'width: 100%; border-radius: var(--radius-lg); box-shadow: 0 10px 40px rgba(0,0,0,0.15); transition: transform 0.3s ease;',
                            'class' => 'release-cover-image'
                        ) ); ?>
                    <?php else : ?>
                        <div style="width: 100%; aspect-ratio: 2/3; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                            <span style="color: #fff; font-size: var(--font-size-3xl);">📖</span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $release_pdf_url ) : ?>
                        <div style="margin-top: var(--spacing-md);">
                            <a href="<?php echo esc_url( $release_pdf_url ); ?>" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: var(--font-size-lg); padding: 14px 20px;" target="_blank" download>
                                <?php echo nadiim_get_icon( 'download' ); ?>
                                <?php esc_html_e( 'تحميل الكتاب', 'nadiim' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="release-info">
                    <h1 class="entry-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-sm); line-height: 1.2;">
                        <?php the_title(); ?>
                    </h1>

                    <?php if ( $release_author ) : ?>
                        <p class="release-author" style="font-size: var(--font-size-xl); color: var(--color-primary); margin-bottom: var(--spacing-lg); font-weight: 600;">
                            <?php echo nadiim_get_icon( 'user' ); ?>
                            <?php echo esc_html( $release_author ); ?>
                        </p>
                    <?php endif; ?>

                    <div class="release-meta" style="background: rgba(255,255,255,0.8); padding: var(--spacing-lg); border-radius: var(--radius-lg); margin-bottom: var(--spacing-md); border-right: 4px solid var(--color-primary); backdrop-filter: blur(10px);">
                        <h3 style="font-size: var(--font-size-lg); margin-bottom: var(--spacing-md); color: var(--color-primary);">
                            <?php esc_html_e( 'معلومات الكتاب', 'nadiim' ); ?>
                        </h3>
                        <div style="display: grid; gap: var(--spacing-sm);">
                            <?php if ( $release_publisher ) : ?>
                                <p style="display: flex; gap: var(--spacing-xs);"><strong style="min-width: 100px;"><?php esc_html_e( 'الناشر:', 'nadiim' ); ?></strong> <span><?php echo esc_html( $release_publisher ); ?></span></p>
                            <?php endif; ?>
                            <?php if ( $release_date ) : ?>
                                <p style="display: flex; gap: var(--spacing-xs);"><strong style="min-width: 100px;"><?php esc_html_e( 'سنة النشر:', 'nadiim' ); ?></strong> <span><?php echo esc_html( date_i18n( 'Y', strtotime( $release_date ) ) ); ?></span></p>
                            <?php endif; ?>
                            <?php if ( $release_pages ) : ?>
                                <p style="display: flex; gap: var(--spacing-xs);"><strong style="min-width: 100px;"><?php esc_html_e( 'الصفحات:', 'nadiim' ); ?></strong> <span><?php echo esc_html( $release_pages ); ?> <?php esc_html_e( 'صفحة', 'nadiim' ); ?></span></p>
                            <?php endif; ?>
                            <?php if ( $release_isbn ) : ?>
                                <p style="display: flex; gap: var(--spacing-xs);"><strong style="min-width: 100px;">ISBN:</strong> <span style="direction: ltr; text-align: left;"><?php echo esc_html( $release_isbn ); ?></span></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ( has_excerpt() ) : ?>
                        <div class="release-excerpt" style="padding: var(--spacing-md); background: rgba(255,255,255,0.6); border-radius: var(--radius-md); font-size: var(--font-size-lg); line-height: 1.8; color: var(--color-text-secondary); border-right: 3px solid var(--color-secondary);">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- قسم القراءة عبر الإنترنت -->
            <?php if ( $release_pdf_url || $release_pdf_embed ) : ?>
                <div class="release-reader" style="margin-bottom: var(--spacing-xl);">
                    <div class="section-header" style="text-align: center; margin-bottom: var(--spacing-lg);">
                        <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-sm);">
                            <?php esc_html_e( 'قراءة الكتاب', 'nadiim' ); ?>
                        </h2>
                        <p style="color: var(--color-text-secondary);">
                            <?php esc_html_e( 'يمكنك قراءة الكتاب مباشرة عبر المتصفح أو تحميله', 'nadiim' ); ?>
                        </p>
                    </div>

                    <div class="pdf-viewer" style="background: var(--color-bg-section); padding: var(--spacing-sm); border-radius: var(--radius-lg); box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <?php if ( $release_pdf_embed ) : ?>
                            <?php echo wp_kses_post( $release_pdf_embed ); ?>
                        <?php elseif ( $release_pdf_url ) : ?>
                            <iframe src="<?php echo esc_url( $release_pdf_url ); ?>"
                                    style="width: 100%; height: 800px; border: none; border-radius: var(--radius-md);"
                                    allowfullscreen>
                            </iframe>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- محتوى الوصف التفصيلي -->
            <?php if ( get_the_content() ) : ?>
                <div class="entry-content" style="background: var(--color-bg-section); padding: var(--spacing-xl); border-radius: var(--radius-lg); margin-bottom: var(--spacing-xl); line-height: 2;">
                    <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-lg); text-align: center;">
                        <?php esc_html_e( 'نبذة عن الكتاب', 'nadiim' ); ?>
                    </h2>
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>

            <?php
            // عرض المشاركين إذا كانت مفعلة
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
