<?php
/**
 * Page content template part.
 *
 * @package ExecutiveSignal
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' ); ?>>
	<header class="es-article-hero es-article-hero--page">
		<div class="es-article-hero__content">
			<?php the_title( '<h1 class="es-article-hero__title">', '</h1>' ); ?>
			<?php if ( has_excerpt() ) : ?>
				<p class="es-article-hero__description"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</header>

	<div class="entry__content es-article-prose">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page navigation', 'executive-signal-wordpress-theme' ) . '">',
				'after'  => '</nav>',
			)
		);
		?>
	</div>
</article>
