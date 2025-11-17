<?php
/**
 * قسم Hero - المنطقة البصرية الأبرز
 *
 * منطقة Hero بتصميم Grid: 2/3 للصورة، 1/3 للنص على desktop
 * متراص على mobile (صورة في الأعلى، نص في الأسفل)
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات Hero من Customizer
$hero_title          = get_theme_mod( 'home_hero_title', 'مرحباً بكم في نديم' );
$hero_subtitle       = get_theme_mod( 'home_hero_subtitle', 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة' );
$hero_cta_text       = get_theme_mod( 'home_hero_cta_text', 'استكشف المحتوى' );
$hero_cta_link       = get_theme_mod( 'home_hero_cta_link', '#dialogues' );
$hero_bg_type        = get_theme_mod( 'home_hero_bg_type', 'gradient' ); // image/color/gradient
$hero_bg_image       = get_theme_mod( 'home_hero_bg_image', '' );
$hero_bg_color       = get_theme_mod( 'home_hero_bg_color', '#F6FFF9' );
$hero_overlay        = get_theme_mod( 'home_hero_overlay_opacity', 0.3 );

// تحديد نمط الخلفية
$background_style = '';
if ( $hero_bg_type === 'image' && $hero_bg_image ) {
	$bg_image_url = is_numeric( $hero_bg_image )
		? wp_get_attachment_image_url( $hero_bg_image, 'full' )
		: $hero_bg_image;
	$background_style = "background-image: url('" . esc_url( $bg_image_url ) . "'); background-size: cover; background-position: center;";
} elseif ( $hero_bg_type === 'gradient' ) {
	$background_style = "background: linear-gradient(180deg, #FFFFFF 0%, #F6FFF9 100%);";
} else {
	$background_style = "background-color: " . esc_attr( $hero_bg_color ) . ";";
}
?>

<section class="hero-section" style="<?php echo $background_style; ?>">
	<?php if ( $hero_bg_type === 'image' && $hero_bg_image && $hero_overlay > 0 ) : ?>
		<div class="hero-overlay" style="opacity: <?php echo esc_attr( $hero_overlay ); ?>;"></div>
	<?php endif; ?>

	<div class="hero-container">
		<div class="hero-content">
			<div class="hero-text">
				<h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
				<p class="hero-lead"><?php echo esc_html( $hero_subtitle ); ?></p>
				<?php if ( $hero_cta_text && $hero_cta_link ) : ?>
					<a href="<?php echo esc_url( $hero_cta_link ); ?>" class="hero-cta-btn">
						<?php echo esc_html( $hero_cta_text ); ?>
						<span class="cta-arrow">←</span>
					</a>
				<?php endif; ?>
			</div>

			<div class="hero-image">
				<?php if ( $hero_bg_type === 'image' && $hero_bg_image ) : ?>
					<?php
					if ( is_numeric( $hero_bg_image ) ) {
						echo wp_get_attachment_image( $hero_bg_image, 'full', false, array( 'class' => 'hero-img' ) );
					} else {
						echo '<img src="' . esc_url( $hero_bg_image ) . '" alt="' . esc_attr( $hero_title ) . '" class="hero-img" />';
					}
					?>
				<?php else : ?>
					<!-- صورة افتراضية أو شكل زخرفي -->
					<div class="hero-placeholder">
						<svg width="100%" height="100%" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="200" cy="200" r="150" fill="var(--color-primary)" opacity="0.1"/>
							<circle cx="200" cy="200" r="100" fill="var(--color-primary)" opacity="0.2"/>
							<circle cx="200" cy="200" r="50" fill="var(--color-primary)" opacity="0.3"/>
						</svg>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
