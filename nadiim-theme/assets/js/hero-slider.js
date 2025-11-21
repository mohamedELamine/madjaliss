/**
 * Hero Slider JavaScript
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function() {
    'use strict';

    // الانتظار حتى يتم تحميل DOM و Swiper
    document.addEventListener('DOMContentLoaded', function() {
        initHeroSlider();
    });

    /**
     * تهيئة Hero Slider
     */
    function initHeroSlider() {
        // التحقق من وجود Swiper
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper is not loaded. Hero slider will not work.');
            return;
        }

        // العنصر الأساسي
        const swiperEl = document.querySelector('.hero-swiper');
        if (!swiperEl) {
            return;
        }

        // الحصول على الإعدادات من wp_localize_script
        const settings = typeof NADIIM_HERO !== 'undefined' ? NADIIM_HERO : {};

        const autoplay = settings.autoplay !== false ? {
            delay: settings.delay || 6000,
            disableOnInteraction: true,
            pauseOnMouseEnter: true,
        } : false;

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // تهيئة Swiper
        const swiper = new Swiper('.hero-swiper', {
            // إعدادات أساسية
            slidesPerView: 1,
            spaceBetween: 28,
            loop: true,
            speed: reducedMotion ? 0 : 600,

            // Autoplay
            autoplay: reducedMotion ? false : autoplay,

            // Lazy loading
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 1,
            },

            // Keyboard control
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },

            // Mousewheel
            mousewheel: false,

            // Pagination
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true,
                renderBullet: function(index, className) {
                    return '<span class="' + className + '" role="button" aria-label="Go to slide ' + (index + 1) + '"></span>';
                },
            },

            // Navigation
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Accessibility
            a11y: {
                enabled: true,
                prevSlideMessage: 'الشريحة السابقة',
                nextSlideMessage: 'الشريحة التالية',
                firstSlideMessage: 'هذه هي الشريحة الأولى',
                lastSlideMessage: 'هذه هي الشريحة الأخيرة',
            },

            // Events
            on: {
                init: function() {
                    updateAria(this);
                },
                slideChange: function() {
                    updateAria(this);
                    handleFocus(this);
                },
                autoplayStart: function() {
                    updatePlayPauseButton(true);
                },
                autoplayStop: function() {
                    updatePlayPauseButton(false);
                },
            },
        });

        // Play/Pause button
        initPlayPauseButton(swiper);

        // Keyboard shortcuts
        initKeyboardShortcuts(swiper);

        // Pause on visibility change
        initVisibilityHandler(swiper);

        // Return swiper instance for potential external use
        return swiper;
    }

    /**
     * تحديث ARIA attributes
     */
    function updateAria(swiper) {
        const slides = swiper.slides;
        const activeIndex = swiper.realIndex;

        slides.forEach((slide, index) => {
            const isActive = index === swiper.activeIndex;
            slide.setAttribute('aria-hidden', !isActive);

            // تحديث tab index
            const focusableElements = slide.querySelectorAll('a, button, [tabindex]');
            focusableElements.forEach(el => {
                el.setAttribute('tabindex', isActive ? '0' : '-1');
            });

            // تحديث aria-label
            slide.setAttribute('aria-label', 'Slide ' + (activeIndex + 1) + ' of ' + swiper.slides.length);
        });
    }

    /**
     * التركيز على الشريحة النشطة
     */
    function handleFocus(swiper) {
        const activeSlide = swiper.slides[swiper.activeIndex];
        if (activeSlide) {
            // التركيز على البطاقة إذا لم يكن المستخدم يتفاعل مع عنصر آخر
            setTimeout(() => {
                if (!document.activeElement || document.activeElement === document.body) {
                    const card = activeSlide.querySelector('.hero-slide-card');
                    if (card) {
                        card.focus();
                    }
                }
            }, 100);
        }
    }

    /**
     * تهيئة زر Play/Pause
     */
    function initPlayPauseButton(swiper) {
        const playPauseBtn = document.querySelector('.hero-slider-play-pause');
        if (!playPauseBtn) {
            return;
        }

        playPauseBtn.addEventListener('click', function() {
            if (swiper.autoplay.running) {
                swiper.autoplay.stop();
                this.setAttribute('data-playing', 'false');
                this.setAttribute('aria-label', 'تشغيل التمرير التلقائي');
            } else {
                swiper.autoplay.start();
                this.setAttribute('data-playing', 'true');
                this.setAttribute('aria-label', 'إيقاف التمرير التلقائي');
            }
        });
    }

    /**
     * تحديث حالة زر Play/Pause
     */
    function updatePlayPauseButton(isPlaying) {
        const playPauseBtn = document.querySelector('.hero-slider-play-pause');
        if (playPauseBtn) {
            playPauseBtn.setAttribute('data-playing', isPlaying ? 'true' : 'false');
            playPauseBtn.setAttribute('aria-label', isPlaying ? 'إيقاف التمرير التلقائي' : 'تشغيل التمرير التلقائي');
        }
    }

    /**
     * اختصارات لوحة المفاتيح
     */
    function initKeyboardShortcuts(swiper) {
        document.addEventListener('keydown', function(e) {
            // التحقق من أن السلايدر مرئي
            const swiperEl = document.querySelector('.hero-swiper');
            if (!swiperEl || !isElementInViewport(swiperEl)) {
                return;
            }

            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    swiper.slideNext();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    swiper.slidePrev();
                    break;
                case 'Home':
                    e.preventDefault();
                    swiper.slideTo(0);
                    break;
                case 'End':
                    e.preventDefault();
                    swiper.slideTo(swiper.slides.length - 1);
                    break;
                case ' ': // Spacebar
                    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                        e.preventDefault();
                        if (swiper.autoplay.running) {
                            swiper.autoplay.stop();
                        } else {
                            swiper.autoplay.start();
                        }
                    }
                    break;
            }
        });
    }

    /**
     * إيقاف autoplay عند عدم ظهور الصفحة
     */
    function initVisibilityHandler(swiper) {
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                swiper.autoplay.stop();
            } else {
                // إعادة التشغيل فقط إذا كان مفعلاً من قبل
                const playPauseBtn = document.querySelector('.hero-slider-play-pause');
                if (playPauseBtn && playPauseBtn.getAttribute('data-playing') === 'true') {
                    swiper.autoplay.start();
                }
            }
        });
    }

    /**
     * التحقق من أن العنصر ظاهر في viewport
     */
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Export for external use if needed
    window.nadiimHeroSlider = {
        init: initHeroSlider
    };

})();
