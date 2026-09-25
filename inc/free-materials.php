<?php
/**
 * Free material content type.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE', function_exists( 'free_materials_post_type' ) ? free_materials_post_type() : 'material_gratuito' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY', function_exists( 'free_materials_taxonomy' ) ? free_materials_taxonomy() : 'material_categoria' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL', function_exists( 'free_materials_cta_label_meta_key' ) ? free_materials_cta_label_meta_key() : '_executive_signal_material_capture_label' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID', function_exists( 'free_materials_brevo_list_id_meta_key' ) ? free_materials_brevo_list_id_meta_key() : '_brevo_leads_capture_list_id' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL', function_exists( 'free_materials_brevo_delivery_url_meta_key' ) ? free_materials_brevo_delivery_url_meta_key() : '_brevo_leads_capture_delivery_url' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_FORMAT', function_exists( 'free_materials_format_meta_key' ) ? free_materials_format_meta_key() : '_free_materials_format' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_PAGES', function_exists( 'free_materials_pages_meta_key' ) ? free_materials_pages_meta_key() : '_free_materials_pages' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_FILE_SIZE', function_exists( 'free_materials_file_size_meta_key' ) ? free_materials_file_size_meta_key() : '_free_materials_file_size' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_LEVEL', function_exists( 'free_materials_level_meta_key' ) ? free_materials_level_meta_key() : '_free_materials_level' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_HIGHLIGHTS', function_exists( 'free_materials_highlights_meta_key' ) ? free_materials_highlights_meta_key() : '_free_materials_highlights' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIAL_DOWNLOADS', function_exists( 'free_materials_downloads_meta_key' ) ? free_materials_downloads_meta_key() : '_free_materials_downloads' );
define( 'EXECUTIVE_SIGNAL_FREE_MATERIALS_PAGE_PATH', 'materiais-gratuitos' );

/**
 * Check whether the Free Materials content plugin is available.
 *
 * @return bool
 */
function executive_signal_free_materials_plugin_is_available() {
	return function_exists( 'free_materials' ) || post_type_exists( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE );
}

/**
 * Get the default free materials eyebrow.
 *
 * @return string
 */
function executive_signal_get_free_materials_eyebrow_default() {
	return __( 'Free materials', 'executive-signal-wordpress-theme' );
}

/**
 * Get the default free materials title.
 *
 * @return string
 */
function executive_signal_get_free_materials_title_default() {
	return __( 'Free materials', 'executive-signal-wordpress-theme' );
}

/**
 * Get the default free materials description.
 *
 * @return string
 */
function executive_signal_get_free_materials_description_default() {
	return __( 'Guides, checklists and working notes for leaders who want clearer operating signals.', 'executive-signal-wordpress-theme' );
}

/**
 * Get a Customizer-backed free materials setting.
 *
 * @param string $setting Setting name suffix.
 * @param string $default_value Default value.
 * @return string
 */
function executive_signal_get_free_materials_setting( $setting, $default_value ) {
	return get_theme_mod( 'executive_signal_free_materials_' . $setting, $default_value );
}

/**
 * Get the landing page used to edit the free materials index copy.
 *
 * @return WP_Post|null
 */
function executive_signal_get_free_materials_page() {
	$page = get_page_by_path( EXECUTIVE_SIGNAL_FREE_MATERIALS_PAGE_PATH );

	return $page instanceof WP_Post ? $page : null;
}

/**
 * Get the canonical URL for the free materials landing page.
 *
 * @return string
 */
function executive_signal_get_free_materials_page_url() {
	$page = executive_signal_get_free_materials_page();

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/' . EXECUTIVE_SIGNAL_FREE_MATERIALS_PAGE_PATH . '/' );
}

/**
 * Get capture CTA data for a free material.
 *
 * @param int|null $post_id Post ID.
 * @return array{label:string}
 */
function executive_signal_get_free_material_cta( $post_id = null ) {
	$post_id   = $post_id ? (int) $post_id : get_the_ID();
	$cta_label = $post_id ? get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL, true ) : '';

	return array(
		'label' => $cta_label ? $cta_label : __( 'Download free material', 'executive-signal-wordpress-theme' ),
	);
}

/**
 * Get the visitor-facing details supplied by the Free Materials plugin.
 *
 * @param int|null $post_id Post ID.
 * @return array{format:string,pages:int,file_size:string,level:string,highlights:array<int,string>,downloads:int}
 */
function executive_signal_get_free_material_details( $post_id = null ) {
	$post_id    = $post_id ? (int) $post_id : get_the_ID();
	$format     = $post_id ? (string) get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_FORMAT, true ) : '';
	$highlights = $post_id ? get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_HIGHLIGHTS, true ) : array();

	return array(
		'format'     => $format && function_exists( 'free_materials_format_label' ) ? free_materials_format_label( $format ) : $format,
		'pages'      => $post_id ? (int) get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_PAGES, true ) : 0,
		'file_size'  => $post_id ? (string) get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_FILE_SIZE, true ) : '',
		'level'      => $post_id ? (string) get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_LEVEL, true ) : '',
		'highlights' => is_array( $highlights ) ? array_values( array_filter( array_map( 'sanitize_text_field', $highlights ) ) ) : array(),
		'downloads'  => $post_id ? (int) get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_DOWNLOADS, true ) : 0,
	);
}

/**
 * Get the UTM fields forwarded by the free material capture form.
 *
 * @return array<int, string>
 */
function executive_signal_get_free_material_capture_utm_fields() {
	return array(
		'utm_source',
		'utm_medium',
		'utm_campaign',
		'utm_term',
		'utm_content',
	);
}

/**
 * Get a sanitized request value for a free material capture UTM field.
 *
 * @param string $field UTM field name.
 * @return string
 */
function executive_signal_get_free_material_capture_utm_value( $field ) {
	if ( ! in_array( $field, executive_signal_get_free_material_capture_utm_fields(), true ) ) {
		return '';
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public UTM passthrough; value is allowlisted and sanitized before output.
	return isset( $_GET[ $field ] ) ? sanitize_text_field( wp_unslash( $_GET[ $field ] ) ) : '';
}

/**
 * Get the primary free material category.
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|null
 */
function executive_signal_get_primary_free_material_category( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$terms   = $post_id ? get_the_terms( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY ) : false;

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	return $terms[0];
}

/**
 * Render free material category links.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function executive_signal_render_free_material_terms( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$terms   = get_the_term_list(
		$post_id,
		EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY,
		'',
		esc_html_x( ', ', 'free material category list separator', 'executive-signal-wordpress-theme' )
	);

	if ( ! $terms ) {
		return;
	}
	?>
	<div class="free-material-terms">
		<span class="free-material-terms__label"><?php esc_html_e( 'Category', 'executive-signal-wordpress-theme' ); ?></span>
		<span class="free-material-terms__links"><?php echo wp_kses_post( $terms ); ?></span>
	</div>
	<?php
}
