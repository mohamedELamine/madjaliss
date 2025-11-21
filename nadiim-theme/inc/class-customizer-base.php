<?php
/**
 * Customizer Base Class - لتقليل التكرار في ملفات التخصيص
 *
 * @package Nadiim
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Nadiim_Customizer_Base
 *
 * فئة أساسية توفر دوال مساعدة لإنشاء الإعدادات والمحكمات بسهولة
 */
class Nadiim_Customizer_Base {

    /**
     * WP_Customize_Manager instance
     *
     * @var WP_Customize_Manager
     */
    protected $wp_customize;

    /**
     * Constructor
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function __construct( $wp_customize ) {
        $this->wp_customize = $wp_customize;
    }

    /**
     * إضافة قسم (Section)
     *
     * @param string $id معرف القسم
     * @param array  $args الإعدادات
     */
    protected function add_section( $id, $args = array() ) {
        $this->wp_customize->add_section( $id, $args );
    }

    /**
     * إضافة لوحة (Panel)
     *
     * @param string $id معرف اللوحة
     * @param array  $args الإعدادات
     */
    protected function add_panel( $id, $args = array() ) {
        $this->wp_customize->add_panel( $id, $args );
    }

    /**
     * إضافة إعداد بسيط (Setting)
     *
     * @param string $id معرف الإعداد
     * @param array  $args الإعدادات
     */
    protected function add_setting( $id, $args = array() ) {
        $defaults = array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        );

        $args = wp_parse_args( $args, $defaults );
        $this->wp_customize->add_setting( $id, $args );
    }

    /**
     * إضافة محكم نص (Text Control)
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_text_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'type'            => 'text',
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control( $id, $control_args );
    }

    /**
     * إضافة محكم textarea
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_textarea_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : '',
            'sanitize_callback' => isset( $args['sanitize_callback'] ) ? $args['sanitize_callback'] : 'wp_kses_post',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'type'            => 'textarea',
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control( $id, $control_args );
    }

    /**
     * إضافة محكم checkbox
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_checkbox_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'type'            => 'checkbox',
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control( $id, $control_args );
    }

    /**
     * إضافة محكم select
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_select_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'type'            => 'select',
            'choices'         => isset( $args['choices'] ) ? $args['choices'] : array(),
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control( $id, $control_args );
    }

    /**
     * إضافة محكم number/range
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_number_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : 0,
            'sanitize_callback' => 'absint',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'type'            => isset( $args['type'] ) ? $args['type'] : 'number',
            'input_attrs'     => isset( $args['input_attrs'] ) ? $args['input_attrs'] : array(),
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control( $id, $control_args );
    }

    /**
     * إضافة محكم range
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_range_control( $id, $section, $args = array() ) {
        $args['type'] = 'range';
        $this->add_number_control( $id, $section, $args );
    }

    /**
     * إضافة محكم color
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_color_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : '#000000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'postMessage',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control(
            new WP_Customize_Color_Control( $this->wp_customize, $id, $control_args )
        );
    }

    /**
     * إضافة محكم image/media
     *
     * @param string $id معرف المحكم
     * @param string $section القسم
     * @param array  $args الإعدادات
     */
    protected function add_image_control( $id, $section, $args = array() ) {
        $this->add_setting( $id, array(
            'default'           => isset( $args['default'] ) ? $args['default'] : '',
            'sanitize_callback' => 'absint',
            'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
        ) );

        $control_args = array(
            'label'           => isset( $args['label'] ) ? $args['label'] : '',
            'description'     => isset( $args['description'] ) ? $args['description'] : '',
            'section'         => $section,
            'mime_type'       => 'image',
            'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : null,
        );

        $this->wp_customize->add_control(
            new WP_Customize_Media_Control( $this->wp_customize, $id, $control_args )
        );
    }

    /**
     * إضافة مجموعة من المحكمات الشائعة لقسم معين
     *
     * @param string $section_id معرف القسم
     * @param array  $controls مصفوفة المحكمات
     */
    protected function add_controls_group( $section_id, $controls = array() ) {
        foreach ( $controls as $control ) {
            if ( ! isset( $control['id'] ) || ! isset( $control['type'] ) ) {
                continue;
            }

            $method = 'add_' . $control['type'] . '_control';
            if ( method_exists( $this, $method ) ) {
                $this->$method( $control['id'], $section_id, $control );
            }
        }
    }

    /**
     * دالة مساعدة للحصول على theme mod بقيمة افتراضية
     *
     * @param string $key المفتاح
     * @param mixed  $default القيمة الافتراضية
     * @return mixed
     */
    public static function get_mod( $key, $default = '' ) {
        return get_theme_mod( $key, $default );
    }

    /**
     * دالة مساعدة لـ sanitize float
     *
     * @param mixed $value القيمة
     * @return float
     */
    public static function sanitize_float( $value ) {
        return floatval( $value );
    }

    /**
     * دالة مساعدة لـ sanitize RGBA color
     *
     * @param string $value القيمة
     * @return string
     */
    public static function sanitize_rgba( $value ) {
        // تحقق من نمط rgba
        if ( strpos( $value, 'rgba' ) !== false ) {
            return $value;
        }
        // تحقق من hex color
        return sanitize_hex_color( $value );
    }
}
