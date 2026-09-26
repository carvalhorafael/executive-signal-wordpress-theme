<?php
/**
 * Template part for the highlighted free material.
 *
 * @package ExecutiveSignal
 */

$material = isset( $args['material'] ) && $args['material'] instanceof WP_Post ? $args['material'] : get_post();

if ( ! $material instanceof WP_Post ) {
	return;
}

$material_id       = (int) $material->ID;
$material_title    = get_the_title( $material_id );
$material_url      = get_permalink( $material_id );
$material_excerpt  = executive_signal_get_listing_excerpt( $material_id );
$material_category = executive_signal_get_primary_free_material_category( $material_id );
?>

<div class="free-materials-featured">
	<article
		id="post-<?php echo esc_attr( $material_id ); ?>"
		<?php post_class( 'es-featured-article-card free-material-featured-card', $material_id ); ?>
	>
		<?php if ( has_post_thumbnail( $material_id ) ) : ?>
			<a class="es-featured-article-card__media free-material-featured-card__media" href="<?php echo esc_url( $material_url ); ?>" aria-label="<?php echo esc_attr( $material_title ); ?>">
				<?php echo get_the_post_thumbnail( $material_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated image markup. ?>
			</a>
		<?php else : ?>
			<a class="es-featured-article-card__media free-material-featured-card__media free-material-featured-card__media--placeholder" href="<?php echo esc_url( $material_url ); ?>" aria-label="<?php echo esc_attr( $material_title ); ?>">
				<span class="free-material-card__placeholder-kicker"><?php esc_html_e( 'Material', 'executive-signal-wordpress-theme' ); ?></span>
				<span class="free-material-card__placeholder-title"><?php esc_html_e( 'Free', 'executive-signal-wordpress-theme' ); ?></span>
			</a>
		<?php endif; ?>

		<div class="es-featured-article-card__body">
			<p class="es-featured-article-card__category"><?php esc_html_e( 'Featured material', 'executive-signal-wordpress-theme' ); ?></p>

			<h2 class="es-featured-article-card__title">
				<a class="es-featured-article-card__title-link" href="<?php echo esc_url( $material_url ); ?>"><?php echo esc_html( $material_title ); ?></a>
			</h2>

			<?php if ( $material_excerpt ) : ?>
				<p class="es-featured-article-card__excerpt"><?php echo esc_html( $material_excerpt ); ?></p>
			<?php endif; ?>

			<div class="es-featured-article-card__footer">
				<?php if ( $material_category ) : ?>
					<span class="es-featured-article-card__meta"><?php echo esc_html( $material_category->name ); ?></span>
				<?php endif; ?>
				<div class="es-featured-article-card__actions">
					<a class="es-button" data-variant="primary" href="<?php echo esc_url( $material_url ); ?>">
						<?php esc_html_e( 'Access free material', 'executive-signal-wordpress-theme' ); ?>
					</a>
				</div>
			</div>
		</div>
	</article>
</div>
