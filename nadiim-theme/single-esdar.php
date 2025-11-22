<?php
/**
 * قالب عرض الإصدار المفرد - تصميم عصري
 *
 * @package Nadiim
 * @since 2.1.0
 */

get_header();

// الحصول على بيانات الإصدار مع قيم افتراضية
$meta = nadiim_get_esdar_meta( get_the_ID() );

// إذا كانت البيانات فارغة، استخدم قيم افتراضية
if ( ! $meta || ! is_array( $meta ) ) {
	$meta = array(
		'release_type'           => 'book',
		'release_date'           => '',
		'release_authors'        => array(),
		'release_isbn'           => '',
		'release_pages'          => '',
		'release_language'       => 'ar',
		'release_format'         => array(),
		'release_excerpt'        => '',
		'release_preview_images' => array(),
		'release_preview_embed'  => '',
		'release_download_count' => 0,
	);
}

// تحميل CSS الخاص بالصفحة
wp_enqueue_style( 'nadiim-esdar-single', get_template_directory_uri() . '/assets/css/esdar-single.css', array(), '2.1.0' );
?>

<main id="primary" class="site-main esdar-single-modern">

	<?php
	while ( have_posts() ) :
		the_post();
	?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'esdar-article-modern' ); ?>>

			<!-- Hero Section العصري -->
			<section class="esdar-hero-modern">
				<div class="esdar-hero-bg-pattern" aria-hidden="true">
					<div class="pattern-shape pattern-shape-1"></div>
					<div class="pattern-shape pattern-shape-2"></div>
				</div>

				<div class="section-container">
					<div class="esdar-hero-inner">

						<!-- Cover Image -->
						<div class="esdar-hero-cover" data-aos="fade-left">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="cover-wrapper">
									<?php
									the_post_thumbnail( 'large', array(
										'class' => 'esdar-cover-image',
										'alt'   => get_the_title(),
									) );
									?>
									<div class="cover-shadow"></div>
								</div>
							<?php else : ?>
								<div class="esdar-cover-placeholder">
									<?php echo nadiim_get_esdar_type_icon( $meta['release_type'] ); ?>
								</div>
							<?php endif; ?>

							<!-- شارة النوع -->
							<div class="type-badge-modern badge-<?php echo esc_attr( $meta['release_type'] ); ?>">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
									<path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
								</svg>
								<span><?php echo esc_html( nadiim_get_esdar_type_label( $meta['release_type'] ) ); ?></span>
							</div>
						</div>

						<!-- Metadata Panel العصري -->
						<div class="esdar-hero-meta" data-aos="fade-right">

							<!-- العنوان -->
							<h1 class="esdar-title-modern"><?php the_title(); ?></h1>

							<!-- المقتطف القصير -->
							<?php if ( ! empty( $meta['release_excerpt'] ) ) : ?>
								<div class="esdar-excerpt-modern">
									<?php echo wpautop( esc_html( $meta['release_excerpt'] ) ); ?>
								</div>
							<?php endif; ?>

							<!-- المعلومات الأساسية -->
							<div class="esdar-info-modern">

								<!-- المؤلفون -->
								<?php if ( ! empty( $meta['release_authors'] ) ) : ?>
									<div class="info-item">
										<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
											<circle cx="12" cy="7" r="4"/>
										</svg>
										<div class="info-content">
											<span class="info-label"><?php _e( 'المؤلف', 'nadiim' ); ?></span>
											<span class="info-value">
												<?php echo nadiim_get_esdar_authors( get_the_ID(), '، ', true ); ?>
											</span>
										</div>
									</div>
								<?php endif; ?>

								<!-- تاريخ الإصدار -->
								<?php if ( ! empty( $meta['release_date'] ) ) : ?>
									<div class="info-item">
										<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
											<line x1="16" y1="2" x2="16" y2="6"/>
											<line x1="8" y1="2" x2="8" y2="6"/>
											<line x1="3" y1="10" x2="21" y2="10"/>
										</svg>
										<div class="info-content">
											<span class="info-label"><?php _e( 'تاريخ الإصدار', 'nadiim' ); ?></span>
											<time class="info-value" datetime="<?php echo esc_attr( $meta['release_date'] ); ?>">
												<?php echo date_i18n( get_option( 'date_format' ), strtotime( $meta['release_date'] ) ); ?>
											</time>
										</div>
									</div>
								<?php endif; ?>

								<!-- عدد الصفحات -->
								<?php if ( ! empty( $meta['release_pages'] ) ) : ?>
									<div class="info-item">
										<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
											<polyline points="14 2 14 8 20 8"/>
										</svg>
										<div class="info-content">
											<span class="info-label"><?php _e( 'عدد الصفحات', 'nadiim' ); ?></span>
											<span class="info-value">
												<?php echo number_format_i18n( $meta['release_pages'] ); ?> <?php _e( 'صفحة', 'nadiim' ); ?>
											</span>
										</div>
									</div>
								<?php endif; ?>

								<!-- اللغة -->
								<?php if ( ! empty( $meta['release_language'] ) ) : ?>
									<div class="info-item">
										<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<circle cx="12" cy="12" r="10"/>
											<line x1="2" y1="12" x2="22" y2="12"/>
											<path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
										</svg>
										<div class="info-content">
											<span class="info-label"><?php _e( 'اللغة', 'nadiim' ); ?></span>
											<span class="info-value">
												<?php
												$langs = array(
													'ar'    => 'العربية',
													'en'    => 'الإنجليزية',
													'fr'    => 'الفرنسية',
													'other' => 'أخرى',
												);
												echo isset( $langs[ $meta['release_language'] ] ) ? esc_html( $langs[ $meta['release_language'] ] ) : esc_html( $meta['release_language'] );
												?>
											</span>
										</div>
									</div>
								<?php endif; ?>

								<!-- ISBN -->
								<?php if ( ! empty( $meta['release_isbn'] ) ) : ?>
									<div class="info-item">
										<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<rect x="3" y="3" width="18" height="18" rx="2"/>
											<path d="M7 7h.01M7 12h.01M7 17h.01M12 7h5M12 12h5M12 17h5"/>
										</svg>
										<div class="info-content">
											<span class="info-label">ISBN</span>
											<span class="info-value"><?php echo esc_html( $meta['release_isbn'] ); ?></span>
										</div>
									</div>
								<?php endif; ?>

							</div><!-- .esdar-info-modern -->

							<!-- الصيغ المتوفرة -->
							<?php if ( ! empty( $meta['release_format'] ) ) : ?>
								<div class="esdar-formats-modern">
									<span class="formats-label"><?php _e( 'الصيغ المتوفرة:', 'nadiim' ); ?></span>
									<div class="formats-badges">
										<?php echo nadiim_get_esdar_format_badges( get_the_ID() ); ?>
									</div>
								</div>
							<?php endif; ?>

							<!-- أزرار التحميل والمعاينة -->
							<div class="esdar-actions-modern">

								<?php
								$download_url = nadiim_get_esdar_download_url( get_the_ID() );
								if ( $download_url ) :
								?>
									<a href="<?php echo esc_url( $download_url ); ?>"
									   class="btn-modern btn-primary btn-download"
									   data-post-id="<?php echo get_the_ID(); ?>"
									   download>
										<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
											<polyline points="7 10 12 15 17 10"/>
											<line x1="12" y1="15" x2="12" y2="3"/>
										</svg>
										<span><?php _e( 'تحميل الإصدار', 'nadiim' ); ?></span>
									</a>
								<?php endif; ?>

								<?php if ( nadiim_esdar_has_preview( get_the_ID() ) ) : ?>
									<a href="#esdar-preview-section" class="btn-modern btn-secondary btn-preview">
										<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
											<circle cx="12" cy="12" r="3"/>
										</svg>
										<span><?php _e( 'معاينة', 'nadiim' ); ?></span>
									</a>
								<?php endif; ?>

							</div><!-- .esdar-actions-modern -->

							<!-- إحصائيات التحميل -->
							<?php if ( isset( $meta['release_download_count'] ) && $meta['release_download_count'] > 0 ) : ?>
								<div class="esdar-stats-modern">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<polyline points="8 17 12 21 16 17"/>
										<line x1="12" y1="12" x2="12" y2="21"/>
										<path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"/>
									</svg>
									<span>
										<?php
										printf(
											_n( 'تم التحميل %s مرة', 'تم التحميل %s مرة', $meta['release_download_count'], 'nadiim' ),
											'<strong>' . number_format_i18n( $meta['release_download_count'] ) . '</strong>'
										);
										?>
									</span>
								</div>
							<?php endif; ?>

						</div><!-- .esdar-hero-meta -->

					</div><!-- .esdar-hero-inner -->
				</div><!-- .section-container -->
			</section><!-- .esdar-hero-modern -->

			<!-- قسم المحتوى -->
			<div class="section-container esdar-content-container">

				<!-- المعاينة -->
				<?php if ( nadiim_esdar_has_preview( get_the_ID() ) ) : ?>
					<section id="esdar-preview-section" class="esdar-preview-section-modern" data-aos="fade-up">
						<h2 class="section-title-modern">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
								<circle cx="12" cy="12" r="3"/>
							</svg>
							<?php _e( 'معاينة الإصدار', 'nadiim' ); ?>
						</h2>

						<?php if ( ! empty( $meta['release_preview_embed'] ) ) : ?>
							<div class="esdar-preview-embed">
								<iframe
									src="<?php echo esc_url( $meta['release_preview_embed'] ); ?>"
									allowfullscreen
									loading="lazy"
								></iframe>
							</div>
						<?php elseif ( ! empty( $meta['release_preview_images'] ) ) : ?>
							<?php echo nadiim_get_esdar_preview_gallery( get_the_ID(), 'large' ); ?>
						<?php endif; ?>
					</section>
				<?php endif; ?>

				<!-- المحتوى الرئيسي -->
				<?php if ( get_the_content() ) : ?>
					<section class="esdar-content-section-modern" data-aos="fade-up">
						<h2 class="section-title-modern">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
								<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
							</svg>
							<?php _e( 'عن الإصدار', 'nadiim' ); ?>
						</h2>
						<div class="entry-content-modern">
							<?php the_content(); ?>
						</div>
					</section>
				<?php endif; ?>

				<!-- إصدارات ذات صلة -->
				<?php
				$related_query = nadiim_get_recent_esdar( 3, array(
					'post__not_in' => array( get_the_ID() ),
					'tax_query'    => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => wp_get_post_categories( get_the_ID() ),
						),
					),
				) );

				if ( $related_query->have_posts() ) :
				?>
					<section class="esdar-related-section-modern" data-aos="fade-up">
						<h2 class="section-title-modern">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
							</svg>
							<?php _e( 'إصدارات ذات صلة', 'nadiim' ); ?>
						</h2>

						<div class="esdar-related-grid">
							<?php
							$index = 0;
							while ( $related_query->have_posts() ) :
								$related_query->the_post();
								$related_id = get_the_ID();
								$related_meta = nadiim_get_esdar_meta( $related_id );

								$image_url = get_the_post_thumbnail_url( $related_id, 'large' );
								if ( ! $image_url ) {
									$image_url = get_template_directory_uri() . '/assets/images/placeholder-book.jpg';
								}

								$item = array(
									'title'        => get_the_title(),
									'excerpt'      => wp_trim_words( get_the_excerpt(), 12, '...' ),
									'image'        => $image_url,
									'type'         => isset( $related_meta['release_type'] ) ? $related_meta['release_type'] : 'book',
									'type_label'   => nadiim_get_esdar_type_label( isset( $related_meta['release_type'] ) ? $related_meta['release_type'] : 'book' ),
									'date'         => isset( $related_meta['release_date'] ) ? $related_meta['release_date'] : get_the_date( 'Y-m-d' ),
									'pages'        => isset( $related_meta['release_pages'] ) ? $related_meta['release_pages'] : '',
									'format'       => isset( $related_meta['release_format'] ) ? $related_meta['release_format'] : 'PDF',
									'download_url' => nadiim_get_esdar_download_url( $related_id ),
									'view_link'    => get_permalink(),
								);

								set_query_var( 'esdar_item', $item );
								set_query_var( 'esdar_index', $index );
								get_template_part( 'template-parts/components/esdar-card-modern' );
								$index++;
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</section>
				<?php endif; ?>

				<!-- التصنيفات والمشاركة -->
				<section class="esdar-footer-section-modern" data-aos="fade-up">

					<!-- التصنيفات والوسوم -->
					<?php
					$categories = get_the_category();
					$tags       = get_the_tags();
					if ( $categories || $tags ) :
					?>
						<div class="esdar-taxonomy-modern">
							<?php if ( $categories ) : ?>
								<div class="taxonomy-group">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
										<line x1="7" y1="7" x2="7.01" y2="7"/>
									</svg>
									<div class="taxonomy-items">
										<?php
										foreach ( $categories as $category ) {
											echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="taxonomy-item">' . esc_html( $category->name ) . '</a>';
										}
										?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $tags ) : ?>
								<div class="taxonomy-group">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<line x1="4" y1="9" x2="20" y2="9"/>
										<line x1="4" y1="15" x2="20" y2="15"/>
										<line x1="10" y1="3" x2="8" y2="21"/>
										<line x1="16" y1="3" x2="14" y2="21"/>
									</svg>
									<div class="taxonomy-items">
										<?php
										foreach ( $tags as $tag ) {
											echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="taxonomy-item">' . esc_html( $tag->name ) . '</a>';
										}
										?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<!-- أزرار المشاركة -->
					<div class="esdar-share-modern">
						<span class="share-label"><?php _e( 'مشاركة:', 'nadiim' ); ?></span>
						<div class="share-buttons">
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
							   target="_blank"
							   rel="noopener"
							   class="share-btn share-facebook"
							   aria-label="<?php _e( 'مشاركة على فيسبوك', 'nadiim' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
									<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
								</svg>
							</a>
							<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>"
							   target="_blank"
							   rel="noopener"
							   class="share-btn share-twitter"
							   aria-label="<?php _e( 'مشاركة على تويتر', 'nadiim' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
									<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
								</svg>
							</a>
							<a href="https://wa.me/?text=<?php echo urlencode( get_the_title() . ' - ' . get_permalink() ); ?>"
							   target="_blank"
							   rel="noopener"
							   class="share-btn share-whatsapp"
							   aria-label="<?php _e( 'مشاركة على واتساب', 'nadiim' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
									<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
								</svg>
							</a>
							<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>"
							   target="_blank"
							   rel="noopener"
							   class="share-btn share-linkedin"
							   aria-label="<?php _e( 'مشاركة على لينكدإن', 'nadiim' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
									<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
								</svg>
							</a>
						</div>
					</div>

				</section>

			</div><!-- .esdar-content-container -->

		</article>

	<?php endwhile; ?>

	<!-- JSON-LD Schema -->
	<?php
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => $meta['release_type'] === 'book' ? 'Book' : 'CreativeWork',
		'name'        => get_the_title(),
		'description' => ! empty( $meta['release_excerpt'] ) ? $meta['release_excerpt'] : get_the_excerpt(),
		'url'         => get_permalink(),
	);

	if ( has_post_thumbnail() ) {
		$schema['image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}

	if ( ! empty( $meta['release_date'] ) ) {
		$schema['datePublished'] = $meta['release_date'];
	}

	if ( ! empty( $meta['release_isbn'] ) ) {
		$schema['isbn'] = $meta['release_isbn'];
	}

	if ( ! empty( $meta['release_authors'] ) ) {
		$schema['author'] = array();
		foreach ( $meta['release_authors'] as $author ) {
			if ( $author['type'] === 'user' && ! empty( $author['id'] ) ) {
				$user = get_userdata( $author['id'] );
				if ( $user ) {
					$schema['author'][] = array(
						'@type' => 'Person',
						'name'  => $user->display_name,
					);
				}
			} elseif ( $author['type'] === 'free' && ! empty( $author['name'] ) ) {
				$schema['author'][] = array(
					'@type' => 'Person',
					'name'  => $author['name'],
				);
			}
		}
	}

	$download_url = nadiim_get_esdar_download_url( get_the_ID() );
	if ( $download_url && ! empty( $meta['release_format'] ) ) {
		$schema['encoding'] = array();
		foreach ( $meta['release_format'] as $format ) {
			$schema['encoding'][] = array(
				'@type'          => 'MediaObject',
				'encodingFormat' => 'application/' . $format,
				'contentUrl'     => $download_url,
			);
		}
	}
	?>

	<script type="application/ld+json">
	<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
	</script>

</main>

<?php
get_footer();
