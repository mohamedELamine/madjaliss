<?php
/**
 * Template Part: نموذج الاتصال
 *
 * يعرض نموذج الاتصال مع الخريطة حسب إعدادات Customizer
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// جلب الإعدادات من Customizer
$form_enabled = get_theme_mod( 'contact_enable_form', true );
$show_subject = get_theme_mod( 'contact_show_subject', true );
$show_phone = get_theme_mod( 'contact_show_phone', true );
$show_preferred_time = get_theme_mod( 'contact_show_preferred_time', false );
$consent_required = get_theme_mod( 'contact_consent_required', true );
$consent_text = get_theme_mod( 'contact_consent_text', __( 'أوافق على سياسة الخصوصية ومعالجة بياناتي', 'nadiim' ) );
$btn_text = get_theme_mod( 'contact_btn_text', __( 'إرسال الرسالة', 'nadiim' ) );

// إعدادات الخريطة
$map_enabled = get_theme_mod( 'contact_map_enable', true );
$show_address = get_theme_mod( 'contact_show_address', true );
$address_text = get_theme_mod( 'contact_address_text', __( 'الرياض، المملكة العربية السعودية', 'nadiim' ) );

// التخطيط
$layout = get_theme_mod( 'contact_layout', 'form-left' );
$layout_override = get_query_var( 'contact_form_layout_override', '' );
if ( $layout_override ) {
	$layout = $layout_override;
}

// إخفاء الخريطة من Shortcode
$hide_map = get_query_var( 'contact_form_hide_map', false );
if ( $hide_map ) {
	$map_enabled = false;
}

// reCAPTCHA
$recaptcha_enabled = get_theme_mod( 'contact_recaptcha_enable', false );
$recaptcha_site_key = get_theme_mod( 'contact_recaptcha_site_key', '' );

if ( ! $form_enabled ) {
	return;
}
?>

<div class="contact-form-section" data-layout="<?php echo esc_attr( $layout ); ?>">
	<div class="contact-form-container">

		<!-- رسائل الحالة -->
		<div class="contact-messages" style="display: none;">
			<div class="contact-message contact-success"></div>
			<div class="contact-message contact-error"></div>
		</div>

		<div class="contact-grid">

			<!-- النموذج -->
			<div class="contact-form-wrapper">
				<form id="nadiim-contact-form" class="nadiim-contact-form" method="post">
					<?php wp_nonce_field( 'nadiim_contact_form', 'contact_nonce' ); ?>

					<!-- الاسم -->
					<div class="form-field">
						<label for="contact_name">
							<?php _e( 'الاسم', 'nadiim' ); ?>
							<span class="required">*</span>
						</label>
						<input
							type="text"
							id="contact_name"
							name="contact_name"
							required
							aria-required="true"
						/>
					</div>

					<!-- البريد الإلكتروني -->
					<div class="form-field">
						<label for="contact_email">
							<?php _e( 'البريد الإلكتروني', 'nadiim' ); ?>
							<span class="required">*</span>
						</label>
						<input
							type="email"
							id="contact_email"
							name="contact_email"
							required
							aria-required="true"
						/>
					</div>

					<!-- الهاتف -->
					<?php if ( $show_phone ) : ?>
					<div class="form-field">
						<label for="contact_phone">
							<?php _e( 'الهاتف', 'nadiim' ); ?>
							<span class="optional"><?php _e( '(اختياري)', 'nadiim' ); ?></span>
						</label>
						<input
							type="tel"
							id="contact_phone"
							name="contact_phone"
						/>
					</div>
					<?php endif; ?>

					<!-- الموضوع -->
					<?php if ( $show_subject ) : ?>
					<div class="form-field">
						<label for="contact_subject">
							<?php _e( 'الموضوع', 'nadiim' ); ?>
							<span class="required">*</span>
						</label>
						<input
							type="text"
							id="contact_subject"
							name="contact_subject"
							required
							aria-required="true"
						/>
					</div>
					<?php endif; ?>

					<!-- الوقت المفضل -->
					<?php if ( $show_preferred_time ) : ?>
					<div class="form-field">
						<label for="contact_preferred_time">
							<?php _e( 'الوقت المفضل للتواصل', 'nadiim' ); ?>
							<span class="optional"><?php _e( '(اختياري)', 'nadiim' ); ?></span>
						</label>
						<select id="contact_preferred_time" name="contact_preferred_time">
							<option value=""><?php _e( '-- اختر --', 'nadiim' ); ?></option>
							<option value="morning"><?php _e( 'صباحاً (8 ص - 12 م)', 'nadiim' ); ?></option>
							<option value="afternoon"><?php _e( 'ظهراً (12 م - 4 م)', 'nadiim' ); ?></option>
							<option value="evening"><?php _e( 'مساءً (4 م - 8 م)', 'nadiim' ); ?></option>
							<option value="anytime"><?php _e( 'أي وقت', 'nadiim' ); ?></option>
						</select>
					</div>
					<?php endif; ?>

					<!-- الرسالة -->
					<div class="form-field">
						<label for="contact_message">
							<?php _e( 'الرسالة', 'nadiim' ); ?>
							<span class="required">*</span>
						</label>
						<textarea
							id="contact_message"
							name="contact_message"
							rows="6"
							required
							aria-required="true"
						></textarea>
					</div>

					<!-- موافقة الخصوصية -->
					<?php if ( $consent_required ) : ?>
					<div class="form-field form-field-checkbox">
						<label>
							<input
								type="checkbox"
								id="contact_consent"
								name="contact_consent"
								required
								aria-required="true"
							/>
							<span><?php echo esc_html( $consent_text ); ?></span>
							<span class="required">*</span>
						</label>
					</div>
					<?php endif; ?>

					<!-- reCAPTCHA -->
					<?php if ( $recaptcha_enabled && ! empty( $recaptcha_site_key ) ) : ?>
					<div class="form-field form-field-recaptcha">
						<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_site_key ); ?>"></div>
					</div>
					<?php endif; ?>

					<!-- زر الإرسال -->
					<div class="form-field form-field-submit">
						<button type="submit" class="btn btn-primary btn-submit">
							<span class="btn-text"><?php echo esc_html( $btn_text ); ?></span>
							<span class="btn-loading" style="display: none;">
								<svg class="spinner" viewBox="0 0 50 50">
									<circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
								</svg>
								<?php _e( 'جارٍ الإرسال...', 'nadiim' ); ?>
							</span>
						</button>
					</div>

				</form>
			</div>

			<!-- الخريطة ومعلومات الاتصال -->
			<?php if ( $map_enabled || $show_address ) : ?>
			<div class="contact-info-wrapper">

					<!-- معلومات الاتصال -->
				<?php if ( $show_address ) : ?>
				<div class="contact-info-box">
					<h3><?php _e( 'معلومات الاتصال', 'nadiim' ); ?></h3>

					<!-- العنوان -->
					<?php if ( ! empty( $address_text ) ) : ?>
					<div class="contact-info-item">
						<svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
							<circle cx="12" cy="10" r="3"></circle>
						</svg>
						<div class="contact-info-text">
							<?php echo wp_kses_post( nl2br( $address_text ) ); ?>
						</div>
					</div>
					<?php endif; ?>

					<!-- رقم الهاتف -->
					<?php
					$phone_number = get_theme_mod( 'contact_phone_number', '' );
					if ( ! empty( $phone_number ) ) :
					?>
					<div class="contact-info-item">
						<svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
						</svg>
						<div class="contact-info-text">
							<a href="tel:<?php echo esc_attr( $phone_number ); ?>"><?php echo esc_html( $phone_number ); ?></a>
						</div>
					</div>
					<?php endif; ?>

					<!-- البريد الإلكتروني -->
					<?php
					$email_display = get_theme_mod( 'contact_email_display', get_option( 'admin_email' ) );
					if ( ! empty( $email_display ) ) :
					?>
					<div class="contact-info-item">
						<svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
							<polyline points="22,6 12,13 2,6"></polyline>
						</svg>
						<div class="contact-info-text">
							<a href="mailto:<?php echo esc_attr( $email_display ); ?>"><?php echo esc_html( $email_display ); ?></a>
						</div>
					</div>
					<?php endif; ?>

					<!-- مواقع التواصل الاجتماعي -->
					<?php
					$social_links = array(
						'facebook'  => array( 'url' => get_theme_mod( 'contact_facebook', '' ), 'icon' => 'facebook', 'label' => 'فيسبوك' ),
						'twitter'   => array( 'url' => get_theme_mod( 'contact_twitter', '' ), 'icon' => 'twitter', 'label' => 'تويتر' ),
						'instagram' => array( 'url' => get_theme_mod( 'contact_instagram', '' ), 'icon' => 'instagram', 'label' => 'إنستغرام' ),
						'linkedin'  => array( 'url' => get_theme_mod( 'contact_linkedin', '' ), 'icon' => 'linkedin', 'label' => 'لينكد إن' ),
						'youtube'   => array( 'url' => get_theme_mod( 'contact_youtube', '' ), 'icon' => 'youtube', 'label' => 'يوتيوب' ),
					);

					$has_social = false;
					foreach ( $social_links as $social ) {
						if ( ! empty( $social['url'] ) ) {
							$has_social = true;
							break;
						}
					}

					if ( $has_social ) :
					?>
					<div class="contact-social-links">
						<h4><?php _e( 'تابعنا على', 'nadiim' ); ?></h4>
						<div class="social-icons">
							<?php foreach ( $social_links as $key => $social ) : ?>
								<?php if ( ! empty( $social['url'] ) ) : ?>
								<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener" class="social-icon social-<?php echo esc_attr( $key ); ?>" title="<?php echo esc_attr( $social['label'] ); ?>">
									<?php echo nadiim_get_social_icon( $key ); ?>
								</a>
								<?php endif; ?>
							<?php endforeach; ?>

							<?php
							// واتساب
							$whatsapp = get_theme_mod( 'contact_whatsapp', '' );
							if ( ! empty( $whatsapp ) ) :
							?>
							<a href="https://wa.me/<?php echo esc_attr( $whatsapp ); ?>" target="_blank" rel="noopener" class="social-icon social-whatsapp" title="واتساب">
								<?php echo nadiim_get_social_icon( 'whatsapp' ); ?>
							</a>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- الخريطة -->
				<?php if ( $map_enabled ) : ?>
				<div class="contact-map-wrapper">
					<?php
					$map_embed = get_theme_mod( 'contact_map_embed', '' );
					if ( ! empty( $map_embed ) ) :
						// استخدام iframe من Google Maps
						echo wp_kses_post( $map_embed );
					else :
						// استخدام Leaflet
						$map_lat = get_theme_mod( 'contact_map_lat', '24.7136' );
						$map_lng = get_theme_mod( 'contact_map_lng', '46.6753' );
						$map_zoom = get_theme_mod( 'contact_map_zoom', '13' );
						?>
						<div id="contact-map"
						     data-lat="<?php echo esc_attr( $map_lat ); ?>"
						     data-lng="<?php echo esc_attr( $map_lng ); ?>"
						     data-zoom="<?php echo esc_attr( $map_zoom ); ?>"
						     style="height: 400px; border-radius: 12px; overflow: hidden;">
						</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

			</div>
			<?php endif; ?>

		</div>

	</div>
</div>

<?php
// تحميل reCAPTCHA إذا كان مفعلاً
if ( $recaptcha_enabled && ! empty( $recaptcha_site_key ) ) {
	wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true );
}

// تحميل Leaflet إذا كانت الخريطة مفعلة وليس هناك iframe
if ( $map_enabled && empty( get_theme_mod( 'contact_map_embed', '' ) ) ) {
	wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
	wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
}
