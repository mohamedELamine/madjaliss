<?php
/**
 * قالب صفحة الكاتب
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
$author = get_queried_object();
?>

<main id="primary" class="site-main author-page">
    <div class="container">

        <!-- بطاقة الكاتب -->
        <div class="author-card" style="background-color: var(--color-bg-section); padding: var(--spacing-xl); border-radius: var(--radius-lg); margin-bottom: var(--spacing-xl); text-align: center;">
            <?php echo get_avatar( $author->ID, 150, '', '', array( 'style' => 'border-radius: 50%; border: 4px solid #fff; box-shadow: var(--shadow-medium); margin-bottom: var(--spacing-md);' ) ); ?>
            <h1 class="author-name" style="margin-bottom: var(--spacing-sm);"><?php echo esc_html( $author->display_name ); ?></h1>
            <?php if ( $author->description ) : ?>
                <p class="author-bio" style="max-width: 700px; margin: 0 auto var(--spacing-md); color: var(--color-text-secondary); font-size: var(--font-size-lg);">
                    <?php echo wp_kses_post( wpautop( $author->description ) ); ?>
                </p>
            <?php endif; ?>

            <!-- روابط التواصل -->
            <div class="author-social" style="display: flex; gap: var(--spacing-sm); justify-content: center;">
                <?php if ( $author->user_url ) : ?>
                    <a href="<?php echo esc_url( $author->user_url ); ?>" class="btn btn-outline btn-sm" target="_blank">
                        <?php esc_html_e( 'الموقع الشخصي', 'nadiim' ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- التبويبات -->
        <div class="author-tabs">
            <ul class="tabs-nav" style="display: flex; justify-content: center; gap: var(--spacing-md); margin-bottom: var(--spacing-xl); border-bottom: 2px solid var(--color-border); list-style: none; padding: 0;">
                <li class="active">
                    <a href="#posts-tab" style="display: block; padding: var(--spacing-sm) var(--spacing-md); font-weight: 600; border-bottom: 3px solid var(--color-primary); margin-bottom: -2px;">
                        <?php esc_html_e( 'المقالات', 'nadiim' ); ?>
                    </a>
                </li>
                <li>
                    <a href="#dialogues-tab" style="display: block; padding: var(--spacing-sm) var(--spacing-md); color: var(--color-text-secondary);">
                        <?php esc_html_e( 'الحوارات', 'nadiim' ); ?>
                    </a>
                </li>
                <li>
                    <a href="#releases-tab" style="display: block; padding: var(--spacing-sm) var(--spacing-md); color: var(--color-text-secondary);">
                        <?php esc_html_e( 'الإصدارات', 'nadiim' ); ?>
                    </a>
                </li>
            </ul>

            <!-- محتوى التبويبات -->
            <div class="tabs-content">
                <!-- تبويب المقالات -->
                <div id="posts-tab" class="tab-content active">
                    <?php
                    $posts_query = new WP_Query( array(
                        'author'         => $author->ID,
                        'post_type'      => 'post',
                        'posts_per_page' => 12,
                        'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
                    ) );

                    if ( $posts_query->have_posts() ) : ?>
                        <div class="grid grid-3">
                            <?php while ( $posts_query->have_posts() ) : $posts_query->the_post();
                                get_template_part( 'template-parts/content' );
                            endwhile; ?>
                        </div>
                        <?php the_posts_pagination(); ?>
                    <?php else : ?>
                        <p><?php esc_html_e( 'لا توجد مقالات حالياً', 'nadiim' ); ?></p>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>

                <!-- تبويب الحوارات -->
                <div id="dialogues-tab" class="tab-content">
                    <?php
                    $participants_meta_query = array(
                        'key'     => 'dialogue_participants',
                        'value'   => sprintf( 's:%d:"%d"', strlen( $author->ID ), $author->ID ),
                        'compare' => 'LIKE',
                    );

                    $dialogues_query = new WP_Query( array(
                        'post_type'      => 'howarat',
                        'posts_per_page' => 12,
                        'meta_query'     => array( $participants_meta_query ),
                    ) );

                    if ( $dialogues_query->have_posts() ) : ?>
                        <div class="grid grid-3">
                            <?php while ( $dialogues_query->have_posts() ) : $dialogues_query->the_post();
                                get_template_part( 'template-parts/content', 'howarat-card' );
                            endwhile; ?>
                        </div>
                    <?php else : ?>
                        <p><?php esc_html_e( 'لم يشارك في حوارات حتى الآن', 'nadiim' ); ?></p>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>

                <!-- تبويب الإصدارات -->
                <div id="releases-tab" class="tab-content">
                    <?php
                    $releases_query = new WP_Query( array(
                        'author'         => $author->ID,
                        'post_type'      => 'esdar',
                        'posts_per_page' => 12,
                    ) );

                    if ( $releases_query->have_posts() ) : ?>
                        <div class="grid grid-4">
                            <?php while ( $releases_query->have_posts() ) : $releases_query->the_post(); ?>
                                <article <?php post_class( 'card' ); ?>>
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="card-content">
                                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <p><?php esc_html_e( 'لا توجد إصدارات حالياً', 'nadiim' ); ?></p>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>

    </div>
</main>

<?php
get_footer();
