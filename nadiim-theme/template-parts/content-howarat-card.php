<?php
/**
 * قالب بطاقة الحوار (للعرض في الأرشيف والصفحة الرئيسية)
 *
 * @package Nadiim
 * @since 1.0.0
 */

$dialogue_date       = get_post_meta( get_the_ID(), 'dialogue_date', true );
$dialogue_media_type = get_post_meta( get_the_ID(), 'dialogue_media_type', true );
$dialogue_location   = get_post_meta( get_the_ID(), 'dialogue_location', true );
$participants        = get_post_meta( get_the_ID(), 'dialogue_participants', true );

// تحديد أيقونة نوع الحوار
$media_icon = 'document';
$media_label = __( 'مكتوب', 'nadiim' );

switch ( $dialogue_media_type ) {
    case 'video':
        $media_icon  = 'video';
        $media_label = __( 'فيديو', 'nadiim' );
        break;
    case 'audio':
        $media_icon  = 'audio';
        $media_label = __( 'صوتي', 'nadiim' );
        break;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card howarat-card' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" class="card-image-link">
            <?php the_post_thumbnail( 'nadiim-card', array( 'class' => 'card-image' ) ); ?>
            <!-- شارة نوع الحوار -->
            <div class="dialogue-type-badge" style="position: absolute; top: 12px; right: 12px; background: rgba(51, 144, 99, 0.95); color: white; padding: 6px 12px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                <?php echo nadiim_get_icon( $media_icon ); ?>
                <span><?php echo esc_html( $media_label ); ?></span>
            </div>
        </a>
    <?php endif; ?>

    <div class="card-content">

        <!-- التاريخ والموقع -->
        <div class="card-meta" style="margin-bottom: var(--spacing-sm);">
            <?php if ( $dialogue_date ) : ?>
                <span class="dialogue-date" style="display: inline-flex; align-items: center; gap: 4px; margin-left: 12px;">
                    <?php echo nadiim_get_icon( 'calendar' ); ?>
                    <?php echo esc_html( date_i18n( 'j F، Y', strtotime( $dialogue_date ) ) ); ?>
                </span>
            <?php endif; ?>

            <?php if ( $dialogue_location ) : ?>
                <span class="dialogue-location" style="color: var(--color-text-light); font-size: var(--font-size-sm);">
                    📍 <?php echo esc_html( $dialogue_location ); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- العنوان -->
        <h3 class="card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <!-- المقتطف -->
        <div class="card-excerpt">
            <?php echo nadiim_get_excerpt( 15 ); ?>
        </div>

        <!-- المشاركون -->
        <?php if ( ! empty( $participants ) && is_array( $participants ) ) : ?>
            <div class="dialogue-participants" style="margin: var(--spacing-sm) 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="font-size: 14px; color: var(--color-text-light); font-weight: 600;">
                    <?php esc_html_e( 'المشاركون:', 'nadiim' ); ?>
                </span>
                <?php
                $participant_names = array();
                foreach ( array_slice( $participants, 0, 3 ) as $user_id ) :
                    $user = get_user_by( 'ID', $user_id );
                    if ( $user ) :
                        $participant_names[] = sprintf(
                            '<a href="%s" style="font-size: 14px; color: var(--color-primary);">%s</a>',
                            esc_url( get_author_posts_url( $user->ID ) ),
                            esc_html( $user->display_name )
                        );
                    endif;
                endforeach;

                echo implode( '، ', $participant_names );

                if ( count( $participants ) > 3 ) :
                    echo ' <span style="font-size: 14px; color: var(--color-text-light);">+'
                        . ( count( $participants ) - 3 ) . '</span>';
                endif;
                ?>
            </div>
        <?php endif; ?>

        <!-- المواضيع -->
        <?php
        $topics = get_the_terms( get_the_ID(), 'dialogue_topic' );
        if ( $topics && ! is_wp_error( $topics ) ) :
            ?>
            <div class="dialogue-topics" style="margin-top: var(--spacing-sm); display: flex; gap: 6px; flex-wrap: wrap;">
                <?php foreach ( array_slice( $topics, 0, 3 ) as $topic ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $topic ) ); ?>"
                       class="badge badge-outline"
                       style="font-size: 12px; padding: 3px 10px;">
                        <?php echo esc_html( $topic->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- زر القراءة/الاستماع -->
        <div style="margin-top: var(--spacing-md);">
            <?php
            if ( 'video' === $dialogue_media_type ) {
                echo nadiim_read_more_link( __( 'شاهد الحوار', 'nadiim' ) );
            } elseif ( 'audio' === $dialogue_media_type ) {
                echo nadiim_read_more_link( __( 'استمع الآن', 'nadiim' ) );
            } else {
                echo nadiim_read_more_link( __( 'اقرأ المزيد', 'nadiim' ) );
            }
            ?>
        </div>

    </div>

</article>
