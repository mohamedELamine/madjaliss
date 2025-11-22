<?php
/**
 * Template part for displaying author box
 *
 * @package Nadiim
 * @since 1.0.0
 */

$author_id = get_the_author_meta( 'ID' );
$author_name = get_the_author();
$author_bio = get_the_author_meta( 'description' );
$author_url = get_author_posts_url( $author_id );
$author_posts_count = count_user_posts( $author_id, 'post' );

// الحصول على صورة الملف الشخصي المخصصة
$author_avatar = '';
$profile_picture_id = get_user_meta( $author_id, 'profile_picture_id', true );
if ( $profile_picture_id ) {
    $author_avatar = wp_get_attachment_image_url( $profile_picture_id, 'thumbnail' );
}

// الحصول على featured_excerpt من user meta (يمكن إضافته لاحقاً)
$author_excerpt = get_user_meta( $author_id, 'featured_excerpt', true );
if ( empty( $author_excerpt ) && ! empty( $author_bio ) ) {
    // استخدام أول 150 حرف من السيرة
    $author_excerpt = mb_substr( $author_bio, 0, 150 );
    if ( mb_strlen( $author_bio ) > 150 ) {
        $author_excerpt .= '...';
    }
}

// التحقق من وجود محتوى للعرض
if ( empty( $author_excerpt ) && empty( $author_bio ) ) {
    return;
}
?>

<div class="article-author-box">
    <div class="author-box-content">
        <div class="author-box-avatar">
            <?php if ( $author_avatar ) : ?>
                <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" width="72" height="72" class="avatar avatar-72 photo" loading="lazy">
            <?php else : ?>
                <?php echo get_avatar( $author_id, 72, '', $author_name ); ?>
            <?php endif; ?>
        </div>

        <div class="author-box-info">
            <div class="author-box-label"><?php esc_html_e( 'نبذة عن الكاتب', 'nadiim' ); ?></div>

            <h3 class="author-box-name">
                <a href="<?php echo esc_url( $author_url ); ?>" rel="author">
                    <?php echo esc_html( $author_name ); ?>
                </a>
            </h3>

            <?php if ( ! empty( $author_excerpt ) ) : ?>
                <div class="author-box-bio">
                    <?php echo wp_kses_post( $author_excerpt ); ?>
                </div>
            <?php endif; ?>

            <div class="author-box-stats">
                <?php
                printf(
                    /* translators: %s: number of published posts */
                    esc_html( _n( 'مقال واحد منشور', '%s مقالات منشورة', $author_posts_count, 'nadiim' ) ),
                    number_format_i18n( $author_posts_count )
                );
                ?>
            </div>

            <a href="<?php echo esc_url( $author_url ); ?>" class="author-box-link">
                <?php esc_html_e( 'اعرف المزيد عن الكاتب', 'nadiim' ); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>
</div>

<style>
.author-box-stats {
    font-size: 13px;
    color: var(--color-text-light, #9ca3a0);
    margin-bottom: 1rem;
}

[dir="rtl"] .author-box-link svg {
    transform: scaleX(-1);
}
</style>
