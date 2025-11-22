<?php
/**
 * قالب أرشيف الإصدارات - تصميم عصري
 *
 * @package Nadiim
 * @since 2.1.0
 */

get_header();

// تحميل CSS الخاص بالصفحة
wp_enqueue_style( 'nadiim-esdar-archive', get_template_directory_uri() . '/assets/css/esdar-archive.css', array(), '2.1.0' );
?>

<main id="primary" class="site-main esdar-archive-modern">

	<!-- Hero Section العصري -->
	<section class="esdar-archive-hero-modern">
		<div class="hero-bg-pattern" aria-hidden="true">
			<div class="pattern-circle pattern-circle-1"></div>
			<div class="pattern-circle pattern-circle-2"></div>
			<div class="pattern-circle pattern-circle-3"></div>
		</div>

		<div class="section-container">
			<div class="archive-header-modern" data-aos="fade-up">

				<!-- أيقونة القسم -->
				<div class="header-icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
						<path d="M9 2v20M15 2v20"/>
					</svg>
				</div>

				<!-- العنوان -->
				<h1 class="archive-title-modern">
					<?php
					if ( is_category() || is_tag() ) {
						single_term_title();
					} else {
						_e( 'مكتبة الإصدارات', 'nadiim' );
					}
					?>
				</h1>

				<!-- الوصف -->
				<?php
				$description = '';
				if ( is_category() || is_tag() ) {
					$description = term_description();
				} else {
					$description = __( 'استكشف مجموعتنا الكاملة من الكتب والمجلات والتقارير والكتيبات الرقمية', 'nadiim' );
				}

				if ( $description ) :
				?>
					<div class="archive-description-modern">
						<?php echo wp_kses_post( $description ); ?>
					</div>
				<?php endif; ?>

			</div><!-- .archive-header-modern -->
		</div><!-- .section-container -->
	</section><!-- .esdar-archive-hero-modern -->

	<!-- Filter Bar العصري -->
	<section class="esdar-filter-section-modern">
		<div class="section-container">
			<form class="esdar-filter-bar-modern" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>">

				<!-- نوع الإصدار -->
				<div class="filter-item">
					<label for="filter-type" class="filter-label">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
						</svg>
						<?php _e( 'النوع', 'nadiim' ); ?>
					</label>
					<select name="release_type" id="filter-type" class="filter-select">
						<option value=""><?php _e( 'جميع الأنواع', 'nadiim' ); ?></option>
						<?php
						$types = array(
							'book'     => __( 'كتاب', 'nadiim' ),
							'magazine' => __( 'مجلة', 'nadiim' ),
							'brochure' => __( 'كتيب', 'nadiim' ),
							'report'   => __( 'تقرير', 'nadiim' ),
							'issue'    => __( 'عدد', 'nadiim' ),
						);

						$selected_type = isset( $_GET['release_type'] ) ? sanitize_text_field( $_GET['release_type'] ) : '';
						foreach ( $types as $key => $label ) {
							$selected = $selected_type === $key ? 'selected' : '';
							echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $label ) . '</option>';
						}
						?>
					</select>
				</div>

				<!-- السنة -->
				<div class="filter-item">
					<label for="filter-year" class="filter-label">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
							<line x1="16" y1="2" x2="16" y2="6"/>
							<line x1="8" y1="2" x2="8" y2="6"/>
							<line x1="3" y1="10" x2="21" y2="10"/>
						</svg>
						<?php _e( 'السنة', 'nadiim' ); ?>
					</label>
					<select name="release_year" id="filter-year" class="filter-select">
						<option value=""><?php _e( 'جميع السنوات', 'nadiim' ); ?></option>
						<?php
						// توليد قائمة السنوات من 2010 إلى السنة الحالية
						$current_year  = gmdate( 'Y' );
						$selected_year = isset( $_GET['release_year'] ) ? sanitize_text_field( $_GET['release_year'] ) : '';

						for ( $year = $current_year; $year >= 2010; $year-- ) {
							$selected = $selected_year == $year ? 'selected' : '';
							echo '<option value="' . esc_attr( $year ) . '" ' . $selected . '>' . esc_html( $year ) . '</option>';
						}
						?>
					</select>
				</div>

				<!-- التصنيف -->
				<div class="filter-item">
					<label for="filter-category" class="filter-label">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
							<line x1="7" y1="7" x2="7.01" y2="7"/>
						</svg>
						<?php _e( 'التصنيف', 'nadiim' ); ?>
					</label>
					<select name="category" id="filter-category" class="filter-select">
						<option value=""><?php _e( 'جميع التصنيفات', 'nadiim' ); ?></option>
						<?php
						$categories  = get_categories( array(
							'taxonomy'   => 'category',
							'hide_empty' => true,
						) );
						$selected_cat = isset( $_GET['category'] ) ? intval( $_GET['category'] ) : 0;
						foreach ( $categories as $cat ) {
							$selected = $selected_cat === $cat->term_id ? 'selected' : '';
							echo '<option value="' . esc_attr( $cat->term_id ) . '" ' . $selected . '>' . esc_html( $cat->name ) . '</option>';
						}
						?>
					</select>
				</div>

				<!-- زر الفلترة والإعادة -->
				<div class="filter-actions">
					<button type="submit" class="filter-btn btn-primary">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
						</svg>
						<span><?php _e( 'تصفية', 'nadiim' ); ?></span>
					</button>

					<?php if ( ! empty( $_GET['release_type'] ) || ! empty( $_GET['release_year'] ) || ! empty( $_GET['category'] ) ) : ?>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>" class="filter-reset">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<line x1="18" y1="6" x2="6" y2="18"/>
								<line x1="6" y1="6" x2="18" y2="18"/>
							</svg>
							<span><?php _e( 'إعادة تعيين', 'nadiim' ); ?></span>
						</a>
					<?php endif; ?>
				</div>

			</form>
		</div><!-- .section-container -->
	</section><!-- .esdar-filter-section-modern -->

	<!-- Grid Section -->
	<section class="esdar-grid-section-modern">
		<div class="section-container">

			<?php
			// تطبيق الفلاتر
			$paged    = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$per_page = get_theme_mod( 'esdar_archive_per_page', 12 );

			$args = array(
				'post_type'      => 'esdar',
				'posts_per_page' => $per_page,
				'paged'          => $paged,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			// فلتر التصنيف
			if ( ! empty( $_GET['category'] ) ) {
				$args['cat'] = intval( $_GET['category'] );
			}

			// فلتر حسب النوع والسنة
			if ( ! empty( $_GET['release_type'] ) || ! empty( $_GET['release_year'] ) ) {
				$args['posts_per_page'] = -1;
			}

			$query = new WP_Query( $args );

			// فلترة يدوية إذا لزم الأمر
			if ( ! empty( $_GET['release_type'] ) || ! empty( $_GET['release_year'] ) ) {
				$filtered_posts = array();

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$meta = nadiim_get_esdar_meta( get_the_ID() );

						$type_match = empty( $_GET['release_type'] ) || ( isset( $meta['release_type'] ) && $meta['release_type'] === $_GET['release_type'] );
						$year_match = empty( $_GET['release_year'] ) || ( isset( $meta['release_date'] ) && date( 'Y', strtotime( $meta['release_date'] ) ) == $_GET['release_year'] );

						if ( $type_match && $year_match ) {
							$filtered_posts[] = get_post();
						}
					}
					wp_reset_postdata();
				}

				// إعادة بناء query بالنتائج المفلترة
				$query->posts      = array_slice( $filtered_posts, ( $paged - 1 ) * $per_page, $per_page );
				$query->post_count = count( $query->posts );
				$query->found_posts = count( $filtered_posts );
				$query->max_num_pages = ceil( count( $filtered_posts ) / $per_page );
			}

			if ( $query->have_posts() && $query->post_count > 0 ) :
			?>

				<!-- عدد النتائج -->
				<div class="results-count" data-aos="fade-up">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="8"/>
						<path d="m21 21-4.35-4.35"/>
					</svg>
					<span>
						<?php
						printf(
							_n( 'تم العثور على %s إصدار', 'تم العثور على %s إصدار', $query->found_posts, 'nadiim' ),
							'<strong>' . number_format_i18n( $query->found_posts ) . '</strong>'
						);
						?>
					</span>
				</div>

				<!-- Grid -->
				<div class="esdar-archive-grid" data-aos="fade-up" data-aos-delay="100">
					<?php
					if ( ! empty( $_GET['release_type'] ) || ! empty( $_GET['release_year'] ) ) {
						// عرض المنشورات المفلترة
						$index = 0;
						foreach ( $query->posts as $post ) {
							setup_postdata( $post );
							$post_id   = get_the_ID();
							$post_meta = nadiim_get_esdar_meta( $post_id );

							$image_url = get_the_post_thumbnail_url( $post_id, 'large' );
							if ( ! $image_url ) {
								$image_url = get_template_directory_uri() . '/assets/images/placeholder-book.jpg';
							}

							$item = array(
								'title'        => get_the_title(),
								'excerpt'      => wp_trim_words( get_the_excerpt(), 12, '...' ),
								'image'        => $image_url,
								'type'         => isset( $post_meta['release_type'] ) ? $post_meta['release_type'] : 'book',
								'type_label'   => nadiim_get_esdar_type_label( isset( $post_meta['release_type'] ) ? $post_meta['release_type'] : 'book' ),
								'date'         => isset( $post_meta['release_date'] ) ? $post_meta['release_date'] : get_the_date( 'Y-m-d' ),
								'pages'        => isset( $post_meta['release_pages'] ) ? $post_meta['release_pages'] : '',
								'format'       => isset( $post_meta['release_format'] ) ? $post_meta['release_format'] : 'PDF',
								'download_url' => nadiim_get_esdar_download_url( $post_id ),
								'view_link'    => get_permalink(),
							);

							set_query_var( 'esdar_item', $item );
							set_query_var( 'esdar_index', $index );
							get_template_part( 'template-parts/components/esdar-card-modern' );
							$index++;
						}
						wp_reset_postdata();
					} else {
						// عرض عادي
						$index = 0;
						while ( $query->have_posts() ) {
							$query->the_post();
							$post_id   = get_the_ID();
							$post_meta = nadiim_get_esdar_meta( $post_id );

							$image_url = get_the_post_thumbnail_url( $post_id, 'large' );
							if ( ! $image_url ) {
								$image_url = get_template_directory_uri() . '/assets/images/placeholder-book.jpg';
							}

							$item = array(
								'title'        => get_the_title(),
								'excerpt'      => wp_trim_words( get_the_excerpt(), 12, '...' ),
								'image'        => $image_url,
								'type'         => isset( $post_meta['release_type'] ) ? $post_meta['release_type'] : 'book',
								'type_label'   => nadiim_get_esdar_type_label( isset( $post_meta['release_type'] ) ? $post_meta['release_type'] : 'book' ),
								'date'         => isset( $post_meta['release_date'] ) ? $post_meta['release_date'] : get_the_date( 'Y-m-d' ),
								'pages'        => isset( $post_meta['release_pages'] ) ? $post_meta['release_pages'] : '',
								'format'       => isset( $post_meta['release_format'] ) ? $post_meta['release_format'] : 'PDF',
								'download_url' => nadiim_get_esdar_download_url( $post_id ),
								'view_link'    => get_permalink(),
							);

							set_query_var( 'esdar_item', $item );
							set_query_var( 'esdar_index', $index );
							get_template_part( 'template-parts/components/esdar-card-modern' );
							$index++;
						}
						wp_reset_postdata();
					}
					?>
				</div><!-- .esdar-archive-grid -->

				<!-- Pagination العصري -->
				<?php
				if ( $query->max_num_pages > 1 ) :
					$pagination_args = array(
						'total'     => $query->max_num_pages,
						'current'   => $paged,
						'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg><span>' . __( 'السابق', 'nadiim' ) . '</span>',
						'next_text' => '<span>' . __( 'التالي', 'nadiim' ) . '</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>',
						'type'      => 'array',
					);

					$pagination = paginate_links( $pagination_args );

					if ( $pagination ) :
					?>
						<nav class="esdar-pagination-modern" aria-label="<?php _e( 'التنقل بين الصفحات', 'nadiim' ); ?>" data-aos="fade-up" data-aos-delay="200">
							<ul class="pagination-list">
								<?php foreach ( $pagination as $page ) : ?>
									<li><?php echo $page; ?></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php
					endif;
				endif;
				?>

			<?php else : ?>

				<!-- No Results -->
				<div class="esdar-no-results-modern" data-aos="fade-up">
					<div class="no-results-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
							<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
							<line x1="10" y1="10" x2="16" y2="10"/>
							<line x1="10" y1="14" x2="16" y2="14"/>
						</svg>
					</div>
					<h2 class="no-results-title"><?php _e( 'لا توجد إصدارات', 'nadiim' ); ?></h2>
					<p class="no-results-text">
						<?php _e( 'عذراً، لم يتم العثور على أي إصدارات تطابق معايير البحث الخاصة بك.', 'nadiim' ); ?>
					</p>

					<?php if ( ! empty( $_GET['release_type'] ) || ! empty( $_GET['release_year'] ) || ! empty( $_GET['category'] ) ) : ?>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'esdar' ) ); ?>" class="btn-modern btn-primary">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="1 4 1 10 7 10"/>
								<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
							</svg>
							<span><?php _e( 'عرض جميع الإصدارات', 'nadiim' ); ?></span>
						</a>
					<?php endif; ?>
				</div>

			<?php
			endif;
			wp_reset_postdata();
			?>

		</div><!-- .section-container -->
	</section><!-- .esdar-grid-section-modern -->

</main>

<?php
get_footer();
