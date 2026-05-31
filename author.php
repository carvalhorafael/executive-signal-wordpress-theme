<?php
/**
 * Author archive template.
 *
 * @package ExecutiveSignal
 */

get_header();

global $wp_query;

$author_id          = (int) get_queried_object_id();
$author_name        = get_the_author_meta( 'display_name', $author_id );
$author_description = get_the_author_meta( 'description', $author_id );
$author_url         = get_the_author_meta( 'user_url', $author_id );
$found_posts        = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
?>

<main id="primary" class="site-main site-main--blog">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php esc_html_e( 'Author', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 class="es-blog-archive-header__title"><?php echo esc_html( $author_name ); ?></h1>
				<p class="es-blog-archive-header__description">
					<?php esc_html_e( 'Articles, notes and strategic signals published by this author.', 'executive-signal-wordpress-theme' ); ?>
				</p>
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

	<aside class="es-author-byline">
		<div class="es-author-byline__avatar">
			<?php echo get_avatar( $author_id, 96 ); ?>
		</div>
		<div class="es-author-byline__content">
			<div class="es-author-byline__header">
				<div class="es-author-byline__identity">
					<p class="es-author-byline__role"><?php esc_html_e( 'Author', 'executive-signal-wordpress-theme' ); ?></p>
					<h2 class="es-author-byline__name"><?php echo esc_html( $author_name ); ?></h2>
				</div>
				<p class="es-author-byline__meta">
					<?php
					printf(
						/* translators: %s: Number of posts found in the current author archive. */
						esc_html( _n( '%s article', '%s articles', $found_posts, 'executive-signal-wordpress-theme' ) ),
						esc_html( number_format_i18n( $found_posts ) )
					);
					?>
				</p>
			</div>

			<?php if ( $author_description ) : ?>
				<p class="es-author-byline__bio"><?php echo esc_html( $author_description ); ?></p>
			<?php endif; ?>

			<?php if ( $author_url ) : ?>
				<div class="es-author-byline__actions">
					<a class="es-article-card__action" href="<?php echo esc_url( $author_url ); ?>"><?php esc_html_e( 'Author website', 'executive-signal-wordpress-theme' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</aside>

	<section class="es-article-archive-grid" data-columns="two" aria-label="<?php esc_attr_e( 'Author articles', 'executive-signal-wordpress-theme' ); ?>">
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
