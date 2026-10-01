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
 * @covers ::executive_signal_get_page_portrait
 * @covers ::executive_signal_get_unique_article_heading_id
 * @covers ::executive_signal_prepare_article_content
 * @covers ::executive_signal_render_article_table_of_contents
 * @covers ::executive_signal_render_badge
 * @covers ::executive_signal_render_post_meta
 */
final class TemplateTagsTest extends TestCase {
	/**
	 * Pages without a featured image should keep the bundled portrait.
	 */
	public function test_page_portrait_falls_back_to_bundled_image(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Page without portrait',
				'post_status' => 'draft',
				'post_type'   => 'page',
			),
			true
		);

		$this->assertIsInt( $post_id );

		$portrait = executive_signal_get_page_portrait( $post_id );

		$this->assertSame( get_theme_file_uri( 'assets/images/rafael-carvalho-coo-as-a-service.jpeg' ), $portrait['url'] );
		$this->assertSame( 'Retrato de Rafael Carvalho.', $portrait['alt'] );
		$this->assertSame( 550, $portrait['width'] );
		$this->assertSame( 550, $portrait['height'] );

		wp_delete_post( $post_id, true );
	}

	/**
	 * A configured featured image should replace the bundled portrait.
	 */
	public function test_page_portrait_uses_featured_image(): void {
		$post_id       = wp_insert_post(
			array(
				'post_title'  => 'Page with portrait',
				'post_status' => 'draft',
				'post_type'   => 'page',
			),
			true
		);
		$attachment_id = wp_insert_attachment(
			array(
				'guid'           => 'https://example.test/portrait.jpg',
				'post_mime_type' => 'image/jpeg',
				'post_status'    => 'inherit',
				'post_title'     => 'Page portrait',
			)
		);

		$this->assertIsInt( $post_id );
		$this->assertIsInt( $attachment_id );

		update_post_meta( $attachment_id, '_wp_attachment_image_alt', 'Retrato configurado no WordPress' );
		update_post_meta( $post_id, '_thumbnail_id', $attachment_id );

		$image_downsize = static function ( $downsize, $requested_attachment_id, $size ) use ( $attachment_id ) {
			if ( $attachment_id === (int) $requested_attachment_id && 'full' === $size ) {
				return array( 'https://example.test/portrait.jpg', 1200, 1500, false );
			}

			return $downsize;
		};
		add_filter( 'image_downsize', $image_downsize, 10, 3 );

		$portrait = executive_signal_get_page_portrait( $post_id );

		remove_filter( 'image_downsize', $image_downsize, 10 );

		$this->assertSame( 'https://example.test/portrait.jpg', $portrait['url'] );
		$this->assertSame( 'Retrato configurado no WordPress', $portrait['alt'] );
		$this->assertSame( 1200, $portrait['width'] );
		$this->assertSame( 1500, $portrait['height'] );

		wp_delete_attachment( $attachment_id, true );
		wp_delete_post( $post_id, true );
	}

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
		$this->assertStringContainsString( 'data-density="compact"', $output );
		$this->assertStringContainsString( 'data-scrollable="true"', $output );
		$this->assertStringContainsString( 'class="es-table-of-contents__item" data-level="2"', $output );
		$this->assertStringContainsString( 'href="#overview"', $output );
	}
}
