<?php
/**
 * قالب أرشيف الفعاليات
 *
 * تجربة المستخدم النهائية:
 * - يشاهد المستخدم جميع الفعاليات بتصميم بطاقات جميل
 * - يمكن التصفية حسب الحالة (قادمة، جارية، منتهية)
 * - يمكن التصفية حسب النوع (حضوري، عبر الإنترنت)
 * - البحث النصي في الفعاليات
 * - ترتيب حسب التاريخ
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

// تحميل ملف CSS الخاص بالفعاليات
wp_enqueue_style( 'events-style', get_template_directory_uri() . '/assets/css/events.css', array(), '1.0.0' );

// تحميل ملف JavaScript الخاص بالفعاليات
wp_enqueue_script( 'events-archive', get_template_directory_uri() . '/assets/js/events-archive.js', array( 'jquery' ), '1.0.0', true );
?>

<div class="events-archive">

	<!-- Header -->
	<div class="events-archive-header">
		<div class="container">
			<h1 class="archive-title">
				<?php
				if ( is_tax() ) {
					single_term_title();
				} else {
					echo 'الفعاليات';
				}
				?>
			</h1>
			<?php if ( is_tax() && term_description() ) : ?>
				<div class="archive-description">
					<?php echo term_description(); ?>
				</div>
			<?php else : ?>
				<p class="archive-description">
					تعرّف على جميع فعالياتنا القادمة والسابقة. سجّل حضورك وكن جزءاً من مجتمعنا الثقافي.
				</p>
			<?php endif; ?>
		</div>
	</div>

	<!-- Filters -->
	<div class="events-filters">
		<div class="container">
			<div class="filters-grid">

				<!-- تصفية حسب الحالة -->
				<div class="filter-group">
					<label class="filter-label">الحالة</label>
					<select class="filter-select" id="filter-status">
						<option value="">جميع الفعاليات</option>
						<option value="upcoming">قادمة</option>
						<option value="ongoing">جارية</option>
						<option value="ended">منتهية</option>
					</select>
				</div>

				<!-- تصفية حسب النوع -->
				<div class="filter-group">
					<label class="filter-label">النوع</label>
					<select class="filter-select" id="filter-type">
						<option value="">جميع الأنواع</option>
						<option value="online">عبر الإنترنت</option>
						<option value="offline">حضوري</option>
					</select>
				</div>

				<!-- تصفية حسب التاريخ -->
				<div class="filter-group">
					<label class="filter-label">الترتيب</label>
					<select class="filter-select" id="filter-order">
						<option value="asc">الأقرب أولاً</option>
						<option value="desc">الأحدث أولاً</option>
					</select>
				</div>

				<!-- البحث -->
				<div class="filter-group">
					<label class="filter-label">البحث</label>
					<input type="text" class="filter-input" id="filter-search" placeholder="ابحث في الفعاليات...">
				</div>

			</div>

			<div class="filters-actions">
				<button class="filter-btn filter-btn-reset" id="reset-filters">إعادة تعيين</button>
			</div>
		</div>
	</div>

	<!-- Events Grid -->
	<div class="events-archive-content">
		<div class="container">

			<?php if ( have_posts() ) : ?>

				<div class="events-grid" id="events-grid">

					<?php
					while ( have_posts() ) :
						the_post();

						// جلب البيانات الوصفية
						$event_date     = get_post_meta( get_the_ID(), 'event_date', true );
						$event_time     = get_post_meta( get_the_ID(), 'event_time', true );
						$event_location = get_post_meta( get_the_ID(), 'event_location', true );
						$event_type     = get_post_meta( get_the_ID(), 'event_type', true );
						$event_status   = get_post_meta( get_the_ID(), 'event_status', true );
						$event_link     = get_post_meta( get_the_ID(), 'event_link', true );

						// تحديد الحالة تلقائياً
						if ( ! $event_status && $event_date ) {
							$current_datetime = current_time( 'timestamp' );
							$event_timestamp  = strtotime( $event_date . ' ' . $event_time );
							$event_status     = ( $event_timestamp > $current_datetime ) ? 'upcoming' : 'ended';
						}

						// تنسيق التاريخ
						$formatted_date = $event_date ? date_i18n( 'j F، Y', strtotime( $event_date ) ) : '';
						$formatted_time = $event_time ? date_i18n( 'g:i A', strtotime( $event_time ) ) : '';
						?>

						<article class="event-card" data-status="<?php echo esc_attr( $event_status ); ?>" data-type="<?php echo esc_attr( $event_type ); ?>">

							<!-- صورة الفعالية -->
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="event-card-image">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'nadiim-card' ); ?>
									</a>

									<!-- شارة الحالة -->
									<span class="event-card-status <?php echo esc_attr( $event_status ); ?>">
										<?php
										$status_labels = array(
											'upcoming' => 'قادمة',
											'ongoing'  => 'جارية',
											'ended'    => 'انتهت',
										);
										echo isset( $status_labels[ $event_status ] ) ? $status_labels[ $event_status ] : 'فعالية';
										?>
									</span>

									<!-- شارة النوع -->
									<?php if ( $event_type ) : ?>
										<span class="event-card-type">
											<?php echo $event_type === 'online' ? '💻 أونلاين' : '📍 حضوري'; ?>
										</span>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<!-- محتوى الفعالية -->
							<div class="event-card-content">

								<!-- التاريخ والوقت -->
								<div class="event-card-meta">
									<?php if ( $formatted_date ) : ?>
										<span class="event-meta-item">
											<span class="meta-icon">📅</span>
											<?php echo esc_html( $formatted_date ); ?>
										</span>
									<?php endif; ?>

									<?php if ( $formatted_time ) : ?>
										<span class="event-meta-item">
											<span class="meta-icon">🕐</span>
											<?php echo esc_html( $formatted_time ); ?>
										</span>
									<?php endif; ?>
								</div>

								<!-- العنوان -->
								<h3 class="event-card-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>

								<!-- المقتطف -->
								<?php if ( has_excerpt() ) : ?>
									<div class="event-card-excerpt">
										<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
									</div>
								<?php endif; ?>

								<!-- الفوتر -->
								<div class="event-card-footer">
									<?php if ( $event_location ) : ?>
										<span class="event-location">
											<span class="location-icon">📍</span>
											<?php echo esc_html( wp_trim_words( $event_location, 3, '...' ) ); ?>
										</span>
									<?php endif; ?>

									<a href="<?php the_permalink(); ?>" class="event-read-more">
										التفاصيل
										<span class="arrow">←</span>
									</a>
								</div>

							</div>

						</article>

					<?php endwhile; ?>

				</div>

				<!-- Pagination -->
				<div class="events-pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'           => 2,
							'prev_text'          => '→ السابق',
							'next_text'          => 'التالي ←',
							'screen_reader_text' => 'التنقل بين الصفحات',
						)
					);
					?>
				</div>

			<?php else : ?>

				<!-- لا توجد فعاليات -->
				<div class="no-events">
					<div class="no-events-icon">📅</div>
					<h2>لا توجد فعاليات</h2>
					<p>لم نعثر على أي فعاليات تطابق معايير البحث.</p>
					<a href="<?php echo get_post_type_archive_link( 'events' ); ?>" class="btn-primary">
						عرض جميع الفعاليات
					</a>
				</div>

			<?php endif; ?>

		</div>
	</div>

</div><!-- .events-archive -->

<?php get_footer(); ?>
