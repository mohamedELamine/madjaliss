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

// Get members columns setting
$members_columns = get_theme_mod('about_members_columns', 3);

// Get all users who should appear in About page
$members_args = array(
    'meta_key'     => 'show_in_about_page',
    'meta_value'   => '1',
    'orderby'      => 'display_name',
    'order'        => 'ASC',
);

$members = get_users($members_args);

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
                $user_id = $member->ID;
                $name = $member->display_name;
                $role = get_user_meta($user_id, 'about_page_role', true);
                $short_bio = get_user_meta($user_id, 'description', true);
                $author_url = get_author_posts_url($user_id);

                // Get avatar URL - use custom profile picture if available
                $avatar_url = '';
                $profile_picture_id = get_user_meta($user_id, 'profile_picture_id', true);

                if ($profile_picture_id) {
                    // استخدام الصورة المخصصة المرفوعة
                    $avatar_url = wp_get_attachment_image_url($profile_picture_id, 'medium');
                }

                // If no custom picture, use Gravatar
                if (!$avatar_url) {
                    $avatar_url = get_avatar_url($user_id, array('size' => 200));
                }

                // Truncate bio to 2 lines (approximately 80 chars)
                $truncated_bio = $short_bio;
                if (mb_strlen($short_bio) > 80) {
                    $truncated_bio = mb_substr($short_bio, 0, 80) . '...';
                }

                // If no custom role, get user role
                if (empty($role)) {
                    $user_data = get_userdata($user_id);
                    $user_roles = $user_data->roles;
                    if (!empty($user_roles)) {
                        $role_names = array(
                            'administrator' => 'مدير',
                            'editor'        => 'محرر',
                            'author'        => 'كاتب',
                            'contributor'   => 'مساهم',
                        );
                        $role = isset($role_names[$user_roles[0]]) ? $role_names[$user_roles[0]] : $user_roles[0];
                    }
                }
            ?>

                <a href="<?php echo esc_url($author_url); ?>" class="member-card" style="background: linear-gradient(135deg, #ffffff 0%, #f9fffe 100%); border-radius: 12px; padding: 32px 24px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 4px 16px rgba(0,0,0,0.06); border-top: 4px solid var(--color-primary, #339063); text-decoration: none; display: block; color: inherit;">

                    <!-- Member Avatar -->
                    <div class="member-avatar" style="width: 140px; height: 140px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; border: 4px solid var(--color-primary, #339063); box-shadow: 0 4px 12px rgba(51, 144, 99, 0.2);">
                        <img src="<?php echo esc_url($avatar_url); ?>"
                             alt="<?php echo esc_attr($name); ?>"
                             style="width: 100%; height: 100%; object-fit: cover;">
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

                    <!-- View Profile Link -->
                    <div class="member-link">
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: var(--color-primary, #339063); font-weight: 600; font-size: 0.875rem; transition: gap 0.3s ease;">
                            عرض الملف الشخصي
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    </div>
</section>

<style>
.member-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}

.member-card:hover .member-link span {
    gap: 10px;
}

.member-card:hover .member-avatar {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .members-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
    }
}
</style>
