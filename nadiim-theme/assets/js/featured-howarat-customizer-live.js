/**
 * Featured Howarat Customizer Live Preview
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
	customize('featured_howarat_enable', function(value) {
		value.bind(function(to) {
			const section = $('.featured-howarat-section');
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
	customize('featured_howarat_title', function(value) {
		value.bind(function(to) {
			$('.featured-howarat-section .section-title').text(to);
		});
	});

	/**
	 * وصف القسم
	 */
	customize('featured_howarat_description', function(value) {
		value.bind(function(to) {
			$('.featured-howarat-section .section-description').text(to);
		});
	});

	/**
	 * لون عنوان القسم
	 */
	customize('featured_howarat_title_color', function(value) {
		value.bind(function(to) {
			$('.featured-howarat-section .section-title').css('color', to);
		});
	});

	/**
	 * لون وصف القسم
	 */
	customize('featured_howarat_description_color', function(value) {
		value.bind(function(to) {
			$('.featured-howarat-section .section-description').css('color', to);
		});
	});

	/**
	 * نوع التخطيط - يحتاج refresh
	 */
	customize('featured_howarat_layout', function(value) {
		value.bind(function(to) {
			const section = $('.featured-howarat-section');

			if (to === 'carousel') {
				section.removeClass('layout-grid').addClass('layout-carousel');
				// إعادة تهيئة Swiper
				if (window.nadiimFeaturedHowarat && window.nadiimFeaturedHowarat.init) {
					setTimeout(function() {
						window.nadiimFeaturedHowarat.init();
					}, 100);
				}
			} else {
				section.removeClass('layout-carousel').addClass('layout-grid');
				// تدمير Swiper إن وُجد
				if (window.nadiimFeaturedHowaratSwiper && !window.nadiimFeaturedHowaratSwiper.destroyed) {
					window.nadiimFeaturedHowaratSwiper.destroy(true, true);
				}
			}
		});
	});

	/**
	 * إظهار أيقونات الوسائط
	 */
	customize('featured_howarat_show_media_icon', function(value) {
		value.bind(function(to) {
			const icons = $('.howarat-card .media-icon');
			if (to) {
				icons.fadeIn(200);
			} else {
				icons.fadeOut(200);
			}
		});
	});

	/**
	 * تفعيل خلفية القسم
	 */
	customize('featured_howarat_bg_enable', function(value) {
		value.bind(function(to) {
			const section = $('.featured-howarat-section');
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
	customize('featured_howarat_bg_image', function(value) {
		value.bind(function(to) {
			const section = $('.featured-howarat-section');
			if (to) {
				section.css('background-image', 'url(' + to + ')');
			} else {
				section.css('background-image', 'none');
			}
		});
	});

	/**
	 * Embed الخلفية
	 */
	customize('featured_howarat_bg_embed', function(value) {
		value.bind(function(to) {
			let embedContainer = $('.featured-howarat-section .section-bg-embed');

			// إنشاء container إن لم يكن موجوداً
			if (embedContainer.length === 0 && to) {
				embedContainer = $('<div class="section-bg-embed" aria-hidden="true"></div>');
				$('.featured-howarat-section').prepend(embedContainer);
			}

			if (to) {
				embedContainer.html(to).show();
			} else {
				embedContainer.html('').hide();
			}
		});
	});

	/**
	 * شفافية الـ Overlay
	 */
	customize('featured_howarat_overlay_opacity', function(value) {
		value.bind(function(to) {
			const style = document.getElementById('featured-howarat-overlay-opacity-style') ||
				createStyleElement('featured-howarat-overlay-opacity-style');

			style.textContent = `
				:root {
					--featured-howarat-overlay-opacity: ${to};
				}
				.featured-howarat-section .section-bg-overlay {
					opacity: ${to};
				}
			`;
		});
	});

	/**
	 * لون الـ Overlay
	 */
	customize('featured_howarat_overlay_color', function(value) {
		value.bind(function(to) {
			$('.featured-howarat-section .section-bg-overlay').css('background-color', to);
		});
	});

	/**
	 * مصدر المحتوى - يحتاج refresh
	 */
	customize('featured_howarat_source', function(value) {
		value.bind(function(to) {
			customize.preview.send('refresh');
		});
	});

	/**
	 * عدد الحوارات - يحتاج refresh
	 */
	customize('featured_howarat_count', function(value) {
		value.bind(function(to) {
			customize.preview.send('refresh');
		});
	});

	/**
	 * تاج الحوارات المميزة - يحتاج refresh
	 */
	customize('featured_howarat_tag', function(value) {
		value.bind(function(to) {
			customize.preview.send('refresh');
		});
	});

	/**
	 * JSON البطاقات اليدوية - يحتاج refresh
	 */
	customize('featured_howarat_cards_json', function(value) {
		value.bind(function(to) {
			// تحقق من صحة JSON
			try {
				JSON.parse(to);
				customize.preview.send('refresh');
			} catch (e) {
				console.warn('Invalid JSON for featured howarat cards:', e);
				// يمكن إضافة رسالة خطأ للمستخدم هنا
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
	 * تحديث الـ Swiper عند تغيير الإعدادات
	 */
	function updateSwiper() {
		if (window.nadiimFeaturedHowaratSwiper && !window.nadiimFeaturedHowaratSwiper.destroyed) {
			window.nadiimFeaturedHowaratSwiper.update();
		}
	}

	/**
	 * مراقبة التغييرات العامة
	 */
	customize.preview.bind('active', function() {
		console.log('Featured Howarat Customizer Preview Active');

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
	window.nadiimFeaturedHowaratCustomizer = {
		updateSwiper: updateSwiper,
		createStyleElement: createStyleElement,
	};

})(jQuery);
