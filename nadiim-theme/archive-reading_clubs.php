<?php
/**
 * Template for Reading Clubs Archive
 *
 * @package Madjaliss
 */

get_header();
?>

<div class="reading-clubs-archive-wrapper">

    <!-- Header Section -->
    <div class="clubs-archive-header">
        <div class="clubs-archive-header-container">
            <h1 class="clubs-archive-title">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
                <?php _e('نوادي القراءة', 'madjaliss'); ?>
            </h1>
            <p class="clubs-archive-description">
                <?php _e('استكشف نوادي القراءة المختلفة وانضم إلى مجتمع القراء المتحمسين', 'madjaliss'); ?>
            </p>
        </div>
    </div>

    <!-- Clubs Grid -->
    <div class="clubs-archive-grid-container">
        <?php if (have_posts()) : ?>

        <div class="clubs-grid">
            <?php
            while (have_posts()) :
                the_post();

                // الحصول على البيانات
                $club_meta = get_post_meta(get_the_ID(), 'club_meta', true);

                if (!is_array($club_meta)) {
                    $club_meta = array();
                }

                // القيم الافتراضية
                $defaults = array(
                    'short_description' => '',
                    'meeting_location' => array(
                        'address' => '',
                    ),
                    'visibility' => 'public',
                );

                $club_meta = wp_parse_args($club_meta, $defaults);

                // تخطي النوادي الخاصة
                if ($club_meta['visibility'] !== 'public') {
                    continue;
                }
            ?>

            <article class="club-card">
                <div class="club-card-inner">

                    <!-- الصورة البارزة -->
                    <div class="club-card-thumbnail">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', array('class' => 'club-card-image')); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="club-card-placeholder">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- المحتوى -->
                    <div class="club-card-content">

                        <!-- العنوان -->
                        <h2 class="club-card-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <!-- الوصف المختصر -->
                        <?php if (!empty($club_meta['short_description'])) : ?>
                        <div class="club-card-description">
                            <?php
                            $short_desc = $club_meta['short_description'];
                            // تحديد طول الوصف إلى 120 حرف
                            if (mb_strlen($short_desc) > 120) {
                                $short_desc = mb_substr($short_desc, 0, 120) . '...';
                            }
                            echo nl2br(esc_html($short_desc));
                            ?>
                        </div>
                        <?php endif; ?>

                        <!-- عنوان الموقع -->
                        <?php if (!empty($club_meta['meeting_location']['address'])) : ?>
                        <div class="club-card-location">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2C6.69 2 4 4.69 4 8c0 4.5 6 10 6 10s6-5.5 6-10c0-3.31-2.69-6-6-6zm0 8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                            </svg>
                            <span><?php echo esc_html($club_meta['meeting_location']['address']); ?></span>
                        </div>
                        <?php endif; ?>

                        <!-- زر عرض النادي -->
                        <div class="club-card-footer">
                            <a href="<?php the_permalink(); ?>" class="club-card-button">
                                <?php _e('عرض النادي', 'madjaliss'); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                                </svg>
                            </a>
                        </div>

                    </div>

                </div>
            </article>

            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="clubs-pagination">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('« السابق', 'madjaliss'),
                'next_text' => __('التالي »', 'madjaliss'),
            ));
            ?>
        </div>

        <?php else : ?>

        <!-- رسالة عدم وجود نوادي -->
        <div class="clubs-no-results">
            <svg width="100" height="100" viewBox="0 0 24 24" fill="currentColor" style="opacity: 0.3;">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            <h2><?php _e('لا توجد نوادي قراءة حالياً', 'madjaliss'); ?></h2>
            <p><?php _e('عد لاحقاً لاستكشاف نوادي القراءة الجديدة', 'madjaliss'); ?></p>
        </div>

        <?php endif; ?>
    </div>

</div>

<?php
get_footer();
