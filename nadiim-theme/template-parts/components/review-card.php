<?php
/**
 * Component: بطاقة المراجعة
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على البيانات
$post_id       = get_the_ID();
$rating        = get_post_meta( $post_id, '_review_rating', true ) ?: 5;
$item_title    = get_post_meta( $post_id, '_review_item_title', true );
$author_name   = get_post_meta( $post_id, '_review_author_name', true );
$publish_year  = get_post_meta( $post_id, '_review_publish_year', true );
$show_rating   = get_query_var( 'show_rating', true );

// الحصول على نوع المراجعة
$review_types = get_the_terms( $post_id, 'review_type' );
$review_type  = $review_types && ! is_wp_error( $review_types ) ? $review_types[0]->name : '';
?>

<article <?php post_class( 'review-card' ); ?>>

	<!-- صورة الغلاف -->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="review-card-image">
			<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
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
	<div class="review-card-content">

		<!-- التقييم -->
		<?php if ( $show_rating ) : ?>
			<div class="review-card-rating">
				<div class="rating-stars">
					<?php
					for ( $i = 1; $i <= 5; $i++ ) {
						if ( $i <= $rating ) {
							echo '<span class="star star-filled" aria-hidden="true">★</span>';
						} else {
							echo '<span class="star star-empty" aria-hidden="true">☆</span>';
						}
					}
					?>
				</div>
				<span class="rating-value"><?php echo esc_html( $rating ); ?><span class="rating-max">/5</span></span>
			</div>
		<?php endif; ?>

		<!-- عنوان المراجعة -->
		<h3 class="review-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<!-- معلومات العمل المُراجَع -->
		<?php if ( $item_title || $author_name || $publish_year ) : ?>
			<div class="review-card-meta">
				<?php if ( $item_title ) : ?>
					<div class="meta-item">
						<span class="meta-icon" aria-hidden="true">📖</span>
						<span class="meta-text"><?php echo esc_html( $item_title ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( $author_name ) : ?>
					<div class="meta-item">
						<span class="meta-icon" aria-hidden="true">✍️</span>
						<span class="meta-text"><?php echo esc_html( $author_name ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( $publish_year ) : ?>
					<div class="meta-item">
						<span class="meta-icon" aria-hidden="true">📅</span>
						<span class="meta-text"><?php echo esc_html( $publish_year ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- المقتطف -->
		<div class="review-card-excerpt">
			<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
		</div>

		<!-- التاريخ -->
		<div class="review-card-date">
			<span class="date-icon" aria-hidden="true">🕒</span>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo get_the_date(); ?>
			</time>
		</div>

		<!-- زر "قراءة المراجعة" -->
		<a href="<?php the_permalink(); ?>" class="review-card-btn" aria-label="<?php printf( __( 'قراءة مراجعة: %s', 'nadiim' ), get_the_title() ); ?>">
			<?php _e( 'قراءة المراجعة', 'nadiim' ); ?>
			<span class="btn-arrow" aria-hidden="true">←</span>
		</a>

	</div><!-- .review-card-content -->

</article><!-- .review-card -->

<style>
.review-card {
	background: #ffffff;
	border-radius: 16px;
	overflow: hidden;
	box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
	transition: all 0.3s ease;
	display: flex;
	flex-direction: column;
	height: 100%;
}

.review-card:hover {
	transform: translateY(-6px);
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.review-card-image {
	position: relative;
	width: 100%;
	height: 280px;
	overflow: hidden;
}

.review-card-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s ease;
}

.review-card:hover .review-card-image img {
	transform: scale(1.05);
}

.card-type-badge {
	position: absolute;
	top: 16px;
	right: 16px;
	padding: 8px 16px;
	background: rgba(51, 144, 99, 0.95);
	backdrop-filter: blur(10px);
	color: #ffffff;
	font-size: 13px;
	font-weight: 700;
	border-radius: 20px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.review-card-content {
	padding: 24px;
	display: flex;
	flex-direction: column;
	gap: 16px;
	flex: 1;
}

.review-card-rating {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	padding: 12px 16px;
	background: #F6F9F7;
	border-radius: 12px;
}

.review-card-rating .rating-stars {
	display: flex;
	gap: 4px;
	font-size: 18px;
}

.review-card-rating .star-filled {
	color: #f39c12;
}

.review-card-rating .star-empty {
	color: #ddd;
}

.review-card-rating .rating-value {
	font-size: 16px;
	font-weight: 700;
	color: #1c2d27;
}

.review-card-rating .rating-max {
	font-size: 14px;
	color: #6B7A72;
}

.review-card-title {
	margin: 0;
	font-size: 20px;
	font-weight: 700;
	line-height: 1.4;
}

.review-card-title a {
	color: #1c2d27;
	text-decoration: none;
	transition: color 0.3s ease;
}

.review-card-title a:hover {
	color: #339063;
}

.review-card-meta {
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.meta-item {
	display: flex;
	align-items: center;
	gap: 8px;
	font-size: 14px;
	color: #6B7A72;
}

.meta-icon {
	font-size: 16px;
}

.meta-text {
	font-weight: 600;
}

.review-card-excerpt {
	font-size: 15px;
	line-height: 1.6;
	color: #1C2D27;
	flex: 1;
}

.review-card-date {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 13px;
	color: #6B7A72;
	padding-top: 12px;
	border-top: 1px solid #E5E7EB;
}

.date-icon {
	font-size: 14px;
}

.review-card-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	padding: 12px 24px;
	background: #339063;
	color: #ffffff;
	border-radius: 12px;
	font-size: 16px;
	font-weight: 700;
	text-decoration: none;
	text-align: center;
	transition: all 0.3s ease;
	margin-top: auto;
}

.review-card-btn:hover {
	background: #2a7851;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(51, 144, 99, 0.3);
	color: #ffffff;
}

.review-card-btn .btn-arrow {
	font-size: 18px;
	transition: transform 0.3s ease;
}

.review-card-btn:hover .btn-arrow {
	transform: translateX(-4px);
}
</style>
