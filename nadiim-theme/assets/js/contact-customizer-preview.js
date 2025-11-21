/**
 * Customizer Preview JS - صفحة اتصل بنا
 *
 * يتعامل مع التحديثات المباشرة في Customizer
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// معاينة مباشرة لعنوان الصفحة
	wp.customize('contact_page_title', function(value) {
		value.bind(function(newval) {
			$('.contact-page .page-title').text(newval);
		});
	});

	// معاينة مباشرة لمقدمة الصفحة
	wp.customize('contact_page_intro', function(value) {
		value.bind(function(newval) {
			$('.contact-page .page-intro').text(newval);
		});
	});

	// معاينة مباشرة لنص الزر
	wp.customize('contact_btn_text', function(value) {
		value.bind(function(newval) {
			$('.btn-submit .btn-text').text(newval);
		});
	});

	// معاينة مباشرة لنص الموافقة
	wp.customize('contact_consent_text', function(value) {
		value.bind(function(newval) {
			$('.form-field-checkbox span:not(.required)').text(newval);
		});
	});

	// معاينة مباشرة لنص العنوان
	wp.customize('contact_address_text', function(value) {
		value.bind(function(newval) {
			$('.contact-info-item .contact-info-text').first().html(newval.replace(/\n/g, '<br>'));
		});
	});

	// معاينة مباشرة لرقم الهاتف
	wp.customize('contact_phone_number', function(value) {
		value.bind(function(newval) {
			var $phoneItem = $('.contact-info-item').has('a[href^="tel:"]');
			if (newval) {
				if ($phoneItem.length) {
					$phoneItem.find('a').attr('href', 'tel:' + newval).text(newval);
				} else {
					// إنشاء عنصر جديد إذا لم يكن موجوداً
					var phoneHtml = `
						<div class="contact-info-item">
							<svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
							</svg>
							<div class="contact-info-text">
								<a href="tel:${newval}">${newval}</a>
							</div>
						</div>
					`;
					$('.contact-info-item').first().after(phoneHtml);
				}
			} else {
				$phoneItem.remove();
			}
		});
	});

	// معاينة مباشرة للبريد الإلكتروني
	wp.customize('contact_email_display', function(value) {
		value.bind(function(newval) {
			var $emailItem = $('.contact-info-item').has('a[href^="mailto:"]');
			if (newval) {
				if ($emailItem.length) {
					$emailItem.find('a').attr('href', 'mailto:' + newval).text(newval);
				}
			}
		});
	});

	// معاينة مباشرة لروابط مواقع التواصل الاجتماعي
	var socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'whatsapp'];

	socialPlatforms.forEach(function(platform) {
		wp.customize('contact_' + platform, function(value) {
			value.bind(function(newval) {
				var $socialIcon = $('.social-icon.social-' + platform);
				if (newval) {
					if ($socialIcon.length) {
						$socialIcon.attr('href', platform === 'whatsapp' ? 'https://wa.me/' + newval : newval);
						$socialIcon.show();
					} else {
						// إظهار قسم التواصل الاجتماعي إذا لم يكن موجوداً
						if (!$('.contact-social-links').length) {
							var socialLinksHtml = `
								<div class="contact-social-links">
									<h4>تابعنا على</h4>
									<div class="social-icons"></div>
								</div>
							`;
							$('.contact-info-box').append(socialLinksHtml);
						}
					}
				} else {
					$socialIcon.hide();
					// إخفاء القسم إذا لم يكن هناك روابط
					if ($('.social-icon:visible').length === 0) {
						$('.contact-social-links').hide();
					}
				}
			});
		});
	});

	// معاينة مباشرة لإظهار/إخفاء حقل الهاتف
	wp.customize('contact_show_phone', function(value) {
		value.bind(function(newval) {
			if (newval) {
				$('.form-field').has('#contact_phone').show();
			} else {
				$('.form-field').has('#contact_phone').hide();
			}
		});
	});

	// معاينة مباشرة لإظهار/إخفاء حقل الموضوع
	wp.customize('contact_show_subject', function(value) {
		value.bind(function(newval) {
			if (newval) {
				$('.form-field').has('#contact_subject').show();
			} else {
				$('.form-field').has('#contact_subject').hide();
			}
		});
	});

	// معاينة مباشرة لإظهار/إخفاء حقل الوقت المفضل
	wp.customize('contact_show_preferred_time', function(value) {
		value.bind(function(newval) {
			if (newval) {
				$('.form-field').has('#contact_preferred_time').show();
			} else {
				$('.form-field').has('#contact_preferred_time').hide();
			}
		});
	});

	// معاينة مباشرة لإظهار/إخفاء موافقة الخصوصية
	wp.customize('contact_consent_required', function(value) {
		value.bind(function(newval) {
			if (newval) {
				$('.form-field-checkbox').show();
			} else {
				$('.form-field-checkbox').hide();
			}
		});
	});

	// معاينة مباشرة لإظهار/إخفاء الخريطة
	wp.customize('contact_map_enable', function(value) {
		value.bind(function(newval) {
			if (newval) {
				$('.contact-map-wrapper').show();
			} else {
				$('.contact-map-wrapper').hide();
			}
		});
	});

	// معاينة مباشرة لإظهار/إخفاء معلومات العنوان
	wp.customize('contact_show_address', function(value) {
		value.bind(function(newval) {
			if (newval) {
				$('.contact-info-box').show();
			} else {
				$('.contact-info-box').hide();
			}
		});
	});

	// معاينة مباشرة لتخطيط الصفحة
	wp.customize('contact_layout', function(value) {
		value.bind(function(newval) {
			$('.contact-form-section').attr('data-layout', newval);
		});
	});

})(jQuery);
