<?php
/**
 * معالج نموذج الاتصال
 *
 * يتعامل مع إرسال النموذج عبر AJAX و POST
 * يقوم بالتحقق، التحقق من reCAPTCHA، حفظ البيانات، وإرسال البريد
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * معالج AJAX لنموذج الاتصال
 */
function nadiim_handle_contact_form_ajax() {
	// معالجة النموذج
	$result = nadiim_process_contact_form();

	// إرسال النتيجة كـ JSON
	if ( $result['success'] ) {
		wp_send_json_success( $result );
	} else {
		wp_send_json_error( $result );
	}
}
add_action( 'wp_ajax_nadiim_contact_form', 'nadiim_handle_contact_form_ajax' );
add_action( 'wp_ajax_nopriv_nadiim_contact_form', 'nadiim_handle_contact_form_ajax' );

/**
 * دالة معالجة النموذج الرئيسية
 */
function nadiim_process_contact_form() {
	// التحقق من nonce
	if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( $_POST['contact_nonce'], 'nadiim_contact_form' ) ) {
		return array(
			'success' => false,
			'message' => __( 'فشل التحقق الأمني. يُرجى تحديث الصفحة والمحاولة مرة أخرى.', 'nadiim' ),
		);
	}

	// جلب الإعدادات
	$recaptcha_enabled = get_theme_mod( 'contact_recaptcha_enable', false );
	$save_to_db = get_theme_mod( 'contact_save_to_inquiries', true );
	$auto_reply = get_theme_mod( 'contact_auto_reply', false );

	// التحقق من reCAPTCHA
	if ( $recaptcha_enabled ) {
		$recaptcha_valid = nadiim_verify_recaptcha();
		if ( ! $recaptcha_valid['success'] ) {
			return $recaptcha_valid;
		}
	}

	// جمع وتنظيف البيانات
	$data = array(
		'name'             => isset( $_POST['contact_name'] ) ? sanitize_text_field( $_POST['contact_name'] ) : '',
		'email'            => isset( $_POST['contact_email'] ) ? sanitize_email( $_POST['contact_email'] ) : '',
		'phone'            => isset( $_POST['contact_phone'] ) ? sanitize_text_field( $_POST['contact_phone'] ) : '',
		'subject'          => isset( $_POST['contact_subject'] ) ? sanitize_text_field( $_POST['contact_subject'] ) : '',
		'message'          => isset( $_POST['contact_message'] ) ? sanitize_textarea_field( $_POST['contact_message'] ) : '',
		'preferred_time'   => isset( $_POST['contact_preferred_time'] ) ? sanitize_text_field( $_POST['contact_preferred_time'] ) : '',
		'consent'          => isset( $_POST['contact_consent'] ) ? true : false,
	);

	// التحقق من الحقول المطلوبة
	$validation = nadiim_validate_contact_data( $data );
	if ( ! $validation['valid'] ) {
		return array(
			'success' => false,
			'message' => $validation['message'],
		);
	}

	// حفظ في قاعدة البيانات
	if ( $save_to_db ) {
		$inquiry_id = nadiim_save_inquiry_to_db( $data );
		if ( ! $inquiry_id ) {
			return array(
				'success' => false,
				'message' => __( 'حدث خطأ أثناء حفظ البيانات. يُرجى المحاولة مرة أخرى.', 'nadiim' ),
			);
		}
	}

	// إرسال البريد للإداريين
	$email_sent = nadiim_send_contact_notification( $data );

	// إرسال رد تلقائي للمُرسِل
	if ( $auto_reply ) {
		nadiim_send_auto_reply( $data );
	}

	// رسالة النجاح
	$success_message = get_theme_mod( 'contact_success_message', __( 'شكراً لتواصلك معنا! سنرد عليك في أقرب وقت.', 'nadiim' ) );

	return array(
		'success' => true,
		'message' => $success_message,
	);
}

/**
 * التحقق من reCAPTCHA
 */
function nadiim_verify_recaptcha() {
	$secret_key = get_theme_mod( 'contact_recaptcha_secret_key', '' );

	if ( empty( $secret_key ) ) {
		return array(
			'success' => false,
			'message' => __( 'خطأ في إعدادات reCAPTCHA.', 'nadiim' ),
		);
	}

	$recaptcha_response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( $_POST['g-recaptcha-response'] ) : '';

	if ( empty( $recaptcha_response ) ) {
		return array(
			'success' => false,
			'message' => __( 'يُرجى التحقق من أنك لست روبوت.', 'nadiim' ),
		);
	}

	// إرسال طلب للتحقق من Google
	$verify_url = 'https://www.google.com/recaptcha/api/siteverify';
	$response = wp_remote_post( $verify_url, array(
		'body' => array(
			'secret'   => $secret_key,
			'response' => $recaptcha_response,
			'remoteip' => nadiim_get_user_ip(),
		),
	) );

	if ( is_wp_error( $response ) ) {
		return array(
			'success' => false,
			'message' => __( 'فشل التحقق من reCAPTCHA. يُرجى المحاولة مرة أخرى.', 'nadiim' ),
		);
	}

	$response_body = wp_remote_retrieve_body( $response );
	$result = json_decode( $response_body );

	if ( ! $result->success ) {
		return array(
			'success' => false,
			'message' => __( 'فشل التحقق من reCAPTCHA. يُرجى المحاولة مرة أخرى.', 'nadiim' ),
		);
	}

	return array( 'success' => true );
}

/**
 * التحقق من صحة بيانات النموذج
 */
function nadiim_validate_contact_data( $data ) {
	// التحقق من الاسم
	if ( empty( $data['name'] ) ) {
		return array(
			'valid'   => false,
			'message' => __( 'يُرجى إدخال اسمك.', 'nadiim' ),
		);
	}

	// التحقق من البريد الإلكتروني
	if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
		return array(
			'valid'   => false,
			'message' => __( 'يُرجى إدخال بريد إلكتروني صحيح.', 'nadiim' ),
		);
	}

	// التحقق من الرسالة
	if ( empty( $data['message'] ) ) {
		return array(
			'valid'   => false,
			'message' => __( 'يُرجى إدخال رسالتك.', 'nadiim' ),
		);
	}

	// التحقق من موافقة الخصوصية
	$consent_required = get_theme_mod( 'contact_consent_required', true );
	if ( $consent_required && ! $data['consent'] ) {
		return array(
			'valid'   => false,
			'message' => __( 'يُرجى الموافقة على سياسة الخصوصية.', 'nadiim' ),
		);
	}

	return array( 'valid' => true );
}

/**
 * حفظ الاستفسار في قاعدة البيانات
 */
function nadiim_save_inquiry_to_db( $data ) {
	// إنشاء عنوان للمنشور
	$post_title = ! empty( $data['subject'] )
		? sprintf( '%s - %s', $data['name'], $data['subject'] )
		: sprintf( '%s - %s', $data['name'], __( 'استفسار عام', 'nadiim' ) );

	// إنشاء منشور في CPT inquiries
	$inquiry_id = wp_insert_post( array(
		'post_title'   => $post_title,
		'post_content' => $data['message'],
		'post_type'    => 'inquiries',
		'post_status'  => 'publish',
		'post_author'  => 1,
	) );

	if ( ! $inquiry_id || is_wp_error( $inquiry_id ) ) {
		return false;
	}

	// حفظ البيانات الإضافية كـ meta
	update_post_meta( $inquiry_id, '_inquiry_email', $data['email'] );
	update_post_meta( $inquiry_id, '_inquiry_phone', $data['phone'] );
	update_post_meta( $inquiry_id, '_inquiry_subject', $data['subject'] );
	update_post_meta( $inquiry_id, '_inquiry_preferred_time', $data['preferred_time'] );
	update_post_meta( $inquiry_id, '_inquiry_status', 'new' );
	update_post_meta( $inquiry_id, '_inquiry_ip', nadiim_get_user_ip() );
	update_post_meta( $inquiry_id, '_inquiry_user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '' );
	update_post_meta( $inquiry_id, '_inquiry_referer', isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( $_SERVER['HTTP_REFERER'] ) : '' );

	return $inquiry_id;
}

/**
 * إرسال إشعار بريد إلكتروني للإداريين
 */
function nadiim_send_contact_notification( $data ) {
	// جلب عناوين البريد المستقبِلة
	$receivers = get_theme_mod( 'contact_receiver_emails', get_option( 'admin_email' ) );
	$receivers = nadiim_parse_email_list( $receivers );

	if ( empty( $receivers ) ) {
		// Log error
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'Contact Form: No receiver emails configured' );
		}
		return false;
	}

	// جلب قوالب البريد
	$subject_template = get_theme_mod( 'contact_email_subject_template', '[{site}] رسالة جديدة من {name}' );
	$body_template = get_theme_mod( 'contact_email_body_template', "رسالة جديدة من موقع {site}\n\nالاسم: {name}\nالبريد: {email}\nالهاتف: {phone}\n\nالموضوع: {subject}\n\nالرسالة:\n{message}" );

	// استبدال المتغيرات
	$replacements = array(
		'{site}'    => get_bloginfo( 'name' ),
		'{name}'    => $data['name'],
		'{email}'   => $data['email'],
		'{phone}'   => ! empty( $data['phone'] ) ? $data['phone'] : 'غير محدد',
		'{subject}' => ! empty( $data['subject'] ) ? $data['subject'] : 'بدون موضوع',
		'{message}' => $data['message'],
	);

	$subject = str_replace( array_keys( $replacements ), array_values( $replacements ), $subject_template );
	$body = str_replace( array_keys( $replacements ), array_values( $replacements ), $body_template );

	// إعداد رؤوس البريد
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
	);

	// تعيين From باستخدام فلاتر WordPress
	add_filter( 'wp_mail_from', function() {
		return get_option( 'admin_email' );
	} );

	add_filter( 'wp_mail_from_name', function() {
		return get_bloginfo( 'name' );
	} );

	// إضافة معالج لأخطاء wp_mail
	$mail_error = '';
	add_action( 'wp_mail_failed', function( $wp_error ) use ( &$mail_error ) {
		$mail_error = $wp_error->get_error_message();
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'Contact Form: wp_mail error: ' . $mail_error );
		}
	} );

	// إرسال البريد
	$sent = wp_mail( $receivers, $subject, $body, $headers );

	// إزالة الفلاتر بعد الإرسال
	remove_all_filters( 'wp_mail_from' );
	remove_all_filters( 'wp_mail_from_name' );

	// Log result
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		if ( $sent ) {
			error_log( 'Contact Form: Email sent successfully to ' . implode( ', ', $receivers ) );
			error_log( 'Contact Form: From: ' . get_option( 'admin_email' ) . ' (' . get_bloginfo( 'name' ) . ')' );
			error_log( 'Contact Form: Subject: ' . $subject );
		} else {
			error_log( 'Contact Form: Failed to send email. Check wp_mail configuration.' );
			error_log( 'Contact Form: Receivers: ' . implode( ', ', $receivers ) );
			error_log( 'Contact Form: Subject: ' . $subject );
			if ( ! empty( $mail_error ) ) {
				error_log( 'Contact Form: Error details: ' . $mail_error );
			}
		}
	}

	return $sent;
}

/**
 * إرسال رد تلقائي للمُرسِل
 */
function nadiim_send_auto_reply( $data ) {
	$reply_text = get_theme_mod( 'contact_auto_reply_text', __( 'شكراً لتواصلك معنا! تم استلام رسالتك وسنرد عليك في أقرب وقت ممكن.', 'nadiim' ) );

	$subject = sprintf( __( 'شكراً لتواصلك مع %s', 'nadiim' ), get_bloginfo( 'name' ) );
	$body = sprintf(
		"%s،\n\n%s\n\n---\n%s",
		$data['name'],
		$reply_text,
		get_bloginfo( 'name' )
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
	);

	wp_mail( $data['email'], $subject, $body, $headers );
}

/**
 * تحليل قائمة عناوين البريد
 */
function nadiim_parse_email_list( $emails_string ) {
	// فصل بالفواصل أو أسطر جديدة
	$emails = preg_split( '/[\n,;]+/', $emails_string );
	$emails = array_map( 'trim', $emails );
	$emails = array_filter( $emails, 'is_email' );

	return $emails;
}

/**
 * الحصول على IP المستخدم بشكل آمن
 */
function nadiim_get_user_ip() {
	$ip = '';

	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED'];
	} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_FORWARDED_FOR'];
	} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
		$ip = $_SERVER['HTTP_FORWARDED'];
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	// تنظيف IP
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );

	return $ip ? $ip : '0.0.0.0';
}

/**
 * Shortcode لعرض نموذج الاتصال
 */
function nadiim_contact_form_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'map'    => '1',
		'layout' => '',
	), $atts, 'nadiim_contact_form' );

	// حفظ الإعدادات مؤقتاً
	if ( ! empty( $atts['layout'] ) ) {
		set_query_var( 'contact_form_layout_override', $atts['layout'] );
	}
	if ( $atts['map'] === '0' ) {
		set_query_var( 'contact_form_hide_map', true );
	}

	ob_start();
	get_template_part( 'template-parts/contact/form', 'contact' );
	return ob_get_clean();
}
add_shortcode( 'nadiim_contact_form', 'nadiim_contact_form_shortcode' );
