<?php
/**
 * Course content type presentation helpers.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXECUTIVE_SIGNAL_COURSE_POST_TYPE', function_exists( 'online_courses_post_type' ) ? online_courses_post_type() : 'course' );
define( 'EXECUTIVE_SIGNAL_COURSE_TAXONOMY', function_exists( 'online_courses_taxonomy' ) ? online_courses_taxonomy() : 'course_category' );
define( 'EXECUTIVE_SIGNAL_COURSE_CHECKOUT_URL', function_exists( 'online_courses_checkout_url_meta_key' ) ? online_courses_checkout_url_meta_key() : '_online_courses_checkout_url' );
define( 'EXECUTIVE_SIGNAL_COURSES_PAGE_PATH', 'cursos' );

/**
 * Check whether the Online Courses content plugin is available.
 *
 * @return bool
 */
function executive_signal_courses_plugin_is_available() {
	return function_exists( 'online_courses' ) || post_type_exists( EXECUTIVE_SIGNAL_COURSE_POST_TYPE );
}

/**
 * Prefer the editable Courses page over the course archive route.
 *
 * The companion plugin owns the persistent course domain and may expose a
 * post type archive at /cursos/. The theme keeps the listing page explicit by
 * routing that archive request to the page template when the page exists.
 *
 * @param array<string, mixed> $query_vars Public query variables.
 * @return array<string, mixed>
 */
function executive_signal_route_courses_archive_to_page( array $query_vars ) {
	if ( is_admin() || ! executive_signal_get_courses_page() ) {
		return $query_vars;
	}

	$post_type = isset( $query_vars['post_type'] ) ? $query_vars['post_type'] : '';

	if (
		EXECUTIVE_SIGNAL_COURSE_POST_TYPE !== $post_type
		|| ! empty( $query_vars['name'] )
		|| ! empty( $query_vars[ EXECUTIVE_SIGNAL_COURSE_POST_TYPE ] )
	) {
		return $query_vars;
	}

	unset( $query_vars['post_type'] );
	$query_vars['pagename'] = EXECUTIVE_SIGNAL_COURSES_PAGE_PATH;

	return $query_vars;
}
add_filter( 'request', 'executive_signal_route_courses_archive_to_page' );

/**
 * Get the landing page used to edit the courses index copy.
 *
 * @return WP_Post|null
 */
function executive_signal_get_courses_page() {
	$page = get_page_by_path( EXECUTIVE_SIGNAL_COURSES_PAGE_PATH );

	return $page instanceof WP_Post ? $page : null;
}

/**
 * Get the canonical URL for the courses landing page.
 *
 * @return string
 */
function executive_signal_get_courses_page_url() {
	$page = executive_signal_get_courses_page();

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/' . EXECUTIVE_SIGNAL_COURSES_PAGE_PATH . '/' );
}

/**
 * Get the primary course category.
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|null
 */
function executive_signal_get_primary_course_category( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$terms   = $post_id ? get_the_terms( $post_id, EXECUTIVE_SIGNAL_COURSE_TAXONOMY ) : false;

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	return $terms[0];
}

/**
 * Get the sanitized checkout URL for a course.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function executive_signal_get_course_checkout_url( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	if ( function_exists( 'online_courses_get_checkout_url' ) ) {
		return online_courses_get_checkout_url( $post_id );
	}

	return esc_url_raw( get_post_meta( $post_id, EXECUTIVE_SIGNAL_COURSE_CHECKOUT_URL, true ) );
}
