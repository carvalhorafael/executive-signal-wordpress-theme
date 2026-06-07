<?php
/**
 * Free materials landing page template.
 *
 * Template Name: Free materials
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

get_header();

$landing_page_id          = get_queried_object_id();
$landing_page_description = '';

if ( has_excerpt( $landing_page_id ) ) {
	$landing_page_description = wpautop( get_the_excerpt( $landing_page_id ) );
} elseif ( trim( get_post_field( 'post_content', $landing_page_id ) ) ) {
	$landing_page_description = apply_filters( 'the_content', get_post_field( 'post_content', $landing_page_id ) );
}

$free_material_categories     = get_terms(
	array(
		'hide_empty' => true,
		'taxonomy'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY,
	)
);
$has_free_material_categories = ! is_wp_error( $free_material_categories ) && ! empty( $free_material_categories );
$free_materials_query         = new WP_Query(
	array(
		'no_found_rows'          => false,
		'post_status'            => 'publish',
		'post_type'              => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		'posts_per_page'         => -1,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
	)
);
$found_posts                  = (int) $free_materials_query->found_posts;
?>

<main id="primary" class="site-main site-main--blog site-main--free-materials">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php esc_html_e( 'Free materials', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 class="es-blog-archive-header__title"><?php the_title(); ?></h1>
				<?php if ( $landing_page_description ) : ?>
					<div class="es-blog-archive-header__description">
						<?php echo wp_kses_post( $landing_page_description ); ?>
					</div>
				<?php else : ?>
					<p class="es-blog-archive-header__description">
						<?php esc_html_e( 'Guides, checklists and working notes for leaders who want clearer operating signals.', 'executive-signal-wordpress-theme' ); ?>
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
	</header>

	<section class="es-resource-browser" data-es-resource-browser="true">
		<aside class="es-resource-browser__filters" aria-label="<?php esc_attr_e( 'Material categories', 'executive-signal-wordpress-theme' ); ?>">
			<div class="es-resource-browser__filters-header">
				<p id="free-material-filters-title" class="es-resource-browser__filters-title"><?php esc_html_e( 'Material categories', 'executive-signal-wordpress-theme' ); ?></p>
				<button class="es-resource-browser__clear" type="button" data-es-resource-clear="true">
					<?php esc_html_e( 'Clear filters', 'executive-signal-wordpress-theme' ); ?>
				</button>
			</div>

			<?php if ( $has_free_material_categories ) : ?>
				<form>
					<fieldset class="es-resource-browser__facet-group">
						<legend class="es-resource-browser__facet-legend"><?php esc_html_e( 'Filter by topic', 'executive-signal-wordpress-theme' ); ?></legend>
						<div class="es-resource-browser__facet-options">
						<?php foreach ( $free_material_categories as $free_material_category ) : ?>
							<label class="es-resource-browser__facet-option">
								<input
									class="es-resource-browser__facet-input"
									name="<?php echo esc_attr( EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY ); ?>"
									type="checkbox"
									value="<?php echo esc_attr( $free_material_category->slug ); ?>"
									data-es-resource-filter="true"
								>
								<span class="es-resource-browser__facet-label"><?php echo esc_html( $free_material_category->name ); ?></span>
								<span class="es-resource-browser__facet-count"><?php echo esc_html( number_format_i18n( (int) $free_material_category->count ) ); ?></span>
							</label>
						<?php endforeach; ?>
						</div>
					</fieldset>
				</form>
			<?php else : ?>
				<p><?php esc_html_e( 'No material categories found.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php endif; ?>
		</aside>

		<section class="es-resource-browser__results" aria-label="<?php esc_attr_e( 'Material listing', 'executive-signal-wordpress-theme' ); ?>" aria-live="polite" data-es-resource-results="true">
			<?php if ( $free_materials_query->have_posts() ) : ?>
				<div class="es-resource-browser__items">
					<?php
					while ( $free_materials_query->have_posts() ) :
						$free_materials_query->the_post();

						get_template_part( 'template-parts/content', 'free-material-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>

				<p class="es-resource-browser__empty" hidden data-es-resource-empty="true">
					<?php esc_html_e( 'No materials match the selected categories.', 'executive-signal-wordpress-theme' ); ?>
				</p>
			<?php else : ?>
				<div class="es-article-archive-grid__empty es-article-archive-grid__empty--plain">
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				</div>
			<?php endif; ?>
		</section>
	</section>
</main>

<?php
get_footer();
