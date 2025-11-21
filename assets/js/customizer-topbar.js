/**
 * Customizer Live Preview for Top Bar
 *
 * @package Madjaliss
 * @version 2.0
 */

(function($) {
    'use strict';

    // لون خلفية الشريط العلوي
    wp.customize('topbar_bg_color', function(value) {
        value.bind(function(newval) {
            $('.site-topbar').css('background-color', newval);
        });
    });

    // لون نص الشريط العلوي
    wp.customize('topbar_text_color', function(value) {
        value.bind(function(newval) {
            $('.site-topbar').css('color', newval);
        });
    });

    // حجم الخط
    wp.customize('topbar_font_size', function(value) {
        value.bind(function(newval) {
            $('.topbar-inner').css('font-size', newval + 'px');
        });
    });

    // محاذاة النص
    wp.customize('topbar_alignment', function(value) {
        value.bind(function(newval) {
            $('.topbar-inner').css('text-align', newval);
        });
    });

})(jQuery);
