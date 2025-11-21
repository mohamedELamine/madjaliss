/**
 * Home Slider JavaScript
 *
 * @package Madjaliss
 * @version 2.0 - Stage 1B
 */

(function() {
    'use strict';

    // الحصول على الإعدادات من wp_localize_script
    const settings = window.NADIIM_HOME || {
        autoplay: true,
        delay: 6000,
        show_cursor: true
    };

    /**
     * تهيئة Swiper
     */
    function initHeroSlider() {
        const swiperContainer = document.querySelector('.hero-swiper');

        if (!swiperContainer) {
            return;
        }

        // التأكد من تحميل Swiper
        if (typeof Swiper === 'undefined') {
            console.error('Swiper library is not loaded');
            return;
        }

        // تهيئة Swiper
        const heroSwiper = new Swiper('.hero-swiper', {
            // العدد الأساسي للشرائح
            slidesPerView: 1,
            spaceBetween: 28,

            // Loop
            loop: true,

            // Autoplay
            autoplay: settings.autoplay ? {
                delay: settings.delay,
                disableOnInteraction: true,
                pauseOnMouseEnter: true,
            } : false,

            // Keyboard navigation
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },

            // Lazy loading
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 2,
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '" role="button" tabindex="0" aria-label="الانتقال إلى الشريحة ' + (index + 1) + '"></span>';
                },
            },

            // Accessibility
            a11y: {
                enabled: true,
                prevSlideMessage: 'الشريحة السابقة',
                nextSlideMessage: 'الشريحة التالية',
                firstSlideMessage: 'هذه هي الشريحة الأولى',
                lastSlideMessage: 'هذه هي الشريحة الأخيرة',
                paginationBulletMessage: 'الانتقال إلى الشريحة {{index}}',
            },

            // Responsive breakpoints
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 1,
                    spaceBetween: 28,
                },
            },

            // Events
            on: {
                init: function() {
                    console.log('Hero Slider initialized');
                },

                slideChange: function() {
                    // يمكن إضافة تتبع الأحداث هنا
                },
            },
        });

        // ربط Play/Pause Button
        initPlayPauseButton(heroSwiper);

        // إيقاف autoplay عند التركيز على عنصر داخل الشريحة
        initFocusHandling(heroSwiper);

        // دعم لوحة المفاتيح للـ pagination bullets
        initPaginationKeyboard();

        return heroSwiper;
    }

    /**
     * تهيئة زر Play/Pause
     */
    function initPlayPauseButton(swiper) {
        const playPauseBtn = document.querySelector('.slider-play-pause');

        if (!playPauseBtn || !swiper.autoplay) {
            return;
        }

        playPauseBtn.addEventListener('click', function() {
            const isPlaying = this.getAttribute('data-playing') === 'true';

            if (isPlaying) {
                swiper.autoplay.stop();
                this.setAttribute('data-playing', 'false');
                this.setAttribute('aria-label', 'تشغيل السلايدر التلقائي');
            } else {
                swiper.autoplay.start();
                this.setAttribute('data-playing', 'true');
                this.setAttribute('aria-label', 'إيقاف السلايدر التلقائي');
            }
        });

        // تحديث حالة الزر عند إيقاف autoplay تلقائياً
        swiper.on('autoplayStop', function() {
            playPauseBtn.setAttribute('data-playing', 'false');
        });

        swiper.on('autoplayStart', function() {
            playPauseBtn.setAttribute('data-playing', 'true');
        });
    }

    /**
     * إيقاف autoplay عند التركيز على عنصر داخل الشريحة
     */
    function initFocusHandling(swiper) {
        const slides = document.querySelectorAll('.hero-swiper .swiper-slide');

        slides.forEach(function(slide) {
            const focusableElements = slide.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');

            focusableElements.forEach(function(element) {
                element.addEventListener('focus', function() {
                    if (swiper.autoplay && swiper.autoplay.running) {
                        swiper.autoplay.stop();
                    }
                });

                element.addEventListener('blur', function() {
                    // إعادة تشغيل autoplay بعد فترة قصيرة
                    setTimeout(function() {
                        if (swiper.autoplay && !swiper.autoplay.running) {
                            const playPauseBtn = document.querySelector('.slider-play-pause');
                            if (playPauseBtn && playPauseBtn.getAttribute('data-playing') === 'true') {
                                swiper.autoplay.start();
                            }
                        }
                    }, 100);
                });
            });
        });
    }

    /**
     * دعم لوحة المفاتيح لـ pagination bullets
     */
    function initPaginationKeyboard() {
        const bullets = document.querySelectorAll('.swiper-pagination-bullet');

        bullets.forEach(function(bullet) {
            bullet.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    }

    /**
     * تحميل صور Demo عند الحاجة
     */
    function loadDemoImages() {
        const demoImages = document.querySelectorAll('img[src*="demo/"]');

        demoImages.forEach(function(img) {
            // يمكن إضافة منطق لتحميل الصور التجريبية
            img.addEventListener('error', function() {
                // في حالة فشل تحميل الصورة، عرض placeholder
                this.style.display = 'none';
                const placeholder = this.parentElement.querySelector('.slider-card-placeholder');
                if (placeholder) {
                    placeholder.style.display = 'flex';
                }
            });
        });
    }

    /**
     * التهيئة الرئيسية
     */
    function init() {
        // الانتظار حتى تحميل DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initHeroSlider();
                loadDemoImages();
            });
        } else {
            initHeroSlider();
            loadDemoImages();
        }
    }

    // تشغيل السكريبت
    init();

})();
