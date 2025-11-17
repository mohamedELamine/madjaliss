<?php
/**
 * قالب أرشيف الحوارات
 *
 * تجربة المستخدم النهائية:
 * - يرى المستخدم عنواناً واضحاً "جميع الحوارات"
 * - وصف مختصر عن أرشيف الحوارات
 * - شبكة من 3 أعمدة تعرض بطاقات الحوارات (عمود واحد على الموبايل)
 * - كل بطاقة تحتوي على: صورة بنسبة 16:9، تاريخ، نوع، عنوان، مقتطف، زر "عرض الحوار"
 * - pagination للتنقل بين الصفحات
 * - الهيكل جاهز للفلترة في المستقبل
 *
 * @package Nadiim
 * @since 2.0.0
 */

get_header();

// تحميل ملف CSS الخاص بالحوارات
wp_enqueue_style( 'howarat-style', get_template_directory_uri() . '/assets/css/howarat.css', array(), '1.0.0' );
?>

<main id="primary" class="site-main">

	<!-- عنوان الصفحة -->
	<div class="archive-header">
		<div class="archive-header-container">
			<h1 class="archive-title">جميع الحوارات</h1>
			<p class="archive-description">
				مجموعة من الحوارات والمقابلات الفكرية الهادفة، حول مواضيع متنوعة في الأدب والفكر والثقافة.
			</p>
		</div>
	</div>

	<div class="archive-container">

		<!-- مكان للفلاتر المستقبلية -->
		<!-- يمكن إضافة فلاتر هنا لاحقاً: فلتر حسب النوع، الموضوع، التاريخ، إلخ -->
		<div id="howarat-filters" class="howarat-filters">
			<!-- الفلاتر ستضاف هنا لاحقاً -->
		</div>

		<!-- شبكة الحوارات -->
		<?php if ( have_posts() ) : ?>

			<div class="howarat-grid">
				<?php
				while ( have_posts() ) :
					the_post();

					// جلب البيانات الوصفية
					$dialogue_date     = get_post_meta( get_the_ID(), 'dialogue_date', true );
					$dialogue_type     = get_post_meta( get_the_ID(), 'dialogue_type', true );
					$dialogue_duration = get_post_meta( get_the_ID(), 'dialogue_duration', true );
					$dialogue_media    = get_post_meta( get_the_ID(), 'dialogue_media', true );
					$media_type        = get_post_meta( get_the_ID(), 'dialogue_media_type', true );

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

					// جلب المشاركين (يدعم Array و JSON)
					$participants_data = get_post_meta( get_the_ID(), 'dialogue_participants', true );
					$participants      = array();
					if ( ! empty( $participants_data ) ) {
						if ( is_array( $participants_data ) ) {
							$participants = $participants_data;
						} elseif ( is_string( $participants_data ) ) {
							$decoded = json_decode( $participants_data, true );
							if ( ! is_null( $decoded ) && is_array( $decoded ) ) {
								$participants = $decoded;
							}
						}
					}
					?>

					<article <?php post_class( 'howarat-card' ); ?>>

						<!-- صورة الحوار -->
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="howarat-card-image">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium_large' ); ?>
								</a>

								<!-- شارة النوع -->
								<?php if ( $media_type ) : ?>
									<div class="card-type-badge">
										<?php
										$type_icons = array(
											'video'   => '▶️',
											'audio'   => '🎵',
											'podcast' => '🎧',
										);
										echo isset( $type_icons[ $media_type ] ) ? $type_icons[ $media_type ] : '📻';
										?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<!-- محتوى البطاقة -->
						<div class="howarat-card-content">

							<!-- التاريخ -->
							<div class="howarat-card-date">
								📅 <?php echo esc_html( $formatted_date ); ?>
							</div>

							<!-- نوع الحوار -->
							<?php if ( $dialogue_type ) : ?>
								<div class="howarat-card-type">
									<?php echo esc_html( $dialogue_type ); ?>
								</div>
							<?php endif; ?>

							<!-- العنوان -->
							<h2 class="howarat-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<!-- المقتطف -->
							<div class="howarat-card-excerpt">
								<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
							</div>

							<!-- المشاركون -->
							<?php if ( ! empty( $participants ) && is_array( $participants ) ) : ?>
								<div class="howarat-card-participants">
									<span class="participants-label">👥 المشاركون:</span>
									<?php
									$participant_names = array();
									foreach ( $participants as $participant ) {
										// استخدام الدالة المساعدة للحصول على الاسم بأمان
										$name = nadiim_get_participant_field( $participant, 'name' );
										if ( ! empty( $name ) ) {
											$participant_names[] = esc_html( $name );
										}
									}
									echo implode( '، ', $participant_names );
									?>
								</div>
							<?php endif; ?>

							<!-- البيانات الإضافية -->
							<?php if ( $dialogue_duration ) : ?>
								<div class="howarat-card-duration">
									⏱️ <?php echo esc_html( $dialogue_duration ); ?>
								</div>
							<?php endif; ?>

							<!-- زر "عرض الحوار" -->
							<a href="<?php the_permalink(); ?>" class="howarat-card-btn">
								عرض الحوار
								<span class="btn-arrow">←</span>
							</a>

						</div><!-- .howarat-card-content -->

					</article><!-- .howarat-card -->

				<?php endwhile; ?>
			</div><!-- .howarat-grid -->

			<!-- Pagination -->
			<div class="archive-pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 2,
						'prev_text'          => __( '→ السابق', 'nadiim' ),
						'next_text'          => __( 'التالي ←', 'nadiim' ),
						'screen_reader_text' => __( 'التنقل بين الصفحات', 'nadiim' ),
					)
				);
				?>
			</div>

		<?php else : ?>

			<!-- رسالة عند عدم وجود حوارات -->
			<div class="no-results">
				<div class="no-results-icon">🔍</div>
				<h2>لا توجد حوارات حالياً</h2>
				<p>يبدو أنه لا توجد حوارات متاحة في الوقت الحالي. تفقد الصفحة لاحقاً للاطلاع على الحوارات الجديدة.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">العودة للرئيسية</a>
			</div>

		<?php endif; ?>

	</div><!-- .archive-container -->

</main><!-- #primary -->

<?php
get_footer();
