<?php
/**
 * Template part لقسم من نحن المصغر
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'about_mini_enable', true ) ) {
	return;
}

// الحصول على الإعدادات من Customizer
$section_title     = get_theme_mod( 'about_mini_title', __( 'من نحن؟', 'nadiim' ) );
$section_lead      = get_theme_mod( 'about_mini_lead', __( 'نديم مبادرة ثقافية تهدف إلى نشر الوعي وتعزيز القراءة والحوارات الهادفة. نؤمن بقوة الكلمة في بناء المجتمعات.', 'nadiim' ) );
$cta_text          = get_theme_mod( 'about_mini_cta_text', __( 'اقرأ قصتنا', 'nadiim' ) );
$cta_link          = get_theme_mod( 'about_mini_cta_link', '#' );
$bg_enable         = get_theme_mod( 'about_mini_bg_enable', false );
$bg_image          = get_theme_mod( 'about_mini_bg_image', '' );
$bg_position       = get_theme_mod( 'about_mini_bg_position', 'center' );

// بيانات البطاقات الثلاث
$values = array(
	array(
		'icon'  => get_theme_mod( 'about_mini_value_1_icon', '🎯' ),
		'title' => get_theme_mod( 'about_mini_value_1_title', __( 'رؤيتنا', 'nadiim' ) ),
		'text'  => get_theme_mod( 'about_mini_value_1_text', __( 'بناء مجتمع قارئ ومفكّر', 'nadiim' ) ),
	),
	array(
		'icon'  => get_theme_mod( 'about_mini_value_2_icon', '💡' ),
		'title' => get_theme_mod( 'about_mini_value_2_title', __( 'مهمتنا', 'nadiim' ) ),
		'text'  => get_theme_mod( 'about_mini_value_2_text', __( 'نشر المعرفة عبر الحوار الهادف', 'nadiim' ) ),
	),
	array(
		'icon'  => get_theme_mod( 'about_mini_value_3_icon', '🌟' ),
		'title' => get_theme_mod( 'about_mini_value_3_title', __( 'قيمنا', 'nadiim' ) ),
		'text'  => get_theme_mod( 'about_mini_value_3_text', __( 'الجودة والأصالة والاحترام', 'nadiim' ) ),
	),
);

// تحديد classes للقسم
$section_classes = array( 'about-mini-section' );
if ( $bg_enable ) {
	$section_classes[] = 'section-with-bg';
}

// inline style للخلفية
$section_style = '';
if ( $bg_enable && ! empty( $bg_image ) ) {
	$section_style = sprintf(
		'background-image: url(%s); background-position: %s;',
		esc_url( $bg_image ),
		esc_attr( $bg_position )
	);
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>"
         <?php if ( ! empty( $section_style ) ) : ?>style="<?php echo esc_attr( $section_style ); ?>"<?php endif; ?>
         aria-labelledby="about-mini-title">

	<?php if ( $bg_enable ) : ?>
		<!-- طبقة التعتيم فوق الخلفية -->
		<div class="section-overlay" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="container">

		<!-- رأس القسم -->
		<div class="section-header">
			<?php if ( ! empty( $section_title ) ) : ?>
				<h2 id="about-mini-title" class="section-title">
					<?php echo esc_html( $section_title ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( ! empty( $section_lead ) ) : ?>
				<p class="section-lead">
					<?php echo wp_kses_post( $section_lead ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .section-header -->

		<!-- بطاقات المبادئ -->
		<div class="values-grid">
			<?php foreach ( $values as $value ) : ?>
				<div class="value-card">
					<?php if ( ! empty( $value['icon'] ) ) : ?>
						<div class="value-icon" aria-hidden="true">
							<?php echo esc_html( $value['icon'] ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $value['title'] ) ) : ?>
						<h3 class="value-title">
							<?php echo esc_html( $value['title'] ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( ! empty( $value['text'] ) ) : ?>
						<p class="value-text">
							<?php echo esc_html( $value['text'] ); ?>
						</p>
					<?php endif; ?>
				</div><!-- .value-card -->
			<?php endforeach; ?>
		</div><!-- .values-grid -->

		<!-- زر CTA -->
		<?php if ( ! empty( $cta_text ) ) : ?>
			<div class="section-cta">
				<a href="<?php echo esc_url( $cta_link ); ?>" class="cta-button">
					<?php echo esc_html( $cta_text ); ?>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
					</svg>
				</a>
			</div><!-- .section-cta -->
		<?php endif; ?>

	</div><!-- .container -->

</section><!-- .about-mini-section -->
