<?php
/**
 * صفحة إدارة تسجيلات الفعاليات في لوحة التحكم
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * إضافة صفحة في قائمة لوحة التحكم
 */
function nadiim_add_event_registrations_menu() {
	add_menu_page(
		'تسجيلات الفعاليات',
		'تسجيلات الفعاليات',
		'manage_options',
		'event-registrations',
		'nadiim_render_event_registrations_page',
		'dashicons-tickets-alt',
		30
	);
}
add_action( 'admin_menu', 'nadiim_add_event_registrations_menu' );

/**
 * عرض صفحة التسجيلات
 */
function nadiim_render_event_registrations_page() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'event_registrations';

	// معالجة الحذف
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) ) {
		check_admin_referer( 'delete_registration_' . $_GET['id'] );
		$wpdb->delete( $table_name, array( 'id' => absint( $_GET['id'] ) ), array( '%d' ) );
		echo '<div class="notice notice-success"><p>تم حذف التسجيل بنجاح</p></div>';
	}

	// معالجة إعادة إرسال الإيميل
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'resend' && isset( $_GET['id'] ) ) {
		check_admin_referer( 'resend_email_' . $_GET['id'] );
		$registration = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE id = %d",
			absint( $_GET['id'] )
		) );

		if ( $registration ) {
			$email_sent = nadiim_send_registration_confirmation_email(
				$registration->event_id,
				$registration->user_name,
				$registration->user_email
			);

			if ( $email_sent ) {
				$wpdb->update(
					$table_name,
					array(
						'email_sent'      => 1,
						'email_sent_date' => current_time( 'mysql' ),
					),
					array( 'id' => $registration->id ),
					array( '%d', '%s' ),
					array( '%d' )
				);
				echo '<div class="notice notice-success"><p>تم إعادة إرسال الإيميل بنجاح</p></div>';
			} else {
				echo '<div class="notice notice-error"><p>فشل إرسال الإيميل</p></div>';
			}
		}
	}

	// الحصول على فلتر الفعالية
	$event_filter = isset( $_GET['event_id'] ) ? absint( $_GET['event_id'] ) : 0;

	// بناء الاستعلام
	$query = "SELECT r.*, p.post_title as event_title
			  FROM $table_name r
			  LEFT JOIN {$wpdb->posts} p ON r.event_id = p.ID";

	if ( $event_filter ) {
		$query .= $wpdb->prepare( " WHERE r.event_id = %d", $event_filter );
	}

	$query .= " ORDER BY r.registration_date DESC";

	$registrations = $wpdb->get_results( $query );

	// الحصول على قائمة الفعاليات للفلتر
	$events = $wpdb->get_results(
		"SELECT DISTINCT p.ID, p.post_title
		 FROM {$wpdb->posts} p
		 INNER JOIN $table_name r ON p.ID = r.event_id
		 WHERE p.post_type = 'events'
		 ORDER BY p.post_title ASC"
	);

	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">تسجيلات الفعاليات</h1>

		<!-- فلتر الفعاليات -->
		<form method="get" class="alignleft" style="margin: 10px 0 20px;">
			<input type="hidden" name="page" value="event-registrations">
			<select name="event_id" onchange="this.form.submit()">
				<option value="">جميع الفعاليات</option>
				<?php foreach ( $events as $event ) : ?>
					<option value="<?php echo esc_attr( $event->ID ); ?>" <?php selected( $event_filter, $event->ID ); ?>>
						<?php echo esc_html( $event->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</form>

		<hr class="wp-header-end">

		<!-- إحصائيات -->
		<div style="margin: 20px 0; padding: 15px; background: #fff; border-right: 4px solid #339063;">
			<strong>إجمالي التسجيلات:</strong> <?php echo count( $registrations ); ?>
			<?php if ( $event_filter ) : ?>
				| <a href="?page=event-registrations">عرض الكل</a>
			<?php endif; ?>
		</div>

		<!-- جدول التسجيلات -->
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>الاسم</th>
					<th>البريد الإلكتروني</th>
					<th>الهاتف</th>
					<th>الفعالية</th>
					<th>تاريخ التسجيل</th>
					<th>حالة الإيميل</th>
					<th>الإجراءات</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $registrations ) ) : ?>
					<tr>
						<td colspan="8" style="text-align: center; padding: 40px;">
							لا توجد تسجيلات حتى الآن
						</td>
					</tr>
				<?php else : ?>
					<?php foreach ( $registrations as $reg ) : ?>
						<tr>
							<td><?php echo esc_html( $reg->id ); ?></td>
							<td><strong><?php echo esc_html( $reg->user_name ); ?></strong></td>
							<td>
								<a href="mailto:<?php echo esc_attr( $reg->user_email ); ?>">
									<?php echo esc_html( $reg->user_email ); ?>
								</a>
							</td>
							<td><?php echo esc_html( $reg->user_phone ?: '-' ); ?></td>
							<td>
								<a href="<?php echo get_permalink( $reg->event_id ); ?>" target="_blank">
									<?php echo esc_html( $reg->event_title ?: 'فعالية محذوفة' ); ?>
								</a>
							</td>
							<td><?php echo date_i18n( 'Y-m-d H:i', strtotime( $reg->registration_date ) ); ?></td>
							<td>
								<?php if ( $reg->email_sent ) : ?>
									<span style="color: #46b450; font-weight: 600;">✓ تم الإرسال</span>
									<br><small><?php echo date_i18n( 'Y-m-d H:i', strtotime( $reg->email_sent_date ) ); ?></small>
								<?php else : ?>
									<span style="color: #dc3232; font-weight: 600;">✗ لم يُرسل</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( ! $reg->email_sent ) : ?>
									<a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=event-registrations&action=resend&id=' . $reg->id ), 'resend_email_' . $reg->id ); ?>"
									   class="button button-small">
										إعادة إرسال
									</a>
								<?php endif; ?>
								<a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=event-registrations&action=delete&id=' . $reg->id ), 'delete_registration_' . $reg->id ); ?>"
								   class="button button-small"
								   onclick="return confirm('هل أنت متأكد من الحذف؟')">
									حذف
								</a>
							</td>
						</tr>
						<?php if ( $reg->user_message ) : ?>
							<tr>
								<td colspan="8" style="background: #f9f9f9; padding: 10px; border-right: 3px solid #ddd;">
									<strong>ملاحظات:</strong> <?php echo esc_html( $reg->user_message ); ?>
								</td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<!-- تصدير CSV -->
		<div style="margin-top: 20px;">
			<a href="<?php echo admin_url( 'admin.php?page=event-registrations&export=csv' . ( $event_filter ? '&event_id=' . $event_filter : '' ) ); ?>"
			   class="button button-primary">
				تصدير إلى CSV
			</a>
		</div>
	</div>

	<style>
	.wp-list-table th {
		font-weight: 600;
	}
	.wp-list-table td {
		vertical-align: middle;
	}
	</style>
	<?php
}

/**
 * تصدير التسجيلات إلى CSV
 */
function nadiim_export_registrations_csv() {
	if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'event-registrations' ) {
		return;
	}

	if ( ! isset( $_GET['export'] ) || $_GET['export'] !== 'csv' ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'event_registrations';

	$event_filter = isset( $_GET['event_id'] ) ? absint( $_GET['event_id'] ) : 0;

	$query = "SELECT r.*, p.post_title as event_title
			  FROM $table_name r
			  LEFT JOIN {$wpdb->posts} p ON r.event_id = p.ID";

	if ( $event_filter ) {
		$query .= $wpdb->prepare( " WHERE r.event_id = %d", $event_filter );
	}

	$query .= " ORDER BY r.registration_date DESC";

	$registrations = $wpdb->get_results( $query, ARRAY_A );

	// Headers for CSV download
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=event-registrations-' . date( 'Y-m-d' ) . '.csv' );

	$output = fopen( 'php://output', 'w' );

	// BOM for UTF-8
	fprintf( $output, chr(0xEF).chr(0xBB).chr(0xBF) );

	// Column headers
	fputcsv( $output, array( 'ID', 'الاسم', 'البريد الإلكتروني', 'الهاتف', 'الفعالية', 'تاريخ التسجيل', 'حالة الإيميل', 'تاريخ الإرسال', 'ملاحظات' ) );

	// Data rows
	foreach ( $registrations as $reg ) {
		fputcsv( $output, array(
			$reg['id'],
			$reg['user_name'],
			$reg['user_email'],
			$reg['user_phone'],
			$reg['event_title'],
			$reg['registration_date'],
			$reg['email_sent'] ? 'تم الإرسال' : 'لم يرسل',
			$reg['email_sent_date'],
			$reg['user_message'],
		) );
	}

	fclose( $output );
	exit;
}
add_action( 'admin_init', 'nadiim_export_registrations_csv' );
