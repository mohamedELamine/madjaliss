/**
 * Customizer Live Preview for Header
 *
 * @package Madjaliss
 * @version 2.0
 */

(function($) {
    'use strict';

    // عرض الشعار
    wp.customize('header_logo_width', function(value) {
        value.bind(function(newval) {
            $('.custom-logo').css('width', newval + 'px');
        });
    });

    // هامش الشعار
    wp.customize('header_logo_margin', function(value) {
        value.bind(function(newval) {
            $('.custom-logo').css('margin', newval + 'px');
        });
    });

    // لون خلفية الهيدر
    wp.customize('header_bg_color', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('background-color', newval);
            $('head').append('<style>.primary-menu .sub-menu{background-color:' + newval + '!important;}</style>');
        });
    });

    // لون نص الهيدر
    wp.customize('header_text_color', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('color', newval);
        });
    });

    // لون روابط القائمة
    wp.customize('header_link_color', function(value) {
        value.bind(function(newval) {
            $('.primary-menu > li > a').css('color', newval);
        });
    });

    // لون الروابط عند التمرير
    wp.customize('header_link_hover_color', function(value) {
        value.bind(function(newval) {
            $('head').append('<style>.primary-menu > li > a:hover, .primary-menu > li.current-menu-item > a{color:' + newval + '!important;}</style>');
        });
    });

    // لون الحد السفلي
    wp.customize('header_border_bottom_color', function(value) {
        value.bind(function(newval) {
            $('.site-header').css('border-bottom-color', newval);
        });
    });

    // محاذاة القائمة
    wp.customize('header_menu_alignment', function(value) {
        value.bind(function(newval) {
            $('.main-navigation').css('text-align', newval);
        });
    });

    // المسافة بين عناصر القائمة
    wp.customize('header_menu_item_spacing', function(value) {
        value.bind(function(newval) {
            $('.primary-menu').css('gap', newval + 'px');
        });
    });

    // تفعيل ظل Sticky Header
    wp.customize('header_sticky_shadow_enable', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.site-header.is-sticky').css('box-shadow', '0 2px 20px rgba(0,0,0,0.08)');
            } else {
                $('.site-header.is-sticky').css('box-shadow', 'none');
            }
        });
    });

})(jQuery);
