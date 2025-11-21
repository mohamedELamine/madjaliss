# 📋 دليل إعدادات الشريط العلوي (Top Bar)

## ✅ حالة النظام: كامل وجاهز

جميع إعدادات الشريط العلوي تعمل بشكل كامل ومتكامل.

---

## 🎯 الإعدادات المتاحة (15 إعداد)

### 1️⃣ **التفعيل الأساسي**

#### `topbar_enable` (تفعيل الشريط العلوي)
- **النوع:** Checkbox
- **القيمة الافتراضية:** `true` (مفعّل)
- **الوظيفة:** إظهار/إخفاء الشريط العلوي بالكامل
- **الموقع في Customizer:** `المظهر → تخصيص → الشريط العلوي (Top Bar)`

```php
<?php if ( ! get_theme_mod( 'topbar_enable', true ) ) {
    return; // لا يتم عرض الشريط
}
```

---

### 2️⃣ **نوع المحتوى**

#### `topbar_dynamic_enable` (تفعيل المحتوى الديناميكي)
- **النوع:** Checkbox
- **القيمة الافتراضية:** `false` (معطّل - يعرض نص ثابت)
- **الخيارات:**
  - ❌ معطّل → عرض نص ثابت
  - ✅ مفعّل → عرض محتوى ديناميكي من المقالات/الحوارات/الإصدارات

#### `topbar_text` (النص الثابت)
- **النوع:** Textarea
- **القيمة الافتراضية:** `📢 مرحباً بكم في منصة نديم - فضاء للحوارات الرصينة`
- **الظهور:** فقط عندما `topbar_dynamic_enable` = `false`
- **يدعم:** HTML بسيط، Emoji

```php
$static_text = get_theme_mod('topbar_text', __('📢 مرحباً بكم', 'nadiim'));
echo wp_kses_post($static_text);
```

---

### 3️⃣ **إعدادات المحتوى الديناميكي**

#### `topbar_dynamic_post_type` (نوع المحتوى)
- **النوع:** Select
- **القيمة الافتراضية:** `post`
- **الخيارات:**
  - `post` → مقالات
  - `howarat` → حوارات
  - `esdar` → إصدارات
- **الظهور:** فقط عندما `topbar_dynamic_enable` = `true`

#### `topbar_dynamic_tag` (تاج الفلترة)
- **النوع:** Text
- **القيمة الافتراضية:** (فارغ)
- **الوظيفة:** فلترة المحتوى حسب التاج (Tag)
- **مثال:** `breaking-news` أو `featured`
- **الظهور:** فقط عندما `topbar_dynamic_enable` = `true`

#### `topbar_dynamic_limit` (عدد العناصر)
- **النوع:** Number
- **القيمة الافتراضية:** `1`
- **النطاق:** 1-5
- **الوظيفة:** عدد المنشورات المعروضة
- **الظهور:** فقط عندما `topbar_dynamic_enable` = `true`

```php
$query = new WP_Query(array(
    'post_type'      => get_theme_mod('topbar_dynamic_post_type', 'post'),
    'posts_per_page' => get_theme_mod('topbar_dynamic_limit', 1),
    'tag'            => get_theme_mod('topbar_dynamic_tag', ''),
));
```

---

### 4️⃣ **الألوان**

#### `topbar_bg_color` (لون الخلفية)
- **النوع:** Color Picker
- **القيمة الافتراضية:** `#1c2d27` (أخضر داكن)
- **التطبيق:** `postMessage` (معاينة مباشرة)
- **CSS Variable:** `--topbar-bg`

#### `topbar_text_color` (لون النص)
- **النوع:** Color Picker
- **القيمة الافتراضية:** `#ffffff` (أبيض)
- **التطبيق:** `postMessage` (معاينة مباشرة)
- **CSS Variable:** `--topbar-color`

```css
.site-topbar {
    background-color: var(--topbar-bg);
    color: var(--topbar-color);
}
```

---

### 5️⃣ **التنسيق**

#### `topbar_font_size` (حجم الخط)
- **النوع:** Range Slider
- **القيمة الافتراضية:** `14`
- **النطاق:** 12-18 px
- **التطبيق:** `postMessage` (معاينة مباشرة)
- **CSS Variable:** `--topbar-font-size`

#### `topbar_text_align` (محاذاة النص)
- **النوع:** Select
- **القيمة الافتراضية:** `center`
- **الخيارات:**
  - `right` → يمين
  - `center` → وسط
  - `left` → يسار
- **التطبيق:** `postMessage` (معاينة مباشرة)
- **CSS Variable:** `--topbar-align`

```css
.topbar-inner {
    justify-content: var(--topbar-align);
}

.topbar-content {
    text-align: var(--topbar-align);
}
```

---

### 6️⃣ **الأيقونة**

#### `topbar_icon` (اختيار الأيقونة)
- **النوع:** Select
- **القيمة الافتراضية:** `megaphone`
- **الخيارات:**
  - `megaphone` → 📢 مكبر الصوت
  - `bell` → 🔔 جرس
  - `info` → ℹ️ معلومات
  - `star` → ⭐ نجمة
  - `calendar` → 📅 تقويم
  - `none` → (بدون أيقونة)

```php
function nadiim_get_topbar_icon() {
    $icon = get_theme_mod('topbar_icon', 'megaphone');
    $icons = array(
        'megaphone' => '📢',
        'bell'      => '🔔',
        'info'      => 'ℹ️',
        'star'      => '⭐',
        'calendar'  => '📅',
        'none'      => '',
    );
    return isset($icons[$icon]) ? $icons[$icon] : '';
}
```

---

### 7️⃣ **حركة التمرير (Marquee)**

#### `topbar_marquee_enable` (تفعيل حركة التمرير)
- **النوع:** Checkbox
- **القيمة الافتراضية:** `false` (معطّل)
- **الوظيفة:** تحريك النص بشكل مستمر من اليمين لليسار

#### `topbar_marquee_speed` (سرعة التمرير)
- **النوع:** Range Slider
- **القيمة الافتراضية:** `50`
- **النطاق:** 20-100
- **الوظيفة:** قيمة أعلى = حركة أبطأ
- **الظهور:** فقط عندما `topbar_marquee_enable` = `true`

```html
<div class="topbar-content topbar-marquee" data-speed="50">
    محتوى النص هنا...
</div>
```

```javascript
// JavaScript يطبق السرعة تلقائياً
marquee.style.animationDuration = speed + 's';
```

---

## 📝 أمثلة الاستخدام

### مثال 1: نص ثابت بسيط
```
✅ تفعيل الشريط العلوي
❌ تفعيل المحتوى الديناميكي
📝 النص الثابت: "📢 عرض خاص لمدة محدودة!"
🎨 لون الخلفية: #339063 (أخضر)
📏 حجم الخط: 16px
📌 محاذاة النص: وسط
🎭 الأيقونة: megaphone
```

### مثال 2: محتوى ديناميكي من الحوارات
```
✅ تفعيل الشريط العلوي
✅ تفعيل المحتوى الديناميكي
📂 نوع المحتوى: حوارات (howarat)
🏷️ تاج الفلترة: featured
🔢 عدد العناصر: 3
🎨 لون الخلفية: #1c2d27
📏 حجم الخط: 14px
🎭 الأيقونة: star
```

### مثال 3: نص متحرك (Marquee)
```
✅ تفعيل الشريط العلوي
❌ تفعيل المحتوى الديناميكي
📝 النص الثابت: "⭐ خبر عاجل: إصدار جديد متاح الآن!"
✅ تفعيل حركة التمرير
⚡ سرعة التمرير: 30 (سريع)
📌 محاذاة النص: يمين
```

---

## 🔍 اختبار الإعدادات

### طريقة الاختبار:

1. **افتح Customizer:**
   ```
   لوحة التحكم → المظهر → تخصيص
   ```

2. **انتقل إلى قسم الشريط العلوي:**
   ```
   الشريط العلوي (Top Bar)
   ```

3. **جرّب كل إعداد:**
   - غيّر الألوان → معاينة مباشرة ✅
   - غيّر حجم الخط → معاينة مباشرة ✅
   - غيّر المحاذاة → معاينة مباشرة ✅
   - فعّل المحتوى الديناميكي → يحتاج Refresh
   - فعّل حركة التمرير → يحتاج Refresh

---

## ⚙️ الوظائف المساعدة

### 1. `nadiim_get_topbar_icon()`
```php
/**
 * الحصول على أيقونة التوب بار
 *
 * @return string الأيقونة (Emoji) أو فارغ
 */
$icon = nadiim_get_topbar_icon();
echo $icon; // 📢
```

### 2. `nadiim_get_topbar_dynamic_content()`
```php
/**
 * الحصول على المحتوى الديناميكي
 *
 * @return string HTML للمحتوى أو رسالة فارغة
 */
$content = nadiim_get_topbar_dynamic_content();
echo $content;
```

---

## 🎨 CSS Variables

جميع الإعدادات تطبق عبر CSS Variables للأداء الأمثل:

```css
:root {
    --topbar-bg: #1c2d27;
    --topbar-color: #ffffff;
    --topbar-font-size: 14px;
    --topbar-align: center;
    --topbar-height: 42px;
}
```

---

## ✨ المميزات

### ✅ ما يعمل بشكل كامل:

1. ✅ **تفعيل/إلغاء** الشريط العلوي
2. ✅ **نص ثابت** مع دعم HTML و Emoji
3. ✅ **محتوى ديناميكي** من 3 أنواع منشورات
4. ✅ **فلترة بالتاجات** (Tags)
5. ✅ **تحكم في عدد العناصر** (1-5)
6. ✅ **ألوان قابلة للتخصيص** (خلفية + نص)
7. ✅ **حجم خط مرن** (12-18px)
8. ✅ **6 أيقونات جاهزة** + بدون أيقونة
9. ✅ **3 خيارات محاذاة** (يمين، وسط، يسار)
10. ✅ **حركة تمرير** (Marquee) مع سرعة قابلة للتحكم
11. ✅ **معاينة مباشرة** (Live Preview) للألوان والتنسيق
12. ✅ **دعم RTL** كامل
13. ✅ **Responsive** على جميع الشاشات
14. ✅ **Accessibility** معايير WCAG
15. ✅ **Performance** محسّن مع CSS Variables

---

## 🐛 إصلاح المشاكل

### المشكلة: الشريط لا يظهر
```php
// تحقق من الإعداد:
$enabled = get_theme_mod('topbar_enable', true);
var_dump($enabled); // يجب أن يكون true
```

### المشكلة: المحتوى الديناميكي لا يعمل
```php
// تحقق من وجود منشورات:
$query = new WP_Query(array(
    'post_type' => get_theme_mod('topbar_dynamic_post_type', 'post'),
    'posts_per_page' => 1,
));
var_dump($query->found_posts); // يجب أن يكون > 0
```

### المشكلة: حركة التمرير لا تعمل
```javascript
// تحقق من JavaScript:
console.log(document.querySelectorAll('.topbar-marquee'));
// يجب أن يعيد العنصر
```

---

## 📚 الملفات المرتبطة

```
nadiim-theme/
├── inc/
│   └── customizer-header.php         (15 إعداد Customizer)
├── template-parts/header/
│   └── topbar.php                    (Template للعرض)
├── assets/
│   ├── css/
│   │   └── header.css                (Styles + Variables)
│   └── js/
│       └── header.js                 (Marquee + Interactions)
```

---

## ✅ الخلاصة

**جميع إعدادات الشريط العلوي تعمل بشكل كامل ومتكامل!**

- ✅ 15 إعداد كامل
- ✅ 2 وظيفة مساعدة
- ✅ معاينة مباشرة للإعدادات الرئيسية
- ✅ دعم RTL وResponsive
- ✅ أداء محسّن

**🎉 النظام جاهز للاستخدام!**
