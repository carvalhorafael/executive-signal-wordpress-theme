<?php
/**
 * Free material content type tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies the theme's Free Materials plugin integration.
 *
 * @covers ::executive_signal_get_free_material_cta
 * @covers ::executive_signal_get_free_material_details
 * @covers ::executive_signal_get_featured_free_material
 * @covers ::executive_signal_get_free_materials_description_default
 * @covers ::executive_signal_get_free_materials_eyebrow_default
 * @covers ::executive_signal_get_free_materials_page
 * @covers ::executive_signal_get_free_materials_page_url
 * @covers ::executive_signal_get_free_materials_setting
 * @covers ::executive_signal_get_free_materials_title_default
 * @covers ::executive_signal_get_primary_free_material_category
 * @covers ::executive_signal_render_free_material_terms
 */
final class FreeMaterialsTest extends TestCase {
	/**
	 * Featured material helper should select the first marked post in display order.
	 */
	public function test_featured_free_material_uses_the_first_marked_post(): void {
		$first_post_id  = wp_insert_post(
			array(
				'post_title'  => 'First highlighted material',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);
		$second_post_id = wp_insert_post(
			array(
				'post_title'  => 'Second highlighted material',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $first_post_id );
		$this->assertIsInt( $second_post_id );

		update_post_meta( $first_post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_FEATURED, true );
		update_post_meta( $second_post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_FEATURED, true );

		$featured = executive_signal_get_featured_free_material(
			array( get_post( $first_post_id ), get_post( $second_post_id ) )
		);

		$this->assertInstanceOf( WP_Post::class, $featured );
		$this->assertSame( $first_post_id, $featured->ID );

		wp_delete_post( $first_post_id, true );
		wp_delete_post( $second_post_id, true );
	}

	/**
	 * Visitor-facing details should use the plugin-owned metadata contract.
	 */
	public function test_free_material_details_use_plugin_metadata(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Material details fixture',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );

		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_FORMAT, 'pdf' );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_PAGES, 12 );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_FILE_SIZE, '2,4 MB' );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_LEVEL, 'Executive leaders' );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_HIGHLIGHTS, array( 'Scorecard', 'Checklist' ) );
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_DOWNLOADS, 1847 );

		$details = executive_signal_get_free_material_details( $post_id );

		$this->assertSame( 'PDF', $details['format'] );
		$this->assertSame( 12, $details['pages'] );
		$this->assertSame( '2,4 MB', $details['file_size'] );
		$this->assertSame( 'Executive leaders', $details['level'] );
		$this->assertSame( array( 'Scorecard', 'Checklist' ), $details['highlights'] );
		$this->assertSame( 1847, $details['downloads'] );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Free materials archive copy should use Customizer values with defaults.
	 */
	public function test_free_materials_archive_copy_uses_theme_mods(): void {
		remove_theme_mod( 'executive_signal_free_materials_eyebrow' );

		$this->assertSame(
			executive_signal_get_free_materials_eyebrow_default(),
			executive_signal_get_free_materials_setting( 'eyebrow', executive_signal_get_free_materials_eyebrow_default() )
		);

		set_theme_mod( 'executive_signal_free_materials_eyebrow', 'Biblioteca executiva' );

		$this->assertSame(
			'Biblioteca executiva',
			executive_signal_get_free_materials_setting( 'eyebrow', executive_signal_get_free_materials_eyebrow_default() )
		);

		remove_theme_mod( 'executive_signal_free_materials_eyebrow' );
	}

	/**
	 * Free material post type should be public and editor friendly.
	 */
	public function test_free_material_post_type_is_registered(): void {
		$this->assertTrue( function_exists( 'free_materials' ) );
		$this->assertTrue( executive_signal_free_materials_plugin_is_available() );

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
	 * Capture CTA should use explicit button text metadata with a safe fallback.
	 */
	public function test_free_material_cta_uses_button_text_metadata_and_fallback(): void {
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

		$this->assertSame( __( 'Download free material', 'executive-signal-wordpress-theme' ), $fallback['label'] );

		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL, 'Receive checklist' );

		$cta = executive_signal_get_free_material_cta( $post_id );

		$this->assertSame( 'Receive checklist', $cta['label'] );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Capture UTM helpers should only forward supported fields.
	 */
	public function test_free_material_capture_utm_helpers_sanitize_supported_fields(): void {
		$_GET['utm_source'] = 'Newsletter <strong>VIP</strong>';
		$_GET['unexpected'] = 'Do not forward';

		$this->assertSame(
			array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ),
			executive_signal_get_free_material_capture_utm_fields()
		);
		$this->assertSame( 'Newsletter VIP', executive_signal_get_free_material_capture_utm_value( 'utm_source' ) );
		$this->assertSame( '', executive_signal_get_free_material_capture_utm_value( 'unexpected' ) );

		unset( $_GET['utm_source'], $_GET['unexpected'] );
	}

	/**
	 * Landing page helper should prefer an editable WordPress page.
	 */
	public function test_free_material_landing_page_helpers_prefer_page(): void {
		$existing_page = executive_signal_get_free_materials_page();

		if ( $existing_page ) {
			wp_delete_post( $existing_page->ID, true );
		}

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
		$existing_term = get_term_by( 'slug', 'leadership-material-test', EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );

		if ( $existing_term instanceof WP_Term ) {
			wp_delete_term( $existing_term->term_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );
		}

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
		$existing_term = get_term_by( 'slug', 'strategy-material-test', EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );

		if ( $existing_term instanceof WP_Term ) {
			wp_delete_term( $existing_term->term_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );
		}

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
		$this->assertStringContainsString( __( 'Category', 'executive-signal-wordpress-theme' ), $output );
		$this->assertStringContainsString( 'Strategy', $output );

		wp_delete_post( $post_id, true );
		wp_delete_term( (int) $term['term_id'], EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY );
	}
}
