<?php
/**
 * Empty content template part.
 *
 * @package ExecutiveSignal
 */

?>
<section class="es-empty-state no-results not-found">
	<header class="es-empty-state__header">
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
	</header>

	<div class="es-empty-state__content">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Try a different search or return to the homepage.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'This archive does not have published articles yet. Browse the latest briefings instead.', 'executive-signal-wordpress-theme' ); ?></p>
			<nav class="es-empty-state__links" aria-label="<?php esc_attr_e( 'Archive recovery links', 'executive-signal-wordpress-theme' ); ?>">
				<?php
				$posts_page_id = (int) get_option( 'page_for_posts' );
				$blog_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/blog/' );
				?>
				<a class="es-empty-state__link" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Browse latest articles', 'executive-signal-wordpress-theme' ); ?></a>
				<a class="es-empty-state__link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to homepage', 'executive-signal-wordpress-theme' ); ?></a>
			</nav>
		<?php endif; ?>
	</div>
</section>
