<?php
/**
 * Hero block pattern.
 *
 * @package ExecutiveSignal
 */

?>
<!-- wp:group {"className":"es-section es-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group es-section es-hero">
	<!-- wp:paragraph {"className":"es-kicker"} -->
	<p class="es-kicker"><?php esc_html_e( 'Executive Signal', 'executive-signal-wordpress-theme' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"className":"es-hero__title"} -->
	<h1 class="wp-block-heading es-hero__title"><?php esc_html_e( 'Decisions, signals and briefings with executive clarity.', 'executive-signal-wordpress-theme' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"es-hero__summary"} -->
	<p class="es-hero__summary"><?php esc_html_e( 'A focused WordPress surface for publishing high-signal operational intelligence.', 'executive-signal-wordpress-theme' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
