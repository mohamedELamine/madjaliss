<?php
/**
 * Template part لبطاقة Hero Slide
 *
 * @package Nadiim
 * @since 1.0.0
 *
 * المتغيرات المطلوبة:
 * - $slide: array مع البيانات التالية:
 *   - title: العنوان
 *   - excerpt: المقتطف
 *   - image: رابط الصورة
 *   - bg_enable: استخدام الصورة كخلفية
 *   - overlay_opacity: شفافية الـ overlay
 *   - cta_text: نص الـ CTA
 *   - cta_link: رابط الـ CTA
 *   - cta_target: هدف الرابط
 *   - has_audio: وجود صوت
 *   - read_time: وقت القراءة
 *   - author_name: اسم المؤلف
 *   - type: نوع المحتوى
 */

if ( ! isset( $slide ) || ! is_array( $slide ) ) {
    return;
}

// Defaults
$defaults = array(
    'title'            => '',
    'excerpt'          => '',
    'image'            => '',
    'bg_enable'        => false,
    'overlay_opacity'  => get_theme_mod( 'hero_overlay_default_opacity', 0.35 ),
    'cta_text'         => __( 'اقرأ المزيد', 'nadiim' ),
    'cta_link'         => '#',
    'cta_target'       => '_self',
    'has_audio'        => false,
    'read_time'        => '',
    'author_name'      => '',
    'type'             => 'post',
);

$slide = wp_parse_args( $slide, $defaults );

// CSS classes
$card_classes = array( 'hero-slide-card' );
if ( $slide['bg_enable'] ) {
    $card_classes[] = 'has-background';
}

$text_scheme = get_theme_mod( 'hero_text_color_scheme', 'auto' );
if ( $text_scheme !== 'auto' ) {
    $card_classes[] = $text_scheme === 'light' ? 'light-text' : 'dark-text';
}

// Inline styles لخلفية الشريحة
$card_styles = '';
if ( $slide['bg_enable'] && ! empty( $slide['image'] ) ) {
    $card_styles = sprintf(
        'background-image: url(%s);',
        esc_url( $slide['image'] )
    );
}

// Overlay styles
$overlay_styles = sprintf(
    'background-color: rgba(0, 0, 0, %s);',
    floatval( $slide['overlay_opacity'] )
);
?>

<div class="swiper-slide" role="group" aria-roledescription="<?php esc_attr_e( 'شريحة', 'nadiim' ); ?>">
    <article class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>"
             <?php if ( $card_styles ) : ?>
             style="<?php echo esc_attr( $card_styles ); ?>"
             <?php endif; ?>
             tabindex="0">

        <?php if ( $slide['bg_enable'] && ! empty( $slide['image'] ) ) : ?>
            <!-- Overlay للخلفية -->
            <div class="slide-bg-overlay" style="<?php echo esc_attr( $overlay_styles ); ?>" aria-hidden="true"></div>
        <?php endif; ?>

        <div class="slide-content-wrapper">

            <?php if ( ! $slide['bg_enable'] && ! empty( $slide['image'] ) ) : ?>
                <!-- صورة الشريحة (ليست خلفية) -->
                <div class="slide-image">
                    <img src="<?php echo esc_url( $slide['image'] ); ?>"
                         alt="<?php echo esc_attr( $slide['title'] ); ?>"
                         loading="lazy" />
                </div>
            <?php endif; ?>

            <!-- محتوى الشريحة -->
            <div class="slide-content">

                <?php if ( ! empty( $slide['title'] ) ) : ?>
                    <h2 class="slide-title">
                        <?php if ( ! empty( $slide['cta_link'] ) && $slide['cta_link'] !== '#' ) : ?>
                            <a href="<?php echo esc_url( $slide['cta_link'] ); ?>"
                               target="<?php echo esc_attr( $slide['cta_target'] ); ?>">
                                <?php echo esc_html( $slide['title'] ); ?>
                            </a>
                        <?php else : ?>
                            <?php echo esc_html( $slide['title'] ); ?>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>

                <?php if ( ! empty( $slide['excerpt'] ) ) : ?>
                    <p class="slide-excerpt">
                        <?php echo esc_html( $slide['excerpt'] ); ?>
                    </p>
                <?php endif; ?>

                <!-- Meta Information -->
                <?php if ( ! empty( $slide['author_name'] ) || ! empty( $slide['read_time'] ) || $slide['has_audio'] ) : ?>
                    <div class="slide-meta">
                        <?php if ( ! empty( $slide['author_name'] ) ) : ?>
                            <span class="meta-author">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                <?php echo esc_html( $slide['author_name'] ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! empty( $slide['read_time'] ) ) : ?>
                            <span class="meta-time">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/>
                                </svg>
                                <?php echo esc_html( $slide['read_time'] ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $slide['has_audio'] ) : ?>
                            <span class="meta-audio">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                                </svg>
                                <?php esc_html_e( 'يتضمن صوت', 'nadiim' ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- CTA Button -->
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

        </div><!-- .slide-content-wrapper -->

    </article>
</div><!-- .swiper-slide -->
