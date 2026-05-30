<?php
/**
 * Single post content template part.
 *
 * @package ExecutiveSignal
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<header class="es-article-hero">
		<div class="es-article-hero__content">
			<?php executive_signal_render_primary_category( 'es-article-hero__eyebrow' ); ?>
			<?php the_title( '<h1 class="es-article-hero__title">', '</h1>' ); ?>
			<?php if ( has_excerpt() ) : ?>
				<p class="es-article-hero__description"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<div class="es-article-hero__meta">
				<?php executive_signal_render_article_meta_row(); ?>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="es-article-hero__media">
				<?php the_post_thumbnail( 'large' ); ?>
			</figure>
		<?php endif; ?>
	</header>

	<div class="entry__content es-article-prose">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>
</article>
