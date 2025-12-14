<?php
/**
 * Template part لقسم Hero Slider
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل Hero Slider
if ( ! get_theme_mod( 'hero_enable', true ) ) {
    return;
}

// الحصول على الإعدادات
$hero_source = get_theme_mod( 'hero_source', 'latest_posts' );
$hero_count  = get_theme_mod( 'hero_count', 5 );

// مصفوفة الشرائح
$slides = array();

// جلب البيانات حسب المصدر
switch ( $hero_source ) {
    case 'latest_posts':
        $slides = nadiim_get_hero_slides_from_posts( 'post', $hero_count );
        break;

    case 'featured_tag':
        $featured_tag = get_theme_mod( 'hero_featured_tag', 'featured' );
        $slides = nadiim_get_hero_slides_from_posts( 'post', $hero_count, $featured_tag );
        break;

    case 'howarat':
        $slides = nadiim_get_hero_slides_from_posts( 'howarat', $hero_count );
        break;

    case 'reviews':
        $slides = nadiim_get_hero_slides_from_posts( 'reviews', $hero_count );
        break;

    case 'mixed':
        $slides = nadiim_get_hero_slides_mixed();
        break;

    case 'manual':
        $slides = nadiim_get_hero_slides_manual();
        break;

    default:
        $slides = nadiim_get_hero_slides_from_posts( 'post', $hero_count );
}

// Fallback إلى demo data إذا لم توجد شرائح
if ( empty( $slides ) ) {
    $slides = nadiim_get_hero_slides_demo();
}

// إذا ما زالت فارغة، لا تعرض شيئاً
if ( empty( $slides ) ) {
    return;
}

// تحديد عدد الشرائح
$slides_count = count( $slides );
?>

<section class="hero-slider-section" aria-label="<?php esc_attr_e( 'المحتوى المميز', 'nadiim' ); ?>">
    <div class="container">

        <!-- Swiper Container -->
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">

                <?php
                // عرض كل شريحة
                foreach ( $slides as $index => $slide ) :
                    // تمرير البيانات إلى القالب
                    set_query_var( 'slide', $slide );
                    get_template_part( 'template-parts/components/hero-slide-card' );
                endforeach;
                ?>

            </div><!-- .swiper-wrapper -->

            <!-- أزرار التنقل في وسط السلايدر -->
            <button class="swiper-button-prev" aria-label="<?php esc_attr_e( 'الشريحة السابقة', 'nadiim' ); ?>">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>
            <button class="swiper-button-next" aria-label="<?php esc_attr_e( 'الشريحة التالية', 'nadiim' ); ?>">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
            </button>

            <!-- Pagination (Bullets) في الأسفل -->
            <div class="swiper-pagination"></div>

        </div><!-- .swiper -->

    </div><!-- .container -->
</section><!-- .hero-slider-section -->

<?php
/**
 * دالة للحصول على شرائح من المنشورات
 */
function nadiim_get_hero_slides_from_posts( $post_type = 'post', $count = 5, $tag = '' ) {
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // إذا كان هناك تاج
    if ( ! empty( $tag ) ) {
        $args['tag'] = $tag;
    }

    $query = new WP_Query( $args );
    $slides = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();

            // الحصول على الصورة المميزة
            $image_url = get_the_post_thumbnail_url( $post_id, 'full' );
            if ( ! $image_url ) {
                $image_url = get_template_directory_uri() . '/assets/images/placeholder.jpg';
            }

            // معلومات إضافية
            $has_audio = get_post_meta( $post_id, 'has_audio', true ) ? true : false;
            $read_time = get_post_meta( $post_id, 'reading_time_manual', true );
            if ( empty( $read_time ) ) {
                // حساب وقت القراءة التقريبي
                $content    = get_the_content();
                $word_count = str_word_count( strip_tags( $content ) );
                $minutes    = ceil( $word_count / 200 ); // 200 كلمة في الدقيقة
                $read_time  = sprintf( _n( '%s دقيقة', '%s دقائق', $minutes, 'nadiim' ), number_format_i18n( $minutes ) );
            }

            // معلومات خاصة بالمراجعات
            $rating = null;
            if ( $post_type === 'reviews' ) {
                $rating = get_post_meta( $post_id, 'review_rating', true );
                $rating = $rating ? absint( $rating ) : null;
            }

            // بناء مصفوفة الشريحة
            $slide_data = array(
                'title'           => get_the_title(),
                'excerpt'         => wp_trim_words( get_the_excerpt(), 25, '...' ),
                'image'           => $image_url,
                'bg_enable'       => false, // يمكن تخصيصه لاحقاً
                'overlay_opacity' => get_theme_mod( 'hero_overlay_default_opacity', 0.35 ),
                'cta_text'        => nadiim_get_hero_cta_text( $post_type ),
                'cta_link'        => get_permalink(),
                'cta_target'      => '_self',
                'has_audio'       => $has_audio,
                'read_time'       => $read_time,
                'author_name'     => get_the_author(),
                'type'            => $post_type,
                'post_date'       => get_the_date( 'U' ), // Timestamp للترتيب
            );

            // إضافة التقييم للمراجعات
            if ( $rating ) {
                $slide_data['rating'] = $rating;
            }

            $slides[] = $slide_data;
        }
        wp_reset_postdata();
    }

    return $slides;
}

/**
 * دالة للحصول على نص CTA حسب نوع المحتوى
 */
function nadiim_get_hero_cta_text( $post_type ) {
    $cta_texts = array(
        'post'    => __( 'اقرأ المزيد', 'nadiim' ),
        'howarat' => __( 'شاهد الحوار', 'nadiim' ),
        'reviews' => __( 'اقرأ المراجعة', 'nadiim' ),
    );

    return isset( $cta_texts[ $post_type ] ) ? $cta_texts[ $post_type ] : __( 'اقرأ المزيد', 'nadiim' );
}

/**
 * دالة للحصول على شرائح مختلطة (مقالات + حوارات + مراجعات)
 */
function nadiim_get_hero_slides_mixed() {
    $posts_count    = get_theme_mod( 'hero_mixed_posts_count', 2 );
    $howarat_count  = get_theme_mod( 'hero_mixed_howarat_count', 2 );
    $reviews_count  = get_theme_mod( 'hero_mixed_reviews_count', 1 );
    $order_type     = get_theme_mod( 'hero_mixed_order', 'date' );

    $all_slides = array();

    // جلب المقالات
    if ( $posts_count > 0 ) {
        $posts_slides = nadiim_get_hero_slides_from_posts( 'post', $posts_count );
        $all_slides = array_merge( $all_slides, $posts_slides );
    }

    // جلب الحوارات
    if ( $howarat_count > 0 ) {
        $howarat_slides = nadiim_get_hero_slides_from_posts( 'howarat', $howarat_count );
        $all_slides = array_merge( $all_slides, $howarat_slides );
    }

    // جلب المراجعات
    if ( $reviews_count > 0 ) {
        $reviews_slides = nadiim_get_hero_slides_from_posts( 'reviews', $reviews_count );
        $all_slides = array_merge( $all_slides, $reviews_slides );
    }

    // ترتيب الشرائح
    if ( $order_type === 'random' ) {
        shuffle( $all_slides );
    } elseif ( $order_type === 'date' ) {
        // ترتيب حسب التاريخ (الأحدث أولاً)
        usort( $all_slides, function( $a, $b ) {
            return $b['post_date'] - $a['post_date'];
        });
    }

    return $all_slides;
}

/**
 * دالة للحصول على شرائح يدوية من JSON
 */
function nadiim_get_hero_slides_manual() {
    $json_string = get_theme_mod( 'hero_slides_json', '' );

    if ( empty( $json_string ) ) {
        return array();
    }

    $slides = json_decode( $json_string, true );

    if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $slides ) ) {
        return array();
    }

    return $slides;
}

/**
 * دالة للحصول على شرائح demo
 */
function nadiim_get_hero_slides_demo() {
    $demo_file = get_template_directory() . '/demo/hero-demo.json';

    if ( ! file_exists( $demo_file ) ) {
        return array();
    }

    $json_string = file_get_contents( $demo_file );
    $slides = json_decode( $json_string, true );

    if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $slides ) ) {
        return array();
    }

    return $slides;
}
