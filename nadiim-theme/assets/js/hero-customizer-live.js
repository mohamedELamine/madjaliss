/**
 * Hero Slider Customizer Live Preview
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // التحقق من وجود wp.customize
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        return;
    }

    const customize = wp.customize;

    /**
     * تفعيل/إلغاء تفعيل Hero Slider
     */
    customize('hero_enable', function(value) {
        value.bind(function(to) {
            const heroSection = $('.hero-slider-section');
            if (to) {
                heroSection.slideDown(300);
            } else {
                heroSection.slideUp(300);
            }
        });
    });

    /**
     * زاوية حواف البطاقة
     */
    customize('hero_card_radius', function(value) {
        value.bind(function(to) {
            const style = document.getElementById('hero-card-radius-style') || createStyleElement('hero-card-radius-style');
            style.textContent = `:root { --hero-card-radius: ${to}px; }`;
        });
    });

    /**
     * شفافية الـ Overlay
     */
    customize('hero_overlay_default_opacity', function(value) {
        value.bind(function(to) {
            const style = document.getElementById('hero-overlay-opacity-style') || createStyleElement('hero-overlay-opacity-style');
            style.textContent = `:root { --hero-overlay-opacity: ${to}; }`;

            // تحديث جميع overlays
            $('.slide-bg-overlay').css('background-color', `rgba(0, 0, 0, ${to})`);
        });
    });

    /**
     * نظام ألوان النص
     */
    customize('hero_text_color_scheme', function(value) {
        value.bind(function(to) {
            const cards = $('.hero-slide-card');

            cards.removeClass('light-text dark-text');

            if (to === 'light') {
                cards.addClass('light-text');
            } else if (to === 'dark') {
                cards.addClass('dark-text');
            }
            // auto يترك CSS الافتراضي
        });
    });

    /**
     * Autoplay
     */
    customize('hero_autoplay', function(value) {
        value.bind(function(to) {
            // سيحتاج refresh لتطبيق التغيير على Swiper
            customize.preview.send('refresh');
        });
    });

    /**
     * مدة التأخير
     */
    customize('hero_delay', function(value) {
        value.bind(function(to) {
            // سيحتاج refresh لتطبيق التغيير على Swiper
            if (window.nadiimHeroSlider && window.nadiimHeroSlider.swiper) {
                window.nadiimHeroSlider.swiper.params.autoplay.delay = parseInt(to);
                if (window.nadiimHeroSlider.swiper.autoplay.running) {
                    window.nadiimHeroSlider.swiper.autoplay.stop();
                    window.nadiimHeroSlider.swiper.autoplay.start();
                }
            }
        });
    });

    /**
     * مصدر المحتوى - يحتاج refresh
     */
    customize('hero_source', function(value) {
        value.bind(function(to) {
            customize.preview.send('refresh');
        });
    });

    /**
     * عدد الشرائح - يحتاج refresh
     */
    customize('hero_count', function(value) {
        value.bind(function(to) {
            customize.preview.send('refresh');
        });
    });

    /**
     * تاج المحتوى المميز - يحتاج refresh
     */
    customize('hero_featured_tag', function(value) {
        value.bind(function(to) {
            customize.preview.send('refresh');
        });
    });

    /**
     * JSON الشرائح اليدوية - يحتاج refresh
     */
    customize('hero_slides_json', function(value) {
        value.bind(function(to) {
            // تحقق من صحة JSON
            try {
                JSON.parse(to);
                customize.preview.send('refresh');
            } catch (e) {
            }
        });
    });

    /**
     * دالة مساعدة لإنشاء عنصر style
     */
    function createStyleElement(id) {
        const style = document.createElement('style');
        style.id = id;
        document.head.appendChild(style);
        return style;
    }

})(jQuery);
