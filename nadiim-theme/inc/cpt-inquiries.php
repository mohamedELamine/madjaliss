<?php
/**
 * نوع المحتوى المخصص: الاستفسارات (Inquiries)
 *
 * تسجيل CPT لحفظ استفسارات الزوار من نموذج الاتصال
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل Custom Post Type للاستفسارات
 */
function nadiim_register_inquiries_cpt() {
	$labels = array(
		'name'                  => _x( 'الاستفسارات', 'Post type general name', 'nadiim' ),
		'singular_name'         => _x( 'استفسار', 'Post type singular name', 'nadiim' ),
		'menu_name'             => _x( 'الاستفسارات', 'Admin Menu text', 'nadiim' ),
		'name_admin_bar'        => _x( 'استفسار', 'Add New on Toolbar', 'nadiim' ),
		'add_new'               => __( 'إضافة استفسار', 'nadiim' ),
		'add_new_item'          => __( 'إضافة استفسار جديد', 'nadiim' ),
		'new_item'              => __( 'استفسار جديد', 'nadiim' ),
		'edit_item'             => __( 'تحرير الاستفسار', 'nadiim' ),
		'view_item'             => __( 'عرض الاستفسار', 'nadiim' ),
		'all_items'             => __( 'جميع الاستفسارات', 'nadiim' ),
		'search_items'          => __( 'بحث في الاستفسارات', 'nadiim' ),
		'not_found'             => __( 'لم يُعثر على استفسارات', 'nadiim' ),
		'not_found_in_trash'    => __( 'لم يُعثر على استفسارات في السلة', 'nadiim' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 26,
		'menu_icon'           => 'dashicons-email',
		'supports'            => array( 'title', 'editor' ),
		'show_in_rest'        => false,
	);

	register_post_type( 'inquiries', $args );
}
add_action( 'init', 'nadiim_register_inquiries_cpt' );

/**
 * إضافة أعمدة مخصصة في قائمة الاستفسارات
 */
function nadiim_inquiries_custom_columns( $columns ) {
	$new_columns = array(
		'cb'           => $columns['cb'],
		'title'        => __( 'الاسم والموضوع', 'nadiim' ),
		'inquiry_email' => __( 'البريد الإلكتروني', 'nadiim' ),
		'inquiry_phone' => __( 'الهاتف', 'nadiim' ),
		'inquiry_status' => __( 'الحالة', 'nadiim' ),
		'inquiry_ip'   => __( 'IP', 'nadiim' ),
		'date'         => __( 'التاريخ', 'nadiim' ),
	);
	return $new_columns;
}
add_filter( 'manage_inquiries_posts_columns', 'nadiim_inquiries_custom_columns' );

/**
 * ملء محتوى الأعمدة المخصصة
 */
function nadiim_inquiries_custom_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'inquiry_email':
			$email = get_post_meta( $post_id, '_inquiry_email', true );
			if ( $email ) {
				echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			} else {
				echo '—';
			}
			break;

		case 'inquiry_phone':
			$phone = get_post_meta( $post_id, '_inquiry_phone', true );
			echo $phone ? esc_html( $phone ) : '—';
			break;

		case 'inquiry_status':
			$status = get_post_meta( $post_id, '_inquiry_status', true );
			$status = $status ? $status : 'new';

			$status_labels = array(
				'new'       => '<span class="inquiry-status inquiry-status-new">' . __( 'جديد', 'nadiim' ) . '</span>',
				'seen'      => '<span class="inquiry-status inquiry-status-seen">' . __( 'مقروء', 'nadiim' ) . '</span>',
				'responded' => '<span class="inquiry-status inquiry-status-responded">' . __( 'تم الرد', 'nadiim' ) . '</span>',
			);

			echo isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : $status_labels['new'];
			break;

		case 'inquiry_ip':
			$ip = get_post_meta( $post_id, '_inquiry_ip', true );
			echo $ip ? esc_html( $ip ) : '—';
			break;
	}
}
add_action( 'manage_inquiries_posts_custom_column', 'nadiim_inquiries_custom_column_content', 10, 2 );

/**
 * جعل أعمدة الحالة والتاريخ قابلة للترتيب
 */
function nadiim_inquiries_sortable_columns( $columns ) {
	$columns['inquiry_status'] = 'inquiry_status';
	return $columns;
}
add_filter( 'manage_edit-inquiries_sortable_columns', 'nadiim_inquiries_sortable_columns' );

/**
 * تطبيق الترتيب حسب الحالة
 */
function nadiim_inquiries_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'inquiry_status' === $orderby ) {
		$query->set( 'meta_key', '_inquiry_status' );
		$query->set( 'orderby', 'meta_value' );
	}
}
add_action( 'pre_get_posts', 'nadiim_inquiries_orderby' );

/**
 * إضافة Meta Box لتفاصيل الاستفسار
 */
function nadiim_inquiry_meta_boxes() {
	add_meta_box(
		'inquiry_details',
		__( 'تفاصيل الاستفسار', 'nadiim' ),
		'nadiim_inquiry_details_callback',
		'inquiries',
		'normal',
		'high'
	);

	add_meta_box(
		'inquiry_actions',
		__( 'إجراءات', 'nadiim' ),
		'nadiim_inquiry_actions_callback',
		'inquiries',
		'side',
		'high'
	);

	add_meta_box(
		'inquiry_notes',
		__( 'ملاحظات داخلية', 'nadiim' ),
		'nadiim_inquiry_notes_callback',
		'inquiries',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'nadiim_inquiry_meta_boxes' );

/**
 * محتوى Meta Box التفاصيل
 */
function nadiim_inquiry_details_callback( $post ) {
	$email = get_post_meta( $post->ID, '_inquiry_email', true );
	$phone = get_post_meta( $post->ID, '_inquiry_phone', true );
	$subject = get_post_meta( $post->ID, '_inquiry_subject', true );
	$ip = get_post_meta( $post->ID, '_inquiry_ip', true );
	$user_agent = get_post_meta( $post->ID, '_inquiry_user_agent', true );
	$referer = get_post_meta( $post->ID, '_inquiry_referer', true );
	?>
	<table class="form-table">
		<tr>
			<th><strong><?php _e( 'البريد الإلكتروني:', 'nadiim' ); ?></strong></th>
			<td>
				<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				<?php else : ?>
					—
				<?php endif; ?>
			</td>
		</tr>
		<?php if ( $phone ) : ?>
		<tr>
			<th><strong><?php _e( 'الهاتف:', 'nadiim' ); ?></strong></th>
			<td><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></td>
		</tr>
		<?php endif; ?>
		<?php if ( $subject ) : ?>
		<tr>
			<th><strong><?php _e( 'الموضوع:', 'nadiim' ); ?></strong></th>
			<td><?php echo esc_html( $subject ); ?></td>
		</tr>
		<?php endif; ?>
		<tr>
			<th><strong><?php _e( 'IP:', 'nadiim' ); ?></strong></th>
			<td><?php echo $ip ? esc_html( $ip ) : '—'; ?></td>
		</tr>
		<?php if ( $user_agent ) : ?>
		<tr>
			<th><strong><?php _e( 'المتصفح:', 'nadiim' ); ?></strong></th>
			<td><code style="font-size: 11px;"><?php echo esc_html( $user_agent ); ?></code></td>
		</tr>
		<?php endif; ?>
		<?php if ( $referer ) : ?>
		<tr>
			<th><strong><?php _e( 'الصفحة المُرسِلة:', 'nadiim' ); ?></strong></th>
			<td><a href="<?php echo esc_url( $referer ); ?>" target="_blank"><?php echo esc_html( $referer ); ?></a></td>
		</tr>
		<?php endif; ?>
	</table>
	<?php
}

/**
 * محتوى Meta Box الإجراءات
 */
function nadiim_inquiry_actions_callback( $post ) {
	$status = get_post_meta( $post->ID, '_inquiry_status', true );
	$status = $status ? $status : 'new';
	$email = get_post_meta( $post->ID, '_inquiry_email', true );

	wp_nonce_field( 'inquiry_status_nonce', 'inquiry_status_nonce_field' );
	?>
	<div class="inquiry-actions-box">
		<p>
			<label for="inquiry_status"><strong><?php _e( 'الحالة:', 'nadiim' ); ?></strong></label><br>
			<select name="inquiry_status" id="inquiry_status" style="width: 100%; margin-top: 8px;">
				<option value="new" <?php selected( $status, 'new' ); ?>><?php _e( 'جديد', 'nadiim' ); ?></option>
				<option value="seen" <?php selected( $status, 'seen' ); ?>><?php _e( 'مقروء', 'nadiim' ); ?></option>
				<option value="responded" <?php selected( $status, 'responded' ); ?>><?php _e( 'تم الرد', 'nadiim' ); ?></option>
			</select>
		</p>

		<?php if ( $email ) : ?>
		<p style="margin-top: 16px;">
			<a href="mailto:<?php echo esc_attr( $email ); ?>" class="button button-primary" style="width: 100%; text-align: center;">
				<?php _e( 'الرد عبر البريد', 'nadiim' ); ?>
			</a>
		</p>
		<?php endif; ?>
	</div>

	<style>
		.inquiry-actions-box {
			padding: 12px 0;
		}
	</style>
	<?php
}

/**
 * محتوى Meta Box الملاحظات الداخلية
 */
function nadiim_inquiry_notes_callback( $post ) {
	$notes = get_post_meta( $post->ID, '_inquiry_notes', true );
	wp_nonce_field( 'inquiry_notes_nonce', 'inquiry_notes_nonce_field' );
	?>
	<p>
		<textarea name="inquiry_notes" id="inquiry_notes" rows="5" style="width: 100%;"><?php echo esc_textarea( $notes ); ?></textarea>
	</p>
	<p class="description"><?php _e( 'ملاحظات داخلية لن تظهر للزائر', 'nadiim' ); ?></p>
	<?php
}

/**
 * حفظ بيانات Meta Boxes
 */
function nadiim_save_inquiry_meta( $post_id ) {
	// التحقق من الأمان
	if ( ! isset( $_POST['inquiry_status_nonce_field'] ) || ! wp_verify_nonce( $_POST['inquiry_status_nonce_field'], 'inquiry_status_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// حفظ الحالة
	if ( isset( $_POST['inquiry_status'] ) ) {
		update_post_meta( $post_id, '_inquiry_status', sanitize_text_field( $_POST['inquiry_status'] ) );
	}

	// حفظ الملاحظات
	if ( isset( $_POST['inquiry_notes_nonce_field'] ) && wp_verify_nonce( $_POST['inquiry_notes_nonce_field'], 'inquiry_notes_nonce' ) ) {
		if ( isset( $_POST['inquiry_notes'] ) ) {
			update_post_meta( $post_id, '_inquiry_notes', sanitize_textarea_field( $_POST['inquiry_notes'] ) );
		}
	}
}
add_action( 'save_post_inquiries', 'nadiim_save_inquiry_meta' );

/**
 * وضع علامة "مقروء" تلقائياً عند فتح الاستفسار
 */
function nadiim_mark_inquiry_as_seen() {
	global $pagenow, $post;

	if ( $pagenow === 'post.php' && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) === 'inquiries' ) {
		$status = get_post_meta( $_GET['post'], '_inquiry_status', true );

		// تغيير الحالة من "new" إلى "seen" تلقائياً
		if ( $status === 'new' || empty( $status ) ) {
			update_post_meta( $_GET['post'], '_inquiry_status', 'seen' );
		}
	}
}
add_action( 'admin_init', 'nadiim_mark_inquiry_as_seen' );

/**
 * إضافة أنماط CSS مخصصة لصفحة الاستفسارات
 */
function nadiim_inquiries_admin_css() {
	global $post_type;
	if ( $post_type === 'inquiries' ) {
		?>
		<style>
			.inquiry-status {
				display: inline-block;
				padding: 4px 10px;
				border-radius: 3px;
				font-size: 12px;
				font-weight: 600;
			}
			.inquiry-status-new {
				background: #e74c3c;
				color: #fff;
			}
			.inquiry-status-seen {
				background: #f39c12;
				color: #fff;
			}
			.inquiry-status-responded {
				background: #27ae60;
				color: #fff;
			}
			.widefat .column-inquiry_email,
			.widefat .column-inquiry_phone {
				width: 15%;
			}
			.widefat .column-inquiry_status {
				width: 10%;
			}
			.widefat .column-inquiry_ip {
				width: 10%;
			}
		</style>
		<?php
	}
}
add_action( 'admin_head', 'nadiim_inquiries_admin_css' );

/**
 * إضافة إجراءات Bulk Actions لتصدير CSV
 */
function nadiim_inquiries_bulk_actions( $bulk_actions ) {
	$bulk_actions['export_csv'] = __( 'تصدير إلى CSV', 'nadiim' );
	$bulk_actions['mark_as_seen'] = __( 'وضع علامة مقروء', 'nadiim' );
	$bulk_actions['mark_as_responded'] = __( 'وضع علامة تم الرد', 'nadiim' );
	return $bulk_actions;
}
add_filter( 'bulk_actions-edit-inquiries', 'nadiim_inquiries_bulk_actions' );

/**
 * معالجة Bulk Actions المخصصة
 */
function nadiim_inquiries_bulk_action_handler( $redirect_to, $action, $post_ids ) {
	if ( $action === 'export_csv' ) {
		// تصدير CSV
		nadiim_export_inquiries_to_csv( $post_ids );
		exit;
	}

	if ( $action === 'mark_as_seen' ) {
		foreach ( $post_ids as $post_id ) {
			update_post_meta( $post_id, '_inquiry_status', 'seen' );
		}
		$redirect_to = add_query_arg( 'bulk_marked_seen', count( $post_ids ), $redirect_to );
	}

	if ( $action === 'mark_as_responded' ) {
		foreach ( $post_ids as $post_id ) {
			update_post_meta( $post_id, '_inquiry_status', 'responded' );
		}
		$redirect_to = add_query_arg( 'bulk_marked_responded', count( $post_ids ), $redirect_to );
	}

	return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-inquiries', 'nadiim_inquiries_bulk_action_handler', 10, 3 );

/**
 * دالة تصدير الاستفسارات إلى CSV
 */
function nadiim_export_inquiries_to_csv( $post_ids ) {
	// تجهيز الملف
	$filename = 'inquiries-export-' . date( 'Y-m-d-His' ) . '.csv';

	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $filename );

	// فتح output stream
	$output = fopen( 'php://output', 'w' );

	// كتابة BOM لدعم UTF-8 في Excel
	fprintf( $output, chr(0xEF).chr(0xBB).chr(0xBF) );

	// كتابة رؤوس الأعمدة
	fputcsv( $output, array(
		'الاسم',
		'البريد الإلكتروني',
		'الهاتف',
		'الموضوع',
		'الرسالة',
		'الحالة',
		'IP',
		'التاريخ'
	) );

	// كتابة البيانات
	foreach ( $post_ids as $post_id ) {
		$post = get_post( $post_id );
		$email = get_post_meta( $post_id, '_inquiry_email', true );
		$phone = get_post_meta( $post_id, '_inquiry_phone', true );
		$subject = get_post_meta( $post_id, '_inquiry_subject', true );
		$status = get_post_meta( $post_id, '_inquiry_status', true );
		$ip = get_post_meta( $post_id, '_inquiry_ip', true );

		fputcsv( $output, array(
			$post->post_title,
			$email,
			$phone,
			$subject,
			strip_tags( $post->post_content ),
			$status,
			$ip,
			get_the_date( 'Y-m-d H:i:s', $post_id )
		) );
	}

	fclose( $output );
}

/**
 * إظهار رسالة بعد تنفيذ Bulk Action
 */
function nadiim_inquiries_bulk_action_notices() {
	if ( ! empty( $_REQUEST['bulk_marked_seen'] ) ) {
		$count = intval( $_REQUEST['bulk_marked_seen'] );
		printf(
			'<div class="notice notice-success is-dismissible"><p>' .
			_n( 'تم وضع علامة مقروء على استفسار واحد.', 'تم وضع علامة مقروء على %s استفسار.', $count, 'nadiim' ) .
			'</p></div>',
			$count
		);
	}

	if ( ! empty( $_REQUEST['bulk_marked_responded'] ) ) {
		$count = intval( $_REQUEST['bulk_marked_responded'] );
		printf(
			'<div class="notice notice-success is-dismissible"><p>' .
			_n( 'تم وضع علامة تم الرد على استفسار واحد.', 'تم وضع علامة تم الرد على %s استفسار.', $count, 'nadiim' ) .
			'</p></div>',
			$count
		);
	}
}
add_action( 'admin_notices', 'nadiim_inquiries_bulk_action_notices' );
