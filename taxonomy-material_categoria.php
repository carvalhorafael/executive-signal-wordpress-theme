<?php
/**
 * Free material category archive template.
 *
 * @package ExecutiveSignal
 */

get_header();

global $wp_query;

$archive_description = get_the_archive_description();
$found_posts         = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
?>

<main id="primary" class="site-main site-main--blog site-main--free-materials">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php esc_html_e( 'Free material category', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 class="es-blog-archive-header__title"><?php single_term_title(); ?></h1>
				<?php if ( $archive_description ) : ?>
					<div class="es-blog-archive-header__description">
						<?php echo wp_kses_post( wpautop( $archive_description ) ); ?>
					</div>
				<?php else : ?>
					<p class="es-blog-archive-header__description">
						<?php esc_html_e( 'Browse practical resources grouped by decision context.', 'executive-signal-wordpress-theme' ); ?>
					</p>
				<?php endif; ?>
			</div>
			<div class="es-blog-archive-header__meta">
				<?php
				printf(
					/* translators: %s: Number of free materials found in an archive. */
					esc_html( _n( '%s material found', '%s materials found', $found_posts, 'executive-signal-wordpress-theme' ) ),
					esc_html( number_format_i18n( $found_posts ) )
				);
				?>
			</div>
		</div>
		<div class="es-blog-archive-header__actions">
			<a class="es-button es-button--secondary" href="<?php echo esc_url( executive_signal_get_free_materials_page_url() ); ?>">
				<?php esc_html_e( 'View all materials', 'executive-signal-wordpress-theme' ); ?>
			</a>
		</div>
	</header>

	<section class="es-article-archive-grid free-material-archive-grid" data-columns="two" aria-label="<?php esc_attr_e( 'Free materials in category', 'executive-signal-wordpress-theme' ); ?>">
		<?php if ( have_posts() ) : ?>
			<div class="es-article-archive-grid__items">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'free-material-card' );
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
