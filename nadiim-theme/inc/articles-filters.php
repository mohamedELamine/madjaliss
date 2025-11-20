<?php
/**
 * Articles Archive Filters
 * Handles filtering and sorting for articles archive page
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * تعديل الاستعلام الرئيسي لأرشيف المقالات
 */
function nadiim_articles_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_home() || is_archive() ) && ! is_post_type_archive() ) {

        // فلتر التصنيف
        if ( ! empty( $_GET['article_category'] ) && $_GET['article_category'] !== 'all' ) {
            $query->set( 'cat', absint( $_GET['article_category'] ) );
        }

        // فلتر الوسوم
        if ( ! empty( $_GET['article_tag'] ) && $_GET['article_tag'] !== 'all' ) {
            $query->set( 'tag_id', absint( $_GET['article_tag'] ) );
        }

        // فلتر الكاتب
        if ( ! empty( $_GET['article_author'] ) && $_GET['article_author'] !== 'all' ) {
            $query->set( 'author', absint( $_GET['article_author'] ) );
        }

        // فلتر البحث
        if ( ! empty( $_GET['article_search'] ) ) {
            $query->set( 's', sanitize_text_field( $_GET['article_search'] ) );
        }

        // فلتر وقت القراءة (سيتم معالجته في posts_where)
        if ( ! empty( $_GET['reading_time'] ) && $_GET['reading_time'] !== 'all' ) {
            // سنضيف meta_query في posts_where
            add_filter( 'posts_where', 'nadiim_filter_by_reading_time', 10, 2 );
        }

        // الترتيب
        $orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'date';
        $order = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';

        switch ( $orderby ) {
            case 'title':
                $query->set( 'orderby', 'title' );
                break;
            case 'comment_count':
                $query->set( 'orderby', 'comment_count' );
                break;
            case 'rand':
                $query->set( 'orderby', 'rand' );
                break;
            default:
                $query->set( 'orderby', 'date' );
        }

        $query->set( 'order', strtoupper( $order ) );
    }
}
add_action( 'pre_get_posts', 'nadiim_articles_archive_query' );

/**
 * فلترة بناءً على وقت القراءة
 */
function nadiim_filter_by_reading_time( $where, $query ) {
    global $wpdb;

    if ( ! empty( $_GET['reading_time'] ) && $_GET['reading_time'] !== 'all' ) {
        $reading_time = sanitize_text_field( $_GET['reading_time'] );

        switch ( $reading_time ) {
            case 'short': // أقل من 5 دقائق
                $where .= " AND {$wpdb->posts}.ID IN (
                    SELECT post_id FROM {$wpdb->postmeta}
                    WHERE meta_key = 'article_meta'
                    AND CAST(
                        JSON_EXTRACT(meta_value, '$.reading_time_manual') AS UNSIGNED
                    ) < 5
                )";
                break;

            case 'medium': // 5-10 دقائق
                $where .= " AND {$wpdb->posts}.ID IN (
                    SELECT post_id FROM {$wpdb->postmeta}
                    WHERE meta_key = 'article_meta'
                    AND CAST(
                        JSON_EXTRACT(meta_value, '$.reading_time_manual') AS UNSIGNED
                    ) BETWEEN 5 AND 10
                )";
                break;

            case 'long': // أكثر من 10 دقائق
                $where .= " AND {$wpdb->posts}.ID IN (
                    SELECT post_id FROM {$wpdb->postmeta}
                    WHERE meta_key = 'article_meta'
                    AND CAST(
                        JSON_EXTRACT(meta_value, '$.reading_time_manual') AS UNSIGNED
                    ) > 10
                )";
                break;

            case 'audio': // فقط المقالات التي تحتوي على صوت
                $where .= " AND {$wpdb->posts}.ID IN (
                    SELECT post_id FROM {$wpdb->postmeta}
                    WHERE meta_key = 'article_meta'
                    AND JSON_EXTRACT(meta_value, '$.audio_url') IS NOT NULL
                    AND JSON_EXTRACT(meta_value, '$.audio_url') != ''
                )";
                break;
        }
    }

    // إزالة الفلتر لتجنب التطبيق المتكرر
    remove_filter( 'posts_where', 'nadiim_filter_by_reading_time', 10 );

    return $where;
}

/**
 * الحصول على رابط الفلتر الحالي
 */
function nadiim_get_filter_url( $args = array() ) {
    $current_url = home_url( $_SERVER['REQUEST_URI'] );
    $current_params = $_GET;

    // دمج المعاملات الجديدة
    $params = array_merge( $current_params, $args );

    // إزالة المعاملات الفارغة
    $params = array_filter( $params, function( $value ) {
        return $value !== '' && $value !== 'all';
    });

    // إنشاء URL جديد
    $base_url = strtok( $current_url, '?' );
    return add_query_arg( $params, $base_url );
}

/**
 * الحصول على القيمة الحالية للفلتر
 */
function nadiim_get_current_filter( $key, $default = 'all' ) {
    return isset( $_GET[ $key ] ) ? sanitize_text_field( $_GET[ $key ] ) : $default;
}

/**
 * عرض خيارات التصنيفات للفلتر
 */
function nadiim_get_categories_options() {
    $categories = get_categories( array(
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true,
    ) );

    $current = nadiim_get_current_filter( 'article_category' );

    $options = '<option value="all">' . esc_html__( 'كل التصنيفات', 'nadiim' ) . '</option>';

    foreach ( $categories as $category ) {
        $selected = selected( $current, $category->term_id, false );
        $options .= sprintf(
            '<option value="%d" %s>%s (%d)</option>',
            $category->term_id,
            $selected,
            esc_html( $category->name ),
            $category->count
        );
    }

    return $options;
}

/**
 * عرض خيارات الوسوم للفلتر
 */
function nadiim_get_tags_options() {
    $tags = get_tags( array(
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true,
        'number' => 20, // الحد الأقصى 20 وسم
    ) );

    $current = nadiim_get_current_filter( 'article_tag' );

    $options = '<option value="all">' . esc_html__( 'كل الوسوم', 'nadiim' ) . '</option>';

    foreach ( $tags as $tag ) {
        $selected = selected( $current, $tag->term_id, false );
        $options .= sprintf(
            '<option value="%d" %s>%s (%d)</option>',
            $tag->term_id,
            $selected,
            esc_html( $tag->name ),
            $tag->count
        );
    }

    return $options;
}

/**
 * عرض خيارات الكتاب للفلتر
 */
function nadiim_get_authors_options() {
    $authors = get_users( array(
        'who' => 'authors',
        'has_published_posts' => array( 'post' ),
        'orderby' => 'post_count',
        'order' => 'DESC',
    ) );

    $current = nadiim_get_current_filter( 'article_author' );

    $options = '<option value="all">' . esc_html__( 'كل الكتاب', 'nadiim' ) . '</option>';

    foreach ( $authors as $author ) {
        $selected = selected( $current, $author->ID, false );
        $post_count = count_user_posts( $author->ID, 'post' );

        $options .= sprintf(
            '<option value="%d" %s>%s (%d)</option>',
            $author->ID,
            $selected,
            esc_html( $author->display_name ),
            $post_count
        );
    }

    return $options;
}

/**
 * عرض نموذج الفلتر
 */
function nadiim_render_articles_filters() {
    $current_search = nadiim_get_current_filter( 'article_search', '' );
    $current_reading_time = nadiim_get_current_filter( 'reading_time' );
    ?>

    <form method="get" action="" class="archive-filters" id="articles-filter-form">
        <div class="filters-grid">
            <!-- التصنيف -->
            <div class="filter-group">
                <label for="article_category" class="filter-label">
                    <?php esc_html_e( 'التصنيف', 'nadiim' ); ?>
                </label>
                <select name="article_category" id="article_category" class="filter-select">
                    <?php echo nadiim_get_categories_options(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </select>
            </div>

            <!-- الوسم -->
            <div class="filter-group">
                <label for="article_tag" class="filter-label">
                    <?php esc_html_e( 'الوسم', 'nadiim' ); ?>
                </label>
                <select name="article_tag" id="article_tag" class="filter-select">
                    <?php echo nadiim_get_tags_options(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </select>
            </div>

            <!-- الكاتب -->
            <div class="filter-group">
                <label for="article_author" class="filter-label">
                    <?php esc_html_e( 'الكاتب', 'nadiim' ); ?>
                </label>
                <select name="article_author" id="article_author" class="filter-select">
                    <?php echo nadiim_get_authors_options(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </select>
            </div>

            <!-- وقت القراءة -->
            <div class="filter-group">
                <label for="reading_time" class="filter-label">
                    <?php esc_html_e( 'وقت القراءة', 'nadiim' ); ?>
                </label>
                <select name="reading_time" id="reading_time" class="filter-select">
                    <option value="all" <?php selected( $current_reading_time, 'all' ); ?>>
                        <?php esc_html_e( 'كل الأوقات', 'nadiim' ); ?>
                    </option>
                    <option value="short" <?php selected( $current_reading_time, 'short' ); ?>>
                        <?php esc_html_e( 'أقل من 5 دقائق', 'nadiim' ); ?>
                    </option>
                    <option value="medium" <?php selected( $current_reading_time, 'medium' ); ?>>
                        <?php esc_html_e( '5-10 دقائق', 'nadiim' ); ?>
                    </option>
                    <option value="long" <?php selected( $current_reading_time, 'long' ); ?>>
                        <?php esc_html_e( 'أكثر من 10 دقائق', 'nadiim' ); ?>
                    </option>
                    <option value="audio" <?php selected( $current_reading_time, 'audio' ); ?>>
                        <?php esc_html_e( 'مع صوت فقط', 'nadiim' ); ?>
                    </option>
                </select>
            </div>

            <!-- البحث -->
            <div class="filter-group" style="grid-column: span 2;">
                <label for="article_search" class="filter-label">
                    <?php esc_html_e( 'البحث', 'nadiim' ); ?>
                </label>
                <input
                    type="text"
                    name="article_search"
                    id="article_search"
                    class="filter-select"
                    value="<?php echo esc_attr( $current_search ); ?>"
                    placeholder="<?php esc_attr_e( 'ابحث في المقالات...', 'nadiim' ); ?>"
                >
            </div>
        </div>

        <div class="filters-actions">
            <button type="submit" class="filter-btn filter-btn-apply">
                <?php esc_html_e( 'تطبيق الفلاتر', 'nadiim' ); ?>
            </button>
            <button type="button" class="filter-btn filter-btn-reset" onclick="nadiimResetFilters()">
                <?php esc_html_e( 'إعادة الضبط', 'nadiim' ); ?>
            </button>
        </div>
    </form>

    <script>
    function nadiimResetFilters() {
        window.location.href = window.location.pathname;
    }
    </script>

    <?php
}
