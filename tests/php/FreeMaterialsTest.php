<?php
/**
 * Free material content type tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies the free material content contract.
 *
 * @covers ::executive_signal_get_free_material_cta
 * @covers ::executive_signal_get_free_materials_page
 * @covers ::executive_signal_get_free_materials_page_url
 * @covers ::executive_signal_get_primary_free_material_category
 * @covers ::executive_signal_register_free_material_content_type
 * @covers ::executive_signal_render_free_material_meta_box
 * @covers ::executive_signal_render_free_material_terms
 */
final class FreeMaterialsTest extends TestCase {
	/**
	 * Free material post type should be public and editor friendly.
	 */
	public function test_free_material_post_type_is_registered(): void {
		$post_type = get_post_type_object( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE );

		$this->assertNotNull( $post_type );
		$this->assertTrue( $post_type->public );
		$this->assertFalse( $post_type->has_archive );
		$this->assertTrue( $post_type->show_in_rest );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE, 'title' ) );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE, 'editor' ) );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE, 'thumbnail' ) );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE, 'excerpt' ) );
	}

	/**
	 * Free material categories should be isolated from blog categories.
	 */
	public function test_free_material_taxonomy_is_registered(): void {
		$taxonomy = get_taxonomy( EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );

		$this->assertNotFalse( $taxonomy );
		$this->assertTrue( $taxonomy->hierarchical );
		$this->assertTrue( $taxonomy->show_in_rest );
		$this->assertContains( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE, $taxonomy->object_type );
		$this->assertSame( 'materiais-gratuitos/categoria', $taxonomy->rewrite['slug'] );
	}

	/**
	 * Brevo capture settings should be registered as explicit post metadata.
	 */
	public function test_free_material_brevo_metadata_is_registered(): void {
		$registered_meta = get_registered_meta_keys( 'post', EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE );

		$this->assertArrayHasKey( EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID, $registered_meta );
		$this->assertArrayHasKey( EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL, $registered_meta );
		$this->assertSame( 'sanitize_text_field', $registered_meta[ EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID ]['sanitize_callback'] );
		$this->assertSame( 'esc_url_raw', $registered_meta[ EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL ]['sanitize_callback'] );
		$this->assertTrue( $registered_meta[ EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID ]['show_in_rest'] );
		$this->assertTrue( $registered_meta[ EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL ]['show_in_rest'] );
	}

	/**
	 * Capture meta box should expose the Brevo fields for editors.
	 */
	public function test_free_material_meta_box_renders_brevo_fields(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Brevo material',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );

		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID, '42' );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL, 'https://example.com/delivery' );

		ob_start();
		executive_signal_render_free_material_meta_box( get_post( $post_id ) );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'name="executive_signal_free_material_brevo_list_id"', $output );
		$this->assertStringContainsString( 'name="executive_signal_free_material_brevo_delivery_url"', $output );
		$this->assertStringContainsString( 'value="42"', $output );
		$this->assertStringContainsString( 'value="https://example.com/delivery"', $output );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Capture CTA should use explicit metadata with a safe fallback.
	 */
	public function test_free_material_cta_uses_metadata_and_fallback(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Decision checklist',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );

		$fallback = executive_signal_get_free_material_cta( $post_id );

		$this->assertSame( 'Download free material', $fallback['label'] );
		$this->assertSame( '#capture', $fallback['url'] );

		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL, 'https://example.com/capture' );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL, 'Receive checklist' );

		$cta = executive_signal_get_free_material_cta( $post_id );

		$this->assertSame( 'Receive checklist', $cta['label'] );
		$this->assertSame( 'https://example.com/capture', $cta['url'] );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Landing page helper should prefer an editable WordPress page.
	 */
	public function test_free_material_landing_page_helpers_prefer_page(): void {
		$post_id = wp_insert_post(
			array(
				'post_name'   => EXECUTIVE_SIGNAL_FREE_MATERIALS_PAGE_PATH,
				'post_status' => 'publish',
				'post_title'  => 'Materiais Gratuitos',
				'post_type'   => 'page',
			),
			true
		);

		$this->assertIsInt( $post_id );

		$page = executive_signal_get_free_materials_page();

		$this->assertInstanceOf( WP_Post::class, $page );
		$this->assertSame( $post_id, $page->ID );
		$this->assertSame( get_permalink( $post_id ), executive_signal_get_free_materials_page_url() );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Category helper should return the dedicated free material taxonomy.
	 */
	public function test_primary_free_material_category_is_returned(): void {
		$term = wp_insert_term(
			'Leadership',
			EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY,
			array(
				'slug' => 'leadership-material-test',
			)
		);

		$this->assertIsArray( $term );

		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Operating guide',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );
		wp_set_object_terms( $post_id, (int) $term['term_id'], EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );

		$category = executive_signal_get_primary_free_material_category( $post_id );

		$this->assertInstanceOf( WP_Term::class, $category );
		$this->assertSame( 'Leadership', $category->name );

		wp_delete_post( $post_id, true );
		wp_delete_term( (int) $term['term_id'], EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );
	}

	/**
	 * Term renderer should output category links for the single template.
	 */
	public function test_free_material_terms_render_category_links(): void {
		$term = wp_insert_term(
			'Strategy',
			EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY,
			array(
				'slug' => 'strategy-material-test',
			)
		);

		$this->assertIsArray( $term );

		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Strategic note',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );
		wp_set_object_terms( $post_id, (int) $term['term_id'], EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );

		ob_start();
		executive_signal_render_free_material_terms( $post_id );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'free-material-terms', $output );
		$this->assertStringContainsString( 'Category', $output );
		$this->assertStringContainsString( 'Strategy', $output );

		wp_delete_post( $post_id, true );
		wp_delete_term( (int) $term['term_id'], EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );
	}
}
