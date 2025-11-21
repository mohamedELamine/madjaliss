/**
 * Header JavaScript
 *
 * @package Madjaliss
 * @version 2.0
 */

(function() {
    'use strict';

    /**
     * Sticky Header Functionality
     */
    function initStickyHeader() {
        const header = document.querySelector('.site-header.header-sticky-enabled');

        if (!header) return;

        let lastScrollTop = 0;
        let ticking = false;

        function updateStickyState() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // إضافة class عند التمرير لأكثر من 100px
            if (scrollTop > 100) {
                header.classList.add('is-sticky');
            } else {
                header.classList.remove('is-sticky');
            }

            lastScrollTop = scrollTop;
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(updateStickyState);
                ticking = true;
            }
        }, { passive: true });
    }

    /**
     * Search Overlay Functionality
     */
    function initSearchOverlay() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.querySelector('.search-close');
        const searchField = document.querySelector('.search-field');

        if (!searchToggle || !searchOverlay) return;

        // فتح البحث
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            openSearch();
        });

        // إغلاق البحث
        if (searchClose) {
            searchClose.addEventListener('click', function(e) {
                e.preventDefault();
                closeSearch();
            });
        }

        // إغلاق عند النقر خارج النافذة
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearch();
            }
        });

        // إغلاق بزر ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearch();
            }
        });

        function openSearch() {
            searchOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            searchToggle.setAttribute('aria-expanded', 'true');

            // التركيز على حقل البحث بعد فتح النافذة
            setTimeout(function() {
                if (searchField) {
                    searchField.focus();
                }
            }, 100);
        }

        function closeSearch() {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
            searchToggle.setAttribute('aria-expanded', 'false');
        }
    }

    /**
     * Dropdown Menu Enhancement
     * تحسين قوائم الـ dropdown للمس على الأجهزة المحمولة
     */
    function initDropdownMenus() {
        const menuItems = document.querySelectorAll('.primary-menu > li.menu-item-has-children');

        menuItems.forEach(function(item) {
            const link = item.querySelector('a');
            let touchStarted = false;

            // على الأجهزة التي تدعم اللمس
            if ('ontouchstart' in window) {
                link.addEventListener('touchstart', function(e) {
                    // إذا لم تكن القائمة مفتوحة، نمنع الرابط ونفتح القائمة
                    if (!item.classList.contains('touch-active')) {
                        e.preventDefault();

                        // إغلاق جميع القوائم الأخرى
                        menuItems.forEach(function(otherItem) {
                            if (otherItem !== item) {
                                otherItem.classList.remove('touch-active');
                            }
                        });

                        item.classList.add('touch-active');
                        touchStarted = true;
                    }
                }, { passive: false });
            }
        });

        // إغلاق القوائم عند النقر خارجها
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.primary-menu')) {
                menuItems.forEach(function(item) {
                    item.classList.remove('touch-active');
                });
            }
        });
    }

    /**
     * Accessibility Enhancements
     * تحسينات لإمكانية الوصول
     */
    function initAccessibility() {
        // إضافة keyboard navigation للقوائم المنسدلة
        const menuLinks = document.querySelectorAll('.primary-menu a');

        menuLinks.forEach(function(link, index) {
            link.addEventListener('focus', function() {
                // إظهار القائمة المنسدلة عند التركيز
                const parentLi = link.closest('li');
                if (parentLi && parentLi.classList.contains('menu-item-has-children')) {
                    parentLi.classList.add('focus-within');
                }
            });

            link.addEventListener('blur', function() {
                // إخفاء القائمة عند فقدان التركيز
                setTimeout(function() {
                    const parentLi = link.closest('li');
                    if (parentLi && !parentLi.contains(document.activeElement)) {
                        parentLi.classList.remove('focus-within');
                    }
                }, 100);
            });
        });
    }

    /**
     * Initialize all header functionality
     */
    function init() {
        // الانتظار حتى تحميل DOM بالكامل
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initStickyHeader();
                initSearchOverlay();
                initDropdownMenus();
                initAccessibility();
            });
        } else {
            // DOM محمل بالفعل
            initStickyHeader();
            initSearchOverlay();
            initDropdownMenus();
            initAccessibility();
        }
    }

    // تهيئة السكريبت
    init();

})();
