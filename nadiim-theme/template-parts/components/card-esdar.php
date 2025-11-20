<?php
/**
 * بطاقة الإصدار
 *
 * Template part لعرض بطاقة إصدار في الأرشيف
 *
 * @package Nadiim
 */

$meta = nadiim_get_esdar_meta(get_the_ID());

// إذا كانت البيانات فارغة، استخدم قيم افتراضية
if (!$meta || !is_array($meta)) {
    $meta = array(
        'release_type' => 'book',
        'release_date' => '',
        'release_authors' => array(),
        'release_pages' => '',
        'release_format' => array(),
        'release_excerpt' => '',
        'release_download_count' => 0,
    );
}
?>

<article <?php post_class('esdar-card'); ?> data-post-id="<?php echo get_the_ID(); ?>">

    <!-- الرابط الرئيسي -->
    <a href="<?php the_permalink(); ?>" class="esdar-card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">

        <!-- صورة الغلاف -->
        <div class="esdar-card-cover">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium', array(
                    'class' => 'esdar-card-image',
                    'alt' => get_the_title(),
                    'loading' => 'lazy',
                )); ?>
            <?php else : ?>
                <div class="esdar-card-placeholder">
                    <?php echo nadiim_get_esdar_type_icon($meta['release_type']); ?>
                </div>
            <?php endif; ?>

            <!-- شارة النوع -->
            <div class="esdar-card-type-badge esdar-type-<?php echo esc_attr($meta['release_type']); ?>">
                <?php echo esc_html(nadiim_get_esdar_type_label($meta['release_type'])); ?>
            </div>

            <!-- الصيغ المتوفرة -->
            <?php if (!empty($meta['release_format'])) : ?>
                <div class="esdar-card-formats">
                    <?php foreach ($meta['release_format'] as $format) : ?>
                        <span class="esdar-format-mini"><?php echo esc_html(strtoupper($format)); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- محتوى البطاقة -->
        <div class="esdar-card-content">

            <!-- العنوان -->
            <h3 class="esdar-card-title">
                <?php the_title(); ?>
            </h3>

            <!-- المؤلفون -->
            <?php if (!empty($meta['release_authors'])) : ?>
                <div class="esdar-card-authors">
                    <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span><?php echo nadiim_get_esdar_authors(get_the_ID(), '، ', false); ?></span>
                </div>
            <?php endif; ?>

            <!-- التاريخ -->
            <?php if (!empty($meta['release_date'])) : ?>
                <div class="esdar-card-date">
                    <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <time datetime="<?php echo esc_attr($meta['release_date']); ?>">
                        <?php echo date_i18n('Y', strtotime($meta['release_date'])); ?>
                    </time>
                </div>
            <?php endif; ?>

            <!-- المقتطف -->
            <?php if (!empty($meta['release_excerpt'])) : ?>
                <div class="esdar-card-excerpt">
                    <?php
                    $excerpt = $meta['release_excerpt'];
                    $words = explode(' ', $excerpt);
                    $limited = array_slice($words, 0, 18);
                    echo esc_html(implode(' ', $limited));
                    if (count($words) > 18) {
                        echo '...';
                    }
                    ?>
                </div>
            <?php elseif (has_excerpt()) : ?>
                <div class="esdar-card-excerpt">
                    <?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?>
                </div>
            <?php endif; ?>

            <!-- معلومات إضافية -->
            <div class="esdar-card-meta">
                <?php if (!empty($meta['release_pages'])) : ?>
                    <span class="esdar-card-meta-item">
                        <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <?php echo number_format_i18n($meta['release_pages']); ?> <?php _e('ص', 'nadiim'); ?>
                    </span>
                <?php endif; ?>

                <?php if (isset($meta['release_download_count']) && $meta['release_download_count'] > 0) : ?>
                    <span class="esdar-card-meta-item">
                        <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <?php echo number_format_i18n($meta['release_download_count']); ?>
                    </span>
                <?php endif; ?>
            </div>

        </div>
        <!-- نهاية card-content -->

    </a>
    <!-- نهاية card-link -->

    <!-- زر التحميل السريع -->
    <?php
    $download_url = nadiim_get_esdar_download_url(get_the_ID());
    if ($download_url) :
    ?>
        <div class="esdar-card-actions">
            <a href="<?php echo esc_url($download_url); ?>"
               class="esdar-card-download-btn"
               onclick="event.stopPropagation();"
               data-post-id="<?php echo get_the_ID(); ?>"
               download
               aria-label="<?php _e('تحميل', 'nadiim'); ?>">
                <svg class="esdar-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span><?php _e('تحميل', 'nadiim'); ?></span>
            </a>
        </div>
    <?php endif; ?>

</article>
