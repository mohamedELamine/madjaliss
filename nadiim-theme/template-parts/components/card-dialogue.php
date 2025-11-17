<?php
/**
 * Component: بطاقة الحوار
 *
 * يعرض بطاقة حوار واحدة مع:
 * - صورة بنسبة 16:9
 * - Date badge (56×56px) في الأعلى اليسار
 * - عنوان (H3, 20px)
 * - مقتطف (14-15px)
 * - Meta row (نوع الحوار، المدة)
 * - CTA صغير
 *
 * @package Nadiim
 * @since 2.0.0
 */

// Meta data
$dialogue_date     = get_post_meta( get_the_ID(), 'dialogue_date', true );
$dialogue_type     = get_post_meta( get_the_ID(), 'dialogue_type', true );
$dialogue_duration = get_post_meta( get_the_ID(), 'dialogue_duration', true );
$dialogue_media    = get_post_meta( get_the_ID(), 'dialogue_media', true );

// تنسيق التاريخ
$date_day   = $dialogue_date ? date_i18n( 'd', strtotime( $dialogue_date ) ) : get_the_date( 'd' );
$date_month = $dialogue_date ? date_i18n( 'M', strtotime( $dialogue_date ) ) : get_the_date( 'M' );
?>

<article <?php post_class( 'dialogue-card' ); ?>>
	<div class="dialogue-card-inner">
		<!-- الصورة مع Date Badge -->
		<div class="dialogue-image-wrapper">
			<a href="<?php the_permalink(); ?>" class="dialogue-image-link">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'dialogue-image' ) ); ?>
				<?php else : ?>
					<div class="dialogue-image-placeholder">
						<span class="placeholder-icon">🎙️</span>
					</div>
				<?php endif; ?>
			</a>

			<!-- Date Badge -->
			<div class="dialogue-date-badge">
				<span class="badge-day"><?php echo esc_html( $date_day ); ?></span>
				<span class="badge-month"><?php echo esc_html( $date_month ); ?></span>
			</div>

			<!-- Media Type Badge -->
			<?php if ( $dialogue_media ) : ?>
				<div class="dialogue-media-badge">
					<?php
					if ( strpos( $dialogue_media, 'youtube' ) !== false || strpos( $dialogue_media, 'youtu.be' ) !== false ) {
						echo '<span class="media-icon">▶️</span>';
					} elseif ( strpos( $dialogue_media, '.mp3' ) !== false || strpos( $dialogue_media, 'audio' ) !== false ) {
						echo '<span class="media-icon">🎵</span>';
					} else {
						echo '<span class="media-icon">🎧</span>';
					}
					?>
				</div>
			<?php endif; ?>
		</div>

		<!-- المحتوى -->
		<div class="dialogue-content">
			<h3 class="dialogue-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<div class="dialogue-excerpt">
				<?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?>
			</div>

			<!-- Meta Row -->
			<div class="dialogue-meta">
				<?php if ( $dialogue_type ) : ?>
					<span class="dialogue-type">
						<span class="meta-icon">📻</span>
						<?php echo esc_html( $dialogue_type ); ?>
					</span>
				<?php endif; ?>

				<?php if ( $dialogue_duration ) : ?>
					<span class="dialogue-duration">
						<span class="meta-icon">⏱️</span>
						<?php echo esc_html( $dialogue_duration ); ?>
					</span>
				<?php endif; ?>
			</div>

			<!-- CTA -->
			<a href="<?php the_permalink(); ?>" class="dialogue-cta">
				استمع الآن
				<span class="cta-arrow">←</span>
			</a>
		</div>
	</div>
</article>
