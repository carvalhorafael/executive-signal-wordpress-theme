<?php
/**
 * Pattern registration integration tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies Executive Signal pattern registration.
 *
 * @covers ::executive_signal_get_pattern_content
 * @covers ::executive_signal_register_block_patterns
 * @covers ::executive_signal_register_pattern_categories
 */
final class PatternRegistrationTest extends TestCase {
	/**
	 * The theme should expose its pattern category to the editor.
	 */
	public function test_pattern_category_is_registered(): void {
		$registry = WP_Block_Pattern_Categories_Registry::get_instance();

		$this->assertTrue( $registry->is_registered( 'executive-signal-wordpress-theme' ) );
	}

	/**
	 * Expected patterns should be registered.
	 */
	public function test_expected_patterns_are_registered(): void {
		$registry = WP_Block_Patterns_Registry::get_instance();
		$patterns = array(
			'executive-signal/hero',
			'executive-signal/signal-grid',
			'executive-signal/report-preview',
			'executive-signal/cta',
			'executive-signal/landing-page',
		);

		foreach ( $patterns as $pattern ) {
			$this->assertTrue( $registry->is_registered( $pattern ), "{$pattern} should be registered." );
		}
	}

	/**
	 * The landing-page pattern should compose the expected sections.
	 */
	public function test_landing_page_pattern_composes_sections(): void {
		$content = executive_signal_get_pattern_content( EXECUTIVE_SIGNAL_THEME_DIR . '/patterns/landing-page.php' );

		$this->assertStringContainsString( 'es-hero', $content );
		$this->assertStringContainsString( 'es-signal-grid', $content );
		$this->assertStringContainsString( 'es-report-preview', $content );
		$this->assertStringContainsString( 'es-cta', $content );
	}
}
