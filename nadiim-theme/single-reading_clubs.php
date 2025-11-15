<?php
/**
 * قالب نادي القراءة المفرد
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) : the_post();
    $supervisor_id = get_post_meta( get_the_ID(), 'club_supervisor', true );
    $members = get_post_meta( get_the_ID(), 'club_members', true );
    $current_book_id = get_post_meta( get_the_ID(), 'club_current_book', true );
    $join_url = get_post_meta( get_the_ID(), 'club_join_url', true );
    $max_members = get_post_meta( get_the_ID(), 'club_max_members', true );
    $schedule = get_post_meta( get_the_ID(), 'club_schedule', true );
    $meeting_url = get_post_meta( get_the_ID(), 'club_meeting_url', true );
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'club-single' ); ?>>
        <div class="container container-narrow">

            <!-- Hero بتصميم محسّن -->
            <div class="club-hero" style="text-align: center; padding: var(--spacing-xxl) var(--spacing-xl); background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); margin: 0 calc(-1 * var(--spacing-lg)) var(--spacing-xl); border-radius: var(--radius-xl); color: #fff; position: relative; overflow: hidden; box-shadow: 0 15px 50px rgba(0,0,0,0.15);">
                <!-- خلفية زخرفية -->
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h60v60H0z\' fill=\'none\'/%3E%3Cpath d=\'M30 0v60M0 30h60\' stroke=\'%23fff\' stroke-width=\'1\' opacity=\'.2\'/%3E%3C/svg%3E');"></div>

                <div style="position: relative; z-index: 1;">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div style="width: 120px; height: 120px; margin: 0 auto var(--spacing-md); border-radius: 50%; overflow: hidden; border: 5px solid rgba(255,255,255,0.3); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                            <?php the_post_thumbnail( 'thumbnail', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="entry-title" style="color: #fff; font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md);">
                        <?php the_title(); ?>
                    </h1>

                    <?php if ( $schedule ) : ?>
                        <p style="color: rgba(255,255,255,0.95); font-size: var(--font-size-xl); margin-bottom: var(--spacing-lg); display: flex; align-items: center; justify-content: center; gap: var(--spacing-xs);">
                            <span style="font-size: 24px;">📅</span>
                            <span><?php echo esc_html( $schedule ); ?></span>
                        </p>
                    <?php endif; ?>

                    <?php if ( $join_url ) : ?>
                        <div style="text-align: center;">
                            <a href="<?php echo esc_url( $join_url ); ?>" class="btn" style="background: #fff; color: var(--color-primary); border: none; font-size: var(--font-size-lg); padding: 14px 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" target="_blank">
                                <?php esc_html_e( 'انضم الآن', 'nadiim' ); ?>
                                <span style="margin-right: 8px;">→</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- الوصف -->
            <div class="entry-content" style="margin-bottom: var(--spacing-xl);">
                <?php the_content(); ?>
            </div>

            <!-- الكتاب الجاري قراءته -->
            <?php if ( $current_book_id ) :
                $book = get_post( $current_book_id );
                if ( $book ) : ?>
                    <div class="current-book" style="margin-bottom: var(--spacing-xl); padding: var(--spacing-lg); background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); color: #fff; border-radius: var(--radius-lg);">
                        <h2 style="text-align: center; margin-bottom: var(--spacing-lg); color: #fff;"><?php esc_html_e( 'الكتاب الجاري قراءته', 'nadiim' ); ?></h2>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: var(--spacing-lg); align-items: center;">
                            <div>
                                <?php echo get_the_post_thumbnail( $book->ID, 'medium', array( 'style' => 'width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-medium);' ) ); ?>
                            </div>
                            <div>
                                <h3 style="color: #fff; margin-bottom: var(--spacing-sm);"><?php echo esc_html( $book->post_title ); ?></h3>
                                <?php
                                $book_author = get_post_meta( $book->ID, 'release_author', true );
                                if ( $book_author ) : ?>
                                    <p style="color: rgba(255,255,255,0.9); margin-bottom: var(--spacing-md);">
                                        <?php echo esc_html( $book_author ); ?>
                                    </p>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( get_permalink( $book->ID ) ); ?>" class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #fff;">
                                    <?php esc_html_e( 'تفاصيل الكتاب', 'nadiim' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif;
            endif; ?>

            <?php
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>
        </div>
    </article>

<?php
endwhile;
get_footer();
?>
