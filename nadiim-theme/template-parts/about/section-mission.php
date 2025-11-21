<?php
/**
 * Template part for displaying About Mission section
 *
 * @package Nadiim
 * @since 1.0.0
 */

// Get mission settings
$mission_title = get_theme_mod('about_mission_title', 'رسالتنا');
$mission_text = get_theme_mod('about_mission_text', '');

// Default demo text if empty
if (empty($mission_text)) {
    $mission_text = '<p>نحن مبادرة ثقافية تهدف إلى نشر الوعي وتعزيز القراءة والمعرفة في المجتمع العربي. نؤمن بأن الثقافة هي أساس التقدم والتطور.</p>';
}
?>

<section class="about-mission" style="padding: 80px 0; background: #fff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">

        <?php if ($mission_title) : ?>
            <h2 class="section-title" style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; text-align: center; margin-bottom: 48px; color: var(--color-primary, #339063);">
                <?php echo esc_html($mission_title); ?>
            </h2>
        <?php endif; ?>

        <?php if ($mission_text) : ?>
            <div class="mission-content" style="max-width: 900px; margin: 0 auto 64px; font-size: 1.125rem; line-height: 2; color: var(--color-text-secondary, #555);">
                <?php echo wp_kses_post($mission_text); ?>
            </div>
        <?php endif; ?>

        <?php
        // Get values/principles (up to 3)
        $values = array();
        for ($i = 1; $i <= 3; $i++) {
            $value_title = get_theme_mod("about_value_{$i}_title", '');
            $value_text = get_theme_mod("about_value_{$i}_text", '');
            $value_icon = get_theme_mod("about_value_{$i}_icon", '');

            if ($value_title || $value_text) {
                $values[] = array(
                    'title' => $value_title,
                    'text' => $value_text,
                    'icon' => $value_icon,
                );
            }
        }

        // Add demo values if empty
        if (empty($values)) {
            $values = array(
                array(
                    'title' => 'الأصالة',
                    'text' => 'نحافظ على القيم والتراث الثقافي العربي الأصيل',
                    'icon' => '📚',
                ),
                array(
                    'title' => 'الإبداع',
                    'text' => 'نشجع الإبداع والابتكار في تقديم المحتوى الثقافي',
                    'icon' => '✨',
                ),
                array(
                    'title' => 'المجتمع',
                    'text' => 'نبني مجتمعاً متفاعلاً من المثقفين والقراء',
                    'icon' => '🤝',
                ),
            );
        }
        ?>

        <?php if (!empty($values)) : ?>
            <div class="values-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px;">
                <?php foreach ($values as $value) : ?>
                    <div class="value-card" style="text-align: center; padding: 40px 24px; background: linear-gradient(135deg, #f9fffe 0%, #f0faf6 100%); border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 4px solid var(--color-primary, #339063);">

                        <?php if (!empty($value['icon'])) : ?>
                            <div class="value-icon" style="font-size: 3rem; margin-bottom: 16px;">
                                <?php
                                // Check if it's emoji or SVG/icon class
                                if (preg_match('/^[\x{1F300}-\x{1F9FF}]/u', $value['icon'])) {
                                    echo $value['icon'];
                                } else {
                                    echo wp_kses_post($value['icon']);
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($value['title'])) : ?>
                            <h3 class="value-title" style="font-size: 1.5rem; font-weight: 600; margin-bottom: 12px; color: var(--color-text);">
                                <?php echo esc_html($value['title']); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!empty($value['text'])) : ?>
                            <p class="value-text" style="font-size: 1rem; line-height: 1.7; color: var(--color-text-secondary, #666);">
                                <?php echo esc_html($value['text']); ?>
                            </p>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
