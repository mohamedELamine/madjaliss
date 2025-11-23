<?php
/**
 * Template part لقسم الفعاليات
 *
 * عرض الفعاليات القادمة في الصفحة الرئيسية
 *
 * @package Nadiim
 * @since 1.0.0
 */

// التحقق من تفعيل القسم
if ( ! get_theme_mod( 'events_section_enable', true ) ) {
	return;
}

// الحصول على الإعدادات من Customizer
$section_title       = get_theme_mod( 'events_section_title', __( 'الفعاليات القادمة', 'nadiim' ) );
$section_description = get_theme_mod( 'events_section_description', __( 'تعرّف على فعالياتنا القادمة وكن جزءاً من مجتمعنا الثقافي', 'nadiim' ) );
$events_count        = get_theme_mod( 'events_section_count', 3 );
$show_past_events    = get_theme_mod( 'events_section_show_past', false );
$show_more_button    = get_theme_mod( 'events_section_show_more', true );
$more_button_text    = get_theme_mod( 'events_section_more_text', __( 'جميع الفعاليات', 'nadiim' ) );

// إعدادات التنسيق والألوان
$bg_color              = get_theme_mod( 'events_section_bg_color', '#F8F9F8' );
$title_color           = get_theme_mod( 'events_section_title_color', '#1C2D27' );
$description_color     = get_theme_mod( 'events_section_description_color', '#425F54' );
$bg_image              = get_theme_mod( 'events_section_bg_image', '' );
$bg_position           = get_theme_mod( 'events_section_bg_position', 'center' );
$overlay_opacity       = get_theme_mod( 'events_section_overlay_opacity', 0.5 );
$overlay_color         = get_theme_mod( 'events_section_overlay_color', '#000000' );

// بناء WP_Query arguments
$args = array(
	'post_type'      => 'events',
	'posts_per_page' => $events_count,
	'post_status'    => 'publish',
	'orderby'        => 'meta_value',
	'meta_key'       => 'event_date',
	'order'          => 'ASC',
);

// تصفية الفعاليات القادمة فقط (إلا إذا كانت تفضيلات المستخدم تسمح بالسابقة)
if ( ! $show_past_events ) {
	$args['meta_query'] = array(
		array(
			'key'     => 'event_date',
			'value'   => current_time( 'Y-m-d' ),
			'compare' => '>=',
			'type'    => 'DATE',
		),
	);
}

// تنفيذ الاستعلام
$events_query = new WP_Query( $args );

?>

<section class="events-section section-padding" id="events-section">
	<div class="container">

		<!-- Section Header -->
		<div class="section-header text-center">
			<?php if ( $section_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_description ) : ?>
				<p class="section-description"><?php echo esc_html( $section_description ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Events Grid -->
		<?php if ( $events_query->have_posts() ) : ?>
		<div class="events-grid">

			<?php
			while ( $events_query->have_posts() ) :
				$events_query->the_post();

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

				// حساب الوقت المتبقي للعداد التنازلي
				$countdown_timestamp = $event_date && $event_time ? strtotime( $event_date . ' ' . $event_time ) : '';
				?>

				<article class="event-card-home" data-status="<?php echo esc_attr( $event_status ); ?>" data-type="<?php echo esc_attr( $event_type ); ?>">

					<!-- صورة الفعالية -->
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="event-card-image">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'nadiim-card' ); ?>
							</a>

							<!-- شارة الحالة -->
							<span class="event-status-badge <?php echo esc_attr( $event_status ); ?>">
								<?php
								$status_labels = array(
									'upcoming' => '📅 قادمة',
									'ongoing'  => '🔴 الآن',
									'ended'    => '✓ انتهت',
								);
								echo isset( $status_labels[ $event_status ] ) ? $status_labels[ $event_status ] : 'فعالية';
								?>
							</span>

							<!-- شارة النوع -->
							<?php if ( $event_type ) : ?>
								<span class="event-type-badge">
									<?php echo $event_type === 'online' ? '💻' : '📍'; ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<!-- محتوى البطاقة -->
					<div class="event-card-content">

						<!-- التاريخ والمكان -->
						<div class="event-meta-compact">
							<?php if ( $formatted_date ) : ?>
								<div class="meta-item date">
									<span class="icon">📅</span>
									<span class="text"><?php echo esc_html( $formatted_date ); ?></span>
								</div>
							<?php endif; ?>

							<?php if ( $event_location && $event_type !== 'online' ) : ?>
								<div class="meta-item location">
									<span class="icon">📍</span>
									<span class="text"><?php echo esc_html( wp_trim_words( $event_location, 3, '...' ) ); ?></span>
								</div>
							<?php elseif ( $event_type === 'online' ) : ?>
								<div class="meta-item location">
									<span class="icon">💻</span>
									<span class="text">عبر الإنترنت</span>
								</div>
							<?php endif; ?>
						</div>

						<!-- العنوان -->
						<h3 class="event-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<!-- المقتطف -->
						<?php if ( has_excerpt() ) : ?>
							<div class="event-card-excerpt">
								<?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
							</div>
						<?php endif; ?>

						<!-- العداد التنازلي (للفعاليات القادمة فقط) -->
						<?php if ( $event_status === 'upcoming' && $countdown_timestamp ) : ?>
							<div class="event-countdown-mini" data-countdown="<?php echo esc_attr( $countdown_timestamp ); ?>">
								<div class="countdown-item">
									<span class="number days">00</span>
									<span class="label">يوم</span>
								</div>
								<div class="countdown-item">
									<span class="number hours">00</span>
									<span class="label">ساعة</span>
								</div>
								<div class="countdown-item">
									<span class="number minutes">00</span>
									<span class="label">دقيقة</span>
								</div>
							</div>
						<?php endif; ?>

						<!-- أزرار CTA -->
						<div class="event-card-actions">
							<a href="<?php the_permalink(); ?>" class="btn-details">
								التفاصيل
							</a>
							<?php if ( $event_link && $event_status !== 'ended' ) : ?>
								<a href="<?php echo esc_url( $event_link ); ?>" target="_blank" class="btn-register">
									<?php echo $event_status === 'ongoing' ? 'انضم' : 'سجّل'; ?>
									<span class="arrow">→</span>
								</a>
							<?php endif; ?>
						</div>

					</div>

				</article>

			<?php endwhile; wp_reset_postdata(); ?>

		</div>
		<?php else : ?>
		<div class="no-events-message">
			<p>لا توجد فعاليات قادمة حالياً. تابعونا لمعرفة الفعاليات الجديدة!</p>
		</div>
		<?php endif; ?>

		<!-- زر عرض المزيد -->
		<?php if ( $show_more_button ) : ?>
			<div class="section-cta text-center">
				<a href="<?php echo get_post_type_archive_link( 'events' ); ?>" class="btn btn-primary btn-large">
					<?php echo esc_html( $more_button_text ); ?>
					<span class="arrow">←</span>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>

<style>
/* أنماط CSS لقسم الفعاليات في الصفحة الرئيسية */
.events-section {
    background-color: <?php echo esc_attr( $bg_color ); ?>;
    <?php if ( $bg_image ) : ?>
    background-image: url('<?php echo esc_url( $bg_image ); ?>');
    background-size: cover;
    background-position: <?php echo esc_attr( $bg_position ); ?>;
    background-repeat: no-repeat;
    background-attachment: fixed;
    position: relative;
    <?php endif; ?>
    padding: 80px 0;
}

<?php if ( $bg_image ) : ?>
.events-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: <?php echo esc_attr( $overlay_color ); ?>;
    opacity: <?php echo esc_attr( $overlay_opacity ); ?>;
    z-index: 1;
}

.events-section > .container {
    position: relative;
    z-index: 2;
}
<?php endif; ?>

.events-section .section-header {
    margin-bottom: 50px;
}

.events-section .section-title {
    font-size: 36px;
    font-weight: 700;
    color: <?php echo esc_attr( $title_color ); ?>;
    margin-bottom: 15px;
}

.events-section .section-description {
    font-size: 18px;
    color: <?php echo esc_attr( $description_color ); ?>;
    max-width: 600px;
    margin: 0 auto;
}

.events-section .events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.events-section .no-events-message {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 12px;
    margin-bottom: 50px;
}

.events-section .no-events-message p {
    font-size: 18px;
    color: #6B7A72;
    margin: 0;
}

.event-card-home {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.event-card-home:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(51, 144, 99, 0.15);
}

.event-card-home .event-card-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.event-card-home .event-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card-home:hover .event-card-image img {
    transform: scale(1.05);
}

.event-card-home .event-status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 14px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.event-card-home .event-status-badge.upcoming {
    background: rgba(76, 175, 80, 0.95);
    color: #fff;
}

.event-card-home .event-status-badge.ongoing {
    background: rgba(244, 67, 54, 0.95);
    color: #fff;
}

.event-card-home .event-type-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    font-size: 20px;
    background: rgba(0, 0, 0, 0.6);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.event-card-home .event-card-content {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.event-card-home .event-meta-compact {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}

.event-card-home .meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #6B7A72;
}

.event-card-home .meta-item .icon {
    font-size: 16px;
}

.event-card-home .event-card-title {
    font-size: 20px;
    font-weight: 700;
    color: #1C2D27;
    margin-bottom: 12px;
    line-height: 1.3;
}

.event-card-home .event-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.event-card-home .event-card-title a:hover {
    color: #339063;
}

.event-card-home .event-card-excerpt {
    font-size: 14px;
    color: #6B7A72;
    line-height: 1.6;
    margin-bottom: 16px;
    flex: 1;
}

.event-card-home .event-countdown-mini {
    display: flex;
    gap: 12px;
    justify-content: center;
    padding: 16px;
    background: #F6F9F7;
    border-radius: 12px;
    margin-bottom: 16px;
}

.event-card-home .countdown-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 50px;
}

.event-card-home .countdown-item .number {
    font-size: 24px;
    font-weight: 700;
    color: #339063;
    font-family: 'Courier New', monospace;
}

.event-card-home .countdown-item .label {
    font-size: 11px;
    color: #6B7A72;
    margin-top: 4px;
}

.event-card-home .event-card-actions {
    display: flex;
    gap: 12px;
    margin-top: auto;
}

.event-card-home .btn-details {
    flex: 1;
    padding: 10px 20px;
    background: transparent;
    color: #339063;
    border: 2px solid #339063;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.event-card-home .btn-details:hover {
    background: #339063;
    color: #fff;
}

.event-card-home .btn-register {
    flex: 1;
    padding: 10px 20px;
    background: #339063;
    color: #fff;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.event-card-home .btn-register:hover {
    background: #2a7851;
    transform: translateY(-2px);
}

.events-section .section-cta {
    margin-top: 40px;
}

.events-section .btn-large {
    padding: 16px 40px;
    font-size: 18px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

.btn-primary {
    background: #339063;
    color: #fff;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #2a7851;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(51, 144, 99, 0.3);
}

@media (max-width: 768px) {
    .events-section {
        padding: 60px 0;
    }

    .events-section .section-title {
        font-size: 28px;
    }

    .events-section .events-grid {
        grid-template-columns: 1fr;
    }

    .event-card-home .event-card-actions {
        flex-direction: column;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // تحديث العداد التنازلي
    $('.event-countdown-mini').each(function() {
        const $countdown = $(this);
        const timestamp = $countdown.data('countdown');

        if (!timestamp) return;

        function update() {
            const now = Math.floor(Date.now() / 1000);
            const remaining = timestamp - now;

            if (remaining <= 0) {
                $countdown.html('<div style="text-align:center; color: #339063; font-weight: 600;">بدأت الفعالية!</div>');
                return;
            }

            const days = Math.floor(remaining / 86400);
            const hours = Math.floor((remaining % 86400) / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);

            $countdown.find('.days').text(String(days).padStart(2, '0'));
            $countdown.find('.hours').text(String(hours).padStart(2, '0'));
            $countdown.find('.minutes').text(String(minutes).padStart(2, '0'));
        }

        update();
        setInterval(update, 60000); // تحديث كل دقيقة
    });
});
</script>
