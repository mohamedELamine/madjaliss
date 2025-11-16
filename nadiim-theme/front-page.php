<?php
/**
 * قالب الصفحة الرئيسية (Front Page)
 *
 * هذا القالب له الأولوية الأعلى في التسلسل الهرمي لقوالب WordPress
 * ويُستخدم لعرض الصفحة الرئيسية عندما يتم تعيين صفحة ثابتة كصفحة رئيسية
 *
 * يعرض هذا القالب:
 * - قسم Hero الترحيبي
 * - أحدث الحوارات
 * - أحدث الإصدارات
 * - عينة من المقالات
 * - نوادي القراءة
 * - نموذج النشرة البريدية
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main home-page front-page">

    <?php
    // قسم Hero الترحيبي
    if ( get_theme_mod( 'nadiim_hero_enable', true ) ) :
        $hero_title = get_theme_mod( 'nadiim_hero_title', __( 'مرحباً بكم في نديم', 'nadiim' ) );
        $hero_desc = get_theme_mod( 'nadiim_hero_description', __( 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة', 'nadiim' ) );
        $hero_button_text = get_theme_mod( 'nadiim_hero_button_text', __( 'استكشف المحتوى', 'nadiim' ) );
        $hero_button_url = get_theme_mod( 'nadiim_hero_button_url', '#' );
        $hero_bg_color = get_theme_mod( 'nadiim_hero_bg_color', '#f5f5f5' );
        $hero_bg_image = get_theme_mod( 'nadiim_hero_bg_image' );
        ?>
        <section class="hero-section" style="background: linear-gradient(135deg, <?php echo esc_attr( $hero_bg_color ); ?> 0%, #ffffff 100%); <?php if ( $hero_bg_image ) : ?>background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $hero_bg_image, 'full' ) ); ?>); background-size: cover; background-position: center; background-blend-mode: overlay;<?php endif; ?> padding: calc(var(--spacing-xxl) + 40px) 0 var(--spacing-xxl); position: relative; overflow: hidden;">
            <div class="hero-decoration" style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(51, 144, 99, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
            <div class="hero-decoration" style="position: absolute; bottom: -150px; right: -150px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(51, 144, 99, 0.08) 0%, transparent 70%); border-radius: 50%;"></div>
            <div class="container text-center" style="position: relative; z-index: 2;">
                <h1 class="hero-title" style="font-size: clamp(32px, 5vw, var(--font-size-3xl)); margin-bottom: var(--spacing-md); font-weight: 800; line-height: 1.3;">
                    <?php echo esc_html( $hero_title ); ?>
                </h1>
                <p class="hero-description" style="font-size: clamp(18px, 3vw, var(--font-size-xl)); color: var(--color-text-secondary); max-width: 700px; margin: 0 auto var(--spacing-lg); line-height: 1.8;">
                    <?php echo esc_html( $hero_desc ); ?>
                </p>
                <?php if ( $hero_button_url && $hero_button_text ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary" style="padding: 14px 40px; font-size: 18px; box-shadow: 0 4px 20px rgba(51, 144, 99, 0.2);">
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
                    <div class="grid grid-4">
                        <?php while ( $releases_query->have_posts() ) : $releases_query->the_post(); ?>
                            <article <?php post_class( 'card release-card' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-content">
                                    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <?php
                                    $author = get_post_meta( get_the_ID(), 'release_author', true );
                                    if ( $author ) : ?>
                                        <p style="color: var(--color-text-secondary); font-size: 14px;"><?php echo esc_html( $author ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                    <div class="text-center" style="margin-top: var(--spacing-lg);">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'جميع الإصدارات', 'nadiim' ); ?>
                        </a>
                    </div>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <?php
    // قسم المقالات
    if ( get_theme_mod( 'nadiim_posts_enable', true ) ) :
        $posts_count = get_theme_mod( 'nadiim_posts_count', 3 );
        $posts_title = get_theme_mod( 'nadiim_posts_title', __( 'آخر المقالات', 'nadiim' ) );

        $posts_query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => $posts_count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $posts_query->have_posts() ) :
            ?>
            <section class="posts-section section">
                <div class="container">
                    <div class="section-title">
                        <h2><?php echo esc_html( $posts_title ); ?></h2>
                    </div>
                    <div class="grid grid-3">
                        <?php while ( $posts_query->have_posts() ) : $posts_query->the_post();
                            get_template_part( 'template-parts/content' );
                        endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </section>
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
        <section class="newsletter-section section" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); color: #fff; position: relative; overflow: hidden; padding: calc(var(--spacing-xl) + 40px) 0;">
            <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
            <div style="position: absolute; bottom: -80px; right: -80px; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; filter: blur(50px);"></div>
            <div class="container text-center" style="position: relative; z-index: 2;">
                <h2 style="color: #fff; margin-bottom: var(--spacing-sm); font-size: var(--font-size-2xl); font-weight: 800;"><?php echo esc_html( $newsletter_title ); ?></h2>
                <p style="color: rgba(255,255,255,0.95); font-size: var(--font-size-lg); margin-bottom: var(--spacing-lg); max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.8;">
                    <?php echo esc_html( $newsletter_desc ); ?>
                </p>
                <form class="newsletter-form" style="max-width: 500px; margin: 0 auto; display: flex; gap: var(--spacing-sm); flex-wrap: wrap; justify-content: center;">
                    <input type="email" placeholder="<?php esc_attr_e( 'بريدك الإلكتروني', 'nadiim' ); ?>" required style="flex: 1; min-width: 250px; padding: 14px 24px; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: var(--radius-md); font-size: var(--font-size-base); background: rgba(255, 255, 255, 0.15); color: #fff; backdrop-filter: blur(10px);" onfocus="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.borderColor='rgba(255, 255, 255, 0.5)';" onblur="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.borderColor='rgba(255, 255, 255, 0.3)';">
                    <button type="submit" class="btn" style="background: #fff; color: var(--color-primary); border: none; padding: 14px 36px; font-weight: 700; border-radius: var(--radius-md); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.2)';">
                        <?php esc_html_e( 'اشترك', 'nadiim' ); ?>
                    </button>
                </form>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php
get_footer();
