<?php
/**
 * Post sharing template part.
 *
 * @package ExecutiveSignal
 */

$post_url   = get_permalink();
$post_title = get_the_title();

if ( ! $post_url || ! $post_title ) {
	return;
}

$encoded_url   = rawurlencode( $post_url );
$encoded_title = rawurlencode( $post_title );
$share_links   = array(
	'whatsapp' => array(
		'label' => __( 'WhatsApp', 'executive-signal-wordpress-theme' ),
		'url'   => 'https://wa.me/?text=' . $encoded_title . '%20' . $encoded_url,
	),
	'linkedin' => array(
		'label' => __( 'LinkedIn', 'executive-signal-wordpress-theme' ),
		'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url,
	),
	'x'        => array(
		'label' => __( 'X', 'executive-signal-wordpress-theme' ),
		'url'   => 'https://twitter.com/intent/tweet?text=' . $encoded_title . '&url=' . $encoded_url,
	),
);
?>

<aside class="es-social-share-bar es-social-share-bar--article" aria-labelledby="article-share-title">
	<p class="es-social-share-bar__title" id="article-share-title"><?php esc_html_e( 'Share article', 'executive-signal-wordpress-theme' ); ?></p>
	<ul class="es-social-share-bar__items">
		<?php foreach ( $share_links as $network => $share_link ) : ?>
			<li>
				<a class="es-social-share-bar__link" href="<?php echo esc_url( $share_link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<span class="es-social-share-bar__icon" aria-hidden="true"><?php echo esc_html( strtoupper( substr( $network, 0, 1 ) ) ); ?></span>
					<span><?php echo esc_html( $share_link['label'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
		<li>
			<button
				class="es-social-share-bar__link es-social-share-bar__button"
				type="button"
				aria-live="polite"
				data-copy-link="<?php echo esc_url( $post_url ); ?>"
				data-copy-label="<?php esc_attr_e( 'Copy link', 'executive-signal-wordpress-theme' ); ?>"
				data-copied-label="<?php esc_attr_e( 'Copied', 'executive-signal-wordpress-theme' ); ?>"
			>
				<span class="es-social-share-bar__icon" aria-hidden="true">↗</span>
				<span data-copy-link-label><?php esc_html_e( 'Copy link', 'executive-signal-wordpress-theme' ); ?></span>
			</button>
		</li>
	</ul>
</aside>
