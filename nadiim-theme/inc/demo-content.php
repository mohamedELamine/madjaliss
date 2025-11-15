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
 * Create demo dialogues
 */
function nadiim_create_demo_dialogues( $users ) {
	$dialogues = array(
		array(
			'title'       => 'حوار حول مستقبل الفكر العربي المعاصر',
			'content'     => '<p>في هذا الحوار الثري، نناقش التحديات التي تواجه الفكر العربي في العصر الحديث، ونستكشف السبل الممكنة لتجديد الخطاب الفكري والثقافي.</p><p>تطرق الحوار إلى عدة محاور رئيسية، منها: دور المثقف في المجتمع، أزمة المنهج في الدراسات العربية، وضرورة الانفتاح على التجارب الإنسانية المختلفة مع الحفاظ على الهوية.</p><p>كما ناقشنا أهمية القراءة النقدية للتراث، وعدم الوقوع في فخ التقديس أو الإسقاط، بل التعامل مع التراث كمنجز إنساني قابل للدراسة والتحليل.</p>',
			'media_type'  => 'video',
			'media_url'   => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
			'date'        => '2024-10-15',
			'location'    => 'القاهرة - مصر',
			'transcript'  => 'هذا نص تجريبي للحوار الكامل. في الحوار الحقيقي، سيكون هنا النص الكامل للحوار أو المقابلة...',
			'participants' => array( $users[0], $users[1] ),
			'type'        => 'مقابلة',
			'topic'       => 'فكر',
		),
		array(
			'title'       => 'ندوة: الأدب العربي بين الأصالة والمعاصرة',
			'content'     => '<p>ندوة علمية شارك فيها نخبة من الأدباء والنقاد، تناولت العلاقة بين التراث الأدبي العربي والأشكال الأدبية الحديثة.</p><p>ناقش المشاركون كيفية الاستفادة من التراث دون الوقوع في التقليد، وأهمية التجديد في الأشكال والمضامين مع الحفاظ على الروح العربية للنص.</p>',
			'media_type'  => 'audio',
			'media_url'   => 'https://soundcloud.com/example/track',
			'date'        => '2024-09-20',
			'location'    => 'بيروت - لبنان',
			'transcript'  => 'نص الندوة الكامل...',
			'participants' => array( $users[1], $users[2], $users[3] ),
			'type'        => 'ندوة',
			'topic'       => 'أدب',
		),
		array(
			'title'       => 'حوار مكتوب: قراءة في مشروع محمود شاكر الفكري',
			'content'     => '<p><strong>السؤال الأول: ما الذي يميز مشروع محمود شاكر الفكري؟</strong></p><p>الإجابة: يتميز مشروع الأستاذ محمود شاكر بعمق التحليل ودقة التوثيق، وبنظرته الشاملة للتراث العربي...</p><p><strong>السؤال الثاني: كيف يمكن الاستفادة من منهجه اليوم؟</strong></p><p>الإجابة: منهج محمود شاكر في قراءة النصوص وتحليلها يقدم لنا نموذجاً للقراءة العميقة...</p>',
			'media_type'  => 'written',
			'media_url'   => '',
			'date'        => '2024-11-01',
			'location'    => '',
			'transcript'  => '',
			'participants' => array( $users[0] ),
			'type'        => 'حوار مكتوب',
			'topic'       => 'تراث',
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
 * Create demo releases
 */
function nadiim_create_demo_releases( $users ) {
	$releases = array(
		array(
			'title'          => 'مداخل إعجاز القرآن',
			'content'        => '<p>كتاب رائد في دراسة إعجاز القرآن الكريم، يقدم مداخل جديدة لفهم هذا الموضوع الشائك من خلال منهج علمي دقيق.</p><p>يتناول الكتاب الإعجاز البياني والبلاغي واللغوي، مع دراسات تطبيقية على آيات قرآنية مختارة.</p>',
			'author_name'    => 'محمود شاكر',
			'publish_date'   => '1976-01-01',
			'isbn'           => '978-1234567890',
			'pages'          => 450,
			'publisher'      => 'دار المعارف',
			'pdf_url'        => '',
			'purchase_url'   => '#',
			'preview_url'    => '#',
			'type'           => 'كتاب',
			'topic'          => 'دراسات قرآنية',
		),
		array(
			'title'          => 'أباطيل وأسمار',
			'content'        => '<p>مجموعة من المقالات النقدية التي تناقش قضايا فكرية وأدبية معاصرة بأسلوب رصين وحجة قوية.</p>',
			'author_name'    => 'محمود شاكر',
			'publish_date'   => '1972-06-15',
			'isbn'           => '978-1234567891',
			'pages'          => 320,
			'publisher'      => 'مكتبة الخانجي',
			'pdf_url'        => '',
			'purchase_url'   => '#',
			'preview_url'    => '#',
			'type'           => 'كتاب',
			'topic'          => 'نقد أدبي',
		),
		array(
			'title'          => 'تجديد الفكر الديني في الإسلام',
			'content'        => '<p>دراسة معمقة حول ضرورة تجديد الفكر الديني وفق منهجية علمية تراعي الأصول وتستجيب لتحديات العصر.</p>',
			'author_name'    => 'أحمد منصور',
			'publish_date'   => '2020-03-10',
			'isbn'           => '978-1234567892',
			'pages'          => 280,
			'publisher'      => 'دار الفكر المعاصر',
			'pdf_url'        => '',
			'purchase_url'   => '#',
			'preview_url'    => '#',
			'type'           => 'بحث',
			'topic'          => 'فكر إسلامي',
		),
		array(
			'title'          => 'معجم المصطلحات الأدبية المعاصرة',
			'content'        => '<p>معجم شامل يضم أهم المصطلحات الأدبية والنقدية المعاصرة مع شرح وافٍ لكل مصطلح.</p>',
			'author_name'    => 'فاطمة علي',
			'publish_date'   => '2019-11-22',
			'isbn'           => '978-1234567893',
			'pages'          => 520,
			'publisher'      => 'دار الكتب العلمية',
			'pdf_url'        => '',
			'purchase_url'   => '#',
			'preview_url'    => '#',
			'type'           => 'مرجع',
			'topic'          => 'أدب',
		),
	);

	foreach ( $releases as $release ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $release['title'],
				'post_content' => $release['content'],
				'post_type'    => 'esdar',
				'post_status'  => 'publish',
				'post_author'  => $users[0],
			)
		);

		if ( $post_id ) {
			// Set featured image (placeholder).
			nadiim_set_placeholder_image( $post_id, 600, 900 );

			// Set meta fields.
			update_post_meta( $post_id, 'esdar_author', $release['author_name'] );
			update_post_meta( $post_id, 'esdar_publish_date', $release['publish_date'] );
			update_post_meta( $post_id, 'esdar_isbn', $release['isbn'] );
			update_post_meta( $post_id, 'esdar_pages', $release['pages'] );
			update_post_meta( $post_id, 'esdar_publisher', $release['publisher'] );
			update_post_meta( $post_id, 'esdar_pdf_url', $release['pdf_url'] );
			update_post_meta( $post_id, 'esdar_purchase_url', $release['purchase_url'] );
			update_post_meta( $post_id, 'esdar_preview_url', $release['preview_url'] );

			// Set terms.
			wp_set_object_terms( $post_id, $release['type'], 'release_type' );
			wp_set_object_terms( $post_id, $release['topic'], 'release_topic' );
		}
	}
}

/**
 * Create demo reading clubs
 */
function nadiim_create_demo_reading_clubs( $users ) {
	// Get first release for linking.
	$releases = get_posts(
		array(
			'post_type'      => 'esdar',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	$clubs = array(
		array(
			'title'         => 'نادي قراءة التراث',
			'content'       => '<p>نادٍ متخصص في قراءة ومناقشة كتب التراث العربي والإسلامي، نجتمع كل أسبوعين لمناقشة كتاب مختار.</p><p>الهدف من النادي هو إحياء التراث وفهمه فهماً معاصراً، والاستفادة من كنوز الفكر الإسلامي في حياتنا.</p>',
			'supervisor'    => $users[0],
			'members'       => array( $users[1], $users[2] ),
			'current_book'  => ! empty( $releases ) ? $releases[0] : 0,
			'schedule'      => 'كل يوم جمعة الساعة 8 مساءً',
			'meeting_url'   => 'https://zoom.us/j/123456789',
			'join_url'      => '#',
			'max_members'   => 20,
		),
		array(
			'title'         => 'نادي الأدب المعاصر',
			'content'       => '<p>نادي قراءة مخصص للأدب العربي المعاصر، نناقش الروايات والدواوين الشعرية الحديثة.</p>',
			'supervisor'    => $users[1],
			'members'       => array( $users[2], $users[3] ),
			'current_book'  => ! empty( $releases ) ? $releases[0] : 0,
			'schedule'      => 'كل يوم سبت الساعة 7 مساءً',
			'meeting_url'   => 'https://meet.google.com/abc-defg-hij',
			'join_url'      => '#',
			'max_members'   => 15,
		),
	);

	foreach ( $clubs as $club ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $club['title'],
				'post_content' => $club['content'],
				'post_type'    => 'reading_clubs',
				'post_status'  => 'publish',
				'post_author'  => $club['supervisor'],
			)
		);

		if ( $post_id ) {
			// Set featured image (placeholder).
			nadiim_set_placeholder_image( $post_id, 800, 500 );

			// Set meta fields.
			update_post_meta( $post_id, 'club_supervisor', $club['supervisor'] );
			update_post_meta( $post_id, 'club_members', $club['members'] );
			update_post_meta( $post_id, 'club_current_book', $club['current_book'] );
			update_post_meta( $post_id, 'club_schedule', $club['schedule'] );
			update_post_meta( $post_id, 'club_meeting_url', $club['meeting_url'] );
			update_post_meta( $post_id, 'club_join_url', $club['join_url'] );
			update_post_meta( $post_id, 'club_max_members', $club['max_members'] );
		}
	}
}

/**
 * Create demo posts
 */
function nadiim_create_demo_posts( $users ) {
	$posts = array(
		array(
			'title'   => 'أهمية القراءة في بناء الشخصية',
			'content' => '<p>القراءة غذاء الروح والعقل، وهي المفتاح الأول لبناء شخصية متوازنة وواعية. في هذا المقال نستكشف الأبعاد المختلفة لأهمية القراءة.</p><p>من خلال القراءة، نكتسب المعرفة ونوسع آفاقنا، ونتعرف على تجارب الآخرين وثقافاتهم. القراءة ليست مجرد هواية، بل هي أسلوب حياة ومنهج تفكير.</p>',
			'author'  => $users[0],
		),
		array(
			'title'   => 'مراجعة كتاب: مداخل إعجاز القرآن',
			'content' => '<p>في هذه المراجعة نتناول كتاب "مداخل إعجاز القرآن" للأستاذ محمود شاكر، وهو من أهم الكتب في هذا المجال.</p><p>يتميز الكتاب بالعمق والدقة، ويقدم منهجاً علمياً في دراسة الإعجاز القرآني بعيداً عن المبالغات...</p>',
			'author'  => $users[1],
		),
		array(
			'title'   => 'التراث والمعاصرة: جدلية مستمرة',
			'content' => '<p>العلاقة بين التراث والمعاصرة من القضايا الشائكة التي تثير جدلاً واسعاً في الأوساط الفكرية والثقافية.</p>',
			'author'  => $users[2],
		),
		array(
			'title'   => 'منهجية البحث العلمي في الدراسات الأدبية',
			'content' => '<p>البحث العلمي في الأدب يتطلب منهجية دقيقة وأدوات علمية محددة. في هذا المقال نستعرض أهم المناهج البحثية.</p>',
			'author'  => $users[3],
		),
		array(
			'title'   => 'دور المثقف في المجتمع المعاصر',
			'content' => '<p>المثقف له دور محوري في توجيه المجتمع وتنويره، لكن هذا الدور يواجه تحديات كبيرة في عصرنا الحالي.</p>',
			'author'  => $users[0],
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
 * Setup demo customizer settings
 */
function nadiim_setup_demo_customizer() {
	// Hero section settings.
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
