<?php
/**
 * Template Name: Home — Rafael Carvalho
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="rafael-home">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/home/content' );
	endwhile;
	?>
</main>

<?php
get_footer();
