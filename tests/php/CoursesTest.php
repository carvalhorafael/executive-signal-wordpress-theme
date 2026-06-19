<?php
/**
 * Course content type presentation tests.
 *
 * @package ExecutiveSignal
 */

use PHPUnit\Framework\TestCase;

/**
 * Verifies the theme's Online Courses plugin integration.
 *
 * @covers ::executive_signal_courses_plugin_is_available
 * @covers ::executive_signal_get_course_checkout_url
 * @covers ::executive_signal_get_courses_page
 * @covers ::executive_signal_get_courses_page_url
 * @covers ::executive_signal_get_primary_course_category
 * @covers ::executive_signal_route_courses_archive_to_page
 */
final class CoursesTest extends TestCase {
	/**
	 * Course post type should come from the companion plugin contract.
	 */
	public function test_course_post_type_is_registered(): void {
		$this->assertTrue( function_exists( 'online_courses' ) );
		$this->assertTrue( executive_signal_courses_plugin_is_available() );

		$post_type = get_post_type_object( EXECUTIVE_SIGNAL_COURSE_POST_TYPE );

		$this->assertNotNull( $post_type );
		$this->assertTrue( $post_type->public );
		$this->assertSame( 'cursos', $post_type->has_archive );
		$this->assertTrue( $post_type->show_in_rest );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_COURSE_POST_TYPE, 'title' ) );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_COURSE_POST_TYPE, 'editor' ) );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_COURSE_POST_TYPE, 'thumbnail' ) );
		$this->assertTrue( post_type_supports( EXECUTIVE_SIGNAL_COURSE_POST_TYPE, 'excerpt' ) );
	}

	/**
	 * Course categories should be isolated from blog categories.
	 */
	public function test_course_taxonomy_is_registered(): void {
		$taxonomy = get_taxonomy( EXECUTIVE_SIGNAL_COURSE_TAXONOMY );

		$this->assertNotFalse( $taxonomy );
		$this->assertTrue( $taxonomy->hierarchical );
		$this->assertTrue( $taxonomy->show_in_rest );
		$this->assertContains( EXECUTIVE_SIGNAL_COURSE_POST_TYPE, $taxonomy->object_type );
		$this->assertSame( 'cursos/categoria', $taxonomy->rewrite['slug'] );
	}

	/**
	 * Checkout URL helper should read the companion plugin metadata.
	 */
	public function test_course_checkout_url_uses_plugin_helper(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Signal course',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );

		update_post_meta( $post_id, EXECUTIVE_SIGNAL_COURSE_CHECKOUT_URL, 'https://checkout.example.com/course' );

		$this->assertSame( 'https://checkout.example.com/course', executive_signal_get_course_checkout_url( $post_id ) );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Landing page helper should prefer an editable WordPress page.
	 */
	public function test_courses_landing_page_helpers_prefer_page(): void {
		$existing_page = executive_signal_get_courses_page();

		if ( $existing_page ) {
			wp_delete_post( $existing_page->ID, true );
		}

		$post_id = wp_insert_post(
			array(
				'post_name'   => EXECUTIVE_SIGNAL_COURSES_PAGE_PATH,
				'post_status' => 'publish',
				'post_title'  => 'Cursos',
				'post_type'   => 'page',
			),
			true
		);

		$this->assertIsInt( $post_id );

		$page = executive_signal_get_courses_page();

		$this->assertInstanceOf( WP_Post::class, $page );
		$this->assertSame( $post_id, $page->ID );
		$this->assertSame( get_permalink( $post_id ), executive_signal_get_courses_page_url() );

		wp_delete_post( $post_id, true );
	}

	/**
	 * Course archive route should resolve to the editable page when it exists.
	 */
	public function test_courses_archive_route_prefers_page_template(): void {
		$existing_page = executive_signal_get_courses_page();

		if ( $existing_page ) {
			wp_delete_post( $existing_page->ID, true );
		}

		$this->assertSame(
			array(
				'post_type' => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
			),
			executive_signal_route_courses_archive_to_page(
				array(
					'post_type' => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
				)
			)
		);

		$post_id = wp_insert_post(
			array(
				'post_name'   => EXECUTIVE_SIGNAL_COURSES_PAGE_PATH,
				'post_status' => 'publish',
				'post_title'  => 'Cursos',
				'post_type'   => 'page',
			),
			true
		);

		$this->assertIsInt( $post_id );

		$this->assertSame(
			array(
				'pagename' => EXECUTIVE_SIGNAL_COURSES_PAGE_PATH,
			),
			executive_signal_route_courses_archive_to_page(
				array(
					'post_type' => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
				)
			)
		);
		$this->assertSame(
			array(
				'name'      => 'signal-course',
				'post_type' => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
			),
			executive_signal_route_courses_archive_to_page(
				array(
					'name'      => 'signal-course',
					'post_type' => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
				)
			)
		);

		wp_delete_post( $post_id, true );
	}

	/**
	 * Category helper should return the dedicated course taxonomy.
	 */
	public function test_primary_course_category_is_returned(): void {
		$existing_term = get_term_by( 'slug', 'strategy-course-test', EXECUTIVE_SIGNAL_COURSE_TAXONOMY );

		if ( $existing_term instanceof WP_Term ) {
			wp_delete_term( $existing_term->term_id, EXECUTIVE_SIGNAL_COURSE_TAXONOMY );
		}

		$term = wp_insert_term(
			'Strategy',
			EXECUTIVE_SIGNAL_COURSE_TAXONOMY,
			array(
				'slug' => 'strategy-course-test',
			)
		);

		$this->assertIsArray( $term );

		$post_id = wp_insert_post(
			array(
				'post_title'  => 'Operating course',
				'post_status' => 'publish',
				'post_type'   => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
			),
			true
		);

		$this->assertIsInt( $post_id );
		wp_set_object_terms( $post_id, (int) $term['term_id'], EXECUTIVE_SIGNAL_COURSE_TAXONOMY );

		$category = executive_signal_get_primary_course_category( $post_id );

		$this->assertInstanceOf( WP_Term::class, $category );
		$this->assertSame( 'Strategy', $category->name );

		wp_delete_post( $post_id, true );
		wp_delete_term( (int) $term['term_id'], EXECUTIVE_SIGNAL_COURSE_TAXONOMY );
	}
}
