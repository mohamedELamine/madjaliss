<?php
/**
 * قالب عرض المقال في صفحات الأرشيف
 *
 * @package Nadiim
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" class="card-image-link">
            <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
        </a>
    <?php endif; ?>

    <div class="card-content">

        <div class="card-meta">
            <?php nadiim_posted_on(); ?>
        </div>

        <h3 class="card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="card-excerpt">
            <?php echo nadiim_get_excerpt( 20 ); ?>
        </div>

        <?php echo nadiim_read_more_link(); ?>

    </div>

</article>
