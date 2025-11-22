/**
 * سكريبت الواجهة الأمامية لصفحة الكاتب (Author Frontend JavaScript)
 *
 * يوفر:
 * - Sticky behavior للـToolbar
 * - تبديل الفلاتر وإعادة تحميل الصفحة
 * - Lazy loading للصور
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function() {
    'use strict';

    // ==========================================
    // الانتظار حتى يتم تحميل DOM
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {

        // ==========================================
        // Sticky Toolbar
        // ==========================================
        const toolbar = document.getElementById('author-toolbar');
        if (toolbar) {
            const toolbarOffsetTop = toolbar.offsetTop;

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > toolbarOffsetTop) {
                    toolbar.classList.add('is-sticky');
                } else {
                    toolbar.classList.remove('is-sticky');
                }
            });
        }

        // ==========================================
        // تبديل الفلاتر - إعادة تحميل الصفحة
        // ==========================================
        const contentTypeFilter = document.getElementById('content-type-filter');
        const orderbyFilter = document.getElementById('orderby-filter');

        function updateURL() {
            const currentURL = new URL(window.location.href);

            if (contentTypeFilter) {
                const contentType = contentTypeFilter.value;
                if (contentType && contentType !== 'all') {
                    currentURL.searchParams.set('content_type', contentType);
                } else {
                    currentURL.searchParams.delete('content_type');
                }
            }

            if (orderbyFilter) {
                const orderby = orderbyFilter.value;
                if (orderby && orderby !== 'date') {
                    currentURL.searchParams.set('orderby', orderby);
                } else {
                    currentURL.searchParams.delete('orderby');
                }
            }

            // إعادة تعيين الصفحة إلى 1 عند تغيير الفلتر
            currentURL.searchParams.delete('paged');

            // إعادة التوجيه إلى URL الجديد
            window.location.href = currentURL.toString();
        }

        if (contentTypeFilter) {
            contentTypeFilter.addEventListener('change', updateURL);
        }

        if (orderbyFilter) {
            orderbyFilter.addEventListener('change', updateURL);
        }

        // ==========================================
        // Lazy Loading للصور (Fallback)
        // ==========================================
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');

            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;

                        // إذا كانت الصورة لها data-src، نحمّلها
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }

                        // إذا كانت الصورة لها srcset
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }

                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        }

        // ==========================================
        // Smooth Scroll للروابط الداخلية
        // ==========================================
        const internalLinks = document.querySelectorAll('a[href^="#"]');
        internalLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();

                    const offsetTop = targetElement.offsetTop - 100; // مع مراعاة الـheader

                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });

                    // تحديث URL بدون إعادة تحميل
                    history.pushState(null, null, targetId);
                }
            });
        });

        // ==========================================
        // عرض رسالة "تم النسخ" عند نسخ الرابط
        // ==========================================
        const shareButtons = document.querySelectorAll('[data-action="copy-link"]');
        shareButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const link = this.dataset.url || window.location.href;

                // نسخ الرابط إلى الحافظة
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(link).then(function() {
                        showNotification('تم نسخ الرابط!');
                    }).catch(function(err) {
                    });
                }
            });
        });

        // ==========================================
        // دالة مساعدة لعرض الإشعارات
        // ==========================================
        function showNotification(message, type) {
            type = type || 'success';

            const notification = document.createElement('div');
            notification.className = 'notification notification-' + type;
            notification.textContent = message;
            notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #339063; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; animation: slideInRight 0.3s ease-out;';

            document.body.appendChild(notification);

            setTimeout(function() {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // ==========================================
        // عد الأحرف في textarea (إن وُجد)
        // ==========================================
        const textareas = document.querySelectorAll('textarea[maxlength]');
        textareas.forEach(function(textarea) {
            const maxLength = textarea.getAttribute('maxlength');

            if (maxLength) {
                const counter = document.createElement('div');
                counter.className = 'char-counter';
                counter.style.cssText = 'text-align: left; font-size: 12px; color: #6c757d; margin-top: 4px;';

                textarea.parentNode.insertBefore(counter, textarea.nextSibling);

                function updateCounter() {
                    const remaining = maxLength - textarea.value.length;
                    counter.textContent = remaining + ' حرف متبقي';

                    if (remaining < 20) {
                        counter.style.color = '#e74c3c';
                    } else {
                        counter.style.color = '#6c757d';
                    }
                }

                textarea.addEventListener('input', updateCounter);
                updateCounter();
            }
        });

        // ==========================================
        // تحسين الأداء - Debounce للبحث
        // ==========================================
        const searchInput = document.getElementById('author-search-input');
        if (searchInput) {
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                // إضافة visual feedback
                this.style.borderColor = '#339063';

                searchTimeout = setTimeout(function() {
                    searchInput.style.borderColor = '';
                }, 500);
            });
        }

        // ==========================================
        // Console log للتطوير
        // ==========================================
        if (nadiimAuthorVars && nadiimAuthorVars.debug === '1') {
        }

    }); // End DOMContentLoaded

    // ==========================================
    // CSS Animations (إضافة ديناميكياً)
    // ==========================================
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }

        img.loaded {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);

})();
