<?php
/**
 * Template Name: اتصل بنا
 * Template Post Type: page
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main page-contact">
    <div class="container container-narrow">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- Hero Section -->
                <header class="page-header" style="text-align: center; padding: var(--spacing-xxl) 0 var(--spacing-xl); background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); margin: 0 calc(-1 * var(--spacing-lg)) var(--spacing-xl); border-radius: var(--radius-xl); color: #fff; position: relative; overflow: hidden;">
                    <!-- خلفية زخرفية -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h60v60H0z\' fill=\'none\'/%3E%3Cpath d=\'M30 0v60M0 30h60\' stroke=\'%23fff\' stroke-width=\'1\' opacity=\'.2\'/%3E%3C/svg%3E');"></div>

                    <div style="position: relative; z-index: 1;">
                        <div style="font-size: 64px; margin-bottom: var(--spacing-md);">✉️</div>
                        <h1 class="entry-title" style="font-size: var(--font-size-3xl); margin-bottom: var(--spacing-md); color: #fff;">
                            <?php the_title(); ?>
                        </h1>

                        <?php if ( has_excerpt() ) : ?>
                            <div class="page-excerpt" style="font-size: var(--font-size-xl); color: rgba(255,255,255,0.95); max-width: 600px; margin: 0 auto; line-height: 1.8;">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- المحتوى الرئيسي -->
                <?php if ( get_the_content() ) : ?>
                    <div class="entry-content" style="font-size: var(--font-size-lg); line-height: 2; margin-bottom: var(--spacing-xl); text-align: center; color: var(--color-text-secondary);">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <!-- قسم معلومات الاتصال ونموذج التواصل -->
                <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: var(--spacing-xl); margin-bottom: var(--spacing-xl);">

                    <!-- معلومات الاتصال -->
                    <div style="background: linear-gradient(135deg, var(--color-bg-section) 0%, var(--color-bg-lighter) 100%); padding: var(--spacing-xl); border-radius: var(--radius-xl); border-right: 4px solid var(--color-primary);">
                        <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-lg); color: var(--color-primary);">
                            <?php esc_html_e( 'معلومات التواصل', 'nadiim' ); ?>
                        </h2>

                        <div style="display: grid; gap: var(--spacing-lg);">
                            <?php
                            $contact_email = get_theme_mod( 'nadiim_contact_email', get_option( 'admin_email' ) );
                            $contact_phone = get_theme_mod( 'nadiim_contact_phone', '' );
                            $contact_address = get_theme_mod( 'nadiim_contact_address', '' );
                            $contact_hours = get_theme_mod( 'nadiim_contact_hours', '' );
                            ?>

                            <?php if ( $contact_email ) : ?>
                                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--color-primary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                        <?php echo nadiim_get_icon( 'email' ); ?>
                                    </div>
                                    <div style="flex: 1;">
                                        <h3 style="font-size: var(--font-size-base); margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">
                                            <?php esc_html_e( 'البريد الإلكتروني', 'nadiim' ); ?>
                                        </h3>
                                        <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" style="color: var(--color-text); text-decoration: none; font-weight: 600; word-break: break-all;">
                                            <?php echo esc_html( $contact_email ); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $contact_phone ) : ?>
                                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--color-primary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                        📱
                                    </div>
                                    <div style="flex: 1;">
                                        <h3 style="font-size: var(--font-size-base); margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">
                                            <?php esc_html_e( 'رقم الهاتف', 'nadiim' ); ?>
                                        </h3>
                                        <a href="tel:<?php echo esc_attr( str_replace( ' ', '', $contact_phone ) ); ?>" style="color: var(--color-text); text-decoration: none; font-weight: 600; direction: ltr; display: block; text-align: right;">
                                            <?php echo esc_html( $contact_phone ); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $contact_address ) : ?>
                                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--color-primary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                        📍
                                    </div>
                                    <div style="flex: 1;">
                                        <h3 style="font-size: var(--font-size-base); margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">
                                            <?php esc_html_e( 'العنوان', 'nadiim' ); ?>
                                        </h3>
                                        <p style="color: var(--color-text); font-weight: 600; line-height: 1.6; margin: 0;">
                                            <?php echo nl2br( esc_html( $contact_address ) ); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $contact_hours ) : ?>
                                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--color-primary); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                        ⏰
                                    </div>
                                    <div style="flex: 1;">
                                        <h3 style="font-size: var(--font-size-base); margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">
                                            <?php esc_html_e( 'ساعات العمل', 'nadiim' ); ?>
                                        </h3>
                                        <p style="color: var(--color-text); font-weight: 600; line-height: 1.6; margin: 0;">
                                            <?php echo nl2br( esc_html( $contact_hours ) ); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- وسائل التواصل الاجتماعي -->
                        <div style="margin-top: var(--spacing-xl); padding-top: var(--spacing-xl); border-top: 2px solid var(--color-border-light);">
                            <h3 style="font-size: var(--font-size-lg); margin-bottom: var(--spacing-md); color: var(--color-text);">
                                <?php esc_html_e( 'تابعنا على', 'nadiim' ); ?>
                            </h3>
                            <div style="display: flex; gap: var(--spacing-sm); flex-wrap: wrap;">
                                <?php
                                $social_links = array(
                                    'facebook'  => get_theme_mod( 'nadiim_social_facebook', '' ),
                                    'twitter'   => get_theme_mod( 'nadiim_social_twitter', '' ),
                                    'instagram' => get_theme_mod( 'nadiim_social_instagram', '' ),
                                    'linkedin'  => get_theme_mod( 'nadiim_social_linkedin', '' ),
                                    'youtube'   => get_theme_mod( 'nadiim_social_youtube', '' ),
                                );

                                foreach ( $social_links as $platform => $url ) :
                                    if ( ! empty( $url ) ) : ?>
                                        <a href="<?php echo esc_url( $url ); ?>"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; border-radius: 50%; background: var(--color-primary); color: #fff; font-size: 20px; transition: all 0.3s ease; text-decoration: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                            <?php echo nadiim_get_icon( $platform ); ?>
                                        </a>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- نموذج التواصل -->
                    <div style="background: #fff; padding: var(--spacing-xl); border-radius: var(--radius-xl); box-shadow: 0 5px 30px rgba(0,0,0,0.1); border-top: 4px solid var(--color-primary);">
                        <h2 style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-md); color: var(--color-primary);">
                            <?php esc_html_e( 'أرسل رسالة', 'nadiim' ); ?>
                        </h2>
                        <p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-lg); line-height: 1.7;">
                            <?php esc_html_e( 'نسعد بتواصلك معنا. املأ النموذج وسنرد عليك في أقرب وقت ممكن.', 'nadiim' ); ?>
                        </p>

                        <?php
                        // يمكنك استخدام Contact Form 7 أو Gravity Forms هنا
                        // مثال: echo do_shortcode('[contact-form-7 id="123"]');
                        ?>

                        <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: grid; gap: var(--spacing-md);">
                            <input type="hidden" name="action" value="nadiim_contact_form">
                            <?php wp_nonce_field( 'nadiim_contact_form', 'nadiim_contact_nonce' ); ?>

                            <div>
                                <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600; color: var(--color-text);">
                                    <?php esc_html_e( 'الاسم', 'nadiim' ); ?> <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="contact_name" required
                                       style="width: 100%; padding: 14px 18px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); transition: border-color 0.3s ease;">
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600; color: var(--color-text);">
                                    <?php esc_html_e( 'البريد الإلكتروني', 'nadiim' ); ?> <span style="color: red;">*</span>
                                </label>
                                <input type="email" name="contact_email" required
                                       style="width: 100%; padding: 14px 18px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); transition: border-color 0.3s ease;">
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600; color: var(--color-text);">
                                    <?php esc_html_e( 'الموضوع', 'nadiim' ); ?> <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="contact_subject" required
                                       style="width: 100%; padding: 14px 18px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); transition: border-color 0.3s ease;">
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: var(--spacing-xs); font-weight: 600; color: var(--color-text);">
                                    <?php esc_html_e( 'الرسالة', 'nadiim' ); ?> <span style="color: red;">*</span>
                                </label>
                                <textarea name="contact_message" required rows="6"
                                          style="width: 100%; padding: 14px 18px; border: 2px solid var(--color-border); border-radius: var(--radius-md); font-size: var(--font-size-base); resize: vertical; font-family: inherit; line-height: 1.6; transition: border-color 0.3s ease;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; font-size: var(--font-size-lg); padding: 16px 32px;">
                                <?php esc_html_e( 'إرسال الرسالة', 'nadiim' ); ?>
                                <span style="margin-right: 8px;">→</span>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- خريطة (اختياري) -->
                <?php
                $map_embed = get_post_meta( get_the_ID(), 'contact_map_embed', true );
                if ( $map_embed ) : ?>
                    <div class="contact-map" style="margin-top: var(--spacing-xl); border-radius: var(--radius-xl); overflow: hidden; box-shadow: 0 5px 30px rgba(0,0,0,0.1);">
                        <?php echo wp_kses_post( $map_embed ); ?>
                    </div>
                <?php endif; ?>

            </article>

        <?php endwhile; ?>

    </div>
</main>

<?php
get_footer();
