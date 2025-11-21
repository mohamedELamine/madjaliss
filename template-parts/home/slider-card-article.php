<?php
/**
 * Slider Card Article Component
 *
 * بطاقة المقال داخل السلايدر
 *
 * @package Madjaliss
 * @version 2.0 - Stage 1B
 */

// الحصول على بيانات الشريحة من query_var
$slide = get_query_var('slide_data', array());

if (empty($slide)) {
    return;
}

// تجهيز البيانات
$title = isset($slide['title']) ? $slide['title'] : '';
$excerpt = isset($slide['excerpt']) ? $slide['excerpt'] : '';
$permalink = isset($slide['permalink']) ? $slide['permalink'] : '#';
$thumbnail = isset($slide['thumbnail']) ? $slide['thumbnail'] : '';
$author = isset($slide['author']) ? $slide['author'] : '';
$reading_time = isset($slide['reading_time']) ? $slide['reading_time'] : 5;
$has_audio = isset($slide['has_audio']) ? $slide['has_audio'] : false;

// تقصير العنوان إذا كان طويلاً (للموبايل)
$short_title = mb_strlen($title) > 60 ? mb_substr($title, 0, 60) . '...' : $title;

// تقصير المقتطف إلى سطرين
$short_excerpt = mb_strlen($excerpt) > 120 ? mb_substr($excerpt, 0, 120) . '...' : $excerpt;
?>

<article class="slider-card">
    <div class="slider-card-inner">

        <!-- Image Section (يمين في RTL Desktop، أعلى في Mobile) -->
        <div class="slider-card-image">
            <?php if (!empty($thumbnail)) : ?>
                <img
                    src="<?php echo esc_url($thumbnail); ?>"
                    alt="<?php echo esc_attr($title); ?>"
                    loading="lazy"
                    class="swiper-lazy"
                >
                <div class="swiper-lazy-preloader"></div>
            <?php else : ?>
                <!-- صورة افتراضية -->
                <div class="slider-card-placeholder">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
            <?php endif; ?>
        </div>

        <!-- Content Section (يسار في RTL Desktop، تحت الصورة في Mobile) -->
        <div class="slider-card-content">

            <!-- العنوان -->
            <h2 class="slider-card-title">
                <a href="<?php echo esc_url($permalink); ?>" tabindex="0">
                    <?php echo esc_html($title); ?>
                </a>
            </h2>

            <!-- المقتطف -->
            <div class="slider-card-excerpt">
                <p><?php echo esc_html($short_excerpt); ?></p>
            </div>

            <!-- Meta Row -->
            <div class="slider-card-meta">
                <!-- الكاتب -->
                <?php if (!empty($author)) : ?>
                    <span class="meta-author">
                        <svg class="meta-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span><?php echo esc_html($author); ?></span>
                    </span>
                <?php endif; ?>

                <!-- وقت القراءة -->
                <?php if (!empty($reading_time)) : ?>
                    <span class="meta-reading-time">
                        <svg class="meta-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span><?php echo esc_html($reading_time); ?> <?php esc_html_e('دقائق', 'madjaliss'); ?></span>
                    </span>
                <?php endif; ?>

                <!-- أيقونة الصوت (إن وُجد) -->
                <?php if ($has_audio) : ?>
                    <span class="meta-audio" title="<?php esc_attr_e('يحتوي على تسجيل صوتي', 'madjaliss'); ?>">
                        <svg class="meta-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
                        </svg>
                    </span>
                <?php endif; ?>
            </div>

            <!-- زر CTA -->
            <div class="slider-card-cta">
                <a href="<?php echo esc_url($permalink); ?>" class="btn btn-read" tabindex="0">
                    <?php esc_html_e('اقرأ المزيد', 'madjaliss'); ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </div>

        </div><!-- .slider-card-content -->

    </div><!-- .slider-card-inner -->
</article><!-- .slider-card -->
