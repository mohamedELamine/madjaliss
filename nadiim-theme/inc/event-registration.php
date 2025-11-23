<?php
/**
 * نظام التسجيل في الفعاليات
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * إنشاء جدول التسجيلات في قاعدة البيانات
 */
function nadiim_create_event_registrations_table() {
	global $wpdb;
	$table_name      = $wpdb->prefix . 'event_registrations';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		event_id bigint(20) NOT NULL,
		user_name varchar(255) NOT NULL,
		user_email varchar(255) NOT NULL,
		user_phone varchar(50) DEFAULT '',
		user_message text DEFAULT '',
		registration_date datetime NOT NULL,
		status varchar(20) DEFAULT 'pending',
		PRIMARY KEY  (id),
		KEY event_id (event_id),
		KEY user_email (user_email)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
add_action( 'after_switch_theme', 'nadiim_create_event_registrations_table' );

/**
 * معالجة طلب التسجيل في الفعالية عبر AJAX
 */
function nadiim_handle_event_registration() {
	// التحقق من nonce
	check_ajax_referer( 'event_registration_nonce', 'nonce' );

	// الحصول على البيانات
	$event_id    = isset( $_POST['event_id'] ) ? absint( $_POST['event_id'] ) : 0;
	$user_name   = isset( $_POST['user_name'] ) ? sanitize_text_field( $_POST['user_name'] ) : '';
	$user_email  = isset( $_POST['user_email'] ) ? sanitize_email( $_POST['user_email'] ) : '';
	$user_phone  = isset( $_POST['user_phone'] ) ? sanitize_text_field( $_POST['user_phone'] ) : '';
	$user_message = isset( $_POST['user_message'] ) ? sanitize_textarea_field( $_POST['user_message'] ) : '';

	// التحقق من البيانات
	if ( ! $event_id || ! $user_name || ! $user_email ) {
		wp_send_json_error( array( 'message' => 'يرجى ملء جميع الحقول المطلوبة' ) );
	}

	if ( ! is_email( $user_email ) ) {
		wp_send_json_error( array( 'message' => 'البريد الإلكتروني غير صحيح' ) );
	}

	// التحقق من أن الفعالية موجودة وقادمة
	$event_status = get_post_meta( $event_id, 'event_status', true );
	$event_date   = get_post_meta( $event_id, 'event_date', true );
	$event_time   = get_post_meta( $event_id, 'event_time', true );

	// حساب حالة الفعالية تلقائياً
	if ( $event_date && $event_time ) {
		$event_timestamp  = strtotime( $event_date . ' ' . $event_time );
		$current_timestamp = current_time( 'timestamp' );

		if ( $event_timestamp <= $current_timestamp ) {
			wp_send_json_error( array( 'message' => 'عذراً، هذه الفعالية قد انتهت أو بدأت بالفعل' ) );
		}
	}

	// التحقق من عدم التسجيل المسبق
	global $wpdb;
	$table_name = $wpdb->prefix . 'event_registrations';
	$existing   = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM $table_name WHERE event_id = %d AND user_email = %s",
		$event_id,
		$user_email
	) );

	if ( $existing ) {
		wp_send_json_error( array( 'message' => 'لقد سجّلت في هذه الفعالية مسبقاً' ) );
	}

	// إضافة التسجيل إلى قاعدة البيانات
	$inserted = $wpdb->insert(
		$table_name,
		array(
			'event_id'          => $event_id,
			'user_name'         => $user_name,
			'user_email'        => $user_email,
			'user_phone'        => $user_phone,
			'user_message'      => $user_message,
			'registration_date' => current_time( 'mysql' ),
			'status'            => 'confirmed',
		),
		array( '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	if ( ! $inserted ) {
		wp_send_json_error( array( 'message' => 'حدث خطأ أثناء التسجيل، يرجى المحاولة مرة أخرى' ) );
	}

	// إرسال إيميل تأكيد للمستخدم
	nadiim_send_registration_confirmation_email( $event_id, $user_name, $user_email );

	// إرسال إشعار للمسؤول (إذا كان مفعلاً)
	$admin_notification_enabled = get_theme_mod( 'event_registration_admin_notification', true );
	if ( $admin_notification_enabled ) {
		nadiim_send_admin_registration_notification( $event_id, $user_name, $user_email );
	}

	wp_send_json_success( array( 'message' => 'تم التسجيل بنجاح! سنرسل لك رسالة تأكيد على بريدك الإلكتروني' ) );
}
add_action( 'wp_ajax_event_registration', 'nadiim_handle_event_registration' );
add_action( 'wp_ajax_nopriv_event_registration', 'nadiim_handle_event_registration' );

/**
 * إرسال إيميل تأكيد التسجيل للمستخدم
 */
function nadiim_send_registration_confirmation_email( $event_id, $user_name, $user_email ) {
	$event_title = get_the_title( $event_id );
	$event_date  = get_post_meta( $event_id, 'event_date', true );
	$event_time  = get_post_meta( $event_id, 'event_time', true );
	$event_location = get_post_meta( $event_id, 'event_location', true );
	$event_link  = get_post_meta( $event_id, 'event_link', true );

	// تنسيق التاريخ
	$formatted_date = $event_date ? date_i18n( 'l، j F، Y', strtotime( $event_date ) ) : '';
	$formatted_time = $event_time ? date_i18n( 'g:i A', strtotime( $event_time ) ) : '';

	// الحصول على إعدادات البريد من Customizer
	$email_subject = get_theme_mod( 'event_registration_email_subject', 'تأكيد التسجيل في {event_title}' );
	$email_body    = get_theme_mod( 'event_registration_email_body',
		"مرحباً {user_name}،\n\nشكراً لتسجيلك في فعالية: {event_title}\n\nتفاصيل الفعالية:\n📅 التاريخ: {event_date}\n🕐 الوقت: {event_time}\n📍 المكان: {event_location}\n🔗 الرابط: {event_link}\n\nنتطلع لرؤيتك!\n\nمع أطيب التحيات،\nفريق نديم"
	);

	// استبدال المتغيرات
	$replacements = array(
		'{user_name}'    => $user_name,
		'{event_title}'  => $event_title,
		'{event_date}'   => $formatted_date,
		'{event_time}'   => $formatted_time,
		'{event_location}' => $event_location ?: 'سيتم الإعلان عنه لاحقاً',
		'{event_link}'   => $event_link ?: get_permalink( $event_id ),
	);

	$subject = str_replace( array_keys( $replacements ), array_values( $replacements ), $email_subject );
	$message = str_replace( array_keys( $replacements ), array_values( $replacements ), $email_body );

	// إرسال البريد
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	wp_mail( $user_email, $subject, $message, $headers );
}

/**
 * إرسال إشعار للمسؤول بالتسجيل الجديد
 */
function nadiim_send_admin_registration_notification( $event_id, $user_name, $user_email ) {
	$admin_email = get_theme_mod( 'event_registration_admin_email', get_option( 'admin_email' ) );
	$event_title = get_the_title( $event_id );

	$subject = 'تسجيل جديد في فعالية: ' . $event_title;
	$message = "تم تسجيل مستخدم جديد في الفعالية:\n\n";
	$message .= "اسم المستخدم: $user_name\n";
	$message .= "البريد الإلكتروني: $user_email\n";
	$message .= "الفعالية: $event_title\n";
	$message .= "رابط الفعالية: " . get_permalink( $event_id ) . "\n";

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	wp_mail( $admin_email, $subject, $message, $headers );
}

/**
 * الحصول على عدد المسجلين في فعالية
 */
function nadiim_get_event_registrations_count( $event_id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'event_registrations';

	return $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM $table_name WHERE event_id = %d",
		$event_id
	) );
}

/**
 * الحصول على جميع المسجلين في فعالية
 */
function nadiim_get_event_registrations( $event_id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'event_registrations';

	return $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM $table_name WHERE event_id = %d ORDER BY registration_date DESC",
		$event_id
	) );
}

/**
 * التحقق من تسجيل المستخدم في فعالية
 */
function nadiim_is_user_registered( $event_id, $user_email ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'event_registrations';

	$result = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM $table_name WHERE event_id = %d AND user_email = %s",
		$event_id,
		$user_email
	) );

	return ! empty( $result );
}
