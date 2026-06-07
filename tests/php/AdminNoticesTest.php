<?php
/**
 * Admin notice integration tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies companion plugin recommendations.
 *
 * @covers ::executive_signal_get_missing_recommended_plugins
 * @covers ::executive_signal_get_recommended_plugins
 * @covers ::executive_signal_is_recommended_plugin_active
 */
final class AdminNoticesTest extends TestCase {
	/**
	 * Recommended plugins should document the companion plugin contract.
	 */
	public function test_recommended_plugins_include_materials_and_brevo(): void {
		$plugins = executive_signal_get_recommended_plugins();
		$files   = wp_list_pluck( $plugins, 'file' );

		$this->assertContains( 'free-materials/free-materials.php', $files );
		$this->assertContains( 'brevo-leads-capture/brevo-leads-capture.php', $files );
	}

	/**
	 * Active companion plugins should not be reported as missing.
	 */
	public function test_active_free_materials_plugin_is_not_reported_as_missing(): void {
		$missing_plugins = executive_signal_get_missing_recommended_plugins();
		$missing_files   = wp_list_pluck( $missing_plugins, 'file' );

		$this->assertNotContains( 'free-materials/free-materials.php', $missing_files );
	}
}
