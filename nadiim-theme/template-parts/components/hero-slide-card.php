<?php
/**
 * Template part لبطاقة Hero Slide
 *
 * @package Nadiim
 * @since 1.0.0
 *
 * تصميم: صورة خلفية + تدرج ألوان + محتوى في الأسفل
 */

if ( ! isset( $slide ) || ! is_array( $slide ) ) {
    return;
}

// Defaults
$defaults = array(
    'title'     => '',
    'excerpt'   => '',
    'image'     => '',
    'cta_text'  => __( 'اقرأ المزيد', 'nadiim' ),
    'cta_link'  => '#',
    'cta_target'=> '_self',
);

$slide = wp_parse_args( $slide, $defaults );

// Background image style
$bg_style = '';
if ( ! empty( $slide['image'] ) ) {
    $bg_style = sprintf( 'background-image: url(%s);', esc_url( $slide['image'] ) );
}
?>

<div class="swiper-slide" role="group" aria-roledescription="<?php esc_attr_e( 'شريحة', 'nadiim' ); ?>">
    <article class="hero-slide-card"
             style="<?php echo esc_attr( $bg_style ); ?>"
             tabindex="0">

        <!-- تدرج الألوان من الأسفل للأعلى -->
        <div class="slide-gradient-overlay" aria-hidden="true"></div>

        <!-- المحتوى في الأسفل -->
        <div class="slide-content">

            <?php if ( ! empty( $slide['title'] ) ) : ?>
                <h2 class="slide-title">
                    <?php echo esc_html( $slide['title'] ); ?>
                </h2>
            <?php endif; ?>

            <?php
            // عرض التقييم بالنجوم للمراجعات
            if ( ! empty( $slide['rating'] ) && absint( $slide['rating'] ) > 0 ) :
                $rating = absint( $slide['rating'] );
                ?>
                <div class="slide-rating" aria-label="<?php echo esc_attr( sprintf( __( 'التقييم: %s من 5', 'nadiim' ), $rating ) ); ?>">
                    <?php
                    // عرض النجوم (5 نجوم)
                    for ( $i = 1; $i <= 5; $i++ ) :
                        if ( $i <= $rating ) :
                            // نجمة ممتلئة
                            ?>
                            <svg class="star-icon star-filled" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        <?php else : ?>
                            <!-- نجمة فارغة -->
                            <svg class="star-icon star-empty" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M22 9.24l-7.19-.62L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24zM12 15.4l-3.76 2.27 1-4.28-3.32-2.88 4.38-.38L12 6.1l1.71 4.04 4.38.38-3.32 2.88 1 4.28L12 15.4z"/>
                            </svg>
                        <?php
                        endif;
                    endfor;
                    ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $slide['excerpt'] ) ) : ?>
                <p class="slide-excerpt">
                    <?php echo esc_html( $slide['excerpt'] ); ?>
                </p>
            <?php endif; ?>

            <?php if ( ! empty( $slide['cta_text'] ) && ! empty( $slide['cta_link'] ) ) : ?>
                <div class="slide-cta">
                    <a href="<?php echo esc_url( $slide['cta_link'] ); ?>"
                       class="cta-button"
                       target="<?php echo esc_attr( $slide['cta_target'] ); ?>"
                       <?php if ( $slide['cta_target'] === '_blank' ) : ?>
                       rel="noopener noreferrer"
                       <?php endif; ?>>
                        <?php echo esc_html( $slide['cta_text'] ); ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

        </div><!-- .slide-content -->

    </article>
</div><!-- .swiper-slide -->
