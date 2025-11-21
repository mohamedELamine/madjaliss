<?php
/**
 * Template part for displaying About Members section
 *
 * @package Nadiim
 * @since 1.0.0
 */

// Check if members section is enabled
$members_enable = get_theme_mod('about_members_enable', true);
if (!$members_enable) {
    return;
}

// Get members settings
$members_json = get_theme_mod('about_members_json', '');
$members_columns = get_theme_mod('about_members_columns', 3);

// Parse members JSON
$members = array();
if (!empty($members_json)) {
    $decoded = json_decode($members_json, true);
    if (is_array($decoded)) {
        // Filter only displayed members
        $members = array_filter($decoded, function($member) {
            return !isset($member['display']) || $member['display'] === true || $member['display'] === 'true';
        });
    }
}

// Add demo members if empty
if (empty($members)) {
    $members = array(
        array(
            'name' => 'أحمد محمد',
            'role' => 'المؤسس والمدير التنفيذي',
            'short_bio' => 'كاتب وباحث في الأدب العربي، يهتم بنشر الثقافة والمعرفة في المجتمع العربي.',
            'photo_id' => '',
            'profile_link' => '',
            'display' => true,
        ),
        array(
            'name' => 'فاطمة عبدالله',
            'role' => 'مديرة المحتوى',
            'short_bio' => 'صحفية ومحررة، متخصصة في الكتابة الثقافية والأدبية.',
            'photo_id' => '',
            'profile_link' => '',
            'display' => true,
        ),
        array(
            'name' => 'عمر حسن',
            'role' => 'مدير التواصل الاجتماعي',
            'short_bio' => 'خبير في التسويق الرقمي والتواصل مع الجمهور عبر منصات التواصل الاجتماعي.',
            'photo_id' => '',
            'profile_link' => '',
            'display' => true,
        ),
        array(
            'name' => 'سارة إبراهيم',
            'role' => 'منسقة الفعاليات',
            'short_bio' => 'متخصصة في تنظيم الفعاليات الثقافية والأدبية وإدارة المشاريع.',
            'photo_id' => '',
            'profile_link' => '',
            'display' => true,
        ),
    );
}

if (empty($members)) {
    return;
}

// Set column class based on columns setting
$column_class = '';
switch ($members_columns) {
    case 2:
        $column_class = 'repeat(auto-fit, minmax(400px, 1fr))';
        break;
    case 4:
        $column_class = 'repeat(auto-fit, minmax(240px, 1fr))';
        break;
    case 3:
    default:
        $column_class = 'repeat(auto-fit, minmax(280px, 1fr))';
        break;
}
?>

<section class="about-members" style="padding: 80px 0; background: #fff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">

        <h2 class="section-title" style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; text-align: center; margin-bottom: 24px; color: var(--color-primary, #339063);">
            فريق العمل
        </h2>

        <p class="section-subtitle" style="text-align: center; font-size: 1.125rem; color: var(--color-text-secondary, #666); margin-bottom: 64px; max-width: 700px; margin-left: auto; margin-right: auto;">
            تعرف على الأشخاص الذين يعملون بجد لتقديم محتوى مميز ونشر الثقافة والمعرفة
        </p>

        <div class="members-grid" style="display: grid; grid-template-columns: <?php echo esc_attr($column_class); ?>; gap: 32px;">

            <?php foreach ($members as $member) :
                $name = isset($member['name']) ? $member['name'] : '';
                $role = isset($member['role']) ? $member['role'] : '';
                $short_bio = isset($member['short_bio']) ? $member['short_bio'] : '';
                $photo_id = isset($member['photo_id']) ? $member['photo_id'] : '';
                $profile_link = isset($member['profile_link']) ? $member['profile_link'] : '';

                if (empty($name)) {
                    continue;
                }

                // Get photo URL
                $photo_url = '';
                if ($photo_id) {
                    $photo_url = wp_get_attachment_image_url($photo_id, 'medium');
                }

                // Truncate bio to 2 lines (approximately 80 chars)
                $truncated_bio = $short_bio;
                if (mb_strlen($short_bio) > 80) {
                    $truncated_bio = mb_substr($short_bio, 0, 80) . '...';
                }
            ?>

                <div class="member-card" style="background: linear-gradient(135deg, #ffffff 0%, #f9fffe 100%); border-radius: 12px; padding: 32px 24px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 4px 16px rgba(0,0,0,0.06); border-top: 4px solid var(--color-primary, #339063);">

                    <!-- Member Avatar -->
                    <div class="member-avatar" style="width: 140px; height: 140px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; border: 4px solid var(--color-primary, #339063); box-shadow: 0 4px 12px rgba(51, 144, 99, 0.2);">
                        <?php if ($photo_url) : ?>
                            <img src="<?php echo esc_url($photo_url); ?>"
                                 alt="<?php echo esc_attr($name); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else : ?>
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--color-primary, #339063), rgba(51, 144, 99, 0.7)); display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700; color: #fff;">
                                <?php echo esc_html(mb_substr($name, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Member Name -->
                    <h3 class="member-name" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">
                        <?php echo esc_html($name); ?>
                    </h3>

                    <!-- Member Role -->
                    <?php if ($role) : ?>
                        <p class="member-role" style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary, #339063); margin-bottom: 16px;">
                            <?php echo esc_html($role); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Member Bio -->
                    <?php if ($truncated_bio) : ?>
                        <p class="member-bio" style="font-size: 0.9375rem; line-height: 1.6; color: var(--color-text-secondary, #666); margin-bottom: 20px; min-height: 3em;">
                            <?php echo esc_html($truncated_bio); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Profile Link -->
                    <?php if ($profile_link) : ?>
                        <div class="member-link">
                            <a href="<?php echo esc_url($profile_link); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               style="display: inline-flex; align-items: center; gap: 6px; color: var(--color-primary, #339063); font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: gap 0.3s ease;">
                                عرض الملف الشخصي
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="7" y1="17" x2="17" y2="7"></line>
                                    <polyline points="7 7 17 7 17 17"></polyline>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>

<style>
.member-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}

.member-link a:hover {
    gap: 10px;
}

@media (max-width: 768px) {
    .members-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
    }
}
</style>
