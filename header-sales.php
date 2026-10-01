<?php
/**
 * Focused header for sales pages.
 *
 * @package ExecutiveSignal
 */

?><!doctype html>
<html <?php language_attributes(); ?> data-es-theme="light" data-es-palette="signal">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'sales-page-body' ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site site--sales">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Pular para o conteúdo', 'executive-signal-wordpress-theme' ); ?></a>

	<header class="sales-site-header">
		<div class="sales-site-header__inner">
			<div class="sales-site-header__brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="sales-site-header__brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</div>

			<nav class="sales-site-header__nav" aria-label="<?php esc_attr_e( 'Navegação da página', 'executive-signal-wordpress-theme' ); ?>">
				<a href="#como-funciona"><?php esc_html_e( 'Como funciona', 'executive-signal-wordpress-theme' ); ?></a>
				<a href="#para-quem"><?php esc_html_e( 'Para quem é', 'executive-signal-wordpress-theme' ); ?></a>
			</nav>

			<a class="es-button sales-site-header__cta" data-variant="primary" data-size="sm" href="#conversar"><?php esc_html_e( 'Conversar sobre minha operação', 'executive-signal-wordpress-theme' ); ?></a>
		</div>
	</header>
