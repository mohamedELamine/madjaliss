# صفحة "اتصل بنا" - دليل الاستخدام

نظام صفحة اتصل بنا المتكامل لموقع **نديم** WordPress RTL

## 📋 المحتويات

1. [نظرة عامة](#نظرة-عامة)
2. [التثبيت](#التثبيت)
3. [الإعداد الأولي](#الإعداد-الأولي)
4. [إعدادات Customizer](#إعدادات-customizer)
5. [إدارة الاستفسارات](#إدارة-الاستفسارات)
6. [Shortcode & Widgets](#shortcode--widgets)
7. [التخصيص المتقدم](#التخصيص-المتقدم)
8. [الأسئلة الشائعة](#الأسئلة-الشائعة)

---

## نظرة عامة

صفحة "اتصل بنا" هي نظام متكامل يوفر:

✅ **نموذج اتصال** قابل للتخصيص بالكامل عبر Customizer
✅ **تخزين الاستفسارات** في CPT مخصص (`inquiries`)
✅ **إرسال البريد الإلكتروني** للإداريين والرد التلقائي
✅ **حماية reCAPTCHA** ضد الرسائل المزعجة
✅ **خريطة Leaflet** قابلة للتخصيص
✅ **تصدير CSV** للاستفسارات
✅ **تصميم RTL** هادئ ومتجاوب

---

## التثبيت

### الملفات المطلوبة

جميع الملفات موجودة بالفعل في القالب:

```
nadiim-theme/
├── inc/
│   ├── cpt-inquiries.php          # CPT الاستفسارات
│   ├── customizer-contact.php     # إعدادات Customizer
│   └── contact-handler.php        # معالج النموذج والـ AJAX
├── template-parts/
│   └── contact/
│       └── form-contact.php       # قالب النموذج
├── page-contact.php               # قالب الصفحة
├── assets/
│   ├── css/contact.css            # أنماط الصفحة
│   ├── js/contact-frontend.js     # JavaScript الواجهة الأمامية
│   └── js/contact-admin.js        # JavaScript لوحة الإدارة
├── demo/
│   └── contact-demo.json          # محتوى تجريبي
└── CONTACT-PAGE-README.md         # هذا الملف
```

### خطوات التفعيل

تم تفعيل النظام تلقائياً من خلال `functions.php`:

```php
// تضمين ملفات صفحة الاتصال
require_once NADIIM_THEME_DIR . '/inc/cpt-inquiries.php';
require_once NADIIM_THEME_DIR . '/inc/customizer-contact.php';
require_once NADIIM_THEME_DIR . '/inc/contact-handler.php';
```

---

## الإعداد الأولي

### 1. إنشاء الصفحة

1. اذهب إلى **الصفحات → أضف جديداً**
2. أدخل العنوان: **اتصل بنا**
3. في **سمات الصفحة** → **القالب**، اختر: **صفحة اتصل بنا**
4. احفظ الصفحة وانشرها

### 2. استيراد المحتوى التجريبي (اختياري)

لاستيراد محتوى تجريبي:

```php
// أضف هذا الكود إلى inc/demo-content.php

function nadiim_import_contact_demo() {
	$demo_file = NADIIM_THEME_DIR . '/demo/contact-demo.json';
	if ( ! file_exists( $demo_file ) ) {
		return;
	}

	$demo_data = json_decode( file_get_contents( $demo_file ), true );

	// استيراد الاستفسارات التجريبية
	foreach ( $demo_data['contact_inquiries'] as $inquiry ) {
		wp_insert_post( array(
			'post_title'   => $inquiry['post_title'],
			'post_content' => $inquiry['post_content'],
			'post_type'    => $inquiry['post_type'],
			'post_status'  => $inquiry['post_status'],
			'post_date'    => $inquiry['post_date'],
		) );

		$post_id = wp_insert_post( $inquiry );
		if ( $post_id && isset( $inquiry['meta'] ) ) {
			foreach ( $inquiry['meta'] as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	// استيراد إعدادات Customizer الافتراضية
	if ( isset( $demo_data['customizer_defaults'] ) ) {
		foreach ( $demo_data['customizer_defaults'] as $key => $value ) {
			set_theme_mod( $key, $value );
		}
	}
}
```

---

## إعدادات Customizer

اذهب إلى **المظهر → تخصيص → صفحة اتصل بنا**

### 🔧 الإعدادات العامة

| الإعداد | الوصف | القيمة الافتراضية |
|---------|-------|-------------------|
| **تفعيل النموذج** | إظهار/إخفاء النموذج | مفعّل ✓ |
| **عناوين البريد المستقبِلة** | عناوين الإداريين (مفصولة بفواصل) | admin@example.com |
| **حفظ في قاعدة البيانات** | حفظ الرسائل في CPT `inquiries` | مفعّل ✓ |
| **الرد التلقائي** | إرسال رسالة تلقائية للمُرسِل | مُعطّل ✗ |
| **تفعيل reCAPTCHA** | حماية من الرسائل المزعجة | مُعطّل ✗ |

### 📝 الحقول والنصوص

يمكنك إظهار/إخفاء الحقول التالية:
- ✅ حقل الموضوع
- ✅ حقل الهاتف
- ✅ حقل الوقت المفضل للتواصل
- ✅ Checkbox موافقة الخصوصية (إلزامي/اختياري)

**رسائل الحالة:**
- رسالة النجاح (قابلة للتعديل)
- رسالة الخطأ (قابلة للتعديل)

### 🎨 التصميم

| العنصر | القيمة الافتراضية |
|--------|-------------------|
| **لون الخلفية** | `#f8f9fa` |
| **لون خلفية النموذج** | `#ffffff` |
| **لون الزر** | `#339063` |
| **نص الزر** | "إرسال الرسالة" |
| **الخط** | Cairo / Tajawal / Noto Kufi Arabic |

**تخطيط الصفحة:**
1. **النموذج يسار - الخريطة يمين** (افتراضي)
2. **النموذج أعلى - الخريطة أسفل**
3. **النموذج بعرض كامل** (بدون خريطة)

### 🗺️ الخريطة والموقع

| الإعداد | الوصف |
|---------|-------|
| **إظهار الخريطة** | تفعيل/إيقاف الخريطة |
| **Latitude** | خط العرض (مثال: 24.7136) |
| **Longitude** | خط الطول (مثال: 46.6753) |
| **Zoom** | مستوى التكبير (1-18) |
| **كود Iframe** | إدخال iframe من Google Maps (احتياطي) |
| **نص العنوان** | العنوان المعروض |

### 📧 قوالب البريد الإلكتروني

**قالب عنوان البريد:**
```
[{site}] رسالة جديدة من {name}
```

**قالب محتوى البريد:**
```
رسالة جديدة من موقع {site}

الاسم: {name}
البريد: {email}
الهاتف: {phone}

الموضوع: {subject}

الرسالة:
{message}
```

**المتغيرات المتاحة:**
- `{site}` - اسم الموقع
- `{name}` - اسم المُرسِل
- `{email}` - بريد المُرسِل
- `{phone}` - هاتف المُرسِل
- `{subject}` - الموضوع
- `{message}` - نص الرسالة

---

## إدارة الاستفسارات

### عرض الاستفسارات

اذهب إلى **لوحة التحكم → الاستفسارات**

ستجد قائمة بجميع الرسائل مع:
- ✉️ **الحالة**: جديد / مقروء / تم الرد
- 📧 **البريد الإلكتروني**: رابط mailto
- 📱 **الهاتف**
- 🌐 **IP Address**
- 📅 **التاريخ**

### حالات الاستفسار

| الحالة | اللون | الوصف |
|--------|------|-------|
| **جديد** | 🔴 أحمر | رسالة جديدة لم تُفتح بعد |
| **مقروء** | 🟠 برتقالي | تم فتح الرسالة |
| **تم الرد** | 🟢 أخضر | تم الرد على المُرسِل |

**ملاحظة:** عند فتح الرسالة، تتغير الحالة تلقائياً من "جديد" إلى "مقروء".

### Bulk Actions (الإجراءات الجماعية)

يمكنك تطبيق إجراءات على عدة رسائل:
- ✅ **وضع علامة مقروء**
- ✅ **وضع علامة تم الرد**
- 📥 **تصدير إلى CSV**
- 🗑️ **حذف**

### تصدير CSV

لتصدير الاستفسارات:
1. حدد الاستفسارات المطلوبة
2. اختر **تصدير إلى CSV** من القائمة المنسدلة
3. اضغط **تطبيق**

سيتم تنزيل ملف CSV يحتوي على:
- الاسم
- البريد الإلكتروني
- الهاتف
- الموضوع
- الرسالة
- الحالة
- IP
- التاريخ

---

## Shortcode & Widgets

### استخدام Shortcode

لعرض النموذج في أي صفحة، استخدم:

```
[nadiim_contact_form]
```

**مع خيارات:**

```
[nadiim_contact_form map="0" layout="full-width"]
```

**الخيارات المتاحة:**
- `map="0"` - إخفاء الخريطة
- `map="1"` - إظهار الخريطة (افتراضي)
- `layout="form-left"` - النموذج يسار
- `layout="form-top"` - النموذج أعلى
- `layout="full-width"` - عرض كامل

---

## التخصيص المتقدم

### إضافة حقول مخصصة

لإضافة حقل جديد، عدّل ملف `template-parts/contact/form-contact.php`:

```php
<!-- حقل مخصص: الشركة -->
<div class="form-field">
	<label for="contact_company">
		<?php _e( 'اسم الشركة', 'nadiim' ); ?>
		<span class="optional"><?php _e( '(اختياري)', 'nadiim' ); ?></span>
	</label>
	<input
		type="text"
		id="contact_company"
		name="contact_company"
	/>
</div>
```

ثم عدّل معالج النموذج في `inc/contact-handler.php`:

```php
'company' => isset( $_POST['contact_company'] ) ? sanitize_text_field( $_POST['contact_company'] ) : '',
```

واحفظه في قاعدة البيانات:

```php
update_post_meta( $inquiry_id, '_inquiry_company', $data['company'] );
```

### تخصيص الألوان عبر CSS

أضف هذا الكود إلى `assets/css/contact.css` أو `style.css`:

```css
/* تخصيص لون الزر */
.btn-submit {
	background-color: #your-color !important;
}

.btn-submit:hover {
	background-color: #your-hover-color !important;
}
```

### تخصيص قالب البريد الإلكتروني

عدّل `inc/contact-handler.php` واستخدم HTML للبريد:

```php
$headers = array(
	'Content-Type: text/html; charset=UTF-8',
	'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
	'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
);

$body = '
<html dir="rtl">
<body style="font-family: Arial, sans-serif;">
	<h2 style="color: #339063;">رسالة جديدة من موقع ' . get_bloginfo( 'name' ) . '</h2>
	<p><strong>الاسم:</strong> ' . esc_html( $data['name'] ) . '</p>
	<p><strong>البريد:</strong> ' . esc_html( $data['email'] ) . '</p>
	<hr>
	<p>' . nl2br( esc_html( $data['message'] ) ) . '</p>
</body>
</html>
';
```

---

## الأسئلة الشائعة

### كيف أفعّل reCAPTCHA؟

1. احصل على مفاتيح API من [Google reCAPTCHA](https://www.google.com/recaptcha)
2. اذهب إلى **المظهر → تخصيص → صفحة اتصل بنا → الإعدادات العامة**
3. فعّل **تفعيل reCAPTCHA**
4. أدخل **Site Key** و **Secret Key**
5. احفظ التغييرات

### كيف أغير عناوين البريد المستقبِلة؟

1. **المظهر → تخصيص → صفحة اتصل بنا → الإعدادات العامة**
2. في حقل **عناوين البريد المستقبِلة**، أدخل:
   ```
   admin@example.com, info@example.com, support@example.com
   ```
3. احفظ التغييرات

### لماذا لا تصلني الإيميلات؟

تحقق من:
1. ✅ إعدادات البريد في WordPress (استخدم plugin مثل WP Mail SMTP)
2. ✅ Spam folder في بريدك
3. ✅ تفعيل إرسال البريد في الـ hosting

### كيف أعرض النموذج في الـ Sidebar؟

استخدم Widget مخصص:
1. **المظهر → ودجات**
2. أضف **Custom HTML Widget**
3. أدخل Shortcode:
   ```
   [nadiim_contact_form map="0" layout="full-width"]
   ```

### كيف أخفي الخريطة؟

**الطريقة 1:** من Customizer
- **المظهر → تخصيص → صفحة اتصل بنا → الخريطة والموقع**
- أزل علامة ✓ من **إظهار الخريطة**

**الطريقة 2:** من Shortcode
```
[nadiim_contact_form map="0"]
```

### كيف أحذف جميع الاستفسارات القديمة؟

**تحذير:** هذا الإجراء نهائي!

```sql
-- احذف جميع الاستفسارات الأقدم من 6 أشهر
DELETE FROM wp_posts
WHERE post_type = 'inquiries'
AND post_date < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- احذف البيانات الوصفية المرتبطة
DELETE meta FROM wp_postmeta meta
LEFT JOIN wp_posts posts ON posts.ID = meta.post_id
WHERE posts.ID IS NULL;
```

**بدلاً من ذلك:**
1. اذهب إلى **الاستفسارات**
2. حدد الرسائل القديمة
3. **نقل إلى السلة** أو **حذف نهائي**

---

## 🛠️ دعم ومساعدة

إذا واجهت مشكلة:
1. تأكد من تحديث WordPress إلى آخر إصدار
2. تحقق من سجل الأخطاء (Debugging): `wp-content/debug.log`
3. عطّل Plugins الأخرى للتحقق من تعارض
4. راجع هذا الملف للحلول

---

## 📝 ملاحظات مهمة

- ✅ النظام متوافق مع GDPR (حذف البيانات عند الطلب)
- ✅ جميع البيانات مُعقّمة (sanitized) للحماية من XSS و SQL Injection
- ✅ reCAPTCHA يحمي من الرسائل الآلية
- ✅ localStorage يحفظ المسودة محلياً (لا تُرسل للسيرفر)
- ✅ AJAX يُرسل النموذج بدون إعادة تحميل الصفحة

---

## 🎯 خارطة الطريق (Road map)

تحسينات مستقبلية:
- [ ] تكامل مع Mailchimp
- [ ] إشعارات فورية للإداريين (Push Notifications)
- [ ] إحصائيات متقدمة (Dashboard Widget)
- [ ] تصدير PDF للاستفسارات
- [ ] نظام Tags/Categories للاستفسارات
- [ ] ردود سريعة مُعدة مسبقاً (Quick Replies)

---

تم إعداد هذا الدليل بواسطة فريق **نديم** 🌿
آخر تحديث: يناير 2025
