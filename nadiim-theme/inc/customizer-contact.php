<?php
/**
 * إعدادات Customizer لصفحة اتصل بنا
 *
 * يحتوي على جميع الإعدادات القابلة للتخصيص عبر المظهر → تخصيص
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات Customizer لصفحة الاتصال
 */
function nadiim_contact_customizer_register( $wp_customize ) {

	// ==========================================
	// Panel: صفحة اتصل بنا
	// ==========================================
	$wp_customize->add_panel( 'nadiim_contact_panel', array(
		'title'       => __( 'صفحة اتصل بنا', 'nadiim' ),
		'description' => __( 'إعدادات نموذج الاتصال والخريطة والبريد الإلكتروني', 'nadiim' ),
		'priority'    => 130,
	) );

	// ==========================================
	// Section 1: الإعدادات العامة
	// ==========================================
	$wp_customize->add_section( 'nadiim_contact_general', array(
		'title'    => __( 'الإعدادات العامة', 'nadiim' ),
		'panel'    => 'nadiim_contact_panel',
		'priority' => 10,
	) );

	// تفعيل النموذج
	$wp_customize->add_setting( 'contact_enable_form', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_enable_form', array(
		'label'    => __( 'تفعيل نموذج الاتصال', 'nadiim' ),
		'section'  => 'nadiim_contact_general',
		'type'     => 'checkbox',
	) );

	// عناوين البريد المستقبِلة
	$wp_customize->add_setting( 'contact_receiver_emails', array(
		'default'           => get_option( 'admin_email' ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'contact_receiver_emails', array(
		'label'       => __( 'عناوين البريد المستقبِلة', 'nadiim' ),
		'description' => __( 'أدخل عنواناً أو أكثر مفصولة بفواصل أو أسطر', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'textarea',
	) );

	// حفظ في CPT inquiries
	$wp_customize->add_setting( 'contact_save_to_inquiries', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'contact_save_to_inquiries', array(
		'label'       => __( 'حفظ الرسائل في قاعدة البيانات', 'nadiim' ),
		'description' => __( 'سيتم حفظ الرسائل في نوع المحتوى "الاستفسارات"', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'checkbox',
	) );

	// الرد التلقائي
	$wp_customize->add_setting( 'contact_auto_reply', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'contact_auto_reply', array(
		'label'       => __( 'تفعيل الرد التلقائي', 'nadiim' ),
		'description' => __( 'إرسال رسالة تلقائية للمُرسِل', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'checkbox',
	) );

	// نص الرد التلقائي
	$wp_customize->add_setting( 'contact_auto_reply_text', array(
		'default'           => __( 'شكراً لتواصلك معنا! تم استلام رسالتك وسنرد عليك في أقرب وقت ممكن.', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'contact_auto_reply_text', array(
		'label'       => __( 'نص الرد التلقائي', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'textarea',
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'contact_auto_reply' )->value();
		},
	) );

	// تفعيل reCAPTCHA
	$wp_customize->add_setting( 'contact_recaptcha_enable', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'contact_recaptcha_enable', array(
		'label'       => __( 'تفعيل reCAPTCHA', 'nadiim' ),
		'description' => __( 'حماية إضافية ضد الرسائل المزعجة', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'checkbox',
	) );

	// Site Key لـ reCAPTCHA
	$wp_customize->add_setting( 'contact_recaptcha_site_key', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_recaptcha_site_key', array(
		'label'       => __( 'reCAPTCHA Site Key', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'text',
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'contact_recaptcha_enable' )->value();
		},
	) );

	// Secret Key لـ reCAPTCHA
	$wp_customize->add_setting( 'contact_recaptcha_secret_key', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_recaptcha_secret_key', array(
		'label'       => __( 'reCAPTCHA Secret Key', 'nadiim' ),
		'section'     => 'nadiim_contact_general',
		'type'        => 'text',
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'contact_recaptcha_enable' )->value();
		},
	) );

	// ==========================================
	// Section 2: الحقول والنصوص
	// ==========================================
	$wp_customize->add_section( 'nadiim_contact_fields', array(
		'title'    => __( 'الحقول والنصوص', 'nadiim' ),
		'panel'    => 'nadiim_contact_panel',
		'priority' => 20,
	) );

	// إظهار حقل الموضوع
	$wp_customize->add_setting( 'contact_show_subject', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_show_subject', array(
		'label'    => __( 'إظهار حقل الموضوع', 'nadiim' ),
		'section'  => 'nadiim_contact_fields',
		'type'     => 'checkbox',
	) );

	// إظهار حقل الهاتف
	$wp_customize->add_setting( 'contact_show_phone', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_show_phone', array(
		'label'    => __( 'إظهار حقل الهاتف', 'nadiim' ),
		'section'  => 'nadiim_contact_fields',
		'type'     => 'checkbox',
	) );

	// إظهار حقل الوقت المفضل
	$wp_customize->add_setting( 'contact_show_preferred_time', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_show_preferred_time', array(
		'label'       => __( 'إظهار حقل الوقت المفضل للتواصل', 'nadiim' ),
		'description' => __( 'قائمة منسدلة بأوقات التواصل المفضلة', 'nadiim' ),
		'section'     => 'nadiim_contact_fields',
		'type'        => 'checkbox',
	) );

	// موافقة الخصوصية إلزامية
	$wp_customize->add_setting( 'contact_consent_required', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_consent_required', array(
		'label'    => __( 'اشتراط موافقة الخصوصية', 'nadiim' ),
		'section'  => 'nadiim_contact_fields',
		'type'     => 'checkbox',
	) );

	// نص موافقة الخصوصية
	$wp_customize->add_setting( 'contact_consent_text', array(
		'default'           => __( 'أوافق على سياسة الخصوصية ومعالجة بياناتي', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_consent_text', array(
		'label'    => __( 'نص موافقة الخصوصية', 'nadiim' ),
		'section'  => 'nadiim_contact_fields',
		'type'     => 'textarea',
	) );

	// رسالة النجاح
	$wp_customize->add_setting( 'contact_success_message', array(
		'default'           => __( 'شكراً لتواصلك معنا! سنرد عليك في أقرب وقت.', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_success_message', array(
		'label'    => __( 'رسالة النجاح', 'nadiim' ),
		'section'  => 'nadiim_contact_fields',
		'type'     => 'text',
	) );

	// رسالة الخطأ
	$wp_customize->add_setting( 'contact_error_message', array(
		'default'           => __( 'حدث خطأ أثناء إرسال رسالتك. يُرجى المحاولة مرة أخرى.', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_error_message', array(
		'label'    => __( 'رسالة الخطأ', 'nadiim' ),
		'section'  => 'nadiim_contact_fields',
		'type'     => 'text',
	) );

	// ==========================================
	// Section 3: التصميم
	// ==========================================
	$wp_customize->add_section( 'nadiim_contact_design', array(
		'title'    => __( 'التصميم', 'nadiim' ),
		'panel'    => 'nadiim_contact_panel',
		'priority' => 30,
	) );

	// لون الخلفية
	$wp_customize->add_setting( 'contact_bg_color', array(
		'default'           => '#f8f9fa',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'contact_bg_color', array(
		'label'    => __( 'لون خلفية الصفحة', 'nadiim' ),
		'section'  => 'nadiim_contact_design',
	) ) );

	// لون خلفية النموذج
	$wp_customize->add_setting( 'contact_form_bg', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'contact_form_bg', array(
		'label'    => __( 'لون خلفية النموذج', 'nadiim' ),
		'section'  => 'nadiim_contact_design',
	) ) );

	// نص زر الإرسال
	$wp_customize->add_setting( 'contact_btn_text', array(
		'default'           => __( 'إرسال الرسالة', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_btn_text', array(
		'label'    => __( 'نص زر الإرسال', 'nadiim' ),
		'section'  => 'nadiim_contact_design',
		'type'     => 'text',
	) );

	// لون زر الإرسال
	$wp_customize->add_setting( 'contact_btn_color', array(
		'default'           => '#339063',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'contact_btn_color', array(
		'label'    => __( 'لون زر الإرسال', 'nadiim' ),
		'section'  => 'nadiim_contact_design',
	) ) );

	// الخط المستخدم
	$wp_customize->add_setting( 'contact_font', array(
		'default'           => 'Cairo',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_font', array(
		'label'    => __( 'الخط المستخدم', 'nadiim' ),
		'section'  => 'nadiim_contact_design',
		'type'     => 'select',
		'choices'  => array(
			'Cairo'      => 'Cairo',
			'Tajawal'    => 'Tajawal',
			'Noto Kufi Arabic' => 'Noto Kufi Arabic',
		),
	) );

	// تخطيط الصفحة
	$wp_customize->add_setting( 'contact_layout', array(
		'default'           => 'form-left',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_layout', array(
		'label'    => __( 'تخطيط الصفحة', 'nadiim' ),
		'section'  => 'nadiim_contact_design',
		'type'     => 'select',
		'choices'  => array(
			'form-left'  => __( 'النموذج يسار - الخريطة يمين', 'nadiim' ),
			'form-top'   => __( 'النموذج أعلى - الخريطة أسفل', 'nadiim' ),
			'full-width' => __( 'النموذج بعرض كامل', 'nadiim' ),
		),
	) );

	// ==========================================
	// Section 4: الخريطة وموقع اللقاء
	// ==========================================
	$wp_customize->add_section( 'nadiim_contact_map', array(
		'title'    => __( 'الخريطة والموقع', 'nadiim' ),
		'panel'    => 'nadiim_contact_panel',
		'priority' => 40,
	) );

	// تفعيل الخريطة
	$wp_customize->add_setting( 'contact_map_enable', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_map_enable', array(
		'label'    => __( 'إظهار الخريطة', 'nadiim' ),
		'section'  => 'nadiim_contact_map',
		'type'     => 'checkbox',
	) );

	// Latitude
	$wp_customize->add_setting( 'contact_map_lat', array(
		'default'           => '24.7136',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_map_lat', array(
		'label'       => __( 'خط العرض (Latitude)', 'nadiim' ),
		'description' => __( 'مثال: 24.7136', 'nadiim' ),
		'section'     => 'nadiim_contact_map',
		'type'        => 'text',
	) );

	// Longitude
	$wp_customize->add_setting( 'contact_map_lng', array(
		'default'           => '46.6753',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_map_lng', array(
		'label'       => __( 'خط الطول (Longitude)', 'nadiim' ),
		'description' => __( 'مثال: 46.6753', 'nadiim' ),
		'section'     => 'nadiim_contact_map',
		'type'        => 'text',
	) );

	// Zoom
	$wp_customize->add_setting( 'contact_map_zoom', array(
		'default'           => '13',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'contact_map_zoom', array(
		'label'       => __( 'مستوى التكبير (Zoom)', 'nadiim' ),
		'description' => __( 'رقم من 1 إلى 18', 'nadiim' ),
		'section'     => 'nadiim_contact_map',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 18,
			'step' => 1,
		),
	) );

	// Embed Iframe (fallback)
	$wp_customize->add_setting( 'contact_map_embed', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'contact_map_embed', array(
		'label'       => __( 'كود iframe للخريطة (احتياطي)', 'nadiim' ),
		'description' => __( 'إذا أردت استخدام Google Maps iframe بدلاً من Leaflet', 'nadiim' ),
		'section'     => 'nadiim_contact_map',
		'type'        => 'textarea',
	) );

	// إظهار العنوان
	$wp_customize->add_setting( 'contact_show_address', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_show_address', array(
		'label'    => __( 'إظهار العنوان', 'nadiim' ),
		'section'  => 'nadiim_contact_map',
		'type'     => 'checkbox',
	) );

	// نص العنوان
	$wp_customize->add_setting( 'contact_address_text', array(
		'default'           => __( 'الرياض، المملكة العربية السعودية', 'nadiim' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'contact_address_text', array(
		'label'    => __( 'نص العنوان', 'nadiim' ),
		'section'  => 'nadiim_contact_map',
		'type'     => 'textarea',
	) );

	// ==========================================
	// Section 5: البريد التلقائي
	// ==========================================
	$wp_customize->add_section( 'nadiim_contact_email', array(
		'title'    => __( 'قوالب البريد الإلكتروني', 'nadiim' ),
		'panel'    => 'nadiim_contact_panel',
		'priority' => 50,
	) );

	// قالب عنوان البريد
	$wp_customize->add_setting( 'contact_email_subject_template', array(
		'default'           => '[{site}] رسالة جديدة من {name}',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'contact_email_subject_template', array(
		'label'       => __( 'قالب عنوان البريد', 'nadiim' ),
		'description' => __( 'يمكنك استخدام: {site}, {name}, {subject}', 'nadiim' ),
		'section'     => 'nadiim_contact_email',
		'type'        => 'text',
	) );

	// قالب محتوى البريد
	$wp_customize->add_setting( 'contact_email_body_template', array(
		'default'           => "رسالة جديدة من موقع {site}\n\nالاسم: {name}\nالبريد: {email}\nالهاتف: {phone}\n\nالموضوع: {subject}\n\nالرسالة:\n{message}",
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'contact_email_body_template', array(
		'label'       => __( 'قالب محتوى البريد', 'nadiim' ),
		'description' => __( 'يمكنك استخدام: {site}, {name}, {email}, {phone}, {subject}, {message}', 'nadiim' ),
		'section'     => 'nadiim_contact_email',
		'type'        => 'textarea',
		'input_attrs' => array(
			'rows' => 10,
		),
	) );
}
add_action( 'customize_register', 'nadiim_contact_customizer_register' );

/**
 * إضافة JS للمعاينة الحية في Customizer
 */
function nadiim_contact_customizer_preview_js() {
	wp_enqueue_script(
		'nadiim-contact-customizer-preview',
		NADIIM_THEME_URI . '/assets/js/contact-customizer-preview.js',
		array( 'customize-preview' ),
		NADIIM_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'nadiim_contact_customizer_preview_js' );
