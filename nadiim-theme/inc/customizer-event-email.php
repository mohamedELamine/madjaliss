<?php
/**
 * إعدادات Customizer لإيميلات التسجيل في الفعاليات
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل إعدادات إيميلات التسجيل في Customizer
 */
function nadiim_register_event_email_customizer( $wp_customize ) {

	// ========================================
	// Panel: إعدادات الفعاليات
	// ========================================
	$wp_customize->add_panel( 'nadiim_events_panel', array(
		'title'    => __( 'إعدادات الفعاليات', 'nadiim' ),
		'priority' => 200,
	) );

	// ========================================
	// Section: إيميلات التسجيل
	// ========================================
	$wp_customize->add_section( 'nadiim_event_email_section', array(
		'title'    => __( 'إيميلات التسجيل', 'nadiim' ),
		'panel'    => 'nadiim_events_panel',
		'priority' => 10,
	) );

	// ========================================
	// Setting: عنوان الإيميل
	// ========================================
	$wp_customize->add_setting( 'event_registration_email_subject', array(
		'default'           => __( 'تأكيد التسجيل في {event_title}', 'nadiim' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'event_registration_email_subject', array(
		'label'       => __( 'عنوان الإيميل', 'nadiim' ),
		'description' => __( 'المتغيرات المتاحة: {user_name}, {event_title}', 'nadiim' ),
		'section'     => 'nadiim_event_email_section',
		'type'        => 'text',
		'priority'    => 10,
	) );

	// ========================================
	// Setting: محتوى الإيميل
	// ========================================
	$default_body = "مرحباً {user_name}،\n\nشكراً لتسجيلك في فعالية: {event_title}\n\nتفاصيل الفعالية:\n📅 التاريخ: {event_date}\n🕐 الوقت: {event_time}\n📍 المكان: {event_location}\n🔗 الرابط: {event_link}\n\nنتطلع لرؤيتك!\n\nمع أطيب التحيات،\nفريق نديم";

	$wp_customize->add_setting( 'event_registration_email_body', array(
		'default'           => $default_body,
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'event_registration_email_body', array(
		'label'       => __( 'محتوى الإيميل', 'nadiim' ),
		'description' => __( 'المتغيرات: {user_name}, {event_title}, {event_date}, {event_time}, {event_location}, {event_link}', 'nadiim' ),
		'section'     => 'nadiim_event_email_section',
		'type'        => 'textarea',
		'input_attrs' => array(
			'rows' => 12,
		),
		'priority'    => 20,
	) );

	// ========================================
	// Setting: تفعيل إشعار المسؤول
	// ========================================
	$wp_customize->add_setting( 'event_registration_admin_notification', array(
		'default'           => true,
		'sanitize_callback' => 'nadiim_sanitize_checkbox',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'event_registration_admin_notification', array(
		'label'       => __( 'إرسال إشعار للمسؤول', 'nadiim' ),
		'description' => __( 'إرسال إيميل للمسؤول عند كل تسجيل جديد', 'nadiim' ),
		'section'     => 'nadiim_event_email_section',
		'type'        => 'checkbox',
		'priority'    => 30,
	) );

	// ========================================
	// Setting: بريد المسؤول
	// ========================================
	$wp_customize->add_setting( 'event_registration_admin_email', array(
		'default'           => get_option( 'admin_email' ),
		'sanitize_callback' => 'sanitize_email',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'event_registration_admin_email', array(
		'label'           => __( 'بريد المسؤول', 'nadiim' ),
		'description'     => __( 'البريد الإلكتروني لاستقبال إشعارات التسجيل', 'nadiim' ),
		'section'         => 'nadiim_event_email_section',
		'type'            => 'email',
		'priority'        => 40,
		'active_callback' => function() use ( $wp_customize ) {
			return $wp_customize->get_setting( 'event_registration_admin_notification' )->value() === true;
		},
	) );
}
add_action( 'customize_register', 'nadiim_register_event_email_customizer' );
