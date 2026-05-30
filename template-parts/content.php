<?php
/**
 * Default content template part.
 *
 * @package ExecutiveSignal
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card' ); ?>>
	<header class="entry-card__header">
		<?php executive_signal_render_post_meta(); ?>
		<?php the_title( '<h2 class="entry-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	</header>

	<div class="entry-card__summary">
		<?php echo esc_html( executive_signal_get_listing_excerpt() ); ?>
	</div>
</article>
