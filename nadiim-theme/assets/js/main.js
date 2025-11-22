/**
 * ملف JavaScript الرئيسي لقالب نديم
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * تفعيل القائمة للهواتف
     */
    function initMobileMenu() {
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.main-navigation').toggleClass('toggled');
            $(this).attr('aria-expanded', $('.main-navigation').hasClass('toggled'));
        });
    }

    /**
     * تفعيل نموذج البحث المنبثق
     */
    function initSearchModal() {
        // فتح نموذج البحث
        $('.search-toggle').on('click', function(e) {
            e.preventDefault();
            $('#search-modal').addClass('active');
            $('#search-modal .search-field').focus();
        });

        // إغلاق نموذج البحث
        $('.search-close').on('click', function(e) {
            e.preventDefault();
            $('#search-modal').removeClass('active');
        });

        // إغلاق عند الضغط على Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#search-modal').hasClass('active')) {
                $('#search-modal').removeClass('active');
            }
        });

        // إغلاق عند الضغط على الخلفية
        $('#search-modal').on('click', function(e) {
            if ($(e.target).is('#search-modal')) {
                $(this).removeClass('active');
            }
        });
    }

    /**
     * زر العودة إلى الأعلى
     */
    function initBackToTop() {
        var $backToTop = $('#back-to-top');

        // إظهار/إخفاء الزر عند التمرير
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.addClass('visible');
            } else {
                $backToTop.removeClass('visible');
            }
        });

        // التمرير إلى الأعلى عند الضغط
        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });
    }

    /**
     * تحسين القوائم الفرعية للوصولية
     */
    function improveSubmenuAccessibility() {
        $('.menu-item-has-children > a').on('focus', function() {
            $(this).siblings('.sub-menu').addClass('focused');
        });

        $('.menu-item-has-children > a').on('blur', function() {
            $(this).siblings('.sub-menu').removeClass('focused');
        });
    }

    /**
     * Lazy Loading للصور
     */
    function initLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            // المتصفح يدعم lazy loading
            var images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(function(img) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
            });
        } else {
            // استخدام Intersection Observer للمتصفحات القديمة
            if ('IntersectionObserver' in window) {
                var lazyImages = document.querySelectorAll('img[loading="lazy"]');

                var imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                            }
                            if (img.dataset.srcset) {
                                img.srcset = img.dataset.srcset;
                            }
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        }
    }

    /**
     * تحسين التمرير السلس
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            var href = $(this).attr('href');

            // تجاهل الروابط الفارغة أو #
            if (href === '#' || href === '#0') {
                return;
            }

            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }
        });
    }

    /**
     * إضافة أنيميشن عند التمرير
     */
    function initScrollAnimations() {
        if ('IntersectionObserver' in window) {
            var animateElements = document.querySelectorAll('.card, .section-title');

            var animationObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                        animationObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            animateElements.forEach(function(el) {
                el.classList.add('animate-ready');
                animationObserver.observe(el);
            });
        }
    }

    /**
     * تحسين التبويبات (للاستخدام في صفحة الكاتب)
     */
    function initTabs() {
        $('.tabs-nav a').on('click', function(e) {
            e.preventDefault();

            var $this = $(this);
            var target = $this.attr('href');

            // تحديث التبويبات
            $this.parent().addClass('active').siblings().removeClass('active');

            // إظهار المحتوى المطلوب
            $(target).addClass('active').siblings('.tab-content').removeClass('active');
        });
    }

    /**
     * التهيئة عند جاهزية الصفحة
     */
    $(document).ready(function() {
        initMobileMenu();
        initSearchModal();
        initBackToTop();
        improveSubmenuAccessibility();
        initLazyLoading();
        initSmoothScroll();
        initScrollAnimations();
        initTabs();

        // إخفاء Topbar بعد 5 ثواني (اختياري)
        if ($('.topbar').length && $('.topbar').data('auto-hide')) {
            setTimeout(function() {
                $('.topbar').slideUp();
            }, 5000);
        }
    });

    /**
     * التهيئة عند تحميل الصفحة بالكامل
     */
    $(window).on('load', function() {
        // إخفاء loader إن وُجد
        $('.page-loader').fadeOut();
    });

})(jQuery);
