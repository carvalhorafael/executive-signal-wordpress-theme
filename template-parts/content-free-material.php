<?php
/**
 * Template part for a single free material capture page.
 *
 * @package ExecutiveSignal
 */

$material_cta        = executive_signal_get_free_material_cta();
$capture_form_action = admin_url( 'admin-post.php' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single free-material-single' ); ?> itemscope itemtype="https://schema.org/CreativeWork">
	<header class="free-material-capture-hero">
		<div class="free-material-capture-hero__copy">
			<p class="free-material-capture-hero__eyebrow"><?php esc_html_e( 'Free material', 'executive-signal-wordpress-theme' ); ?></p>
			<?php the_title( '<h1 class="free-material-capture-hero__title" itemprop="headline">', '</h1>' ); ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="free-material-capture-hero__media">
					<?php the_post_thumbnail( 'large', array( 'class' => 'free-material-capture-hero__image' ) ); ?>
				</figure>
			<?php endif; ?>

			<meta itemprop="mainEntityOfPage" content="<?php echo esc_url( get_permalink() ); ?>">
		</div>

		<aside id="capture" class="free-material-capture-panel" aria-labelledby="free-material-capture-title">
			<div class="free-material-capture-panel__header">
				<p class="free-material-capture-panel__eyebrow"><?php esc_html_e( 'Immediate access', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="free-material-capture-title" class="free-material-capture-panel__title"><?php esc_html_e( 'Complete the form', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>

			<p class="free-material-capture-panel__description"><?php esc_html_e( 'To receive the material.', 'executive-signal-wordpress-theme' ); ?></p>

			<form class="free-material-capture-panel__form" action="<?php echo esc_url( $capture_form_action ); ?>" method="post">
				<input type="hidden" name="action" value="brevo_leads_capture_free_material">
				<?php wp_nonce_field( 'brevo_leads_capture_free_material' ); ?>
				<input type="hidden" name="material_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
				<p class="free-material-capture-panel__field">
					<label for="free-material-capture-name"><?php esc_html_e( 'Name', 'executive-signal-wordpress-theme' ); ?></label>
					<input id="free-material-capture-name" name="name" type="text" autocomplete="name" required placeholder="<?php esc_attr_e( 'Your full name', 'executive-signal-wordpress-theme' ); ?>">
				</p>
				<p class="free-material-capture-panel__field">
					<label for="free-material-capture-email"><?php esc_html_e( 'Email', 'executive-signal-wordpress-theme' ); ?></label>
					<input id="free-material-capture-email" name="email" type="email" autocomplete="email" required placeholder="<?php esc_attr_e( 'you@example.com', 'executive-signal-wordpress-theme' ); ?>">
				</p>
				<p class="free-material-capture-panel__field">
					<label for="free-material-capture-whatsapp"><?php esc_html_e( 'WhatsApp', 'executive-signal-wordpress-theme' ); ?></label>
					<input id="free-material-capture-whatsapp" name="whatsapp" type="tel" autocomplete="tel" required placeholder="<?php esc_attr_e( '(00) 00000-0000', 'executive-signal-wordpress-theme' ); ?>">
				</p>
				<button class="es-button free-material-capture-panel__button" data-variant="primary" data-full-width="true" type="submit">
					<?php echo esc_html( $material_cta['label'] ); ?>
				</button>
			</form>

			<p class="free-material-capture-panel__note"><?php esc_html_e( 'No payment required. Keep the material for future reference.', 'executive-signal-wordpress-theme' ); ?></p>
		</aside>
	</header>

	<section class="free-material-detail">
		<div class="free-material-detail__intro">
			<p class="free-material-detail__eyebrow"><?php esc_html_e( 'What you will find', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="free-material-detail__title"><?php esc_html_e( 'Applied knowledge to accelerate your journey and avoid costly mistakes.', 'executive-signal-wordpress-theme' ); ?></h2>
		</div>

		<div class="entry__content es-article-prose free-material-single__content" itemprop="text">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</section>

	<section class="free-material-final-cta" aria-labelledby="free-material-final-cta-title">
		<div>
			<p class="free-material-final-cta__eyebrow"><?php esc_html_e( 'Ready to use', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 id="free-material-final-cta-title" class="free-material-final-cta__title"><?php esc_html_e( 'Access the content and apply the ideas today.', 'executive-signal-wordpress-theme' ); ?></h2>
		</div>
		<div class="free-material-final-cta__actions">
			<a class="es-button" data-variant="primary" href="#capture">
				<?php echo esc_html( $material_cta['label'] ); ?>
			</a>
		</div>
	</section>
</article>
