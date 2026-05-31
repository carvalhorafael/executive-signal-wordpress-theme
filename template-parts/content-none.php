<?php
/**
 * Empty content template part.
 *
 * @package ExecutiveSignal
 */

?>
<section class="es-empty-state no-results not-found">
	<header class="es-empty-state__header">
		<p class="es-empty-state__eyebrow"><?php esc_html_e( 'No results', 'executive-signal-wordpress-theme' ); ?></p>
		<h1 class="es-empty-state__title"><?php esc_html_e( 'Nothing found', 'executive-signal-wordpress-theme' ); ?></h1>
	</header>

	<div class="es-empty-state__content">
		<p><?php esc_html_e( 'Try a different search or return to the homepage.', 'executive-signal-wordpress-theme' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</section>
