<?php
/**
 * قسم Posts - أحدث المقالات
 *
 * عرض two-column: مقال كبير يسار + 2 مقالات صغيرة يمين على desktop
 * عمود واحد على mobile
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات المقالات من Customizer
$posts_count    = get_theme_mod( 'home_posts_count', 3 );
$posts_category = get_theme_mod( 'home_posts_category', '' );

// Query المقالات
$posts_args = array(
	'post_type'      => 'post',
	'posts_per_page' => $posts_count,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( $posts_category ) {
	$posts_args['cat'] = $posts_category;
}

$posts_query = new WP_Query( $posts_args );

if ( ! $posts_query->have_posts() ) {
	return;
}
?>

<section class="posts-section section-padding">
	<div class="section-container">
		<div class="section-header">
			<h2 class="section-title">أحدث المقالات</h2>
			<p class="section-description">
				مقالاتٌ متنوعة في القراءة والكتب والثقافة
			</p>
		</div>

		<div class="posts-layout">
			<?php
			$post_index = 0;
			while ( $posts_query->have_posts() ) : $posts_query->the_post();
				$post_index++;
				$is_featured = ( $post_index === 1 );
				?>

				<?php if ( $is_featured ) : ?>
					<!-- المقال الكبير (المميز) -->
					<article class="post-card post-featured">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="post-image">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'large', array( 'class' => 'post-img' ) ); ?>
								</a>
							</div>
						<?php endif; ?>
						<div class="post-content">
							<div class="post-meta">
								<span class="post-category"><?php the_category( ', ' ); ?></span>
								<span class="post-date"><?php echo get_the_date(); ?></span>
							</div>
							<h3 class="post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<div class="post-excerpt">
								<?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?>
							</div>
							<a href="<?php the_permalink(); ?>" class="post-read-more">
								اقرأ المزيد ←
							</a>
						</div>
					</article>

				<?php else : ?>
					<!-- المقالات الصغيرة -->
					<?php if ( $post_index === 2 ) : ?>
						<div class="posts-sidebar">
					<?php endif; ?>

					<article class="post-card post-compact">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="post-image-compact">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium', array( 'class' => 'post-img-compact' ) ); ?>
								</a>
							</div>
						<?php endif; ?>
						<div class="post-content-compact">
							<div class="post-meta-compact">
								<span class="post-date"><?php echo get_the_date( 'M j' ); ?></span>
							</div>
							<h4 class="post-title-compact">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h4>
						</div>
					</article>

					<?php if ( $post_index === $posts_query->post_count ) : ?>
						</div><!-- .posts-sidebar -->
					<?php endif; ?>

				<?php endif; ?>

			<?php endwhile; wp_reset_postdata(); ?>
		</div>

		<div class="section-footer">
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-outline">
				جميع المقالات
				<span class="btn-arrow">←</span>
			</a>
		</div>
	</div>
</section>
