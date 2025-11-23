<?php
/**
 * قالب الصفحة المفردة للفعاليات
 *
 * تجربة المستخدم النهائية:
 * - يشاهد المستخدم صفحة احترافية للفعالية
 * - هيرو كامل بصورة الفعالية، العنوان، التاريخ، والمكان
 * - عداد تنازلي للفعاليات القادمة
 * - خريطة للموقع إذا كان المكان فعلياً
 * - زر التسجيل/الانضمام للفعالية
 * - معلومات تفصيلية عن المتحدثين والبرنامج
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();

// تحميل ملف CSS الخاص بالفعاليات
wp_enqueue_style( 'events-style', get_template_directory_uri() . '/assets/css/events.css', array(), '1.0.0' );

// تحميل ملف JavaScript الخاص بالفعاليات
wp_enqueue_script( 'events-script', get_template_directory_uri() . '/assets/js/events-frontend.js', array( 'jquery' ), '1.0.0', true );

while ( have_posts() ) :
	the_post();

	// جلب البيانات الوصفية
	$event_date      = get_post_meta( get_the_ID(), 'event_date', true );
	$event_time      = get_post_meta( get_the_ID(), 'event_time', true );
	$event_end_time  = get_post_meta( get_the_ID(), 'event_end_time', true );
	$event_location  = get_post_meta( get_the_ID(), 'event_location', true );
	$event_type      = get_post_meta( get_the_ID(), 'event_type', true ); // online/offline
	$event_link      = get_post_meta( get_the_ID(), 'event_link', true );
	$event_seats     = get_post_meta( get_the_ID(), 'event_seats', true );
	$event_status    = get_post_meta( get_the_ID(), 'event_status', true ); // upcoming/ongoing/ended
	$event_latitude  = get_post_meta( get_the_ID(), 'event_latitude', true );
	$event_longitude = get_post_meta( get_the_ID(), 'event_longitude', true );

	// تحديد حالة الفعالية تلقائياً إذا لم تكن محددة
	if ( ! $event_status && $event_date ) {
		$current_datetime = current_time( 'timestamp' );
		$event_timestamp  = strtotime( $event_date . ' ' . $event_time );

		if ( $event_timestamp > $current_datetime ) {
			$event_status = 'upcoming';
		} elseif ( $event_end_time ) {
			$event_end_timestamp = strtotime( $event_date . ' ' . $event_end_time );
			if ( $current_datetime >= $event_timestamp && $current_datetime <= $event_end_timestamp ) {
				$event_status = 'ongoing';
			} else {
				$event_status = 'ended';
			}
		} else {
			$event_status = 'ended';
		}
	}

	// تنسيق التاريخ
	$formatted_date = $event_date ? date_i18n( 'l، j F Y', strtotime( $event_date ) ) : get_the_date();
	$formatted_time = $event_time ? date_i18n( 'g:i A', strtotime( $event_time ) ) : '';

	// حساب الوقت المتبقي (للعداد التنازلي)
	$countdown_timestamp = $event_date && $event_time ? strtotime( $event_date . ' ' . $event_time ) : '';

	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'event-single' ); ?> data-event-status="<?php echo esc_attr( $event_status ); ?>">

		<!-- قسم الهيرو -->
		<div class="event-hero">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="event-hero-image">
					<?php the_post_thumbnail( 'full' ); ?>
					<div class="event-hero-overlay"></div>
				</div>
			<?php endif; ?>

			<div class="event-hero-content">
				<div class="event-hero-container">

					<!-- شارة حالة الفعالية -->
					<div class="event-status-badge <?php echo esc_attr( $event_status ); ?>">
						<?php
						$status_labels = array(
							'upcoming' => '📅 قادمة',
							'ongoing'  => '🔴 جارية الآن',
							'ended'    => '✓ انتهت',
						);
						echo isset( $status_labels[ $event_status ] ) ? $status_labels[ $event_status ] : '📅 فعالية';
						?>
					</div>

					<!-- شارة نوع الفعالية -->
					<?php if ( $event_type ) : ?>
						<div class="event-type-badge">
							<?php
							echo $event_type === 'online' ? '💻 عبر الإنترنت' : '📍 حضوري';
							?>
						</div>
					<?php endif; ?>

					<!-- العنوان -->
					<h1 class="event-title"><?php the_title(); ?></h1>

					<!-- البيانات الوصفية -->
					<div class="event-meta">
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

						<?php if ( $event_location && $event_type !== 'online' ) : ?>
							<span class="event-meta-item">
								<span class="meta-icon">📍</span>
								<?php echo esc_html( $event_location ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $event_seats ) : ?>
							<span class="event-meta-item">
								<span class="meta-icon">👥</span>
								<?php echo esc_html( $event_seats ); ?> مقعد
							</span>
						<?php endif; ?>
					</div>

					<!-- العداد التنازلي (للفعاليات القادمة فقط) -->
					<?php if ( $event_status === 'upcoming' && $countdown_timestamp ) : ?>
						<div class="event-countdown" data-countdown="<?php echo esc_attr( $countdown_timestamp ); ?>">
							<div class="countdown-item">
								<span class="countdown-number days">00</span>
								<span class="countdown-label">يوم</span>
							</div>
							<div class="countdown-item">
								<span class="countdown-number hours">00</span>
								<span class="countdown-label">ساعة</span>
							</div>
							<div class="countdown-item">
								<span class="countdown-number minutes">00</span>
								<span class="countdown-label">دقيقة</span>
							</div>
							<div class="countdown-item">
								<span class="countdown-number seconds">00</span>
								<span class="countdown-label">ثانية</span>
							</div>
						</div>
					<?php endif; ?>

					<!-- أزرار CTA -->
					<div class="event-cta-buttons">
						<?php if ( $event_link && $event_status !== 'ended' ) : ?>
							<a href="<?php echo esc_url( $event_link ); ?>" target="_blank" class="event-cta-btn event-cta-primary">
								<?php echo $event_status === 'ongoing' ? 'انضم الآن' : 'سجّل حضورك'; ?>
								<span class="cta-arrow">→</span>
							</a>
						<?php endif; ?>
						<a href="#details" class="event-cta-btn event-cta-secondary">
							تفاصيل الفعالية
							<span class="cta-arrow">↓</span>
						</a>
					</div>

				</div>
			</div>
		</div>

		<!-- المحتوى الرئيسي -->
		<div class="event-main-container">
			<div class="event-content-wrapper">

				<!-- ملخص الفعالية -->
				<?php if ( has_excerpt() ) : ?>
					<div id="summary" class="event-summary-section">
						<h2 class="section-title">نبذة عن الفعالية</h2>
						<div class="event-summary-content">
							<?php the_excerpt(); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- المحتوى الكامل -->
				<div id="details" class="event-content-section">
					<h2 class="section-title">تفاصيل الفعالية</h2>
					<div class="event-content-body">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- الخريطة (للفعاليات الحضورية فقط) -->
				<?php if ( $event_type === 'offline' && $event_latitude && $event_longitude ) : ?>
					<div class="event-map-section">
						<h2 class="section-title">الموقع على الخريطة</h2>
						<div class="event-map"
							data-lat="<?php echo esc_attr( $event_latitude ); ?>"
							data-lng="<?php echo esc_attr( $event_longitude ); ?>"
							data-location="<?php echo esc_attr( $event_location ); ?>">
							<!-- سيتم إدراج الخريطة هنا عبر JavaScript -->
						</div>
					</div>
				<?php endif; ?>

				<!-- التنقل بين المنشورات -->
				<div class="event-navigation">
					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();
					?>

					<?php if ( $prev_post ) : ?>
						<a href="<?php echo get_permalink( $prev_post ); ?>" class="nav-prev">
							<span class="nav-arrow">→</span>
							<span class="nav-label">الفعالية السابقة</span>
							<span class="nav-title"><?php echo get_the_title( $prev_post ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $next_post ) : ?>
						<a href="<?php echo get_permalink( $next_post ); ?>" class="nav-next">
							<span class="nav-label">الفعالية التالية</span>
							<span class="nav-title"><?php echo get_the_title( $next_post ); ?></span>
							<span class="nav-arrow">←</span>
						</a>
					<?php endif; ?>
				</div>

				<!-- قسم التعليقات -->
				<?php
				if ( comments_open() || get_comments_number() ) :
					?>
					<div id="comments" class="event-comments-section">
						<?php comments_template(); ?>
					</div>
				<?php endif; ?>

			</div><!-- .event-content-wrapper -->

			<!-- Sidebar -->
			<aside class="event-sidebar">

				<!-- فعاليات قادمة -->
				<?php
				$upcoming_args = array(
					'post_type'      => 'events',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'meta_query'     => array(
						array(
							'key'     => 'event_date',
							'value'   => current_time( 'Y-m-d' ),
							'compare' => '>=',
							'type'    => 'DATE',
						),
					),
					'orderby'        => 'meta_value',
					'meta_key'       => 'event_date',
					'order'          => 'ASC',
				);
				$upcoming_query = new WP_Query( $upcoming_args );

				if ( $upcoming_query->have_posts() ) :
					?>
					<div class="sidebar-widget">
						<h3 class="widget-title">فعاليات قادمة</h3>
						<div class="sidebar-events">
							<?php while ( $upcoming_query->have_posts() ) : $upcoming_query->the_post(); ?>
								<article class="sidebar-event-item">
									<?php if ( has_post_thumbnail() ) : ?>
										<a href="<?php the_permalink(); ?>" class="event-thumbnail">
											<?php the_post_thumbnail( 'thumbnail' ); ?>
										</a>
									<?php endif; ?>
									<div class="event-content">
										<h4 class="event-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h4>
										<?php
										$sidebar_event_date = get_post_meta( get_the_ID(), 'event_date', true );
										if ( $sidebar_event_date ) :
											?>
											<p class="event-date">
												<?php echo date_i18n( 'j F، Y', strtotime( $sidebar_event_date ) ); ?>
											</p>
										<?php endif; ?>
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

			</aside><!-- .event-sidebar -->

		</div><!-- .event-main-container -->

	</article><!-- #post-<?php the_ID(); ?> -->

<?php
endwhile;

get_footer();
