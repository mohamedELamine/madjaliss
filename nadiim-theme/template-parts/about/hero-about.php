<?php
/**
 * Template part for displaying About Hero section
 *
 * @package Nadiim
 * @since 1.0.0
 */

// Check if hero is enabled
$hero_enable = get_theme_mod('about_hero_enable', true);
if (!$hero_enable) {
    return;
}

// Get hero settings
$hero_title = get_theme_mod('about_hero_title', get_the_title());
$hero_lead = get_theme_mod('about_hero_lead', '');
$hero_cta_text = get_theme_mod('about_hero_cta_text', 'انضم معنا');
$hero_cta_link = get_theme_mod('about_hero_cta_link', '#');
$hero_bg_type = get_theme_mod('about_hero_bg_type', 'color');
$hero_bg_color = get_theme_mod('about_hero_bg_color', '#F6FFF9');
$hero_bg_image = get_theme_mod('about_hero_bg_image', '');
$hero_overlay = get_theme_mod('about_hero_overlay', 0.3);

// Build hero styles
$hero_styles = '';
if ($hero_bg_type === 'image' && $hero_bg_image) {
    $hero_styles = 'background-image: url(' . esc_url($hero_bg_image) . '); background-size: cover; background-position: center;';
} else {
    $hero_styles = 'background: linear-gradient(135deg, ' . esc_attr($hero_bg_color) . ' 0%, ' . esc_attr($hero_bg_color) . 'ee 100%);';
}
?>

<section class="about-hero" style="<?php echo $hero_styles; ?> position: relative; padding: 120px 0 80px;">
    <?php if ($hero_bg_type === 'image' && $hero_bg_image) : ?>
        <div class="about-hero__overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, <?php echo esc_attr($hero_overlay); ?>);"></div>
    <?php endif; ?>

    <div class="container" style="position: relative; z-index: 2; max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div class="about-hero__content" style="max-width: 800px; margin: 0 auto; text-align: center;">

            <?php if ($hero_title) : ?>
                <h1 class="about-hero__title" style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 700; margin-bottom: 24px; line-height: 1.2; <?php echo ($hero_bg_type === 'image' ? 'color: #fff;' : 'color: var(--color-text);'); ?>">
                    <?php echo wp_kses_post($hero_title); ?>
                </h1>
            <?php endif; ?>

            <?php if ($hero_lead) : ?>
                <div class="about-hero__lead" style="font-size: clamp(1.125rem, 2vw, 1.375rem); line-height: 1.8; margin-bottom: 40px; <?php echo ($hero_bg_type === 'image' ? 'color: rgba(255,255,255,0.95);' : 'color: var(--color-text-secondary);'); ?>">
                    <?php echo wp_kses_post(wpautop($hero_lead)); ?>
                </div>
            <?php endif; ?>

            <?php if ($hero_cta_text && $hero_cta_link) : ?>
                <div class="about-hero__cta">
                    <a href="<?php echo esc_url($hero_cta_link); ?>"
                       class="btn btn-primary"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 32px; background: var(--color-primary, #339063); color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.125rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(51, 144, 99, 0.3);">
                        <?php echo esc_html($hero_cta_text); ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
