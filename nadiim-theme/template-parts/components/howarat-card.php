<?php
/**
 * Component - بطاقة حوار مميز
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على بيانات البطاقة
$card = isset( $howarat_card ) ? $howarat_card : array();

// القيم الافتراضية
$defaults = array(
	'title'    => '',
	'excerpt'  => '',
	'image'    => '',
	'type'     => 'text', // audio, video, text
	'cta_text' => __( 'اقرأ المزيد', 'nadiim' ),
	'cta_link' => '#',
	'date'     => '',
);

$card = wp_parse_args( $card, $defaults );

// إذا لم يكن هناك عنوان، لا تعرض البطاقة
if ( empty( $card['title'] ) ) {
	return;
}

// إعدادات إظهار أيقونات الوسائط
$show_media_icon = get_theme_mod( 'featured_howarat_show_media_icon', true );

// تنسيق التاريخ
$formatted_date = '';
if ( ! empty( $card['date'] ) ) {
	$timestamp = is_numeric( $card['date'] ) ? $card['date'] : strtotime( $card['date'] );
	if ( $timestamp ) {
		$formatted_date = date_i18n( get_option( 'date_format' ), $timestamp );
	}
}

// أيقونات الوسائط SVG
$media_icons = array(
	'audio' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>',
	'video' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>',
	'text'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>',
);
?>

<article class="howarat-card" tabindex="0">

	<?php if ( ! empty( $card['image'] ) ) : ?>
		<!-- صورة الحوار -->
		<div class="card-image">
			<a href="<?php echo esc_url( $card['cta_link'] ); ?>" aria-label="<?php echo esc_attr( $card['title'] ); ?>">
				<img src="<?php echo esc_url( $card['image'] ); ?>"
				     alt="<?php echo esc_attr( $card['title'] ); ?>"
				     loading="lazy"
				     width="400"
				     height="300">
			</a>

			<?php if ( $show_media_icon && isset( $media_icons[ $card['type'] ] ) ) : ?>
				<!-- أيقونة نوع الوسائط -->
				<span class="media-icon media-icon-<?php echo esc_attr( $card['type'] ); ?>"
				      aria-label="<?php echo esc_attr( ucfirst( $card['type'] ) ); ?>">
					<?php echo $media_icons[ $card['type'] ]; ?>
				</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- محتوى البطاقة -->
	<div class="card-content">

		<?php if ( ! empty( $formatted_date ) ) : ?>
			<!-- تاريخ الحوار -->
			<div class="card-meta">
				<time class="card-date" datetime="<?php echo esc_attr( $card['date'] ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM9 14H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2zm-8 4H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z"/>
					</svg>
					<?php echo esc_html( $formatted_date ); ?>
				</time>
			</div>
		<?php endif; ?>

		<!-- عنوان الحوار -->
		<h3 class="card-title">
			<a href="<?php echo esc_url( $card['cta_link'] ); ?>">
				<?php echo esc_html( $card['title'] ); ?>
			</a>
		</h3>

		<?php if ( ! empty( $card['excerpt'] ) ) : ?>
			<!-- مقتطف الحوار -->
			<p class="card-excerpt">
				<?php echo esc_html( $card['excerpt'] ); ?>
			</p>
		<?php endif; ?>

		<!-- زر الإجراء -->
		<div class="card-cta">
			<a href="<?php echo esc_url( $card['cta_link'] ); ?>"
			   class="cta-button"
			   aria-label="<?php echo esc_attr( sprintf( __( '%s - %s', 'nadiim' ), $card['cta_text'], $card['title'] ) ); ?>">
				<?php echo esc_html( $card['cta_text'] ); ?>
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
				</svg>
			</a>
		</div>

	</div><!-- .card-content -->

</article><!-- .howarat-card -->
