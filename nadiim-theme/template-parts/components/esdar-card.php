<?php
/**
 * Component: بطاقة الإصدار
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على بيانات الإصدار
$item = get_query_var( 'esdar_item', array() );

if ( empty( $item ) ) {
	return;
}

// الحصول على الإعدادات
$show_download = get_theme_mod( 'esdar_card_show_download', true );
$show_badge    = get_theme_mod( 'esdar_card_show_badge', true );

// قيم افتراضية
$title        = isset( $item['title'] ) ? $item['title'] : __( 'بدون عنوان', 'nadiim' );
$excerpt      = isset( $item['excerpt'] ) ? $item['excerpt'] : '';
$image        = isset( $item['image'] ) ? $item['image'] : get_template_directory_uri() . '/assets/images/placeholder-book.jpg';
$type         = isset( $item['type'] ) ? $item['type'] : 'book';
$type_label   = isset( $item['type_label'] ) ? $item['type_label'] : __( 'كتاب', 'nadiim' );
$date         = isset( $item['date'] ) ? $item['date'] : '';
$pages        = isset( $item['pages'] ) ? $item['pages'] : '';
$format       = isset( $item['format'] ) ? $item['format'] : '';
$download_url = isset( $item['download_url'] ) ? $item['download_url'] : '';
$view_link    = isset( $item['view_link'] ) ? $item['view_link'] : '#';

// تنسيق التاريخ
if ( ! empty( $date ) ) {
	$date_obj = date_create( $date );
	if ( $date_obj ) {
		$date_formatted = date_i18n( get_option( 'date_format' ), $date_obj->getTimestamp() );
	} else {
		$date_formatted = $date;
	}
} else {
	$date_formatted = '';
}
?>

<article class="esdar-card" tabindex="0" aria-label="<?php echo esc_attr( sprintf( __( 'إصدار: %s', 'nadiim' ), $title ) ); ?>">

	<!-- غلاف الإصدار -->
	<div class="esdar-card-image">
		<img src="<?php echo esc_url( $image ); ?>"
		     alt="<?php echo esc_attr( $title ); ?>"
		     loading="lazy"
		     class="cover-image" />

		<?php if ( $show_badge && ! empty( $type_label ) ) : ?>
			<!-- شارة نوع الإصدار -->
			<span class="esdar-type-badge type-<?php echo esc_attr( $type ); ?>">
				<?php echo esc_html( $type_label ); ?>
			</span>
		<?php endif; ?>
	</div><!-- .esdar-card-image -->

	<!-- محتوى البطاقة -->
	<div class="esdar-card-content">

		<!-- عنوان الإصدار -->
		<h3 class="esdar-card-title">
			<a href="<?php echo esc_url( $view_link ); ?>" class="card-title-link">
				<?php echo esc_html( $title ); ?>
			</a>
		</h3>

		<!-- الوصف -->
		<?php if ( ! empty( $excerpt ) ) : ?>
			<p class="esdar-card-excerpt">
				<?php echo esc_html( $excerpt ); ?>
			</p>
		<?php endif; ?>

		<!-- معلومات إضافية (Meta) -->
		<div class="esdar-card-meta">

			<?php if ( ! empty( $date_formatted ) ) : ?>
				<span class="meta-item meta-date">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
					</svg>
					<time datetime="<?php echo esc_attr( $date ); ?>"><?php echo esc_html( $date_formatted ); ?></time>
				</span>
			<?php endif; ?>

			<?php if ( ! empty( $pages ) ) : ?>
				<span class="meta-item meta-pages">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
					</svg>
					<span><?php echo esc_html( sprintf( _n( '%s صفحة', '%s صفحة', $pages, 'nadiim' ), number_format_i18n( $pages ) ) ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( ! empty( $format ) ) : ?>
				<span class="meta-item meta-format">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
					</svg>
					<span><?php echo esc_html( strtoupper( $format ) ); ?></span>
				</span>
			<?php endif; ?>

		</div><!-- .esdar-card-meta -->

		<!-- أزرار الإجراء -->
		<div class="esdar-card-actions">

			<!-- زر العرض -->
			<a href="<?php echo esc_url( $view_link ); ?>"
			   class="action-button button-view"
			   aria-label="<?php echo esc_attr( sprintf( __( 'عرض: %s', 'nadiim' ), $title ) ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
				</svg>
				<span><?php _e( 'عرض', 'nadiim' ); ?></span>
			</a>

			<!-- زر التحميل -->
			<?php if ( $show_download && ! empty( $download_url ) ) : ?>
				<a href="<?php echo esc_url( $download_url ); ?>"
				   class="action-button button-download"
				   download
				   aria-label="<?php echo esc_attr( sprintf( __( 'تحميل: %s', 'nadiim' ), $title ) ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2v9.67z"/>
					</svg>
					<span><?php _e( 'تحميل', 'nadiim' ); ?></span>
				</a>
			<?php endif; ?>

		</div><!-- .esdar-card-actions -->

	</div><!-- .esdar-card-content -->

</article><!-- .esdar-card -->
