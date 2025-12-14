<?php
/**
 * قالب الصفحة الرئيسية - Front Page
 *
 * صفحة رئيسية قابلة للتخصيص بالكامل مع أقسام متعددة
 * يمكن التحكم في ظهور وترتيب الأقسام من Customizer
 *
 * @package Nadiim
 * @since 2.0.0
 */

get_header();

// تحميل CSS و JS الخاصة بالصفحة الرئيسية
wp_enqueue_style( 'nadiim-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array(), '2.0.0' );
wp_enqueue_style( 'nadiim-about-mini', get_template_directory_uri() . '/assets/css/about-mini.css', array(), '1.0.0' );
wp_enqueue_script( 'nadiim-front-page', get_template_directory_uri() . '/assets/js/front-page.js', array( 'jquery' ), '2.0.0', true );

// تمرير متغيرات AJAX إلى JavaScript
wp_localize_script( 'nadiim-front-page', 'nadiimFrontPage', array(
	'ajax_url' => admin_url( 'admin-ajax.php' ),
	'nonce'    => wp_create_nonce( 'nadiim-front-page-nonce' ),
) );
?>

<main id="primary" class="site-main front-page-main">

	<?php
	/**
	 * ترتيب الأقسام المخصص
	 * يمكن تغيير الترتيب من Customizer
	 */
	$sections_order = get_theme_mod( 'home_sections_order', array(
		'hero-slider', // Hero Slider الجديد
		'about-mini', // قسم من نحن المصغر
		'featured-howarat', // الحوارات المميزة
		'reviews', // قسم المراجعات
		'dialogues',
		'esdar', // قسم الإصدارات
		'articles', // قسم المقالات الجديد
		'clubs',
		'newsletter',
	) );

	// عرض الأقسام حسب الترتيب المحدد
	foreach ( $sections_order as $section ) {
		// تحديد اسم الإعداد الصحيح لكل قسم
		$setting_name = "home_{$section}_enable";

		// معالجة الأقسام التي لها أسماء إعدادات مختلفة
		switch ( $section ) {
			case 'esdar':
				$setting_name = 'esdar_section_enable';
				break;
			case 'hero-slider':
				$setting_name = 'hero_enable';
				break;
			case 'featured-howarat':
				$setting_name = 'featured_howarat_enable';
				break;
			case 'reviews':
				$setting_name = 'reviews_enable';
				break;
			case 'clubs':
				$setting_name = 'clubs_section_enable';
				break;
			case 'about-mini':
				$setting_name = 'about_mini_enable';
				break;
			case 'articles':
				$setting_name = 'articles_section_enable';
				break;
		}

		$section_enabled = get_theme_mod( $setting_name, true );

		if ( $section_enabled ) {
			$template_file = "template-parts/sections/{$section}.php";

			// التحقق من وجود الملف قبل تحميله
			if ( locate_template( $template_file ) ) {
				get_template_part( 'template-parts/sections/' . $section );
			}
		}
	}
	?>

</main><!-- #primary -->

<?php
get_footer();
