<?php
/**
 * قالب أرشيف المراجعات
 *
 * تجربة المستخدم النهائية:
 * - يرى المستخدم عنواناً واضحاً "جميع المراجعات"
 * - وصف مختصر عن أرشيف المراجعات
 * - شبكة من 3 أعمدة تعرض بطاقات المراجعات (عمود واحد على الموبايل)
 * - كل بطاقة تحتوي على: صورة الغلاف، التقييم بالنجوم، العنوان، مقتطف، زر "قراءة المراجعة"
 * - pagination للتنقل بين الصفحات
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

// تحميل ملف CSS الخاص بالمراجعات
wp_enqueue_style( 'reviews-style', get_template_directory_uri() . '/assets/css/reviews.css', array(), '1.0.0' );

// حساب عدد المراجعات
$total_reviews = wp_count_posts( 'reviews' )->publish;
?>

<main id="primary" class="site-main">

	<!-- هيرو الأرشيف -->
	<div class="reviews-archive-hero">
		<div class="reviews-archive-hero-overlay"></div>
		<div class="reviews-archive-hero-content">
			<div class="reviews-archive-hero-container">
				<span class="archive-hero-badge">⭐ المراجعات</span>
				<h1 class="archive-hero-title">اكتشف آراءنا الصادقة</h1>
				<p class="archive-hero-description">
					مراجعات متعمقة وشاملة للكتب والأفلام والأعمال الفنية، نقدم لك رأياً صادقاً يساعدك على اختيار ما يستحق وقتك
				</p>
				<div class="archive-hero-stats">
					<div class="archive-stat-item">
						<span class="stat-number"><?php echo number_format_i18n( $total_reviews ); ?></span>
						<span class="stat-label">مراجعة متاحة</span>
					</div>
					<div class="archive-stat-divider"></div>
					<div class="archive-stat-item">
						<span class="stat-icon">⭐</span>
						<span class="stat-label">تقييمات موثوقة</span>
					</div>
					<div class="archive-stat-divider"></div>
					<div class="archive-stat-item">
						<span class="stat-icon">📝</span>
						<span class="stat-label">تحليل متعمق</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="archive-container">

		<!-- مكان للفلاتر المستقبلية -->
		<div id="reviews-filters" class="reviews-filters">
			<!-- الفلاتر ستضاف هنا لاحقاً: فلتر حسب التقييم، النوع، التصنيف، إلخ -->
		</div>

		<!-- شبكة المراجعات -->
		<?php
		// تطبيق pagination
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		if ( have_posts() ) :
			?>

			<div class="reviews-grid">
				<?php
				while ( have_posts() ) :
					the_post();

					// جلب البيانات الوصفية
					$rating       = get_post_meta( get_the_ID(), '_review_rating', true );
					$item_title   = get_post_meta( get_the_ID(), '_review_item_title', true );
					$author_name  = get_post_meta( get_the_ID(), '_review_author_name', true );
					$publish_year = get_post_meta( get_the_ID(), '_review_publish_year', true );

					// قيمة افتراضية للتقييم
					$rating = $rating ?: 5;

					// الحصول على نوع المراجعة
					$review_types = get_the_terms( get_the_ID(), 'review_type' );
					$review_type  = $review_types && ! is_wp_error( $review_types ) ? $review_types[0]->name : '';
					?>

					<article <?php post_class( 'reviews-card' ); ?>>

						<!-- صورة الغلاف -->
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="reviews-card-image">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium_large' ); ?>
								</a>

								<!-- شارة النوع -->
								<?php if ( $review_type ) : ?>
									<div class="card-type-badge">
										<?php echo esc_html( $review_type ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<!-- محتوى البطاقة -->
						<div class="reviews-card-content">

							<!-- التقييم -->
							<div class="reviews-card-rating">
								<div class="rating-stars">
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
								<span class="rating-value"><?php echo esc_html( $rating ); ?>/5</span>
							</div>

							<!-- عنوان المراجعة -->
							<h2 class="reviews-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<!-- معلومات العمل المُراجَع -->
							<?php if ( $item_title || $author_name ) : ?>
								<div class="reviews-card-meta">
									<?php if ( $item_title ) : ?>
										<div class="meta-item">
											<span class="meta-icon">📖</span>
											<span class="meta-text"><?php echo esc_html( $item_title ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( $author_name ) : ?>
										<div class="meta-item">
											<span class="meta-icon">✍️</span>
											<span class="meta-text"><?php echo esc_html( $author_name ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( $publish_year ) : ?>
										<div class="meta-item">
											<span class="meta-icon">📅</span>
											<span class="meta-text"><?php echo esc_html( $publish_year ); ?></span>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<!-- المقتطف -->
							<div class="reviews-card-excerpt">
								<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
							</div>

							<!-- التاريخ -->
							<div class="reviews-card-date">
								<span class="date-icon">🕒</span>
								<?php echo get_the_date(); ?>
							</div>

							<!-- زر "قراءة المراجعة" -->
							<a href="<?php the_permalink(); ?>" class="reviews-card-btn">
								قراءة المراجعة
								<span class="btn-arrow">←</span>
							</a>

						</div><!-- .reviews-card-content -->

					</article><!-- .reviews-card -->

				<?php endwhile; ?>
			</div><!-- .reviews-grid -->

			<!-- Pagination -->
			<?php
			global $wp_query;
			$pagination = paginate_links(
				array(
					'total'     => $wp_query->max_num_pages,
					'current'   => $paged,
					'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg> السابق',
					'next_text' => 'التالي <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>',
					'type'      => 'list',
					'mid_size'  => 2,
					'end_size'  => 1,
				)
			);

			if ( $pagination ) :
				?>
				<nav class="reviews-pagination" aria-label="<?php _e( 'التنقل بين الصفحات', 'nadiim' ); ?>">
					<?php echo $pagination; ?>
				</nav>
			<?php endif; ?>

		<?php else : ?>

			<!-- رسالة عند عدم وجود مراجعات -->
			<div class="no-results">
				<div class="no-results-icon">🔍</div>
				<h2><?php _e( 'لا توجد مراجعات حالياً', 'nadiim' ); ?></h2>
				<p><?php _e( 'يبدو أنه لا توجد مراجعات متاحة في الوقت الحالي. تفقد الصفحة لاحقاً للاطلاع على المراجعات الجديدة.', 'nadiim' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php _e( 'العودة للرئيسية', 'nadiim' ); ?></a>
			</div>

		<?php endif; ?>

	</div><!-- .archive-container -->

</main><!-- #primary -->

<?php
get_footer();
