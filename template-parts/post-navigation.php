<?php
/**
 * Adjacent post navigation template part.
 *
 * @package ExecutiveSignal
 */

$previous_post = get_adjacent_post( false, '', true );
$next_post     = get_adjacent_post( false, '', false );

if ( ! $previous_post && ! $next_post ) {
	return;
}
?>

<nav class="es-post-navigation" aria-label="<?php esc_attr_e( 'Article navigation', 'executive-signal-wordpress-theme' ); ?>">
	<?php if ( $previous_post ) : ?>
		<a class="es-post-navigation__link" data-direction="previous" href="<?php echo esc_url( get_permalink( $previous_post ) ); ?>">
			<span class="es-post-navigation__label"><?php esc_html_e( 'Previous article', 'executive-signal-wordpress-theme' ); ?></span>
			<span class="es-post-navigation__title"><?php echo esc_html( get_the_title( $previous_post ) ); ?></span>
		</a>
	<?php else : ?>
		<span class="es-post-navigation__link es-post-navigation__link--empty" data-direction="previous" aria-hidden="true"></span>
	<?php endif; ?>

	<?php if ( $next_post ) : ?>
		<a class="es-post-navigation__link" data-direction="next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
			<span class="es-post-navigation__label"><?php esc_html_e( 'Next article', 'executive-signal-wordpress-theme' ); ?></span>
			<span class="es-post-navigation__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
		</a>
	<?php else : ?>
		<span class="es-post-navigation__link es-post-navigation__link--empty" data-direction="next" aria-hidden="true"></span>
	<?php endif; ?>
</nav>
