/**
 * JavaScript للهيدر - تصميم جديد
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function() {
    'use strict';

    // الانتظار حتى يتم تحميل DOM
    document.addEventListener('DOMContentLoaded', function() {
        initStickyHeader();
        initMobileMenu();
        initSearchModal();
        initDropdownMenus();
    });

    /**
     * تفعيل الهيدر الثابت (Sticky Header)
     */
    function initStickyHeader() {
        const header = document.querySelector('.site-header.header-sticky');

        if (!header) {
            return;
        }

        let lastScrollTop = 0;
        const headerHeight = header.offsetHeight;
        let isStuck = false;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // إضافة/إزالة class is-stuck
            if (scrollTop > headerHeight) {
                if (!isStuck) {
                    header.classList.add('is-stuck');
                    isStuck = true;
                }
            } else {
                if (isStuck) {
                    header.classList.remove('is-stuck');
                    isStuck = false;
                }
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }, { passive: true });
    }

    /**
     * تفعيل قائمة الموبايل
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const navigation = document.querySelector('.main-navigation');

        if (!menuToggle || !navigation) {
            return;
        }

        // فتح/إغلاق القائمة
        menuToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

            this.setAttribute('aria-expanded', !isExpanded);
            navigation.classList.toggle('is-active');

            // منع التمرير عند فتح القائمة
            if (!isExpanded) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // إغلاق القائمة عند النقر خارجها
        navigation.addEventListener('click', function(event) {
            if (event.target === this) {
                closeMenu();
            }
        });

        // إغلاق القائمة عند تغيير حجم الشاشة
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    closeMenu();
                }
            }, 250);
        });

        // دالة إغلاق القائمة
        function closeMenu() {
            navigation.classList.remove('is-active');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
    }

    /**
     * تفعيل نافذة البحث المنبثقة
     */
    function initSearchModal() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchModal = document.getElementById('search-modal');
        const searchClose = document.querySelector('.search-close');
        const searchOverlay = document.querySelector('.search-modal-overlay');

        if (!searchToggle || !searchModal) {
            return;
        }

        // فتح نافذة البحث
        searchToggle.addEventListener('click', function() {
            openSearchModal();
        });

        // إغلاق نافذة البحث
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                closeSearchModal();
            });
        }

        // إغلاق عند النقر على الخلفية
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function() {
                closeSearchModal();
            });
        }

        // إغلاق عند الضغط على ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && searchModal.classList.contains('is-active')) {
                closeSearchModal();
            }
        });

        function openSearchModal() {
            searchModal.classList.add('is-active');
            searchToggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';

            // التركيز على حقل البحث
            setTimeout(function() {
                const searchField = searchModal.querySelector('.search-field');
                if (searchField) {
                    searchField.focus();
                }
            }, 100);
        }

        function closeSearchModal() {
            searchModal.classList.remove('is-active');
            searchToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
    }

    /**
     * تفعيل القوائم المنسدلة (Dropdown Menus) للموبايل
     */
    function initDropdownMenus() {
        // فقط في شاشات الموبايل
        if (window.innerWidth > 768) {
            return;
        }

        const menuItems = document.querySelectorAll('.primary-menu .menu-item-has-children');

        menuItems.forEach(function(item) {
            const link = item.querySelector('a');

            if (!link) {
                return;
            }

            // إضافة سهم للعناصر التي لها قوائم فرعية
            const arrow = document.createElement('button');
            arrow.className = 'submenu-toggle';
            arrow.setAttribute('aria-expanded', 'false');
            arrow.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
            arrow.style.cssText = 'background: none; border: none; padding: 16px 12px; cursor: pointer; color: inherit;';

            link.parentNode.insertBefore(arrow, link.nextSibling);

            arrow.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                // إغلاق جميع القوائم الفرعية الأخرى
                menuItems.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.classList.remove('is-open');
                        const otherToggle = otherItem.querySelector('.submenu-toggle');
                        if (otherToggle) {
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                // تبديل حالة القائمة الحالية
                this.setAttribute('aria-expanded', !isExpanded);
                item.classList.toggle('is-open');
            });
        });
    }

})();
