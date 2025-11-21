<?php
/**
 * Template part for displaying About Timeline section
 *
 * @package Nadiim
 * @since 1.0.0
 */

// Check if timeline is enabled
$timeline_enable = get_theme_mod('about_timeline_enable', true);
if (!$timeline_enable) {
    return;
}

// Get timeline settings
$timeline_order = get_theme_mod('about_timeline_order', 'asc');
$timeline_json = get_theme_mod('about_timeline_json', '');

// Parse timeline JSON
$timeline_events = array();
if (!empty($timeline_json)) {
    $decoded = json_decode($timeline_json, true);
    if (is_array($decoded)) {
        $timeline_events = $decoded;
    }
}

// Add demo timeline if empty
if (empty($timeline_events)) {
    $timeline_events = array(
        array(
            'date' => '2018-01',
            'title' => 'التأسيس',
            'short_description' => 'تأسيس المبادرة بهدف نشر الثقافة والمعرفة',
            'full_description' => '<p>بدأت رحلتنا في عام 2018 بفكرة بسيطة: جعل الثقافة والمعرفة متاحة للجميع. تأسست المبادرة على يد مجموعة من المثقفين والكتاب الذين يؤمنون بقوة الكلمة.</p>',
            'image' => '',
            'link' => '',
        ),
        array(
            'date' => '2019-06',
            'title' => 'أول مبادرة',
            'short_description' => 'إطلاق أول برنامج قراءة جماعية',
            'full_description' => '<p>أطلقنا برنامج القراءة الجماعية الذي لاقى إقبالاً كبيراً من المهتمين بالقراءة والثقافة.</p>',
            'image' => '',
            'link' => '',
        ),
        array(
            'date' => '2020-03',
            'title' => 'أول إصدار',
            'short_description' => 'نشر أول كتاب رقمي من إنتاج المبادرة',
            'full_description' => '<p>أصدرنا أول كتاب رقمي يجمع مقالات وأبحاث من كتابنا ومتابعينا.</p>',
            'image' => '',
            'link' => '',
        ),
        array(
            'date' => '2021-09',
            'title' => 'التوسع',
            'short_description' => 'توسيع نشاطات المبادرة لتشمل مدناً جديدة',
            'full_description' => '<p>توسعنا لنصل إلى مدن عربية متعددة وأطلقنا فروعاً محلية في عدة بلدان.</p>',
            'image' => '',
            'link' => '',
        ),
        array(
            'date' => '2023-05',
            'title' => 'تعاون إقليمي',
            'short_description' => 'شراكات مع مؤسسات ثقافية إقليمية',
            'full_description' => '<p>عقدنا شراكات استراتيجية مع مؤسسات ثقافية في المنطقة لتعزيز التبادل الثقافي.</p>',
            'image' => '',
            'link' => '',
        ),
        array(
            'date' => '2025-01',
            'title' => 'الحاضر',
            'short_description' => 'مواصلة المسيرة نحو مستقبل ثقافي مشرق',
            'full_description' => '<p>نواصل مسيرتنا بخطى ثابتة نحو تحقيق رؤيتنا في نشر الثقافة والمعرفة.</p>',
            'image' => '',
            'link' => '',
        ),
    );
}

// Sort timeline based on order setting
if ($timeline_order === 'desc') {
    usort($timeline_events, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });
} else {
    usort($timeline_events, function($a, $b) {
        return strcmp($a['date'], $b['date']);
    });
}

if (empty($timeline_events)) {
    return;
}
?>

<section class="about-timeline" style="padding: 80px 0; background: linear-gradient(135deg, #f9fffe 0%, #f0faf6 100%);">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">

        <h2 class="section-title" style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; text-align: center; margin-bottom: 64px; color: var(--color-primary, #339063);">
            مسيرتنا
        </h2>

        <div class="timeline-wrapper" style="position: relative; max-width: 900px; margin: 0 auto;">
            <!-- Timeline Line -->
            <div class="timeline-line" style="position: absolute; right: 50%; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, var(--color-primary, #339063) 0%, rgba(51, 144, 99, 0.3) 100%); transform: translateX(50%);"></div>

            <?php foreach ($timeline_events as $index => $event) :
                $is_even = $index % 2 === 0;
                $date = isset($event['date']) ? $event['date'] : '';
                $title = isset($event['title']) ? $event['title'] : '';
                $short_desc = isset($event['short_description']) ? $event['short_description'] : '';
                $full_desc = isset($event['full_description']) ? $event['full_description'] : '';
                $image = isset($event['image']) ? $event['image'] : '';
                $link = isset($event['link']) ? $event['link'] : '';

                // Format date
                $formatted_date = '';
                if ($date) {
                    $date_parts = explode('-', $date);
                    if (count($date_parts) >= 1) {
                        $formatted_date = $date_parts[0]; // Year
                        if (count($date_parts) >= 2) {
                            $months = array('', 'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر');
                            $month_num = intval($date_parts[1]);
                            if ($month_num > 0 && $month_num <= 12) {
                                $formatted_date = $months[$month_num] . ' ' . $date_parts[0];
                            }
                        }
                    }
                }
            ?>
                <div class="timeline-item" data-index="<?php echo esc_attr($index); ?>" style="position: relative; display: flex; align-items: center; margin-bottom: 48px; <?php echo $is_even ? 'flex-direction: row-reverse;' : ''; ?>">

                    <!-- Timeline Dot -->
                    <div class="timeline-dot" style="position: absolute; right: 50%; width: 20px; height: 20px; background: var(--color-primary, #339063); border: 4px solid #fff; border-radius: 50%; transform: translateX(50%); z-index: 2; box-shadow: 0 2px 8px rgba(51, 144, 99, 0.3);"></div>

                    <!-- Timeline Content -->
                    <div class="timeline-content" style="width: 45%; <?php echo $is_even ? 'padding-left: 40px; text-align: right;' : 'padding-right: 40px; text-align: left;'; ?>">

                        <?php if ($formatted_date) : ?>
                            <div class="timeline-date" style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary, #339063); margin-bottom: 8px;">
                                <?php echo esc_html($formatted_date); ?>
                            </div>
                        <?php endif; ?>

                        <div class="timeline-card" style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: <?php echo ($full_desc || $link) ? 'pointer' : 'default'; ?>;" <?php if ($full_desc) : ?>onclick="openTimelineModal(<?php echo esc_attr($index); ?>)"<?php endif; ?>>

                            <?php if ($image) : ?>
                                <div class="timeline-image" style="width: 100%; height: 150px; margin-bottom: 16px; border-radius: 8px; overflow: hidden;">
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            <?php endif; ?>

                            <?php if ($title) : ?>
                                <h3 class="timeline-title" style="font-size: 1.375rem; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">
                                    <?php echo esc_html($title); ?>
                                </h3>
                            <?php endif; ?>

                            <?php if ($short_desc) : ?>
                                <p class="timeline-description" style="font-size: 1rem; line-height: 1.7; color: var(--color-text-secondary, #666); margin-bottom: 12px;">
                                    <?php echo esc_html($short_desc); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($full_desc || $link) : ?>
                                <div class="timeline-actions" style="display: flex; gap: 12px; align-items: center;">
                                    <?php if ($full_desc) : ?>
                                        <span style="color: var(--color-primary, #339063); font-weight: 600; font-size: 0.875rem;">اقرأ المزيد ←</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Hidden full description for modal -->
                            <?php if ($full_desc) : ?>
                                <div class="timeline-full-description" style="display: none;">
                                    <?php echo wp_kses_post($full_desc); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<!-- Timeline Modal -->
<div id="timeline-modal" class="timeline-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; padding: 24px;" onclick="closeTimelineModal(event)">
    <div class="modal-content" style="background: #fff; border-radius: 16px; max-width: 800px; max-height: 90vh; overflow-y: auto; padding: 40px; position: relative;" onclick="event.stopPropagation()">
        <button onclick="closeTimelineModal()" style="position: absolute; top: 16px; left: 16px; background: none; border: none; font-size: 2rem; color: #999; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background 0.3s ease;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='none'">&times;</button>
        <div id="modal-body"></div>
    </div>
</div>

<script>
function openTimelineModal(index) {
    const items = document.querySelectorAll('.timeline-item');
    const item = items[index];
    if (!item) return;

    const title = item.querySelector('.timeline-title') ? item.querySelector('.timeline-title').textContent : '';
    const date = item.querySelector('.timeline-date') ? item.querySelector('.timeline-date').textContent : '';
    const fullDesc = item.querySelector('.timeline-full-description') ? item.querySelector('.timeline-full-description').innerHTML : '';
    const image = item.querySelector('.timeline-image img') ? item.querySelector('.timeline-image img').src : '';

    let modalContent = '';

    if (date) {
        modalContent += '<div style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary, #339063); margin-bottom: 12px;">' + date + '</div>';
    }

    if (title) {
        modalContent += '<h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 24px; color: var(--color-text);">' + title + '</h2>';
    }

    if (image) {
        modalContent += '<div style="width: 100%; height: 300px; margin-bottom: 24px; border-radius: 12px; overflow: hidden;"><img src="' + image + '" alt="' + title + '" style="width: 100%; height: 100%; object-fit: cover;"></div>';
    }

    if (fullDesc) {
        modalContent += '<div style="font-size: 1.125rem; line-height: 2; color: var(--color-text-secondary, #555);">' + fullDesc + '</div>';
    }

    document.getElementById('modal-body').innerHTML = modalContent;
    const modal = document.getElementById('timeline-modal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeTimelineModal(event) {
    if (event && event.target !== event.currentTarget && !event.target.closest('button')) {
        return;
    }
    const modal = document.getElementById('timeline-modal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTimelineModal();
    }
});
</script>

<style>
/* Responsive Timeline Styles */
@media (max-width: 768px) {
    .timeline-line {
        right: 24px !important;
        transform: none !important;
    }

    .timeline-item {
        flex-direction: column !important;
        align-items: flex-start !important;
        padding-right: 48px;
    }

    .timeline-dot {
        right: 15px !important;
        transform: none !important;
    }

    .timeline-content {
        width: 100% !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
        text-align: right !important;
    }
}

.timeline-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}
</style>
