<?php
/**
 * Template Name: اتصل بنا
 *
 * @package Nadiim
 * @since 1.0.0
 */

// معالجة النموذج
if ( isset( $_POST['nadiim_contact_submit'] ) && wp_verify_nonce( $_POST['nadiim_contact_nonce'], 'nadiim_contact_form' ) ) {
    $name = sanitize_text_field( $_POST['contact_name'] );
    $email = sanitize_email( $_POST['contact_email'] );
    $subject = sanitize_text_field( $_POST['contact_subject'] );
    $message = sanitize_textarea_field( $_POST['contact_message'] );

    // إنشاء منشور في CPT inquiries
    $inquiry_id = wp_insert_post( array(
        'post_title'   => sprintf( '%s - %s', $name, $subject ),
        'post_content' => sprintf(
            "الاسم: %s\nالبريد: %s\n\nالرسالة:\n%s",
            $name,
            $email,
            $message
        ),
        'post_type'    => 'inquiries',
        'post_status'  => 'publish',
    ) );

    if ( $inquiry_id ) {
        // حفظ البريد كـ meta
        update_post_meta( $inquiry_id, 'inquiry_email', $email );

        // إرسال بريد للمدير
        $admin_email = get_option( 'admin_email' );
        $email_subject = sprintf( '[%s] رسالة جديدة: %s', get_bloginfo( 'name' ), $subject );
        $email_body = sprintf(
            "رسالة جديدة من موقع %s\n\nالاسم: %s\nالبريد: %s\n\nالرسالة:\n%s",
            get_bloginfo( 'name' ),
            $name,
            $email,
            $message
        );

        wp_mail( $admin_email, $email_subject, $email_body );

        $success_message = true;
    }
}

get_header();
?>

<main id="primary" class="site-main contact-page">
    <div class="container container-narrow">

        <header class="entry-header text-center" style="margin-bottom: var(--spacing-xl);">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <p style="color: var(--color-text-secondary); font-size: var(--font-size-lg);">
                <?php esc_html_e( 'نسعد بتواصلكم معنا، أرسلوا استفساراتكم ومقترحاتكم', 'nadiim' ); ?>
            </p>
        </header>

        <?php if ( isset( $success_message ) ) : ?>
            <div class="success-message" style="background-color: #d4edda; color: #155724; padding: var(--spacing-md); border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); text-align: center;">
                <?php esc_html_e( 'شكراً لتواصلك معنا! سنرد عليك في أقرب وقت.', 'nadiim' ); ?>
            </div>
        <?php endif; ?>

        <div class="contact-content" style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--spacing-xl); margin-bottom: var(--spacing-xl);">

            <!-- نموذج الاتصال -->
            <div class="contact-form-wrapper">
                <form method="post" class="contact-form" style="background-color: var(--color-bg-section); padding: var(--spacing-xl); border-radius: var(--radius-lg);">
                    <?php wp_nonce_field( 'nadiim_contact_form', 'nadiim_contact_nonce' ); ?>

                    <div style="margin-bottom: var(--spacing-md);">
                        <label for="contact_name" style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <?php esc_html_e( 'الاسم', 'nadiim' ); ?> <span style="color: #e74c3c;">*</span>
                        </label>
                        <input type="text" id="contact_name" name="contact_name" required
                               style="width: 100%; padding: 12px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: var(--font-size-base);">
                    </div>

                    <div style="margin-bottom: var(--spacing-md);">
                        <label for="contact_email" style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <?php esc_html_e( 'البريد الإلكتروني', 'nadiim' ); ?> <span style="color: #e74c3c;">*</span>
                        </label>
                        <input type="email" id="contact_email" name="contact_email" required
                               style="width: 100%; padding: 12px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: var(--font-size-base);">
                    </div>

                    <div style="margin-bottom: var(--spacing-md);">
                        <label for="contact_subject" style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <?php esc_html_e( 'الموضوع', 'nadiim' ); ?> <span style="color: #e74c3c;">*</span>
                        </label>
                        <input type="text" id="contact_subject" name="contact_subject" required
                               style="width: 100%; padding: 12px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: var(--font-size-base);">
                    </div>

                    <div style="margin-bottom: var(--spacing-md);">
                        <label for="contact_message" style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <?php esc_html_e( 'الرسالة', 'nadiim' ); ?> <span style="color: #e74c3c;">*</span>
                        </label>
                        <textarea id="contact_message" name="contact_message" rows="6" required
                                  style="width: 100%; padding: 12px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: var(--font-size-base); resize: vertical;"></textarea>
                    </div>

                    <button type="submit" name="nadiim_contact_submit" class="btn btn-primary" style="width: 100%;">
                        <?php esc_html_e( 'إرسال الرسالة', 'nadiim' ); ?>
                    </button>
                </form>
            </div>

            <!-- معلومات التواصل -->
            <div class="contact-info">
                <div style="background-color: var(--color-bg-section); padding: var(--spacing-lg); border-radius: var(--radius-lg); margin-bottom: var(--spacing-md);">
                    <h3 style="margin-bottom: var(--spacing-md);"><?php esc_html_e( 'معلومات التواصل', 'nadiim' ); ?></h3>

                    <?php
                    $contact_email = get_theme_mod( 'nadiim_contact_email', get_option( 'admin_email' ) );
                    $contact_phone = get_theme_mod( 'nadiim_contact_phone' );
                    $contact_address = get_theme_mod( 'nadiim_contact_address' );
                    ?>

                    <?php if ( $contact_email ) : ?>
                        <p style="margin-bottom: var(--spacing-sm);">
                            <strong><?php esc_html_e( 'البريد:', 'nadiim' ); ?></strong><br>
                            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" style="color: var(--color-primary);">
                                <?php echo esc_html( $contact_email ); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if ( $contact_phone ) : ?>
                        <p style="margin-bottom: var(--spacing-sm);">
                            <strong><?php esc_html_e( 'الهاتف:', 'nadiim' ); ?></strong><br>
                            <?php echo esc_html( $contact_phone ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( $contact_address ) : ?>
                        <p style="margin: 0;">
                            <strong><?php esc_html_e( 'العنوان:', 'nadiim' ); ?></strong><br>
                            <?php echo esc_html( $contact_address ); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <?php while ( have_posts() ) : the_post(); ?>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>

    </div>
</main>

<?php
get_footer();
