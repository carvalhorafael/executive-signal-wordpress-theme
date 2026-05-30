<?php
/**
 * Single post content template part.
 *
 * @package ExecutiveSignal
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<header class="entry__header">
		<?php executive_signal_render_post_meta(); ?>
		<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>
	</header>

	<div class="entry__content">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>
</article>
