/**
 * Custom Cursor JavaScript
 *
 * @package Madjaliss
 * @version 2.0 - Stage 1B
 */

(function() {
    'use strict';

    // الحصول على الإعدادات من wp_localize_script
    const settings = window.NADIIM_HOME || {
        show_cursor: true
    };

    // التحقق من reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // التحقق من إلغاء الكرسر يدوياً
    const disableCursor = window.NADIIM_DISABLE_CURSOR === true;

    // إذا كان المستخدم يفضل reduced motion أو تم إلغاء الكرسر، لا نفعّله
    if (prefersReducedMotion || disableCursor || !settings.show_cursor) {
        return;
    }

    // التحقق من دعم hover (الأجهزة التي تدعم المؤشر)
    const supportsHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    if (!supportsHover) {
        return;
    }

    /**
     * كلاس الكرسر المخصص
     */
    class CustomCursor {
        constructor() {
            this.innerCursor = null;
            this.outerCursor = null;
            this.mouseX = 0;
            this.mouseY = 0;
            this.innerX = 0;
            this.innerY = 0;
            this.outerX = 0;
            this.outerY = 0;
            this.isHovering = false;
            this.animationId = null;

            this.init();
        }

        /**
         * تهيئة الكرسر
         */
        init() {
            // إنشاء عناصر الكرسر
            this.createCursorElements();

            // إضافة class للـ body
            document.body.classList.add('has-custom-cursor');

            // ربط الأحداث
            this.bindEvents();

            // بدء حلقة الرسوم المتحركة
            this.startAnimation();
        }

        /**
         * إنشاء عناصر الكرسر في DOM
         */
        createCursorElements() {
            // Inner cursor (الدائرة الصغيرة)
            this.innerCursor = document.createElement('div');
            this.innerCursor.className = 'nadiim-cursor-inner';
            document.body.appendChild(this.innerCursor);

            // Outer cursor (الدائرة الكبيرة)
            this.outerCursor = document.createElement('div');
            this.outerCursor.className = 'nadiim-cursor-outer';
            document.body.appendChild(this.outerCursor);
        }

        /**
         * ربط أحداث الفأرة
         */
        bindEvents() {
            // تتبع حركة الفأرة
            document.addEventListener('mousemove', (e) => {
                this.mouseX = e.clientX;
                this.mouseY = e.clientY;
            });

            // الكشف عن العناصر القابلة للنقر
            const hoverTargets = 'a, button, .btn, .swiper-slide .btn-read, .swiper-button-prev, .swiper-button-next, .swiper-pagination-bullet';

            document.addEventListener('mouseover', (e) => {
                if (e.target.closest(hoverTargets)) {
                    this.setHoverState(true);
                }
            });

            document.addEventListener('mouseout', (e) => {
                if (e.target.closest(hoverTargets)) {
                    this.setHoverState(false);
                }
            });

            // إخفاء الكرسر عند مغادرة النافذة
            document.addEventListener('mouseleave', () => {
                this.innerCursor.style.opacity = '0';
                this.outerCursor.style.opacity = '0';
            });

            document.addEventListener('mouseenter', () => {
                this.innerCursor.style.opacity = '1';
                this.outerCursor.style.opacity = '0.5';
            });
        }

        /**
         * تعيين حالة hover
         */
        setHoverState(isHovering) {
            this.isHovering = isHovering;

            if (isHovering) {
                this.innerCursor.classList.add('hover');
                this.outerCursor.classList.add('hover');
            } else {
                this.innerCursor.classList.remove('hover');
                this.outerCursor.classList.remove('hover');
            }
        }

        /**
         * Linear interpolation للحركة الناعمة
         */
        lerp(start, end, factor) {
            return start + (end - start) * factor;
        }

        /**
         * تحديث موقع الكرسر
         */
        updateCursor() {
            // Inner cursor يتبع الفأرة مباشرة
            this.innerX = this.mouseX;
            this.innerY = this.mouseY;

            // Outer cursor يتبع بحركة ناعمة (lerp)
            this.outerX = this.lerp(this.outerX, this.mouseX, 0.15);
            this.outerY = this.lerp(this.outerY, this.mouseY, 0.15);

            // تطبيق المواقع
            this.innerCursor.style.left = this.innerX + 'px';
            this.innerCursor.style.top = this.innerY + 'px';

            this.outerCursor.style.left = this.outerX + 'px';
            this.outerCursor.style.top = this.outerY + 'px';
        }

        /**
         * بدء حلقة الرسوم المتحركة
         */
        startAnimation() {
            const animate = () => {
                this.updateCursor();
                this.animationId = requestAnimationFrame(animate);
            };

            animate();
        }

        /**
         * إيقاف الكرسر
         */
        destroy() {
            if (this.animationId) {
                cancelAnimationFrame(this.animationId);
            }

            if (this.innerCursor) {
                this.innerCursor.remove();
            }

            if (this.outerCursor) {
                this.outerCursor.remove();
            }

            document.body.classList.remove('has-custom-cursor');
        }
    }

    /**
     * تهيئة الكرسر المخصص
     */
    function init() {
        // الانتظار حتى تحميل DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                new CustomCursor();
            });
        } else {
            new CustomCursor();
        }
    }

    // تشغيل السكريبت
    init();

})();
