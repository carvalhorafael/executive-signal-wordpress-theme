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
	<script>
		(() => {
			try {
				const theme = window.localStorage.getItem("executive-signal-theme");

				if (theme === "light" || theme === "dark" || theme === "system") {
					document.documentElement.dataset.esTheme = theme;
				}
			} catch (error) {
				document.documentElement.dataset.esTheme = "light";
			}
		})();
	</script>
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

			<div class="es-blog-site-header__actions">
				<?php executive_signal_render_header_search(); ?>
				<a class="es-header-icon-link" href="<?php echo esc_url( get_feed_link() ); ?>" aria-label="<?php esc_attr_e( 'RSS feed', 'executive-signal-wordpress-theme' ); ?>">
					<svg class="es-header-icon-link__svg" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
						<path d="M5 5.5C12.45 5.5 18.5 11.55 18.5 19" />
						<path d="M5 11C9.42 11 13 14.58 13 19" />
						<circle cx="6" cy="18" r="1.5" />
					</svg>
				</a>
				<?php executive_signal_render_theme_switcher(); ?>
			</div>
		</div>
	</header>
