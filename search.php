<?php
/**
 * Search results template.
 *
 * @package ExecutiveSignal
 */

get_header();

global $wp_query;

$search_query = get_search_query();
$found_posts  = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
?>

<main id="primary" class="site-main site-main--blog">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php esc_html_e( 'Search', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 class="es-blog-archive-header__title">
					<?php
					printf(
						/* translators: %s: Search query. */
						esc_html__( 'Search results for: %s', 'executive-signal-wordpress-theme' ),
						esc_html( $search_query )
					);
					?>
				</h1>
				<p class="es-blog-archive-header__description">
					<?php esc_html_e( 'Find briefings, operational notes and strategic signals across the archive.', 'executive-signal-wordpress-theme' ); ?>
				</p>
			</div>
			<div class="es-blog-archive-header__meta">
				<?php
				printf(
					/* translators: %s: Number of posts found in the current search. */
					esc_html( _n( '%s result found', '%s results found', $found_posts, 'executive-signal-wordpress-theme' ) ),
					esc_html( number_format_i18n( $found_posts ) )
				);
				?>
			</div>
		</div>
	</header>

	<section class="es-article-archive-grid" data-columns="two" aria-label="<?php esc_attr_e( 'Search results', 'executive-signal-wordpress-theme' ); ?>">
		<?php if ( have_posts() ) : ?>
			<div class="es-article-archive-grid__items">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>

			<?php executive_signal_render_posts_pagination(); ?>
		<?php else : ?>
			<div class="es-article-archive-grid__empty es-article-archive-grid__empty--plain">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</div>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
