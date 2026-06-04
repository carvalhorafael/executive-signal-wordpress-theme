<?php
/**
 * Empty content template part.
 *
 * @package ExecutiveSignal
 */

?>
<section class="es-panel es-panel--muted es-empty-state no-results not-found" data-variant="editorial">
	<p class="es-empty-state__eyebrow">
		<?php
		if ( is_search() ) {
			esc_html_e( 'No results', 'executive-signal-wordpress-theme' );
		} else {
			esc_html_e( 'Empty archive', 'executive-signal-wordpress-theme' );
		}
		?>
	</p>
	<h1 class="es-empty-state__title">
		<?php
		if ( is_search() ) {
			esc_html_e( 'Nothing found', 'executive-signal-wordpress-theme' );
		} else {
			esc_html_e( 'No articles yet', 'executive-signal-wordpress-theme' );
		}
		?>
	</h1>
	<p class="es-empty-state__description">
		<?php
		if ( is_search() ) {
			esc_html_e( 'Try a different search or return to the homepage.', 'executive-signal-wordpress-theme' );
		} else {
			esc_html_e( 'This archive does not have published articles yet. Browse the latest briefings instead.', 'executive-signal-wordpress-theme' );
		}
		?>
	</p>

	<?php if ( is_search() ) : ?>
		<div class="es-empty-state__body">
			<?php get_search_form(); ?>
		</div>
	<?php else : ?>
		<div class="es-empty-state__action">
			<?php
			$posts_page_id = (int) get_option( 'page_for_posts' );
			$blog_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/blog/' );
			?>
			<a class="es-button" data-variant="primary" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Browse latest articles', 'executive-signal-wordpress-theme' ); ?></a>
			<a class="es-button" data-variant="ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to homepage', 'executive-signal-wordpress-theme' ); ?></a>
		</div>
	<?php endif; ?>
</section>
