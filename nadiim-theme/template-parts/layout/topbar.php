<?php
/**
 * Top Bar Template
 *
 * @package Madjaliss
 * @version 2.0
 */

// الحصول على إعدادات Customizer
$topbar_bg_color = get_theme_mod('topbar_bg_color', '#1c2d27');
$topbar_text_color = get_theme_mod('topbar_text_color', '#ffffff');
$topbar_font_size = get_theme_mod('topbar_font_size', 14);
$topbar_alignment = get_theme_mod('topbar_alignment', 'right');
$topbar_icon = get_theme_mod('topbar_icon', 'megaphone');
$topbar_dynamic_enable = get_theme_mod('topbar_dynamic_enable', false);

// تحديد النص المراد عرضه
$topbar_content = '';

if ($topbar_dynamic_enable) {
    // جلب محتوى ديناميكي
    $post_type = get_theme_mod('topbar_dynamic_post_type', 'post');
    $tag = get_theme_mod('topbar_dynamic_tag', '');
    $limit = get_theme_mod('topbar_dynamic_limit', 1);

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($tag)) {
        $args['tag'] = $tag;
    }

    $recent_posts = get_posts($args);

    if (!empty($recent_posts)) {
        $post = $recent_posts[0];
        $topbar_content = sprintf(
            '<a href="%s" class="topbar-link">%s</a>',
            get_permalink($post->ID),
            esc_html($post->post_title)
        );
    }
} else {
    // النص الثابت
    $topbar_content = get_theme_mod('topbar_text', 'مرحباً بكم في مجالس - منصة الحوار والفكر');
}

// أيقونات SVG المتاحة
$icons = array(
    'megaphone' => '<svg class="topbar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>',
    'bell' => '<svg class="topbar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
    'calendar' => '<svg class="topbar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
    'info' => '<svg class="topbar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
    'star' => '<svg class="topbar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
);

// إذا كان هناك رفع أيقونة مخصصة
$custom_icon = get_theme_mod('topbar_custom_icon', '');
$icon_html = '';

if (!empty($custom_icon)) {
    $icon_html = sprintf('<img src="%s" alt="" class="topbar-icon topbar-icon-custom">', esc_url($custom_icon));
} elseif (isset($icons[$topbar_icon])) {
    $icon_html = $icons[$topbar_icon];
}

// إذا لم يكن هناك محتوى، لا نعرض شيء
if (empty($topbar_content)) {
    return;
}
?>

<div class="site-topbar" style="background-color: <?php echo esc_attr($topbar_bg_color); ?>; color: <?php echo esc_attr($topbar_text_color); ?>;">
    <div class="container">
        <div class="topbar-inner" style="text-align: <?php echo esc_attr($topbar_alignment); ?>; font-size: <?php echo esc_attr($topbar_font_size); ?>px;">
            <?php if (!empty($icon_html)) : ?>
                <span class="topbar-icon-wrapper">
                    <?php echo $icon_html; ?>
                </span>
            <?php endif; ?>

            <span class="topbar-content">
                <?php echo wp_kses_post($topbar_content); ?>
            </span>
        </div>
    </div>
</div>
