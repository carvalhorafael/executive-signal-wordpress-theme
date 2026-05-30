<?php
/**
 * Frontend and editor asset loading.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend assets.
 *
 * @return void
 */
function executive_signal_enqueue_assets() {
	wp_enqueue_style(
		'executive-signal-style',
		get_stylesheet_uri(),
		array(),
		EXECUTIVE_SIGNAL_THEME_VERSION
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( executive_signal_vite_is_development() && executive_signal_vite_dev_server_is_running() ) {
		wp_enqueue_script( 'executive-signal-vite-client', EXECUTIVE_SIGNAL_VITE_DEV_SERVER . '/@vite/client', array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_script( 'executive-signal-theme', EXECUTIVE_SIGNAL_VITE_DEV_SERVER . '/src/main.js', array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_script_add_data( 'executive-signal-vite-client', 'type', 'module' );
		wp_script_add_data( 'executive-signal-theme', 'type', 'module' );
		return;
	}

	$entry = executive_signal_vite_manifest_entry( 'src/main.js' );

	if ( ! $entry || empty( $entry['file'] ) ) {
		return;
	}

	if ( ! empty( $entry['css'] ) && is_array( $entry['css'] ) ) {
		foreach ( $entry['css'] as $index => $css_file ) {
			wp_enqueue_style(
				'executive-signal-theme-' . $index,
				executive_signal_vite_asset_uri( $css_file ),
				array(),
				EXECUTIVE_SIGNAL_THEME_VERSION
			);
		}
	}

	wp_enqueue_script(
		'executive-signal-theme',
		executive_signal_vite_asset_uri( $entry['file'] ),
		array(),
		EXECUTIVE_SIGNAL_THEME_VERSION,
		true
	);
	wp_script_add_data( 'executive-signal-theme', 'type', 'module' );
}
add_action( 'wp_enqueue_scripts', 'executive_signal_enqueue_assets' );

/**
 * Enqueue block editor assets.
 *
 * @return void
 */
function executive_signal_enqueue_editor_assets() {
	if ( executive_signal_vite_is_development() && executive_signal_vite_dev_server_is_running() ) {
		wp_enqueue_style( 'executive-signal-editor', EXECUTIVE_SIGNAL_VITE_DEV_SERVER . '/src/styles/editor.css', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		return;
	}

	$entry = executive_signal_vite_manifest_entry( 'src/editor.js' );

	if ( ! $entry || empty( $entry['file'] ) ) {
		return;
	}

	if ( ! empty( $entry['css'] ) && is_array( $entry['css'] ) ) {
		foreach ( $entry['css'] as $index => $css_file ) {
			wp_enqueue_style(
				'executive-signal-editor-' . $index,
				executive_signal_vite_asset_uri( $css_file ),
				array(),
				EXECUTIVE_SIGNAL_THEME_VERSION
			);
		}
	}
}
add_action( 'enqueue_block_editor_assets', 'executive_signal_enqueue_editor_assets' );
