<?php
/**
 * 404 template.
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="es-empty-state error-404 not-found">
		<header class="es-empty-state__header">
			<p class="es-empty-state__eyebrow"><?php esc_html_e( 'Error 404', 'executive-signal-wordpress-theme' ); ?></p>
			<h1 class="es-empty-state__title"><?php esc_html_e( 'Page not found', 'executive-signal-wordpress-theme' ); ?></h1>
		</header>

		<div class="es-empty-state__content">
			<p><?php esc_html_e( 'The requested page could not be found. Try searching for what you need.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</section>
</main>

<?php
get_footer();
