<?php
/**
 * Signal grid block pattern.
 *
 * @package ExecutiveSignal
 */

?>
<!-- wp:group {"className":"es-section es-signal-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group es-section es-signal-grid">
	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Signals worth attention', 'executive-signal-wordpress-theme' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"className":"es-card-grid"} -->
	<div class="wp-block-columns es-card-grid">
		<!-- wp:column {"className":"es-card"} -->
		<div class="wp-block-column es-card"><!-- wp:paragraph {"className":"es-kicker"} --><p class="es-kicker"><?php esc_html_e( 'Market', 'executive-signal-wordpress-theme' ); ?></p><!-- /wp:paragraph --><!-- wp:paragraph --><p><?php esc_html_e( 'Track shifts before they become obvious.', 'executive-signal-wordpress-theme' ); ?></p><!-- /wp:paragraph --></div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"es-card"} -->
		<div class="wp-block-column es-card"><!-- wp:paragraph {"className":"es-kicker"} --><p class="es-kicker"><?php esc_html_e( 'Operation', 'executive-signal-wordpress-theme' ); ?></p><!-- /wp:paragraph --><!-- wp:paragraph --><p><?php esc_html_e( 'Connect execution signals to decisions.', 'executive-signal-wordpress-theme' ); ?></p><!-- /wp:paragraph --></div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
