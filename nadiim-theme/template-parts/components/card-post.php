<?php
/**
 * Component: بطاقة المقال
 *
 * يعرض بطاقة مقال بسيطة:
 * - صورة بارزة
 * - عنوان
 * - مقتطف قصير (20-30 كلمة)
 * - meta (تاريخ، تصنيف)
 * - رابط قراءة المزيد
 *
 * @package Nadiim
 * @since 2.0.0
 */
?>

<article <?php post_class( 'post-card-component' ); ?>>
	<div class="post-card-inner">
		<!-- الصورة -->
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-card-image-wrapper">
				<a href="<?php the_permalink(); ?>" class="post-card-image-link">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'post-card-image' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<!-- المحتوى -->
		<div class="post-card-content">
			<!-- Meta -->
			<div class="post-card-meta">
				<span class="post-card-date">
					<?php echo get_the_date(); ?>
				</span>
				<?php
				$categories = get_the_category();
				if ( ! empty( $categories ) ) :
					?>
					<span class="post-card-category">
						<?php echo esc_html( $categories[0]->name ); ?>
					</span>
				<?php endif; ?>
			</div>

			<!-- العنوان -->
			<h3 class="post-card-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<!-- المقتطف -->
			<div class="post-card-excerpt">
				<?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?>
			</div>

			<!-- رابط قراءة المزيد -->
			<a href="<?php the_permalink(); ?>" class="post-card-read-more">
				اقرأ المزيد
				<span class="read-more-arrow">←</span>
			</a>
		</div>
	</div>
</article>
