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

        <!-- الفلاتر -->
        <div class="archive-filters" style="margin-bottom: var(--spacing-lg);">
            <div class="filters-inner" style="display: flex; gap: var(--spacing-md); flex-wrap: wrap; align-items: center; justify-content: center;">

                <!-- فلتر حسب النوع -->
                <?php
                $dialogue_types = get_terms( array(
                    'taxonomy'   => 'dialogue_type',
                    'hide_empty' => true,
                ) );

                if ( ! empty( $dialogue_types ) && ! is_wp_error( $dialogue_types ) ) :
                    ?>
                    <div class="filter-group">
                        <label for="filter-type" style="font-weight: 600; margin-left: 8px;">
                            <?php esc_html_e( 'النوع:', 'nadiim' ); ?>
                        </label>
                        <select id="filter-type" class="filter-select" style="padding: 8px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
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
                    <div class="filter-group">
                        <label for="filter-topic" style="font-weight: 600; margin-left: 8px;">
                            <?php esc_html_e( 'الموضوع:', 'nadiim' ); ?>
                        </label>
                        <select id="filter-topic" class="filter-select" style="padding: 8px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
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
                <div class="filter-group">
                    <label for="filter-date" style="font-weight: 600; margin-left: 8px;">
                        <?php esc_html_e( 'التاريخ:', 'nadiim' ); ?>
                    </label>
                    <select id="filter-date" class="filter-select" style="padding: 8px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
                        <option value="desc"><?php esc_html_e( 'الأحدث أولاً', 'nadiim' ); ?></option>
                        <option value="asc"><?php esc_html_e( 'الأقدم أولاً', 'nadiim' ); ?></option>
                    </select>
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
