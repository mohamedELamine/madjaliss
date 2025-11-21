/**
 * Esdar Section JavaScript
 * سكريبت قسم الإصدارات
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function() {
	'use strict';

	/**
	 * متغيرات عامة
	 */
	let esdarSwiper = null;

	/**
	 * الإعدادات من WordPress
	 */
	const settings = window.NADIIM_ESDAR || {};

	/**
	 * تهيئة قسم الإصدارات
	 */
	function initEsdarSection() {
		// التحقق من وجود القسم
		const section = document.querySelector('.esdar-section');
		if (!section) {
			return;
		}

		// تهيئة Carousel إذا كان التخطيط carousel
		const layout = settings.layout || 'grid';
		if (layout === 'carousel') {
			initEsdarSlider();
		}

		// تهيئة Lazy Loading للصور
		initLazyLoading();

		// تهيئة Keyboard Navigation للبطاقات
		initKeyboardNavigation();

		// تهيئة تأثيرات البطاقات
		initCardEffects();
	}

	/**
	 * تهيئة Swiper Slider
	 */
	function initEsdarSlider() {
		const swiperContainer = document.querySelector('.esdar-swiper');

		if (!swiperContainer) {
			return;
		}

		// التحقق من توفر Swiper
		if (typeof Swiper === 'undefined') {
			console.warn('Swiper library is not loaded');
			return;
		}

		// إنشاء Swiper جديد
		esdarSwiper = new Swiper('.esdar-swiper', {
			// عدد الشرائح
			slidesPerView: 1,
			spaceBetween: 24,

			// Loop
			loop: false,

			// Center slides
			centeredSlides: false,

			// Lazy loading
			lazy: {
				loadPrevNext: true,
				loadPrevNextAmount: 2,
			},

			// Keyboard control
			keyboard: {
				enabled: true,
				onlyInViewport: true,
			},

			// Mouse wheel
			mousewheel: {
				forceToAxis: true,
			},

			// Grab cursor
			grabCursor: true,

			// Accessibility
			a11y: {
				enabled: true,
				prevSlideMessage: 'الإصدار السابق',
				nextSlideMessage: 'الإصدار التالي',
				firstSlideMessage: 'هذا هو الإصدار الأول',
				lastSlideMessage: 'هذا هو الإصدار الأخير',
			},

			// Navigation
			navigation: {
				nextEl: '.esdar-section .swiper-button-next',
				prevEl: '.esdar-section .swiper-button-prev',
			},

			// Pagination
			pagination: {
				el: '.esdar-section .swiper-pagination',
				clickable: true,
				dynamicBullets: true,
				dynamicMainBullets: 3,
			},

			// Responsive breakpoints
			breakpoints: {
				// Mobile (320px+)
				320: {
					slidesPerView: 1,
					spaceBetween: 16,
				},
				// Tablet (768px+)
				768: {
					slidesPerView: 2,
					spaceBetween: 20,
				},
				// Desktop (1024px+)
				1024: {
					slidesPerView: 3,
					spaceBetween: 28,
				},
			},

			// Events
			on: {
				init: function() {
					console.log('Esdar Swiper initialized');
				},
				slideChange: function() {
					// يمكن إضافة تتبع تحليلي هنا
				},
			},
		});

		// حفظ المرجع للاستخدام العالمي
		window.nadiimEsdarSwiper = esdarSwiper;
	}

	/**
	 * تهيئة Lazy Loading للصور
	 */
	function initLazyLoading() {
		// التحقق من دعم IntersectionObserver
		if (!('IntersectionObserver' in window)) {
			return;
		}

		const images = document.querySelectorAll('.esdar-card-image img[loading="lazy"]');

		const imageObserver = new IntersectionObserver(
			function(entries, observer) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						const img = entry.target;

						// إضافة class عند التحميل
						img.addEventListener('load', function() {
							img.classList.add('loaded');
						});

						// إيقاف المراقبة
						observer.unobserve(img);
					}
				});
			},
			{
				rootMargin: '50px',
			}
		);

		images.forEach(function(img) {
			imageObserver.observe(img);
		});
	}

	/**
	 * تهيئة Keyboard Navigation للبطاقات
	 */
	function initKeyboardNavigation() {
		const cards = document.querySelectorAll('.esdar-card');

		cards.forEach(function(card) {
			card.addEventListener('keydown', function(e) {
				// Enter أو Space لفتح البطاقة
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					const viewLink = card.querySelector('.card-title-link, .button-view');
					if (viewLink) {
						viewLink.click();
					}
				}
			});
		});
	}

	/**
	 * تهيئة تأثيرات البطاقات
	 */
	function initCardEffects() {
		const cards = document.querySelectorAll('.esdar-card');

		cards.forEach(function(card) {
			// Hover effect enhancement
			card.addEventListener('mouseenter', function() {
				// يمكن إضافة تأثيرات إضافية هنا
			});

			// Focus effect
			card.addEventListener('focus', function() {
				this.classList.add('is-focused');
			});

			card.addEventListener('blur', function() {
				this.classList.remove('is-focused');
			});
		});
	}

	/**
	 * تحديث Swiper (للاستخدام من الخارج)
	 */
	function updateSwiper() {
		if (esdarSwiper && !esdarSwiper.destroyed) {
			esdarSwiper.update();
		}
	}

	/**
	 * تدمير Swiper (للاستخدام من الخارج)
	 */
	function destroySwiper() {
		if (esdarSwiper && !esdarSwiper.destroyed) {
			esdarSwiper.destroy(true, true);
			esdarSwiper = null;
		}
	}

	/**
	 * إعادة تهيئة القسم (للاستخدام من Customizer)
	 */
	function reinit() {
		destroySwiper();
		initEsdarSection();
	}

	/**
	 * معالجة تغيير حجم النافذة
	 */
	let resizeTimer;
	window.addEventListener('resize', function() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			updateSwiper();
		}, 250);
	});

	/**
	 * التهيئة عند جاهزية DOM
	 */
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initEsdarSection);
	} else {
		initEsdarSection();
	}

	/**
	 * تصدير الدوال للاستخدام العالمي
	 */
	window.nadiimEsdar = {
		init: initEsdarSection,
		reinit: reinit,
		updateSwiper: updateSwiper,
		destroySwiper: destroySwiper,
	};

})();
