<?php
/**
 * قالب أرشيف الإصدارات
 *
 * @package Nadiim
 */

get_header();
?>

<main id="primary" class="site-main esdar-archive">

    <!-- Hero Section -->
    <section class="esdar-archive-hero">
        <div class="container">
            <div class="esdar-archive-header">
                <h1 class="esdar-archive-title">
                    <?php
                    if (is_category() || is_tag()) {
                        single_term_title();
                    } else {
                        _e('الإصدارات', 'nadiim');
                    }
                    ?>
                </h1>

                <?php
                $description = '';
                if (is_category() || is_tag()) {
                    $description = term_description();
                } else {
                    $description = __('استعرض مكتبتنا من الكتب والمجلات والنشرات والتقارير', 'nadiim');
                }

                if ($description) :
                ?>
                    <div class="esdar-archive-description">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Filter Bar -->
    <section class="esdar-filter-section">
        <div class="container">
            <form class="esdar-filter-bar" method="get" action="<?php echo esc_url(get_post_type_archive_link('esdar')); ?>">

                <!-- نوع الإصدار -->
                <div class="esdar-filter-item">
                    <label for="filter-type"><?php _e('النوع', 'nadiim'); ?></label>
                    <select name="release_type" id="filter-type" class="esdar-filter-select">
                        <option value=""><?php _e('الكل', 'nadiim'); ?></option>
                        <?php
                        $types = array(
                            'book' => __('كتاب', 'nadiim'),
                            'magazine' => __('مجلة', 'nadiim'),
                            'brochure' => __('كتيب', 'nadiim'),
                            'report' => __('تقرير', 'nadiim'),
                            'issue' => __('عدد', 'nadiim'),
                        );

                        // جلب إحصائيات الأنواع
                        $type_counts = nadiim_get_esdar_type_counts();

                        $selected_type = isset($_GET['release_type']) ? sanitize_text_field($_GET['release_type']) : '';
                        foreach ($types as $key => $label) {
                            $selected = $selected_type === $key ? 'selected' : '';
                            $count = isset($type_counts[$key]) ? $type_counts[$key] : 0;
                            $label_with_count = $count > 0 ? sprintf('%s (%d)', $label, $count) : $label;
                            echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label_with_count) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- السنة -->
                <div class="esdar-filter-item">
                    <label for="filter-year"><?php _e('السنة', 'nadiim'); ?></label>
                    <select name="release_year" id="filter-year" class="esdar-filter-select">
                        <option value=""><?php _e('الكل', 'nadiim'); ?></option>
                        <?php
                        // جلب إحصائيات السنوات
                        $year_counts = nadiim_get_esdar_year_counts();

                        // ترتيب السنوات تنازلياً
                        krsort($year_counts);

                        $selected_year = isset($_GET['release_year']) ? sanitize_text_field($_GET['release_year']) : '';

                        foreach ($year_counts as $year => $count) {
                            if ($year) {
                                $selected = $selected_year == $year ? 'selected' : '';
                                $label = sprintf('%s (%d)', $year, $count);
                                echo '<option value="' . esc_attr($year) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- التصنيف -->
                <div class="esdar-filter-item">
                    <label for="filter-category"><?php _e('التصنيف', 'nadiim'); ?></label>
                    <select name="category" id="filter-category" class="esdar-filter-select">
                        <option value=""><?php _e('الكل', 'nadiim'); ?></option>
                        <?php
                        $categories = get_categories(array(
                            'taxonomy' => 'category',
                            'hide_empty' => true,
                        ));
                        $selected_cat = isset($_GET['category']) ? intval($_GET['category']) : 0;
                        foreach ($categories as $cat) {
                            $selected = $selected_cat === $cat->term_id ? 'selected' : '';
                            // حساب عدد الإصدارات في هذا التصنيف
                            $cat_count = get_posts(array(
                                'post_type' => 'esdar',
                                'category' => $cat->term_id,
                                'posts_per_page' => -1,
                                'fields' => 'ids',
                            ));
                            $count = count($cat_count);
                            $label = $count > 0 ? sprintf('%s (%d)', $cat->name, $count) : $cat->name;
                            echo '<option value="' . esc_attr($cat->term_id) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- زر الفلترة -->
                <div class="esdar-filter-item esdar-filter-submit">
                    <button type="submit" class="esdar-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        <?php _e('تصفية', 'nadiim'); ?>
                    </button>

                    <?php if (!empty($_GET['release_type']) || !empty($_GET['release_year']) || !empty($_GET['category'])) : ?>
                        <a href="<?php echo esc_url(get_post_type_archive_link('esdar')); ?>" class="esdar-filter-reset">
                            <?php _e('إعادة تعيين', 'nadiim'); ?>
                        </a>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </section>

    <!-- Grid Section -->
    <section class="esdar-grid-section">
        <div class="container">

            <?php
            // تطبيق الفلاتر
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $per_page = get_theme_mod('esdar_archive_per_page', 12);

            $args = array(
                'post_type' => 'esdar',
                'posts_per_page' => $per_page,
                'paged' => $paged,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            );

            // فلتر التصنيف
            if (!empty($_GET['category'])) {
                $args['cat'] = intval($_GET['category']);
            }

            // فلتر حسب النوع والسنة (يتطلب meta_query)
            if (!empty($_GET['release_type']) || !empty($_GET['release_year'])) {
                // للأسف لا يمكن فلترة JSON بسهولة في WP_Query
                // سنحتاج لجلب جميع المنشورات وفلترتها يدوياً أو استخدام posts_where filter
            }

            $query = new WP_Query($args);

            // فلترة يدوية إذا لزم الأمر
            if (!empty($_GET['release_type']) || !empty($_GET['release_year'])) {
                $filtered_posts = array();

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $meta = nadiim_get_esdar_meta(get_the_ID());

                        $type_match = empty($_GET['release_type']) || (isset($meta['release_type']) && $meta['release_type'] === $_GET['release_type']);
                        $year_match = empty($_GET['release_year']) || (isset($meta['release_date']) && date('Y', strtotime($meta['release_date'])) == $_GET['release_year']);

                        if ($type_match && $year_match) {
                            $filtered_posts[] = get_post();
                        }
                    }
                    wp_reset_postdata();
                }

                // إعادة بناء query بالنتائج المفلترة
                $query->posts = $filtered_posts;
                $query->post_count = count($filtered_posts);
                $query->found_posts = count($filtered_posts);
            }

            if ($query->have_posts()) :
            ?>

                <div class="esdar-grid esdar-grid-3">
                    <?php
                    if (!empty($_GET['release_type']) || !empty($_GET['release_year'])) {
                        // عرض المنشورات المفلترة
                        foreach ($query->posts as $post) {
                            setup_postdata($post);
                            get_template_part('template-parts/components/card', 'esdar');
                        }
                        wp_reset_postdata();
                    } else {
                        // عرض عادي
                        while ($query->have_posts()) {
                            $query->the_post();
                            get_template_part('template-parts/components/card', 'esdar');
                        }
                        wp_reset_postdata();
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <?php
                $pagination = paginate_links(array(
                    'total' => $query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>',
                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>',
                    'type' => 'list',
                ));

                if ($pagination) :
                ?>
                    <nav class="esdar-pagination" aria-label="<?php _e('التنقل بين الصفحات', 'nadiim'); ?>">
                        <?php echo $pagination; ?>
                    </nav>
                <?php endif; ?>

            <?php else : ?>

                <div class="esdar-no-results">
                    <div class="esdar-no-results-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            <line x1="10" y1="10" x2="16" y2="10"/>
                            <line x1="10" y1="14" x2="16" y2="14"/>
                        </svg>
                    </div>
                    <h2 class="esdar-no-results-title"><?php _e('لا توجد إصدارات', 'nadiim'); ?></h2>
                    <p class="esdar-no-results-text">
                        <?php _e('عذراً، لم يتم العثور على أي إصدارات تطابق معايير البحث.', 'nadiim'); ?>
                    </p>

                    <?php if (!empty($_GET['release_type']) || !empty($_GET['release_year']) || !empty($_GET['category'])) : ?>
                        <a href="<?php echo esc_url(get_post_type_archive_link('esdar')); ?>" class="esdar-btn esdar-btn-primary">
                            <?php _e('عرض جميع الإصدارات', 'nadiim'); ?>
                        </a>
                    <?php endif; ?>
                </div>

            <?php
            endif;
            wp_reset_postdata();
            ?>

        </div>
    </section>

</main>

<?php
get_footer();
