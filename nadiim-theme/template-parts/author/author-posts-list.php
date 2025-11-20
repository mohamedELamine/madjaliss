<?php
/**
 * Template Part: Author Posts List (قائمة منشورات الكاتب)
 *
 * يعرض قائمة بمنشورات الكاتب مع دعم الفلترة والبحث
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على المتغيرات المررة
$author_id = get_query_var( 'author_id' );
$content_type = get_query_var( 'content_type', 'all' );
$search_query = get_query_var( 'search_query', '' );
$orderby = get_query_var( 'orderby', 'date' );

// تحديد أنواع المنشورات للاستعلام
$post_types = array();
if ( $content_type === 'all' ) {
    $post_types = array( 'post', 'howarat', 'esdar' );
} elseif ( in_array( $content_type, array( 'post', 'howarat', 'esdar' ), true ) ) {
    $post_types = array( $content_type );
}

// إعداد معاملات الاستعلام
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$args = array(
    'author'         => $author_id,
    'post_type'      => $post_types,
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => $orderby,
    'order'          => ( $orderby === 'title' ) ? 'ASC' : 'DESC',
);

// إضافة البحث إن وُجد
if ( ! empty( $search_query ) ) {
    $args['s'] = $search_query;
}

// تنفيذ الاستعلام
$author_posts = new WP_Query( $args );

?>

<div class="posts-grid">

    <?php if ( $author_posts->have_posts() ) : ?>

        <?php while ( $author_posts->have_posts() ) : $author_posts->the_post(); ?>

            <?php
            // تحديد نوع البطاقة حسب نوع المنشور
            $post_type = get_post_type();

            switch ( $post_type ) {
                case 'howarat':
                    get_template_part( 'template-parts/components/card', 'dialogue' );
                    break;

                case 'esdar':
                    get_template_part( 'template-parts/components/card', 'esdar' );
                    break;

                case 'post':
                default:
                    get_template_part( 'template-parts/components/card', 'article' );
                    break;
            }
            ?>

        <?php endwhile; ?>

        <?php wp_reset_postdata(); ?>

    <?php else : ?>

        <!-- رسالة عدم وجود نتائج -->
        <div class="no-results">
            <div class="no-results-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>

            <h3 class="no-results-title">
                <?php
                if ( ! empty( $search_query ) ) {
                    esc_html_e( 'لم يتم العثور على نتائج', 'nadiim' );
                } else {
                    esc_html_e( 'لا توجد منشورات بعد', 'nadiim' );
                }
                ?>
            </h3>

            <p class="no-results-description">
                <?php
                if ( ! empty( $search_query ) ) {
                    printf(
                        /* translators: %s: Search query */
                        esc_html__( 'لم يتم العثور على نتائج للبحث عن: "%s"', 'nadiim' ),
                        '<strong>' . esc_html( $search_query ) . '</strong>'
                    );
                } else {
                    esc_html_e( 'لم ينشر هذا الكاتب أي محتوى من هذا النوع حتى الآن.', 'nadiim' );
                }
                ?>
            </p>

            <?php if ( ! empty( $search_query ) || $content_type !== 'all' ) : ?>
            <div class="no-results-actions">
                <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"
                   class="btn btn-secondary">
                    <?php esc_html_e( 'عرض جميع المنشورات', 'nadiim' ); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div><!-- .posts-grid -->

<?php if ( $author_posts->have_posts() && $author_posts->max_num_pages > 1 ) : ?>
    <!-- Pagination -->
    <nav class="pagination" role="navigation" aria-label="<?php esc_attr_e( 'التنقل بين الصفحات', 'nadiim' ); ?>">
        <?php
        $pagination_args = array(
            'total'     => $author_posts->max_num_pages,
            'current'   => max( 1, $paged ),
            'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg> ' . __( 'السابق', 'nadiim' ),
            'next_text' => __( 'التالي', 'nadiim' ) . ' <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
            'type'      => 'list',
            'add_args'  => array(
                'content_type' => $content_type,
                's'            => $search_query,
                'orderby'      => $orderby,
            ),
        );

        echo paginate_links( $pagination_args );
        ?>
    </nav>
<?php endif; ?>
