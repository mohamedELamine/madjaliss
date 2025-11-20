/**
 * Admin JavaScript for Articles Meta Box
 * Handles: Media Upload, Reading Time Calculation, Preview
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * تهيئة عند تحميل الصفحة
     */
    $(document).ready(function() {
        initMediaUploader();
        initReadingTimeCalculator();
        initAudioPreview();
        initAudioRemoval();
    });

    /**
     * تهيئة رافع الملفات (Media Uploader)
     */
    function initMediaUploader() {
        let mediaUploader;

        $('#nadiim-upload-audio').on('click', function(e) {
            e.preventDefault();

            // إذا كان الـ uploader موجود مسبقاً، افتحه
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // إنشاء Media Uploader جديد
            mediaUploader = wp.media({
                title: 'اختر ملف صوتي للمقال',
                button: {
                    text: 'استخدم هذا الملف'
                },
                library: {
                    type: 'audio' // فقط الملفات الصوتية
                },
                multiple: false
            });

            // عند اختيار الملف
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();

                // تحديث الحقول
                $('#nadiim_audio_attachment_id').val(attachment.id);
                $('#nadiim_audio_url').val(attachment.url);

                // محاولة الحصول على مدة الملف إن أمكن
                if (attachment.fileLength) {
                    const duration = formatDuration(attachment.fileLength);
                    $('#nadiim_audio_duration').val(duration);
                }

                // إظهار أزرار الإزالة والمعاينة
                $('#nadiim-remove-audio').show();
                $('#nadiim-preview-audio').show();

                // تحديث مصدر المعاينة
                $('#nadiim-audio-preview audio source').attr('src', attachment.url);
                $('#nadiim-audio-preview audio')[0].load();

                // إظهار معلومات الملف
                showAudioInfo(attachment);

                console.log('تم رفع الملف الصوتي:', attachment);
            });

            // فتح الـ uploader
            mediaUploader.open();
        });
    }

    /**
     * إزالة الملف الصوتي
     */
    function initAudioRemoval() {
        $('#nadiim-remove-audio').on('click', function(e) {
            e.preventDefault();

            if (!confirm('هل أنت متأكد من إزالة الملف الصوتي؟')) {
                return;
            }

            // تفريغ الحقول
            $('#nadiim_audio_attachment_id').val('');
            $('#nadiim_audio_url').val('');
            $('#nadiim_audio_duration').val('');

            // إخفاء الأزرار والمعاينة
            $('#nadiim-remove-audio').hide();
            $('#nadiim-preview-audio').hide();
            $('#nadiim-audio-preview').removeClass('active');
            $('.nadiim-audio-info').remove();

            console.log('تم إزالة الملف الصوتي');
        });
    }

    /**
     * معاينة الملف الصوتي
     */
    function initAudioPreview() {
        $('#nadiim-preview-audio').on('click', function(e) {
            e.preventDefault();

            const preview = $('#nadiim-audio-preview');
            const audio = preview.find('audio')[0];

            if (preview.hasClass('active')) {
                // إخفاء المعاينة وإيقاف التشغيل
                preview.removeClass('active');
                audio.pause();
                $(this).text('▶️ معاينة');
            } else {
                // إظهار المعاينة
                preview.addClass('active');
                $(this).text('⏸️ إخفاء المعاينة');
            }
        });

        // تحديث النص عند انتهاء التشغيل
        $('#nadiim-audio-preview audio').on('ended', function() {
            $('#nadiim-preview-audio').text('▶️ معاينة');
        });
    }

    /**
     * حساب وقت القراءة
     */
    function initReadingTimeCalculator() {
        // زر الحساب التلقائي
        $('#nadiim-calc-reading-time').on('click', function(e) {
            e.preventDefault();
            calculateReadingTime();
        });

        // زر استخدام الوقت التلقائي
        $('#nadiim-use-auto-time').on('click', function(e) {
            e.preventDefault();

            const autoTime = $('#nadiim-auto-time').text().replace(' دقيقة', '').trim();
            $('#nadiim_reading_time_manual').val(autoTime);

            // إظهار رسالة نجاح مؤقتة
            const $btn = $(this);
            const originalText = $btn.text();
            $btn.text('✓ تم النسخ!').prop('disabled', true);

            setTimeout(function() {
                $btn.text(originalText).prop('disabled', false);
            }, 2000);
        });
    }

    /**
     * حساب وقت القراءة من المحتوى
     */
    function calculateReadingTime() {
        let content = '';

        // الحصول على المحتوى من محرر ووردبريس
        // Classic Editor
        if (typeof tinymce !== 'undefined') {
            const editor = tinymce.get('content');
            if (editor) {
                content = editor.getContent({ format: 'text' });
            } else {
                content = $('#content').val();
            }
        }
        // Gutenberg
        else if (wp.data && wp.data.select('core/editor')) {
            const blocks = wp.data.select('core/editor').getBlocks();
            content = blocks.map(block => block.attributes.content || '').join(' ');
        }
        // Fallback
        else {
            content = $('#content').val();
        }

        // إزالة HTML tags
        content = stripHtml(content);

        // حساب عدد الكلمات
        const wordCount = countWords(content);

        // حساب وقت القراءة (200 كلمة/دقيقة)
        const readingTime = Math.ceil(wordCount / 200);

        // تحديث القيم
        $('#nadiim-word-count').text(wordCount);
        $('#nadiim-auto-time').text(readingTime + ' دقيقة');
        $('input[name="article_meta[reading_time_auto]"]').val(readingTime);

        // إظهار رسالة نجاح
        showNotification('تم حساب وقت القراءة: ' + readingTime + ' دقيقة من ' + wordCount + ' كلمة', 'success');

        console.log('عدد الكلمات:', wordCount, '- وقت القراءة:', readingTime);
    }

    /**
     * عد الكلمات (يدعم العربية والإنجليزية)
     */
    function countWords(text) {
        if (!text || text.trim() === '') {
            return 0;
        }

        // إزالة المسافات الزائدة
        text = text.trim().replace(/\s+/g, ' ');

        // تقسيم النص إلى كلمات
        const words = text.split(/[\s,،]+/);

        // تصفية الكلمات الفارغة
        return words.filter(word => word.length > 0).length;
    }

    /**
     * إزالة HTML tags
     */
    function stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    /**
     * تنسيق المدة من ثواني إلى دقائق:ثواني
     */
    function formatDuration(seconds) {
        if (!seconds) return '';

        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);

        return minutes + ':' + (secs < 10 ? '0' : '') + secs;
    }

    /**
     * إظهار معلومات الملف الصوتي
     */
    function showAudioInfo(attachment) {
        // إزالة المعلومات القديمة
        $('.nadiim-audio-info').remove();

        // إنشاء عنصر المعلومات
        const info = $('<div class="nadiim-audio-info"></div>');
        info.append('<span class="dashicons dashicons-controls-volumeon"></span>');
        info.append('<span>تم رفع: ' + attachment.filename + '</span>');

        // إضافة المعلومات بعد حقل URL
        $('#nadiim_audio_url').after(info);
    }

    /**
     * إظهار إشعار مؤقت
     */
    function showNotification(message, type) {
        const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        const notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

        // إضافة الإشعار
        $('.nadiim-article-meta-box').prepend(notice);

        // إزالة الإشعار بعد 5 ثواني
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * التحقق من الحقول قبل الحفظ (validation)
     */
    $(document).on('submit', '#post', function(e) {
        const audioUrl = $('#nadiim_audio_url').val();
        const audioDuration = $('#nadiim_audio_duration').val();

        // التحقق من صيغة مدة الملف
        if (audioDuration && !audioDuration.match(/^\d+:\d{2}$/)) {
            showNotification('صيغة مدة الملف غير صحيحة. استخدم صيغة دقائق:ثواني (مثال: 15:30)', 'error');
            $('#nadiim_audio_duration').focus();
            return false;
        }

        return true;
    });

    /**
     * تحديث تلقائي لوقت القراءة عند تغيير المحتوى (اختياري)
     * يمكن تفعيله أو تعطيله حسب الحاجة
     */
    function enableAutoCalculation() {
        // Classic Editor
        if (typeof tinymce !== 'undefined') {
            tinymce.on('AddEditor', function(e) {
                e.editor.on('change', debounce(calculateReadingTime, 2000));
            });
        }

        // Gutenberg
        if (wp.data && wp.data.subscribe) {
            let previousContent = '';
            wp.data.subscribe(debounce(function() {
                const editor = wp.data.select('core/editor');
                if (!editor) return;

                const currentContent = editor.getEditedPostAttribute('content');
                if (currentContent !== previousContent) {
                    previousContent = currentContent;
                    calculateReadingTime();
                }
            }, 2000));
        }
    }

    /**
     * Debounce function لتقليل عدد الاستدعاءات
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // تفعيل الحساب التلقائي (اختياري - يمكن التعليق على هذا السطر)
    // enableAutoCalculation();

})(jQuery);
