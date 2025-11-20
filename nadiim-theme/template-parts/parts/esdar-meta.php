<?php
/**
 * جزء عرض معلومات الإصدار (Metadata)
 *
 * Template part لعرض المعلومات التفصيلية للإصدار
 *
 * @package Nadiim
 */

$meta = nadiim_get_esdar_meta(get_the_ID());

if (!$meta) {
    return;
}
?>

<div class="esdar-metadata">

    <!-- تاريخ الإصدار -->
    <?php if (!empty($meta['release_date'])) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <?php _e('تاريخ الإصدار:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value">
                <time datetime="<?php echo esc_attr($meta['release_date']); ?>">
                    <?php echo date_i18n(get_option('date_format'), strtotime($meta['release_date'])); ?>
                </time>
            </span>
        </div>
    <?php endif; ?>

    <!-- نوع الإصدار -->
    <?php if (!empty($meta['release_type'])) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <?php echo nadiim_get_esdar_type_icon($meta['release_type']); ?>
                <?php _e('النوع:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value">
                <?php echo esc_html(nadiim_get_esdar_type_label($meta['release_type'])); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- عدد الصفحات -->
    <?php if (!empty($meta['release_pages'])) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <?php _e('عدد الصفحات:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value">
                <?php echo number_format_i18n($meta['release_pages']); ?> <?php _e('صفحة', 'nadiim'); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- اللغة -->
    <?php if (!empty($meta['release_language'])) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                <?php _e('اللغة:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value">
                <?php
                $langs = array(
                    'ar' => __('العربية', 'nadiim'),
                    'en' => __('الإنجليزية', 'nadiim'),
                    'fr' => __('الفرنسية', 'nadiim'),
                    'other' => __('أخرى', 'nadiim'),
                );
                echo isset($langs[$meta['release_language']]) ? esc_html($langs[$meta['release_language']]) : esc_html($meta['release_language']);
                ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- ISBN -->
    <?php if (!empty($meta['release_isbn'])) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M7 7h.01M7 12h.01M7 17h.01M12 7h5M12 12h5M12 17h5"/>
                </svg>
                <?php _e('ISBN:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value">
                <?php echo esc_html($meta['release_isbn']); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- السعر -->
    <?php if (!empty($meta['release_price'])) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                <?php _e('السعر:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value esdar-meta-price">
                <?php echo esc_html($meta['release_price']); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- الصيغ المتوفرة -->
    <?php if (!empty($meta['release_format'])) : ?>
        <div class="esdar-meta-item esdar-meta-formats">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                    <polyline points="13 2 13 9 20 9"/>
                </svg>
                <?php _e('الصيغ:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value">
                <?php
                foreach ($meta['release_format'] as $format) {
                    echo '<span class="esdar-format-badge">' . esc_html(strtoupper($format)) . '</span> ';
                }
                ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- عدد التحميلات -->
    <?php if (isset($meta['release_download_count']) && $meta['release_download_count'] > 0) : ?>
        <div class="esdar-meta-item">
            <span class="esdar-meta-label">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <?php _e('التحميلات:', 'nadiim'); ?>
            </span>
            <span class="esdar-meta-value esdar-meta-downloads">
                <?php echo number_format_i18n($meta['release_download_count']); ?>
            </span>
        </div>
    <?php endif; ?>

</div>
