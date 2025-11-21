<?php
/**
 * About Page Customizer Settings
 *
 * @package Nadiim
 * @since 1.0.0
 */

/**
 * Register About Page Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function nadiim_about_customize_register($wp_customize) {

    // ========================================
    // Add About Page Section
    // ========================================
    $wp_customize->add_section('nadiim_about_settings', array(
        'title'    => __('صفحة من نحن', 'nadiim'),
        'priority' => 50,
    ));

    // ========================================
    // Hero Settings
    // ========================================

    // Hero Enable
    $wp_customize->add_setting('about_hero_enable', array(
        'default'           => true,
        'sanitize_callback' => 'nadiim_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_hero_enable', array(
        'label'   => __('تفعيل قسم Hero', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'checkbox',
    ));

    // Hero Title
    $wp_customize->add_setting('about_hero_title', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_hero_title', array(
        'label'       => __('عنوان Hero', 'nadiim'),
        'description' => __('اتركه فارغاً لاستخدام عنوان الصفحة', 'nadiim'),
        'section'     => 'nadiim_about_settings',
        'type'        => 'text',
    ));

    // Hero Lead
    $wp_customize->add_setting('about_hero_lead', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_hero_lead', array(
        'label'   => __('نص تمهيدي', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'textarea',
    ));

    // Hero CTA Text
    $wp_customize->add_setting('about_hero_cta_text', array(
        'default'           => 'انضم معنا',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_hero_cta_text', array(
        'label'   => __('نص زر الدعوة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'text',
    ));

    // Hero CTA Link
    $wp_customize->add_setting('about_hero_cta_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_hero_cta_link', array(
        'label'   => __('رابط زر الدعوة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'url',
    ));

    // Hero Background Type
    $wp_customize->add_setting('about_hero_bg_type', array(
        'default'           => 'color',
        'sanitize_callback' => 'nadiim_sanitize_select',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_hero_bg_type', array(
        'label'   => __('نوع خلفية Hero', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'select',
        'choices' => array(
            'color' => __('لون', 'nadiim'),
            'image' => __('صورة', 'nadiim'),
        ),
    ));

    // Hero Background Color
    $wp_customize->add_setting('about_hero_bg_color', array(
        'default'           => '#F6FFF9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'about_hero_bg_color', array(
        'label'   => __('لون الخلفية', 'nadiim'),
        'section' => 'nadiim_about_settings',
    )));

    // Hero Background Image
    $wp_customize->add_setting('about_hero_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_hero_bg_image', array(
        'label'   => __('صورة الخلفية', 'nadiim'),
        'section' => 'nadiim_about_settings',
    )));

    // Hero Overlay
    $wp_customize->add_setting('about_hero_overlay', array(
        'default'           => 0.3,
        'sanitize_callback' => 'nadiim_sanitize_float',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_hero_overlay', array(
        'label'       => __('شفافية الغطاء', 'nadiim'),
        'description' => __('من 0 إلى 0.6', 'nadiim'),
        'section'     => 'nadiim_about_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 0.6,
            'step' => 0.1,
        ),
    ));

    // ========================================
    // Mission Settings
    // ========================================

    // Mission Title
    $wp_customize->add_setting('about_mission_title', array(
        'default'           => 'رسالتنا',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_mission_title', array(
        'label'   => __('عنوان قسم المهمة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'text',
    ));

    // Mission Text
    $wp_customize->add_setting('about_mission_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_mission_text', array(
        'label'   => __('نص المهمة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'textarea',
    ));

    // Values (3 cards)
    for ($i = 1; $i <= 3; $i++) {
        // Value Icon
        $wp_customize->add_setting("about_value_{$i}_icon", array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control("about_value_{$i}_icon", array(
            'label'       => sprintf(__('أيقونة القيمة %d', 'nadiim'), $i),
            'description' => __('أدخل emoji أو كود SVG', 'nadiim'),
            'section'     => 'nadiim_about_settings',
            'type'        => 'text',
        ));

        // Value Title
        $wp_customize->add_setting("about_value_{$i}_title", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control("about_value_{$i}_title", array(
            'label'   => sprintf(__('عنوان القيمة %d', 'nadiim'), $i),
            'section' => 'nadiim_about_settings',
            'type'    => 'text',
        ));

        // Value Text
        $wp_customize->add_setting("about_value_{$i}_text", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control("about_value_{$i}_text", array(
            'label'   => sprintf(__('نص القيمة %d', 'nadiim'), $i),
            'section' => 'nadiim_about_settings',
            'type'    => 'textarea',
        ));
    }

    // ========================================
    // Timeline Settings
    // ========================================

    // Timeline Enable
    $wp_customize->add_setting('about_timeline_enable', array(
        'default'           => true,
        'sanitize_callback' => 'nadiim_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_timeline_enable', array(
        'label'   => __('تفعيل الخط الزمني', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'checkbox',
    ));

    // Timeline Order
    $wp_customize->add_setting('about_timeline_order', array(
        'default'           => 'asc',
        'sanitize_callback' => 'nadiim_sanitize_select',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_timeline_order', array(
        'label'   => __('ترتيب الخط الزمني', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'select',
        'choices' => array(
            'asc'  => __('من الأقدم إلى الأحدث', 'nadiim'),
            'desc' => __('من الأحدث إلى الأقدم', 'nadiim'),
        ),
    ));

    // Timeline JSON (hidden, managed by JS)
    $wp_customize->add_setting('about_timeline_json', array(
        'default'           => '',
        'sanitize_callback' => 'nadiim_sanitize_timeline_json',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_timeline_json', array(
        'label'       => __('بيانات الخط الزمني (JSON)', 'nadiim'),
        'description' => __('استخدم الواجهة أدناه لإدارة الأحداث', 'nadiim'),
        'section'     => 'nadiim_about_settings',
        'type'        => 'textarea',
    ));

    // ========================================
    // Members Settings
    // ========================================

    // Members Enable
    $wp_customize->add_setting('about_members_enable', array(
        'default'           => true,
        'sanitize_callback' => 'nadiim_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_members_enable', array(
        'label'   => __('تفعيل قسم الأعضاء', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'checkbox',
    ));

    // Members Columns
    $wp_customize->add_setting('about_members_columns', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_members_columns', array(
        'label'   => __('عدد الأعمدة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'select',
        'choices' => array(
            2 => __('عمودين', 'nadiim'),
            3 => __('3 أعمدة', 'nadiim'),
            4 => __('4 أعمدة', 'nadiim'),
        ),
    ));

    // Members JSON (hidden, managed by JS)
    $wp_customize->add_setting('about_members_json', array(
        'default'           => '',
        'sanitize_callback' => 'nadiim_sanitize_members_json',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_members_json', array(
        'label'       => __('بيانات الأعضاء (JSON)', 'nadiim'),
        'description' => __('استخدم الواجهة أدناه لإدارة الأعضاء', 'nadiim'),
        'section'     => 'nadiim_about_settings',
        'type'        => 'textarea',
    ));

    // ========================================
    // CTA Settings
    // ========================================

    // CTA Text
    $wp_customize->add_setting('about_cta_text', array(
        'default'           => 'انضم إلينا في رحلة نشر الثقافة والمعرفة',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_cta_text', array(
        'label'   => __('نص دعوة للعمل', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'textarea',
    ));

    // CTA Button Text
    $wp_customize->add_setting('about_cta_button_text', array(
        'default'           => 'ابدأ الآن',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_cta_button_text', array(
        'label'   => __('نص زر الدعوة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'text',
    ));

    // CTA Button Link
    $wp_customize->add_setting('about_cta_button_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('about_cta_button_link', array(
        'label'   => __('رابط زر الدعوة', 'nadiim'),
        'section' => 'nadiim_about_settings',
        'type'    => 'url',
    ));

    // CTA Background
    $wp_customize->add_setting('about_cta_bg', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('about_cta_bg', array(
        'label'       => __('خلفية CTA', 'nadiim'),
        'description' => __('اتركه فارغاً للتدرج الافتراضي', 'nadiim'),
        'section'     => 'nadiim_about_settings',
        'type'        => 'text',
    ));
}
add_action('customize_register', 'nadiim_about_customize_register');

/**
 * Sanitize Timeline JSON
 */
function nadiim_sanitize_timeline_json($input) {
    if (empty($input)) {
        return '';
    }

    $data = json_decode($input, true);
    if (!is_array($data)) {
        return '';
    }

    // Sanitize each timeline event
    $sanitized = array();
    foreach ($data as $event) {
        if (!is_array($event)) {
            continue;
        }

        $sanitized_event = array(
            'date'              => isset($event['date']) ? sanitize_text_field($event['date']) : '',
            'title'             => isset($event['title']) ? sanitize_text_field($event['title']) : '',
            'short_description' => isset($event['short_description']) ? sanitize_text_field($event['short_description']) : '',
            'full_description'  => isset($event['full_description']) ? wp_kses_post($event['full_description']) : '',
            'image'             => isset($event['image']) ? esc_url_raw($event['image']) : '',
            'link'              => isset($event['link']) ? esc_url_raw($event['link']) : '',
        );

        $sanitized[] = $sanitized_event;
    }

    return wp_json_encode($sanitized, JSON_UNESCAPED_UNICODE);
}

/**
 * Sanitize Members JSON
 */
function nadiim_sanitize_members_json($input) {
    if (empty($input)) {
        return '';
    }

    $data = json_decode($input, true);
    if (!is_array($data)) {
        return '';
    }

    // Sanitize each member
    $sanitized = array();
    foreach ($data as $member) {
        if (!is_array($member)) {
            continue;
        }

        $sanitized_member = array(
            'name'         => isset($member['name']) ? sanitize_text_field($member['name']) : '',
            'role'         => isset($member['role']) ? sanitize_text_field($member['role']) : '',
            'short_bio'    => isset($member['short_bio']) ? sanitize_textarea_field($member['short_bio']) : '',
            'photo_id'     => isset($member['photo_id']) ? absint($member['photo_id']) : 0,
            'profile_link' => isset($member['profile_link']) ? esc_url_raw($member['profile_link']) : '',
            'display'      => isset($member['display']) ? (bool)$member['display'] : true,
        );

        $sanitized[] = $sanitized_member;
    }

    return wp_json_encode($sanitized, JSON_UNESCAPED_UNICODE);
}

/**
 * Enqueue Customizer scripts
 */
function nadiim_about_customizer_scripts() {
    wp_enqueue_script(
        'nadiim-about-customizer',
        get_template_directory_uri() . '/assets/js/about-customizer.js',
        array('jquery', 'customize-controls'),
        '1.0.0',
        true
    );

    wp_localize_script('nadiim-about-customizer', 'nadiimAboutCustomizer', array(
        'strings' => array(
            'addEvent'       => __('إضافة حدث', 'nadiim'),
            'removeEvent'    => __('حذف', 'nadiim'),
            'eventDate'      => __('التاريخ (YYYY-MM-DD)', 'nadiim'),
            'eventTitle'     => __('العنوان', 'nadiim'),
            'eventShortDesc' => __('وصف قصير', 'nadiim'),
            'eventFullDesc'  => __('وصف كامل', 'nadiim'),
            'eventImage'     => __('رابط الصورة', 'nadiim'),
            'eventLink'      => __('رابط', 'nadiim'),
            'addMember'      => __('إضافة عضو', 'nadiim'),
            'removeMember'   => __('حذف', 'nadiim'),
            'memberName'     => __('الاسم', 'nadiim'),
            'memberRole'     => __('الدور', 'nadiim'),
            'memberBio'      => __('نبذة قصيرة', 'nadiim'),
            'memberPhoto'    => __('معرف الصورة', 'nadiim'),
            'memberLink'     => __('رابط الملف الشخصي', 'nadiim'),
            'memberDisplay'  => __('عرض', 'nadiim'),
        ),
    ));
}
add_action('customize_controls_enqueue_scripts', 'nadiim_about_customizer_scripts');
