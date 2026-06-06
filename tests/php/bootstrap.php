<?php
/**
 * PHPUnit bootstrap for wp-env integration tests.
 *
 * @package ExecutiveSignal
 */

$wp_load = '/var/www/html/wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	echo "WordPress bootstrap not found at {$wp_load}. Run npm run wp:start first.\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

require_once $wp_load;

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( function_exists( 'activate_plugin' ) && ! is_plugin_active( 'free-materials/free-materials.php' ) ) {
	activate_plugin( 'free-materials/free-materials.php' );
}

if ( ! defined( 'EXECUTIVE_SIGNAL_THEME_DIR' ) ) {
	$theme = wp_get_theme( 'executive-signal-wordpress-theme' );

	if ( ! $theme->exists() ) {
		echo "Theme executive-signal-wordpress-theme is not installed in wp-env.\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit( 1 );
	}

	switch_theme( 'executive-signal-wordpress-theme' );
	require_once get_theme_root() . '/executive-signal-wordpress-theme/functions.php';
}

if ( function_exists( 'executive_signal_theme_setup' ) ) {
	executive_signal_theme_setup();
}

if (
	function_exists( 'executive_signal_register_pattern_categories' )
	&& ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( 'executive-signal-wordpress-theme' )
) {
	executive_signal_register_pattern_categories();
}

if (
	function_exists( 'executive_signal_register_block_patterns' )
	&& ! WP_Block_Patterns_Registry::get_instance()->is_registered( 'executive-signal/hero' )
) {
	executive_signal_register_block_patterns();
}
