/**
 * إدارة Meta Box للمشاركين في الحوارات
 *
 * الوظائف:
 * - إضافة مشارك جديد
 * - حذف مشارك
 * - تبديل الحقول حسب نوع المشارك (ضيف/مستخدم)
 * - رفع صورة باستخدام WordPress Media Library
 * - إزالة صورة
 *
 * @package Nadiim
 * @since 2.0.0
 */

(function($) {
    'use strict';

    var participantIndex = 0;

    $(document).ready(function() {

        // تعيين الـ index الحالي بناءً على عدد الصفوف الموجودة
        participantIndex = $('#howarat-participants-container .participant-row').length;

        /**
         * إضافة مشارك جديد
         */
        $('#add-participant-btn').on('click', function(e) {
            e.preventDefault();

            // جلب قالب HTML
            var template = $('#participant-row-template').html();

            // استبدال {{INDEX}} بالرقم الفعلي
            var newRow = template.replace(/\{\{INDEX\}\}/g, participantIndex);

            // إضافة الصف الجديد
            $('#howarat-participants-container').append(newRow);

            // زيادة العداد
            participantIndex++;
        });

        /**
         * حذف مشارك
         */
        $(document).on('click', '.remove-participant-btn', function(e) {
            e.preventDefault();

            if (confirm(howaratMeta.confirmDelete)) {
                $(this).closest('.participant-row').fadeOut(300, function() {
                    $(this).remove();
                    updateParticipantNumbers();
                });
            }
        });

        /**
         * تبديل الحقول حسب نوع المشارك
         */
        $(document).on('change', '.participant-type-select', function() {
            var $row = $(this).closest('.participant-row');
            var type = $(this).val();

            $row.find('.user-fields, .guest-fields').removeClass('active');

            if (type === 'user') {
                $row.find('.user-fields').addClass('active');
            } else {
                $row.find('.guest-fields').addClass('active');
            }
        });

        /**
         * رفع صورة
         */
        $(document).on('click', '.upload-photo-btn', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $row = $button.closest('.participant-row');
            var $photoIdInput = $row.find('.participant-photo-id');
            var $photoPreview = $row.find('.participant-photo-preview');
            var $removeBtn = $row.find('.remove-photo-btn');

            // فتح WordPress Media Uploader
            var mediaUploader = wp.media({
                title: howaratMeta.selectImage,
                button: {
                    text: howaratMeta.useImage
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            // عند اختيار الصورة
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                // تحديث الحقول
                $photoIdInput.val(attachment.id);

                // عرض الصورة
                var imageUrl = attachment.sizes && attachment.sizes.thumbnail
                    ? attachment.sizes.thumbnail.url
                    : attachment.url;

                $photoPreview.html('<img src="' + imageUrl + '" alt="">');
                $removeBtn.show();
            });

            // فتح النافذة
            mediaUploader.open();
        });

        /**
         * إزالة صورة
         */
        $(document).on('click', '.remove-photo-btn', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $row = $button.closest('.participant-row');
            var $photoIdInput = $row.find('.participant-photo-id');
            var $photoPreview = $row.find('.participant-photo-preview');

            // حذف القيم
            $photoIdInput.val('');
            $photoPreview.html('');
            $button.hide();
        });

        /**
         * تحديث أرقام المشاركين
         */
        function updateParticipantNumbers() {
            $('#howarat-participants-container .participant-row').each(function(index) {
                $(this).find('.participant-row-title').text('المشارك #' + (index + 1));
            });
        }

        /**
         * تحديث اسم المستخدم تلقائياً عند الاختيار
         */
        $(document).on('change', '.participant-user-select', function() {
            var $select = $(this);
            var selectedText = $select.find('option:selected').text();

            // يمكن استخدام هذا لتحديث حقل الاسم إذا لزم الأمر
            // لكن في الحفظ، سنعتمد على user_id لجلب البيانات من قاعدة البيانات
        });

        /**
         * التحقق من وجود بيانات قبل الإرسال (اختياري)
         */
        $('#post').on('submit', function() {
            // يمكن إضافة validation هنا إذا لزم الأمر
            return true;
        });

    });

})(jQuery);
