<?php
/**
 * قالب المشاركين في الحوار
 *
 * نظام مخصص لعرض المشاركين في الحوار بدلاً من نظام التعليقات التقليدي
 * يسمح بإضافة مشاركين مع نصوص وصور - قد لا يكونون أعضاء مسجلين
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( post_password_required() ) {
    return;
}

// عرض المشاركين فقط في صفحات الحوارات (نوع المنشور: howarat)
if ( get_post_type() !== 'howarat' ) {
    return;
}

// جلب المشاركين المخصصين من post meta
$participants = get_post_meta( get_the_ID(), 'dialogue_participants', true );
?>

<div id="participants" class="participants-section" style="padding-top: var(--spacing-xl); border-top: 2px solid var(--color-border); margin-top: var(--spacing-xl);">

    <?php if ( ! empty( $participants ) && is_array( $participants ) ) : ?>

        <div class="participants-list" style="display: grid; gap: var(--spacing-xl);">
            <?php foreach ( $participants as $index => $participant ) :
                $name = isset( $participant['name'] ) ? $participant['name'] : '';
                $role = isset( $participant['role'] ) ? $participant['role'] : '';
                $bio = isset( $participant['bio'] ) ? $participant['bio'] : '';
                $image = isset( $participant['image'] ) ? $participant['image'] : '';
                $image_url = $image ? wp_get_attachment_image_url( $image, 'thumbnail' ) : '';

                if ( empty( $name ) ) continue;

                $alternate_class = ( $index % 2 === 0 ) ? 'participant-even' : 'participant-odd';
                $flex_direction = ( $index % 2 === 0 ) ? 'row' : 'row-reverse';
            ?>
                <div class="participant-card <?php echo esc_attr( $alternate_class ); ?>" style="display: grid; grid-template-columns: 150px 1fr; gap: var(--spacing-lg); padding: var(--spacing-lg); background: var(--color-bg-section); border-radius: var(--radius-lg); border-right: 4px solid var(--color-primary); <?php echo $index % 2 !== 0 ? 'direction: rtl;' : ''; ?>">

                    <!-- صورة المشارك -->
                    <div class="participant-image-wrapper" style="text-align: center;">
                        <?php if ( $image_url ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>"
                                 alt="<?php echo esc_attr( $name ); ?>"
                                 class="participant-image"
                                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--color-bg-lighter); box-shadow: var(--shadow-medium);" />
                        <?php else : ?>
                            <div class="participant-avatar-placeholder" style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 4px solid var(--color-bg-lighter); box-shadow: var(--shadow-medium);">
                                <span style="color: #fff; font-size: var(--font-size-2xl); font-weight: 700;">
                                    <?php echo esc_html( mb_substr( $name, 0, 1 ) ); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- معلومات المشارك -->
                    <div class="participant-info" style="<?php echo $index % 2 !== 0 ? 'direction: rtl; text-align: right;' : ''; ?>">
                        <h3 class="participant-name" style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-xs); color: var(--color-primary);">
                            <?php echo esc_html( $name ); ?>
                        </h3>

                        <?php if ( $role ) : ?>
                            <p class="participant-role" style="color: var(--color-text-secondary); font-size: var(--font-size-base); margin-bottom: var(--spacing-sm); font-weight: 600;">
                                <?php echo esc_html( $role ); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ( $bio ) : ?>
                            <div class="participant-bio" style="color: var(--color-text); font-size: var(--font-size-base); line-height: 1.7; padding-top: var(--spacing-sm); border-top: 1px solid var(--color-border-light);">
                                <?php echo wp_kses_post( wpautop( $bio ) ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    <?php else : ?>

        <!-- رسالة في حالة عدم وجود مشاركين -->
        <div class="no-participants" style="text-align: center; padding: var(--spacing-xl); background: var(--color-bg-section); border-radius: var(--radius-lg); margin: var(--spacing-xl) 0;">
            <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg);">
                <?php esc_html_e( 'لم يتم إضافة مشاركين لهذا الحوار بعد.', 'nadiim' ); ?>
            </p>
        </div>

    <?php endif; ?>

</div><!-- #participants -->
