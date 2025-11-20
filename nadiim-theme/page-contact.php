<?php
/**
 * Template Name: صفحة اتصل بنا
 *
 * قالب صفحة الاتصال المتكامل
 *
 * @package Nadiim
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main contact-page">
	<div class="container">

		<?php while ( have_posts() ) : the_post(); ?>

			<header class="page-header text-center">
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<div class="page-intro">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</header>

		<?php endwhile; ?>

		<!-- نموذج الاتصال والخريطة -->
		<?php get_template_part( 'template-parts/contact/form', 'contact' ); ?>

	</div>
</main>

<?php
get_footer();
