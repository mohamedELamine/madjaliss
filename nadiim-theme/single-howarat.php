<?php
/**
 * قالب الحوار المفرد
 *
 * يعرض الحوار بتفاصيله الكاملة مع الوسائط والترانسكريبت والمشاركين
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) :
    the_post();

    // جلب البيانات المخصصة
    $dialogue_date       = get_post_meta( get_the_ID(), 'dialogue_date', true );
    $dialogue_media_type = get_post_meta( get_the_ID(), 'dialogue_media_type', true );
    $dialogue_media_url  = get_post_meta( get_the_ID(), 'dialogue_media_url', true );
    $dialogue_location   = get_post_meta( get_the_ID(), 'dialogue_location', true );
    $dialogue_transcript = get_post_meta( get_the_ID(), 'dialogue_transcript', true );
    $participants        = get_post_meta( get_the_ID(), 'dialogue_participants', true );

    // تحديد أيقونة ونص نوع الحوار
    $media_icon  = 'document';
    $media_label = __( 'حوار مكتوب', 'nadiim' );
    $action_text = __( 'اقرأ النص', 'nadiim' );

    switch ( $dialogue_media_type ) {
        case 'video':
            $media_icon  = 'video';
            $media_label = __( 'حوار مرئي', 'nadiim' );
            $action_text = __( 'شاهد الآن', 'nadiim' );
            break;
        case 'audio':
            $media_icon  = 'audio';
            $media_label = __( 'حوار صوتي', 'nadiim' );
            $action_text = __( 'استمع الآن', 'nadiim' );
            break;
    }
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-howarat' ); ?>>

        <!-- Hero Section -->
        <div class="dialogue-hero" style="position: relative; background-color: var(--color-text-primary); color: #ffffff; padding: var(--spacing-xxl) 0; margin-bottom: var(--spacing-xl);">

            <?php if ( has_post_thumbnail() ) : ?>
                <!-- صورة الخلفية -->
                <div class="hero-background" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.2; background-size: cover; background-position: center;">
                    <?php the_post_thumbnail( 'nadiim-hero' ); ?>
                </div>
            <?php endif; ?>

            <div class="container" style="position: relative; z-index: 1;">
                <div class="hero-content" style="max-width: 900px; margin: 0 auto; text-align: center;">

                    <!-- نوع الحوار -->
                    <div class="dialogue-type" style="margin-bottom: var(--spacing-md); display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.1); padding: 8px 20px; border-radius: var(--radius-lg); backdrop-filter: blur(10px);">
                        <?php echo nadiim_get_icon( $media_icon ); ?>
                        <span style="font-weight: 600;"><?php echo esc_html( $media_label ); ?></span>
                    </div>

                    <!-- العنوان -->
                    <h1 class="entry-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md); color: #ffffff;">
                        <?php the_title(); ?>
                    </h1>

                    <!-- الميتا -->
                    <div class="dialogue-meta" style="display: flex; align-items: center; justify-content: center; gap: var(--spacing-md); flex-wrap: wrap; margin-bottom: var(--spacing-lg); color: rgba(255, 255, 255, 0.9);">
                        <?php if ( $dialogue_date ) : ?>
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <?php echo nadiim_get_icon( 'calendar' ); ?>
                                <?php echo esc_html( date_i18n( 'j F، Y', strtotime( $dialogue_date ) ) ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $dialogue_location ) : ?>
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                📍 <?php echo esc_html( $dialogue_location ); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="dialogue-actions" style="display: flex; gap: var(--spacing-sm); justify-content: center; flex-wrap: wrap;">
                        <?php if ( $dialogue_media_url && in_array( $dialogue_media_type, array( 'video', 'audio' ), true ) ) : ?>
                            <a href="#media-player" class="btn btn-primary" style="scroll-behavior: smooth;">
                                <?php echo esc_html( $action_text ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( $dialogue_transcript ) : ?>
                            <a href="#transcript" class="btn btn-outline" style="background-color: rgba(255, 255, 255, 0.1); color: #ffffff; border-color: rgba(255, 255, 255, 0.3);">
                                <?php esc_html_e( 'اقرأ الملخص', 'nadiim' ); ?>
                            </a>
                        <?php endif; ?>

                        <button id="share-dialogue" class="btn btn-outline" style="background-color: rgba(255, 255, 255, 0.1); color: #ffffff; border-color: rgba(255, 255, 255, 0.3);">
                            <?php esc_html_e( 'شارك', 'nadiim' ); ?>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="container container-narrow">

            <!-- المشاركون -->
            <?php if ( ! empty( $participants ) && is_array( $participants ) ) : ?>
                <section class="dialogue-participants-section" style="margin-bottom: var(--spacing-xl); padding: var(--spacing-lg); background-color: var(--color-bg-section); border-radius: var(--radius-lg);">
                    <h2 style="text-align: center; margin-bottom: var(--spacing-lg);">
                        <?php esc_html_e( 'المشاركون في الحوار', 'nadiim' ); ?>
                    </h2>

                    <div class="participants-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-md);">
                        <?php foreach ( $participants as $user_id ) :
                            $user = get_user_by( 'ID', $user_id );
                            if ( ! $user ) {
                                continue;
                            }
                            ?>
                            <div class="participant-card" style="text-align: center; padding: var(--spacing-md); background: var(--color-bg-lighter); border-radius: var(--radius-md); transition: transform 0.3s ease;">
                                <a href="<?php echo esc_url( get_author_posts_url( $user->ID ) ); ?>" style="display: block;">
                                    <?php echo get_avatar( $user->ID, 80, '', '', array( 'style' => 'border-radius: 50%; margin: 0 auto var(--spacing-sm);' ) ); ?>
                                    <h4 style="margin: 0; color: var(--color-text-primary); font-size: var(--font-size-lg);">
                                        <?php echo esc_html( $user->display_name ); ?>
                                    </h4>
                                    <?php if ( $user->description ) : ?>
                                        <p style="margin: var(--spacing-sm) 0 0; font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                                            <?php echo esc_html( wp_trim_words( $user->description, 10 ) ); ?>
                                        </p>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- مشغل الوسائط -->
            <?php if ( $dialogue_media_url && in_array( $dialogue_media_type, array( 'video', 'audio' ), true ) ) : ?>
                <section id="media-player" class="dialogue-media" style="margin-bottom: var(--spacing-xl);">
                    <h2 style="text-align: center; margin-bottom: var(--spacing-lg);">
                        <?php
                        if ( 'video' === $dialogue_media_type ) {
                            esc_html_e( 'شاهد الحوار', 'nadiim' );
                        } else {
                            esc_html_e( 'استمع إلى الحوار', 'nadiim' );
                        }
                        ?>
                    </h2>

                    <div class="media-embed" style="background-color: var(--color-bg-section); padding: var(--spacing-md); border-radius: var(--radius-lg);">
                        <?php
                        // استخدام wp_oembed لدعم YouTube, Vimeo, SoundCloud وغيرها
                        echo wp_oembed_get( $dialogue_media_url, array( 'width' => 800 ) );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- المحتوى الرئيسي -->
            <div class="entry-content" style="margin-bottom: var(--spacing-xl); line-height: 1.9; font-size: var(--font-size-lg);">
                <?php the_content(); ?>
            </div>

            <!-- نص الترانسكريبت -->
            <?php if ( $dialogue_transcript ) : ?>
                <section id="transcript" class="dialogue-transcript" style="margin-bottom: var(--spacing-xl);">
                    <h2 style="text-align: center; margin-bottom: var(--spacing-lg); padding-bottom: var(--spacing-md); border-bottom: 2px solid var(--color-primary);">
                        <?php esc_html_e( 'نص الحوار الكامل', 'nadiim' ); ?>
                    </h2>

                    <div class="transcript-content" style="background-color: var(--color-bg-section); padding: var(--spacing-xl); border-radius: var(--radius-lg); border-right: 4px solid var(--color-primary); line-height: 2; font-size: var(--font-size-base);">
                        <?php echo wp_kses_post( wpautop( $dialogue_transcript ) ); ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- المواضيع والتصنيفات -->
            <div class="dialogue-taxonomies" style="margin-bottom: var(--spacing-xl); padding: var(--spacing-lg); background-color: var(--color-bg-section); border-radius: var(--radius-lg);">
                <?php
                // نوع الحوار
                $types = get_the_terms( get_the_ID(), 'dialogue_type' );
                if ( $types && ! is_wp_error( $types ) ) :
                    ?>
                    <div style="margin-bottom: var(--spacing-md);">
                        <strong style="margin-left: var(--spacing-sm);"><?php esc_html_e( 'النوع:', 'nadiim' ); ?></strong>
                        <?php foreach ( $types as $type ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $type ) ); ?>" class="badge">
                                <?php echo esc_html( $type->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                // مواضيع الحوار
                $topics = get_the_terms( get_the_ID(), 'dialogue_topic' );
                if ( $topics && ! is_wp_error( $topics ) ) :
                    ?>
                    <div>
                        <strong style="margin-left: var(--spacing-sm);"><?php esc_html_e( 'المواضيع:', 'nadiim' ); ?></strong>
                        <?php foreach ( $topics as $topic ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $topic ) ); ?>" class="badge badge-outline">
                                <?php echo esc_html( $topic->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- حوارات مشابهة -->
            <?php
            $related_howarat = new WP_Query( array(
                'post_type'      => 'howarat',
                'posts_per_page' => 3,
                'post__not_in'   => array( get_the_ID() ),
                'orderby'        => 'rand',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'dialogue_topic',
                        'terms'    => wp_get_post_terms( get_the_ID(), 'dialogue_topic', array( 'fields' => 'ids' ) ),
                    ),
                ),
            ) );

            if ( $related_howarat->have_posts() ) :
                ?>
                <section class="related-dialogues" style="margin-bottom: var(--spacing-xl);">
                    <h2 style="text-align: center; margin-bottom: var(--spacing-lg);">
                        <?php esc_html_e( 'حوارات مشابهة', 'nadiim' ); ?>
                    </h2>

                    <div class="grid grid-3">
                        <?php
                        while ( $related_howarat->have_posts() ) :
                            $related_howarat->the_post();
                            get_template_part( 'template-parts/content', 'howarat-card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- التعليقات -->
            <?php
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        </div>

    </article>

    <?php
    // إضافة Schema.org للحوار
    nadiim_dialogue_schema();
    ?>

<?php
endwhile;

get_footer();

/**
 * إضافة Schema.org للحوار
 */
function nadiim_dialogue_schema() {
    $dialogue_date = get_post_meta( get_the_ID(), 'dialogue_date', true );
    $dialogue_location = get_post_meta( get_the_ID(), 'dialogue_location', true );
    $participants = get_post_meta( get_the_ID(), 'dialogue_participants', true );

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'Event',
        'name'          => get_the_title(),
        'description'   => get_the_excerpt(),
        'startDate'     => $dialogue_date ? date( 'c', strtotime( $dialogue_date ) ) : get_the_date( 'c' ),
        'eventStatus'   => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    );

    if ( $dialogue_location ) {
        $schema['location'] = array(
            '@type' => 'Place',
            'name'  => $dialogue_location,
        );
    }

    if ( has_post_thumbnail() ) {
        $schema['image'] = get_the_post_thumbnail_url( null, 'full' );
    }

    if ( ! empty( $participants ) && is_array( $participants ) ) {
        $schema['performer'] = array();
        foreach ( $participants as $user_id ) {
            $user = get_user_by( 'ID', $user_id );
            if ( $user ) {
                $schema['performer'][] = array(
                    '@type' => 'Person',
                    'name'  => $user->display_name,
                );
            }
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
?>

<script>
// زر المشاركة
jQuery(document).ready(function($) {
    $('#share-dialogue').on('click', function() {
        if (navigator.share) {
            navigator.share({
                title: '<?php echo esc_js( get_the_title() ); ?>',
                text: '<?php echo esc_js( get_the_excerpt() ); ?>',
                url: '<?php echo esc_js( get_permalink() ); ?>'
            }).catch(function(error) {
                console.log('Error sharing:', error);
            });
        } else {
            // نسخ الرابط للحافظة
            var tempInput = document.createElement('input');
            tempInput.value = '<?php echo esc_js( get_permalink() ); ?>';
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('تم نسخ الرابط!');
        }
    });
});
</script>
