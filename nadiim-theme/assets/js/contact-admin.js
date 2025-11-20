/**
 * JavaScript للوحة الإدارة - صفحة اتصل بنا
 *
 * يحسّن واجهة إدارة الاستفسارات، يضيف AJAX للتحديثات السريعة
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// التحقق من وجود صفحة الاستفسارات
	if (!$('body').hasClass('post-type-inquiries')) {
		return;
	}

	// ==========================================
	// تظليل الرسائل الجديدة
	// ==========================================
	$('.wp-list-table tbody tr').each(function() {
		const $statusCell = $(this).find('.inquiry-status-new');
		if ($statusCell.length) {
			$(this).css({
				'background-color': '#fff3cd',
				'font-weight': '500'
			});
		}
	});

	// ==========================================
	// AJAX لتغيير الحالة سريعاً
	// ==========================================
	$('.inquiry-status').on('click', function(e) {
		e.preventDefault();

		const $status = $(this);
		const $row = $status.closest('tr');
		const postId = $row.find('.check-column input').val();

		if (!postId) return;

		// دورة الحالات: new → seen → responded → new
		const currentStatus = $status.hasClass('inquiry-status-new') ? 'new' :
		                     $status.hasClass('inquiry-status-seen') ? 'seen' :
		                     'responded';

		const nextStatus = currentStatus === 'new' ? 'seen' :
		                   currentStatus === 'seen' ? 'responded' :
		                   'new';

		// تحديث الحالة عبر AJAX
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'nadiim_update_inquiry_status',
				post_id: postId,
				status: nextStatus,
				nonce: nadiimVars.nonce
			},
			success: function(response) {
				if (response.success) {
					// تحديث الواجهة
					$status.removeClass('inquiry-status-new inquiry-status-seen inquiry-status-responded');
					$status.addClass('inquiry-status-' + nextStatus);

					const statusLabels = {
						'new': 'جديد',
						'seen': 'مقروء',
						'responded': 'تم الرد'
					};
					$status.text(statusLabels[nextStatus]);

					// إزالة التظليل إذا لم تعد جديدة
					if (nextStatus !== 'new') {
						$row.css({
							'background-color': '',
							'font-weight': ''
						});
					}
				}
			}
		});
	});

	// ==========================================
	// زر تصدير سريع
	// ==========================================
	if ($('.tablenav .actions').length) {
		const $exportBtn = $('<button type="button" class="button button-secondary" id="export-all-inquiries">تصدير الكل إلى CSV</button>');
		$('.tablenav.top .actions:first').append($exportBtn);

		$exportBtn.on('click', function() {
			const postIds = [];
			$('.wp-list-table tbody tr').each(function() {
				const id = $(this).find('.check-column input').val();
				if (id) postIds.push(id);
			});

			if (postIds.length === 0) {
				alert('لا توجد استفسارات لتصديرها');
				return;
			}

			// إنشاء نموذج مخفي للتصدير
			const $form = $('<form>', {
				method: 'POST',
				action: window.location.href
			});

			$form.append($('<input>', {
				type: 'hidden',
				name: 'post[]',
				value: postIds.join(',')
			}));

			$form.append($('<input>', {
				type: 'hidden',
				name: 'action',
				value: 'export_csv'
			}));

			$form.append($('<input>', {
				type: 'hidden',
				name: '_wpnonce',
				value: nadiimVars.nonce
			}));

			$('body').append($form);
			$form.submit();
			$form.remove();
		});
	}

	// ==========================================
	// فلترة سريعة حسب الحالة
	// ==========================================
	if ($('.subsubsub').length === 0 && $('.tablenav.top').length) {
		const $filterBar = $('<ul class="subsubsub"></ul>');

		const filters = [
			{ status: 'all', label: 'الكل' },
			{ status: 'new', label: 'جديد' },
			{ status: 'seen', label: 'مقروء' },
			{ status: 'responded', label: 'تم الرد' }
		];

		filters.forEach((filter, index) => {
			const $li = $('<li></li>');
			const $link = $('<a href="#" data-status="' + filter.status + '">' + filter.label + '</a>');

			if (index === 0) {
				$link.addClass('current');
			}

			$li.append($link);
			if (index < filters.length - 1) {
				$li.append(' | ');
			}
			$filterBar.append($li);
		});

		$('.tablenav.top').prepend($filterBar);

		// التعامل مع النقر على الفلاتر
		$filterBar.on('click', 'a', function(e) {
			e.preventDefault();

			const status = $(this).data('status');

			$filterBar.find('a').removeClass('current');
			$(this).addClass('current');

			// إظهار/إخفاء الصفوف
			$('.wp-list-table tbody tr').each(function() {
				if (status === 'all') {
					$(this).show();
				} else {
					const hasStatus = $(this).find('.inquiry-status-' + status).length > 0;
					$(this).toggle(hasStatus);
				}
			});
		});
	}

	// ==========================================
	// بحث سريع
	// ==========================================
	if ($('.search-box').length === 0 && $('.tablenav.top').length) {
		const $searchBox = $(`
			<div class="search-box" style="float: left; margin-right: 8px;">
				<input type="search"
				       placeholder="بحث في الاستفسارات..."
				       style="padding: 4px 8px; width: 200px; border: 1px solid #ddd; border-radius: 3px;">
			</div>
		`);

		$('.tablenav.top .actions:first').after($searchBox);

		const $searchInput = $searchBox.find('input');

		$searchInput.on('input', function() {
			const query = $(this).val().toLowerCase().trim();

			$('.wp-list-table tbody tr').each(function() {
				const text = $(this).text().toLowerCase();
				$(this).toggle(text.indexOf(query) !== -1);
			});
		});
	}

	// ==========================================
	// تأكيد الحذف
	// ==========================================
	$('.submitdelete').on('click', function(e) {
		if (!confirm('هل أنت متأكد من حذف هذا الاستفسار؟ لا يمكن التراجع عن هذا الإجراء.')) {
			e.preventDefault();
		}
	});

	// ==========================================
	// نسخ البريد الإلكتروني بسرعة
	// ==========================================
	$('.column-inquiry_email a').on('click', function(e) {
		if (e.shiftKey) {
			e.preventDefault();

			const email = $(this).text();

			// نسخ إلى الحافظة
			if (navigator.clipboard) {
				navigator.clipboard.writeText(email).then(function() {
					// إظهار تأكيد
					const $notification = $('<div class="notice notice-success is-dismissible" style="position: fixed; top: 32px; left: 50%; transform: translateX(-50%); z-index: 9999;"><p>تم نسخ البريد الإلكتروني</p></div>');
					$('body').append($notification);

					setTimeout(function() {
						$notification.fadeOut(function() {
							$(this).remove();
						});
					}, 2000);
				});
			}
		}
	});

	// ==========================================
	// إحصائيات سريعة
	// ==========================================
	if ($('.wp-list-table tbody tr').length > 0) {
		const total = $('.wp-list-table tbody tr').length;
		const newCount = $('.inquiry-status-new').length;
		const seenCount = $('.inquiry-status-seen').length;
		const respondedCount = $('.inquiry-status-responded').length;

		const $stats = $(`
			<div class="inquiry-stats" style="background: #f0f0f1; padding: 12px 16px; margin: 16px 0; border-radius: 4px; display: flex; gap: 24px; align-items: center;">
				<strong>الإحصائيات:</strong>
				<span>المجموع: <strong>${total}</strong></span>
				<span style="color: #e74c3c;">جديد: <strong>${newCount}</strong></span>
				<span style="color: #f39c12;">مقروء: <strong>${seenCount}</strong></span>
				<span style="color: #27ae60;">تم الرد: <strong>${respondedCount}</strong></span>
			</div>
		`);

		$('.tablenav.top').after($stats);
	}

	// ==========================================
	// تحسين تجربة التحرير
	// ==========================================
	if ($('body').hasClass('post-php') && $('input[name="post_type"]').val() === 'inquiries') {
		// إضافة زر نسخ محتوى الرسالة
		if ($('#content').length) {
			const $copyBtn = $('<button type="button" class="button" style="margin: 8px 0;">نسخ محتوى الرسالة</button>');
			$('#postdivrich').before($copyBtn);

			$copyBtn.on('click', function() {
				const content = $('#content').val();
				if (navigator.clipboard) {
					navigator.clipboard.writeText(content);
					$(this).text('تم النسخ!');
					setTimeout(() => {
						$(this).text('نسخ محتوى الرسالة');
					}, 2000);
				}
			});
		}
	}

})(jQuery);

// ==========================================
// دالة PHP AJAX لتحديث الحالة
// ==========================================
// ملاحظة: يجب إضافة هذا الكود إلى functions.php أو ملف منفصل
/*
function nadiim_update_inquiry_status_ajax() {
	check_ajax_referer( 'nadiim-nonce', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( array( 'message' => 'Unauthorized' ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	$status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';

	if ( ! $post_id || ! in_array( $status, array( 'new', 'seen', 'responded' ) ) ) {
		wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
	}

	update_post_meta( $post_id, '_inquiry_status', $status );

	wp_send_json_success( array( 'message' => 'Status updated', 'status' => $status ) );
}
add_action( 'wp_ajax_nadiim_update_inquiry_status', 'nadiim_update_inquiry_status_ajax' );
*/
