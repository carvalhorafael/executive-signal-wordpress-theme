<?php
/**
 * Executive Signal theme bootstrap.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXECUTIVE_SIGNAL_THEME_DIR', get_template_directory() );
define( 'EXECUTIVE_SIGNAL_THEME_URI', get_template_directory_uri() );
define( 'EXECUTIVE_SIGNAL_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/setup.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/vite.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/assets.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/customizer.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/admin-notices.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/free-materials.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/patterns.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/template-tags.php';
require_once EXECUTIVE_SIGNAL_THEME_DIR . '/inc/updater.php';
