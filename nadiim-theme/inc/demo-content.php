<?php
/**
 * Demo Content Management
 *
 * @package Nadiim
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add demo content admin menu
 */
function nadiim_demo_content_menu() {
	add_theme_page(
		__( 'المحتوى التجريبي', 'nadiim' ),
		__( 'المحتوى التجريبي', 'nadiim' ),
		'manage_options',
		'nadiim-demo-content',
		'nadiim_demo_content_page'
	);
}
add_action( 'admin_menu', 'nadiim_demo_content_menu' );

/**
 * Demo content page
 */
function nadiim_demo_content_page() {
	// Handle form submissions.
	if ( isset( $_POST['nadiim_import_demo'] ) && check_admin_referer( 'nadiim_demo_content', 'nadiim_demo_nonce' ) ) {
		nadiim_import_demo_content();
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم استيراد المحتوى التجريبي بنجاح!', 'nadiim' ) . '</p></div>';
	}

	if ( isset( $_POST['nadiim_delete_demo'] ) && check_admin_referer( 'nadiim_demo_content', 'nadiim_demo_nonce' ) ) {
		nadiim_delete_demo_content();
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم حذف المحتوى التجريبي بنجاح!', 'nadiim' ) . '</p></div>';
	}

	$demo_imported = get_option( 'nadiim_demo_imported', false );
	?>
	<div class="wrap" dir="rtl">
		<h1><?php esc_html_e( 'إدارة المحتوى التجريبي', 'nadiim' ); ?></h1>

		<div class="card" style="max-width: 800px; margin-top: 20px;">
			<h2><?php esc_html_e( 'المحتوى التجريبي لقالب نديم', 'nadiim' ); ?></h2>

			<p><?php esc_html_e( 'استيراد المحتوى التجريبي سيساعدك على رؤية القالب بشكل كامل وفهم كيفية استخدامه. يتضمن المحتوى التجريبي:', 'nadiim' ); ?></p>

			<ul style="list-style: disc; margin-right: 30px; line-height: 1.8;">
				<li><?php esc_html_e( '3 حوارات نموذجية (مرئية، صوتية، مكتوبة)', 'nadiim' ); ?></li>
				<li><?php esc_html_e( '4 إصدارات نموذجية (كتب ودراسات)', 'nadiim' ); ?></li>
				<li><?php esc_html_e( '2 نادي قراءة نشط', 'nadiim' ); ?></li>
				<li><?php esc_html_e( '5 مقالات في المدونة', 'nadiim' ); ?></li>
				<li><?php esc_html_e( 'الصفحات الأساسية (من نحن، اتصل بنا)', 'nadiim' ); ?></li>
				<li><?php esc_html_e( 'مستخدمين نموذجيين (كُتّاب ومشاركين)', 'nadiim' ); ?></li>
				<li><?php esc_html_e( 'إعدادات المظهر والقوائم', 'nadiim' ); ?></li>
			</ul>

			<?php if ( $demo_imported ) : ?>
				<div class="notice notice-info inline" style="margin: 20px 0;">
					<p><strong><?php esc_html_e( '⚠️ تم استيراد المحتوى التجريبي مسبقاً', 'nadiim' ); ?></strong></p>
					<p><?php esc_html_e( 'يمكنك حذف المحتوى التجريبي إذا كنت تريد البدء من جديد.', 'nadiim' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" style="margin-top: 20px;">
				<?php wp_nonce_field( 'nadiim_demo_content', 'nadiim_demo_nonce' ); ?>

				<p>
					<input type="submit"
						   name="nadiim_import_demo"
						   class="button button-primary button-hero"
						   value="<?php esc_attr_e( 'استيراد المحتوى التجريبي', 'nadiim' ); ?>"
						   <?php echo $demo_imported ? 'disabled' : ''; ?>>
				</p>

				<?php if ( $demo_imported ) : ?>
					<p>
						<input type="submit"
							   name="nadiim_delete_demo"
							   class="button button-secondary"
							   value="<?php esc_attr_e( 'حذف المحتوى التجريبي', 'nadiim' ); ?>"
							   onclick="return confirm('<?php esc_attr_e( 'هل أنت متأكد من حذف المحتوى التجريبي؟ هذا الإجراء لا يمكن التراجع عنه.', 'nadiim' ); ?>');">
					</p>
				<?php endif; ?>
			</form>

			<hr style="margin: 30px 0;">

			<h3><?php esc_html_e( '📝 ملاحظات هامة', 'nadiim' ); ?></h3>
			<ul style="list-style: disc; margin-right: 30px; line-height: 1.8;">
				<li><?php esc_html_e( 'يُفضل استيراد المحتوى التجريبي على موقع جديد أو فارغ', 'nadiim' ); ?></li>
				<li><?php esc_html_e( 'جميع الصور في المحتوى التجريبي هي روابط نائبة (placeholders)', 'nadiim' ); ?></li>
				<li><?php esc_html_e( 'يمكنك تعديل أو حذف أي محتوى بعد الاستيراد', 'nadiim' ); ?></li>
				<li><?php esc_html_e( 'تأكد من الذهاب إلى الإعدادات ← روابط دائمة واضغط "حفظ" بعد الاستيراد', 'nadiim' ); ?></li>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Import demo content
 */
function nadiim_import_demo_content() {
	// Create demo users.
	$users = nadiim_create_demo_users();

	// Create demo dialogues.
	nadiim_create_demo_dialogues( $users );

	// Create demo releases.
	nadiim_create_demo_releases( $users );

	// Create demo reading clubs.
	nadiim_create_demo_reading_clubs( $users );

	// Create demo posts.
	nadiim_create_demo_posts( $users );

	// Create demo pages.
	nadiim_create_demo_pages();

	// Setup menus.
	nadiim_setup_demo_menus();

	// Setup customizer settings.
	nadiim_setup_demo_customizer();

	// Mark demo as imported.
	update_option( 'nadiim_demo_imported', true );
	update_option( 'nadiim_demo_user_ids', $users );
}

/**
 * Create demo users
 */
function nadiim_create_demo_users() {
	$user_ids = array();

	$demo_users = array(
		array(
			'user_login'   => 'ahmad_mansour',
			'user_pass'    => wp_generate_password(),
			'user_email'   => 'ahmad@example.com',
			'display_name' => 'أحمد منصور',
			'first_name'   => 'أحمد',
			'last_name'    => 'منصور',
			'description'  => 'كاتب وباحث في الفكر الإسلامي المعاصر، له العديد من المؤلفات في التاريخ والحضارة.',
			'role'         => 'author',
		),
		array(
			'user_login'   => 'fatima_ali',
			'user_pass'    => wp_generate_password(),
			'user_email'   => 'fatima@example.com',
			'display_name' => 'فاطمة علي',
			'first_name'   => 'فاطمة',
			'last_name'    => 'علي',
			'description'  => 'أستاذة الأدب العربي بجامعة القاهرة، متخصصة في الشعر الجاهلي والأموي.',
			'role'         => 'author',
		),
		array(
			'user_login'   => 'omar_hassan',
			'user_pass'    => wp_generate_password(),
			'user_email'   => 'omar@example.com',
			'display_name' => 'عمر حسن',
			'first_name'   => 'عمر',
			'last_name'    => 'حسن',
			'description'  => 'مفكر وفيلسوف، مهتم بالفلسفة الإسلامية والفكر الحديث.',
			'role'         => 'author',
		),
		array(
			'user_login'   => 'sara_ibrahim',
			'user_pass'    => wp_generate_password(),
			'user_email'   => 'sara@example.com',
			'display_name' => 'سارة إبراهيم',
			'first_name'   => 'سارة',
			'last_name'    => 'إبراهيم',
			'description'  => 'باحثة في الدراسات الثقافية والنقد الأدبي المعاصر.',
			'role'         => 'author',
		),
	);

	foreach ( $demo_users as $user_data ) {
		// Check if user already exists.
		$existing_user = get_user_by( 'login', $user_data['user_login'] );
		if ( ! $existing_user ) {
			$user_id = wp_insert_user( $user_data );
			if ( ! is_wp_error( $user_id ) ) {
				$user_ids[] = $user_id;
			}
		} else {
			$user_ids[] = $existing_user->ID;
		}
	}

	return $user_ids;
}

/**
 * Create demo dialogues (Front Page Version)
 * المحتوى التجريبي للصفحة الرئيسية - حسب المواصفات المطلوبة
 */
function nadiim_create_demo_dialogues( $users ) {
	$dialogues = array(
		array(
			'title'       => 'حوارة مع فلان: عن القراءة والمدينة',
			'content'     => '<p>جلسة خفيفة تناقش مفاهيم القراءة اليومية وعلاقتها بالحياة الحضرية المعاصرة، نستكشف فيها كيف تشكل المدن تجربة القراءة وكيف تؤثر القراءة في فهمنا للمكان.</p><p>في هذه الجلسة الممتعة، نستضيف أحد المفكرين المهتمين بالعلاقة بين القراءة والمدينة. نناقش كيف تؤثر البيئة الحضرية على عاداتنا القرائية، وكيف يمكن للقراءة أن تغير نظرتنا للمدن التي نسكنها.</p><p>تطرقنا إلى موضوعات متنوعة منها: المكتبات العامة ودورها في الحياة الثقافية، المقاهي كفضاءات للقراءة، وتأثير الحياة السريعة على أنماط القراءة المختلفة.</p>',
			'media_type'  => 'video',
			'media_url'   => 'https://youtu.be/xxxxx',
			'duration'    => '45 دقيقة',
			'date'        => '2026-03-02',
			'location'    => '',
			'transcript'  => '',
			'participants' => array( $users[0], $users[1] ),
			'type'        => 'مقابلة مسجلة',
			'topic'       => 'قراءة',
		),
		array(
			'title'       => 'جلسة: قراءات في الأدب الجزائري',
			'content'     => '<p>حوارات عن المنحى الحداثي في الرواية الجزائرية المعاصرة، نستعرض أبرز الأعمال والكتّاب الذين أثروا المشهد الأدبي الجزائري.</p><p>نغوص في هذه الجلسة في عالم الأدب الجزائري المعاصر، مركزين على التيارات الحداثية التي ظهرت خلال العقود الأخيرة. نناقش أعمال كتّاب مثل واسيني الأعرج، أحلام مستغانمي، وياسمينة خضرا.</p><p>نتطرق إلى كيفية تناول هؤلاء الكتّاب لقضايا الهوية، التاريخ، والحداثة في سياق جزائري معقد ومتعدد الأبعاد.</p>',
			'media_type'  => 'audio',
			'media_url'   => 'audio/file2.mp3',
			'duration'    => '60 دقيقة',
			'date'        => '2026-04-10',
			'location'    => '',
			'transcript'  => '',
			'participants' => array( $users[1] ),
			'type'        => 'نقاش',
			'topic'       => 'أدب',
		),
		array(
			'title'       => 'حوار خاص: الكتاب والصوت',
			'content'     => '<p>حديث حول العلاقة بين النص المكتوب والتجربة السمعية، وكيف يمكن للصوت أن يضيف بُعداً جديداً لتجربة القراءة.</p><p>في هذا الحوار الخاص، نستكشف العلاقة الفريدة بين الكلمة المكتوبة والصوت المنطوق. مع انتشار الكتب الصوتية والبودكاست، أصبح السؤال ملحاً: هل تغير طبيعة التجربة الأدبية عندما ننتقل من القراءة إلى الاستماع؟</p><p>نناقش مع ضيوفنا تجاربهم في إنتاج المحتوى الصوتي، وكيف يختارون النصوص المناسبة للتسجيل، والتحديات التي يواجهونها في نقل المعنى والمشاعر عبر الصوت.</p>',
			'media_type'  => 'podcast',
			'media_url'   => 'https://youtu.be/yyyyy',
			'duration'    => '52 دقيقة',
			'date'        => '2026-05-18',
			'location'    => '',
			'transcript'  => '',
			'participants' => array( $users[2], $users[3] ),
			'type'        => 'بودكاست',
			'topic'       => 'صوت',
		),
	);

	foreach ( $dialogues as $dialogue ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $dialogue['title'],
				'post_content' => $dialogue['content'],
				'post_type'    => 'howarat',
				'post_status'  => 'publish',
				'post_author'  => $dialogue['participants'][0],
			)
		);

		if ( $post_id ) {
			// Set featured image (placeholder).
			nadiim_set_placeholder_image( $post_id, 800, 500 );

			// Set meta fields.
			update_post_meta( $post_id, 'dialogue_date', $dialogue['date'] );
			update_post_meta( $post_id, 'dialogue_type', $dialogue['type'] );
			update_post_meta( $post_id, 'dialogue_duration', $dialogue['duration'] );
			update_post_meta( $post_id, 'dialogue_media', $dialogue['media_url'] );
			update_post_meta( $post_id, 'dialogue_media_type', $dialogue['media_type'] );
			update_post_meta( $post_id, 'dialogue_media_url', $dialogue['media_url'] );
			update_post_meta( $post_id, 'dialogue_location', $dialogue['location'] );
			update_post_meta( $post_id, 'dialogue_transcript', $dialogue['transcript'] );
			update_post_meta( $post_id, 'dialogue_participants', $dialogue['participants'] );

			// Set terms.
			wp_set_object_terms( $post_id, $dialogue['type'], 'dialogue_type' );
			wp_set_object_terms( $post_id, $dialogue['topic'], 'dialogue_topic' );
		}
	}
}

/**
 * Create demo releases (Front Page Version)
 * الإصدارات التجريبية للصفحة الرئيسية
 */
function nadiim_create_demo_releases( $users ) {
	$releases = array(
		array(
			'title'   => 'خطوات نحو القراءة',
			'content' => '<h2>نظرة عامة</h2><p>دليل عملي للقراء المبتدئين، يقدم نصائح واستراتيجيات لبناء عادة القراءة اليومية وتطوير مهارات الفهم والنقد.</p><p>يقدم هذا الكتاب منهجية شاملة لمن يرغب في بناء عادة قرائية صحية ومستدامة. يبدأ بأساسيات اختيار الكتب المناسبة، ثم ينتقل إلى تقنيات القراءة الفعّالة، وينتهي بكيفية الاحتفاظ بالمعلومات وتطبيقها في الحياة اليومية.</p><h3>فصول الكتاب</h3><ol><li><strong>الأساسيات</strong>: كيف تبدأ رحلة القراءة</li><li><strong>التقنيات</strong>: استراتيجيات القراءة الفعّالة</li><li><strong>الممارسة</strong>: كيف تحافظ على استمرارية القراءة</li></ol>',
			'excerpt' => 'دليل موجز لبدء رحلة القراءة وبناء عادة يومية مستدامة.',
			'meta'    => array(
				'release_date'    => '2025-11-01',
				'release_type'    => 'book',
				'release_authors' => array(
					array(
						'type' => 'free',
						'id'   => 0,
						'name' => 'د. أحمد بن علي',
						'link' => '',
					),
				),
				'release_isbn'     => '978-1-234-56789-0',
				'release_pages'    => 184,
				'release_language' => 'ar',
				'release_format'   => array( 'pdf', 'epub' ),
				'release_excerpt'  => 'دليل شامل للمبتدئين في عالم القراءة، يقدم استراتيجيات عملية لبناء عادة القراءة اليومية.',
			),
			'category' => 'تطوير الذات',
			'tags'     => array( 'قراءة', 'عادات', 'مهارات' ),
		),
		array(
			'title'   => 'مجلة الخريف 2025',
			'content' => '<h2>في هذا العدد</h2><p>العدد الخاص من مجلة نديم الفصلية، يحتوي على مقالات ومراجعات وحوارات حول أبرز الإصدارات الأدبية لموسم الخريف.</p><h3>المحتويات</h3><ul><li>مراجعة معمقة لـ 10 كتب صدرت في خريف 2025</li><li>حوار مع الروائي الجزائري الشاب</li><li>ملف خاص: الشعر العربي المعاصر</li><li>دليل نوادي القراءة للموسم القادم</li><li>قراءات نقدية في الأدب المترجم</li></ul><p>كتب هذا العدد نخبة من النقاد والكتّاب العرب، وهو متاح للقراءة والتحميل مجاناً.</p>',
			'excerpt' => 'العدد الفصلي من مجلة نديم لخريف 2025.',
			'meta'    => array(
				'release_date'    => '2025-09-15',
				'release_type'    => 'magazine',
				'release_authors' => array(
					array(
						'type' => 'user',
						'id'   => $users[0],
						'name' => '',
						'link' => '',
					),
				),
				'release_pages'    => 64,
				'release_language' => 'ar',
				'release_format'   => array( 'pdf' ),
				'release_excerpt'  => 'عدد خاص يحتوي على مقالات ومراجعات لأبرز الإصدارات الأدبية.',
				'release_price'    => 'مجاني',
			),
			'category' => 'مجلات',
			'tags'     => array( 'أدب', 'مراجعات', 'خريف' ),
		),
		array(
			'title'   => 'دليل نوادي القراءة',
			'content' => '<h2>دليل شامل</h2><p>دليل شامل لإنشاء وإدارة نوادي القراءة، يتضمن نصائح عملية واستراتيجيات مجربة لبناء مجتمع قرائي نشط.</p><h3>ما ستجده في الدليل</h3><ul><li>كيفية اختيار الكتب المناسبة للنقاش</li><li>طرق إدارة الجلسات بفعالية</li><li>استراتيجيات لجذب أعضاء جدد</li><li>أدوات رقمية لتنظيم النادي</li><li>أمثلة على نوادي ناجحة في العالم العربي</li></ul><p>يتضمن الدليل أيضاً قوائم جاهزة وأسئلة نقاشية يمكن استخدامها مباشرة.</p>',
			'excerpt' => 'دليل عملي لتأسيس وإدارة نوادي القراءة.',
			'meta'    => array(
				'release_date'    => '2026-01-01',
				'release_type'    => 'brochure',
				'release_authors' => array(
					array(
						'type' => 'free',
						'id'   => 0,
						'name' => 'فريق نديم',
						'link' => '',
					),
				),
				'release_pages'    => 48,
				'release_language' => 'ar',
				'release_format'   => array( 'pdf' ),
				'release_excerpt'  => 'دليل يحتوي على نصائح عملية واستراتيجيات لإدارة نوادي القراءة.',
				'release_price'    => 'مجاني',
			),
			'category' => 'أدلة',
			'tags'     => array( 'نوادي القراءة', 'إدارة', 'تنظيم' ),
		),
		array(
			'title'   => 'تاريخ الأدب العربي',
			'content' => '<h2>رحلة عبر الزمن</h2><p>تقرير شامل يستعرض تطور الأدب العربي من العصر الجاهلي حتى العصر الحديث، مع تحليل للتيارات الأدبية الرئيسية.</p><h3>محتويات التقرير</h3><ol><li>العصر الجاهلي والشعر المعلق</li><li>العصر الإسلامي والأموي</li><li>العصر العباسي الذهبي</li><li>الأندلس والموشحات</li><li>عصر الانحطاط</li><li>النهضة الأدبية الحديثة</li><li>الأدب المعاصر</li></ol><p>يتضمن التقرير نماذج نصية ومقتطفات من أعمال أدبية رئيسية.</p>',
			'excerpt' => 'تقرير يستعرض تطور الأدب العربي عبر العصور.',
			'meta'    => array(
				'release_date'    => '2025-12-10',
				'release_type'    => 'report',
				'release_authors' => array(
					array(
						'type' => 'free',
						'id'   => 0,
						'name' => 'د. محمد السعيد',
						'link' => '',
					),
					array(
						'type' => 'free',
						'id'   => 0,
						'name' => 'أ. فاطمة الزهراء',
						'link' => '',
					),
				),
				'release_isbn'     => '978-1-234-56790-6',
				'release_pages'    => 120,
				'release_language' => 'ar',
				'release_format'   => array( 'pdf', 'epub' ),
				'release_excerpt'  => 'دراسة شاملة لتطور الأدب العربي من الجاهلية حتى العصر الحديث.',
			),
			'category' => 'دراسات',
			'tags'     => array( 'أدب', 'تاريخ', 'ثقافة' ),
		),
	);

	foreach ( $releases as $release ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $release['title'],
				'post_content' => $release['content'],
				'post_excerpt' => $release['excerpt'],
				'post_type'    => 'esdar',
				'post_status'  => 'publish',
				'post_author'  => $users[0],
			)
		);

		if ( $post_id ) {
			// Set featured image (placeholder) - نسبة 3:4 (600×800)
			nadiim_set_placeholder_image( $post_id, 600, 800 );

			// Save using new JSON structure
			$esdar_meta = wp_parse_args(
				$release['meta'],
				array(
					'release_file_id'          => '',
					'release_file_url'         => '',
					'release_preview_embed'    => '',
					'release_preview_images'   => array(),
					'release_download_count'   => 0,
				)
			);

			update_post_meta( $post_id, 'esdar_meta', wp_json_encode( $esdar_meta, JSON_UNESCAPED_UNICODE ) );

			// Set category
			if ( ! empty( $release['category'] ) ) {
				$category = get_term_by( 'name', $release['category'], 'category' );
				if ( ! $category ) {
					$category_id = wp_insert_term( $release['category'], 'category' );
					if ( ! is_wp_error( $category_id ) ) {
						wp_set_object_terms( $post_id, $category_id['term_id'], 'category' );
					}
				} else {
					wp_set_object_terms( $post_id, $category->term_id, 'category' );
				}
			}

			// Set tags
			if ( ! empty( $release['tags'] ) ) {
				wp_set_object_terms( $post_id, $release['tags'], 'post_tag' );
			}
		}
	}
}

/**
 * Create demo reading clubs (New System)
 * نوادي القراءة التجريبية - النظام الجديد
 */
function nadiim_create_demo_reading_clubs( $users ) {
	$clubs = array(
		array(
			'title'   => 'نادي القراءة الأسبوعي',
			'content' => '<p>نادي القراءة الأسبوعي هو مساحة ثقافية للقراء المتحمسين الذين يجتمعون أسبوعياً لمناقشة الكتب الحديثة والكلاسيكية. نرحب بجميع القراء بغض النظر عن مستوى خبرتهم القرائية.</p>',
			'excerpt' => 'مساحة ثقافية للقراء المتحمسين لمناقشة الكتب الحديثة والكلاسيكية',
			'meta'    => array(
				'short_description'      => 'نادي يجتمع أسبوعياً لمناقشة الكتب الحديثة والكلاسيكية. نرحب بجميع القراء من مختلف الأعمار والخلفيات.',
				'full_description'       => '<h2>عن النادي</h2><p>نادي القراءة الأسبوعي تأسس في عام 2020 بهدف جمع محبي القراءة والأدب في الجزائر. نلتقي كل أسبوع لمناقشة كتاب جديد ومشاركة الأفكار والآراء حول الأعمال الأدبية المختلفة.</p><h3>ما نقدمه</h3><ul><li>مناقشات أدبية متعمقة</li><li>اختيار دوري لكتب متنوعة</li><li>لقاءات مع كتّاب محليين وعالميين</li><li>ورش عمل للكتابة الإبداعية</li><li>مكتبة مشتركة للإعارة بين الأعضاء</li></ul><h3>كيف تنضم؟</h3><p>للانضمام إلى النادي، يكفي الحضور في أحد لقاءاتنا الأسبوعية. جميع اللقاءات مفتوحة للجميع ولا تتطلب تسجيلاً مسبقاً.</p>',
				'meeting_location'       => array(
					'address' => 'مكتبة المدينة – وسط الجزائر العاصمة',
					'lat'     => 36.7538,
					'lng'     => 3.0588,
				),
				'meeting_schedule_note'  => 'نلتقي كل يوم سبت على الساعة 18:00 مساءً. المدة: ساعتان تقريباً. يُنصح بقراءة الكتاب المختار قبل اللقاء.',
				'facebook_page'          => 'https://facebook.com/weekly.reading.club',
				'telegram_channel'       => 'https://t.me/weeklyreadingclub',
				'website'                => 'https://weeklyreadingclub.dz',
				'contact_email'          => 'contact@weeklyreadingclub.dz',
				'map_embed'              => '',
				'visibility'             => 'public',
			),
		),
		array(
			'title'   => 'نادي الأدب العربي الحديث',
			'content' => '<p>نادي متخصص في الأدب العربي المعاصر، نركز على أعمال الكتّاب العرب من القرن العشرين إلى اليوم. نسعى لتعزيز الوعي بالأدب العربي الحديث وإبراز الأصوات الأدبية المتميزة.</p>',
			'excerpt' => 'نادي متخصص في مناقشة الأدب العربي المعاصر والأصوات الأدبية الجديدة',
			'meta'    => array(
				'short_description'      => 'نادي متخصص في الأدب العربي المعاصر. نركز على أعمال الكتّاب العرب من القرن العشرين حتى اليوم.',
				'full_description'       => '<h2>رؤيتنا</h2><p>نؤمن بأهمية الأدب العربي الحديث في تشكيل الوعي الثقافي والاجتماعي. نسعى من خلال لقاءاتنا إلى:</p><ul><li>استكشاف التيارات الأدبية الحديثة في الوطن العربي</li><li>تحليل نقدي للأعمال الأدبية المعاصرة</li><li>دعم الكتّاب الشباب والأصوات الناشئة</li><li>ربط الأدب بالواقع الاجتماعي والسياسي</li></ul><h3>برنامجنا الشهري</h3><p>كل شهر نختار رواية أو مجموعة قصصية من الأدب العربي الحديث. نقرأها معاً ثم نلتقي لمناقشتها بشكل معمّق. نستضيف أحياناً نقاداً وكتّاباً للمشاركة في نقاشاتنا.</p><h3>الكتّاب الذين ناقشناهم</h3><p>نجيب محفوظ، غسان كنفاني، حنان الشيخ، إبراهيم الكوني، سنان أنطون، رجاء عالم، والعديد من الأسماء المميزة.</p>',
				'meeting_location'       => array(
					'address' => 'المركز الثقافي الجزائري – حي بئر مراد رايس',
					'lat'     => 36.7389,
					'lng'     => 3.0642,
				),
				'meeting_schedule_note'  => 'نجتمع في الأحد الأول من كل شهر على الساعة 17:00. المناقشات تستغرق حوالي ساعتين ونصف مع استراحة قصيرة.',
				'facebook_page'          => 'https://facebook.com/modern.arabic.lit',
				'telegram_channel'       => 'https://t.me/modernarabiclitclub',
				'website'                => '',
				'contact_email'          => 'info@arabiclitclub.org',
				'map_embed'              => '',
				'visibility'             => 'public',
			),
		),
		array(
			'title'   => 'نادي القراءة الفلسفية',
			'content' => '<p>نادي مخصص لعشاق الفلسفة والفكر. نقرأ ونناقش الأعمال الفلسفية الكلاسيكية والمعاصرة، من أفلاطون إلى سارتر ومن ابن رشد إلى محمد عابد الجابري.</p>',
			'excerpt' => 'نادي لعشاق الفلسفة والفكر، نقرأ ونناقش الأعمال الفلسفية الكلاسيكية والمعاصرة',
			'meta'    => array(
				'short_description'      => 'نادي مخصص لعشاق الفلسفة والفكر. نقرأ ونناقش الأعمال الفلسفية الكلاسيكية والمعاصرة من مختلف الحضارات.',
				'full_description'       => '<h2>منهجنا في القراءة</h2><p>الفلسفة ليست مجرد نصوص قديمة، بل هي طريقة تفكير وأسلوب حياة. في نادي القراءة الفلسفية، نتبنى منهجاً تفاعلياً لقراءة النصوص الفلسفية:</p><h3>ما نفعله</h3><ul><li><strong>قراءة نقدية:</strong> نقرأ النصوص الفلسفية بعمق ونحللها سياقياً وتاريخياً</li><li><strong>نقاشات مفتوحة:</strong> نشجع على التساؤل والجدل الفكري البنّاء</li><li><strong>ربط بالواقع:</strong> نربط الأفكار الفلسفية بقضايانا المعاصرة</li><li><strong>تنوع المدارس:</strong> نستكشف الفلسفة الغربية والشرقية والإسلامية</li></ul><h3>مواضيعنا الحالية</h3><p>حالياً نقرأ سلسلة حول فلسفة الأخلاق، بدءاً من أرسطو وصولاً إلى الفلاسفة المعاصرين مثل بيتر سينجر ومارثا نوسباوم.</p><h3>من يمكنه الانضمام؟</h3><p>النادي مفتوح للجميع. لا تحتاج إلى خلفية فلسفية مسبقة، فقط عقلاً فضولياً ورغبة في التعلم والتفكير.</p>',
				'meeting_location'       => array(
					'address' => 'مقهى المفكرين – حي ديدوش مراد',
					'lat'     => 36.7694,
					'lng'     => 3.0606,
				),
				'meeting_schedule_note'  => 'نلتقي كل يوم جمعة على الساعة 16:00. الجلسات تستمر ساعتين مع إمكانية التمديد حسب النقاش.',
				'facebook_page'          => 'https://facebook.com/philosophy.reading.club',
				'telegram_channel'       => 'https://t.me/philoreadingclub',
				'website'                => 'https://philoclub-dz.com',
				'contact_email'          => 'hello@philoclub-dz.com',
				'map_embed'              => '',
				'visibility'             => 'public',
			),
		),
	);

	foreach ( $clubs as $club ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $club['title'],
				'post_content' => $club['content'],
				'post_excerpt' => $club['excerpt'],
				'post_type'    => 'reading_clubs',
				'post_status'  => 'publish',
				'post_author'  => $users[0],
			)
		);

		if ( $post_id ) {
			// Set featured image (placeholder)
			nadiim_set_placeholder_image( $post_id, 800, 500 );

			// Set club_meta with new system
			update_post_meta( $post_id, 'club_meta', $club['meta'] );
		}
	}
}

/**
 * Create demo posts (Front Page Version)
 * المقالات التجريبية للصفحة الرئيسية
 */
function nadiim_create_demo_posts( $users ) {
	$posts = array(
		array(
			'title'   => 'كيف تقرأ بمزاج',
			'content' => '<p>نصائح وأفكار لجعل القراءة تجربة ممتعة ومريحة، بعيداً عن الضغوط والالتزامات.</p><p>القراءة ليست واجباً ولا مهمة يجب إنجازها. إنها متعة شخصية يمكن أن تصبح جزءاً طبيعياً من يومك إذا اقتربت منها بالطريقة الصحيحة.</p><p>في هذا المقال، نشارك بعض النصائح البسيطة:</p><p><strong>1. اختر الوقت المناسب:</strong> ليس عليك القراءة في وقت محدد. اقرأ عندما تشعر بالرغبة.</p><p><strong>2. لا تكمل كل كتاب:</strong> إذا لم يعجبك كتاب، اتركه واختر غيره. الحياة قصيرة والكتب كثيرة.</p><p><strong>3. نوّع قراءاتك:</strong> لا تقيد نفسك بنوع واحد. اقرأ الرواية والشعر والمقالة والقصة القصيرة.</p><p><strong>4. اصنع مكاناً مريحاً:</strong> زاوية هادئة، إضاءة جيدة، كرسي مريح، ومشروب دافئ.</p><p><strong>5. شارك ما تقرأ:</strong> الحديث عن الكتب مع الآخرين يضيف بُعداً جديداً للتجربة.</p><p>تذكر: القراءة رحلة شخصية، لا تقارن نفسك بأحد.</p>',
			'author'  => $users[0],
		),
		array(
			'title'   => 'تقرير: معرض الكتب المحلي',
			'content' => '<p>جولة في معرض الكتاب السنوي، مع أبرز الإصدارات والفعاليات التي شهدها المعرض هذا العام.</p><p>شهد معرض الكتاب هذا العام حضوراً لافتاً وتنوعاً في العروض. زرنا المعرض وقضينا يوماً كاملاً بين الأجنحة، وهنا خلاصة ما رأيناه:</p><h3>الإصدارات الجديدة:</h3><p>تنوعت الإصدارات بين الرواية والشعر والدراسات النقدية. لفت انتباهنا صدور مجموعة من الروايات المترجمة لأول مرة إلى العربية.</p><h3>الفعاليات الثقافية:</h3><p>نظم المعرض عدة ندوات وحوارات مع كتّاب وناشرين. كانت جلسة "القراءة في العصر الرقمي" من أكثر الجلسات حضوراً ونقاشاً.</p><h3>ملاحظات:</h3><ul><li>الأسعار متفاوتة لكن معقولة بشكل عام</li><li>حضور الشباب كان ملفتاً</li><li>نقص في تمثيل الناشرين المستقلين</li></ul><p>ننصح بزيارة المعرض في الأيام الأخيرة للحصول على تخفيضات جيدة.</p>',
			'author'  => $users[1],
		),
		array(
			'title'   => 'قراءة في قصيدة فلان',
			'content' => '<p>تحليل أدبي لإحدى القصائد المعاصرة، نستكشف فيه الصور الشعرية والبنية اللغوية والسياق الثقافي.</p><p>القصيدة التي بين أيدينا تمثل نموذجاً مميزاً من الشعر العربي المعاصر. سنحاول في هذه القراءة أن نفكك بعض طبقات المعنى ونستكشف التقنيات الفنية التي وظفها الشاعر.</p><h3>البنية:</h3><p>القصيدة مكونة من أربعة مقاطع، كل مقطع يمثل لحظة زمنية مختلفة. يستخدم الشاعر تقنية القطع والوصل لخلق إيقاع متنوع.</p><h3>الصور الشعرية:</h3><p>يكثر الشاعر من استخدام صور الطبيعة، لكنه يوظفها في سياق حضري معاصر. هذا التوتر بين الطبيعي والصناعي يخلق جمالية خاصة.</p><h3>اللغة:</h3><p>اللغة بسيطة لكنها مكثفة. لا زخرف ولا تعقيد، بل بحث عن الكلمة الأدق التي تحمل المعنى الأعمق.</p><h3>الخاتمة:</h3><p>القصيدة دعوة للتأمل في التجربة الإنسانية المشتركة، وهي تنجح في ذلك من خلال بساطة خادعة وعمق حقيقي.</p>',
			'author'  => $users[2],
		),
	);

	foreach ( $posts as $post ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $post['title'],
				'post_content' => $post['content'],
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_author'  => $post['author'],
			)
		);

		if ( $post_id ) {
			// Set featured image (placeholder).
			nadiim_set_placeholder_image( $post_id, 800, 500 );

			// Set category.
			wp_set_object_terms( $post_id, 'مقالات', 'category' );
		}
	}
}

/**
 * Create demo pages
 */
function nadiim_create_demo_pages() {
	$pages = array(
		array(
			'title'    => 'من نحن',
			'content'  => '<p>نديم منصة ثقافية فكرية تهدف إلى نشر المعرفة وتعزيز الحوار الفكري البناء.</p><h2>رؤيتنا</h2><p>نسعى لأن نكون منارة للفكر العربي الأصيل المعاصر، ومنصة تجمع المفكرين والباحثين والقراء في فضاء ثقافي راقٍ.</p><h2>رسالتنا</h2><p>نشر الوعي الثقافي والفكري من خلال الحوارات النوعية، الإصدارات المتميزة، ونوادي القراءة التفاعلية.</p>',
			'template' => 'template-about.php',
		),
		array(
			'title'    => 'اتصل بنا',
			'content'  => '<p>نسعد بتواصلكم معنا. يمكنكم التواصل من خلال النموذج أدناه أو عبر معلومات الاتصال التالية.</p>',
			'template' => 'template-contact.php',
		),
		array(
			'title'    => 'المدونة',
			'content'  => '',
			'template' => '',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = wp_insert_post(
			array(
				'post_title'   => $page['title'],
				'post_content' => $page['content'],
				'post_type'    => 'page',
				'post_status'  => 'publish',
			)
		);

		if ( $page_id && ! empty( $page['template'] ) ) {
			update_post_meta( $page_id, '_wp_page_template', $page['template'] );
		}

		// Save page IDs for later use.
		if ( $page['title'] === 'المدونة' ) {
			update_option( 'nadiim_demo_blog_page_id', $page_id );
		} elseif ( $page['title'] === 'من نحن' ) {
			update_option( 'nadiim_demo_about_page_id', $page_id );
		} elseif ( $page['title'] === 'اتصل بنا' ) {
			update_option( 'nadiim_demo_contact_page_id', $page_id );
		}
	}
}

/**
 * Setup demo menus
 */
function nadiim_setup_demo_menus() {
	// Create primary menu.
	$primary_menu_id = wp_create_nav_menu( 'القائمة الرئيسية التجريبية' );

	if ( ! is_wp_error( $primary_menu_id ) ) {
		// Get page IDs.
		$about_page_id   = get_option( 'nadiim_demo_about_page_id', 0 );
		$contact_page_id = get_option( 'nadiim_demo_contact_page_id', 0 );
		$blog_page_id    = get_option( 'nadiim_demo_blog_page_id', 0 );

		// Add menu items.
		wp_update_nav_menu_item(
			$primary_menu_id,
			0,
			array(
				'menu-item-title'   => 'الرئيسية',
				'menu-item-url'     => home_url( '/' ),
				'menu-item-status'  => 'publish',
				'menu-item-type'    => 'custom',
				'menu-item-classes' => 'home',
			)
		);

		wp_update_nav_menu_item(
			$primary_menu_id,
			0,
			array(
				'menu-item-title'     => 'الحوارات',
				'menu-item-url'       => get_post_type_archive_link( 'howarat' ),
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'post_type_archive',
				'menu-item-object'    => 'howarat',
			)
		);

		wp_update_nav_menu_item(
			$primary_menu_id,
			0,
			array(
				'menu-item-title'     => 'الإصدارات',
				'menu-item-url'       => get_post_type_archive_link( 'esdar' ),
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'post_type_archive',
				'menu-item-object'    => 'esdar',
			)
		);

		wp_update_nav_menu_item(
			$primary_menu_id,
			0,
			array(
				'menu-item-title'     => 'نوادي القراءة',
				'menu-item-url'       => get_post_type_archive_link( 'reading_clubs' ),
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'post_type_archive',
				'menu-item-object'    => 'reading_clubs',
			)
		);

		if ( $blog_page_id ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'      => 'المدونة',
					'menu-item-object-id'  => $blog_page_id,
					'menu-item-object'     => 'page',
					'menu-item-type'       => 'post_type',
					'menu-item-status'     => 'publish',
				)
			);
		}

		if ( $about_page_id ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'      => 'من نحن',
					'menu-item-object-id'  => $about_page_id,
					'menu-item-object'     => 'page',
					'menu-item-type'       => 'post_type',
					'menu-item-status'     => 'publish',
				)
			);
		}

		if ( $contact_page_id ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'      => 'اتصل بنا',
					'menu-item-object-id'  => $contact_page_id,
					'menu-item-object'     => 'page',
					'menu-item-type'       => 'post_type',
					'menu-item-status'     => 'publish',
				)
			);
		}

		// Assign to location.
		$locations                = get_theme_mod( 'nav_menu_locations' );
		$locations['primary']     = $primary_menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

/**
 * Setup demo customizer settings (Front Page Version)
 * إعدادات الصفحة الرئيسية التجريبية
 */
function nadiim_setup_demo_customizer() {
	// Front Page Settings (New)
	// Topbar
	set_theme_mod( 'home_topbar_enable', true );
	set_theme_mod( 'home_topbar_count', 5 );
	set_theme_mod( 'home_topbar_bg', '#26704A' );
	set_theme_mod( 'home_topbar_text', '#FFFFFF' );
	set_theme_mod( 'home_topbar_source', 'events' );

	// Hero section settings.
	set_theme_mod( 'home_hero_enable', true );
	set_theme_mod( 'home_hero_title', 'مرحباً بكم في نديم' );
	set_theme_mod( 'home_hero_subtitle', 'فضاءٌ هادئ للحوارات الرصينة والإصدارات النافعة ونوادي القراءة الممتعة' );
	set_theme_mod( 'home_hero_cta_text', 'استكشف المحتوى' );
	set_theme_mod( 'home_hero_cta_link', '#dialogues' );
	set_theme_mod( 'home_hero_bg_type', 'gradient' );
	set_theme_mod( 'home_hero_bg_color', '#F6FFF9' );
	set_theme_mod( 'home_hero_overlay_opacity', 0.3 );

	// Dialogues section.
	set_theme_mod( 'home_dialogues_enable', true );
	set_theme_mod( 'home_dialogues_count', 6 );
	set_theme_mod( 'home_dialogues_layout', 'grid' );
	set_theme_mod( 'home_dialogues_source', 'recent' );

	// Releases section.
	set_theme_mod( 'home_releases_enable', true );
	set_theme_mod( 'home_releases_count', 8 );
	set_theme_mod( 'home_releases_layout', 'carousel' );

	// Posts section.
	set_theme_mod( 'home_posts_enable', true );
	set_theme_mod( 'home_posts_count', 3 );
	set_theme_mod( 'home_posts_category', '' );

	// Clubs section.
	set_theme_mod( 'home_clubs_enable', true );
	set_theme_mod( 'home_clubs_count', 3 );

	// Newsletter section.
	set_theme_mod( 'home_newsletter_enable', true );
	set_theme_mod( 'home_newsletter_title', 'اشترك في نشرتنا البريدية' );
	set_theme_mod( 'home_newsletter_desc', 'تلقَّ آخر الأخبار والإصدارات والفعاليات مباشرة في بريدك' );
	set_theme_mod( 'home_newsletter_provider', 'mailchimp' );

	// Old Settings (للتوافق مع المستقبل)
	set_theme_mod( 'nadiim_hero_enable', true );
	set_theme_mod( 'nadiim_hero_title', 'مرحباً بكم في نديم' );
	set_theme_mod( 'nadiim_hero_description', 'منصة ثقافية فكرية تهدف إلى نشر المعرفة وتعزيز الحوار الفكري البناء' );
	set_theme_mod( 'nadiim_hero_button_text', 'استكشف المزيد' );
	set_theme_mod( 'nadiim_hero_button_url', '#' );
	set_theme_mod( 'nadiim_hero_bg_color', '#f8f9fa' );

	// Dialogues section.
	set_theme_mod( 'nadiim_dialogues_enable', true );
	set_theme_mod( 'nadiim_dialogues_title', 'أحدث الحوارات' );
	set_theme_mod( 'nadiim_dialogues_count', 3 );

	// Releases section.
	set_theme_mod( 'nadiim_releases_enable', true );
	set_theme_mod( 'nadiim_releases_title', 'أحدث الإصدارات' );
	set_theme_mod( 'nadiim_releases_count', 4 );

	// Posts section.
	set_theme_mod( 'nadiim_posts_enable', true );
	set_theme_mod( 'nadiim_posts_title', 'آخر المقالات' );
	set_theme_mod( 'nadiim_posts_count', 3 );

	// Clubs section.
	set_theme_mod( 'nadiim_clubs_enable', true );
	set_theme_mod( 'nadiim_clubs_title', 'نوادي القراءة' );
	set_theme_mod( 'nadiim_clubs_count', 2 );

	// Newsletter section.
	set_theme_mod( 'nadiim_newsletter_enable', true );
	set_theme_mod( 'nadiim_newsletter_title', 'اشترك في نشرتنا البريدية' );
	set_theme_mod( 'nadiim_newsletter_description', 'احصل على آخر الأخبار والحوارات والإصدارات مباشرة في بريدك' );

	// About page timeline.
	set_theme_mod(
		'nadiim_timeline_items',
		array(
			array(
				'year'        => '2015',
				'title'       => 'التأسيس',
				'description' => 'تأسست منصة نديم كمبادرة ثقافية لنشر الفكر العربي الأصيل',
			),
			array(
				'year'        => '2017',
				'title'       => 'إطلاق الحوارات',
				'description' => 'بدأنا سلسلة الحوارات الفكرية مع نخبة من المفكرين والأدباء',
			),
			array(
				'year'        => '2019',
				'title'       => 'قسم الإصدارات',
				'description' => 'أطلقنا قسم الإصدارات لنشر الكتب والدراسات المتميزة',
			),
			array(
				'year'        => '2021',
				'title'       => 'نوادي القراءة',
				'description' => 'تم تدشين أول نادي قراءة إلكتروني تفاعلي',
			),
			array(
				'year'        => '2024',
				'title'       => 'التوسع والتطوير',
				'description' => 'نواصل مسيرتنا بتطوير المنصة وإضافة المزيد من المحتوى النوعي',
			),
		)
	);

	// Contact page settings.
	set_theme_mod( 'nadiim_contact_email', 'info@nadiim.com' );
	set_theme_mod( 'nadiim_contact_phone', '+966 12 345 6789' );
	set_theme_mod( 'nadiim_contact_address', 'الرياض، المملكة العربية السعودية' );

	// Footer copyright.
	set_theme_mod( 'nadiim_footer_copyright', 'جميع الحقوق محفوظة © ' . date( 'Y' ) . ' - نديم' );

	// Set homepage.
	$blog_page_id = get_option( 'nadiim_demo_blog_page_id', 0 );
	if ( $blog_page_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $blog_page_id );
		update_option( 'page_on_front', 0 ); // Will use home.php.
	}
}

/**
 * Delete demo content
 */
function nadiim_delete_demo_content() {
	global $wpdb;

	// Get demo user IDs.
	$user_ids = get_option( 'nadiim_demo_user_ids', array() );

	// Delete posts created by demo users.
	$post_types = array( 'howarat', 'esdar', 'reading_clubs', 'post', 'page' );
	foreach ( $post_types as $post_type ) {
		$posts = get_posts(
			array(
				'post_type'      => $post_type,
				'author__in'     => $user_ids,
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		foreach ( $posts as $post_id ) {
			wp_delete_post( $post_id, true );
		}
	}

	// Delete demo pages by title.
	$demo_pages = array( 'من نحن', 'اتصل بنا', 'المدونة' );
	foreach ( $demo_pages as $page_title ) {
		$page = get_page_by_title( $page_title );
		if ( $page ) {
			wp_delete_post( $page->ID, true );
		}
	}

	// Delete demo users.
	foreach ( $user_ids as $user_id ) {
		if ( $user_id !== get_current_user_id() ) { // Don't delete current user.
			wp_delete_user( $user_id );
		}
	}

	// Delete demo menu.
	$menu = wp_get_nav_menu_object( 'القائمة الرئيسية التجريبية' );
	if ( $menu ) {
		wp_delete_nav_menu( $menu->term_id );
	}

	// Reset customizer settings.
	remove_theme_mods();

	// Delete options.
	delete_option( 'nadiim_demo_imported' );
	delete_option( 'nadiim_demo_user_ids' );
	delete_option( 'nadiim_demo_blog_page_id' );
	delete_option( 'nadiim_demo_about_page_id' );
	delete_option( 'nadiim_demo_contact_page_id' );

	// Reset reading settings.
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_for_posts', 0 );
	update_option( 'page_on_front', 0 );
}

/**
 * Set placeholder image for a post
 */
function nadiim_set_placeholder_image( $post_id, $width = 800, $height = 500 ) {
	// Using placeholder service.
	$placeholder_url = "https://via.placeholder.com/{$width}x{$height}/339063/FFFFFF?text=Nadiim";

	// Set as featured image URL in post meta (for demo purposes).
	// In production, you would download and attach the image.
	set_post_thumbnail( $post_id, 0 );
}
