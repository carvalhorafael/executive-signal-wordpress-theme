<?php
/**
 * Front page template.
 *
 * @package ExecutiveSignal
 */

if ( 'posts' === get_option( 'show_on_front' ) ) {
	require get_home_template();
	return;
}

$assigned_page_template = get_page_template();

if ( $assigned_page_template && realpath( $assigned_page_template ) !== realpath( __FILE__ ) ) {
	require $assigned_page_template;
	return;
}

get_header();
?>

<main id="primary" class="site-main site-main--front-page">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );
		endwhile;
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>
</main>

<?php
get_footer();
