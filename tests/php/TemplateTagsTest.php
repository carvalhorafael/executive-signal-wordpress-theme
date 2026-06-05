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
 * @covers ::executive_signal_get_unique_article_heading_id
 * @covers ::executive_signal_prepare_article_content
 * @covers ::executive_signal_render_article_table_of_contents
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

	/**
	 * Article content preparation should add heading anchors and collect h2/h3 items.
	 */
	public function test_article_content_table_of_contents_uses_h2_and_h3(): void {
		$prepared = executive_signal_prepare_article_content(
			'<p>Intro</p><h2>Primeira seção</h2><h3 id="custom-anchor">Detalhe interno</h3><h4>Ignorado</h4><h2>Primeira seção</h2>'
		);

		$this->assertStringContainsString( '<h2 id="primeira-secao">Primeira seção</h2>', $prepared['content'] );
		$this->assertStringContainsString( '<h3 id="custom-anchor">Detalhe interno</h3>', $prepared['content'] );
		$this->assertStringContainsString( '<h2 id="primeira-secao-2">Primeira seção</h2>', $prepared['content'] );

		$this->assertSame(
			array(
				array(
					'level' => 2,
					'href'  => '#primeira-secao',
					'label' => 'Primeira seção',
				),
				array(
					'level' => 3,
					'href'  => '#custom-anchor',
					'label' => 'Detalhe interno',
				),
				array(
					'level' => 2,
					'href'  => '#primeira-secao-2',
					'label' => 'Primeira seção',
				),
			),
			$prepared['table_of_contents']
		);
	}

	/**
	 * Table of contents output should follow the design-system class contract.
	 */
	public function test_article_table_of_contents_markup_uses_design_system_contract(): void {
		ob_start();
		executive_signal_render_article_table_of_contents(
			array(
				array(
					'level' => 2,
					'href'  => '#overview',
					'label' => 'Overview',
				),
			)
		);
		$output = ob_get_clean();

		$this->assertStringContainsString( 'class="es-table-of-contents"', $output );
		$this->assertStringContainsString( 'data-sticky="true"', $output );
		$this->assertStringContainsString( 'class="es-table-of-contents__item" data-level="2"', $output );
		$this->assertStringContainsString( 'href="#overview"', $output );
	}
}
