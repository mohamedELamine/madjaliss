<?php
/**
 * قالب أرشيف الحوارات
 *
 * يعرض قائمة الحوارات مع الفلاتر والتصنيف
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- عنوان الصفحة -->
    <div class="page-hero" style="background-color: var(--color-bg-section); padding: var(--spacing-xl) 0;">
        <div class="container">
            <header class="page-header text-center">
                <h1 class="page-title">
                    <?php
                    if ( is_tax( 'dialogue_type' ) || is_tax( 'dialogue_topic' ) ) {
                        single_term_title();
                    } else {
                        esc_html_e( 'أرشيف الحوارات', 'nadiim' );
                    }
                    ?>
                </h1>
                <?php
                $description = get_the_archive_description();
                if ( $description ) :
                    ?>
                    <div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
                <?php endif; ?>
            </header>
        </div>
    </div>

    <div class="container section">

        <!-- الفلاتر بتصميم عصري -->
        <div class="archive-filters" style="margin-bottom: var(--spacing-xl);">
            <div class="filters-container" style="background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); padding: var(--spacing-xl); border-radius: var(--radius-xl); box-shadow: 0 5px 20px rgba(0,0,0,0.08);">

                <!-- عنوان الفلاتر -->
                <div style="text-align: center; margin-bottom: var(--spacing-lg);">
                    <h3 style="font-size: var(--font-size-xl); color: var(--color-primary); margin-bottom: var(--spacing-xs); display: flex; align-items: center; justify-content: center; gap: var(--spacing-sm);">
                        <span style="font-size: 24px;">🔍</span>
                        <?php esc_html_e( 'تصفية الحوارات', 'nadiim' ); ?>
                    </h3>
                    <p style="color: var(--color-text-secondary); font-size: 14px;">
                        <?php esc_html_e( 'استخدم الفلاتر أدناه للعثور على ما تبحث عنه', 'nadiim' ); ?>
                    </p>
                </div>

                <div class="filters-inner" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-lg);">

                    <!-- فلتر حسب النوع -->
                    <?php
                    $dialogue_types = get_terms( array(
                        'taxonomy'   => 'dialogue_type',
                        'hide_empty' => true,
                    ) );

                    if ( ! empty( $dialogue_types ) && ! is_wp_error( $dialogue_types ) ) :
                        ?>
                        <div class="filter-group" style="background: #fff; padding: var(--spacing-lg); border-radius: var(--radius-lg); box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 3px solid var(--color-primary);">
                            <label for="filter-type" style="display: flex; align-items: center; gap: var(--spacing-xs); font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--color-primary); font-size: var(--font-size-base);">
                                <span style="font-size: 20px;">📁</span>
                                <?php esc_html_e( 'النوع', 'nadiim' ); ?>
                            </label>
                            <select id="filter-type" class="filter-select" style="width: 100%; padding: 12px 16px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); background: var(--color-bg-lighter); transition: all 0.3s ease; cursor: pointer;">
                                <option value=""><?php esc_html_e( 'جميع الأنواع', 'nadiim' ); ?></option>
                                <?php foreach ( $dialogue_types as $type ) : ?>
                                    <option value="<?php echo esc_url( get_term_link( $type ) ); ?>" <?php selected( is_tax( 'dialogue_type', $type->slug ) ); ?>>
                                        <?php echo esc_html( $type->name ); ?> (<?php echo esc_html( $type->count ); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- فلتر حسب الموضوع -->
                    <?php
                    $dialogue_topics = get_terms( array(
                        'taxonomy'   => 'dialogue_topic',
                        'hide_empty' => true,
                        'number'     => 20,
                    ) );

                    if ( ! empty( $dialogue_topics ) && ! is_wp_error( $dialogue_topics ) ) :
                        ?>
                        <div class="filter-group" style="background: #fff; padding: var(--spacing-lg); border-radius: var(--radius-lg); box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 3px solid var(--color-secondary);">
                            <label for="filter-topic" style="display: flex; align-items: center; gap: var(--spacing-xs); font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--color-secondary); font-size: var(--font-size-base);">
                                <span style="font-size: 20px;">🏷️</span>
                                <?php esc_html_e( 'الموضوع', 'nadiim' ); ?>
                            </label>
                            <select id="filter-topic" class="filter-select" style="width: 100%; padding: 12px 16px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); background: var(--color-bg-lighter); transition: all 0.3s ease; cursor: pointer;">
                                <option value=""><?php esc_html_e( 'جميع المواضيع', 'nadiim' ); ?></option>
                                <?php foreach ( $dialogue_topics as $topic ) : ?>
                                    <option value="<?php echo esc_url( get_term_link( $topic ) ); ?>" <?php selected( is_tax( 'dialogue_topic', $topic->slug ) ); ?>>
                                        <?php echo esc_html( $topic->name ); ?> (<?php echo esc_html( $topic->count ); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- فلتر حسب التاريخ -->
                    <div class="filter-group" style="background: #fff; padding: var(--spacing-lg); border-radius: var(--radius-lg); box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 3px solid var(--color-accent);">
                        <label for="filter-date" style="display: flex; align-items: center; gap: var(--spacing-xs); font-weight: 600; margin-bottom: var(--spacing-sm); color: var(--color-accent); font-size: var(--font-size-base);">
                            <span style="font-size: 20px;">📅</span>
                            <?php esc_html_e( 'التاريخ', 'nadiim' ); ?>
                        </label>
                        <select id="filter-date" class="filter-select" style="width: 100%; padding: 12px 16px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); background: var(--color-bg-lighter); transition: all 0.3s ease; cursor: pointer;">
                            <option value="desc"><?php esc_html_e( 'الأحدث أولاً', 'nadiim' ); ?></option>
                            <option value="asc"><?php esc_html_e( 'الأقدم أولاً', 'nadiim' ); ?></option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <?php if ( have_posts() ) : ?>

            <!-- شبكة الحوارات -->
            <div class="howarat-grid grid grid-3">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'howarat-card' );
                endwhile;
                ?>
            </div>

            <!-- الترقيم -->
            <?php
            the_posts_pagination( array(
                'mid_size'           => 2,
                'prev_text'          => __( '&rarr; السابق', 'nadiim' ),
                'next_text'          => __( 'التالي &larr;', 'nadiim' ),
                'screen_reader_text' => __( 'التنقل بين الصفحات', 'nadiim' ),
            ) );
            ?>

        <?php else : ?>

            <div class="no-results" style="text-align: center; padding: var(--spacing-xxl) 0;">
                <h2><?php esc_html_e( 'لا توجد حوارات', 'nadiim' ); ?></h2>
                <p><?php esc_html_e( 'عذراً، لم نعثر على حوارات تطابق معايير البحث الخاصة بك.', 'nadiim' ); ?></p>
            </div>

        <?php endif; ?>

    </div>

</main>

<script>
// JavaScript للفلاتر
jQuery(document).ready(function($) {
    $('.filter-select').on('change', function() {
        var url = $(this).val();
        if (url) {
            window.location.href = url;
        }
    });

    // فلتر التاريخ (سيحتاج AJAX أو إعادة تحميل)
    $('#filter-date').on('change', function() {
        var order = $(this).val();
        var currentUrl = window.location.href;
        var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
        window.location.href = currentUrl + separator + 'order=' + order;
    });
});
</script>

<?php
get_footer();
