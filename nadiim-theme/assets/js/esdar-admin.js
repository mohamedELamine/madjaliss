/**
 * سكربت إدارة الإصدارات (Admin Script)
 *
 * يدير ميتا بوكس الإصدارات في صفحة التحرير:
 * - إضافة/إزالة صفوف المؤلفين
 * - رفع الملفات والصور باستخدام wp.media
 * - التبديل بين أنواع المؤلفين
 *
 * @package Nadiim
 */

(function ($) {
    'use strict';

    // عند تحميل الصفحة
    $(document).ready(function () {
        initEsdarAdmin();
    });

    /**
     * تهيئة الإدارة
     */
    function initEsdarAdmin() {
        initAuthorRows();
        initFileUploader();
        initImageUploader();
    }

    /**
     * تهيئة صفوف المؤلفين
     */
    function initAuthorRows() {
        const $container = $('#esdar-authors-container');
        const $addButton = $('#esdar-add-author');
        let authorIndex = $container.find('.esdar-author-row').length;

        // التبديل بين نوع المؤلف
        $container.on('change', '.esdar-author-type', function () {
            const $row = $(this).closest('.esdar-author-row');
            const type = $(this).val();

            if (type === 'user') {
                $row.find('.esdar-author-user-field').show();
                $row.find('.esdar-author-free-field').hide();
                $row.find('.esdar-author-link-field').hide();
            } else {
                $row.find('.esdar-author-user-field').hide();
                $row.find('.esdar-author-free-field').show();
                $row.find('.esdar-author-link-field').show();
            }
        });

        // إضافة صف جديد
        $addButton.on('click', function (e) {
            e.preventDefault();

            if (typeof esdarAdmin === 'undefined' || !esdarAdmin.authorRowTemplate) {
                console.error('Author row template not found');
                return;
            }

            // استبدال {{INDEX}} بالرقم الحالي
            const newRow = esdarAdmin.authorRowTemplate.replace(/\{\{INDEX\}\}/g, authorIndex);

            $container.append(newRow);
            authorIndex++;
        });

        // إزالة صف
        $container.on('click', '.esdar-remove-author', function (e) {
            e.preventDefault();
            $(this).closest('.esdar-author-row').fadeOut(300, function () {
                $(this).remove();
            });
        });
    }

    /**
     * تهيئة رافع الملفات (wp.media)
     */
    function initFileUploader() {
        let fileFrame;

        // زر رفع الملف
        $(document).on('click', '.esdar-upload-file', function (e) {
            e.preventDefault();

            const $button = $(this);
            const target = $button.data('target');

            // إنشاء نافذة wp.media إذا لم تكن موجودة
            if (fileFrame) {
                fileFrame.open();
                return;
            }

            fileFrame = wp.media({
                title: esdarAdmin.strings.selectFile || 'اختر ملف',
                button: {
                    text: esdarAdmin.strings.useFile || 'استخدام هذا الملف',
                },
                multiple: false,
                library: {
                    type: ['application/pdf', 'application/epub+zip', 'application/x-mobipocket-ebook'],
                },
            });

            // عند اختيار ملف
            fileFrame.on('select', function () {
                const attachment = fileFrame.state().get('selection').first().toJSON();

                $('#esdar_release_file_id').val(attachment.id);
                $('#esdar_release_file_url').val(attachment.url);

                // عرض معلومات الملف
                const fileInfo = `
                    <div class="esdar-file-info">
                        <span class="dashicons dashicons-media-document"></span>
                        <span>${attachment.filename}</span>
                        <span class="file-size">(${formatFileSize(attachment.filesizeInBytes)})</span>
                    </div>
                `;

                // إزالة أي معلومات ملف قديمة
                $('.esdar-file-info').remove();

                // إضافة معلومات الملف الجديد
                $('.esdar-file-upload').after(fileInfo);
            });

            fileFrame.open();
        });

        // زر إزالة الملف
        $(document).on('click', '.esdar-remove-file', function (e) {
            e.preventDefault();

            $('#esdar_release_file_id').val('');
            $('#esdar_release_file_url').val('');
            $('.esdar-file-info').fadeOut(300, function () {
                $(this).remove();
            });
        });
    }

    /**
     * تهيئة رافع الصور (wp.media)
     */
    function initImageUploader() {
        let imageFrame;

        // زر إضافة صور معاينة
        $('#esdar-add-preview-images').on('click', function (e) {
            e.preventDefault();

            // إنشاء نافذة wp.media إذا لم تكن موجودة
            if (imageFrame) {
                imageFrame.open();
                return;
            }

            imageFrame = wp.media({
                title: esdarAdmin.strings.selectImages || 'اختر صور',
                button: {
                    text: esdarAdmin.strings.addImages || 'إضافة الصور المختارة',
                },
                multiple: true,
                library: {
                    type: 'image',
                },
            });

            // عند اختيار صور
            imageFrame.on('select', function () {
                const attachments = imageFrame.state().get('selection').toJSON();
                const $container = $('#esdar-preview-images-container');

                attachments.forEach(function (attachment) {
                    // التحقق من عدم وجود الصورة مسبقاً
                    if ($container.find('[data-id="' + attachment.id + '"]').length > 0) {
                        return;
                    }

                    // إضافة الصورة
                    const imageItem = `
                        <div class="esdar-gallery-item" data-id="${attachment.id}">
                            <img src="${attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url}" alt="" />
                            <button type="button" class="esdar-remove-image" data-id="${attachment.id}">
                                <span class="dashicons dashicons-no-alt"></span>
                            </button>
                            <input type="hidden" name="esdar_release_preview_images[]" value="${attachment.id}" />
                        </div>
                    `;

                    $container.append(imageItem);
                });
            });

            imageFrame.open();
        });

        // زر إزالة صورة
        $(document).on('click', '.esdar-remove-image', function (e) {
            e.preventDefault();

            const $item = $(this).closest('.esdar-gallery-item');

            $item.fadeOut(300, function () {
                $item.remove();
            });
        });
    }

    /**
     * تنسيق حجم الملف
     *
     * @param {number} bytes حجم الملف بالبايت
     * @return {string} حجم منسق
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

})(jQuery);
