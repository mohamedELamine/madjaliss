<?php
/**
 * Template for single Reading Club
 *
 * @package Madjaliss
 */

get_header();

// الحصول على البيانات
$club_meta = get_post_meta(get_the_ID(), 'club_meta', true);

if (!is_array($club_meta)) {
    $club_meta = array();
}

// القيم الافتراضية
$defaults = array(
    'short_description' => '',
    'full_description' => '',
    'meeting_location' => array(
        'address' => '',
        'lat' => 0,
        'lng' => 0,
    ),
    'meeting_schedule_note' => '',
    'facebook_page' => '',
    'telegram_channel' => '',
    'website' => '',
    'contact_email' => '',
    'map_embed' => '',
    'visibility' => 'public',
);

$club_meta = wp_parse_args($club_meta, $defaults);

// تحديد ما إذا كانت هناك خريطة
$has_map = !empty($club_meta['meeting_location']['lat']) && !empty($club_meta['meeting_location']['lng']);
$has_embed_map = !empty($club_meta['map_embed']);
?>

<div class="reading-club-single-wrapper">
    <article id="post-<?php the_ID(); ?>" <?php post_class('reading-club-single'); ?>>

        <!-- Hero Section -->
        <div class="club-hero-section">
            <div class="club-hero-container">

                <?php if ($has_map || $has_embed_map) : ?>
                <div class="club-hero-content-with-map">
                    <!-- المحتوى على اليسار -->
                    <div class="club-hero-text">
                <?php else : ?>
                <div class="club-hero-content-full">
                <?php endif; ?>

                        <!-- العنوان -->
                        <h1 class="club-title"><?php the_title(); ?></h1>

                        <!-- الوصف المختصر -->
                        <?php if (!empty($club_meta['short_description'])) : ?>
                        <div class="club-short-description">
                            <?php echo nl2br(esc_html($club_meta['short_description'])); ?>
                        </div>
                        <?php endif; ?>

                        <!-- عنوان الموقع -->
                        <?php if (!empty($club_meta['meeting_location']['address'])) : ?>
                        <div class="club-location-badge">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2C6.69 2 4 4.69 4 8c0 4.5 6 10 6 10s6-5.5 6-10c0-3.31-2.69-6-6-6zm0 8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                            </svg>
                            <span><?php echo esc_html($club_meta['meeting_location']['address']); ?></span>
                        </div>
                        <?php endif; ?>

                        <!-- أزرار التواصل الاجتماعي -->
                        <div class="club-social-buttons">
                            <?php if (!empty($club_meta['facebook_page'])) : ?>
                            <a href="<?php echo esc_url($club_meta['facebook_page']); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="club-social-btn club-social-facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span>فيسبوك</span>
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($club_meta['telegram_channel'])) : ?>
                            <a href="<?php echo esc_url($club_meta['telegram_channel']); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="club-social-btn club-social-telegram">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                                </svg>
                                <span>تيليجرام</span>
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($club_meta['website'])) : ?>
                            <a href="<?php echo esc_url($club_meta['website']); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="club-social-btn club-social-website">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                </svg>
                                <span>الموقع</span>
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($club_meta['contact_email'])) : ?>
                            <a href="mailto:<?php echo esc_attr($club_meta['contact_email']); ?>"
                               class="club-social-btn club-social-email">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                                <span>بريد</span>
                            </a>
                            <?php endif; ?>
                        </div>

                <?php if ($has_map || $has_embed_map) : ?>
                    </div>

                    <!-- الخريطة على اليمين -->
                    <div class="club-hero-map">
                        <?php if ($has_embed_map) : ?>
                            <!-- خريطة Embed -->
                            <div class="club-embed-map">
                                <?php echo $club_meta['map_embed']; ?>
                            </div>
                        <?php elseif ($has_map) : ?>
                            <!-- خريطة Leaflet -->
                            <div id="club-hero-map"
                                 class="club-leaflet-map"
                                 data-lat="<?php echo esc_attr($club_meta['meeting_location']['lat']); ?>"
                                 data-lng="<?php echo esc_attr($club_meta['meeting_location']['lng']); ?>"
                                 data-address="<?php echo esc_attr($club_meta['meeting_location']['address']); ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else : ?>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- صندوق مواعيد اللقاءات -->
        <?php if (!empty($club_meta['meeting_schedule_note'])) : ?>
        <div class="club-schedule-box">
            <div class="club-schedule-container">
                <div class="club-schedule-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5zm2 4h10v2H7v-2z"/>
                    </svg>
                </div>
                <div class="club-schedule-content">
                    <h2 class="club-schedule-title">مواعيد الاجتماعات</h2>
                    <p class="club-schedule-text"><?php echo nl2br(esc_html($club_meta['meeting_schedule_note'])); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- الوصف الكامل -->
        <?php if (!empty($club_meta['full_description'])) : ?>
        <div class="club-full-description-box">
            <div class="club-full-description-container">
                <?php echo wp_kses_post($club_meta['full_description']); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- خريطة تفاعلية كبيرة -->
        <?php if ($has_map && !$has_embed_map) : ?>
        <div class="club-interactive-map-section">
            <div class="club-interactive-map-container">
                <h2 class="club-map-title">موقع الاجتماع</h2>
                <div id="club-main-map"
                     class="club-interactive-map"
                     data-lat="<?php echo esc_attr($club_meta['meeting_location']['lat']); ?>"
                     data-lng="<?php echo esc_attr($club_meta['meeting_location']['lng']); ?>"
                     data-address="<?php echo esc_attr($club_meta['meeting_location']['address']); ?>">
                </div>
            </div>
        </div>
        <?php endif; ?>

    </article>
</div>

<?php
get_footer();
