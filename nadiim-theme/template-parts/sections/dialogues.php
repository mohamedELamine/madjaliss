<?php
/**
 * قسم Dialogues - الحوارات المميزة
 *
 * عرض الحوارات في Grid: 3 أعمدة desktop، 2 tablet، 1 mobile
 * بطاقات مع صور بنسبة 16:9، عنوان، مقتطف، meta data
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات الحوارات من Customizer
$dialogues_count  = get_theme_mod( 'home_dialogues_count', 6 );
$dialogues_layout = get_theme_mod( 'home_dialogues_layout', 'grid' ); // grid/list
$dialogues_source = get_theme_mod( 'home_dialogues_source', 'recent' ); // recent/featured/tag

// بناء Query
$dialogues_args = array(
	'post_type'      => 'howarat',
	'posts_per_page' => $dialogues_count,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

// تخصيص Query حسب المصدر
if ( $dialogues_source === 'featured' ) {
	$dialogues_args['meta_key']   = 'is_featured';
	$dialogues_args['meta_value'] = '1';
}

$dialogues_query = new WP_Query( $dialogues_args );

if ( ! $dialogues_query->have_posts() ) {
	return;
}
?>

<section class="dialogues-section section-padding" id="dialogues">
	<div class="section-container">
		<div class="section-header">
			<h2 class="section-title">الحوارات المميزة</h2>
			<p class="section-description">
				حواراتٌ رصينة مع أهل العلم والفكر، نستمع فيها إلى أصواتٍ متنوعة وأفكارٍ عميقة
			</p>
		</div>

		<div class="dialogues-grid dialogues-layout-<?php echo esc_attr( $dialogues_layout ); ?>">
			<?php while ( $dialogues_query->have_posts() ) : $dialogues_query->the_post(); ?>
				<?php get_template_part( 'template-parts/components/card', 'dialogue' ); ?>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>

		<div class="section-footer">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'howarat' ) ); ?>" class="btn btn-outline">
				جميع الحوارات
				<span class="btn-arrow">←</span>
			</a>
		</div>
	</div>
</section>
