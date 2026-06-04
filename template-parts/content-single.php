<?php
/**
 * Single post content template part.
 *
 * @package ExecutiveSignal
 */

?>
<?php
$article_content_data = executive_signal_prepare_article_content( str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', get_the_content() ) ) );
$has_right_rail       = ! empty( $article_content_data['table_of_contents'] ) || is_active_sidebar( 'post-right' );
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

	<div class="entry__body-layout">
		<?php if ( is_active_sidebar( 'post-left' ) ) : ?>
			<aside class="entry__widget-area entry__widget-area--left" aria-label="<?php esc_attr_e( 'Post left rail', 'executive-signal-wordpress-theme' ); ?>">
				<?php dynamic_sidebar( 'post-left' ); ?>
			</aside>
		<?php endif; ?>

		<div class="entry__content es-article-prose" itemprop="articleBody">
			<?php
			echo $article_content_data['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is filtered through the WordPress content pipeline above.
			wp_link_pages();
			?>
		</div>

		<?php if ( $has_right_rail ) : ?>
			<aside class="entry__widget-area entry__widget-area--right" aria-label="<?php esc_attr_e( 'Post right rail', 'executive-signal-wordpress-theme' ); ?>">
				<?php executive_signal_render_article_table_of_contents( $article_content_data['table_of_contents'] ); ?>
				<?php dynamic_sidebar( 'post-right' ); ?>
			</aside>
		<?php endif; ?>
	</div>

	<footer class="entry__footer">
		<?php executive_signal_render_article_tags(); ?>
	</footer>
</article>
