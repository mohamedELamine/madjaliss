/**
 * JavaScript للواجهة الأمامية - صفحة اتصل بنا
 *
 * يتعامل مع إرسال AJAX، الخريطة، حفظ المسودة المحلية
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function() {
	'use strict';

	// ==========================================
	// إعداد النموذج
	// ==========================================
	const form = document.getElementById('nadiim-contact-form');
	if (!form) return;

	const submitBtn = form.querySelector('.btn-submit');
	const btnText = submitBtn.querySelector('.btn-text');
	const btnLoading = submitBtn.querySelector('.btn-loading');
	const messagesContainer = document.querySelector('.contact-messages');
	const successMessage = messagesContainer.querySelector('.contact-success');
	const errorMessage = messagesContainer.querySelector('.contact-error');

	// ==========================================
	// معالجة إرسال النموذج
	// ==========================================
	form.addEventListener('submit', function(e) {
		e.preventDefault();

		// إخفاء رسائل سابقة
		hideMessages();

		// التحقق من صحة النموذج
		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}

		// تعطيل الزر وإظهار حالة التحميل
		submitBtn.disabled = true;
		submitBtn.classList.add('loading');
		form.classList.add('submitting');

		// جمع البيانات
		const formData = new FormData(form);
		formData.append('action', 'nadiim_contact_form');

		// إرسال AJAX
		fetch(nadiimVars.ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				// نجح الإرسال
				showSuccessMessage(data.data.message);
				form.reset();
				clearDraft();

				// إعادة تعيين reCAPTCHA إذا كان موجوداً
				if (typeof grecaptcha !== 'undefined') {
					grecaptcha.reset();
				}
			} else {
				// فشل الإرسال
				showErrorMessage(data.data.message || 'حدث خطأ أثناء إرسال رسالتك.');
			}
		})
		.catch(error => {
			showErrorMessage('حدث خطأ أثناء إرسال رسالتك. يُرجى المحاولة مرة أخرى.');
		})
		.finally(() => {
			// إعادة تفعيل الزر
			submitBtn.disabled = false;
			submitBtn.classList.remove('loading');
			form.classList.remove('submitting');
		});
	});

	// ==========================================
	// دوال عرض الرسائل
	// ==========================================
	function showSuccessMessage(message) {
		successMessage.textContent = message;
		successMessage.classList.add('show');
		messagesContainer.style.display = 'block';

		// التمرير إلى الرسالة
		successMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

		// إخفاء تلقائياً بعد 5 ثوانٍ
		setTimeout(() => {
			successMessage.classList.remove('show');
		}, 5000);
	}

	function showErrorMessage(message) {
		errorMessage.textContent = message;
		errorMessage.classList.add('show');
		messagesContainer.style.display = 'block';

		// التمرير إلى الرسالة
		errorMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

		// إخفاء تلقائياً بعد 7 ثوانٍ
		setTimeout(() => {
			errorMessage.classList.remove('show');
		}, 7000);
	}

	function hideMessages() {
		successMessage.classList.remove('show');
		errorMessage.classList.remove('show');
	}

	// ==========================================
	// حفظ المسودة محلياً (localStorage)
	// ==========================================
	const DRAFT_KEY = 'nadiim_contact_draft';

	// حفظ البيانات عند كتابة المستخدم
	const draftFields = ['contact_name', 'contact_email', 'contact_phone', 'contact_subject', 'contact_message'];
	draftFields.forEach(fieldName => {
		const field = form.querySelector(`[name="${fieldName}"]`);
		if (field) {
			field.addEventListener('input', saveDraft);
		}
	});

	function saveDraft() {
		const draft = {};
		draftFields.forEach(fieldName => {
			const field = form.querySelector(`[name="${fieldName}"]`);
			if (field) {
				draft[fieldName] = field.value;
			}
		});

		try {
			localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
		} catch (e) {
			// localStorage غير متاح
		}
	}

	function loadDraft() {
		try {
			const draftJSON = localStorage.getItem(DRAFT_KEY);
			if (!draftJSON) return;

			const draft = JSON.parse(draftJSON);
			draftFields.forEach(fieldName => {
				const field = form.querySelector(`[name="${fieldName}"]`);
				if (field && draft[fieldName]) {
					field.value = draft[fieldName];
				}
			});
		} catch (e) {
		}
	}

	function clearDraft() {
		try {
			localStorage.removeItem(DRAFT_KEY);
		} catch (e) {
			// ignore
		}
	}

	// تحميل المسودة عند تحميل الصفحة
	loadDraft();

	// ==========================================
	// إعداد الخريطة (Leaflet)
	// ==========================================
	const mapElement = document.getElementById('contact-map');
	if (mapElement && typeof L !== 'undefined') {
		initMap(mapElement);
	}

	function initMap(mapElement) {
		const lat = parseFloat(mapElement.dataset.lat) || 24.7136;
		const lng = parseFloat(mapElement.dataset.lng) || 46.6753;
		const zoom = parseInt(mapElement.dataset.zoom) || 13;

		// إنشاء الخريطة
		const map = L.map('contact-map', {
			center: [lat, lng],
			zoom: zoom,
			zoomControl: true,
			scrollWheelZoom: false
		});

		// إضافة طبقة الخريطة
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
			maxZoom: 18
		}).addTo(map);

		// إضافة علامة
		const marker = L.marker([lat, lng]).addTo(map);

		// إضافة نافذة منبثقة
		const popupContent = `
			<div style="text-align: center; padding: 8px;">
				<strong style="display: block; margin-bottom: 8px;">موقعنا</strong>
				<a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}"
				   target="_blank"
				   rel="noopener"
				   style="color: #339063; text-decoration: none; font-weight: 600;">
					اتجاهات →
				</a>
			</div>
		`;
		marker.bindPopup(popupContent);

		// تفعيل التمرير عند النقر على الخريطة
		map.on('click', function() {
			map.scrollWheelZoom.enable();
		});
	}

	// ==========================================
	// Validation Enhancement
	// ==========================================
	const emailField = form.querySelector('#contact_email');
	if (emailField) {
		emailField.addEventListener('blur', function() {
			if (this.value && !isValidEmail(this.value)) {
				this.setCustomValidity('يُرجى إدخال بريد إلكتروني صحيح');
			} else {
				this.setCustomValidity('');
			}
		});
	}

	function isValidEmail(email) {
		const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return re.test(email);
	}

	// ==========================================
	// Character Count (optional)
	// ==========================================
	const messageField = form.querySelector('#contact_message');
	if (messageField) {
		const maxLength = 1000;

		// إنشاء عداد الأحرف
		const counter = document.createElement('div');
		counter.className = 'char-counter';
		counter.style.cssText = 'text-align: left; font-size: 0.875rem; color: #6c757d; margin-top: 4px;';
		messageField.parentElement.appendChild(counter);

		messageField.addEventListener('input', function() {
			const remaining = maxLength - this.value.length;
			counter.textContent = `${this.value.length} / ${maxLength}`;

			if (remaining < 50) {
				counter.style.color = '#e74c3c';
			} else {
				counter.style.color = '#6c757d';
			}
		});

		// إظهار العداد عند التركيز
		messageField.dispatchEvent(new Event('input'));
	}

})();
