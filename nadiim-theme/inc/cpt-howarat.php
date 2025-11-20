<?php
/**
 * Custom Post Type: الحوارات (Howarat)
 *
 * تجربة المستخدم النهائية:
 * - يمكن للمحرر إنشاء حوار جديد من لوحة التحكم
 * - يدعم العنوان، المحتوى، المقتطف، الصورة البارزة، والكاتب
 * - يظهر في القائمة الجانبية بأيقونة محادثة
 * - له أرشيف عام وصفحة مفردة
 * - يدعم REST API للاستخدام في Gutenberg
 *
 * @package Nadiim
 * @since 2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل Custom Post Type للحوارات
 */
function nadiim_register_howarat_cpt() {
	$labels = array(
		'name'                  => _x( 'الحوارات', 'Post Type General Name', 'nadiim' ),
		'singular_name'         => _x( 'حوار', 'Post Type Singular Name', 'nadiim' ),
		'menu_name'             => __( 'الحوارات', 'nadiim' ),
		'name_admin_bar'        => __( 'حوار', 'nadiim' ),
		'archives'              => __( 'أرشيف الحوارات', 'nadiim' ),
		'attributes'            => __( 'خصائص الحوار', 'nadiim' ),
		'parent_item_colon'     => __( 'الحوار الأب:', 'nadiim' ),
		'all_items'             => __( 'كل الحوارات', 'nadiim' ),
		'add_new_item'          => __( 'إضافة حوار جديد', 'nadiim' ),
		'add_new'               => __( 'إضافة جديد', 'nadiim' ),
		'new_item'              => __( 'حوار جديد', 'nadiim' ),
		'edit_item'             => __( 'تحرير الحوار', 'nadiim' ),
		'update_item'           => __( 'تحديث الحوار', 'nadiim' ),
		'view_item'             => __( 'عرض الحوار', 'nadiim' ),
		'view_items'            => __( 'عرض الحوارات', 'nadiim' ),
		'search_items'          => __( 'بحث في الحوارات', 'nadiim' ),
		'not_found'             => __( 'لم يتم العثور على حوارات', 'nadiim' ),
		'not_found_in_trash'    => __( 'لم يتم العثور على حوارات في سلة المهملات', 'nadiim' ),
		'featured_image'        => __( 'صورة الحوار', 'nadiim' ),
		'set_featured_image'    => __( 'تعيين صورة الحوار', 'nadiim' ),
		'remove_featured_image' => __( 'إزالة صورة الحوار', 'nadiim' ),
		'use_featured_image'    => __( 'استخدام كصورة للحوار', 'nadiim' ),
		'insert_into_item'      => __( 'إدراج في الحوار', 'nadiim' ),
		'uploaded_to_this_item' => __( 'رُفع إلى هذا الحوار', 'nadiim' ),
		'items_list'            => __( 'قائمة الحوارات', 'nadiim' ),
		'items_list_navigation' => __( 'التنقل في قائمة الحوارات', 'nadiim' ),
		'filter_items_list'     => __( 'تصفية قائمة الحوارات', 'nadiim' ),
	);

	$args = array(
		'label'               => __( 'حوار', 'nadiim' ),
		'description'         => __( 'الحوارات والمقابلات الفكرية', 'nadiim' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments' ),
		'taxonomies'          => array(), // سيتم إضافة التصنيفات لاحقاً إذا لزم الأمر
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-chat',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
		'rest_base'           => 'howarat',
		'rewrite'             => array(
			'slug'       => 'howarat',
			'with_front' => false,
		),
	);

	register_post_type( 'howarat', $args );
}
add_action( 'init', 'nadiim_register_howarat_cpt', 0 );

/**
 * تسجيل التصنيفات المخصصة للحوارات (اختياري)
 */
function nadiim_register_howarat_taxonomies() {
	// تصنيف: نوع الحوار (فيديو، صوت، مكتوب)
	$type_labels = array(
		'name'                       => _x( 'أنواع الحوارات', 'Taxonomy General Name', 'nadiim' ),
		'singular_name'              => _x( 'نوع الحوار', 'Taxonomy Singular Name', 'nadiim' ),
		'menu_name'                  => __( 'أنواع الحوارات', 'nadiim' ),
		'all_items'                  => __( 'كل الأنواع', 'nadiim' ),
		'parent_item'                => __( 'النوع الأب', 'nadiim' ),
		'parent_item_colon'          => __( 'النوع الأب:', 'nadiim' ),
		'new_item_name'              => __( 'اسم نوع جديد', 'nadiim' ),
		'add_new_item'               => __( 'إضافة نوع جديد', 'nadiim' ),
		'edit_item'                  => __( 'تحرير النوع', 'nadiim' ),
		'update_item'                => __( 'تحديث النوع', 'nadiim' ),
		'view_item'                  => __( 'عرض النوع', 'nadiim' ),
		'separate_items_with_commas' => __( 'فصل الأنواع بفواصل', 'nadiim' ),
		'add_or_remove_items'        => __( 'إضافة أو حذف أنواع', 'nadiim' ),
		'choose_from_most_used'      => __( 'اختر من الأكثر استخداماً', 'nadiim' ),
		'popular_items'              => __( 'الأنواع الشائعة', 'nadiim' ),
		'search_items'               => __( 'بحث في الأنواع', 'nadiim' ),
		'not_found'                  => __( 'لم يتم العثور على أنواع', 'nadiim' ),
		'no_terms'                   => __( 'لا توجد أنواع', 'nadiim' ),
		'items_list'                 => __( 'قائمة الأنواع', 'nadiim' ),
		'items_list_navigation'      => __( 'التنقل في قائمة الأنواع', 'nadiim' ),
	);

	$type_args = array(
		'labels'            => $type_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'dialogue-type' ),
	);

	register_taxonomy( 'dialogue_type', array( 'howarat' ), $type_args );

	// تصنيف: الموضوع
	$topic_labels = array(
		'name'                       => _x( 'مواضيع الحوارات', 'Taxonomy General Name', 'nadiim' ),
		'singular_name'              => _x( 'موضوع', 'Taxonomy Singular Name', 'nadiim' ),
		'menu_name'                  => __( 'المواضيع', 'nadiim' ),
		'all_items'                  => __( 'كل المواضيع', 'nadiim' ),
		'parent_item'                => __( 'الموضوع الأب', 'nadiim' ),
		'parent_item_colon'          => __( 'الموضوع الأب:', 'nadiim' ),
		'new_item_name'              => __( 'اسم موضوع جديد', 'nadiim' ),
		'add_new_item'               => __( 'إضافة موضوع جديد', 'nadiim' ),
		'edit_item'                  => __( 'تحرير الموضوع', 'nadiim' ),
		'update_item'                => __( 'تحديث الموضوع', 'nadiim' ),
		'view_item'                  => __( 'عرض الموضوع', 'nadiim' ),
		'separate_items_with_commas' => __( 'فصل المواضيع بفواصل', 'nadiim' ),
		'add_or_remove_items'        => __( 'إضافة أو حذف مواضيع', 'nadiim' ),
		'choose_from_most_used'      => __( 'اختر من الأكثر استخداماً', 'nadiim' ),
		'popular_items'              => __( 'المواضيع الشائعة', 'nadiim' ),
		'search_items'               => __( 'بحث في المواضيع', 'nadiim' ),
		'not_found'                  => __( 'لم يتم العثور على مواضيع', 'nadiim' ),
		'no_terms'                   => __( 'لا توجد مواضيع', 'nadiim' ),
		'items_list'                 => __( 'قائمة المواضيع', 'nadiim' ),
		'items_list_navigation'      => __( 'التنقل في قائمة المواضيع', 'nadiim' ),
	);

	$topic_args = array(
		'labels'            => $topic_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'dialogue-topic' ),
	);

	register_taxonomy( 'dialogue_topic', array( 'howarat' ), $topic_args );
}
add_action( 'init', 'nadiim_register_howarat_taxonomies', 0 );

/**
 * تخصيص رسائل التحديث للحوارات
 */
function nadiim_howarat_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['howarat'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'تم تحديث الحوار.', 'nadiim' ),
		2  => __( 'تم تحديث الحقل المخصص.', 'nadiim' ),
		3  => __( 'تم حذف الحقل المخصص.', 'nadiim' ),
		4  => __( 'تم تحديث الحوار.', 'nadiim' ),
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'تم استعادة الحوار من المراجعة %s', 'nadiim' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'تم نشر الحوار.', 'nadiim' ),
		7  => __( 'تم حفظ الحوار.', 'nadiim' ),
		8  => __( 'تم إرسال الحوار.', 'nadiim' ),
		9  => sprintf(
			__( 'الحوار مجدول للنشر في: <strong>%1$s</strong>.', 'nadiim' ),
			date_i18n( __( 'M j, Y @ G:i', 'nadiim' ), strtotime( $post->post_date ) )
		),
		10 => __( 'تم تحديث مسودة الحوار.', 'nadiim' ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'nadiim_howarat_updated_messages' );

/**
 * تخصيص النص في صندوق النشر
 */
function nadiim_howarat_publish_box_text( $translation, $text ) {
	if ( 'howarat' === get_post_type() ) {
		if ( $text === 'Publish' ) {
			return __( 'نشر الحوار', 'nadiim' );
		}
	}
	return $translation;
}
add_filter( 'gettext', 'nadiim_howarat_publish_box_text', 10, 2 );
