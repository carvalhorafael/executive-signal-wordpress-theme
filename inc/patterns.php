<?php
/**
 * Block pattern registration.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme pattern categories.
 *
 * @return void
 */
function executive_signal_register_pattern_categories() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'executive-signal-wordpress-theme',
		array(
			'label' => esc_html__( 'Executive Signal', 'executive-signal-wordpress-theme' ),
		)
	);
}
add_action( 'init', 'executive_signal_register_pattern_categories' );

/**
 * Capture the HTML output of a pattern PHP file.
 *
 * @param string $file Pattern file path.
 * @return string
 */
function executive_signal_get_pattern_content( $file ) {
	if ( ! file_exists( $file ) ) {
		return '';
	}

	ob_start();
	include $file;
	return trim( ob_get_clean() );
}

/**
 * Register Executive Signal block patterns.
 *
 * @return void
 */
function executive_signal_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	$patterns = array(
		'hero'           => array(
			'title'       => __( 'Executive Signal hero', 'executive-signal-wordpress-theme' ),
			'description' => __( 'Hero section for the Executive Signal homepage.', 'executive-signal-wordpress-theme' ),
		),
		'signal-grid'    => array(
			'title'       => __( 'Signal grid', 'executive-signal-wordpress-theme' ),
			'description' => __( 'Grid for editorial or operational signals.', 'executive-signal-wordpress-theme' ),
		),
		'report-preview' => array(
			'title'       => __( 'Report preview', 'executive-signal-wordpress-theme' ),
			'description' => __( 'Preview band for a report or briefing.', 'executive-signal-wordpress-theme' ),
		),
		'cta'            => array(
			'title'       => __( 'Executive Signal CTA', 'executive-signal-wordpress-theme' ),
			'description' => __( 'Focused call to action section.', 'executive-signal-wordpress-theme' ),
		),
		'landing-page'   => array(
			'title'       => __( 'Executive Signal landing page', 'executive-signal-wordpress-theme' ),
			'description' => __( 'Initial homepage composition.', 'executive-signal-wordpress-theme' ),
		),
	);

	foreach ( $patterns as $slug => $metadata ) {
		$pattern_name = 'executive-signal/' . $slug;

		if ( WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern_name ) ) {
			continue;
		}

		$content = executive_signal_get_pattern_content( EXECUTIVE_SIGNAL_THEME_DIR . '/patterns/' . $slug . '.php' );

		if ( '' === $content ) {
			continue;
		}

		register_block_pattern(
			$pattern_name,
			array(
				'title'       => $metadata['title'],
				'description' => $metadata['description'],
				'categories'  => array( 'executive-signal-wordpress-theme' ),
				'content'     => $content,
			)
		);
	}
}
add_action( 'init', 'executive_signal_register_block_patterns', 20 );
