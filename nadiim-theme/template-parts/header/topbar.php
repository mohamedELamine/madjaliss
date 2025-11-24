<?php
/**
 * Template part للشريط العلوي (Top Bar)
 *
 * @package Nadiim
 * @since 1.0.0
 */

// إذا كان الشريط العلوي غير مفعّل، لا نعرض شيئاً
if ( ! get_theme_mod( 'topbar_enable', true ) ) {
    return;
}

$is_dynamic      = get_theme_mod( 'topbar_dynamic_enable', false );
$static_text     = get_theme_mod( 'topbar_text', __( 'مرحباً بكم في منصة نديم - فضاء للحوارات الرصينة', 'nadiim' ) );
$icon            = nadiim_get_topbar_icon();
$marquee_enabled = get_theme_mod( 'topbar_marquee_enable', false );
$marquee_speed   = get_theme_mod( 'topbar_marquee_speed', 50 );
?>

<div class="site-topbar" role="banner">
    <div class="container">
        <div class="topbar-inner">
            <div class="topbar-content<?php echo $marquee_enabled ? ' topbar-marquee' : ''; ?>"
                 <?php if ( $marquee_enabled ) : ?>
                     data-speed="<?php echo esc_attr( $marquee_speed ); ?>"
                     data-icon="<?php echo esc_attr( $icon ); ?>"
                 <?php endif; ?>>
                <?php if ( ! empty( $icon ) && ! $marquee_enabled ) : ?>
                    <span class="topbar-icon" aria-hidden="true"><?php echo $icon; ?></span>
                <?php endif; ?>
                <?php if ( $is_dynamic ) : ?>
                    <?php echo nadiim_get_topbar_dynamic_content(); ?>
                <?php else : ?>
                    <span class="topbar-text"><?php echo wp_kses_post( $static_text ); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
