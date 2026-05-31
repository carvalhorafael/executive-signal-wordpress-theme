<?php
/**
 * Template Name: Centered page
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main site-main--page site-main--page-centered">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'page' );
	endwhile;
	?>
</main>

<?php
get_footer();
