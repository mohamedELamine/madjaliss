<?php
/**
 * Meta Box: إدارة المشاركين في الحوارات
 *
 * تجربة المستخدم النهائية:
 * - عند تحرير حوار، يرى المحرر صندوق "المشاركون في الحوار"
 * - يمكنه إضافة مشاركين غير محدودين بزر "إضافة مشارك"
 * - لكل مشارك: اختيار النوع (ضيف/مستخدم)، الاسم، الدور، السيرة، الصورة، الرابط
 * - إذا اختار "مستخدم"، يمكنه البحث واختيار من المستخدمين المسجلين
 * - إذا اختار "ضيف"، يدخل البيانات يدوياً
 * - يمكن رفع صورة لكل مشارك باستخدام WordPress Media Library
 * - يمكن حذف أي مشارك بزر "حذف"
 * - يتم حفظ جميع البيانات في حقل واحد بصيغة JSON
 *
 * @package Nadiim
 * @since 2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل Meta Box للمشاركين
 */
function nadiim_add_howarat_participants_meta_box() {
	add_meta_box(
		'howarat_participants',
		__( 'المشاركون في الحوار', 'nadiim' ),
		'nadiim_render_howarat_participants_meta_box',
		'howarat',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'nadiim_add_howarat_participants_meta_box' );

/**
 * عرض محتوى Meta Box
 */
function nadiim_render_howarat_participants_meta_box( $post ) {
	// إضافة nonce للأمان
	wp_nonce_field( 'nadiim_save_howarat_participants', 'nadiim_howarat_participants_nonce' );

	// جلب البيانات المحفوظة مع معالجة آمنة
	$participants_json = get_post_meta( $post->ID, 'dialogue_participants', true );
	$participants      = array();

	if ( ! empty( $participants_json ) && is_string( $participants_json ) ) {
		$decoded = json_decode( $participants_json, true );
		if ( ! is_null( $decoded ) && is_array( $decoded ) ) {
			$participants = $decoded;
		}
	}

	// جلب قائمة المستخدمين لاستخدامها في Select
	$users = get_users( array(
		'orderby' => 'display_name',
		'order'   => 'ASC',
	) );

	?>
	<div class="howarat-participants-wrapper">
		<div class="howarat-participants-description">
			<p><?php _e( 'أضف المشاركين في هذا الحوار. يمكنك اختيار مستخدمين مسجلين أو إضافة ضيوف جدد.', 'nadiim' ); ?></p>
		</div>

		<div id="howarat-participants-container">
			<?php if ( ! empty( $participants ) ) : ?>
				<?php foreach ( $participants as $index => $participant ) : ?>
					<?php nadiim_render_participant_row( $index, $participant, $users ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="howarat-participants-actions">
			<button type="button" id="add-participant-btn" class="button button-primary">
				<span class="dashicons dashicons-plus-alt"></span>
				<?php _e( 'إضافة مشارك', 'nadiim' ); ?>
			</button>
		</div>

		<!-- قالب HTML للمشارك الجديد (مخفي) -->
		<script type="text/html" id="participant-row-template">
			<?php nadiim_render_participant_row( '{{INDEX}}', array(), $users ); ?>
		</script>
	</div>

	<style>
		.howarat-participants-wrapper {
			padding: 12px;
		}
		.howarat-participants-description {
			margin-bottom: 20px;
			padding: 12px;
			background: #f0f6fc;
			border-right: 4px solid #339063;
		}
		#howarat-participants-container {
			margin-bottom: 20px;
		}
		.participant-row {
			background: #fff;
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 15px;
			position: relative;
		}
		.participant-row-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 15px;
			padding-bottom: 12px;
			border-bottom: 2px solid #f0f0f0;
		}
		.participant-row-title {
			font-size: 15px;
			font-weight: 600;
			color: #339063;
		}
		.participant-row-actions {
			display: flex;
			gap: 8px;
		}
		.participant-row-body {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 15px;
		}
		.participant-field-full {
			grid-column: 1 / -1;
		}
		.participant-field {
			display: flex;
			flex-direction: column;
		}
		.participant-field label {
			font-weight: 600;
			margin-bottom: 6px;
			color: #1d2327;
		}
		.participant-field input[type="text"],
		.participant-field select,
		.participant-field textarea {
			width: 100%;
			padding: 8px 12px;
			border: 1px solid #ddd;
			border-radius: 4px;
		}
		.participant-field textarea {
			min-height: 80px;
			resize: vertical;
		}
		.participant-photo-preview {
			margin-top: 10px;
		}
		.participant-photo-preview img {
			max-width: 120px;
			height: auto;
			border-radius: 50%;
			border: 3px solid #339063;
		}
		.remove-participant-btn {
			color: #dc3232;
			border-color: #dc3232;
		}
		.remove-participant-btn:hover {
			background: #dc3232;
			color: #fff;
		}
		.user-fields,
		.guest-fields {
			display: none;
		}
		.user-fields.active,
		.guest-fields.active {
			display: contents;
		}
		.howarat-participants-actions {
			text-align: center;
		}
		#add-participant-btn {
			padding: 10px 24px;
			font-size: 14px;
		}
		#add-participant-btn .dashicons {
			margin-left: 5px;
		}
	</style>
	<?php
}

/**
 * عرض صف مشارك واحد
 */
function nadiim_render_participant_row( $index, $participant = array(), $users = array() ) {
	// القيم الافتراضية
	$defaults = array(
		'type'     => 'guest',
		'id'       => 0,
		'name'     => '',
		'photo_id' => 0,
		'role'     => '',
		'bio'      => '',
		'link'     => '',
	);

	$participant = wp_parse_args( $participant, $defaults );
	$photo_url   = $participant['photo_id'] ? wp_get_attachment_image_url( $participant['photo_id'], 'thumbnail' ) : '';
	?>
	<div class="participant-row" data-index="<?php echo esc_attr( $index ); ?>">
		<div class="participant-row-header">
			<h4 class="participant-row-title">
				<?php
				if ( $index === '{{INDEX}}' ) {
					_e( 'مشارك جديد', 'nadiim' );
				} else {
					printf( __( 'المشارك #%s', 'nadiim' ), $index + 1 );
				}
				?>
			</h4>
			<div class="participant-row-actions">
				<button type="button" class="button remove-participant-btn">
					<span class="dashicons dashicons-trash"></span>
					<?php _e( 'حذف', 'nadiim' ); ?>
				</button>
			</div>
		</div>

		<div class="participant-row-body">
			<!-- نوع المشارك -->
			<div class="participant-field">
				<label><?php _e( 'نوع المشارك', 'nadiim' ); ?></label>
				<select name="participants[<?php echo esc_attr( $index ); ?>][type]" class="participant-type-select">
					<option value="guest" <?php selected( $participant['type'], 'guest' ); ?>><?php _e( 'ضيف', 'nadiim' ); ?></option>
					<option value="user" <?php selected( $participant['type'], 'user' ); ?>><?php _e( 'مستخدم مسجل', 'nadiim' ); ?></option>
				</select>
			</div>

			<!-- اختيار المستخدم (إذا كان النوع = user) -->
			<div class="participant-field user-fields <?php echo $participant['type'] === 'user' ? 'active' : ''; ?>">
				<label><?php _e( 'اختر المستخدم', 'nadiim' ); ?></label>
				<select name="participants[<?php echo esc_attr( $index ); ?>][user_id]" class="participant-user-select">
					<option value="0"><?php _e( '-- اختر مستخدماً --', 'nadiim' ); ?></option>
					<?php foreach ( $users as $user ) : ?>
						<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $participant['id'], $user->ID ); ?>>
							<?php echo esc_html( $user->display_name ); ?> (<?php echo esc_html( $user->user_email ); ?>)
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<!-- الاسم (للضيف) -->
			<div class="participant-field guest-fields <?php echo $participant['type'] === 'guest' ? 'active' : ''; ?>">
				<label><?php _e( 'الاسم', 'nadiim' ); ?></label>
				<input type="text" name="participants[<?php echo esc_attr( $index ); ?>][name]" value="<?php echo esc_attr( $participant['name'] ); ?>" placeholder="<?php esc_attr_e( 'أدخل اسم الضيف', 'nadiim' ); ?>">
			</div>

			<!-- الدور -->
			<div class="participant-field">
				<label><?php _e( 'الدور', 'nadiim' ); ?></label>
				<input type="text" name="participants[<?php echo esc_attr( $index ); ?>][role]" value="<?php echo esc_attr( $participant['role'] ); ?>" placeholder="<?php esc_attr_e( 'مثال: ضيف، مقدم، منسّق', 'nadiim' ); ?>">
			</div>

			<!-- السيرة الذاتية -->
			<div class="participant-field participant-field-full">
				<label><?php _e( 'السيرة الذاتية', 'nadiim' ); ?></label>
				<textarea name="participants[<?php echo esc_attr( $index ); ?>][bio]" placeholder="<?php esc_attr_e( 'نبذة مختصرة عن المشارك', 'nadiim' ); ?>"><?php echo esc_textarea( $participant['bio'] ); ?></textarea>
			</div>

			<!-- الرابط -->
			<div class="participant-field">
				<label><?php _e( 'الرابط', 'nadiim' ); ?></label>
				<input type="url" name="participants[<?php echo esc_attr( $index ); ?>][link]" value="<?php echo esc_url( $participant['link'] ); ?>" placeholder="<?php esc_attr_e( 'رابط الموقع أو الملف الشخصي', 'nadiim' ); ?>">
			</div>

			<!-- الصورة -->
			<div class="participant-field">
				<label><?php _e( 'الصورة', 'nadiim' ); ?></label>
				<input type="hidden" name="participants[<?php echo esc_attr( $index ); ?>][photo_id]" class="participant-photo-id" value="<?php echo esc_attr( $participant['photo_id'] ); ?>">
				<button type="button" class="button upload-photo-btn"><?php _e( 'رفع صورة', 'nadiim' ); ?></button>
				<button type="button" class="button remove-photo-btn" style="<?php echo ! $photo_url ? 'display:none;' : ''; ?>"><?php _e( 'إزالة الصورة', 'nadiim' ); ?></button>
				<div class="participant-photo-preview">
					<?php if ( $photo_url ) : ?>
						<img src="<?php echo esc_url( $photo_url ); ?>" alt="">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * حفظ بيانات المشاركين
 */
function nadiim_save_howarat_participants( $post_id ) {
	// التحقق من nonce
	if ( ! isset( $_POST['nadiim_howarat_participants_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['nadiim_howarat_participants_nonce'], 'nadiim_save_howarat_participants' ) ) {
		return;
	}

	// التحقق من autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// التحقق من الصلاحيات
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// التحقق من نوع المنشور
	if ( 'howarat' !== get_post_type( $post_id ) ) {
		return;
	}

	// جلب بيانات المشاركين وتنظيفها
	$participants_raw = isset( $_POST['participants'] ) ? $_POST['participants'] : array();
	$participants     = array();

	foreach ( $participants_raw as $participant_data ) {
		$participant = array();

		// نوع المشارك
		$participant['type'] = isset( $participant_data['type'] ) && in_array( $participant_data['type'], array( 'guest', 'user' ) ) ? $participant_data['type'] : 'guest';

		// إذا كان مستخدماً
		if ( $participant['type'] === 'user' ) {
			$user_id = isset( $participant_data['user_id'] ) ? absint( $participant_data['user_id'] ) : 0;
			if ( $user_id > 0 ) {
				$user = get_userdata( $user_id );
				if ( $user ) {
					$participant['id']   = $user_id;
					$participant['name'] = $user->display_name;
				} else {
					continue; // تخطي إذا كان المستخدم غير موجود
				}
			} else {
				continue; // تخطي إذا لم يتم اختيار مستخدم
			}
		} else {
			// إذا كان ضيفاً
			$participant['id']   = 0;
			$participant['name'] = isset( $participant_data['name'] ) ? sanitize_text_field( $participant_data['name'] ) : '';

			// تخطي إذا لم يكن هناك اسم
			if ( empty( $participant['name'] ) ) {
				continue;
			}
		}

		// باقي الحقول
		$participant['photo_id'] = isset( $participant_data['photo_id'] ) ? absint( $participant_data['photo_id'] ) : 0;
		$participant['role']     = isset( $participant_data['role'] ) ? sanitize_text_field( $participant_data['role'] ) : '';
		$participant['bio']      = isset( $participant_data['bio'] ) ? sanitize_textarea_field( $participant_data['bio'] ) : '';
		$participant['link']     = isset( $participant_data['link'] ) ? esc_url_raw( $participant_data['link'] ) : '';

		$participants[] = $participant;
	}

	// حفظ البيانات بصيغة JSON
	if ( ! empty( $participants ) ) {
		$json_data = wp_json_encode( $participants, JSON_UNESCAPED_UNICODE );
		update_post_meta( $post_id, 'dialogue_participants', $json_data );

		// Debug: تسجيل البيانات المحفوظة
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( 'Howarat Participants Saved for Post ID ' . $post_id . ': ' . $json_data );
		}
	} else {
		delete_post_meta( $post_id, 'dialogue_participants' );

		// Debug: تسجيل حذف البيانات
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( 'Howarat Participants deleted for Post ID ' . $post_id . ' (empty array)' );
		}
	}
}
add_action( 'save_post_howarat', 'nadiim_save_howarat_participants' );

/**
 * تحميل Scripts و Styles للميتا بوكس
 */
function nadiim_enqueue_howarat_meta_scripts( $hook ) {
	global $post_type;

	// تحميل فقط في صفحة تحرير howarat
	if ( 'howarat' !== $post_type || ( 'post.php' !== $hook && 'post-new.php' !== $hook ) ) {
		return;
	}

	// تحميل ملف JavaScript
	wp_enqueue_media();
	wp_enqueue_script(
		'howarat-meta-box',
		get_template_directory_uri() . '/assets/js/howarat-meta.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);

	// تمرير متغيرات
	wp_localize_script(
		'howarat-meta-box',
		'howaratMeta',
		array(
			'confirmDelete' => __( 'هل أنت متأكد من حذف هذا المشارك؟', 'nadiim' ),
			'selectImage'   => __( 'اختر صورة', 'nadiim' ),
			'useImage'      => __( 'استخدام الصورة', 'nadiim' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'nadiim_enqueue_howarat_meta_scripts' );
