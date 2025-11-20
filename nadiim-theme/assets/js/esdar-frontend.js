/**
 * سكربت الواجهة الأمامية للإصدارات (Frontend Script)
 *
 * يدير:
 * - AJAX لزيادة عداد التحميل
 * - زر المعاينة والتمرير السلس
 * - lightbox بسيط للصور
 *
 * @package Nadiim
 */

(function () {
    'use strict';

    // عند تحميل DOM
    document.addEventListener('DOMContentLoaded', function () {
        initDownloadTracking();
        initPreviewButton();
        initLightbox();
    });

    /**
     * تتبع التحميلات
     */
    function initDownloadTracking() {
        const downloadButtons = document.querySelectorAll('.esdar-download-btn, .esdar-card-download-btn');

        downloadButtons.forEach(function (button) {
            button.addEventListener('click', function (e) {
                const postId = this.getAttribute('data-post-id');

                if (!postId) {
                    return;
                }

                // إرسال طلب AJAX لزيادة العداد
                // لا نوقف التحميل، فقط نسجل العدد
                incrementDownloadCount(postId);
            });
        });
    }

    /**
     * زيادة عداد التحميل عبر AJAX
     *
     * @param {number} postId معرف المنشور
     */
    function incrementDownloadCount(postId) {
        // التحقق من وجود jQuery و AJAX settings
        if (typeof jQuery === 'undefined' || typeof wp === 'undefined' || typeof wp.ajax === 'undefined') {
            return;
        }

        jQuery.ajax({
            url: wp.ajax.settings.url,
            type: 'POST',
            data: {
                action: 'increment_download_count',
                post_id: postId,
                nonce: getDownloadNonce(),
            },
            success: function (response) {
                if (response.success) {
                    console.log('Download count incremented for post ' + postId);
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to increment download count:', error);
            },
        });
    }

    /**
     * الحصول على nonce للتحميل
     * يجب تمريره من PHP عبر wp_localize_script
     *
     * @return {string}
     */
    function getDownloadNonce() {
        if (typeof esdarFrontend !== 'undefined' && esdarFrontend.downloadNonce) {
            return esdarFrontend.downloadNonce;
        }
        return '';
    }

    /**
     * تهيئة زر المعاينة
     */
    function initPreviewButton() {
        const previewButton = document.querySelector('.esdar-preview-btn');

        if (!previewButton) {
            return;
        }

        previewButton.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('data-target');

            if (!targetId) {
                return;
            }

            const targetElement = document.getElementById(targetId);

            if (!targetElement) {
                return;
            }

            // التمرير السلس إلى قسم المعاينة
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });

            // إضافة تأثير تمييز
            targetElement.classList.add('esdar-highlight');

            setTimeout(function () {
                targetElement.classList.remove('esdar-highlight');
            }, 2000);
        });
    }

    /**
     * تهيئة lightbox بسيط للصور
     */
    function initLightbox() {
        const previewLinks = document.querySelectorAll('.esdar-preview-link');

        if (previewLinks.length === 0) {
            return;
        }

        previewLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const imageSrc = this.getAttribute('href');

                if (!imageSrc) {
                    return;
                }

                openLightbox(imageSrc);
            });
        });
    }

    /**
     * فتح lightbox
     *
     * @param {string} imageSrc مصدر الصورة
     */
    function openLightbox(imageSrc) {
        // إنشاء عناصر lightbox
        const overlay = document.createElement('div');
        overlay.className = 'esdar-lightbox-overlay';

        const lightbox = document.createElement('div');
        lightbox.className = 'esdar-lightbox';

        const closeButton = document.createElement('button');
        closeButton.className = 'esdar-lightbox-close';
        closeButton.innerHTML = '&times;';
        closeButton.setAttribute('aria-label', 'إغلاق');

        const image = document.createElement('img');
        image.src = imageSrc;
        image.className = 'esdar-lightbox-image';
        image.alt = '';

        // تجميع العناصر
        lightbox.appendChild(closeButton);
        lightbox.appendChild(image);
        overlay.appendChild(lightbox);
        document.body.appendChild(overlay);

        // منع التمرير
        document.body.style.overflow = 'hidden';

        // إضافة الأنماط inline (للتأكد من العمل حتى بدون CSS)
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            animation: fadeIn 0.3s ease;
        `;

        lightbox.style.cssText = `
            position: relative;
            max-width: 90%;
            max-height: 90%;
        `;

        closeButton.style.cssText = `
            position: absolute;
            top: -40px;
            right: 0;
            background: transparent;
            border: none;
            color: white;
            font-size: 40px;
            cursor: pointer;
            z-index: 1;
            line-height: 1;
            padding: 0;
            width: 40px;
            height: 40px;
        `;

        image.style.cssText = `
            max-width: 100%;
            max-height: 90vh;
            display: block;
            border-radius: 8px;
        `;

        // إغلاق عند النقر على الخلفية
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                closeLightbox(overlay);
            }
        });

        // إغلاق عند النقر على زر الإغلاق
        closeButton.addEventListener('click', function () {
            closeLightbox(overlay);
        });

        // إغلاق عند الضغط على ESC
        document.addEventListener('keydown', function escHandler(e) {
            if (e.key === 'Escape') {
                closeLightbox(overlay);
                document.removeEventListener('keydown', escHandler);
            }
        });
    }

    /**
     * إغلاق lightbox
     *
     * @param {HTMLElement} overlay عنصر الخلفية
     */
    function closeLightbox(overlay) {
        overlay.style.animation = 'fadeOut 0.3s ease';

        setTimeout(function () {
            if (overlay && overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
            document.body.style.overflow = '';
        }, 300);
    }

    // إضافة CSS للأنيميشن
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        .esdar-highlight {
            animation: highlight 2s ease;
        }
        @keyframes highlight {
            0%, 100% { background: transparent; }
            50% { background: rgba(51, 144, 99, 0.1); }
        }
    `;
    document.head.appendChild(style);

})();
