<?php
/**
 * Template part لقسم المقالات
 *
 * عرض المقالات بتصميم List View أو Grid View
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'articles_section_enable', true ) ) {
	return;
}

// الحصول على الإعدادات من Customizer
$section_title       = get_theme_mod( 'articles_section_title', __( 'المقالات', 'nadiim' ) );
$section_description = get_theme_mod( 'articles_section_description', __( 'استكشف مقالاتنا حول القراءة والكتب والثقافة', 'nadiim' ) );
$posts_count         = get_theme_mod( 'articles_section_count', 6 );
$layout_type         = get_theme_mod( 'articles_section_layout', 'grid' ); // list or grid (افتراضي: grid = عمودين)
$filter_type         = get_theme_mod( 'articles_section_filter', 'latest' ); // latest, category, tag, author
$category_id         = get_theme_mod( 'articles_section_category', '' );
$tag_slug            = get_theme_mod( 'articles_section_tag', '' );
$author_id           = get_theme_mod( 'articles_section_author', '' );
$show_more_button    = get_theme_mod( 'articles_section_show_more', true );
$more_button_text    = get_theme_mod( 'articles_section_more_text', __( 'جميع المقالات', 'nadiim' ) );
$cta_button_text     = get_theme_mod( 'articles_section_cta_text', __( 'اقرأ المزيد', 'nadiim' ) );

// بناء WP_Query arguments
$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $posts_count,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
);

// تطبيق الفلترة
switch ( $filter_type ) {
	case 'category':
		if ( ! empty( $category_id ) ) {
			$args['cat'] = $category_id;
		}
		break;

	case 'tag':
		if ( ! empty( $tag_slug ) ) {
			$args['tag'] = $tag_slug;
		}
		break;

	case 'author':
		if ( ! empty( $author_id ) ) {
			$args['author'] = $author_id;
		}
		break;
}

// تنفيذ الاستعلام
$articles_query = new WP_Query( $args );

// إذا لم توجد مقالات، لا تعرض القسم
if ( ! $articles_query->have_posts() ) {
	return;
}

// تحديد classes للقسم
$section_classes = array( 'articles-section', 'section-padding' );
$section_classes[] = 'layout-' . esc_attr( $layout_type );
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" aria-labelledby="articles-section-title">
	<div class="section-container">

		<!-- رأس القسم -->
		<div class="section-header">
			<?php if ( ! empty( $section_title ) ) : ?>
				<h2 id="articles-section-title" class="section-title">
					<?php echo esc_html( $section_title ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( ! empty( $section_description ) ) : ?>
				<p class="section-description">
					<?php echo esc_html( $section_description ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .section-header -->

		<!-- قائمة المقالات -->
		<div class="articles-list articles-<?php echo esc_attr( $layout_type ); ?>">
			<?php
			while ( $articles_query->have_posts() ) :
				$articles_query->the_post();

				// الحصول على بيانات المقال
				$post_id      = get_the_ID();
				$reading_time = nadiim_get_reading_time( $post_id );
				$has_audio    = nadiim_has_article_audio( $post_id );
				$author_id    = get_the_author_meta( 'ID' );
				$categories   = get_the_category();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card' ); ?>>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="article-card-image">
							<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
								<?php
								// استخدام حجم مناسب حسب التخطيط
								$image_size = ( $layout_type === 'list' ) ? 'medium_large' : 'large';
								the_post_thumbnail( $image_size, array(
									'loading' => 'lazy',
									'class'   => 'article-image',
								) );
								?>
							</a>

							<?php if ( $has_audio ) : ?>
								<div class="article-audio-badge" title="<?php esc_attr_e( 'يتوفر تسجيل صوتي', 'nadiim' ); ?>">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
										<path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
									</svg>
									<span class="sr-only"><?php esc_html_e( 'صوتي', 'nadiim' ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="article-card-content">

						<!-- Meta -->
						<div class="article-meta">
							<?php if ( ! empty( $categories ) ) : ?>
								<span class="article-category">
									<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
										<?php echo esc_html( $categories[0]->name ); ?>
									</a>
								</span>
								<span class="meta-separator">•</span>
							<?php endif; ?>

							<time class="article-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>

							<?php if ( $reading_time > 0 ) : ?>
								<span class="meta-separator">•</span>
								<span class="article-reading-time">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
										<circle cx="12" cy="12" r="10"></circle>
										<polyline points="12 6 12 12 16 14"></polyline>
									</svg>
									<?php printf( esc_html__( '%d دقيقة', 'nadiim' ), $reading_time ); ?>
								</span>
							<?php endif; ?>

							<?php if ( $has_audio ) : ?>
								<span class="meta-separator">•</span>
								<span class="article-has-audio">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
										<path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
									</svg>
									<?php esc_html_e( 'صوت', 'nadiim' ); ?>
								</span>
							<?php endif; ?>
						</div><!-- .article-meta -->

						<!-- العنوان -->
						<h3 class="article-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h3>

						<!-- المقتطف -->
						<?php if ( has_excerpt() || get_the_content() ) : ?>
							<div class="article-excerpt">
								<?php
								$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30, '...' );
								// تحديد عدد الأسطر: 2 سطر max
								echo '<p>' . esc_html( wp_trim_words( $excerpt, 25, '...' ) ) . '</p>';
								?>
							</div>
						<?php endif; ?>

						<!-- Footer: الكاتب + زر CTA -->
						<div class="article-footer">
							<div class="article-author">
								<?php echo get_avatar( $author_id, 32, '', '', array( 'class' => 'author-avatar' ) ); ?>
								<span class="author-name">
									<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
										<?php echo esc_html( get_the_author() ); ?>
									</a>
								</span>
							</div>

							<a href="<?php the_permalink(); ?>" class="article-cta">
								<?php echo esc_html( $cta_button_text ); ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
									<line x1="5" y1="12" x2="19" y2="12"></line>
									<polyline points="12 5 19 12 12 19"></polyline>
								</svg>
							</a>
						</div><!-- .article-footer -->

					</div><!-- .article-card-content -->

				</article><!-- .article-card -->

			<?php endwhile; wp_reset_postdata(); ?>
		</div><!-- .articles-list -->

		<!-- زر "جميع المقالات" -->
		<?php if ( $show_more_button ) : ?>
			<div class="section-footer">
				<?php
				// الحصول على صفحة المدونة
				$blog_page_id = get_option( 'page_for_posts' );
				$blog_url = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog' );
				?>
				<a href="<?php echo esc_url( $blog_url ); ?>" class="btn btn-outline">
					<?php echo esc_html( $more_button_text ); ?>
					<span class="btn-arrow">←</span>
				</a>
			</div>
		<?php endif; ?>

	</div><!-- .section-container -->
</section><!-- .articles-section -->
