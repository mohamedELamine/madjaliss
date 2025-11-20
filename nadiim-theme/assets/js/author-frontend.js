/**
 * سكريبت الواجهة الأمامية لصفحة الكاتب (Author Frontend JavaScript)
 *
 * يوفر:
 * - إدارة Modal نموذج الاتصال
 * - Sticky behavior للـToolbar
 * - تبديل الفلاتر وإعادة تحميل الصفحة
 * - إرسال نموذج الاتصال عبر AJAX
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
        // Modal: نموذج مراسلة الكاتب
        // ==========================================
        const modal = document.getElementById('contact-author-modal');
        if (modal) {
            const openButtons = document.querySelectorAll('.btn-contact');
            const closeButtons = modal.querySelectorAll('.modal-close');
            const overlay = modal.querySelector('.modal-overlay');
            const contactForm = document.getElementById('contact-author-form');

            // فتح Modal
            openButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';

                    // تركيز على أول حقل
                    setTimeout(function() {
                        const firstInput = contactForm.querySelector('input[type="text"]');
                        if (firstInput) {
                            firstInput.focus();
                        }
                    }, 100);

                    // إضافة الـ ARIA
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            // إغلاق Modal
            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                modal.setAttribute('aria-hidden', 'true');

                // إعادة تعيين النموذج
                if (contactForm) {
                    contactForm.reset();
                    const responseDiv = contactForm.querySelector('.form-response');
                    if (responseDiv) {
                        responseDiv.style.display = 'none';
                        responseDiv.className = 'form-response';
                        responseDiv.textContent = '';
                    }
                }
            }

            closeButtons.forEach(function(button) {
                button.addEventListener('click', closeModal);
            });

            if (overlay) {
                overlay.addEventListener('click', closeModal);
            }

            // إغلاق بالضغط على Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeModal();
                }
            });

            // إرسال النموذج عبر AJAX
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(contactForm);
                    const responseDiv = contactForm.querySelector('.form-response');
                    const submitButton = contactForm.querySelector('button[type="submit"]');

                    // تعطيل زر الإرسال
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'جارٍ الإرسال...';
                    }

                    // إخفاء رسالة الاستجابة السابقة
                    if (responseDiv) {
                        responseDiv.style.display = 'none';
                    }

                    // إعداد البيانات للإرسال
                    const data = {
                        action: 'nadiim_contact_author',
                        nonce: nadiimAuthorVars.contactNonce,
                        author_id: formData.get('author_id'),
                        sender_name: formData.get('sender_name'),
                        sender_email: formData.get('sender_email'),
                        message: formData.get('message')
                    };

                    // إرسال الطلب
                    fetch(nadiimAuthorVars.ajaxUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(data)
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(result) {
                        if (responseDiv) {
                            responseDiv.style.display = 'block';

                            if (result.success) {
                                responseDiv.className = 'form-response success';
                                responseDiv.textContent = result.data.message;

                                // إعادة تعيين النموذج بعد النجاح
                                setTimeout(function() {
                                    closeModal();
                                }, 2000);
                            } else {
                                responseDiv.className = 'form-response error';
                                responseDiv.textContent = result.data.message || 'حدث خطأ، يرجى المحاولة مرة أخرى';
                            }
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        if (responseDiv) {
                            responseDiv.style.display = 'block';
                            responseDiv.className = 'form-response error';
                            responseDiv.textContent = 'حدث خطأ في الاتصال، يرجى المحاولة لاحقاً';
                        }
                    })
                    .finally(function() {
                        // إعادة تفعيل زر الإرسال
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.textContent = 'إرسال الرسالة';
                        }
                    });
                });
            }
        }

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
        // تحسين Accessibility - Focus Trap في Modal
        // ==========================================
        if (modal) {
            const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';

            modal.addEventListener('keydown', function(e) {
                if (e.key !== 'Tab') return;

                const focusables = modal.querySelectorAll(focusableElements);
                const firstFocusable = focusables[0];
                const lastFocusable = focusables[focusables.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            });
        }

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
                        console.error('Failed to copy:', err);
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
            console.log('Nadiim Author Page Scripts Loaded');
            console.log('AJAX URL:', nadiimAuthorVars.ajaxUrl);
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
