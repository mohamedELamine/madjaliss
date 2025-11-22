<?php
/**
 * قسم Newsletter - النشرة البريدية (تصميم عصري محدث)
 *
 * نموذج الاشتراك في النشرة البريدية مع تصميم جذاب
 *
 * @package Nadiim
 * @since 2.1.0
 */

// إعدادات Newsletter من Customizer
$newsletter_title    = get_theme_mod( 'home_newsletter_title', 'انضم إلى نشرتنا البريدية' );
$newsletter_desc     = get_theme_mod( 'home_newsletter_desc', 'احصل على آخر الإصدارات والأخبار والفعاليات مباشرة في بريدك الإلكتروني' );
$newsletter_provider = get_theme_mod( 'home_newsletter_provider', 'mailchimp' );
?>

<section class="newsletter-section-modern section-padding">

	<!-- خلفية متحركة -->
	<div class="newsletter-bg-pattern" aria-hidden="true">
		<div class="pattern-wave pattern-wave-1"></div>
		<div class="pattern-wave pattern-wave-2"></div>
		<div class="pattern-wave pattern-wave-3"></div>
	</div>

	<div class="section-container newsletter-container">

		<div class="newsletter-content" data-aos="fade-up">

			<!-- أيقونة البريد -->
			<div class="newsletter-icon">
				<svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
					<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
				</svg>
			</div>

			<!-- العنوان -->
			<h2 class="newsletter-title"><?php echo esc_html( $newsletter_title ); ?></h2>

			<!-- الوصف -->
			<p class="newsletter-description"><?php echo esc_html( $newsletter_desc ); ?></p>

			<!-- النموذج -->
			<form class="newsletter-form-modern" method="post" action="">
				<div class="newsletter-form-group-modern">
					<div class="input-wrapper">
						<svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
						</svg>
						<input
							type="email"
							name="newsletter_email"
							class="newsletter-input-modern"
							placeholder="أدخل بريدك الإلكتروني"
							required
							aria-label="البريد الإلكتروني"
						/>
					</div>
					<button type="submit" class="newsletter-submit-modern">
						<span>اشترك الآن</span>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
						</svg>
					</button>
				</div>

				<?php wp_nonce_field( 'nadiim_newsletter_subscribe', 'newsletter_nonce' ); ?>

				<!-- رسالة الخصوصية -->
				<div class="newsletter-privacy-modern">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
						<path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
					</svg>
					<small>نحترم خصوصيتك. لن نشارك بياناتك مع أي جهة خارجية.</small>
				</div>
			</form>

			<!-- رسائل النجاح/الخطأ -->
			<div class="newsletter-message-modern" style="display: none;"></div>

			<!-- إحصائيات (اختياري) -->
			<?php
			global $wpdb;
			$table_name = $wpdb->prefix . 'nadiim_newsletter_subscribers';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) === $table_name ) {
				$subscribers_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'active'" );
				if ( $subscribers_count > 0 ) :
			?>
				<div class="newsletter-stats" data-aos="fade-up" data-aos-delay="100">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
						<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
					</svg>
					<span>انضم إلى <strong><?php echo number_format_i18n( $subscribers_count ); ?></strong> مشترك في نشرتنا</span>
				</div>
			<?php
				endif;
			}
			?>

		</div><!-- .newsletter-content -->

	</div><!-- .section-container -->

</section><!-- .newsletter-section-modern -->

<!-- CSS مخصص للنشرة البريدية -->
<style>
/* قسم النشرة البريدية العصري */
.newsletter-section-modern {
	position: relative;
	padding: 80px 0;
	background: linear-gradient(135deg, #339063 0%, #2a7a52 100%);
	overflow: hidden;
	color: #ffffff;
}

/* الخلفية المتحركة */
.newsletter-bg-pattern {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	overflow: hidden;
	opacity: 0.1;
	z-index: 0;
}

.pattern-wave {
	position: absolute;
	width: 200%;
	height: 200%;
	background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
	border-radius: 45%;
	animation: wave 25s ease-in-out infinite;
}

.pattern-wave-1 {
	top: -100%;
	left: -50%;
	animation-delay: 0s;
}

.pattern-wave-2 {
	bottom: -100%;
	right: -50%;
	animation-delay: 8s;
}

.pattern-wave-3 {
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	animation-delay: 16s;
}

@keyframes wave {
	0%, 100% {
		transform: rotate(0deg) scale(1);
	}
	50% {
		transform: rotate(180deg) scale(1.2);
	}
}

.newsletter-content {
	position: relative;
	z-index: 2;
	max-width: 700px;
	margin: 0 auto;
	text-align: center;
}

/* أيقونة البريد */
.newsletter-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 80px;
	height: 80px;
	background: rgba(255, 255, 255, 0.15);
	border: 3px solid rgba(255, 255, 255, 0.3);
	border-radius: 50%;
	margin-bottom: 24px;
	animation: pulse 2s ease-in-out infinite;
}

.newsletter-icon svg {
	width: 40px;
	height: 40px;
	color: #ffffff;
}

@keyframes pulse {
	0%, 100% {
		transform: scale(1);
		box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
	}
	50% {
		transform: scale(1.05);
		box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
	}
}

.newsletter-title {
	font-size: clamp(28px, 4vw, 42px);
	font-weight: 800;
	margin: 0 0 16px;
	line-height: 1.2;
	color: #ffffff;
}

.newsletter-description {
	font-size: clamp(16px, 2vw, 19px);
	line-height: 1.6;
	margin: 0 0 40px;
	color: rgba(255, 255, 255, 0.95);
}

/* النموذج العصري */
.newsletter-form-modern {
	margin-bottom: 24px;
}

.newsletter-form-group-modern {
	display: flex;
	gap: 12px;
	max-width: 600px;
	margin: 0 auto 16px;
}

.input-wrapper {
	position: relative;
	flex: 1;
}

.input-icon {
	position: absolute;
	right: 16px;
	top: 50%;
	transform: translateY(-50%);
	color: rgba(51, 144, 99, 0.5);
	pointer-events: none;
}

.newsletter-input-modern {
	width: 100%;
	height: 56px;
	padding: 0 50px 0 20px;
	border: 3px solid rgba(255, 255, 255, 0.2);
	border-radius: 50px;
	font-size: 16px;
	background: rgba(255, 255, 255, 0.95);
	color: #1C2D27;
	outline: none;
	transition: all 0.3s ease;
	direction: rtl;
	text-align: right;
}

.newsletter-input-modern::placeholder {
	color: rgba(28, 45, 39, 0.5);
}

.newsletter-input-modern:focus {
	background: #ffffff;
	border-color: rgba(255, 255, 255, 0.5);
	box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
}

.newsletter-submit-modern {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 0 32px;
	height: 56px;
	background: #ffffff;
	color: var(--color-primary, #339063);
	border: none;
	border-radius: 50px;
	font-size: 16px;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.3s ease;
	white-space: nowrap;
}

.newsletter-submit-modern svg {
	width: 20px;
	height: 20px;
	transition: transform 0.3s ease;
}

.newsletter-submit-modern:hover {
	background: rgba(255, 255, 255, 0.9);
	transform: translateY(-2px);
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.newsletter-submit-modern:hover svg {
	transform: translateX(-4px);
}

[dir="rtl"] .newsletter-submit-modern:hover svg {
	transform: translateX(4px);
}

.newsletter-submit-modern:active {
	transform: translateY(0);
}

/* رسالة الخصوصية */
.newsletter-privacy-modern {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	color: rgba(255, 255, 255, 0.8);
	font-size: 13px;
}

.newsletter-privacy-modern svg {
	width: 16px;
	height: 16px;
	flex-shrink: 0;
}

/* رسائل النجاح/الخطأ */
.newsletter-message-modern {
	padding: 16px 24px;
	border-radius: 12px;
	font-size: 15px;
	font-weight: 600;
	margin-top: 20px;
	animation: slideDown 0.3s ease;
}

.newsletter-message-modern.success {
	background: rgba(16, 185, 129, 0.2);
	border: 2px solid rgba(16, 185, 129, 0.4);
	color: #ffffff;
}

.newsletter-message-modern.error {
	background: rgba(239, 68, 68, 0.2);
	border: 2px solid rgba(239, 68, 68, 0.4);
	color: #ffffff;
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* إحصائيات المشتركين */
.newsletter-stats {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	margin-top: 32px;
	padding: 16px 24px;
	background: rgba(255, 255, 255, 0.1);
	border: 2px solid rgba(255, 255, 255, 0.2);
	border-radius: 50px;
	font-size: 15px;
	color: rgba(255, 255, 255, 0.95);
}

.newsletter-stats svg {
	width: 20px;
	height: 20px;
}

.newsletter-stats strong {
	color: #ffffff;
	font-weight: 700;
}

/* Responsive */
@media (max-width: 768px) {
	.newsletter-section-modern {
		padding: 60px 0;
	}

	.newsletter-form-group-modern {
		flex-direction: column;
	}

	.newsletter-input-modern,
	.newsletter-submit-modern {
		width: 100%;
	}

	.newsletter-icon {
		width: 64px;
		height: 64px;
	}

	.newsletter-icon svg {
		width: 32px;
		height: 32px;
	}
}

@media (max-width: 480px) {
	.newsletter-section-modern {
		padding: 50px 0;
	}

	.newsletter-input-modern,
	.newsletter-submit-modern {
		height: 48px;
		font-size: 14px;
	}

	.newsletter-stats {
		font-size: 13px;
		padding: 12px 16px;
	}
}

/* تقليل الحركة */
@media (prefers-reduced-motion: reduce) {
	.newsletter-icon,
	.pattern-wave,
	.newsletter-submit-modern {
		animation: none;
		transition: none;
	}

	.newsletter-submit-modern:hover {
		transform: none;
	}
}
</style>
