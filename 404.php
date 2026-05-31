<?php
/**
 * 404 template.
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="es-empty-state error-404 not-found">
		<header class="es-empty-state__header">
			<p class="es-empty-state__eyebrow"><?php esc_html_e( 'Error 404', 'executive-signal-wordpress-theme' ); ?></p>
			<h1 class="es-empty-state__title"><?php esc_html_e( 'Page not found', 'executive-signal-wordpress-theme' ); ?></h1>
		</header>

		<div class="es-empty-state__content">
			<p><?php esc_html_e( 'The requested page could not be found. Try searching for what you need.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php get_search_form(); ?>

			<nav class="es-empty-state__links" aria-label="<?php esc_attr_e( 'Helpful links', 'executive-signal-wordpress-theme' ); ?>">
				<a class="es-empty-state__link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to homepage', 'executive-signal-wordpress-theme' ); ?></a>
				<?php
				$posts_page_id = (int) get_option( 'page_for_posts' );
				$blog_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/blog/' );

				if ( $blog_url ) :
					?>
					<a class="es-empty-state__link" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Browse the blog', 'executive-signal-wordpress-theme' ); ?></a>
				<?php endif; ?>
			</nav>

			<?php
			$categories = get_categories(
				array(
					'number'     => 4,
					'orderby'    => 'count',
					'order'      => 'DESC',
					'hide_empty' => true,
				)
			);
			?>
			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="es-empty-state__topics">
					<p class="es-empty-state__topics-title"><?php esc_html_e( 'Popular topics', 'executive-signal-wordpress-theme' ); ?></p>
					<ul class="es-empty-state__topic-list">
						<?php foreach ( $categories as $category ) : ?>
							<li>
								<a class="es-empty-state__topic" href="<?php echo esc_url( get_category_link( $category ) ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
