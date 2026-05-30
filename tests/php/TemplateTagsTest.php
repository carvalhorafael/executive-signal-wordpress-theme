<?php
/**
 * Template helper tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies shared template helpers.
 *
 * @covers ::executive_signal_get_listing_excerpt
 * @covers ::executive_signal_render_badge
 * @covers ::executive_signal_render_post_meta
 */
final class TemplateTagsTest extends TestCase {
	/**
	 * Badge labels should be escaped before rendering.
	 */
	public function test_badge_output_is_escaped(): void {
		ob_start();
		executive_signal_render_badge( '<script>alert("x")</script>', 'signal danger' );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'es-badge--signal-danger', $output );
		$this->assertStringContainsString( '&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	/**
	 * Listing excerpts should stay compact.
	 */
	public function test_listing_excerpt_is_trimmed(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'   => 'Signal note',
				'post_status'  => 'publish',
				'post_content' => 'Conteudo do artigo.',
				'post_excerpt' => 'This editorial summary contains enough words to prove that listing excerpts are trimmed for compact cards.',
			),
			true
		);

		$this->assertIsInt( $post_id );

		$excerpt = executive_signal_get_listing_excerpt( $post_id, 8 );

		$this->assertSame( 'This editorial summary contains enough words to prove [...]', $excerpt );

		wp_delete_post( $post_id, true );
	}
}
