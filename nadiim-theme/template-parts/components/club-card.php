<?php
/**
 * Component: بطاقة النادي
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على بيانات النادي
$club_id = get_query_var( 'club_id', get_the_ID() );

if ( ! $club_id ) {
	return;
}

// الحصول على club_meta
$club_meta = get_post_meta( $club_id, 'club_meta', true );

if ( ! is_array( $club_meta ) ) {
	$club_meta = array();
}

// استخراج البيانات
$short_description = isset( $club_meta['short_description'] ) ? $club_meta['short_description'] : '';
$meeting_location  = isset( $club_meta['meeting_location']['address'] ) ? $club_meta['meeting_location']['address'] : '';
$meeting_schedule  = isset( $club_meta['meeting_schedule_note'] ) ? $club_meta['meeting_schedule_note'] : '';
$facebook_page     = isset( $club_meta['facebook_page'] ) ? $club_meta['facebook_page'] : '';
$telegram_channel  = isset( $club_meta['telegram_channel'] ) ? $club_meta['telegram_channel'] : '';

// إعدادات الخلفية لهذا النادي (per-club override)
$cover_bg_enable      = isset( $club_meta['cover_bg_enable'] ) ? $club_meta['cover_bg_enable'] : false;
$cover_image          = isset( $club_meta['cover_image'] ) ? $club_meta['cover_image'] : '';
$cover_overlay_opacity = isset( $club_meta['cover_color_overlay_opacity'] ) ? floatval( $club_meta['cover_color_overlay_opacity'] ) : 0;

// الحصول على الإعدادات الافتراضية من القسم
$section_overlay_opacity = get_theme_mod( 'clubs_section_overlay_opacity', 0.30 );
$section_overlay_color   = get_theme_mod( 'clubs_section_overlay_color', '#000000' );

// استخدام per-club override إذا كان مفعلاً
$use_club_overlay = $cover_bg_enable && $cover_overlay_opacity > 0;
$final_opacity = $use_club_overlay ? $cover_overlay_opacity : $section_overlay_opacity;

// تحديد classes للبطاقة
$card_classes = array( 'club-card' );
if ( $cover_bg_enable && ! empty( $cover_image ) ) {
	$card_classes[] = 'club-card-with-bg';
}

// inline style للبطاقة
$card_style = '';
if ( $cover_bg_enable && ! empty( $cover_image ) ) {
	$card_style = sprintf( 'background-image: url(%s);', esc_url( $cover_image ) );
}
?>

<article id="club-<?php echo esc_attr( $club_id ); ?>" class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>"
         <?php if ( ! empty( $card_style ) ) : ?>style="<?php echo esc_attr( $card_style ); ?>"<?php endif; ?>>

	<?php if ( $cover_bg_enable && ! empty( $cover_image ) ) : ?>
		<!-- طبقة التعتيم فوق خلفية البطاقة -->
		<div class="club-card-overlay" style="opacity: <?php echo esc_attr( $final_opacity ); ?>;"></div>
	<?php endif; ?>

	<div class="club-card-content">

		<!-- Avatar النادي -->
		<div class="club-avatar">
			<?php if ( has_post_thumbnail( $club_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $club_id, 'thumbnail', array( 'class' => 'club-avatar-img' ) ); ?>
			<?php else : ?>
				<div class="club-avatar-placeholder">
					<span class="club-icon">📚</span>
				</div>
			<?php endif; ?>
		</div>

		<!-- عنوان النادي -->
		<h3 class="club-title">
			<a href="<?php echo esc_url( get_permalink( $club_id ) ); ?>">
				<?php echo esc_html( get_the_title( $club_id ) ); ?>
			</a>
		</h3>

		<!-- الوصف القصير -->
		<?php if ( $short_description ) : ?>
			<div class="club-description">
				<?php echo esc_html( wp_trim_words( $short_description, 15, '...' ) ); ?>
			</div>
		<?php elseif ( get_the_excerpt( $club_id ) ) : ?>
			<div class="club-description">
				<?php echo esc_html( wp_trim_words( get_the_excerpt( $club_id ), 15, '...' ) ); ?>
			</div>
		<?php endif; ?>

		<!-- معلومات إضافية (Meta) -->
		<div class="club-meta-info">

			<?php if ( $meeting_location ) : ?>
				<div class="club-meta-item club-location">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
					</svg>
					<span><?php echo esc_html( wp_trim_words( $meeting_location, 4, '...' ) ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $meeting_schedule ) : ?>
				<div class="club-meta-item club-schedule">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
					</svg>
					<span><?php echo esc_html( wp_trim_words( $meeting_schedule, 6, '...' ) ); ?></span>
				</div>
			<?php endif; ?>

		</div><!-- .club-meta-info -->

		<!-- روابط التواصل الاجتماعي -->
		<?php if ( $facebook_page || $telegram_channel ) : ?>
			<div class="club-social-links">
				<?php if ( $facebook_page ) : ?>
					<a href="<?php echo esc_url( $facebook_page ); ?>"
					   target="_blank"
					   rel="noopener"
					   class="club-social-link facebook"
					   aria-label="<?php esc_attr_e( 'صفحة الفيسبوك', 'nadiim' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
							<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
						</svg>
					</a>
				<?php endif; ?>

				<?php if ( $telegram_channel ) : ?>
					<a href="<?php echo esc_url( $telegram_channel ); ?>"
					   target="_blank"
					   rel="noopener"
					   class="club-social-link telegram"
					   aria-label="<?php esc_attr_e( 'قناة التيليجرام', 'nadiim' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
							<path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
						</svg>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- زر عرض النادي -->
		<a href="<?php echo esc_url( get_permalink( $club_id ) ); ?>" class="club-view-btn">
			<?php _e( 'عرض النادي', 'nadiim' ); ?>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<line x1="5" y1="12" x2="19" y2="12"></line>
				<polyline points="12 5 19 12 12 19"></polyline>
			</svg>
		</a>

	</div><!-- .club-card-content -->

</article><!-- .club-card -->
