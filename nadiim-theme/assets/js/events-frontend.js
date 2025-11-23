/**
 * Events Frontend JavaScript
 * Handles: Countdown Timer, Map Loading, Smooth Scroll
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initCountdownTimer();
        initEventMap();
        initSmoothScroll();
    });

    /**
     * العداد التنازلي للفعالية
     */
    function initCountdownTimer() {
        const $countdown = $('.event-countdown');

        if (!$countdown.length) return;

        const countdownTimestamp = $countdown.data('countdown');

        if (!countdownTimestamp) return;

        function updateCountdown() {
            const now = Math.floor(Date.now() / 1000);
            const timeRemaining = countdownTimestamp - now;

            if (timeRemaining <= 0) {
                // الفعالية بدأت أو انتهت
                $countdown.html('<div class="countdown-message">بدأت الفعالية!</div>');
                return;
            }

            // حساب الأيام، الساعات، الدقائق، الثواني
            const days = Math.floor(timeRemaining / 86400);
            const hours = Math.floor((timeRemaining % 86400) / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;

            // تحديث العرض
            $countdown.find('.days').text(String(days).padStart(2, '0'));
            $countdown.find('.hours').text(String(hours).padStart(2, '0'));
            $countdown.find('.minutes').text(String(minutes).padStart(2, '0'));
            $countdown.find('.seconds').text(String(seconds).padStart(2, '0'));
        }

        // تحديث فوري ثم كل ثانية
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    /**
     * تحميل الخريطة للفعاليات الحضورية
     */
    function initEventMap() {
        const $map = $('.event-map');

        if (!$map.length) return;

        const lat = parseFloat($map.data('lat'));
        const lng = parseFloat($map.data('lng'));
        const location = $map.data('location');

        if (!lat || !lng) {
            $map.html('<div style="padding: 40px; text-align: center; color: #999;">لم يتم تحديد موقع الفعالية</div>');
            return;
        }

        // استخدام Google Maps (يتطلب API key)
        // أو يمكن استخدام OpenStreetMap مع Leaflet (مجاني)

        // مثال باستخدام Leaflet (يتطلب تضمين مكتبة Leaflet)
        if (typeof L !== 'undefined') {
            const map = L.map($map[0]).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('<strong>' + location + '</strong>')
                .openPopup();
        } else {
            // Fallback: إظهار رابط لـ Google Maps
            const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
            $map.html(
                `<div style="padding: 40px; text-align: center;">
                    <p style="margin-bottom: 20px;">اعرض الموقع على الخريطة</p>
                    <a href="${mapsUrl}" target="_blank" style="display: inline-block; padding: 12px 24px; background: #339063; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        فتح في Google Maps
                    </a>
                </div>`
            );
        }
    }

    /**
     * التمرير السلس للروابط الداخلية
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const href = this.getAttribute('href');

            // تجاهل الروابط الفارغة
            if (!href || href === '#' || href === '#!') {
                e.preventDefault();
                return false;
            }

            const target = $(href);

            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        });
    }

})(jQuery);
