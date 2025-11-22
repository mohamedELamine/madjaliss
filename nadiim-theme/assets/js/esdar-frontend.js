/**
 * سكربت الواجهة الأمامية لقسم الإصدارات
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * معالج زر التحميل
     */
    function handleDownloadButtons() {
        $('.esdar-download-btn').on('click', function(e) {
            var downloadUrl = $(this).data('download-url');

            if (!downloadUrl) {
                e.preventDefault();
                return;
            }

            // يمكن إضافة تتبع التحميلات هنا إذا لزم الأمر
            // مثلاً إرسال AJAX request لتسجيل عملية التحميل
        });
    }

    /**
     * تهيئة السكربت عند جاهزية DOM
     */
    $(document).ready(function() {
        handleDownloadButtons();
    });

})(jQuery);
