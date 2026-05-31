<?php
/**
 * Tag archive template.
 *
 * @package ExecutiveSignal
 */

get_header();

global $wp_query;

$queried_tag         = get_queried_object();
$tag_name            = $queried_tag instanceof WP_Term ? $queried_tag->name : single_tag_title( '', false );
$archive_description = tag_description();
$found_posts         = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
?>

<main id="primary" class="site-main site-main--blog">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php esc_html_e( 'Tag', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 class="es-blog-archive-header__title"><?php echo esc_html( $tag_name ); ?></h1>
				<?php if ( $archive_description ) : ?>
					<div class="es-blog-archive-header__description">
						<?php echo wp_kses_post( wpautop( $archive_description ) ); ?>
					</div>
				<?php else : ?>
					<p class="es-blog-archive-header__description">
						<?php
						printf(
							/* translators: %s: Tag name. */
							esc_html__( 'Signals tagged with %s.', 'executive-signal-wordpress-theme' ),
							esc_html( $tag_name )
						);
						?>
					</p>
				<?php endif; ?>
			</div>
			<div class="es-blog-archive-header__meta">
				<?php
				printf(
					/* translators: %s: Number of posts found in the current archive. */
					esc_html( _n( '%s article found', '%s articles found', $found_posts, 'executive-signal-wordpress-theme' ) ),
					esc_html( number_format_i18n( $found_posts ) )
				);
				?>
			</div>
		</div>
	</header>

	<section class="es-article-archive-grid" data-columns="two" aria-label="<?php esc_attr_e( 'Tag articles', 'executive-signal-wordpress-theme' ); ?>">
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
