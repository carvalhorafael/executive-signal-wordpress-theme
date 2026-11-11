<?php
/**
 * Focused header for the links page.
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
<body <?php body_class( 'links-page-body' ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site site--links">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'executive-signal-wordpress-theme' ); ?></a>

	<header class="links-site-header">
		<div class="links-site-header__inner">
			<div class="links-site-header__brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="links-site-header__brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</div>

			<?php executive_signal_render_theme_switcher(); ?>
		</div>
	</header>
