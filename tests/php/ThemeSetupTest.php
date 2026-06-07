<?php
/**
 * Theme setup integration tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies WordPress-facing setup registered by the theme.
 *
 * @covers ::executive_signal_get_editor_stylesheets
 * @covers ::executive_signal_theme_setup
 */
final class ThemeSetupTest extends TestCase {
	/**
	 * Theme setup should enable expected WordPress supports.
	 */
	public function test_expected_theme_supports_are_registered(): void {
		$supports = array(
			'automatic-feed-links',
			'title-tag',
			'post-thumbnails',
			'custom-logo',
			'align-wide',
			'editor-styles',
			'responsive-embeds',
			'wp-block-styles',
			'html5',
		);

		foreach ( $supports as $support ) {
			$this->assertTrue( current_theme_supports( $support ), "{$support} support should be registered." );
		}
	}

	/**
	 * Navigation menu locations should be available to WordPress.
	 */
	public function test_menu_locations_are_registered(): void {
		$menus = get_registered_nav_menus();

		$this->assertArrayHasKey( 'primary', $menus );
		$this->assertArrayHasKey( 'footer', $menus );
	}

	/**
	 * Widget areas should include the post rails and footer surfaces.
	 */
	public function test_widget_areas_are_registered(): void {
		global $wp_registered_sidebars;

		$sidebars = array_keys( $wp_registered_sidebars );

		$this->assertContains( 'post-left', $sidebars );
		$this->assertContains( 'post-right', $sidebars );
		$this->assertContains( 'footer-1', $sidebars );
		$this->assertContains( 'footer-bottom', $sidebars );
	}

	/**
	 * Runtime asset version should match public theme version.
	 */
	public function test_runtime_theme_version_matches_stylesheet_header(): void {
		$this->assertSame( wp_get_theme()->get( 'Version' ), EXECUTIVE_SIGNAL_THEME_VERSION );
	}

	/**
	 * Editor stylesheets should always be available.
	 */
	public function test_editor_stylesheets_are_available(): void {
		$stylesheets = executive_signal_get_editor_stylesheets();

		$this->assertNotEmpty( $stylesheets );
		$this->assertContainsOnly( 'string', $stylesheets );
	}

	/**
	 * Theme JSON should own reusable editor element and block defaults.
	 */
	public function test_theme_json_contains_editorial_element_and_block_styles(): void {
		$theme_json = wp_json_file_decode(
			EXECUTIVE_SIGNAL_THEME_DIR . '/theme.json',
			array( 'associative' => true )
		);

		$this->assertIsArray( $theme_json );
		$this->assertArrayHasKey( 'link', $theme_json['styles']['elements'] );
		$this->assertArrayHasKey( 'h2', $theme_json['styles']['elements'] );
		$this->assertArrayHasKey( 'caption', $theme_json['styles']['elements'] );
		$this->assertArrayHasKey( 'core/quote', $theme_json['styles']['blocks'] );
		$this->assertArrayHasKey( 'core/button', $theme_json['styles']['blocks'] );
		$this->assertArrayHasKey( 'core/separator', $theme_json['styles']['blocks'] );
	}
}
