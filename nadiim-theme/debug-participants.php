<?php
/**
 * سكريبت Debug لفحص بيانات المشاركين
 *
 * كيفية الاستخدام:
 * 1. ارفع هذا الملف إلى مجلد الثيم
 * 2. افتح في المتصفح: http://yoursite.com/wp-content/themes/nadiim-theme/debug-participants.php
 *
 * @package Nadiim
 */

// تحميل WordPress
require_once '../../../wp-load.php';

// التحقق من أنك مسجل دخول كمدير
if ( ! current_user_can( 'manage_options' ) ) {
	die( 'Access Denied: You must be an administrator to view this page.' );
}

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Debug - المشاركون في الحوارات</title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
			direction: rtl;
			padding: 20px;
			background: #f0f0f1;
		}
		.container {
			max-width: 1200px;
			margin: 0 auto;
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		}
		h1 {
			color: #1d2327;
			border-bottom: 3px solid #2271b1;
			padding-bottom: 10px;
		}
		.dialogue-item {
			background: #f6f7f7;
			padding: 20px;
			margin-bottom: 20px;
			border-radius: 6px;
			border-right: 4px solid #2271b1;
		}
		.dialogue-title {
			font-size: 18px;
			font-weight: bold;
			color: #1d2327;
			margin-bottom: 10px;
		}
		.meta-info {
			font-size: 13px;
			color: #646970;
			margin-bottom: 15px;
		}
		.raw-json {
			background: #23282d;
			color: #f0f0f1;
			padding: 15px;
			border-radius: 4px;
			overflow-x: auto;
			margin: 10px 0;
			font-family: 'Courier New', monospace;
			font-size: 12px;
			direction: ltr;
			text-align: left;
		}
		.participant-card {
			background: white;
			padding: 15px;
			margin: 10px 0;
			border-radius: 4px;
			border: 1px solid #dcdcde;
		}
		.field-name {
			font-weight: 600;
			color: #2271b1;
			display: inline-block;
			width: 100px;
		}
		.field-value {
			color: #1d2327;
		}
		.type-badge {
			display: inline-block;
			padding: 2px 8px;
			border-radius: 3px;
			font-size: 11px;
			font-weight: 600;
			margin-right: 5px;
		}
		.type-string {
			background: #00a32a;
			color: white;
		}
		.type-array {
			background: #d63638;
			color: white;
		}
		.type-null {
			background: #646970;
			color: white;
		}
		.error {
			background: #fcf0f1;
			border: 1px solid #d63638;
			color: #d63638;
			padding: 10px;
			border-radius: 4px;
			margin: 10px 0;
		}
		.success {
			background: #edfaef;
			border: 1px solid #00a32a;
			color: #00a32a;
			padding: 10px;
			border-radius: 4px;
			margin: 10px 0;
		}
		.fix-button {
			background: #2271b1;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 4px;
			cursor: pointer;
			font-size: 14px;
			margin-top: 10px;
		}
		.fix-button:hover {
			background: #135e96;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>🔍 Debug - المشاركون في الحوارات</h1>

		<?php
		// جلب جميع الحوارات
		$args = array(
			'post_type'      => 'howarat',
			'posts_per_page' => -1,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$howarat = new WP_Query( $args );

		if ( ! $howarat->have_posts() ) {
			echo '<div class="error">❌ لا توجد أي حوارات في النظام.</div>';
			echo '<p>يرجى إنشاء حوار جديد وإضافة مشاركين له أولاً.</p>';
		} else {
			echo '<p><strong>عدد الحوارات:</strong> ' . $howarat->found_posts . '</p>';

			$dialogues_with_errors = 0;
			$total_participants = 0;

			while ( $howarat->have_posts() ) {
				$howarat->the_post();
				$post_id = get_the_ID();

				// جلب البيانات الخام
				$raw_meta = get_post_meta( $post_id, 'dialogue_participants', true );

				echo '<div class="dialogue-item">';
				echo '<div class="dialogue-title">📝 ' . get_the_title() . '</div>';
				echo '<div class="meta-info">ID: ' . $post_id . ' | الحالة: ' . get_post_status() . ' | التاريخ: ' . get_the_date() . '</div>';

				if ( empty( $raw_meta ) ) {
					echo '<div class="error">⚠️ لا يوجد مشاركون محفوظون لهذا الحوار.</div>';
				} else {
					echo '<h3>البيانات الخام (JSON):</h3>';
					echo '<div class="raw-json">' . esc_html( $raw_meta ) . '</div>';

					// محاولة فك التشفير
					$decoded = json_decode( $raw_meta, true );

					if ( json_last_error() !== JSON_ERROR_NONE ) {
						echo '<div class="error">❌ خطأ في فك تشفير JSON: ' . json_last_error_msg() . '</div>';
						$dialogues_with_errors++;
					} elseif ( ! is_array( $decoded ) ) {
						echo '<div class="error">❌ البيانات المفككة ليست مصفوفة! النوع: ' . gettype( $decoded ) . '</div>';
						$dialogues_with_errors++;
					} else {
						$has_array_values = false;

						echo '<h3>المشاركون المفككون (' . count( $decoded ) . '):</h3>';

						foreach ( $decoded as $index => $participant ) {
							$total_participants++;

							echo '<div class="participant-card">';
							echo '<strong>المشارك #' . ( $index + 1 ) . '</strong><br>';

							if ( ! is_array( $participant ) ) {
								echo '<div class="error">❌ بيانات المشارك ليست مصفوفة! النوع: ' . gettype( $participant ) . '</div>';
								$has_array_values = true;
								continue;
							}

							// فحص كل حقل
							foreach ( array( 'type', 'name', 'role', 'bio', 'link' ) as $field ) {
								if ( ! isset( $participant[ $field ] ) ) {
									echo '<div>' . $field . ': <span class="type-badge type-null">NULL</span></div>';
									continue;
								}

								$value = $participant[ $field ];
								$type = gettype( $value );

								if ( is_array( $value ) ) {
									echo '<div class="error">';
									echo '<span class="field-name">' . $field . ':</span> ';
									echo '<span class="type-badge type-array">ARRAY</span> ';
									echo '<strong>⚠️ مشكلة!</strong> القيمة: ' . print_r( $value, true );
									echo '</div>';
									$has_array_values = true;
								} elseif ( is_string( $value ) ) {
									echo '<div>';
									echo '<span class="field-name">' . $field . ':</span> ';
									echo '<span class="type-badge type-string">STRING</span> ';
									echo '<span class="field-value">' . esc_html( $value ) . '</span>';
									echo '</div>';
								} else {
									echo '<div>';
									echo '<span class="field-name">' . $field . ':</span> ';
									echo '<span class="type-badge type-null">' . strtoupper( $type ) . '</span> ';
									echo '<span class="field-value">' . esc_html( print_r( $value, true ) ) . '</span>';
									echo '</div>';
								}
							}

							// photo_id و id
							echo '<div>';
							echo '<span class="field-name">id:</span> ';
							echo '<span class="field-value">' . ( isset( $participant['id'] ) ? $participant['id'] : 'N/A' ) . '</span>';
							echo '</div>';

							echo '<div>';
							echo '<span class="field-name">photo_id:</span> ';
							echo '<span class="field-value">' . ( isset( $participant['photo_id'] ) ? $participant['photo_id'] : 'N/A' ) . '</span>';
							echo '</div>';

							echo '</div>'; // .participant-card
						}

						if ( $has_array_values ) {
							echo '<div class="error">❌ هذا الحوار يحتوي على قيم من نوع ARRAY بدلاً من STRING!</div>';
							echo '<form method="post" style="margin-top: 10px;">';
							echo '<input type="hidden" name="fix_post_id" value="' . $post_id . '">';
							echo '<button type="submit" name="fix_data" class="fix-button">🔧 إصلاح البيانات تلقائياً</button>';
							echo '</form>';
							$dialogues_with_errors++;
						} else {
							echo '<div class="success">✅ جميع البيانات صحيحة!</div>';
						}
					}
				}

				echo '</div>'; // .dialogue-item
			}

			wp_reset_postdata();

			// ملخص
			echo '<hr>';
			echo '<h2>📊 الملخص:</h2>';
			echo '<p><strong>إجمالي الحوارات:</strong> ' . $howarat->found_posts . '</p>';
			echo '<p><strong>إجمالي المشاركين:</strong> ' . $total_participants . '</p>';

			if ( $dialogues_with_errors > 0 ) {
				echo '<p class="error"><strong>⚠️ حوارات بها مشاكل:</strong> ' . $dialogues_with_errors . '</p>';
			} else {
				echo '<p class="success"><strong>✅ جميع الحوارات خالية من المشاكل!</strong></p>';
			}
		}

		// معالجة إصلاح البيانات
		if ( isset( $_POST['fix_data'] ) && isset( $_POST['fix_post_id'] ) ) {
			$fix_post_id = absint( $_POST['fix_post_id'] );

			echo '<hr>';
			echo '<h2>🔧 إصلاح البيانات للحوار ID: ' . $fix_post_id . '</h2>';

			$raw_meta = get_post_meta( $fix_post_id, 'dialogue_participants', true );

			if ( ! empty( $raw_meta ) ) {
				$decoded = json_decode( $raw_meta, true );

				if ( is_array( $decoded ) ) {
					$fixed_participants = array();

					foreach ( $decoded as $participant ) {
						if ( ! is_array( $participant ) ) {
							continue;
						}

						$fixed = array();

						// إصلاح كل حقل
						$fixed['type']     = isset( $participant['type'] ) && is_string( $participant['type'] ) ? $participant['type'] : 'guest';
						$fixed['id']       = isset( $participant['id'] ) ? absint( $participant['id'] ) : 0;
						$fixed['name']     = isset( $participant['name'] ) && is_string( $participant['name'] ) ? $participant['name'] : ( is_array( $participant['name'] ) ? implode( ' ', $participant['name'] ) : '' );
						$fixed['photo_id'] = isset( $participant['photo_id'] ) ? absint( $participant['photo_id'] ) : 0;
						$fixed['role']     = isset( $participant['role'] ) && is_string( $participant['role'] ) ? $participant['role'] : ( is_array( $participant['role'] ) ? implode( ' ', $participant['role'] ) : '' );
						$fixed['bio']      = isset( $participant['bio'] ) && is_string( $participant['bio'] ) ? $participant['bio'] : ( is_array( $participant['bio'] ) ? implode( ' ', $participant['bio'] ) : '' );
						$fixed['link']     = isset( $participant['link'] ) && is_string( $participant['link'] ) ? $participant['link'] : ( is_array( $participant['link'] ) ? implode( ' ', $participant['link'] ) : '' );

						$fixed_participants[] = $fixed;
					}

					// حفظ البيانات المصلحة
					$new_json = wp_json_encode( $fixed_participants, JSON_UNESCAPED_UNICODE );
					update_post_meta( $fix_post_id, 'dialogue_participants', $new_json );

					echo '<div class="success">✅ تم إصلاح البيانات بنجاح!</div>';
					echo '<p><a href="' . $_SERVER['PHP_SELF'] . '">تحديث الصفحة لرؤية النتائج</a></p>';
				} else {
					echo '<div class="error">❌ فشل فك تشفير البيانات.</div>';
				}
			} else {
				echo '<div class="error">❌ لا توجد بيانات لإصلاحها.</div>';
			}
		}
		?>

		<hr>
		<p style="color: #646970; font-size: 13px;">
			<strong>ملاحظة:</strong> هذا السكريبت للتشخيص فقط. يجب حذفه من السيرفر بعد الانتهاء من التصحيح.
		</p>

	</div>
</body>
</html>
