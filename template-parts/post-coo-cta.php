<?php
/**
 * COO as a Service call to action displayed after single post content.
 *
 * @package ExecutiveSignal
 */

$coo_page = get_page_by_path( 'coo-as-a-service' );
$coo_url  = $coo_page instanceof WP_Post && 'publish' === $coo_page->post_status ? get_permalink( $coo_page ) : home_url( '/coo-as-a-service/' );
?>

<aside class="entry-end-cta es-card" aria-labelledby="entry-end-cta-title">
	<p class="entry-end-cta__eyebrow"><?php esc_html_e( 'COO as a Service', 'executive-signal-wordpress-theme' ); ?></p>
	<h2 class="entry-end-cta__title" id="entry-end-cta-title"><?php esc_html_e( 'Leve mais clareza e capacidade de execução para a operação.', 'executive-signal-wordpress-theme' ); ?></h2>
	<div class="entry-end-cta__body">
		<p class="entry-end-cta__description"><?php esc_html_e( 'Organize prioridades, responsabilidades e decisões para a empresa avançar sem depender de cada decisão do CEO.', 'executive-signal-wordpress-theme' ); ?></p>
		<a class="es-button entry-end-cta__action" data-variant="primary" data-size="md" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Conheça o COO as a Service', 'executive-signal-wordpress-theme' ); ?></a>
	</div>
</aside>
