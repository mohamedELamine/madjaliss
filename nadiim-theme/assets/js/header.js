/**
 * JavaScript للهيدر والشريط العلوي
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
        initMarquee();
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
        const menuToggle = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('.main-navigation');

        if (!menuToggle || !navigation) {
            return;
        }

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

        // إغلاق القائمة عند تغيير حجم الشاشة
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    navigation.classList.remove('is-active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }
            }, 250);
        });

        // إغلاق القائمة عند النقر خارجها
        document.addEventListener('click', function(event) {
            if (!navigation.contains(event.target) && !menuToggle.contains(event.target)) {
                if (navigation.classList.contains('is-active')) {
                    navigation.classList.remove('is-active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }
            }
        });
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
     * تفعيل حركة التمرير للشريط العلوي (Marquee)
     */
    function initMarquee() {
        const marqueeElements = document.querySelectorAll('.topbar-marquee');

        marqueeElements.forEach(function(marquee) {
            const speed = marquee.getAttribute('data-speed') || 50;
            const content = marquee.innerHTML;

            // تكرار المحتوى لضمان استمرارية الحركة
            marquee.innerHTML = content + ' ' + content;

            // تعديل سرعة الأنيميشن
            marquee.style.animationDuration = speed + 's';

            // إيقاف الحركة عند التمرير
            marquee.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });

            marquee.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
        });
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
            arrow.innerHTML = '<span class="screen-reader-text">فتح القائمة الفرعية</span><span aria-hidden="true">▼</span>';

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

    /**
     * دالة مساعدة لإضافة Smooth Scroll للروابط الداخلية
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                const href = this.getAttribute('href');

                // تجاهل الروابط الفارغة أو # ومنع السلوك الافتراضي
                if (href === '#' || href === '#0') {
                    event.preventDefault();
                    return;
                }

                const target = document.querySelector(href);

                if (target) {
                    event.preventDefault();

                    const headerHeight = document.querySelector('.site-header').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // تفعيل Smooth Scroll
    initSmoothScroll();

    /**
     * تحسين الأداء: Debounce Function
     */
    function debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * إضافة دعم للوحة المفاتيح للتنقل في القائمة
     */
    function initKeyboardNavigation() {
        const menuItems = document.querySelectorAll('.primary-menu a');

        menuItems.forEach(function(item, index) {
            item.addEventListener('keydown', function(event) {
                const parent = this.parentElement;
                const submenu = parent.querySelector('.sub-menu');

                // السهم للأسفل: فتح القائمة الفرعية
                if (event.key === 'ArrowDown' && submenu) {
                    event.preventDefault();
                    const firstLink = submenu.querySelector('a');
                    if (firstLink) {
                        firstLink.focus();
                    }
                }

                // السهم للأعلى: الانتقال للعنصر السابق
                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (index > 0) {
                        menuItems[index - 1].focus();
                    }
                }

                // السهم لليمين/اليسار: التنقل بين العناصر
                if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                    event.preventDefault();
                    const direction = event.key === 'ArrowRight' ? 1 : -1;
                    const nextIndex = index + direction;

                    if (menuItems[nextIndex]) {
                        menuItems[nextIndex].focus();
                    }
                }
            });
        });
    }

    initKeyboardNavigation();

    /**
     * إضافة class للـ body عند التمرير
     */
    let scrollTimer = null;
    window.addEventListener('scroll', function() {
        if (scrollTimer !== null) {
            clearTimeout(scrollTimer);
        }

        document.body.classList.add('is-scrolling');

        scrollTimer = setTimeout(function() {
            document.body.classList.remove('is-scrolling');
        }, 100);
    }, { passive: true });

})();
