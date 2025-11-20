<?php
/**
 * قالب أرشيف الكاتب (Author Archive Template)
 *
 * يعرض صفحة الكاتب مع:
 * - بطاقة الكاتب (صورة، اسم، bio، روابط تواصل)
 * - قائمة المنشورات (مقالات + حوارات)
 * - فلتر وبحث داخل منشورات الكاتب
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

// الحصول على بيانات الكاتب
$author = get_queried_object();
$author_id = $author->ID;

// الحصول على إحصائيات المنشورات
$posts_count = nadiim_get_author_post_count( $author_id, 'post' );
$howarat_count = nadiim_get_author_post_count( $author_id, 'howarat' );
$esdar_count = nadiim_get_author_post_count( $author_id, 'esdar' );
$total_count = $posts_count + $howarat_count + $esdar_count;

// الحصول على نوع المحتوى المطلوب (من معامل URL)
$content_type = isset( $_GET['content_type'] ) ? sanitize_key( $_GET['content_type'] ) : 'all';

// الحصول على معامل البحث
$search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

// الحصول على معامل الترتيب
$orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'date';
?>

<main id="primary" class="site-main author-page" dir="rtl">
    <div class="author-page-container">

        <!-- Sidebar: بطاقة الكاتب (Author Card) -->
        <aside class="author-sidebar">
            <?php get_template_part( 'template-parts/author/author', 'header' ); ?>
        </aside>

        <!-- Main Content: محتوى الكاتب -->
        <div class="author-content">

            <!-- ترويسة المحتوى -->
            <div class="author-content-header">
                <h1 class="author-content-title">
                    <?php
                    printf(
                        /* translators: %s: Author display name */
                        esc_html__( 'محتوى %s', 'nadiim' ),
                        '<span class="author-name">' . esc_html( $author->display_name ) . '</span>'
                    );
                    ?>
                </h1>
                <p class="author-content-stats">
                    <?php
                    printf(
                        /* translators: %d: Total posts count */
                        esc_html( _n( '%d منشور', '%d منشورات', $total_count, 'nadiim' ) ),
                        number_format_i18n( $total_count )
                    );
                    ?>
                </p>
            </div>

            <!-- شريط الفلتر والبحث (Sticky Toolbar) -->
            <div class="author-toolbar" id="author-toolbar">
                <div class="author-toolbar-filters">
                    <!-- فلتر نوع المحتوى -->
                    <div class="filter-group">
                        <label for="content-type-filter" class="filter-label">
                            <?php esc_html_e( 'نوع المحتوى:', 'nadiim' ); ?>
                        </label>
                        <select name="content_type" id="content-type-filter" class="filter-select">
                            <option value="all" <?php selected( $content_type, 'all' ); ?>>
                                <?php esc_html_e( 'الكل', 'nadiim' ); ?>
                                (<?php echo number_format_i18n( $total_count ); ?>)
                            </option>
                            <?php if ( $posts_count > 0 ) : ?>
                            <option value="post" <?php selected( $content_type, 'post' ); ?>>
                                <?php esc_html_e( 'مقالات', 'nadiim' ); ?>
                                (<?php echo number_format_i18n( $posts_count ); ?>)
                            </option>
                            <?php endif; ?>
                            <?php if ( $howarat_count > 0 ) : ?>
                            <option value="howarat" <?php selected( $content_type, 'howarat' ); ?>>
                                <?php esc_html_e( 'حوارات', 'nadiim' ); ?>
                                (<?php echo number_format_i18n( $howarat_count ); ?>)
                            </option>
                            <?php endif; ?>
                            <?php if ( $esdar_count > 0 ) : ?>
                            <option value="esdar" <?php selected( $content_type, 'esdar' ); ?>>
                                <?php esc_html_e( 'إصدارات', 'nadiim' ); ?>
                                (<?php echo number_format_i18n( $esdar_count ); ?>)
                            </option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- فلتر الترتيب -->
                    <div class="filter-group">
                        <label for="orderby-filter" class="filter-label">
                            <?php esc_html_e( 'الترتيب:', 'nadiim' ); ?>
                        </label>
                        <select name="orderby" id="orderby-filter" class="filter-select">
                            <option value="date" <?php selected( $orderby, 'date' ); ?>>
                                <?php esc_html_e( 'الأحدث', 'nadiim' ); ?>
                            </option>
                            <option value="title" <?php selected( $orderby, 'title' ); ?>>
                                <?php esc_html_e( 'الأبجدية', 'nadiim' ); ?>
                            </option>
                            <option value="comment_count" <?php selected( $orderby, 'comment_count' ); ?>>
                                <?php esc_html_e( 'الأكثر تفاعلاً', 'nadiim' ); ?>
                            </option>
                        </select>
                    </div>
                </div>

                <!-- شريط البحث -->
                <div class="author-search">
                    <form role="search" method="get" class="author-search-form" action="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                        <label for="author-search-input" class="screen-reader-text">
                            <?php esc_html_e( 'بحث في محتوى الكاتب', 'nadiim' ); ?>
                        </label>
                        <div class="search-input-wrapper">
                            <?php echo nadiim_get_icon( 'search' ); ?>
                            <input type="search"
                                   id="author-search-input"
                                   class="search-field"
                                   placeholder="<?php esc_attr_e( 'بحث...', 'nadiim' ); ?>"
                                   value="<?php echo esc_attr( $search_query ); ?>"
                                   name="s"
                                   autocomplete="off" />
                        </div>
                        <button type="submit" class="search-submit">
                            <?php esc_html_e( 'بحث', 'nadiim' ); ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- قائمة المنشورات -->
            <div class="author-posts-list">
                <?php
                // تمرير المتغيرات إلى template part
                set_query_var( 'author_id', $author_id );
                set_query_var( 'content_type', $content_type );
                set_query_var( 'search_query', $search_query );
                set_query_var( 'orderby', $orderby );

                get_template_part( 'template-parts/author/author-posts', 'list' );
                ?>
            </div>

            <!-- قسم السيرة التفصيلية -->
            <?php
            $author_bio = nadiim_get_author_bio( $author_id );
            if ( ! empty( $author_bio ) ) :
            ?>
            <div class="author-bio-section">
                <?php get_template_part( 'template-parts/author/author', 'bio' ); ?>
            </div>
            <?php endif; ?>

        </div><!-- .author-content -->

    </div><!-- .author-page-container -->
</main><!-- #primary -->

<!-- Modal: نموذج مراسلة الكاتب -->
<?php if ( nadiim_author_allows_contact( $author_id ) ) : ?>
<div id="contact-author-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="contact-modal-title" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button type="button" class="modal-close" aria-label="<?php esc_attr_e( 'إغلاق', 'nadiim' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <h2 id="contact-modal-title" class="modal-title">
            <?php
            printf(
                /* translators: %s: Author display name */
                esc_html__( 'راسل %s', 'nadiim' ),
                esc_html( $author->display_name )
            );
            ?>
        </h2>

        <form id="contact-author-form" class="contact-form">
            <input type="hidden" name="author_id" value="<?php echo esc_attr( $author_id ); ?>" />

            <div class="form-field">
                <label for="sender-name">
                    <?php esc_html_e( 'الاسم', 'nadiim' ); ?>
                    <span class="required">*</span>
                </label>
                <input type="text" id="sender-name" name="sender_name" required />
            </div>

            <div class="form-field">
                <label for="sender-email">
                    <?php esc_html_e( 'البريد الإلكتروني', 'nadiim' ); ?>
                    <span class="required">*</span>
                </label>
                <input type="email" id="sender-email" name="sender_email" required />
            </div>

            <div class="form-field">
                <label for="message">
                    <?php esc_html_e( 'الرسالة', 'nadiim' ); ?>
                    <span class="required">*</span>
                </label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?php esc_html_e( 'إرسال الرسالة', 'nadiim' ); ?>
                </button>
                <button type="button" class="btn btn-secondary modal-close">
                    <?php esc_html_e( 'إلغاء', 'nadiim' ); ?>
                </button>
            </div>

            <div class="form-response" style="display: none;"></div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- JSON-LD Schema.org Person Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?php echo esc_js( $author->display_name ); ?>",
    "url": "<?php echo esc_js( get_author_posts_url( $author_id ) ); ?>",
    <?php
    $profile_picture = nadiim_get_author_profile_picture( $author_id, 'large' );
    if ( $profile_picture ) :
    ?>
    "image": "<?php echo esc_js( $profile_picture ); ?>",
    <?php endif; ?>
    "description": "<?php echo esc_js( wp_strip_all_tags( nadiim_get_author_excerpt( $author_id ) ) ); ?>",
    <?php
    $social_links = nadiim_get_author_social_links( $author_id );
    if ( ! empty( $social_links ) ) :
    ?>
    "sameAs": [
        <?php echo '"' . implode( '","', array_map( 'esc_js', $social_links ) ) . '"'; ?>
    ],
    <?php endif; ?>
    "jobTitle": "<?php echo esc_js( __( 'كاتب', 'nadiim' ) ); ?>",
    "worksFor": {
        "@type": "Organization",
        "name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>"
    }
}
</script>

<?php
get_footer();
