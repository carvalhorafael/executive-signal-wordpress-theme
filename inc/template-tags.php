<?php
/**
 * Shared template helpers.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a compact content label.
 *
 * @param string $label Label text.
 * @param string $variant Visual variant.
 * @return void
 */
function executive_signal_render_badge( $label, $variant = 'signal' ) {
	$variant_class = sanitize_html_class( $variant );
	?>
	<span class="es-badge es-badge--<?php echo esc_attr( $variant_class ); ?>">
		<?php echo esc_html( $label ); ?>
	</span>
	<?php
}

/**
 * Render post metadata for editorial templates.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function executive_signal_render_post_meta( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}
	?>
	<div class="entry-meta" aria-label="<?php esc_attr_e( 'Post information', 'executive-signal-wordpress-theme' ); ?>">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>">
			<?php echo esc_html( get_the_date( '', $post_id ) ); ?>
		</time>
		<span class="entry-meta__separator" aria-hidden="true">/</span>
		<span><?php echo esc_html( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ) ); ?></span>
	</div>
	<?php
}

/**
 * Get a concise excerpt for listings.
 *
 * @param int|null $post_id Post ID.
 * @param int      $words Maximum words.
 * @return string
 */
function executive_signal_get_listing_excerpt( $post_id = null, $words = 24 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	$excerpt = get_the_excerpt( $post_id );

	return wp_trim_words( $excerpt, $words, ' [...]' );
}
