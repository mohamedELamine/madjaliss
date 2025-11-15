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

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container container-narrow">

            <!-- Hero -->
            <div class="club-hero" style="text-align: center; padding: var(--spacing-xl) 0; background-color: var(--color-bg-section); margin: 0 calc(-1 * var(--spacing-md)) var(--spacing-xl); border-radius: var(--radius-lg);">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <?php if ( $schedule ) : ?>
                    <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg); margin-top: var(--spacing-sm);">
                        📅 <?php echo esc_html( $schedule ); ?>
                    </p>
                <?php endif; ?>
                <?php if ( $join_url ) : ?>
                    <div style="margin-top: var(--spacing-md);">
                        <a href="<?php echo esc_url( $join_url ); ?>" class="btn btn-primary" target="_blank">
                            <?php esc_html_e( 'انضم الآن', 'nadiim' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- الوصف -->
            <div class="entry-content" style="margin-bottom: var(--spacing-xl);">
                <?php the_content(); ?>
            </div>

            <!-- المشرف والأعضاء -->
            <div class="club-team" style="margin-bottom: var(--spacing-xl); background-color: var(--color-bg-section); padding: var(--spacing-lg); border-radius: var(--radius-lg);">
                <h2 style="text-align: center; margin-bottom: var(--spacing-lg);"><?php esc_html_e( 'فريق النادي', 'nadiim' ); ?></h2>

                <?php if ( $supervisor_id ) :
                    $supervisor = get_user_by( 'ID', $supervisor_id );
                    if ( $supervisor ) : ?>
                        <div class="club-supervisor-box" style="text-align: center; margin-bottom: var(--spacing-lg); padding: var(--spacing-md); background: var(--color-bg-lighter); border-radius: var(--radius-md);">
                            <h3 style="margin-bottom: var(--spacing-sm);"><?php esc_html_e( 'المشرف', 'nadiim' ); ?></h3>
                            <a href="<?php echo esc_url( get_author_posts_url( $supervisor->ID ) ); ?>" style="display: inline-block;">
                                <?php echo get_avatar( $supervisor->ID, 100, '', '', array( 'style' => 'border-radius: 50%; margin-bottom: var(--spacing-sm);' ) ); ?>
                                <h4 style="margin: 0;"><?php echo esc_html( $supervisor->display_name ); ?></h4>
                            </a>
                        </div>
                    <?php endif;
                endif; ?>

                <?php if ( ! empty( $members ) && is_array( $members ) ) : ?>
                    <div class="club-members">
                        <h3 style="text-align: center; margin-bottom: var(--spacing-md);">
                            <?php printf( esc_html__( 'الأعضاء (%d)', 'nadiim' ), count( $members ) ); ?>
                            <?php if ( $max_members ) : ?>
                                / <?php echo esc_html( $max_members ); ?>
                            <?php endif; ?>
                        </h3>
                        <div class="members-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: var(--spacing-md);">
                            <?php foreach ( $members as $member_id ) :
                                $member = get_user_by( 'ID', $member_id );
                                if ( ! $member ) continue; ?>
                                <div class="member-card" style="text-align: center;">
                                    <a href="<?php echo esc_url( get_author_posts_url( $member->ID ) ); ?>">
                                        <?php echo get_avatar( $member->ID, 80, '', '', array( 'style' => 'border-radius: 50%; margin-bottom: 8px;' ) ); ?>
                                        <div style="font-size: 14px; font-weight: 600;"><?php echo esc_html( $member->display_name ); ?></div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
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

            <!-- معلومات الاجتماعات -->
            <?php if ( $schedule || $meeting_url ) : ?>
                <div class="meeting-info" style="margin-bottom: var(--spacing-xl); padding: var(--spacing-lg); background-color: var(--color-bg-section); border-radius: var(--radius-lg);">
                    <h2 style="text-align: center; margin-bottom: var(--spacing-lg);"><?php esc_html_e( 'معلومات الاجتماعات', 'nadiim' ); ?></h2>
                    <?php if ( $schedule ) : ?>
                        <p style="text-align: center; font-size: var(--font-size-lg); margin-bottom: var(--spacing-md);">
                            <strong><?php esc_html_e( 'الجدول:', 'nadiim' ); ?></strong> <?php echo esc_html( $schedule ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $meeting_url ) : ?>
                        <div style="text-align: center;">
                            <a href="<?php echo esc_url( $meeting_url ); ?>" class="btn btn-primary" target="_blank">
                                <?php esc_html_e( 'دخول الاجتماع', 'nadiim' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

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
