<?php
/**
 * قالب الصفحة الرئيسية
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main home-page">

    <?php
    // قسم Hero
    if ( get_theme_mod( 'nadiim_hero_enable', true ) ) :
        $hero_title = get_theme_mod( 'nadiim_hero_title', __( 'مرحباً بكم في نديم', 'nadiim' ) );
        $hero_desc = get_theme_mod( 'nadiim_hero_description', __( 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة', 'nadiim' ) );
        $hero_button_text = get_theme_mod( 'nadiim_hero_button_text', __( 'استكشف المحتوى', 'nadiim' ) );
        $hero_button_url = get_theme_mod( 'nadiim_hero_button_url', '#' );
        $hero_bg_color = get_theme_mod( 'nadiim_hero_bg_color', '#f5f5f5' );
        $hero_bg_image = get_theme_mod( 'nadiim_hero_bg_image' );
        ?>
        <section class="hero-section" style="background-color: <?php echo esc_attr( $hero_bg_color ); ?>; <?php if ( $hero_bg_image ) : ?>background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $hero_bg_image, 'full' ) ); ?>); background-size: cover; background-position: center;<?php endif; ?> padding: var(--spacing-xxl) 0; position: relative;">
            <div class="container text-center">
                <h1 class="hero-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md);">
                    <?php echo esc_html( $hero_title ); ?>
                </h1>
                <p class="hero-description" style="font-size: var(--font-size-xl); color: var(--color-text-secondary); max-width: 700px; margin: 0 auto var(--spacing-lg);">
                    <?php echo esc_html( $hero_desc ); ?>
                </p>
                <?php if ( $hero_button_url && $hero_button_text ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary btn-lg">
                        <?php echo esc_html( $hero_button_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // قسم الحوارات المميزة
    if ( get_theme_mod( 'nadiim_dialogues_enable', true ) ) :
        $dialogues_count = get_theme_mod( 'nadiim_dialogues_count', 3 );
        $dialogues_title = get_theme_mod( 'nadiim_dialogues_title', __( 'أحدث الحوارات', 'nadiim' ) );

        $dialogues_query = new WP_Query( array(
            'post_type'      => 'howarat',
            'posts_per_page' => $dialogues_count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $dialogues_query->have_posts() ) :
            ?>
            <section class="dialogues-section section">
                <div class="container">
                    <div class="section-title">
                        <h2><?php echo esc_html( $dialogues_title ); ?></h2>
                        <p class="section-description"><?php esc_html_e( 'حواراتٌ رصينة مع أهل العلم والفكر، نستمع فيها إلى أصواتٍ متنوعة وأفكارٍ عميقة', 'nadiim' ); ?></p>
                    </div>
                    <div class="grid grid-3">
                        <?php while ( $dialogues_query->have_posts() ) : $dialogues_query->the_post();
                            get_template_part( 'template-parts/content', 'howarat-card' );
                        endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                    <div class="text-center" style="margin-top: var(--spacing-lg);">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'howarat' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'جميع الحوارات', 'nadiim' ); ?>
                        </a>
                    </div>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <?php
    // قسم الفعاليات المنتهية
    if ( get_theme_mod( 'nadiim_events_enable', true ) ) :
        $events_count = get_theme_mod( 'nadiim_events_count', 3 );
        $events_title = get_theme_mod( 'nadiim_events_title', __( 'الفعاليات', 'nadiim' ) );

        // عرض الفعاليات المنتهية فقط
        $past_events_query = new WP_Query( array(
            'post_type'      => 'events',
            'posts_per_page' => $events_count,
            'meta_key'       => 'event_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'DESC',
            'meta_query'     => array(
                array(
                    'key'     => 'event_start_date',
                    'value'   => current_time( 'Y-m-d H:i' ),
                    'compare' => '<',
                    'type'    => 'DATETIME',
                ),
            ),
        ) );

        if ( $past_events_query->have_posts() ) :
            ?>
            <section class="events-section section" style="background-color: var(--color-bg-section);">
                <div class="container">
                    <div class="section-title">
                        <h2><?php echo esc_html( $events_title ); ?></h2>
                        <p class="section-description"><?php esc_html_e( 'فعالياتٌ ماضية نظمناها، شاركنا فيها بالأفكار والمناقشات الثرية', 'nadiim' ); ?></p>
                    </div>
                    <div class="grid grid-3" style="gap: var(--spacing-lg);">
                        <?php while ( $past_events_query->have_posts() ) : $past_events_query->the_post(); ?>
                            <article <?php post_class( 'card event-card' ); ?> style="border-radius: var(--radius-xl); overflow: hidden; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: all 0.4s ease; position: relative; height: 350px; display: flex; flex-direction: column;">
                                <a href="<?php the_permalink(); ?>" style="position: relative; flex: 1; display: block; overflow: hidden;">
                                    <!-- الصورة البارزة -->
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'nadiim-card', array(
                                            'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;',
                                            'class' => 'event-cover-image'
                                        ) ); ?>
                                    <?php else : ?>
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); display: flex; align-items: center; justify-content: center; font-size: 64px; color: rgba(255,255,255,0.3);">
                                            📅
                                        </div>
                                    <?php endif; ?>

                                    <!-- تدرج لوني وعنوان الفعالية -->
                                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.85) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: var(--spacing-lg);">
                                        <?php
                                        $event_start_date = get_post_meta( get_the_ID(), 'event_start_date', true );
                                        if ( $event_start_date ) :
                                        ?>
                                            <div style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); color: #fff; padding: 6px 12px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; align-self: flex-start; margin-bottom: var(--spacing-sm);">
                                                📅 <?php echo date_i18n( 'j F Y', strtotime( $event_start_date ) ); ?>
                                            </div>
                                        <?php endif; ?>

                                        <h3 style="color: #fff; font-size: var(--font-size-xl); font-weight: 700; margin: 0; line-height: 1.3; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                            <?php the_title(); ?>
                                        </h3>

                                        <?php
                                        $event_location = get_post_meta( get_the_ID(), 'event_location', true );
                                        if ( $event_location ) :
                                        ?>
                                            <p style="color: rgba(255,255,255,0.9); font-size: 14px; margin: var(--spacing-xs) 0 0 0; text-shadow: 0 1px 5px rgba(0,0,0,0.5);">
                                                📍 <?php echo esc_html( $event_location ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                    <div class="text-center" style="margin-top: var(--spacing-xl);">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'events' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'المزيد من الفعاليات', 'nadiim' ); ?>
                        </a>
                    </div>
                </div>
            </section>

            <style>
            .event-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            }

            .event-card:hover .event-cover-image {
                transform: scale(1.08);
            }
            </style>
        <?php endif;
    endif; ?>

    <?php
    // قسم الإصدارات
    if ( get_theme_mod( 'nadiim_releases_enable', true ) ) :
        $releases_count = get_theme_mod( 'nadiim_releases_count', 4 );
        $releases_title = get_theme_mod( 'nadiim_releases_title', __( 'أحدث الإصدارات', 'nadiim' ) );

        $releases_query = new WP_Query( array(
            'post_type'      => 'esdar',
            'posts_per_page' => $releases_count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $releases_query->have_posts() ) :
            ?>
            <section class="releases-section section" style="background-color: var(--color-bg-section);">
                <div class="container">
                    <div class="section-title">
                        <h2><?php echo esc_html( $releases_title ); ?></h2>
                        <p class="section-description"><?php esc_html_e( 'كتبٌ ونشراتٌ وبحوث منتقاة بعناية، تثري العقل وتغذي الروح', 'nadiim' ); ?></p>
                    </div>
                    <div class="grid grid-4" style="gap: var(--spacing-lg);">
                        <?php while ( $releases_query->have_posts() ) : $releases_query->the_post(); ?>
                            <article <?php post_class( 'card release-card' ); ?> style="border-radius: var(--radius-lg); overflow: hidden; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: all 0.4s ease; position: relative; height: 400px; display: flex; flex-direction: column;">
                                <a href="<?php the_permalink(); ?>" style="position: relative; flex: 1; display: block; overflow: hidden;">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'nadiim-card', array(
                                            'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;',
                                            'class' => 'release-cover-image'
                                        ) ); ?>
                                    <?php else : ?>
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); display: flex; align-items: center; justify-content: center; font-size: 64px; color: rgba(255,255,255,0.3);">
                                            📚
                                        </div>
                                    <?php endif; ?>

                                    <!-- تدرج لوني في الأسفل -->
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 50%; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 50%, transparent 100%); padding: var(--spacing-lg); display: flex; flex-direction: column; justify-content: flex-end;">
                                        <h3 style="color: #fff; font-size: var(--font-size-lg); font-weight: 700; margin: 0 0 var(--spacing-xs) 0; line-height: 1.3; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                            <?php the_title(); ?>
                                        </h3>
                                        <?php
                                        $author = get_post_meta( get_the_ID(), 'release_author', true );
                                        if ( $author ) : ?>
                                            <p style="color: rgba(255,255,255,0.9); font-size: 14px; margin: 0; text-shadow: 0 1px 5px rgba(0,0,0,0.5);">
                                                <?php echo esc_html( $author ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                    <div class="text-center" style="margin-top: var(--spacing-xl);">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'جميع الإصدارات', 'nadiim' ); ?>
                        </a>
                    </div>
                </div>
            </section>

            <style>
            .release-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            }

            .release-card:hover .release-cover-image {
                transform: scale(1.08);
            }
            </style>
        <?php endif;
    endif; ?>

    <?php
    // قسم المقالات - سلايدر
    if ( get_theme_mod( 'nadiim_posts_enable', true ) ) :
        $posts_title = get_theme_mod( 'nadiim_posts_title', __( 'أحدث المقالات', 'nadiim' ) );

        $posts_query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $posts_query->have_posts() ) :
            ?>
            <section class="posts-section section">
                <div class="container">
                    <!-- عنوان القسم -->
                    <div class="section-title" style="text-align: center; margin-bottom: var(--spacing-xl);">
                        <h2><?php echo esc_html( $posts_title ); ?></h2>
                    </div>

                    <!-- السلايدر - عرض كامل -->
                    <div class="posts-slider swiper">
                        <div class="swiper-wrapper">
                            <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                                <div class="swiper-slide">
                                    <article class="post-slide" style="position: relative; height: 500px; border-radius: var(--radius-2xl); overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.12);">
                                            <!-- الصورة البارزة كخلفية -->
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div style="position: absolute; inset: 0; z-index: 0;">
                                                    <?php the_post_thumbnail( 'full', array(
                                                        'style' => 'width: 100%; height: 100%; object-fit: cover;'
                                                    ) ); ?>
                                                    <!-- طبقة شفافة -->
                                                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.7) 100%);"></div>
                                                </div>
                                            <?php else : ?>
                                                <!-- خلفية افتراضية -->
                                                <div style="position: absolute; inset: 0; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); opacity: 0.7;"></div>
                                            <?php endif; ?>

                                            <!-- المحتوى -->
                                            <div style="position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: var(--spacing-xl); text-align: center; color: #fff;">
                                                <!-- العنوان في المنتصف -->
                                                <h3 style="font-size: clamp(1.5rem, 3vw, 2.5rem); font-weight: 700; margin-bottom: var(--spacing-md); color: #fff; text-shadow: 0 2px 15px rgba(0,0,0,0.5); line-height: 1.3; max-width: 90%;">
                                                    <?php the_title(); ?>
                                                </h3>

                                                <!-- مقتطف صغير -->
                                                <p style="font-size: var(--font-size-lg); color: rgba(255,255,255,0.95); margin-bottom: var(--spacing-lg); max-width: 80%; line-height: 1.6; text-shadow: 0 1px 10px rgba(0,0,0,0.5);">
                                                    <?php echo nadiim_get_excerpt( 20 ); ?>
                                                </p>

                                                <!-- زر اقرأ من هنا -->
                                                <a href="<?php the_permalink(); ?>" class="btn" style="background: #fff; color: var(--color-primary); border: none; padding: 14px 32px; font-weight: 600; box-shadow: 0 4px 20px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                    <?php esc_html_e( 'اقرأ من هنا', 'nadiim' ); ?>
                                                    <span style="margin-right: 8px;">←</span>
                                                </a>
                                            </div>
                                        </article>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </div>

                            <!-- أزرار التنقل -->
                            <div class="swiper-button-next" style="color: #fff; filter: drop-shadow(0 2px 10px rgba(0,0,0,0.5));"></div>
                            <div class="swiper-button-prev" style="color: #fff; filter: drop-shadow(0 2px 10px rgba(0,0,0,0.5));"></div>

                            <!-- النقاط -->
                            <div class="swiper-pagination" style="bottom: 20px;"></div>
                        </div>
                    </div>

                    <!-- زر المزيد -->
                    <div class="text-center" style="margin-top: var(--spacing-xl);">
                        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'المزيد من المقالات', 'nadiim' ); ?>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Swiper CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const postsSwiper = new Swiper('.posts-slider', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    speed: 800,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            });
            </script>

            <style>
            .post-slide .btn:hover {
                background: var(--color-primary);
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 6px 25px rgba(0,0,0,0.4);
            }

            .swiper-pagination-bullet {
                background: #fff;
                opacity: 0.5;
            }

            .swiper-pagination-bullet-active {
                opacity: 1;
                background: #fff;
            }

            /* تجاوب مع الشاشات الصغيرة */
            @media (max-width: 768px) {
                .post-slide {
                    height: 450px !important;
                }

                .post-slide h3 {
                    font-size: 1.5rem !important;
                }
            }

            @media (max-width: 480px) {
                .post-slide {
                    height: 400px !important;
                }
            }
            </style>
        <?php endif;
    endif; ?>

    <?php
    // قسم نوادي القراءة
    if ( get_theme_mod( 'nadiim_clubs_enable', true ) ) :
        $clubs_count = get_theme_mod( 'nadiim_clubs_count', 3 );
        $clubs_title = get_theme_mod( 'nadiim_clubs_title', __( 'نوادي القراءة', 'nadiim' ) );

        $clubs_query = new WP_Query( array(
            'post_type'      => 'reading_clubs',
            'posts_per_page' => $clubs_count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $clubs_query->have_posts() ) :
            ?>
            <section class="clubs-section section" style="background-color: var(--color-bg-section);">
                <div class="container">
                    <div class="section-title">
                        <h2><?php echo esc_html( $clubs_title ); ?></h2>
                        <p class="section-description"><?php esc_html_e( 'مجتمعاتٌ هادئة للقراءة والنقاش، نجتمع فيها على حب الكتب وتبادل الأفكار', 'nadiim' ); ?></p>
                    </div>
                    <div class="grid grid-3">
                        <?php while ( $clubs_query->have_posts() ) : $clubs_query->the_post(); ?>
                            <article <?php post_class( 'card' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-content">
                                    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="card-excerpt"><?php echo nadiim_get_excerpt( 15 ); ?></div>
                                    <?php echo nadiim_read_more_link( __( 'تفاصيل النادي', 'nadiim' ) ); ?>
                                </div>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                    <div class="text-center" style="margin-top: var(--spacing-lg);">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'reading_clubs' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'جميع النوادي', 'nadiim' ); ?>
                        </a>
                    </div>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <?php
    // قسم النشرة البريدية
    if ( get_theme_mod( 'nadiim_newsletter_enable', true ) ) :
        $newsletter_title = get_theme_mod( 'nadiim_newsletter_title', __( 'اشترك في نشرتنا البريدية', 'nadiim' ) );
        $newsletter_desc = get_theme_mod( 'nadiim_newsletter_description', __( 'تلقَّ آخر الأخبار والإصدارات والفعاليات مباشرة في بريدك', 'nadiim' ) );
        ?>
        <section class="newsletter-section section" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); color: #fff;">
            <div class="container text-center">
                <h2 style="color: #fff; margin-bottom: var(--spacing-sm);"><?php echo esc_html( $newsletter_title ); ?></h2>
                <p style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg); margin-bottom: var(--spacing-lg); max-width: 600px; margin-left: auto; margin-right: auto;">
                    <?php echo esc_html( $newsletter_desc ); ?>
                </p>
                <form class="newsletter-form" style="max-width: 500px; margin: 0 auto; display: flex; gap: var(--spacing-sm);">
                    <input type="email" placeholder="<?php esc_attr_e( 'بريدك الإلكتروني', 'nadiim' ); ?>" required style="flex: 1; padding: 12px 20px; border: none; border-radius: var(--radius-md); font-size: var(--font-size-base);">
                    <button type="submit" class="btn" style="background: #fff; color: var(--color-primary); border: none; padding: 12px 32px; font-weight: 600;">
                        <?php esc_html_e( 'اشترك', 'nadiim' ); ?>
                    </button>
                </form>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php
get_footer();
