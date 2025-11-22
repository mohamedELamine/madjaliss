/**
 * Reading Clubs Frontend JavaScript
 * Handles Leaflet maps initialization
 */

(function() {
    'use strict';

    /**
     * تهيئة خريطة Leaflet
     * @param {string} elementId - معرف العنصر
     * @param {number} lat - خط العرض
     * @param {number} lng - خط الطول
     * @param {string} address - العنوان
     * @param {number} zoom - مستوى التكبير
     */
    function initLeafletMap(elementId, lat, lng, address, zoom) {
        // التحقق من وجود Leaflet
        if (typeof L === 'undefined') {
            return;
        }

        // الحصول على العنصر
        const mapElement = document.getElementById(elementId);
        if (!mapElement) {
            return;
        }

        try {
            // إنشاء الخريطة
            const map = L.map(elementId, {
                center: [lat, lng],
                zoom: zoom,
                scrollWheelZoom: false, // منع التكبير بعجلة الماوس افتراضياً
                zoomControl: true
            });

            // إضافة طبقة الخريطة من OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(map);

            // إنشاء أيقونة مخصصة
            const customIcon = L.divIcon({
                className: 'custom-map-marker',
                html: `
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#339063">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });

            // إضافة Marker
            const marker = L.marker([lat, lng], {
                icon: customIcon
            }).addTo(map);

            // إضافة Popup
            if (address) {
                marker.bindPopup(`
                    <div style="text-align: right; direction: rtl; font-family: 'Cairo', 'Tajawal', sans-serif;">
                        <strong style="color: #339063; font-size: 15px;">📍 موقع الاجتماع</strong>
                        <p style="margin: 8px 0 0 0; font-size: 14px; color: #495057;">${address}</p>
                    </div>
                `);
            }

            // السماح بالتكبير عند التركيز على الخريطة
            map.on('focus', function() {
                map.scrollWheelZoom.enable();
            });

            map.on('blur', function() {
                map.scrollWheelZoom.disable();
            });

            // السماح بالتكبير عند النقر
            mapElement.addEventListener('click', function() {
                map.scrollWheelZoom.enable();
            });

            // إضافة رسالة تعليمية
            const scrollMessage = L.control({position: 'topleft'});
            scrollMessage.onAdd = function() {
                const div = L.DomUtil.create('div', 'leaflet-scroll-message');
                div.innerHTML = '<small style="background: rgba(255,255,255,0.9); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: #666;">انقر لتفعيل التكبير</small>';
                div.style.pointerEvents = 'none';
                setTimeout(function() {
                    div.style.display = 'none';
                }, 3000);
                return div;
            };
            scrollMessage.addTo(map);


        } catch (error) {
        }
    }

    /**
     * تهيئة جميع الخرائط في الصفحة
     */
    function initAllMaps() {
        // خريطة Hero
        const heroMap = document.getElementById('club-hero-map');
        if (heroMap) {
            const lat = parseFloat(heroMap.dataset.lat);
            const lng = parseFloat(heroMap.dataset.lng);
            const address = heroMap.dataset.address;

            if (lat && lng) {
                initLeafletMap('club-hero-map', lat, lng, address, 13);
            }
        }

        // الخريطة التفاعلية الرئيسية
        const mainMap = document.getElementById('club-main-map');
        if (mainMap) {
            const lat = parseFloat(mainMap.dataset.lat);
            const lng = parseFloat(mainMap.dataset.lng);
            const address = mainMap.dataset.address;

            if (lat && lng) {
                initLeafletMap('club-main-map', lat, lng, address, 15);
            }
        }
    }

    /**
     * تحسين تجربة المستخدم للأزرار الاجتماعية
     */
    function enhanceSocialButtons() {
        const socialButtons = document.querySelectorAll('.club-social-btn');

        socialButtons.forEach(function(button) {
            // إضافة تأثير ripple عند النقر
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');

                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';

                button.appendChild(ripple);

                setTimeout(function() {
                    ripple.remove();
                }, 600);
            });
        });
    }

    /**
     * تحسين تجربة بطاقات الأرشيف
     */
    function enhanceArchiveCards() {
        const cards = document.querySelectorAll('.club-card');

        cards.forEach(function(card) {
            // إضافة تأثير hover للبطاقة بأكملها
            card.addEventListener('mouseenter', function() {
                card.style.borderColor = '#339063';
            });

            card.addEventListener('mouseleave', function() {
                card.style.borderColor = '';
            });
        });
    }

    /**
     * Lazy loading للصور
     */
    function setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const images = document.querySelectorAll('.club-card-image');

            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * تحسين إمكانية الوصول (Accessibility)
     */
    function enhanceAccessibility() {
        // إضافة aria-label للأزرار الاجتماعية
        const socialButtons = document.querySelectorAll('.club-social-btn');
        socialButtons.forEach(function(button) {
            if (!button.getAttribute('aria-label')) {
                const text = button.querySelector('span');
                if (text) {
                    button.setAttribute('aria-label', text.textContent);
                }
            }
        });

        // إضافة aria-label للخرائط
        const maps = document.querySelectorAll('.club-leaflet-map, .club-interactive-map');
        maps.forEach(function(map) {
            if (!map.getAttribute('aria-label')) {
                map.setAttribute('aria-label', 'خريطة موقع النادي');
                map.setAttribute('role', 'img');
            }
        });
    }

    /**
     * إضافة smooth scroll للروابط الداخلية
     */
    function setupSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * تهيئة عند تحميل الصفحة
     */
    function init() {
        // انتظار تحميل Leaflet
        if (typeof L !== 'undefined') {
            initAllMaps();
        } else {
            // إعادة المحاولة بعد ثانية
            setTimeout(function() {
                if (typeof L !== 'undefined') {
                    initAllMaps();
                }
            }, 1000);
        }

        // تفعيل التحسينات الأخرى
        enhanceSocialButtons();
        enhanceArchiveCards();
        setupLazyLoading();
        enhanceAccessibility();
        setupSmoothScroll();

    }

    // تشغيل عند استعداد DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

/**
 * CSS للتأثيرات الإضافية
 * يمكن إضافته في inline styles أو في ملف CSS منفصل
 */
const rippleStyles = document.createElement('style');
rippleStyles.textContent = `
    .club-social-btn {
        position: relative;
        overflow: hidden;
    }

    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }

    .custom-map-marker {
        background: transparent;
        border: none;
    }

    .leaflet-scroll-message {
        margin: 10px;
    }
`;

if (document.head) {
    document.head.appendChild(rippleStyles);
}
