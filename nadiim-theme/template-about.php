<?php
/**
 * Template Name: من نحن
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) : the_post();
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container container-narrow">

            <header class="entry-header text-center" style="margin-bottom: var(--spacing-xl);">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content" style="margin-bottom: var(--spacing-xxl);">
                <?php the_content(); ?>
            </div>

            <!-- Timeline -->
            <?php
            $timeline_items = get_theme_mod( 'nadiim_timeline_items', array() );
            if ( ! empty( $timeline_items ) ) :
                ?>
                <section class="timeline-section" style="margin-bottom: var(--spacing-xxl);">
                    <h2 style="text-align: center; margin-bottom: var(--spacing-xl);"><?php esc_html_e( 'رحلتنا عبر الزمن', 'nadiim' ); ?></h2>

                    <div class="timeline" style="position: relative; padding-right: 50px;">
                        <!-- الخط الرأسي -->
                        <div style="position: absolute; right: 20px; top: 0; bottom: 0; width: 2px; background-color: var(--color-primary);"></div>

                        <?php foreach ( $timeline_items as $item ) : ?>
                            <div class="timeline-item" style="position: relative; margin-bottom: var(--spacing-xl);">
                                <!-- النقطة -->
                                <div style="position: absolute; right: -30px; width: 20px; height: 20px; background-color: var(--color-primary); border-radius: 50%; border: 4px solid var(--color-bg-lighter);"></div>

                                <!-- المحتوى -->
                                <div style="background-color: var(--color-bg-section); padding: var(--spacing-lg); border-radius: var(--radius-lg);">
                                    <h3 style="color: var(--color-primary); margin-bottom: var(--spacing-sm);">
                                        <?php echo esc_html( $item['year'] ?? '' ); ?>
                                    </h3>
                                    <h4 style="margin-bottom: var(--spacing-sm);">
                                        <?php echo esc_html( $item['title'] ?? '' ); ?>
                                    </h4>
                                    <p style="color: var(--color-text-secondary); margin: 0;">
                                        <?php echo esc_html( $item['description'] ?? '' ); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- إحصائيات -->
            <section class="stats-section" style="background-color: var(--color-bg-section); padding: var(--spacing-xl); border-radius: var(--radius-lg); margin-bottom: var(--spacing-xl);">
                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg); text-align: center;">
                    <div class="stat-item">
                        <div style="font-size: 48px; font-weight: 700; color: var(--color-primary); margin-bottom: var(--spacing-sm);">
                            <?php
                            $dialogues_count = wp_count_posts( 'howarat' )->publish;
                            echo esc_html( $dialogues_count );
                            ?>
                        </div>
                        <div style="color: var(--color-text-secondary);"><?php esc_html_e( 'حوار', 'nadiim' ); ?></div>
                    </div>
                    <div class="stat-item">
                        <div style="font-size: 48px; font-weight: 700; color: var(--color-primary); margin-bottom: var(--spacing-sm);">
                            <?php
                            $releases_count = wp_count_posts( 'esdar' )->publish;
                            echo esc_html( $releases_count );
                            ?>
                        </div>
                        <div style="color: var(--color-text-secondary);"><?php esc_html_e( 'إصدار', 'nadiim' ); ?></div>
                    </div>
                    <div class="stat-item">
                        <div style="font-size: 48px; font-weight: 700; color: var(--color-primary); margin-bottom: var(--spacing-sm);">
                            <?php
                            $clubs_count = wp_count_posts( 'reading_clubs' )->publish;
                            echo esc_html( $clubs_count );
                            ?>
                        </div>
                        <div style="color: var(--color-text-secondary);"><?php esc_html_e( 'نادي قراءة', 'nadiim' ); ?></div>
                    </div>
                </div>
            </section>

        </div>
    </article>

<?php
endwhile;
get_footer();
