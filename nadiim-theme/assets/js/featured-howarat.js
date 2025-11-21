/**
 * Featured Howarat Slider - الحوارات المميزة
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function() {
	'use strict';

	// التحقق من وجود Swiper
	if (typeof Swiper === 'undefined') {
		console.warn('Swiper library is not loaded. Featured Howarat carousel will not work.');
		return;
	}

	// الانتظار حتى يتم تحميل DOM
	document.addEventListener('DOMContentLoaded', function() {
		initFeaturedHowaratSlider();
	});

	/**
	 * تهيئة السلايدر
	 */
	function initFeaturedHowaratSlider() {
		const swiperContainer = document.querySelector('.featured-howarat-swiper');

		// التحقق من وجود السلايدر
		if (!swiperContainer) {
			return;
		}

		// الحصول على الإعدادات من الـ localized script
		const settings = window.NADIIM_FEATURED_HOWARAT || {};
		const layout = settings.layout || 'grid';

		// إذا كان التخطيط grid، لا نحتاج إلى تهيئة Swiper
		if (layout !== 'carousel') {
			return;
		}

		// تهيئة Swiper
		const swiper = new Swiper('.featured-howarat-swiper', {
			// الإعدادات الأساسية
			slidesPerView: 1,
			spaceBetween: 24,
			grabCursor: true,
			watchOverflow: true,

			// Lazy Loading
			lazy: {
				loadPrevNext: true,
				loadPrevNextAmount: 2,
			},

			// Keyboard Navigation
			keyboard: {
				enabled: true,
				onlyInViewport: true,
			},

			// Accessibility
			a11y: {
				enabled: true,
				prevSlideMessage: 'الشريحة السابقة',
				nextSlideMessage: 'الشريحة التالية',
				firstSlideMessage: 'هذه هي الشريحة الأولى',
				lastSlideMessage: 'هذه هي الشريحة الأخيرة',
				paginationBulletMessage: 'الذهاب إلى الشريحة {{index}}',
			},

			// Navigation
			navigation: {
				nextEl: '.featured-howarat-swiper .swiper-button-next',
				prevEl: '.featured-howarat-swiper .swiper-button-prev',
			},

			// Pagination
			pagination: {
				el: '.featured-howarat-swiper .swiper-pagination',
				clickable: true,
				dynamicBullets: true,
				dynamicMainBullets: 3,
			},

			// Breakpoints للـ Responsive
			breakpoints: {
				// Mobile Small (< 480px)
				320: {
					slidesPerView: 1,
					spaceBetween: 16,
				},
				// Mobile (480px - 768px)
				480: {
					slidesPerView: 1,
					spaceBetween: 20,
				},
				// Tablet (768px - 1024px)
				768: {
					slidesPerView: 2,
					spaceBetween: 24,
				},
				// Desktop (> 1024px)
				1024: {
					slidesPerView: 3,
					spaceBetween: 28,
				},
				// Large Desktop (> 1440px)
				1440: {
					slidesPerView: 3,
					spaceBetween: 32,
				},
			},

			// الأحداث
			on: {
				init: function() {
					console.log('Featured Howarat Swiper initialized');
					handleImagesLoading(this);
				},

				slideChange: function() {
					// يمكن إضافة تتبع تحليلات هنا
					// console.log('Slide changed to:', this.activeIndex);
				},

				reachBeginning: function() {
					// عند الوصول للبداية
					const prevButton = swiperContainer.querySelector('.swiper-button-prev');
					if (prevButton) {
						prevButton.setAttribute('aria-disabled', 'true');
					}
				},

				reachEnd: function() {
					// عند الوصول للنهاية
					const nextButton = swiperContainer.querySelector('.swiper-button-next');
					if (nextButton) {
						nextButton.setAttribute('aria-disabled', 'true');
					}
				},

				fromEdge: function() {
					// عند الابتعاد عن الحواف
					const prevButton = swiperContainer.querySelector('.swiper-button-prev');
					const nextButton = swiperContainer.querySelector('.swiper-button-next');
					if (prevButton) prevButton.setAttribute('aria-disabled', 'false');
					if (nextButton) nextButton.setAttribute('aria-disabled', 'false');
				},
			},
		});

		// حفظ مرجع للـ swiper في الـ window للوصول إليه من Customizer live preview
		window.nadiimFeaturedHowaratSwiper = swiper;

		// تحديث عند تغيير حجم النافذة
		let resizeTimer;
		window.addEventListener('resize', function() {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function() {
				if (swiper && !swiper.destroyed) {
					swiper.update();
				}
			}, 250);
		});
	}

	/**
	 * معالجة تحميل الصور
	 */
	function handleImagesLoading(swiper) {
		const slides = swiper.slides;

		slides.forEach(function(slide) {
			const images = slide.querySelectorAll('img[loading="lazy"]');

			images.forEach(function(img) {
				// إضافة class عند تحميل الصورة
				img.addEventListener('load', function() {
					img.classList.add('loaded');
				});

				// معالجة الأخطاء
				img.addEventListener('error', function() {
					img.classList.add('error');
					console.warn('Failed to load image:', img.src);

					// يمكن إضافة صورة placeholder هنا
					// img.src = '/path/to/placeholder.jpg';
				});
			});
		});
	}

	/**
	 * تحسين إمكانية الوصول للبطاقات
	 */
	function enhanceAccessibility() {
		const cards = document.querySelectorAll('.howarat-card');

		cards.forEach(function(card) {
			// إضافة دعم Enter لفتح البطاقة
			card.addEventListener('keydown', function(e) {
				if (e.key === 'Enter') {
					const link = card.querySelector('.cta-button, .card-title a');
					if (link) {
						link.click();
					}
				}
			});
		});
	}

	// تحسين إمكانية الوصول عند تحميل الصفحة
	document.addEventListener('DOMContentLoaded', enhanceAccessibility);

	/**
	 * Intersection Observer لـ Lazy Loading
	 * تحميل السلايدر فقط عند ظهوره في viewport
	 */
	if ('IntersectionObserver' in window) {
		const observer = new IntersectionObserver(
			function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						// القسم أصبح مرئياً، يمكن تحميل المحتوى
						const section = entry.target;
						section.classList.add('is-visible');

						// إيقاف المراقبة بعد أول ظهور
						observer.unobserve(section);
					}
				});
			},
			{
				rootMargin: '50px',
				threshold: 0.1,
			}
		);

		// مراقبة القسم
		const section = document.querySelector('.featured-howarat-section');
		if (section) {
			observer.observe(section);
		}
	}

	/**
	 * احترام تفضيلات الحركة المنخفضة
	 */
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		// تعطيل الانتقالات التلقائية
		const swiperContainer = document.querySelector('.featured-howarat-swiper');
		if (swiperContainer) {
			swiperContainer.classList.add('reduced-motion');
		}
	}

	/**
	 * تصدير دوال للاستخدام الخارجي (مثل Customizer)
	 */
	window.nadiimFeaturedHowarat = {
		init: initFeaturedHowaratSlider,
		enhanceAccessibility: enhanceAccessibility,
	};

})();
