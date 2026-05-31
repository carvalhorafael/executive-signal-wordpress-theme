<?php
/**
 * Default content template part.
 *
 * @package ExecutiveSignal
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'es-article-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="es-article-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
			<?php the_post_thumbnail( 'large' ); ?>
		</a>
	<?php endif; ?>

	<div class="es-article-card__body">
		<header class="entry-card__header">
			<?php executive_signal_render_primary_category(); ?>
			<?php the_title( '<h2 class="es-article-card__title"><a class="es-article-card__title-link" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>

		<p class="es-article-card__excerpt">
			<?php echo esc_html( executive_signal_get_listing_excerpt() ); ?>
		</p>

		<footer class="es-article-card__footer">
			<?php executive_signal_render_article_meta_row(); ?>
			<a class="es-article-card__action" href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'Read article', 'executive-signal-wordpress-theme' ); ?>
			</a>
		</footer>
	</div>
</article>
