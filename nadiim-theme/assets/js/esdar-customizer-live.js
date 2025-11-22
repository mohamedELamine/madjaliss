/**
 * Esdar Customizer Live Preview
 * المعاينة الحية لإعدادات قسم الإصدارات
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// التحقق من وجود wp.customize
	if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
		return;
	}

	const customize = wp.customize;

	/**
	 * تفعيل/إلغاء تفعيل القسم
	 */
	customize('esdar_section_enable', function(value) {
		value.bind(function(to) {
			const section = $('.esdar-section');
			if (to) {
				section.slideDown(300);
			} else {
				section.slideUp(300);
			}
		});
	});

	/**
	 * عنوان القسم
	 */
	customize('esdar_section_title', function(value) {
		value.bind(function(to) {
			$('.esdar-section .section-title').text(to);
		});
	});

	/**
	 * وصف القسم
	 */
	customize('esdar_section_subtitle', function(value) {
		value.bind(function(to) {
			$('.esdar-section .section-subtitle').text(to);
		});
	});

	/**
	 * لون عنوان القسم
	 */
	customize('esdar_section_title_color', function(value) {
		value.bind(function(to) {
			$('.esdar-section .section-title').css('color', to);
		});
	});

	/**
	 * لون وصف القسم
	 */
	customize('esdar_section_subtitle_color', function(value) {
		value.bind(function(to) {
			$('.esdar-section .section-subtitle').css('color', to);
		});
	});

	/**
	 * نوع التخطيط
	 */
	customize('esdar_section_layout', function(value) {
		value.bind(function(to) {
			const section = $('.esdar-section');

			if (to === 'carousel') {
				section.removeClass('layout-grid').addClass('layout-carousel');
				// إعادة تهيئة Swiper
				if (window.nadiimEsdar && window.nadiimEsdar.reinit) {
					setTimeout(function() {
						window.nadiimEsdar.reinit();
					}, 100);
				}
			} else {
				section.removeClass('layout-carousel').addClass('layout-grid');
				// تدمير Swiper إن وُجد
				if (window.nadiimEsdarSwiper && !window.nadiimEsdarSwiper.destroyed) {
					window.nadiimEsdarSwiper.destroy(true, true);
				}
			}
		});
	});

	/**
	 * إظهار زر التحميل
	 */
	customize('esdar_card_show_download', function(value) {
		value.bind(function(to) {
			const downloadButtons = $('.esdar-card .button-download');
			if (to) {
				downloadButtons.fadeIn(200);
			} else {
				downloadButtons.fadeOut(200);
			}
		});
	});

	/**
	 * إظهار شارة النوع
	 */
	customize('esdar_card_show_badge', function(value) {
		value.bind(function(to) {
			const badges = $('.esdar-card .esdar-type-badge');
			if (to) {
				badges.fadeIn(200);
			} else {
				badges.fadeOut(200);
			}
		});
	});

	/**
	 * تفعيل خلفية القسم
	 */
	customize('esdar_section_bg_enable', function(value) {
		value.bind(function(to) {
			const section = $('.esdar-section');
			if (to) {
				section.addClass('section-with-bg');
			} else {
				section.removeClass('section-with-bg');
			}
		});
	});

	/**
	 * صورة الخلفية
	 */
	customize('esdar_section_bg_image', function(value) {
		value.bind(function(to) {
			const section = $('.esdar-section');
			if (to) {
				section.css('background-image', 'url(' + to + ')');
			} else {
				section.css('background-image', 'none');
			}
		});
	});

	/**
	 * شفافية طبقة التعتيم
	 */
	customize('esdar_section_overlay_opacity', function(value) {
		value.bind(function(to) {
			const style = document.getElementById('esdar-overlay-opacity-style') ||
				createStyleElement('esdar-overlay-opacity-style');

			style.textContent = `
				:root {
					--esdar-overlay-opacity: ${to};
				}
				.esdar-section .section-bg-overlay {
					opacity: ${to};
				}
			`;
		});
	});

	/**
	 * لون طبقة التعتيم
	 */
	customize('esdar_section_overlay_color', function(value) {
		value.bind(function(to) {
			$('.esdar-section .section-bg-overlay').css('background-color', to);
		});
	});

	/**
	 * نظام ألوان النص
	 */
	customize('esdar_section_text_color_scheme', function(value) {
		value.bind(function(to) {
			const section = $('.esdar-section');
			section.removeClass('text-scheme-auto text-scheme-light text-scheme-dark');
			section.addClass('text-scheme-' + to);
		});
	});

	/**
	 * إظهار/إخفاء زر "اطلع على المزيد"
	 */
	customize('esdar_section_show_more_button', function(value) {
		value.bind(function(to) {
			const button = $('.esdar-section .section-more-button');
			if (to) {
				button.fadeIn(300);
			} else {
				button.fadeOut(300);
			}
		});
	});

	/**
	 * نص زر "اطلع على المزيد"
	 */
	customize('esdar_section_more_button_text', function(value) {
		value.bind(function(to) {
			const button = $('.esdar-section .more-button');
			const svg = button.find('svg');
			button.text(to).append(svg);
		});
	});

	/**
	 * رابط زر "اطلع على المزيد"
	 */
	customize('esdar_section_more_button_link', function(value) {
		value.bind(function(to) {
			$('.esdar-section .more-button').attr('href', to);
		});
	});

	/**
	 * مصدر المحتوى - يحتاج refresh
	 */
	customize('esdar_section_source', function(value) {
		value.bind(function(to) {
			customize.preview.send('refresh');
		});
	});

	/**
	 * عدد الإصدارات - يحتاج refresh
	 */
	customize('esdar_section_count', function(value) {
		value.bind(function(to) {
			customize.preview.send('refresh');
		});
	});

	/**
	 * وسم الإصدارات - يحتاج refresh
	 */
	customize('esdar_section_tag', function(value) {
		value.bind(function(to) {
			customize.preview.send('refresh');
		});
	});

	/**
	 * JSON البيانات اليدوية - يحتاج refresh
	 */
	customize('esdar_section_manual_json', function(value) {
		value.bind(function(to) {
			// تحقق من صحة JSON
			try {
				JSON.parse(to);
				customize.preview.send('refresh');
			} catch (e) {
			}
		});
	});

	/**
	 * دالة مساعدة لإنشاء عنصر style
	 */
	function createStyleElement(id) {
		const style = document.createElement('style');
		style.id = id;
		document.head.appendChild(style);
		return style;
	}

	/**
	 * تحديث Swiper عند تغيير الإعدادات
	 */
	function updateSwiper() {
		if (window.nadiimEsdarSwiper && !window.nadiimEsdarSwiper.destroyed) {
			window.nadiimEsdarSwiper.update();
		}
	}

	/**
	 * مراقبة التغييرات العامة
	 */
	customize.preview.bind('active', function() {

		// تحديث Swiper بعد 500ms للسماح بتحميل كامل
		setTimeout(updateSwiper, 500);
	});

	/**
	 * معالجة تغيير حجم النافذة في Customizer
	 */
	let resizeTimer;
	$(window).on('resize', function() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			updateSwiper();
		}, 250);
	});

	/**
	 * تصدير دوال للاستخدام الخارجي
	 */
	window.nadiimEsdarCustomizer = {
		updateSwiper: updateSwiper,
		createStyleElement: createStyleElement,
	};

})(jQuery);
