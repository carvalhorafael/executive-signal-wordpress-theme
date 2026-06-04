<?php
/**
 * Single post content template part.
 *
 * @package ExecutiveSignal
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?> itemscope itemtype="https://schema.org/BlogPosting">
	<header class="es-article-hero" data-layout="text-only">
		<div class="es-article-hero__content">
			<?php executive_signal_render_primary_category( 'es-article-hero__eyebrow' ); ?>
			<?php the_title( '<h1 class="es-article-hero__title" itemprop="headline">', '</h1>' ); ?>
			<?php if ( has_excerpt() ) : ?>
				<p class="es-article-hero__description" itemprop="description"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<div class="es-article-hero__meta">
				<?php executive_signal_render_article_meta_row(); ?>
			</div>
			<meta itemprop="dateModified" content="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>">
			<meta itemprop="mainEntityOfPage" content="<?php echo esc_url( get_permalink() ); ?>">
		</div>

	</header>

	<div class="entry__content es-article-prose" itemprop="articleBody">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>

	<footer class="entry__footer">
		<?php executive_signal_render_article_tags(); ?>
	</footer>
</article>
