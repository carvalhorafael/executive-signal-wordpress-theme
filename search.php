<?php
/**
 * Search results template.
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main">
	<header class="page-header">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: Search query. */
				esc_html__( 'Search results for: %s', 'executive-signal-wordpress-theme' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
	</header>

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;

		the_posts_pagination();
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>
</main>

<?php
get_footer();
