<?php
/**
 * Template part for a single free material capture page.
 *
 * @package ExecutiveSignal
 */

$material_cta        = executive_signal_get_free_material_cta();
$capture_form_action = admin_url( 'admin-post.php' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single free-material-single es-resource-capture-landing' ); ?> itemscope itemtype="https://schema.org/CreativeWork">
	<header class="es-resource-capture-hero" data-visual-placement="below-title">
		<div class="es-resource-capture-hero__copy">
			<p class="es-resource-capture-hero__eyebrow"><?php esc_html_e( 'Free material', 'executive-signal-wordpress-theme' ); ?></p>
			<?php the_title( '<h1 class="es-resource-capture-hero__title" itemprop="headline">', '</h1>' ); ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="es-resource-capture-hero__visual" data-placement="below-title">
					<?php the_post_thumbnail( 'large', array( 'class' => 'es-resource-cover' ) ); ?>
				</figure>
			<?php endif; ?>

			<meta itemprop="mainEntityOfPage" content="<?php echo esc_url( get_permalink() ); ?>">
		</div>

		<div class="es-resource-capture-hero__panel">
			<aside id="capture" class="es-resource-capture-panel" aria-labelledby="free-material-capture-title">
				<p class="es-resource-capture-panel__eyebrow"><?php esc_html_e( 'Immediate access', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="free-material-capture-title" class="es-resource-capture-panel__title"><?php esc_html_e( 'Complete the form', 'executive-signal-wordpress-theme' ); ?></h2>

				<p class="es-resource-capture-panel__description"><?php esc_html_e( 'To receive the material.', 'executive-signal-wordpress-theme' ); ?></p>

				<div class="es-resource-capture-panel__body">
					<?php
					if ( function_exists( 'brevo_leads_capture_render_free_material_error_message' ) ) {
						brevo_leads_capture_render_free_material_error_message();
					}
					?>

					<form action="<?php echo esc_url( $capture_form_action ); ?>" method="post">
						<input type="hidden" name="action" value="brevo_leads_capture_free_material">
						<?php wp_nonce_field( 'brevo_leads_capture_free_material' ); ?>
						<input type="hidden" name="material_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
						<?php foreach ( executive_signal_get_free_material_capture_utm_fields() as $utm_field ) : ?>
							<input
								type="hidden"
								name="<?php echo esc_attr( $utm_field ); ?>"
								value="<?php echo esc_attr( executive_signal_get_free_material_capture_utm_value( $utm_field ) ); ?>"
							>
						<?php endforeach; ?>
						<p>
							<label for="free-material-capture-name"><?php esc_html_e( 'Name', 'executive-signal-wordpress-theme' ); ?></label>
							<input id="free-material-capture-name" name="name" type="text" autocomplete="name" required placeholder="<?php esc_attr_e( 'Your full name', 'executive-signal-wordpress-theme' ); ?>">
						</p>
						<p>
							<label for="free-material-capture-email"><?php esc_html_e( 'Email', 'executive-signal-wordpress-theme' ); ?></label>
							<input id="free-material-capture-email" name="email" type="email" autocomplete="email" required placeholder="<?php esc_attr_e( 'you@example.com', 'executive-signal-wordpress-theme' ); ?>">
						</p>
						<p>
							<label for="free-material-capture-whatsapp"><?php esc_html_e( 'WhatsApp', 'executive-signal-wordpress-theme' ); ?></label>
							<input id="free-material-capture-whatsapp" name="whatsapp" type="tel" autocomplete="tel" required placeholder="<?php esc_attr_e( '(00) 00000-0000', 'executive-signal-wordpress-theme' ); ?>">
						</p>
						<p class="free-material-capture-honeypot" aria-hidden="true">
							<label for="free-material-capture-website"><?php esc_html_e( 'Website', 'executive-signal-wordpress-theme' ); ?></label>
							<input
								id="free-material-capture-website"
								name="brevo_leads_capture_website"
								type="text"
								value=""
								autocomplete="off"
								tabindex="-1"
							>
						</p>
						<button class="es-button" data-variant="primary" data-full-width="true" type="submit">
							<?php echo esc_html( $material_cta['label'] ); ?>
						</button>
					</form>
				</div>

				<p class="free-material-capture-note"><?php esc_html_e( 'No payment required. Keep the material for future reference.', 'executive-signal-wordpress-theme' ); ?></p>
			</aside>
		</div>
	</header>

	<div class="es-resource-capture-landing__details">
		<section class="es-resource-detail">
			<h2 class="es-resource-detail__title"><?php esc_html_e( 'Applied knowledge to accelerate your journey and avoid costly mistakes.', 'executive-signal-wordpress-theme' ); ?></h2>

			<div class="es-resource-detail__body entry__content es-article-prose free-material-single__content" itemprop="text">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</section>
	</div>

	<div class="es-resource-capture-landing__final-cta">
		<section class="es-resource-final-cta" aria-labelledby="free-material-final-cta-title">
			<div>
				<h2 id="free-material-final-cta-title" class="es-resource-final-cta__title"><?php esc_html_e( 'Access the content and apply the ideas today.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<div class="es-resource-final-cta__action">
				<a class="es-button" data-variant="primary" href="#capture">
					<?php echo esc_html( $material_cta['label'] ); ?>
				</a>
			</div>
		</section>
	</div>
</article>
