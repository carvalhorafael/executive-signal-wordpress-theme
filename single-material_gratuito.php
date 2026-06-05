<?php
/**
 * Single free material template.
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main site-main--free-material-single">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'free-material' );
	endwhile;
	?>
</main>

<?php
get_footer();
