<?php
/**
 * Posts page template.
 *
 * @package ExecutiveSignal
 */

get_header();
?>

<main id="primary" class="site-main site-main--blog">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php echo esc_html( executive_signal_get_blog_setting( 'eyebrow', executive_signal_get_blog_eyebrow_default() ) ); ?></p>
				<h1 class="es-blog-archive-header__title"><?php echo esc_html( executive_signal_get_blog_setting( 'title', executive_signal_get_blog_title_default() ) ); ?></h1>
				<p class="es-blog-archive-header__description">
					<?php echo esc_html( executive_signal_get_blog_setting( 'description', executive_signal_get_blog_description_default() ) ); ?>
				</p>
			</div>
			<div class="es-blog-archive-header__meta">
				<?php
				printf(
					/* translators: %s: Number of published posts. */
					esc_html( _n( '%s published article', '%s published articles', (int) wp_count_posts()->publish, 'executive-signal-wordpress-theme' ) ),
					esc_html( number_format_i18n( (int) wp_count_posts()->publish ) )
				);
				?>
			</div>
		</div>
	</header>

	<section class="es-article-archive-grid" data-columns="two" aria-label="<?php esc_attr_e( 'Latest articles', 'executive-signal-wordpress-theme' ); ?>">
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
			<div class="es-article-archive-grid__empty">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</div>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
