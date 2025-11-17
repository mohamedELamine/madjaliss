/**
 * JavaScript الصفحة الرئيسية - Front Page Scripts
 *
 * يحتوي على جميع الوظائف التفاعلية للصفحة الرئيسية:
 * - Releases Carousel (Swiper)
 * - Topbar Auto-scroll
 * - Newsletter Form Handling
 * - Smooth Scrolling
 *
 * @package Nadiim
 * @since 2.0.0
 */

(function ($) {
	'use strict';

	/**
	 * تهيئة عامة عند تحميل الصفحة
	 */
	$(document).ready(function () {
		initReleasesCarousel();
		initTopbarScroll();
		initNewsletterForm();
		initSmoothScroll();
	});

	/**
	 * 1. تهيئة Carousel الإصدارات
	 */
	function initReleasesCarousel() {
		// التحقق من وجود Swiper library
		if (typeof Swiper === 'undefined') {
			console.warn('Swiper library not loaded. Loading from CDN...');
			loadSwiperLibrary();
			return;
		}

		const releasesCarousel = document.querySelector('.releases-carousel');
		if (!releasesCarousel) {
			return;
		}

		new Swiper('.releases-carousel', {
			slidesPerView: 2,
			spaceBetween: 20,
			loop: false,
			rtl: true,
			navigation: {
				nextEl: '.releases-next',
				prevEl: '.releases-prev',
			},
			pagination: {
				el: '.releases-pagination',
				clickable: true,
				dynamicBullets: true,
			},
			breakpoints: {
				480: {
					slidesPerView: 3,
					spaceBetween: 20,
				},
				768: {
					slidesPerView: 4,
					spaceBetween: 20,
				},
				1024: {
					slidesPerView: 5,
					spaceBetween: 20,
				},
				1280: {
					slidesPerView: 6,
					spaceBetween: 20,
				},
			},
		});
	}

	/**
	 * تحميل مكتبة Swiper من CDN إذا لم تكن محملة
	 */
	function loadSwiperLibrary() {
		// تحميل CSS
		const swiperCSS = document.createElement('link');
		swiperCSS.rel = 'stylesheet';
		swiperCSS.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
		document.head.appendChild(swiperCSS);

		// تحميل JS
		const swiperJS = document.createElement('script');
		swiperJS.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
		swiperJS.onload = function () {
			initReleasesCarousel(); // إعادة محاولة التهيئة بعد التحميل
		};
		document.body.appendChild(swiperJS);
	}

	/**
	 * 2. تهيئة Topbar Auto-scroll
	 */
	function initTopbarScroll() {
		const topbarScroller = document.querySelector('.topbar-scroller');
		if (!topbarScroller) {
			return;
		}

		let scrollAmount = 0;
		const scrollSpeed = 0.5; // سرعة التمرير (بكسل في الإطار)
		const pauseOnHover = true;
		let isPaused = false;

		// إيقاف التمرير عند Hover
		if (pauseOnHover) {
			topbarScroller.addEventListener('mouseenter', function () {
				isPaused = true;
			});

			topbarScroller.addEventListener('mouseleave', function () {
				isPaused = false;
			});
		}

		// التمرير التلقائي
		function autoScroll() {
			if (!isPaused) {
				scrollAmount += scrollSpeed;

				// إعادة التمرير من البداية عند الوصول للنهاية
				if (scrollAmount >= topbarScroller.scrollWidth - topbarScroller.clientWidth) {
					scrollAmount = 0;
				}

				topbarScroller.scrollLeft = scrollAmount;
			}

			requestAnimationFrame(autoScroll);
		}

		// بدء التمرير التلقائي
		// requestAnimationFrame(autoScroll);

		// السماح بالتمرير اليدوي
		topbarScroller.addEventListener('scroll', function () {
			scrollAmount = topbarScroller.scrollLeft;
		});
	}

	/**
	 * 3. معالجة نموذج النشرة البريدية
	 */
	function initNewsletterForm() {
		const newsletterForm = document.querySelector('.newsletter-form');
		if (!newsletterForm) {
			return;
		}

		newsletterForm.addEventListener('submit', function (e) {
			e.preventDefault();

			const emailInput = newsletterForm.querySelector('.newsletter-input');
			const submitBtn = newsletterForm.querySelector('.newsletter-submit-btn');
			const messageDiv = document.querySelector('.newsletter-message');

			if (!emailInput || !submitBtn || !messageDiv) {
				return;
			}

			const email = emailInput.value.trim();

			// التحقق من صحة البريد الإلكتروني
			if (!isValidEmail(email)) {
				showMessage(messageDiv, 'يرجى إدخال بريد إلكتروني صحيح', 'error');
				return;
			}

			// تعطيل الزر أثناء الإرسال
			submitBtn.disabled = true;
			const originalText = submitBtn.textContent;
			submitBtn.textContent = 'جارٍ الإرسال...';

			// إرسال البيانات عبر AJAX
			$.ajax({
				url: nadiimFrontPage.ajax_url || '/wp-admin/admin-ajax.php',
				type: 'POST',
				data: {
					action: 'nadiim_subscribe_newsletter',
					email: email,
					nonce: newsletterForm.querySelector('[name="newsletter_nonce"]')?.value || '',
				},
				success: function (response) {
					if (response.success) {
						showMessage(messageDiv, 'تم الاشتراك بنجاح! شكراً لك.', 'success');
						emailInput.value = '';
					} else {
						showMessage(
							messageDiv,
							response.data.message || 'حدث خطأ. يرجى المحاولة مرة أخرى.',
							'error'
						);
					}
				},
				error: function () {
					showMessage(messageDiv, 'حدث خطأ في الاتصال. يرجى المحاولة لاحقاً.', 'error');
				},
				complete: function () {
					// إعادة تفعيل الزر
					submitBtn.disabled = false;
					submitBtn.textContent = originalText;
				},
			});
		});
	}

	/**
	 * التحقق من صحة البريد الإلكتروني
	 */
	function isValidEmail(email) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}

	/**
	 * عرض رسالة النجاح/الخطأ
	 */
	function showMessage(messageDiv, text, type) {
		messageDiv.textContent = text;
		messageDiv.className = 'newsletter-message ' + type;
		messageDiv.style.display = 'block';

		// إخفاء الرسالة بعد 5 ثوانٍ
		setTimeout(function () {
			messageDiv.style.display = 'none';
		}, 5000);
	}

	/**
	 * 4. Smooth Scrolling للروابط الداخلية
	 */
	function initSmoothScroll() {
		const links = document.querySelectorAll('a[href^="#"]');

		links.forEach(function (link) {
			link.addEventListener('click', function (e) {
				const targetId = this.getAttribute('href');

				// تجاهل الروابط الفارغة
				if (targetId === '#' || targetId === '#!') {
					return;
				}

				const targetElement = document.querySelector(targetId);
				if (targetElement) {
					e.preventDefault();

					// التمرير السلس
					targetElement.scrollIntoView({
						behavior: 'smooth',
						block: 'start',
					});

					// تحديث URL بدون reload
					if (history.pushState) {
						history.pushState(null, null, targetId);
					}
				}
			});
		});
	}

	/**
	 * 5. Lazy Loading للصور
	 */
	function initLazyLoading() {
		if ('IntersectionObserver' in window) {
			const imageObserver = new IntersectionObserver(function (entries, observer) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						const img = entry.target;
						const src = img.getAttribute('data-src');

						if (src) {
							img.src = src;
							img.removeAttribute('data-src');
							img.classList.add('loaded');
						}

						observer.unobserve(img);
					}
				});
			});

			const lazyImages = document.querySelectorAll('img[data-src]');
			lazyImages.forEach(function (img) {
				imageObserver.observe(img);
			});
		}
	}

	/**
	 * 6. Animation on Scroll
	 */
	function initScrollAnimations() {
		if ('IntersectionObserver' in window) {
			const animationObserver = new IntersectionObserver(
				function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							entry.target.classList.add('animate-in');
						}
					});
				},
				{
					threshold: 0.1,
					rootMargin: '0px 0px -50px 0px',
				}
			);

			const animatedElements = document.querySelectorAll(
				'.dialogue-card, .release-card, .post-card, .club-card'
			);
			animatedElements.forEach(function (el) {
				animationObserver.observe(el);
			});
		}
	}

	/**
	 * 7. إعدادات إضافية
	 */

	// منع السحب على البطاقات (لتحسين UX على mobile)
	$(document).on('dragstart', '.dialogue-card img, .release-card img, .post-card img', function (e) {
		e.preventDefault();
	});

	// تحسين أداء Resize
	let resizeTimer;
	$(window).on('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function () {
			// يمكن إضافة وظائف إضافية هنا عند تغيير حجم الشاشة
		}, 250);
	});
})(jQuery);

/**
 * Fallback لـ requestAnimationFrame
 */
if (!window.requestAnimationFrame) {
	window.requestAnimationFrame = function (callback) {
		return setTimeout(callback, 1000 / 60);
	};
}
