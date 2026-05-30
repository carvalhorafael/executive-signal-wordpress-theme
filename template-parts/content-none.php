<?php
/**
 * Empty content template part.
 *
 * @package ExecutiveSignal
 */

?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing found', 'executive-signal-wordpress-theme' ); ?></h1>
	</header>

	<div class="page-content">
		<p><?php esc_html_e( 'Try a different search or return to the homepage.', 'executive-signal-wordpress-theme' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</section>
