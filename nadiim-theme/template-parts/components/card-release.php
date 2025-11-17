<?php
/**
 * Component: بطاقة الإصدار
 *
 * يعرض غلاف إصدار واحد:
 * - غلاف بنسبة 1:1.4 (140×200px تقريباً)
 * - عنوان تحت الغلاف
 * - meta قصير (التاريخ، المؤلف)
 *
 * @package Nadiim
 * @since 2.0.0
 */

// Meta data
$release_author = get_post_meta( get_the_ID(), 'release_author', true );
$release_date   = get_post_meta( get_the_ID(), 'release_date', true );
$release_isbn   = get_post_meta( get_the_ID(), 'release_isbn', true );
$release_file   = get_post_meta( get_the_ID(), 'release_file', true );
?>

<article <?php post_class( 'release-card' ); ?>>
	<div class="release-card-inner">
		<!-- الغلاف -->
		<div class="release-cover-wrapper">
			<a href="<?php the_permalink(); ?>" class="release-cover-link">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'medium', array( 'class' => 'release-cover' ) ); ?>
				<?php else : ?>
					<div class="release-cover-placeholder">
						<span class="placeholder-icon">📖</span>
					</div>
				<?php endif; ?>
			</a>

			<!-- File Type Badge -->
			<?php if ( $release_file ) : ?>
				<div class="release-file-badge">
					<?php
					$file_ext = pathinfo( $release_file, PATHINFO_EXTENSION );
					if ( $file_ext === 'pdf' ) {
						echo '<span class="file-icon">PDF</span>';
					} elseif ( in_array( $file_ext, array( 'epub', 'mobi' ) ) ) {
						echo '<span class="file-icon">eBook</span>';
					} else {
						echo '<span class="file-icon">📄</span>';
					}
					?>
				</div>
			<?php endif; ?>
		</div>

		<!-- المحتوى -->
		<div class="release-content">
			<h4 class="release-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h4>

			<?php if ( $release_author ) : ?>
				<p class="release-author">
					<?php echo esc_html( $release_author ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $release_date ) : ?>
				<p class="release-date">
					<?php echo esc_html( date_i18n( 'Y', strtotime( $release_date ) ) ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</article>
