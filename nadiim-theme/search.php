<?php
/**
 * قالب صفحة البحث
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main search-page">
    <div class="container">

        <!-- عنوان البحث -->
        <header class="page-header" style="text-align: center; padding: var(--spacing-xxl) 0 var(--spacing-xl); background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); margin: 0 calc(-1 * var(--spacing-md)) var(--spacing-xl); border-radius: var(--radius-xl); color: #fff; position: relative; overflow: hidden;">
            <!-- خلفية زخرفية -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'20\' cy=\'20\' r=\'2\' fill=\'%23fff\'/%3E%3C/svg%3E');"></div>

            <div style="position: relative; z-index: 1;">
                <div style="font-size: 48px; margin-bottom: var(--spacing-md);">🔍</div>
                <h1 class="page-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md); color: #fff;">
                    <?php
                    printf(
                        esc_html__( 'نتائج البحث عن: %s', 'nadiim' ),
                        '<span style="font-weight: 700;">' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>

                <?php if ( have_posts() ) : ?>
                    <p style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                        <?php
                        printf(
                            esc_html( _n( 'تم العثور على نتيجة واحدة', 'تم العثور على %s نتيجة', $wp_query->found_posts, 'nadiim' ) ),
                            number_format_i18n( $wp_query->found_posts )
                        );
                        ?>
                    </p>
                <?php endif; ?>

                <!-- نموذج بحث جديد -->
                <form role="search" method="get" class="search-form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width: 600px; margin: var(--spacing-lg) auto 0; display: flex; gap: var(--spacing-sm);">
                    <input type="search"
                           class="search-field"
                           placeholder="<?php esc_attr_e( 'ابحث مرة أخرى...', 'nadiim' ); ?>"
                           value="<?php echo get_search_query(); ?>"
                           name="s"
                           style="flex: 1; padding: 14px 20px; border: 2px solid rgba(255,255,255,0.3); border-radius: var(--radius-md); background: rgba(255,255,255,0.9); font-size: var(--font-size-base);" />
                    <button type="submit" class="btn" style="background: #fff; color: var(--color-primary); border: none; padding: 14px 28px; font-weight: 600;">
                        <?php esc_html_e( 'بحث', 'nadiim' ); ?>
                    </button>
                </form>
            </div>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="search-results">
                <div class="posts-list" style="display: grid; gap: var(--spacing-lg);">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article <?php post_class( 'search-result-item' ); ?> style="display: grid; grid-template-columns: 200px 1fr; gap: var(--spacing-lg); padding: var(--spacing-lg); background: var(--color-bg-section); border-radius: var(--radius-lg); border-right: 4px solid var(--color-primary); transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="display: block; border-radius: var(--radius-md); overflow: hidden; height: 140px;">
                                    <?php the_post_thumbnail( 'medium', array(
                                        'style' => 'width: 100%; height: 100%; object-fit: cover;',
                                    ) ); ?>
                                </a>
                            <?php else : ?>
                                <div style="width: 200px; height: 140px; background: linear-gradient(135deg, var(--color-bg-lighter), var(--color-border)); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 48px;">
                                    <?php
                                    $post_type = get_post_type();
                                    if ( $post_type === 'esdar' ) {
                                        echo '📖';
                                    } elseif ( $post_type === 'howarat' ) {
                                        echo '🎙️';
                                    } elseif ( $post_type === 'reading_clubs' ) {
                                        echo '📚';
                                    } else {
                                        echo '📄';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div>
                                <!-- نوع المنشور -->
                                <div style="margin-bottom: var(--spacing-xs);">
                                    <span style="display: inline-block; background: var(--color-primary); color: #fff; padding: 4px 12px; border-radius: var(--radius-full); font-size: 12px; font-weight: 600;">
                                        <?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
                                    </span>
                                </div>

                                <h2 style="margin-bottom: var(--spacing-sm);">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; font-size: var(--font-size-xl); line-height: 1.3; transition: color 0.3s ease;">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="entry-meta" style="display: flex; gap: var(--spacing-sm); align-items: center; color: var(--color-text-secondary); font-size: 14px; margin-bottom: var(--spacing-sm);">
                                    <span><?php echo get_the_date(); ?></span>
                                    <span>•</span>
                                    <span><?php the_author(); ?></span>
                                </div>

                                <div style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-md);">
                                    <?php echo nadiim_get_excerpt( 25 ); ?>
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
                ) );
                ?>
            </div>

        <?php else : ?>

            <div class="no-results" style="text-align: center; padding: var(--spacing-xxl); background: var(--color-bg-section); border-radius: var(--radius-xl);">
                <span style="font-size: 64px; display: block; margin-bottom: var(--spacing-lg);">😕</span>
                <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-md);">
                    <?php esc_html_e( 'لم يتم العثور على نتائج', 'nadiim' ); ?>
                </h2>
                <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg); margin-bottom: var(--spacing-lg); max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.7;">
                    <?php esc_html_e( 'عذراً، لم نتمكن من العثور على أي محتوى يطابق بحثك. جرب استخدام كلمات مفتاحية مختلفة أو تصفح الأقسام الأخرى.', 'nadiim' ); ?>
                </p>

                <div style="display: flex; gap: var(--spacing-sm); justify-content: center; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'العودة للرئيسية', 'nadiim' ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'howarat' ) ); ?>" class="btn btn-outline">
                        <?php esc_html_e( 'تصفح الحوارات', 'nadiim' ); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
