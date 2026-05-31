<?php
/**
 * Theme setup.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and navigation areas.
 *
 * @return void
 */
function executive_signal_theme_setup() {
	load_theme_textdomain( 'executive-signal-wordpress-theme', EXECUTIVE_SIGNAL_THEME_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'search-form',
			'script',
			'style',
		)
	);

	add_editor_style( executive_signal_get_editor_stylesheets() );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary menu', 'executive-signal-wordpress-theme' ),
			'footer'  => esc_html__( 'Footer menu', 'executive-signal-wordpress-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'executive_signal_theme_setup' );

/**
 * Register editor block styles aligned with Executive Signal prose.
 *
 * @return void
 */
function executive_signal_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'executive-signal-solid',
			'label' => __( 'Executive solid', 'executive-signal-wordpress-theme' ),
		)
	);

	register_block_style(
		'core/quote',
		array(
			'name'  => 'executive-signal-panel',
			'label' => __( 'Executive panel', 'executive-signal-wordpress-theme' ),
		)
	);

	register_block_style(
		'core/separator',
		array(
			'name'  => 'executive-signal-accent',
			'label' => __( 'Executive accent', 'executive-signal-wordpress-theme' ),
		)
	);
}
add_action( 'init', 'executive_signal_register_block_styles' );

/**
 * Register footer widget areas.
 *
 * @return void
 */
function executive_signal_register_widget_areas() {
	$footer_widget_areas = array(
		'footer-1'      => __( 'Footer column 1', 'executive-signal-wordpress-theme' ),
		'footer-2'      => __( 'Footer column 2', 'executive-signal-wordpress-theme' ),
		'footer-3'      => __( 'Footer column 3', 'executive-signal-wordpress-theme' ),
		'footer-4'      => __( 'Footer column 4', 'executive-signal-wordpress-theme' ),
		'footer-bottom' => __( 'Footer bottom', 'executive-signal-wordpress-theme' ),
	);

	foreach ( $footer_widget_areas as $id => $name ) {
		register_sidebar(
			array(
				'id'            => $id,
				'name'          => $name,
				/* translators: %s: Footer widget area name. */
				'description'   => sprintf( __( 'Widgets added here appear in %s.', 'executive-signal-wordpress-theme' ), $name ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title es-blog-site-footer__group-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'executive_signal_register_widget_areas' );

/**
 * Get stylesheets loaded in the block editor canvas.
 *
 * @return string[]
 */
function executive_signal_get_editor_stylesheets() {
	if ( function_exists( 'executive_signal_vite_manifest_entry' ) && function_exists( 'executive_signal_vite_asset_uri' ) ) {
		$entry = executive_signal_vite_manifest_entry( 'src/editor.js' );

		if ( ! empty( $entry['css'] ) && is_array( $entry['css'] ) ) {
			return array_map( 'executive_signal_vite_asset_uri', $entry['css'] );
		}
	}

	return array( get_stylesheet_uri() );
}
