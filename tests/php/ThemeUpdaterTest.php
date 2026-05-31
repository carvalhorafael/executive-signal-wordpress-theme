<?php
/**
 * Theme updater tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies GitHub release update conversion.
 *
 * @covers ::executive_signal_find_github_release_asset_url
 * @covers ::executive_signal_normalize_github_release_version
 * @covers ::executive_signal_theme_update_from_release
 */
final class ThemeUpdaterTest extends TestCase {
	/**
	 * A newer GitHub release with the packaged ZIP should produce update data.
	 */
	public function test_github_release_produces_theme_update(): void {
		$new_version = '99.0.0';
		$release     = array(
			'tag_name' => 'v' . $new_version,
			'html_url' => 'https://github.com/carvalhorafael/executive-signal-wordpress-theme/releases/tag/v' . $new_version,
			'assets'   => array(
				array(
					'name'                 => EXECUTIVE_SIGNAL_THEME_RELEASE_ASSET,
					'browser_download_url' => 'https://github.com/carvalhorafael/executive-signal-wordpress-theme/releases/download/v' . $new_version . '/executive-signal-wordpress-theme.zip',
				),
			),
		);

		$update = executive_signal_theme_update_from_release( $release, EXECUTIVE_SIGNAL_THEME_VERSION );

		$this->assertIsArray( $update );
		$this->assertSame( EXECUTIVE_SIGNAL_THEME_SLUG, $update['theme'] );
		$this->assertSame( $new_version, $update['version'] );
		$this->assertSame( $new_version, $update['new_version'] );
		$this->assertSame( $release['assets'][0]['browser_download_url'], $update['package'] );
		$this->assertSame( '6.5', $update['requires'] );
		$this->assertSame( '6.5', $update['tested'] );
		$this->assertSame( '8.2', $update['requires_php'] );
	}

	/**
	 * Current releases should not produce update data.
	 */
	public function test_current_release_does_not_produce_theme_update(): void {
		$release = array(
			'tag_name' => 'v' . EXECUTIVE_SIGNAL_THEME_VERSION,
			'assets'   => array(
				array(
					'name'                 => EXECUTIVE_SIGNAL_THEME_RELEASE_ASSET,
					'browser_download_url' => 'https://example.com/theme.zip',
				),
			),
		);

		$this->assertFalse( executive_signal_theme_update_from_release( $release, EXECUTIVE_SIGNAL_THEME_VERSION ) );
	}

	/**
	 * Releases without the packaged theme ZIP should not produce update data.
	 */
	public function test_release_without_expected_asset_does_not_produce_theme_update(): void {
		$release = array(
			'tag_name' => 'v99.0.0',
			'assets'   => array(
				array(
					'name'                 => 'source.zip',
					'browser_download_url' => 'https://example.com/source.zip',
				),
			),
		);

		$this->assertFalse( executive_signal_theme_update_from_release( $release, EXECUTIVE_SIGNAL_THEME_VERSION ) );
	}

	/**
	 * The Update URI filter should ignore unrelated themes.
	 */
	public function test_update_filter_ignores_unrelated_themes(): void {
		$update = array(
			'theme' => 'another-theme',
		);

		$this->assertSame(
			$update,
			executive_signal_filter_github_theme_update(
				$update,
				array(
					'UpdateURI' => EXECUTIVE_SIGNAL_THEME_UPDATE_URI,
				),
				'another-theme',
				array()
			)
		);
	}
}
