<?php
/**
 * Hero Slider Component
 *
 * السلايدر الرئيسي للصفحة الرئيسية باستخدام Swiper.js
 *
 * @package Madjaliss
 * @version 2.0 - Stage 1B
 */

// جلب عدد الشرائح من Customizer (افتراضي: 5)
$slider_count = get_theme_mod('home_slider_count', 5);

// محاولة جلب المقالات من WordPress
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $slider_count,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$slider_query = new WP_Query($args);
$slides_data = array();

// إذا كانت هناك مقالات، استخدمها
if ($slider_query->have_posts()) {
    while ($slider_query->have_posts()) {
        $slider_query->the_post();

        $slides_data[] = array(
            'id'           => get_the_ID(),
            'title'        => get_the_title(),
            'excerpt'      => get_the_excerpt(),
            'permalink'    => get_permalink(),
            'thumbnail'    => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            'author'       => get_the_author(),
            'date'         => get_the_date(),
            'reading_time' => get_post_meta(get_the_ID(), 'reading_time_manual', true) ?: madjaliss_get_reading_time(get_the_ID()),
            'has_audio'    => get_post_meta(get_the_ID(), 'has_audio', true) ?: false,
        );
    }
    wp_reset_postdata();
} else {
    // Fallback: استخدم demo data من JSON
    $demo_file = get_template_directory() . '/demo/home-demo.json';
    if (file_exists($demo_file)) {
        $demo_content = file_get_contents($demo_file);
        $demo_data = json_decode($demo_content, true);

        if (!empty($demo_data['slides'])) {
            $slides_data = array_slice($demo_data['slides'], 0, $slider_count);
        }
    }
}

// إذا لم تكن هناك بيانات على الإطلاق، لا نعرض السلايدر
if (empty($slides_data)) {
    return;
}

// حساب إجمالي عدد الشرائح
$total_slides = count($slides_data);
?>

<section class="hero-slider-section" aria-label="<?php esc_attr_e('السلايدر الرئيسي', 'madjaliss'); ?>">
    <div class="container">

        <!-- Hero Slider Header -->
        <div class="hero-slider-header">
            <h2 class="hero-slider-title"><?php echo esc_html(get_theme_mod('home_slider_title', 'أحدث المقالات')); ?></h2>

            <!-- Controls -->
            <div class="hero-slider-controls">
                <!-- Play/Pause Button -->
                <button class="slider-play-pause" aria-label="<?php esc_attr_e('إيقاف/تشغيل السلايدر التلقائي', 'madjaliss'); ?>" data-playing="true">
                    <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="6" y="4" width="4" height="16"></rect>
                        <rect x="14" y="4" width="4" height="16"></rect>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Swiper Container -->
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">

                <?php
                $slide_index = 1;
                foreach ($slides_data as $slide) :
                ?>

                <div class="swiper-slide"
                     role="group"
                     aria-roledescription="<?php esc_attr_e('شريحة', 'madjaliss'); ?>"
                     aria-label="<?php echo esc_attr(sprintf(__('الشريحة %d من %d', 'madjaliss'), $slide_index, $total_slides)); ?>">

                    <?php
                    // تمرير بيانات الشريحة إلى template part
                    set_query_var('slide_data', $slide);
                    set_query_var('slide_index', $slide_index);
                    set_query_var('total_slides', $total_slides);

                    get_template_part('template-parts/home/slider-card-article');
                    ?>

                </div>

                <?php
                $slide_index++;
                endforeach;
                ?>

            </div><!-- .swiper-wrapper -->

            <!-- Navigation Arrows -->
            <div class="swiper-button-prev" aria-label="<?php esc_attr_e('الشريحة السابقة', 'madjaliss'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </div>
            <div class="swiper-button-next" aria-label="<?php esc_attr_e('الشريحة التالية', 'madjaliss'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination" role="tablist"></div>

        </div><!-- .hero-swiper -->

    </div><!-- .container -->
</section><!-- .hero-slider-section -->
