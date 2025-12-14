<?php
/**
 * قالب الصفحة المفردة للمراجعات
 *
 * تجربة المستخدم النهائية:
 * - يشاهد المستخدم صفحة احترافية وهادئة للمراجعة
 * - هيرو كامل بصورة الغلاف، العنوان، التقييم، والبيانات الأساسية
 * - يقرأ المحتوى الكامل للمراجعة
 * - يجد رابط الشراء/المشاهدة
 * - يرى في الـsidebar مراجعات مشابهة وأحدث المراجعات
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

// تحميل ملف CSS الخاص بالمراجعات
wp_enqueue_style( 'reviews-style', get_template_directory_uri() . '/assets/css/reviews.css', array(), '1.0.0' );

while ( have_posts() ) :
	the_post();

	// جلب البيانات الوصفية
	$rating        = get_post_meta( get_the_ID(), '_review_rating', true );
	$item_title    = get_post_meta( get_the_ID(), '_review_item_title', true );
	$author_name   = get_post_meta( get_the_ID(), '_review_author_name', true );
	$publisher     = get_post_meta( get_the_ID(), '_review_publisher', true );
	$publish_year  = get_post_meta( get_the_ID(), '_review_publish_year', true );
	$isbn          = get_post_meta( get_the_ID(), '_review_isbn', true );
	$buy_link      = get_post_meta( get_the_ID(), '_review_buy_link', true );

	// الحصول على نوع المراجعة
	$review_types = get_the_terms( get_the_ID(), 'review_type' );
	$review_type  = $review_types && ! is_wp_error( $review_types ) ? $review_types[0]->name : '';

	// قيمة افتراضية للتقييم
	$rating = $rating ?: 5;
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'reviews-single' ); ?>>

		<!-- قسم الهيرو -->
		<div class="reviews-hero">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="reviews-hero-image">
					<?php the_post_thumbnail( 'full' ); ?>
					<div class="reviews-hero-overlay"></div>
				</div>
			<?php endif; ?>

			<div class="reviews-hero-content">
				<div class="reviews-hero-container">

					<!-- شارة النوع -->
					<?php if ( $review_type ) : ?>
						<div class="reviews-type-badge">
							<?php echo esc_html( $review_type ); ?>
						</div>
					<?php endif; ?>

					<!-- العنوان -->
					<h1 class="reviews-title"><?php the_title(); ?></h1>

					<!-- التقييم بالنجوم -->
					<div class="reviews-rating-display">
						<div class="rating-stars-large">
							<?php
							for ( $i = 1; $i <= 5; $i++ ) {
								if ( $i <= $rating ) {
									echo '<span class="star star-filled">★</span>';
								} else {
									echo '<span class="star star-empty">☆</span>';
								}
							}
							?>
						</div>
						<div class="rating-number">
							<strong><?php echo esc_html( $rating ); ?></strong>
							<span class="rating-max">/5</span>
						</div>
					</div>

					<!-- البيانات الوصفية -->
					<div class="reviews-meta-info">
						<?php if ( $item_title ) : ?>
							<div class="meta-info-item meta-item-title">
								<span class="meta-icon">📖</span>
								<div class="meta-content">
									<span class="meta-label"><?php _e( 'العمل المُراجَع', 'nadiim' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $item_title ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $author_name ) : ?>
							<div class="meta-info-item">
								<span class="meta-icon">✍️</span>
								<div class="meta-content">
									<span class="meta-label"><?php _e( 'المؤلف/المخرج', 'nadiim' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $author_name ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $publisher ) : ?>
							<div class="meta-info-item">
								<span class="meta-icon">🏢</span>
								<div class="meta-content">
									<span class="meta-label"><?php _e( 'الناشر/دار النشر', 'nadiim' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $publisher ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $publish_year ) : ?>
							<div class="meta-info-item">
								<span class="meta-icon">📅</span>
								<div class="meta-content">
									<span class="meta-label"><?php _e( 'سنة النشر', 'nadiim' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $publish_year ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $isbn ) : ?>
							<div class="meta-info-item">
								<span class="meta-icon">🔢</span>
								<div class="meta-content">
									<span class="meta-label">ISBN</span>
									<span class="meta-value"><?php echo esc_html( $isbn ); ?></span>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<!-- زر الشراء/المشاهدة -->
					<?php if ( $buy_link ) : ?>
						<div class="reviews-cta-buttons">
							<a href="<?php echo esc_url( $buy_link ); ?>" target="_blank" rel="noopener" class="reviews-cta-btn reviews-cta-primary">
								<?php
								if ( $review_type && strpos( strtolower( $review_type ), 'فيلم' ) !== false ) {
									_e( 'شاهد الآن', 'nadiim' );
								} else {
									_e( 'احصل عليه الآن', 'nadiim' );
								}
								?>
								<span class="cta-arrow">↗</span>
							</a>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>

		<!-- المحتوى الرئيسي -->
		<div class="reviews-main-container">
			<div class="reviews-content-wrapper">

				<!-- ملخص المراجعة -->
				<?php if ( has_excerpt() ) : ?>
					<div id="summary" class="reviews-summary-section">
						<h2 class="section-title">ملخص المراجعة</h2>
						<div class="reviews-summary-content">
							<?php the_excerpt(); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- المحتوى الكامل -->
				<div id="content" class="reviews-content-section">
					<h2 class="section-title">المراجعة الكاملة</h2>
					<div class="reviews-content-body">
						<?php the_content(); ?>
					</div>
				</div>

				$tags       = get_the_tags();
				if ( $categories || $tags ) :
				?>
					<div class="reviews-taxonomy-section">
						<?php if ( $categories ) : ?>
							<div class="taxonomy-group">
								<span class="taxonomy-label"><?php _e( 'التصنيفات:', 'nadiim' ); ?></span>
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
								<span class="taxonomy-label"><?php _e( 'الوسوم:', 'nadiim' ); ?></span>
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
				<div class="reviews-share-section">
					<span class="share-label"><?php _e( 'شارك المراجعة:', 'nadiim' ); ?></span>
					<div class="share-buttons">
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
						   target="_blank"
						   rel="noopener"
						   class="share-btn share-facebook"
						   aria-label="<?php _e( 'مشاركة على فيسبوك', 'nadiim' ); ?>">
							<i class="fa-brands fa-facebook-f"></i>
						</a>
						<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>"
						   target="_blank"
						   rel="noopener"
						   class="share-btn share-twitter"
						   aria-label="<?php _e( 'مشاركة على تويتر', 'nadiim' ); ?>">
							<i class="fa-brands fa-twitter"></i>
						</a>
						<a href="https://wa.me/?text=<?php echo urlencode( get_the_title() . ' - ' . get_permalink() ); ?>"
						   target="_blank"
						   rel="noopener"
						   class="share-btn share-whatsapp"
						   aria-label="<?php _e( 'مشاركة على واتساب', 'nadiim' ); ?>">
							<i class="fa-brands fa-whatsapp"></i>
						</a>
					</div>
				</div>

				<!-- التنقل بين المنشورات -->
				<div class="reviews-navigation">
					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();
					?>

					<?php if ( $prev_post ) : ?>
						<a href="<?php echo get_permalink( $prev_post ); ?>" class="nav-prev">
							<span class="nav-arrow">→</span>
							<div class="nav-content">
								<span class="nav-label"><?php _e( 'المراجعة السابقة', 'nadiim' ); ?></span>
								<span class="nav-title"><?php echo get_the_title( $prev_post ); ?></span>
							</div>
						</a>
					<?php endif; ?>

					<?php if ( $next_post ) : ?>
						<a href="<?php echo get_permalink( $next_post ); ?>" class="nav-next">
							<div class="nav-content">
								<span class="nav-label"><?php _e( 'المراجعة التالية', 'nadiim' ); ?></span>
								<span class="nav-title"><?php echo get_the_title( $next_post ); ?></span>
							</div>
							<span class="nav-arrow">←</span>
						</a>
					<?php endif; ?>
				</div>

				<!-- قسم التعليقات -->
				<?php
				if ( comments_open() || get_comments_number() ) :
					?>
					<div id="comments" class="reviews-comments-section">
						<?php comments_template(); ?>
					</div>
				<?php endif; ?>

			</div><!-- .reviews-content-wrapper -->

			<!-- Sidebar -->
			<aside class="reviews-sidebar">

				<!-- مراجعات مشابهة -->
				<?php
				// الحصول على التصنيفات للبحث عن مراجعات مشابهة
				$post_categories = wp_get_post_categories( get_the_ID() );
				$related_args    = array(
					'post_type'      => 'reviews',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'orderby'        => 'rand',
				);

				// إذا كان هناك تصنيفات، ابحث بناءً عليها
				if ( ! empty( $post_categories ) ) {
					$related_args['category__in'] = $post_categories;
				}

				$related_query = new WP_Query( $related_args );

				if ( $related_query->have_posts() ) :
					?>
					<div class="sidebar-widget">
						<h3 class="widget-title"><?php _e( 'مراجعات مشابهة', 'nadiim' ); ?></h3>
						<div class="sidebar-related-posts">
							<?php
							while ( $related_query->have_posts() ) :
								$related_query->the_post();
								$related_rating = get_post_meta( get_the_ID(), '_review_rating', true ) ?: 5;
								?>
								<article class="sidebar-related-item">
									<?php if ( has_post_thumbnail() ) : ?>
										<a href="<?php the_permalink(); ?>" class="related-thumbnail">
											<?php the_post_thumbnail( 'thumbnail' ); ?>
										</a>
									<?php endif; ?>
									<div class="related-content">
										<h4 class="related-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h4>
										<div class="related-rating">
											<?php
											for ( $i = 1; $i <= 5; $i++ ) {
												echo $i <= $related_rating ? '<span class="star">★</span>' : '<span class="star star-empty">☆</span>';
											}
											?>
										</div>
										<p class="related-date"><?php echo get_the_date(); ?></p>
									</div>
								</article>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- أحدث المراجعات -->
				<?php
				$recent_args = array(
					'post_type'      => 'reviews',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'orderby'        => 'date',
					'order'          => 'DESC',
				);
				$recent_query = new WP_Query( $recent_args );

				if ( $recent_query->have_posts() ) :
					?>
					<div class="sidebar-widget">
						<h3 class="widget-title"><?php _e( 'أحدث المراجعات', 'nadiim' ); ?></h3>
						<div class="sidebar-recent-posts">
							<?php
							while ( $recent_query->have_posts() ) :
								$recent_query->the_post();
								$recent_rating = get_post_meta( get_the_ID(), '_review_rating', true ) ?: 5;
								?>
								<article class="sidebar-recent-item">
									<h4 class="recent-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
									<div class="recent-rating">
										<?php
										for ( $i = 1; $i <= 5; $i++ ) {
											echo $i <= $recent_rating ? '<span class="star">★</span>' : '<span class="star star-empty">☆</span>';
										}
										?>
										<span class="rating-text"><?php echo esc_html( $recent_rating ); ?>/5</span>
									</div>
									<p class="recent-date"><?php echo get_the_date(); ?></p>
								</article>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>

			</aside><!-- .reviews-sidebar -->

		</div><!-- .reviews-main-container -->

	</article><!-- #post-<?php the_ID(); ?> -->

	<!-- JSON-LD Schema للمراجعة -->
	<?php
	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Review',
		'itemReviewed'  => array(
			'@type' => 'CreativeWork',
			'name'  => $item_title ?: get_the_title(),
		),
		'reviewRating'  => array(
			'@type'       => 'Rating',
			'ratingValue' => $rating,
			'bestRating'  => '5',
			'worstRating' => '1',
		),
		'author'        => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'datePublished' => get_the_date( 'c' ),
		'reviewBody'    => wp_strip_all_tags( get_the_content() ),
	);

	if ( has_post_thumbnail() ) {
		$schema['itemReviewed']['image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}

	if ( $author_name ) {
		$schema['itemReviewed']['author'] = array(
			'@type' => 'Person',
			'name'  => $author_name,
		);
	}

	if ( $isbn ) {
		$schema['itemReviewed']['@type'] = 'Book';
		$schema['itemReviewed']['isbn']  = $isbn;
	}
	?>

	<script type="application/ld+json">
	<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
	</script>

<?php
endwhile;

get_footer();
