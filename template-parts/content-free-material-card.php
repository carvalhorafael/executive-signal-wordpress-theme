<?php
/**
 * Template part for free material cards.
 *
 * @package ExecutiveSignal
 */

$material_excerpt  = executive_signal_get_listing_excerpt();
$material_category = executive_signal_get_primary_free_material_category();
$material_terms    = get_the_terms( get_the_ID(), EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );
$material_slugs    = array();

if ( is_array( $material_terms ) ) {
	$material_slugs = wp_list_pluck( $material_terms, 'slug' );
}
?>

<div class="es-resource-browser__item" data-es-resource-item data-es-resource-facets="<?php echo esc_attr( implode( ' ', $material_slugs ) ); ?>">
	<article
		id="post-<?php the_ID(); ?>"
		<?php post_class( 'es-article-card free-material-card' ); ?>
	>
		<?php if ( has_post_thumbnail() ) : ?>
			<a class="es-article-card__media free-material-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		<?php else : ?>
			<a class="es-article-card__media free-material-card__media free-material-card__media--placeholder" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<span class="free-material-card__placeholder-kicker"><?php esc_html_e( 'Material', 'executive-signal-wordpress-theme' ); ?></span>
				<span class="free-material-card__placeholder-title"><?php esc_html_e( 'Free', 'executive-signal-wordpress-theme' ); ?></span>
			</a>
		<?php endif; ?>

		<div class="es-article-card__body">
			<?php if ( $material_category ) : ?>
				<p class="es-article-card__category"><?php echo esc_html( $material_category->name ); ?></p>
			<?php endif; ?>

			<h2 class="es-article-card__title">
				<a class="es-article-card__title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php if ( $material_excerpt ) : ?>
				<p class="es-article-card__excerpt"><?php echo esc_html( $material_excerpt ); ?></p>
			<?php endif; ?>

			<div class="es-article-card__footer">
				<div class="es-article-card__action">
					<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'View material', 'executive-signal-wordpress-theme' ); ?></a>
				</div>
			</div>
		</div>
	</article>
</div>
