<?php
/**
 * Single course template.
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main site-main--course-single">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'course' );
	endwhile;
	?>
</main>

<?php
get_footer();
