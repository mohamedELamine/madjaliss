<?php
/**
 * Template Part: Author Bio (السيرة التفصيلية للكاتب)
 *
 * يعرض السيرة الذاتية الكاملة للكاتب
 *
 * @package Nadiim
 * @since 1.0.0
 */

// الحصول على معرف الكاتب
$author = get_queried_object();
$author_id = $author->ID;

// الحصول على السيرة الذاتية الكاملة
$author_bio = nadiim_get_author_bio( $author_id );

// إذا لم يكن هناك سيرة، لا تعرض شيء
if ( empty( $author_bio ) ) {
    return;
}
?>

<div class="author-bio-container" id="author-bio">

    <div class="section-header">
        <h2 class="section-title">
            <?php esc_html_e( 'عن الكاتب', 'nadiim' ); ?>
        </h2>
    </div>

    <div class="author-bio-content">
        <?php echo wp_kses_post( $author_bio ); ?>
    </div>

</div><!-- .author-bio-container -->
