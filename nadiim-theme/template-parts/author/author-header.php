<?php
/**
 * Template Part: Author Header (بطاقة الكاتب)
 *
 * يعرض بطاقة الكاتب مع:
 * - صورة الملف الشخصي
 * - الاسم والدور
 * - المقتطف القصير
 * - أزرار المتابعة والمراسلة
 * - روابط التواصل الاجتماعي
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على معرف الكاتب
$author = get_queried_object();
$author_id = $author->ID;

// الحصول على بيانات الكاتب
$profile_picture = nadiim_get_author_profile_picture( $author_id, 'medium' );
$user_excerpt = nadiim_get_author_excerpt( $author_id, 150 );
$social_links = nadiim_get_author_social_links( $author_id );
$author_website = nadiim_get_author_website( $author_id );
$allows_contact = nadiim_author_allows_contact( $author_id );

// الحصول على دور المستخدم
$user_data = get_userdata( $author_id );
$user_roles = $user_data->roles;
$user_role_label = '';

if ( ! empty( $user_roles ) ) {
    $role = $user_roles[0];
    $role_names = array(
        'administrator' => __( 'مدير', 'nadiim' ),
        'editor' => __( 'محرر', 'nadiim' ),
        'author' => __( 'كاتب', 'nadiim' ),
        'contributor' => __( 'مساهم', 'nadiim' ),
    );
    $user_role_label = isset( $role_names[ $role ] ) ? $role_names[ $role ] : ucfirst( $role );
}

// إحصائيات المنشورات
$posts_count = nadiim_get_author_post_count( $author_id, 'post' );
$howarat_count = nadiim_get_author_post_count( $author_id, 'howarat' );
$esdar_count = nadiim_get_author_post_count( $author_id, 'esdar' );
?>

<div class="author-card" role="complementary" aria-label="<?php esc_attr_e( 'معلومات الكاتب', 'nadiim' ); ?>">

    <!-- صورة الملف الشخصي -->
    <div class="author-avatar">
        <?php if ( $profile_picture ) : ?>
            <img src="<?php echo esc_url( $profile_picture ); ?>"
                 alt="<?php echo esc_attr( $author->display_name ); ?>"
                 width="140"
                 height="140"
                 class="avatar"
                 loading="eager" />
        <?php else : ?>
            <?php echo get_avatar( $author_id, 140, '', esc_attr( $author->display_name ), array( 'class' => 'avatar' ) ); ?>
        <?php endif; ?>
    </div>

    <!-- الاسم والدور -->
    <div class="author-identity">
        <h2 class="author-name">
            <?php echo esc_html( $author->display_name ); ?>
        </h2>
        <?php if ( $user_role_label ) : ?>
            <p class="author-role">
                <?php echo esc_html( $user_role_label ); ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- المقتطف القصير -->
    <?php if ( ! empty( $user_excerpt ) ) : ?>
        <div class="author-excerpt">
            <p><?php echo esc_html( $user_excerpt ); ?></p>
        </div>
    <?php endif; ?>

    <!-- إحصائيات سريعة -->
    <div class="author-stats">
        <?php if ( $posts_count > 0 ) : ?>
        <div class="stat-item">
            <span class="stat-number"><?php echo number_format_i18n( $posts_count ); ?></span>
            <span class="stat-label"><?php esc_html_e( 'مقالات', 'nadiim' ); ?></span>
        </div>
        <?php endif; ?>

        <?php if ( $howarat_count > 0 ) : ?>
        <div class="stat-item">
            <span class="stat-number"><?php echo number_format_i18n( $howarat_count ); ?></span>
            <span class="stat-label"><?php esc_html_e( 'حوارات', 'nadiim' ); ?></span>
        </div>
        <?php endif; ?>

        <?php if ( $esdar_count > 0 ) : ?>
        <div class="stat-item">
            <span class="stat-number"><?php echo number_format_i18n( $esdar_count ); ?></span>
            <span class="stat-label"><?php esc_html_e( 'إصدارات', 'nadiim' ); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- أزرار التفاعل -->
    <div class="author-actions">
        <?php if ( $allows_contact ) : ?>
        <button type="button"
                class="btn btn-primary btn-contact"
                data-author-id="<?php echo esc_attr( $author_id ); ?>"
                aria-label="<?php esc_attr_e( 'راسل الكاتب', 'nadiim' ); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <?php esc_html_e( 'راسل الكاتب', 'nadiim' ); ?>
        </button>
        <?php endif; ?>

        <?php if ( $author_website ) : ?>
        <a href="<?php echo esc_url( $author_website ); ?>"
           class="btn btn-secondary btn-website"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?php esc_attr_e( 'زيارة موقع الكاتب', 'nadiim' ); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            </svg>
            <?php esc_html_e( 'الموقع الشخصي', 'nadiim' ); ?>
        </a>
        <?php endif; ?>
    </div>

    <!-- روابط التواصل الاجتماعي -->
    <?php if ( ! empty( $social_links ) ) : ?>
    <div class="author-social-links">
        <p class="social-title"><?php esc_html_e( 'تابعني على:', 'nadiim' ); ?></p>
        <div class="social-icons">
            <?php if ( isset( $social_links['facebook'] ) ) : ?>
            <a href="<?php echo esc_url( $social_links['facebook'] ); ?>"
               class="social-link social-facebook"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e( 'فيسبوك', 'nadiim' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </a>
            <?php endif; ?>

            <?php if ( isset( $social_links['twitter'] ) ) : ?>
            <a href="<?php echo esc_url( $social_links['twitter'] ); ?>"
               class="social-link social-twitter"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e( 'تويتر', 'nadiim' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </a>
            <?php endif; ?>

            <?php if ( isset( $social_links['telegram'] ) ) : ?>
            <a href="<?php echo esc_url( $social_links['telegram'] ); ?>"
               class="social-link social-telegram"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e( 'تيليجرام', 'nadiim' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.461-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.911.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.139-5.062 3.345-.479.329-.913.489-1.302.481-.428-.008-1.252-.241-1.865-.44-.752-.244-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                </svg>
            </a>
            <?php endif; ?>

            <?php if ( isset( $social_links['linkedin'] ) ) : ?>
            <a href="<?php echo esc_url( $social_links['linkedin'] ); ?>"
               class="social-link social-linkedin"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e( 'لينكد إن', 'nadiim' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div><!-- .author-card -->
