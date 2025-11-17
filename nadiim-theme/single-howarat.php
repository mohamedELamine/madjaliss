<?php
/**
 * قالب الصفحة المفردة للحوارات
 *
 * تجربة المستخدم النهائية:
 * - يشاهد المستخدم صفحة احترافية وهادئة للحوار
 * - هيرو كامل بصورة الحوار، العنوان، التاريخ، والنوع
 * - إذا كان هناك رابط فيديو/صوت، يظهر مشغل مدمج
 * - يقرأ المحتوى الكامل مع مسافات واسعة ومريحة
 * - يرى قائمة المشاركين بصورهم وسيرهم الذاتية
 * - يجد في الـsidebar حوارات مشابهة وروابط ذات صلة
 *
 * التصميم:
 * - ألوان هادئة تعتمد على #339063
 * - مسافات كبيرة بين العناصر
 * - خطوط واضحة ومريحة للقراءة
 * - تأثيرات hover خفيفة وأنيقة
 *
 * @package Nadiim
 * @since 2.0.0
 */

get_header();

// تحميل ملف CSS الخاص بالحوارات
wp_enqueue_style( 'howarat-style', get_template_directory_uri() . '/assets/css/howarat.css', array(), '1.0.0' );

while ( have_posts() ) :
	the_post();

	// جلب البيانات الوصفية
	$dialogue_date     = get_post_meta( get_the_ID(), 'dialogue_date', true );
	$dialogue_type     = get_post_meta( get_the_ID(), 'dialogue_type', true );
	$dialogue_duration = get_post_meta( get_the_ID(), 'dialogue_duration', true );
	$dialogue_media    = get_post_meta( get_the_ID(), 'dialogue_media', true );
	$media_type        = get_post_meta( get_the_ID(), 'dialogue_media_type', true );

	// جلب المشاركين مع معالجة آمنة
	$participants_json = get_post_meta( get_the_ID(), 'dialogue_participants', true );
	$participants      = array();

	if ( ! empty( $participants_json ) && is_string( $participants_json ) ) {
		// تجربة فك التشفير بشكل آمن
		$decoded = json_decode( $participants_json, true );
		// التحقق من أن النتيجة مصفوفة صالحة وليست null أو false
		if ( ! is_null( $decoded ) && is_array( $decoded ) ) {
			$participants = $decoded;
		}
	}

	// Debug: طباعة البيانات للتحقق (يمكن إزالة هذا لاحقاً)
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		echo '<!-- DEBUG - Participants JSON: ' . esc_html( $participants_json ) . ' -->';
		echo '<!-- DEBUG - Participants Array: ' . esc_html( print_r( $participants, true ) ) . ' -->';
	}

	// تحديد نوع الميديا
	if ( ! $media_type && $dialogue_media ) {
		if ( strpos( $dialogue_media, 'youtube' ) !== false || strpos( $dialogue_media, 'youtu.be' ) !== false ) {
			$media_type = 'video';
		} elseif ( strpos( $dialogue_media, '.mp3' ) !== false || strpos( $dialogue_media, 'audio' ) !== false ) {
			$media_type = 'audio';
		} else {
			$media_type = 'podcast';
		}
	}

	// تنسيق التاريخ
	$formatted_date = $dialogue_date ? date_i18n( 'j F، Y', strtotime( $dialogue_date ) ) : get_the_date();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'howarat-single' ); ?>>

		<!-- قسم الهيرو -->
		<div class="howarat-hero">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="howarat-hero-image">
					<?php the_post_thumbnail( 'full' ); ?>
					<div class="howarat-hero-overlay"></div>
				</div>
			<?php endif; ?>

			<div class="howarat-hero-content">
				<div class="howarat-hero-container">
					<!-- شارة التاريخ -->
					<div class="howarat-date-badge">
						<?php
						$badge_date = ! empty( $dialogue_date ) ? $dialogue_date : current_time( 'mysql' );
						?>
						<span class="date-day"><?php echo date_i18n( 'd', strtotime( $badge_date ) ); ?></span>
						<span class="date-month"><?php echo date_i18n( 'M', strtotime( $badge_date ) ); ?></span>
					</div>

					<!-- شارة النوع -->
					<?php if ( $media_type ) : ?>
						<div class="howarat-type-badge">
							<?php
							$type_icons = array(
								'video'   => '▶️ فيديو',
								'audio'   => '🎵 صوت',
								'podcast' => '🎧 بودكاست',
							);
							echo isset( $type_icons[ $media_type ] ) ? $type_icons[ $media_type ] : '📻 حوار';
							?>
						</div>
					<?php endif; ?>

					<!-- العنوان -->
					<h1 class="howarat-title"><?php the_title(); ?></h1>

					<!-- البيانات الوصفية -->
					<div class="howarat-meta">
						<span class="howarat-meta-item">
							<span class="meta-icon">📅</span>
							<?php echo esc_html( $formatted_date ); ?>
						</span>

						<?php if ( $dialogue_duration ) : ?>
							<span class="howarat-meta-item">
								<span class="meta-icon">⏱️</span>
								<?php echo esc_html( $dialogue_duration ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $dialogue_type ) : ?>
							<span class="howarat-meta-item">
								<span class="meta-icon">📻</span>
								<?php echo esc_html( $dialogue_type ); ?>
							</span>
						<?php endif; ?>
					</div>

					<!-- أزرار CTA -->
					<div class="howarat-cta-buttons">
						<?php if ( $dialogue_media ) : ?>
							<a href="#player" class="howarat-cta-btn howarat-cta-primary">
								<?php echo $media_type === 'video' ? 'شاهد الحوار' : 'استمع للحوار'; ?>
								<span class="cta-arrow">↓</span>
							</a>
						<?php endif; ?>
						<a href="#content" class="howarat-cta-btn howarat-cta-secondary">
							قراءة التلخيص
							<span class="cta-arrow">↓</span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- المحتوى الرئيسي -->
		<div class="howarat-main-container">
			<div class="howarat-content-wrapper">

				<!-- المشغل (إذا كان هناك رابط فيديو/صوت) -->
				<?php if ( $dialogue_media ) : ?>
					<div id="player" class="howarat-player-section">
						<h2 class="section-title">شاهد أو استمع للحوار</h2>

						<?php if ( $media_type === 'video' ) : ?>
							<!-- مشغل فيديو YouTube -->
							<div class="howarat-video-player">
								<?php
								// استخدام WordPress oEmbed
								$embed = wp_oembed_get( $dialogue_media );
								if ( $embed ) {
									echo $embed;
								} else {
									echo '<p>لم يتمكن من تحميل الفيديو. <a href="' . esc_url( $dialogue_media ) . '" target="_blank">شاهده على YouTube</a></p>';
								}
								?>
							</div>
						<?php elseif ( $media_type === 'audio' ) : ?>
							<!-- مشغل صوت -->
							<div class="howarat-audio-player">
								<audio controls>
									<source src="<?php echo esc_url( $dialogue_media ); ?>" type="audio/mpeg">
									متصفحك لا يدعم تشغيل الملفات الصوتية.
								</audio>
							</div>
						<?php else : ?>
							<!-- رابط خارجي (بودكاست) -->
							<div class="howarat-external-player">
								<a href="<?php echo esc_url( $dialogue_media ); ?>" target="_blank" class="external-link-btn">
									استمع على المنصة الأصلية
									<span class="external-icon">↗</span>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<!-- ملخص الحوار -->
				<?php if ( has_excerpt() ) : ?>
					<div id="summary" class="howarat-summary-section">
						<h2 class="section-title">ملخص الحوار</h2>
						<div class="howarat-summary-content">
							<?php the_excerpt(); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- المحتوى الكامل -->
				<div id="content" class="howarat-content-section">
					<h2 class="section-title">نص الحوار الكامل</h2>
					<div class="howarat-content-body">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- المشاركون -->
				<?php if ( ! empty( $participants ) && is_array( $participants ) ) : ?>
					<div id="participants" class="howarat-participants-section">
						<h2 class="section-title">المشاركون في الحوار</h2>
						<div class="howarat-participants-grid">
							<?php foreach ( $participants as $participant ) : ?>
								<?php
								// الحصول على الصورة
								$photo_url = '';
								if ( isset( $participant['photo_id'] ) && $participant['photo_id'] > 0 ) {
									$photo_url = wp_get_attachment_image_url( $participant['photo_id'], 'thumbnail' );
								}

								// إذا كان مستخدماً، محاولة جلب صورة الملف الشخصي
								if ( ! $photo_url && isset( $participant['type'] ) && $participant['type'] === 'user' && isset( $participant['id'] ) ) {
									$photo_url = get_avatar_url( $participant['id'], array( 'size' => 128 ) );
								}
								?>

								<?php
								// الحصول على القيم بشكل آمن (التأكد من أنها strings وليست arrays)
								$p_name = isset( $participant['name'] ) && is_string( $participant['name'] ) ? $participant['name'] : '';
								$p_role = isset( $participant['role'] ) && is_string( $participant['role'] ) ? $participant['role'] : '';
								$p_bio  = isset( $participant['bio'] ) && is_string( $participant['bio'] ) ? $participant['bio'] : '';
								$p_link = isset( $participant['link'] ) && is_string( $participant['link'] ) ? $participant['link'] : '';
								?>

								<div class="participant-card">
									<?php if ( $photo_url ) : ?>
										<div class="participant-photo">
											<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $p_name ); ?>">
										</div>
									<?php endif; ?>

									<div class="participant-info">
										<h3 class="participant-name">
											<?php echo esc_html( $p_name ); ?>
											<?php if ( ! empty( $p_link ) ) : ?>
												<a href="<?php echo esc_url( $p_link ); ?>" target="_blank" class="participant-link-btn" title="تعرف عليه">
													<span class="link-icon">↗</span>
												</a>
											<?php endif; ?>
										</h3>

										<?php if ( ! empty( $p_role ) ) : ?>
											<p class="participant-role"><?php echo esc_html( $p_role ); ?></p>
										<?php endif; ?>

										<?php if ( ! empty( $p_bio ) ) : ?>
											<p class="participant-bio"><?php echo esc_html( $p_bio ); ?></p>
										<?php endif; ?>
									</div>
								</div>

							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- التنقل بين المنشورات -->
				<div class="howarat-navigation">
					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();
					?>

					<?php if ( $prev_post ) : ?>
						<a href="<?php echo get_permalink( $prev_post ); ?>" class="nav-prev">
							<span class="nav-arrow">→</span>
							<span class="nav-label">الحوار السابق</span>
							<span class="nav-title"><?php echo get_the_title( $prev_post ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $next_post ) : ?>
						<a href="<?php echo get_permalink( $next_post ); ?>" class="nav-next">
							<span class="nav-label">الحوار التالي</span>
							<span class="nav-title"><?php echo get_the_title( $next_post ); ?></span>
							<span class="nav-arrow">←</span>
						</a>
					<?php endif; ?>
				</div>

			</div><!-- .howarat-content-wrapper -->

			<!-- Sidebar -->
			<aside class="howarat-sidebar">

				<!-- المشاركون (نسخة مصغرة) -->
				<?php if ( ! empty( $participants ) && is_array( $participants ) ) : ?>
					<div class="sidebar-widget">
						<h3 class="widget-title">المشاركون</h3>
						<div class="sidebar-participants">
							<?php foreach ( $participants as $participant ) : ?>
								<?php
								// جلب الصورة
								$photo_url = '';
								if ( isset( $participant['photo_id'] ) && $participant['photo_id'] > 0 ) {
									$photo_url = wp_get_attachment_image_url( $participant['photo_id'], 'thumbnail' );
								}
								if ( ! $photo_url && isset( $participant['type'] ) && $participant['type'] === 'user' && isset( $participant['id'] ) ) {
									$photo_url = get_avatar_url( $participant['id'], array( 'size' => 64 ) );
								}

								// الحصول على القيم بشكل آمن
								$sidebar_name = isset( $participant['name'] ) && is_string( $participant['name'] ) ? $participant['name'] : '';
								$sidebar_role = isset( $participant['role'] ) && is_string( $participant['role'] ) ? $participant['role'] : '';
								?>
								<div class="sidebar-participant-item">
									<?php if ( $photo_url ) : ?>
										<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $sidebar_name ); ?>" class="sidebar-participant-photo">
									<?php endif; ?>
									<div class="sidebar-participant-text">
										<p class="sidebar-participant-name"><?php echo esc_html( $sidebar_name ); ?></p>
										<?php if ( ! empty( $sidebar_role ) ) : ?>
											<p class="sidebar-participant-role"><?php echo esc_html( $sidebar_role ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- حوارات مشابهة -->
				<?php
				$related_args = array(
					'post_type'      => 'howarat',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'orderby'        => 'rand',
				);
				$related_query = new WP_Query( $related_args );

				if ( $related_query->have_posts() ) :
					?>
					<div class="sidebar-widget">
						<h3 class="widget-title">حوارات مشابهة</h3>
						<div class="sidebar-related-posts">
							<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
								<article class="sidebar-related-item">
									<?php if ( has_post_thumbnail() ) : ?>
										<a href="<?php the_permalink(); ?>" class="related-thumbnail">
											<?php the_post_thumbnail( 'thumbnail' ); ?>
										</a>
									<?php endif; ?>
									<div class="related-content">
										<h4 class="related-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h4>
										<p class="related-date"><?php echo get_the_date(); ?></p>
									</div>
								</article>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- اقرأ أيضاً (من المدونة) -->
				<?php
				$blog_args = array(
					'post_type'      => 'post',
					'posts_per_page' => 3,
					'orderby'        => 'date',
					'order'          => 'DESC',
				);
				$blog_query = new WP_Query( $blog_args );

				if ( $blog_query->have_posts() ) :
					?>
					<div class="sidebar-widget">
						<h3 class="widget-title">اقرأ أيضاً</h3>
						<div class="sidebar-blog-posts">
							<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
								<article class="sidebar-blog-item">
									<h4 class="blog-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
									<p class="blog-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 12, '...' ); ?></p>
								</article>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>

			</aside><!-- .howarat-sidebar -->

		</div><!-- .howarat-main-container -->

	</article><!-- #post-<?php the_ID(); ?> -->

<?php
endwhile;

get_footer();
