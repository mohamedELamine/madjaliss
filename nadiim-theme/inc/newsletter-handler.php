<?php
/**
 * Newsletter Handler - معالج النشرة البريدية
 *
 * يوفر نظام كامل للاشتراك في النشرة البريدية مع:
 * - AJAX handling
 * - التحقق من البريد الإلكتروني
 * - منع التكرار
 * - تخزين في قاعدة البيانات
 * - دعم MailChimp (اختياري)
 *
 * @package Nadiim
 * @since 2.1.0
 */

// منع الوصول المباشر
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تهيئة جدول المشتركين في قاعدة البيانات
 */
function nadiim_create_newsletter_table() {
	global $wpdb;
	$table_name      = $wpdb->prefix . 'nadiim_newsletter_subscribers';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		email varchar(255) NOT NULL,
		status varchar(20) DEFAULT 'active',
		subscribed_at datetime DEFAULT CURRENT_TIMESTAMP,
		ip_address varchar(100) DEFAULT '',
		user_agent text DEFAULT '',
		PRIMARY KEY  (id),
		UNIQUE KEY email (email),
		KEY status (status),
		KEY subscribed_at (subscribed_at)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
add_action( 'after_switch_theme', 'nadiim_create_newsletter_table' );
add_action( 'admin_init', 'nadiim_create_newsletter_table' );

/**
 * التحقق من وجود الجدول وإنشاؤه عند الحاجة
 */
function nadiim_ensure_newsletter_table_exists() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'nadiim_newsletter_subscribers';

	// التحقق من وجود الجدول
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {
		nadiim_create_newsletter_table();
	}
}

/**
 * AJAX Handler للاشتراك في النشرة البريدية
 */
function nadiim_subscribe_newsletter() {
	// التأكد من وجود جدول المشتركين
	nadiim_ensure_newsletter_table_exists();

	// التحقق من nonce
	if ( ! isset( $_POST['newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['newsletter_nonce'], 'nadiim_newsletter_subscribe' ) ) {
		wp_send_json_error( array(
			'message' => 'خطأ في التحقق من الأمان. يرجى تحديث الصفحة والمحاولة مرة أخرى.',
		) );
	}

	// التحقق من البريد الإلكتروني
	if ( empty( $_POST['email'] ) ) {
		wp_send_json_error( array(
			'message' => 'يرجى إدخال بريدك الإلكتروني.',
		) );
	}

	$email = sanitize_email( wp_unslash( $_POST['email'] ) );

	// التحقق من صحة البريد الإلكتروني
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array(
			'message' => 'البريد الإلكتروني غير صحيح. يرجى التحقق والمحاولة مرة أخرى.',
		) );
	}

	// التحقق من عدم وجود البريد مسبقاً
	global $wpdb;
	$table_name = $wpdb->prefix . 'nadiim_newsletter_subscribers';

	$existing = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id FROM $table_name WHERE email = %s",
			$email
		)
	);

	if ( $existing ) {
		wp_send_json_error( array(
			'message' => 'هذا البريد الإلكتروني مسجل مسبقاً في نشرتنا البريدية.',
		) );
	}

	// الحصول على IP والـ User Agent
	$ip_address = nadiim_get_user_ip();
	$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 255 ) : '';

	// إضافة المشترك إلى قاعدة البيانات
	$inserted = $wpdb->insert(
		$table_name,
		array(
			'email'      => $email,
			'status'     => 'active',
			'ip_address' => $ip_address,
			'user_agent' => $user_agent,
		),
		array( '%s', '%s', '%s', '%s' )
	);

	if ( $inserted === false ) {
		wp_send_json_error( array(
			'message' => 'حدث خطأ أثناء التسجيل. يرجى المحاولة لاحقاً.',
		) );
	}

	// إرسال بريد تأكيد (اختياري)
	$send_confirmation = get_theme_mod( 'newsletter_send_confirmation', true );
	if ( $send_confirmation ) {
		nadiim_send_newsletter_confirmation( $email );
	}

	// الاشتراك في MailChimp (إن كان مفعلاً)
	$mailchimp_enabled = get_theme_mod( 'newsletter_mailchimp_enabled', false );
	if ( $mailchimp_enabled ) {
		nadiim_subscribe_to_mailchimp( $email );
	}

	// إشعار المدير (اختياري)
	$notify_admin = get_theme_mod( 'newsletter_notify_admin', false );
	if ( $notify_admin ) {
		nadiim_notify_admin_new_subscriber( $email );
	}

	// إرسال استجابة ناجحة
	wp_send_json_success( array(
		'message' => 'تم الاشتراك بنجاح! شكراً لانضمامك إلى نشرتنا البريدية.',
	) );
}
add_action( 'wp_ajax_nadiim_subscribe_newsletter', 'nadiim_subscribe_newsletter' );
add_action( 'wp_ajax_nopriv_nadiim_subscribe_newsletter', 'nadiim_subscribe_newsletter' );

/**
 * الحصول على IP المستخدم
 */
if ( ! function_exists( 'nadiim_get_user_ip' ) ) {
	function nadiim_get_user_ip() {
		$ip = '';

		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		// تنظيف IP (أخذ أول IP في حال وجود قائمة)
		if ( strpos( $ip, ',' ) !== false ) {
			$ips = explode( ',', $ip );
			$ip  = trim( $ips[0] );
		}

		return substr( $ip, 0, 100 );
	}
}

/**
 * إرسال بريد تأكيد الاشتراك
 */
function nadiim_send_newsletter_confirmation( $email ) {
	$subject = get_theme_mod( 'newsletter_confirmation_subject', 'مرحباً بك في نشرة نديم البريدية!' );
	$message = get_theme_mod(
		'newsletter_confirmation_message',
		'شكراً لاشتراكك في نشرتنا البريدية. سنرسل لك آخر الأخبار والإصدارات والفعاليات مباشرة في بريدك.'
	);

	// تنسيق البريد
	$body = "
		<html dir='rtl'>
		<head>
			<meta charset='UTF-8'>
			<style>
				body { font-family: 'Tajawal', Arial, sans-serif; direction: rtl; text-align: right; }
				.container { max-width: 600px; margin: 0 auto; padding: 20px; }
				.header { background: linear-gradient(135deg, #339063 0%, #2a7a52 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
				.content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
				.button { display: inline-block; padding: 12px 30px; background: #339063; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
			</style>
		</head>
		<body>
			<div class='container'>
				<div class='header'>
					<h1>{$subject}</h1>
				</div>
				<div class='content'>
					<p>{$message}</p>
					<p>يمكنك إلغاء الاشتراك في أي وقت من خلال الرابط الموجود في أسفل رسائلنا.</p>
					<a href='" . home_url() . "' class='button'>زيارة الموقع</a>
				</div>
			</div>
		</body>
		</html>
	";

	$headers = array( 'Content-Type: text/html; charset=UTF-8' );

	wp_mail( $email, $subject, $body, $headers );
}

/**
 * الاشتراك في MailChimp
 */
function nadiim_subscribe_to_mailchimp( $email ) {
	$api_key = get_theme_mod( 'newsletter_mailchimp_api_key', '' );
	$list_id = get_theme_mod( 'newsletter_mailchimp_list_id', '' );

	if ( empty( $api_key ) || empty( $list_id ) ) {
		return false;
	}

	// استخراج datacenter من API key
	$datacenter = substr( $api_key, strpos( $api_key, '-' ) + 1 );

	$url = "https://{$datacenter}.api.mailchimp.com/3.0/lists/{$list_id}/members/";

	$data = array(
		'email_address' => $email,
		'status'        => 'subscribed',
	);

	$args = array(
		'method'  => 'POST',
		'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key ),
			'Content-Type'  => 'application/json',
		),
		'body'    => wp_json_encode( $data ),
		'timeout' => 15,
	);

	$response = wp_remote_post( $url, $args );

	if ( is_wp_error( $response ) ) {
		error_log( 'MailChimp Error: ' . $response->get_error_message() );
		return false;
	}

	$code = wp_remote_retrieve_response_code( $response );

	return in_array( $code, array( 200, 201 ), true );
}

/**
 * إشعار المدير بمشترك جديد
 */
function nadiim_notify_admin_new_subscriber( $email ) {
	$admin_email = get_option( 'admin_email' );
	$subject     = 'مشترك جديد في النشرة البريدية';
	$message     = "تم تسجيل مشترك جديد في النشرة البريدية:\n\n";
	$message    .= "البريد الإلكتروني: {$email}\n";
	$message    .= "التاريخ: " . current_time( 'mysql' ) . "\n";
	$message    .= "IP: " . nadiim_get_user_ip() . "\n";

	wp_mail( $admin_email, $subject, $message );
}

/**
 * صفحة إدارة المشتركين في لوحة التحكم
 */
function nadiim_add_newsletter_admin_page() {
	add_menu_page(
		'المشتركون',
		'النشرة البريدية',
		'manage_options',
		'nadiim-newsletter',
		'nadiim_newsletter_admin_page',
		'dashicons-email-alt',
		25
	);
}
add_action( 'admin_menu', 'nadiim_add_newsletter_admin_page' );

/**
 * محتوى صفحة إدارة المشتركين
 */
function nadiim_newsletter_admin_page() {
	// التأكد من وجود الجدول
	nadiim_ensure_newsletter_table_exists();

	global $wpdb;
	$table_name = $wpdb->prefix . 'nadiim_newsletter_subscribers';

	// معالجة الحذف
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) && check_admin_referer( 'delete_subscriber_' . intval( $_GET['id'] ) ) ) {
		$wpdb->delete( $table_name, array( 'id' => intval( $_GET['id'] ) ), array( '%d' ) );
		echo '<div class="notice notice-success"><p>تم حذف المشترك بنجاح.</p></div>';
	}

	// الحصول على المشتركين
	$subscribers = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY subscribed_at DESC" );
	$total       = count( $subscribers );
	?>
	<div class="wrap">
		<h1>المشتركون في النشرة البريدية</h1>
		<p>إجمالي المشتركين: <strong><?php echo esc_html( $total ); ?></strong></p>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>البريد الإلكتروني</th>
					<th>الحالة</th>
					<th>تاريخ الاشتراك</th>
					<th>IP</th>
					<th>الإجراءات</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( $subscribers ) : ?>
					<?php foreach ( $subscribers as $subscriber ) : ?>
						<tr>
							<td><?php echo esc_html( $subscriber->id ); ?></td>
							<td><?php echo esc_html( $subscriber->email ); ?></td>
							<td>
								<span class="status-<?php echo esc_attr( $subscriber->status ); ?>">
									<?php echo esc_html( $subscriber->status === 'active' ? 'نشط' : 'غير نشط' ); ?>
								</span>
							</td>
							<td><?php echo esc_html( $subscriber->subscribed_at ); ?></td>
							<td><?php echo esc_html( $subscriber->ip_address ); ?></td>
							<td>
								<a href="?page=nadiim-newsletter&action=delete&id=<?php echo esc_attr( $subscriber->id ); ?>&_wpnonce=<?php echo esc_attr( wp_create_nonce( 'delete_subscriber_' . $subscriber->id ) ); ?>"
								   onclick="return confirm('هل أنت متأكد من الحذف؟');"
								   class="button button-small">حذف</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="6">لا يوجد مشتركون بعد.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<hr />

		<h2>تصدير المشتركين</h2>
		<p>
			<a href="?page=nadiim-newsletter&action=export" class="button button-primary">
				تصدير كملف CSV
			</a>
		</p>
	</div>
	<?php

	// معالجة التصدير
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'export' ) {
		nadiim_export_subscribers_csv();
	}
}

/**
 * تصدير المشتركين كملف CSV
 */
function nadiim_export_subscribers_csv() {
	global $wpdb;
	$table_name  = $wpdb->prefix . 'nadiim_newsletter_subscribers';
	$subscribers = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY subscribed_at DESC", ARRAY_A );

	if ( ! $subscribers ) {
		return;
	}

	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=newsletter-subscribers-' . date( 'Y-m-d' ) . '.csv' );

	$output = fopen( 'php://output', 'w' );

	// UTF-8 BOM
	fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

	// العناوين
	fputcsv( $output, array( 'ID', 'Email', 'Status', 'Subscribed At', 'IP Address' ) );

	// البيانات
	foreach ( $subscribers as $subscriber ) {
		fputcsv( $output, array(
			$subscriber['id'],
			$subscriber['email'],
			$subscriber['status'],
			$subscriber['subscribed_at'],
			$subscriber['ip_address'],
		) );
	}

	fclose( $output );
	exit;
}
