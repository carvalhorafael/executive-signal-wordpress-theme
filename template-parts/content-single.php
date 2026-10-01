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
$coo_page             = get_page_by_path( 'coo-as-a-service' );
$coo_url              = $coo_page instanceof WP_Post && 'publish' === $coo_page->post_status ? get_permalink( $coo_page ) : home_url( '/coo-as-a-service/' );
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
		<aside class="entry__widget-area entry__widget-area--left" aria-label="<?php esc_attr_e( 'Post left rail', 'executive-signal-wordpress-theme' ); ?>">
			<section class="entry-coo-cta" aria-labelledby="entry-coo-cta-title">
				<p class="entry-coo-cta__eyebrow"><?php esc_html_e( 'COO as a Service', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="entry-coo-cta-title" class="entry-coo-cta__title"><?php esc_html_e( 'A operação ainda depende demais de você?', 'executive-signal-wordpress-theme' ); ?></h2>
				<p class="entry-coo-cta__description"><?php esc_html_e( 'Organize prioridades e decisões para a operação avançar sem depender tanto de você.', 'executive-signal-wordpress-theme' ); ?></p>
				<a class="es-button entry-coo-cta__action" data-variant="primary" data-size="md" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Veja como funciona', 'executive-signal-wordpress-theme' ); ?></a>
			</section>

			<?php if ( is_active_sidebar( 'post-left' ) ) : ?>
				<?php dynamic_sidebar( 'post-left' ); ?>
			<?php endif; ?>
		</aside>

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
