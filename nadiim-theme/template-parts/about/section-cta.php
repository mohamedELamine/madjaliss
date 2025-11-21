<?php
/**
 * Template part for displaying About CTA section
 *
 * @package Nadiim
 * @since 1.0.0
 */

// Get CTA settings
$cta_text = get_theme_mod('about_cta_text', '');
$cta_button_text = get_theme_mod('about_cta_button_text', '');
$cta_button_link = get_theme_mod('about_cta_button_link', '');
$cta_bg = get_theme_mod('about_cta_bg', '');

// Default values if empty
if (empty($cta_text)) {
    $cta_text = 'انضم إلينا في رحلة نشر الثقافة والمعرفة';
}

if (empty($cta_button_text)) {
    $cta_button_text = 'ابدأ الآن';
}

if (empty($cta_button_link)) {
    $cta_button_link = '#';
}

// Build background style
$bg_style = '';
if ($cta_bg) {
    $bg_style = 'background: ' . esc_attr($cta_bg) . ';';
} else {
    $bg_style = 'background: linear-gradient(135deg, var(--color-primary, #339063) 0%, rgba(51, 144, 99, 0.85) 100%);';
}
?>

<section class="about-cta" style="<?php echo $bg_style; ?> padding: 80px 0; position: relative; overflow: hidden;">

    <!-- Background Pattern (optional decoration) -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 2;">

        <div class="cta-content" style="max-width: 800px; margin: 0 auto; text-align: center;">

            <?php if ($cta_text) : ?>
                <h2 class="cta-title" style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; color: #fff; margin-bottom: 32px; line-height: 1.3;">
                    <?php echo wp_kses_post($cta_text); ?>
                </h2>
            <?php endif; ?>

            <?php if ($cta_button_text && $cta_button_link) : ?>
                <div class="cta-button">
                    <a href="<?php echo esc_url($cta_button_link); ?>"
                       class="btn btn-light"
                       style="display: inline-flex; align-items: center; gap: 12px; padding: 20px 48px; background: #fff; color: var(--color-primary, #339063); border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.25rem; transition: all 0.3s ease; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                        <?php echo esc_html($cta_button_text); ?>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

        </div>

    </div>

</section>

<style>
.about-cta .btn-light:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.25) !important;
}
</style>
