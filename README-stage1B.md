# 📘 Stage 1B - Hero Slider مع المؤشر المخصص

## نظرة عامة

المرحلة الأولى - الجزء B (Stage 1B) من قالب **مجالس** WordPress. تتضمن هذه المرحلة بناء **Hero Slider** احترافي مع **مؤشر فأرة مخصص** في الصفحة الرئيسية.

---

## ✅ الملفات المُنشأة

### 📁 الملفات الرئيسية

```
front-page.php                              // الصفحة الرئيسية
template-parts/home/hero-slider.php         // السلايدر الرئيسي
template-parts/home/slider-card-article.php // بطاقة المقال في السلايدر
```

### 🎨 ملفات الأنماط

```
assets/css/homepage.css                     // أنماط الصفحة الرئيسية والسلايدر
```

### ⚡ ملفات JavaScript

```
assets/js/home-slider.js                    // تهيئة Swiper والتحكم في السلايدر
assets/js/home-cursor.js                    // المؤشر المخصص
```

### ⚙️ ملفات الإعدادات

```
inc/home-enqueue.php                        // تحميل الأصول + إعدادات Customizer
```

### 📊 البيانات التجريبية

```
demo/home-demo.json                         // بيانات تجريبية للسلايدر (5 مقالات)
```

---

## 🎯 المميزات الرئيسية

### 1. السلايدر (Hero Slider)

- ✅ مبني على **Swiper.js v8**
- ✅ يجلب آخر 5 مقالات من WordPress تلقائياً
- ✅ بيانات تجريبية (Fallback) إذا لم توجد مقالات
- ✅ Lazy Loading للصور
- ✅ Autoplay قابل للتحكم (Play/Pause)
- ✅ Navigation: Arrows + Pagination Bullets
- ✅ Keyboard Navigation (أسهم لوحة المفاتيح)
- ✅ دعم RTL كامل
- ✅ Responsive تماماً
- ✅ Accessibility كاملة (A11y)

### 2. بطاقة المقال (Slider Card)

- ✅ تصميم حديث بحواف دائرية (20px)
- ✅ صورة غلاف 16:9 (على اليمين في RTL)
- ✅ عنوان + مقتطف (محدود لسطرين)
- ✅ معلومات Meta: الكاتب + وقت القراءة + أيقونة صوتية
- ✅ زر CTA بلون `#339063`
- ✅ Hover effects ناعمة

### 3. المؤشر المخصص (Custom Cursor)

- ✅ دائرتين: Inner (صغيرة) + Outer (كبيرة)
- ✅ حركة ناعمة باستخدام `lerp` و `requestAnimationFrame`
- ✅ تغيير الشكل عند hover على الروابط/الأزرار
- ✅ يُعطّل تلقائياً على الأجهزة التي لا تدعم المؤشر
- ✅ يُعطّل عند تفضيل `prefers-reduced-motion`
- ✅ قابل للتحكم من Customizer

### 4. إعدادات Customizer

- ✅ عنوان السلايدر
- ✅ عدد الشرائح (1-10)
- ✅ تفعيل/إلغاء Autoplay
- ✅ مدة عرض كل شريحة (2-15 ثانية)
- ✅ تفعيل/إلغاء المؤشر المخصص

---

## 📦 المكتبات المُستخدمة

### Swiper.js v8
- **CDN:** `https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css`
- **CDN:** `https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js`

> يمكنك تحميل المكتبة محلياً إذا أردت.

---

## 🚀 التثبيت والإعداد

### 1. تحديث `functions.php`

أضف السطر التالي إلى ملف `functions.php` لتحميل ملف `home-enqueue.php`:

```php
require_once get_template_directory() . '/inc/home-enqueue.php';
```

### 2. إنشاء مقالات (اختياري)

- إذا كانت هناك مقالات منشورة، سيعرض السلايدر آخر 5 مقالات.
- إذا لم توجد مقالات، سيستخدم البيانات التجريبية من `demo/home-demo.json`.

### 3. التخصيص من Customizer

اذهب إلى:
**المظهر → التخصيص → السلايدر الرئيسي**

يمكنك تعديل:
- عنوان السلايدر
- عدد الشرائح
- Autoplay (تشغيل/إيقاف + المدة)
- المؤشر المخصص (تشغيل/إيقاف)

---

## 🎨 التصميم والألوان

### متغيرات CSS المُستخدمة

```css
--color-primary: #339063;
--color-primary-dark: #2a7550;
--color-secondary: #1c2d27;
--radius-lg: 20px;
--container-max: 1200px;
```

يمكنك تعديل هذه المتغيرات في `assets/css/homepage.css`.

---

## ♿ إمكانية الوصول (Accessibility)

### المميزات المُطبقة

- ✅ `role="group"` لكل شريحة
- ✅ `aria-label` لجميع الأزرار والروابط
- ✅ `aria-roledescription` للشرائح
- ✅ دعم لوحة المفاتيح الكامل
- ✅ إيقاف Autoplay عند التركيز على عناصر الشريحة
- ✅ تباين ألوان مناسب (WCAG AA)

---

## 📱 Responsive Design

### Breakpoints

- **Desktop:** 1024px+
- **Tablet:** 768px - 1023px
- **Mobile:** 480px - 767px
- **Small Mobile:** أقل من 480px

### التعديلات

- على **Tablet:** الصورة تظهر أعلى النص
- على **Mobile:** تصغير الخطوط والمسافات
- على **Small Mobile:** تبسيط التخطيط

---

## 🧪 الاختبار (Testing)

### قائمة الاختبار (Checklist)

- [x] السلايدر يظهر في الصفحة الرئيسية
- [x] عرض 5 شرائح (من WordPress أو Demo)
- [x] Navigation يعمل (Arrows + Bullets)
- [x] Keyboard navigation يعمل
- [x] Autoplay يعمل ويتوقف عند التفاعل
- [x] Lazy loading للصور
- [x] المؤشر المخصص يظهر ويتفاعل
- [x] Responsive على جميع الأحجام
- [x] RTL يعمل بشكل صحيح

---

## 🔧 استكشاف الأخطاء

### السلايدر لا يظهر

1. تأكد من إضافة `require_once` في `functions.php`
2. تأكد من أنك في الصفحة الرئيسية (Front Page)
3. تحقق من Console للأخطاء

### الصور لا تظهر

1. إذا كنت تستخدم Demo data، تأكد من الاتصال بالإنترنت (الصور من Unsplash)
2. تحقق من أن المقالات تحتوي على صور مميزة

### المؤشر المخصص لا يعمل

1. تحقق من إعدادات Customizer (قد يكون معطلاً)
2. تأكد من أن الجهاز يدعم المؤشر (لن يعمل على Touch devices)
3. تحقق من إعدادات النظام `prefers-reduced-motion`

---

## 📚 المصادر

- [Swiper.js Documentation](https://swiperjs.com/)
- [MDN - requestAnimationFrame](https://developer.mozilla.org/en-US/docs/Web/API/window/requestAnimationFrame)
- [WCAG Accessibility Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

---

## 🔜 المراحل القادمة

- **Stage 2:** أقسام الصفحة الرئيسية الأخرى
- **Stage 3:** صفحات المقالات والحوارات
- **Stage 4:** نظام التعليقات والتفاعل
- **Stage 5:** صفحات About و Contact

---

## 👨‍💻 المطور

**Madjaliss Team**
Version: 2.0 - Stage 1B
Date: يناير 2025

---

## 📄 الترخيص

هذا القالب جزء من مشروع **مجالس** ومرخص تحت GNU General Public License v2.
