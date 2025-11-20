# 📖 صفحة الكاتب (Author Profile Page) - قالب نديم

نظام متكامل لعرض صفحة كاتب احترافية وجذابة في موقع عربي RTL، مصمم خصيصاً لقالب نديم.

## 📑 جدول المحتويات

- [المميزات](#-المميزات)
- [الملفات المُنشأة](#-الملفات-المُنشأة)
- [التثبيت والإعداد](#-التثبيت-والإعداد)
- [استخدام النظام](#-استخدام-النظام)
- [التخصيص](#-التخصيص)
- [الأسئلة الشائعة](#-الأسئلة-الشائعة)
- [الدعم الفني](#-الدعم-الفني)

---

## ✨ المميزات

### 🎨 تصميم احترافي وجذاب
- تصميم RTL كامل ومتوافق مع اللغة العربية
- بطاقة كاتب sticky تتضمن (صورة، اسم، دور، مقتطف، إحصائيات)
- أسلوب هادئ ومريح مع مساحات بيضاء واسعة
- لون أساسي: `#339063`

### 📊 عرض المحتوى
- قائمة كاملة بمنشورات الكاتب (مقالات + حوارات + إصدارات)
- فلتر حسب نوع المحتوى (الكل، مقالات، حوارات، إصدارات)
- ترتيب حسب (الأحدث، الأبجدية، الأكثر تفاعلاً)
- شريط بحث داخل محتوى الكاتب
- Pagination مريح وسهل الاستخدام

### 👤 معلومات الكاتب
- صورة ملف شخصي مخصصة (140×140px دائرية)
- المقتطف القصير (2-3 أسطر)
- السيرة الذاتية الكاملة (قسم منفصل)
- إحصائيات سريعة (عدد المقالات، الحوارات، الإصدارات)
- روابط وسائل التواصل الاجتماعي (Facebook, Twitter, Telegram, LinkedIn)
- رابط الموقع الشخصي (إن وُجد)

### 💬 التفاعل
- زر "راسل الكاتب" (Modal AJAX)
- نموذج اتصال آمن مع التحقق من البيانات
- إرسال رسائل عبر البريد الإلكتروني
- خيار تفعيل/تعطيل استقبال الرسائل لكل كاتب

### 🔍 SEO وSchema
- JSON-LD Schema.org Person markup
- OpenGraph tags للمشاركة على وسائل التواصل
- روابط rel="author"
- عناوين وأوصاف محسّنة للمحركات البحث

### ♿ Accessibility
- ARIA labels وroles كاملة
- Focus trap في Modal
- Keyboard navigation دعم كامل
- Screen reader friendly
- Colors contrast مطابقة لمعايير WCAG 2.1

### 📱 Responsive Design
- متجاوب بالكامل (Desktop, Tablet, Mobile)
- Sticky sidebar على Desktop
- تحول إلى عمود واحد على Mobile
- Touch-friendly buttons وlinks

### ⚡ الأداء
- Lazy loading للصور (native + IntersectionObserver fallback)
- CSS و JS محسّنة ومضغوطة
- استخدام wp_cache للبيانات المتكررة
- Debounce للبحث
- استعلامات قاعدة بيانات محسّنة

---

## 📂 الملفات المُنشأة

```
nadiim-theme/
├── author.php                                    # القالب الرئيسي لصفحة الكاتب
├── inc/
│   └── author-meta.php                          # إدارة حقول user_meta
├── template-parts/
│   └── author/
│       ├── author-header.php                    # بطاقة الكاتب (Sidebar)
│       ├── author-bio.php                       # السيرة التفصيلية
│       └── author-posts-list.php                # قائمة المنشورات
├── assets/
│   ├── css/
│   │   └── author.css                           # أنماط صفحة الكاتب
│   └── js/
│       └── author-frontend.js                   # JavaScript التفاعلي
└── demo/
    └── author-demo.json                         # بيانات تجريبية
```

---

## 🚀 التثبيت والإعداد

### 1. تفعيل الملفات

افتح ملف `functions.php` وأضف الكود التالي:

```php
/**
 * ==========================================
 * نظام صفحة الكاتب (Author Profile System)
 * ==========================================
 */

// تضمين ملف إدارة بيانات الكاتب
require_once NADIIM_THEME_DIR . '/inc/author-meta.php';

/**
 * تحميل أصول صفحة الكاتب (CSS & JS)
 */
function nadiim_author_page_enqueue_assets() {
    // تحميل CSS لصفحة الكاتب
    if ( is_author() ) {
        wp_enqueue_style(
            'nadiim-author',
            NADIIM_THEME_URI . '/assets/css/author.css',
            array( 'nadiim-main' ),
            NADIIM_VERSION
        );

        wp_enqueue_script(
            'nadiim-author-frontend',
            NADIIM_THEME_URI . '/assets/js/author-frontend.js',
            array(),
            NADIIM_VERSION,
            true
        );

        // تمرير متغيرات لـ JavaScript
        wp_localize_script( 'nadiim-author-frontend', 'nadiimAuthorVars', array(
            'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
            'contactNonce' => wp_create_nonce( 'nadiim-author-contact' ),
            'debug'        => WP_DEBUG ? '1' : '0',
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'nadiim_author_page_enqueue_assets' );
```

### 2. إعداد بيانات الكاتب

1. اذهب إلى **لوحة التحكم → المستخدمون → تحرير المستخدم**
2. ستجد قسم جديد: **"معلومات الكاتب الإضافية"**
3. قم بملء الحقول التالية:
   - **صورة الملف الشخصي المخصصة**: اختر صورة كبيرة (يُفضل 400×400px أو أكبر)
   - **المقتطف القصير**: نبذة مختصرة (2-3 أسطر)
   - **السيرة الذاتية الكاملة**: سيرة تفصيلية مع إمكانية استخدام HTML
   - **الموقع الإلكتروني الشخصي**: رابط الموقع/المدونة الخاصة
   - **السماح بإرسال رسائل**: فعّل لعرض زر "راسل الكاتب"
4. أضف روابط التواصل الاجتماعي:
   - فيسبوك
   - تويتر / X
   - تيليجرام
   - لينكد إن

### 3. الاختبار

1. قم بإنشاء مستخدم تجريبي
2. أضف بيانات الكاتب كما في الخطوة 2
3. انشر بعض المنشورات باسم هذا المستخدم
4. قم بزيارة: `yoursite.com/author/username`

---

## 💡 استخدام النظام

### عرض صفحة الكاتب

يمكن الوصول لصفحة الكاتب عبر:
- النقر على اسم الكاتب في أي مقال
- الرابط المباشر: `yoursite.com/author/username`
- من قائمة المؤلفين (إذا كانت موجودة)

### الفلترة والبحث

#### فلتر نوع المحتوى
يمكن للزائر فلترة المنشورات حسب النوع:
- **الكل**: عرض جميع أنواع المحتوى
- **مقالات**: المقالات فقط (post)
- **حوارات**: الحوارات فقط (howarat)
- **إصدارات**: الإصدارات فقط (esdar)

#### الترتيب
- **الأحدث**: حسب تاريخ النشر (الأحدث أولاً)
- **الأبجدية**: حسب عنوان المنشور (أ-ي)
- **الأكثر تفاعلاً**: حسب عدد التعليقات

#### البحث
يمكن البحث النصي داخل منشورات الكاتب فقط.

### إرسال رسالة للكاتب

1. انقر على زر **"راسل الكاتب"**
2. سيفتح Modal يحتوي على نموذج اتصال
3. املأ الحقول المطلوبة:
   - الاسم
   - البريد الإلكتروني
   - الرسالة
4. انقر **"إرسال الرسالة"**
5. ستُرسل رسالة إلى البريد الإلكتروني للكاتب

**ملاحظة**: يجب على الكاتب تفعيل خيار "السماح بإرسال رسائل" من إعدادات الملف الشخصي.

---

## 🎨 التخصيص

### تخصيص الألوان

يمكنك تعديل الألوان من ملف `assets/css/author.css`:

```css
:root {
    --author-primary: #339063;              /* اللون الأساسي */
    --author-primary-hover: #2a7a52;        /* اللون عند Hover */
    --author-text-primary: #2c3e50;         /* لون النص الأساسي */
    --author-text-secondary: #6c757d;       /* لون النص الثانوي */
    --author-text-muted: #95a5a6;           /* لون النص الباهت */
    --author-bg-light: #f8f9fa;             /* خلفية فاتحة */
    --author-bg-white: #ffffff;             /* خلفية بيضاء */
    --author-border: #e9ecef;               /* لون الحدود */
}
```

### تخصيص الخطوط

افتح `assets/css/author.css` وابحث عن:

```css
body {
    font-family: 'Cairo', 'Tajawal', sans-serif;
}
```

### تخصيص أحجام الصور

افتح `inc/author-meta.php` واعدّل:

```php
// في دالة get_author_profile_picture
$image = wp_get_attachment_image_url( $profile_picture_id, 'medium' );
```

يمكن تغيير `'medium'` إلى:
- `'thumbnail'` (150x150)
- `'medium'` (300x300)
- `'large'` (1024x1024)
- `'full'` (الحجم الأصلي)

### إضافة حقول جديدة

افتح `inc/author-meta.php` وأضف في دالة `nadiim_add_author_profile_fields`:

```php
// مثال: إضافة حقل "التخصص"
<div class="nadiim-meta-row">
    <label for="author_specialty">
        <?php esc_html_e( 'التخصص', 'nadiim' ); ?>
    </label>
    <input type="text" name="author_specialty" id="author_specialty"
           value="<?php echo esc_attr( get_user_meta( $user->ID, 'author_specialty', true ) ); ?>"
           class="regular-text" />
</div>
```

ثم في دالة `nadiim_save_author_profile_fields`:

```php
if ( isset( $_POST['author_specialty'] ) ) {
    update_user_meta( $user_id, 'author_specialty', sanitize_text_field( $_POST['author_specialty'] ) );
}
```

### تخصيص عدد المنشورات في الصفحة

افتح `template-parts/author/author-posts-list.php` واعدّل:

```php
$args = array(
    // ...
    'posts_per_page' => 12,  // غيّر هذا الرقم
    // ...
);
```

---

## 🔧 الدوال المساعدة (Helper Functions)

يوفر النظام عدة دوال يمكن استخدامها في أي مكان:

### nadiim_get_author_profile_picture( $user_id, $size )
الحصول على صورة الملف الشخصي للكاتب.

```php
$avatar = nadiim_get_author_profile_picture( 5, 'medium' );
if ( $avatar ) {
    echo '<img src="' . esc_url( $avatar ) . '" alt="Author Avatar" />';
}
```

### nadiim_get_author_excerpt( $user_id, $length )
الحصول على المقتطف القصير للكاتب.

```php
$excerpt = nadiim_get_author_excerpt( 5, 150 );
echo esc_html( $excerpt );
```

### nadiim_get_author_bio( $user_id )
الحصول على السيرة الذاتية الكاملة.

```php
$bio = nadiim_get_author_bio( 5 );
echo wp_kses_post( $bio );
```

### nadiim_get_author_social_links( $user_id )
الحصول على روابط التواصل الاجتماعي.

```php
$social_links = nadiim_get_author_social_links( 5 );
foreach ( $social_links as $network => $url ) {
    echo '<a href="' . esc_url( $url ) . '">' . esc_html( $network ) . '</a>';
}
```

### nadiim_author_allows_contact( $user_id )
التحقق من السماح بإرسال رسائل.

```php
if ( nadiim_author_allows_contact( 5 ) ) {
    echo 'يمكن مراسلة هذا الكاتب';
}
```

### nadiim_get_author_post_count( $user_id, $post_type )
الحصول على عدد منشورات الكاتب.

```php
$posts_count = nadiim_get_author_post_count( 5, 'post' );
echo 'عدد المقالات: ' . $posts_count;
```

---

## 📊 البيانات التجريبية (Demo Data)

يتضمن النظام ملف `demo/author-demo.json` يحتوي على:
- 3 كتّاب تجريبيين بمعلومات كاملة
- 6 منشورات لكل كاتب (مقالات + حوارات + إصدارات)
- صور تجريبية من pravatar.cc

### استيراد البيانات التجريبية

#### الطريقة 1: يدوياً

1. افتح `demo/author-demo.json`
2. انسخ بيانات كل كاتب
3. اذهب إلى **لوحة التحكم → المستخدمون → إضافة جديد**
4. أنشئ المستخدم ثم حدّث user_meta يدوياً

#### الطريقة 2: باستخدام Code Snippet

```php
// أضف هذا الكود في functions.php أو Code Snippets plugin
function nadiim_import_demo_authors() {
    $json = file_get_contents( NADIIM_THEME_DIR . '/demo/author-demo.json' );
    $data = json_decode( $json, true );

    foreach ( $data['authors'] as $author_data ) {
        // تحقق من وجود المستخدم
        $user_id = username_exists( $author_data['username'] );

        if ( ! $user_id ) {
            // أنشئ المستخدم
            $user_id = wp_create_user(
                $author_data['username'],
                wp_generate_password(),
                $author_data['email']
            );
        }

        // حدّث user_meta
        foreach ( $author_data['meta'] as $key => $value ) {
            update_user_meta( $user_id, $key, $value );
        }
    }

    echo 'تم استيراد البيانات التجريبية بنجاح!';
}

// استدعِ الدالة مرة واحدة
// nadiim_import_demo_authors();
```

---

## 🐛 استكشاف الأخطاء

### المشكلة: لا تظهر صورة الملف الشخصي

**الحل:**
1. تأكد من رفع صورة في إعدادات الملف الشخصي
2. تأكد من أن الصورة مرفوعة بنجاح في **مكتبة الوسائط**
3. تحقق من أن `profile_picture_id` محفوظ في user_meta

### المشكلة: زر "راسل الكاتب" لا يعمل

**الحل:**
1. تأكد من تفعيل الخيار في إعدادات الكاتب
2. تحقق من أن JavaScript محمّل بشكل صحيح
3. افتح Console في المتصفح وتحقق من وجود أخطاء

### المشكلة: الـ Modal لا يُغلق

**الحل:**
1. امسح الـ cache
2. تأكد من عدم وجود تعارض مع plugins أخرى
3. تحقق من أن JavaScript محمّل بدون أخطاء

### المشكلة: CSS لا يُطبّق بشكل صحيح

**الحل:**
1. امسح cache المتصفح و WordPress
2. تحقق من أن `author.css` محمّل في صفحة الكاتب
3. تحقق من أولوية التحميل في `functions.php`

---

## ❓ الأسئلة الشائعة

### هل يمكن استخدام ACF بدلاً من user_meta؟

نعم! يمكنك استبدال نظام user_meta الحالي بـ ACF:

1. أنشئ Field Group جديد في ACF
2. اجعل الـ Location: User Form
3. أضف نفس الحقول المذكورة
4. عدّل الدوال المساعدة لاستخدام `get_field()`

### هل يعمل النظام مع Multisite؟

نعم، النظام متوافق مع WordPress Multisite.

### هل يمكن تخصيص Modal نموذج الاتصال؟

نعم! يمكنك تعديل:
- التصميم: في `assets/css/author.css` (ابحث عن `.modal`)
- الحقول: في `author.php` (ابحث عن `#contact-author-modal`)
- الوظائف: في `assets/js/author-frontend.js`

### هل يدعم النظام GDPR؟

النظام لا يخزن أي بيانات شخصية للزوار. الرسائل تُرسل مباشرة عبر البريد الإلكتروني.

### كيف أترجم النصوص؟

النصوص جاهزة للترجمة باستخدام:
- **Loco Translate** plugin
- ملفات `.po` و `.mo`
- Domain: `'nadiim'`

---

## 📈 الأداء والتحسينات

### نصائح لتحسين الأداء

1. **استخدم CDN** لتسريع تحميل الصور والأصول
2. **فعّل التخزين المؤقت (Caching)** باستخدام:
   - WP Super Cache
   - W3 Total Cache
   - WP Rocket
3. **ضغط الصور** قبل رفعها (يُفضل WebP)
4. **استخدم Lazy Loading** (مفعّل افتراضياً)
5. **قلّل عدد الـ Plugins** النشطة

### تحسينات SEO

- ✅ JSON-LD Schema مضمّن
- ✅ OpenGraph tags جاهزة
- ✅ عناوين H1/H2/H3 صحيحة
- ✅ alt text للصور
- ✅ روابط داخلية

---

## 🔒 الأمان

النظام يطبق أفضل ممارسات الأمان:

- ✅ **Nonce verification** في جميع النماذج
- ✅ **Sanitization** لجميع المدخلات
- ✅ **Escaping** لجميع المخرجات
- ✅ **Capability checks** للصلاحيات
- ✅ **AJAX security** مع nonce
- ✅ **SQL injection protection** عبر WP_Query
- ✅ **XSS protection** عبر wp_kses_post

---

## 🤝 المساهمة والتطوير

### الإبلاغ عن مشكلة (Bug Report)

إذا وجدت مشكلة:
1. تحقق من أن المشكلة لم تُبلّغ عنها سابقاً
2. قدّم وصفاً تفصيلياً للمشكلة
3. ضمّن خطوات إعادة إنتاج المشكلة
4. أرفق screenshots إن أمكن

### طلب ميزة جديدة (Feature Request)

نرحب بأفكارك! اقترح ميزات جديدة مع:
- وصف تفصيلي للميزة
- حالات الاستخدام
- mockups إن وُجدت

---

## 📝 التغييرات (Changelog)

### النسخة 1.0.0 (2025-01-20)
- ✨ إطلاق النسخة الأولى
- ✅ نظام بطاقة الكاتب الكامل
- ✅ فلترة وبحث في المنشورات
- ✅ نموذج مراسلة الكاتب (AJAX)
- ✅ Schema.org Person markup
- ✅ Responsive design كامل
- ✅ Accessibility متوافق مع WCAG 2.1
- ✅ دعم RTL كامل

---

## 📧 الدعم الفني

للحصول على الدعم:
- 📖 **التوثيق**: اقرأ هذا الملف بالكامل
- 🐛 **المشاكل**: افتح Issue على GitHub
- 💬 **المناقشات**: انضم إلى مجتمع المطورين
- 📧 **البريد**: للاستفسارات الخاصة

---

## 📄 الترخيص

هذا النظام مرخص تحت **GPL v2 or later**، نفس ترخيص WordPress.

يمكنك:
- ✅ الاستخدام التجاري
- ✅ التعديل
- ✅ التوزيع
- ✅ الاستخدام الخاص

---

## 🙏 شكر وتقدير

شكراً لاستخدامك نظام صفحة الكاتب في قالب نديم!

تم التطوير بـ ❤️ للمجتمع العربي

---

**النسخة**: 1.0.0
**تاريخ التحديث**: 20 يناير 2025
**المطور**: فريق قالب نديم
**الموقع**: [nadiim.com](https://nadiim.com)

---

© 2025 قالب نديم. جميع الحقوق محفوظة.
