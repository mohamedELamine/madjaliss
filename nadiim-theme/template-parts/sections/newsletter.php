<?php
/**
 * قسم Newsletter - النشرة البريدية
 *
 * CTA للاشتراك في النشرة البريدية
 * نموذج بسيط: حقل email + زر submit (ارتفاع 48px)
 *
 * @package Nadiim
 * @since 2.0.0
 */

// إعدادات Newsletter من Customizer
$newsletter_title    = get_theme_mod( 'home_newsletter_title', 'اشترك في نشرتنا البريدية' );
$newsletter_desc     = get_theme_mod( 'home_newsletter_desc', 'تلقَّ آخر الأخبار والإصدارات والفعاليات مباشرة في بريدك' );
$newsletter_provider = get_theme_mod( 'home_newsletter_provider', 'mailchimp' ); // mailchimp/none
?>

<section class="newsletter-section section-padding section-primary-bg">
	<div class="section-container newsletter-container">
		<div class="newsletter-content">
			<h2 class="newsletter-title"><?php echo esc_html( $newsletter_title ); ?></h2>
			<p class="newsletter-description"><?php echo esc_html( $newsletter_desc ); ?></p>

			<form class="newsletter-form" method="post" action="">
				<div class="newsletter-form-group">
					<input
						type="email"
						name="newsletter_email"
						class="newsletter-input"
						placeholder="بريدك الإلكتروني"
						required
						aria-label="البريد الإلكتروني"
					/>
					<button type="submit" class="newsletter-submit-btn">
						اشترك الآن
					</button>
				</div>

				<?php wp_nonce_field( 'nadiim_newsletter_subscribe', 'newsletter_nonce' ); ?>

				<div class="newsletter-privacy">
					<small>
						نحترم خصوصيتك. لن نشارك بريدك مع أي طرف ثالث.
					</small>
				</div>
			</form>
		</div>

		<!-- رسائل النجاح/الخطأ -->
		<div class="newsletter-message" style="display: none;"></div>
	</div>
</section>
