<?php
/**
 * قالب أرشيف نوادي القراءة
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="page-hero" style="background-color: var(--color-bg-section); padding: var(--spacing-xl) 0;">
        <div class="container">
            <header class="page-header text-center">
                <h1 class="page-title"><?php esc_html_e( 'نوادي القراءة', 'nadiim' ); ?></h1>
                <p class="archive-description"><?php esc_html_e( 'انضم إلى إحدى مجتمعاتنا الهادئة للقراءة والنقاش', 'nadiim' ); ?></p>
            </header>
        </div>
    </div>

    <div class="container section">
        <?php if ( have_posts() ) : ?>
            <div class="clubs-grid grid grid-3">
                <?php while ( have_posts() ) : the_post();
                    $supervisor_id = get_post_meta( get_the_ID(), 'club_supervisor', true );
                    $members = get_post_meta( get_the_ID(), 'club_members', true );
                    $current_book_id = get_post_meta( get_the_ID(), 'club_current_book', true );
                    $max_members = get_post_meta( get_the_ID(), 'club_max_members', true );
                    ?>
                    <article <?php post_class( 'card club-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="card-content">
                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                            <div class="card-excerpt">
                                <?php echo nadiim_get_excerpt( 15 ); ?>
                            </div>

                            <?php if ( $supervisor_id ) :
                                $supervisor = get_user_by( 'ID', $supervisor_id );
                                if ( $supervisor ) : ?>
                                    <p class="club-supervisor" style="font-size: 14px; color: var(--color-text-secondary); margin-top: var(--spacing-sm);">
                                        <strong><?php esc_html_e( 'المشرف:', 'nadiim' ); ?></strong>
                                        <?php echo esc_html( $supervisor->display_name ); ?>
                                    </p>
                                <?php endif;
                            endif; ?>

                            <?php if ( is_array( $members ) && $max_members ) : ?>
                                <p class="club-capacity" style="font-size: 14px; color: var(--color-text-light); margin-top: 4px;">
                                    <?php printf( esc_html__( '%d من %d أعضاء', 'nadiim' ), count( $members ), $max_members ); ?>
                                </p>
                            <?php endif; ?>

                            <div style="margin-top: var(--spacing-md);">
                                <?php echo nadiim_read_more_link( __( 'تفاصيل النادي', 'nadiim' ) ); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'لا توجد نوادي حالياً', 'nadiim' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
