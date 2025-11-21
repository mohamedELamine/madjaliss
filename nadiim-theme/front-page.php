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
		'topbar',
		'hero-slider', // Hero Slider الجديد
		'featured-howarat', // الحوارات المميزة
		'dialogues',
		'esdar', // قسم الإصدارات
		'articles', // قسم المقالات الجديد
		'clubs',
		'newsletter',
	) );

	// عرض الأقسام حسب الترتيب المحدد
	foreach ( $sections_order as $section ) {
		$section_enabled = get_theme_mod( "home_{$section}_enable", true );

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
