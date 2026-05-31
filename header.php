<?php
/**
 * Theme header.
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
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'executive-signal-wordpress-theme' ); ?></a>

	<header class="site-header es-blog-site-header" data-site-header>
		<div class="site-header__inner es-blog-site-header__inner">
			<div class="site-branding es-blog-site-header__brand">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<a class="site-title es-blog-site-header__brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					<?php
				}
				?>
			</div>

			<?php executive_signal_render_header_navigation(); ?>
		</div>
	</header>
