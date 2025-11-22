<?php
/**
 * Component: بطاقة الإصدار - تصميم عصري
 *
 * @package Nadiim
 * @since 2.1.0
 */

// الحصول على بيانات الإصدار
$item = get_query_var( 'esdar_item', array() );
$index = get_query_var( 'esdar_index', 0 );

if ( empty( $item ) ) {
	return;
}

// قيم افتراضية
$title        = isset( $item['title'] ) ? $item['title'] : __( 'بدون عنوان', 'nadiim' );
$excerpt      = isset( $item['excerpt'] ) ? $item['excerpt'] : '';
$image        = isset( $item['image'] ) ? $item['image'] : get_template_directory_uri() . '/assets/images/placeholder-book.jpg';
$type         = isset( $item['type'] ) ? $item['type'] : 'book';
$type_label   = isset( $item['type_label'] ) ? $item['type_label'] : __( 'كتاب', 'nadiim' );
$date         = isset( $item['date'] ) ? $item['date'] : '';
$pages        = isset( $item['pages'] ) ? $item['pages'] : '';
$format       = isset( $item['format'] ) ? $item['format'] : 'PDF';
$download_url = isset( $item['download_url'] ) ? $item['download_url'] : '';
$view_link    = isset( $item['view_link'] ) ? $item['view_link'] : '#';

// تنسيق التاريخ
if ( ! empty( $date ) ) {
	$date_obj = date_create( $date );
	if ( $date_obj ) {
		$year = date_i18n( 'Y', $date_obj->getTimestamp() );
	} else {
		$year = '';
	}
} else {
	$year = '';
}

// تحديد تأخير الأنيميشن
$animation_delay = ( $index * 50 );
?>

<article class="esdar-card-modern" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $animation_delay ); ?>">

	<!-- غلاف الإصدار -->
	<div class="esdar-card-cover">
		<a href="<?php echo esc_url( $view_link ); ?>" class="cover-link" aria-label="<?php echo esc_attr( sprintf( __( 'عرض: %s', 'nadiim' ), $title ) ); ?>">
			<div class="cover-image-wrapper">
				<img src="<?php echo esc_url( $image ); ?>"
				     alt="<?php echo esc_attr( $title ); ?>"
				     loading="lazy"
				     class="cover-image" />
			</div>

			<!-- Overlay عند التمرير -->
			<div class="cover-overlay">
				<div class="overlay-content">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
					</svg>
					<span><?php esc_html_e( 'عرض التفاصيل', 'nadiim' ); ?></span>
				</div>
			</div>
		</a>

		<!-- شارة نوع الإصدار -->
		<div class="type-badge badge-<?php echo esc_attr( $type ); ?>">
			<span><?php echo esc_html( $type_label ); ?></span>
		</div>

		<!-- شارة الصيغة -->
		<?php if ( ! empty( $format ) ) : ?>
			<?php
			// التعامل مع الصيغة سواء كانت string أو array
			$format_display = is_array( $format ) ? ( ! empty( $format ) ? $format[0] : 'PDF' ) : $format;
			?>
			<div class="format-badge">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
				</svg>
				<span><?php echo esc_html( strtoupper( $format_display ) ); ?></span>
			</div>
		<?php endif; ?>
	</div><!-- .esdar-card-cover -->

	<!-- محتوى البطاقة -->
	<div class="esdar-card-body">

		<!-- عنوان الإصدار -->
		<h3 class="card-title">
			<a href="<?php echo esc_url( $view_link ); ?>" class="title-link">
				<?php echo esc_html( $title ); ?>
			</a>
		</h3>

		<!-- المعلومات الإضافية -->
		<div class="card-meta">
			<?php if ( ! empty( $year ) ) : ?>
				<span class="meta-item">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
					</svg>
					<span><?php echo esc_html( $year ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( ! empty( $pages ) ) : ?>
				<span class="meta-item">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
					</svg>
					<span><?php echo esc_html( sprintf( _n( '%s ص', '%s ص', $pages, 'nadiim' ), number_format_i18n( $pages ) ) ); ?></span>
				</span>
			<?php endif; ?>
		</div><!-- .card-meta -->

		<!-- أزرار الإجراء -->
		<div class="card-actions">
			<a href="<?php echo esc_url( $view_link ); ?>" class="btn-action btn-view">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
				</svg>
				<span><?php esc_html_e( 'عرض', 'nadiim' ); ?></span>
			</a>

			<?php if ( ! empty( $download_url ) ) : ?>
				<a href="<?php echo esc_url( $download_url ); ?>" class="btn-action btn-download" download>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2v9.67z"/>
					</svg>
					<span><?php esc_html_e( 'تحميل', 'nadiim' ); ?></span>
				</a>
			<?php endif; ?>
		</div><!-- .card-actions -->

	</div><!-- .esdar-card-body -->

</article><!-- .esdar-card-modern -->
