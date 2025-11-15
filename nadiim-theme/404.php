<?php
/**
 * قالب صفحة 404 - الصفحة غير موجودة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main error-404-page">
    <div class="container">
        <div class="error-404" style="text-align: center; padding: var(--spacing-xxl) 0; max-width: 700px; margin: 0 auto;">

            <!-- رقم الخطأ -->
            <div style="position: relative; margin-bottom: var(--spacing-xl);">
                <h1 style="font-size: 180px; font-weight: 900; color: var(--color-primary); opacity: 0.1; margin: 0; line-height: 1;">
                    404
                </h1>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 80px;">
                    😕
                </div>
            </div>

            <!-- الرسالة -->
            <div style="margin-bottom: var(--spacing-xl);">
                <h2 style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md); color: var(--color-text);">
                    <?php esc_html_e( 'عذراً، الصفحة غير موجودة!', 'nadiim' ); ?>
                </h2>
                <p style="font-size: var(--font-size-xl); color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-lg);">
                    <?php esc_html_e( 'يبدو أن الصفحة التي تبحث عنها قد تم نقلها أو حذفها، أو ربما لم تكن موجودة من الأساس.', 'nadiim' ); ?>
                </p>
            </div>

            <!-- نموذج البحث -->
            <div style="margin-bottom: var(--spacing-xl); padding: var(--spacing-xl); background: var(--color-bg-section); border-radius: var(--radius-xl); border: 2px dashed var(--color-border);">
                <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-md);">
                    <?php esc_html_e( 'جرب البحث عما تريد', 'nadiim' ); ?>
                </h3>
                <form role="search" method="get" class="search-form-404" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width: 500px; margin: 0 auto; display: flex; gap: var(--spacing-sm);">
                    <input type="search"
                           class="search-field"
                           placeholder="<?php esc_attr_e( 'ابحث هنا...', 'nadiim' ); ?>"
                           value=""
                           name="s"
                           style="flex: 1; padding: 14px 20px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base);" />
                    <button type="submit" class="btn btn-primary" style="padding: 14px 28px;">
                        <?php echo nadiim_get_icon( 'search' ); ?>
                        <?php esc_html_e( 'بحث', 'nadiim' ); ?>
                    </button>
                </form>
            </div>

            <!-- روابط سريعة -->
            <div style="margin-bottom: var(--spacing-lg);">
                <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-md); color: var(--color-text-secondary);">
                    <?php esc_html_e( 'أو تصفح الأقسام التالية:', 'nadiim' ); ?>
                </h3>
                <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-lg">
                        <?php echo nadiim_get_icon( 'home' ); ?>
                        <?php esc_html_e( 'الصفحة الرئيسية', 'nadiim' ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'howarat' ) ); ?>" class="btn btn-outline btn-lg">
                        <?php esc_html_e( 'الحوارات', 'nadiim' ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>" class="btn btn-outline btn-lg">
                        <?php esc_html_e( 'الإصدارات', 'nadiim' ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'reading_clubs' ) ); ?>" class="btn btn-outline btn-lg">
                        <?php esc_html_e( 'نوادي القراءة', 'nadiim' ); ?>
                    </a>
                </div>
            </div>

            <!-- آخر المنشورات -->
            <?php
            $recent_posts = new WP_Query( array(
                'post_type'      => array( 'post', 'howarat', 'esdar' ),
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            if ( $recent_posts->have_posts() ) : ?>
                <div style="margin-top: var(--spacing-xxl); padding-top: var(--spacing-xl); border-top: 2px solid var(--color-border);">
                    <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-lg);">
                        <?php esc_html_e( 'أحدث المنشورات', 'nadiim' ); ?>
                    </h3>
                    <div style="display: grid; gap: var(--spacing-md); text-align: right;">
                        <?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
                            <div style="padding: var(--spacing-md); background: var(--color-bg-section); border-radius: var(--radius-md); display: flex; gap: var(--spacing-md); align-items: center; transition: transform 0.3s ease;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>" style="flex-shrink: 0; width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden;">
                                        <?php the_post_thumbnail( 'thumbnail', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                                    </a>
                                <?php endif; ?>
                                <div style="flex: 1;">
                                    <h4 style="margin-bottom: var(--spacing-xs); font-size: var(--font-size-base);">
                                        <a href="<?php the_permalink(); ?>" style="color: var(--color-text); text-decoration: none; transition: color 0.3s ease;">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>
                                    <div style="font-size: 13px; color: var(--color-text-secondary);">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php
get_footer();
