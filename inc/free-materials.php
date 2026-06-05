<?php
/**
 * Free material content type.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE          = 'material_gratuito';
const EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY           = 'material_categoria';
const EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL            = '_executive_signal_material_capture_url';
const EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL          = '_executive_signal_material_capture_label';
const EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID      = '_brevo_leads_capture_list_id';
const EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL = '_brevo_leads_capture_delivery_url';
const EXECUTIVE_SIGNAL_FREE_MATERIALS_PAGE_PATH         = 'materiais-gratuitos';

/**
 * Register free material post type, taxonomy and metadata.
 *
 * @return void
 */
function executive_signal_register_free_material_content_type() {
	register_post_type(
		EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		array(
			'has_archive'        => false,
			'hierarchical'       => false,
			'labels'             => array(
				'name'                  => _x( 'Free materials', 'Post type general name', 'executive-signal-wordpress-theme' ),
				'singular_name'         => _x( 'Free material', 'Post type singular name', 'executive-signal-wordpress-theme' ),
				'menu_name'             => _x( 'Free materials', 'Admin menu text', 'executive-signal-wordpress-theme' ),
				'name_admin_bar'        => _x( 'Free material', 'Add new on toolbar', 'executive-signal-wordpress-theme' ),
				'add_new'               => __( 'Add new', 'executive-signal-wordpress-theme' ),
				'add_new_item'          => __( 'Add free material', 'executive-signal-wordpress-theme' ),
				'all_items'             => __( 'All materials', 'executive-signal-wordpress-theme' ),
				'archives'              => __( 'Free materials', 'executive-signal-wordpress-theme' ),
				'edit_item'             => __( 'Edit free material', 'executive-signal-wordpress-theme' ),
				'featured_image'        => __( 'Material image', 'executive-signal-wordpress-theme' ),
				'filter_items_list'     => __( 'Filter materials', 'executive-signal-wordpress-theme' ),
				'items_list'            => __( 'Materials list', 'executive-signal-wordpress-theme' ),
				'items_list_navigation' => __( 'Materials list navigation', 'executive-signal-wordpress-theme' ),
				'new_item'              => __( 'New free material', 'executive-signal-wordpress-theme' ),
				'not_found'             => __( 'No materials found.', 'executive-signal-wordpress-theme' ),
				'not_found_in_trash'    => __( 'No materials found in Trash.', 'executive-signal-wordpress-theme' ),
				'remove_featured_image' => __( 'Remove material image', 'executive-signal-wordpress-theme' ),
				'search_items'          => __( 'Search materials', 'executive-signal-wordpress-theme' ),
				'set_featured_image'    => __( 'Set material image', 'executive-signal-wordpress-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this material', 'executive-signal-wordpress-theme' ),
				'use_featured_image'    => __( 'Use as material image', 'executive-signal-wordpress-theme' ),
				'view_item'             => __( 'View free material', 'executive-signal-wordpress-theme' ),
			),
			'menu_icon'          => 'dashicons-download',
			'public'             => true,
			'publicly_queryable' => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'materiais-gratuitos',
				'with_front' => false,
			),
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		)
	);

	register_taxonomy(
		EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY,
		array( EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE ),
		array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => _x( 'Material categories', 'taxonomy general name', 'executive-signal-wordpress-theme' ),
				'singular_name'     => _x( 'Material category', 'taxonomy singular name', 'executive-signal-wordpress-theme' ),
				'add_new_item'      => __( 'Add material category', 'executive-signal-wordpress-theme' ),
				'all_items'         => __( 'All categories', 'executive-signal-wordpress-theme' ),
				'back_to_items'     => __( 'Back to categories', 'executive-signal-wordpress-theme' ),
				'edit_item'         => __( 'Edit category', 'executive-signal-wordpress-theme' ),
				'menu_name'         => __( 'Categories', 'executive-signal-wordpress-theme' ),
				'new_item_name'     => __( 'New category name', 'executive-signal-wordpress-theme' ),
				'not_found'         => __( 'No categories found.', 'executive-signal-wordpress-theme' ),
				'parent_item'       => __( 'Parent category', 'executive-signal-wordpress-theme' ),
				'parent_item_colon' => __( 'Parent category:', 'executive-signal-wordpress-theme' ),
				'search_items'      => __( 'Search categories', 'executive-signal-wordpress-theme' ),
				'update_item'       => __( 'Update category', 'executive-signal-wordpress-theme' ),
				'view_item'         => __( 'View category', 'executive-signal-wordpress-theme' ),
			),
			'public'            => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'materiais-gratuitos/categoria',
				'with_front' => false,
			),
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'show_ui'           => true,
		)
	);

	register_post_meta(
		EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL,
		array(
			'auth_callback'     => static function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
		)
	);

	register_post_meta(
		EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL,
		array(
			'auth_callback'     => static function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
		)
	);

	register_post_meta(
		EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID,
		array(
			'auth_callback'     => static function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
		)
	);

	register_post_meta(
		EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL,
		array(
			'auth_callback'     => static function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
		)
	);

	add_rewrite_rule(
		'^materiais-gratuitos/categoria/([^/]+)/?$',
		'index.php?' . EXECUTIVE_SIGNAL_FREE_MATERIAL_TAXONOMY . '=$matches[1]',
		'top'
	);
}
add_action( 'init', 'executive_signal_register_free_material_content_type' );

/**
 * Flush rewrites after the theme starts registering the free material routes.
 *
 * @return void
 */
function executive_signal_flush_free_material_rewrites() {
	executive_signal_register_free_material_content_type();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'executive_signal_flush_free_material_rewrites' );

/**
 * Register capture settings meta box.
 *
 * @return void
 */
function executive_signal_register_free_material_meta_box() {
	add_meta_box(
		'executive-signal-free-material-capture',
		__( 'Material capture', 'executive-signal-wordpress-theme' ),
		'executive_signal_render_free_material_meta_box',
		EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE,
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'executive_signal_register_free_material_meta_box' );

/**
 * Render capture settings meta box.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function executive_signal_render_free_material_meta_box( $post ) {
	$cta_url            = get_post_meta( $post->ID, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL, true );
	$cta_label          = get_post_meta( $post->ID, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL, true );
	$brevo_list_id      = get_post_meta( $post->ID, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID, true );
	$brevo_delivery_url = get_post_meta( $post->ID, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL, true );

	wp_nonce_field( 'executive_signal_save_free_material_capture', 'executive_signal_free_material_capture_nonce' );
	?>
	<p>
		<label for="executive-signal-free-material-cta-url"><?php esc_html_e( 'Button URL', 'executive-signal-wordpress-theme' ); ?></label>
		<input
			class="widefat"
			id="executive-signal-free-material-cta-url"
			name="executive_signal_free_material_cta_url"
			type="url"
			value="<?php echo esc_attr( $cta_url ); ?>"
			placeholder="https://"
		>
	</p>
	<p>
		<label for="executive-signal-free-material-cta-label"><?php esc_html_e( 'Button text', 'executive-signal-wordpress-theme' ); ?></label>
		<input
			class="widefat"
			id="executive-signal-free-material-cta-label"
			name="executive_signal_free_material_cta_label"
			type="text"
			value="<?php echo esc_attr( $cta_label ); ?>"
			placeholder="<?php esc_attr_e( 'Download free material', 'executive-signal-wordpress-theme' ); ?>"
		>
	</p>
	<p>
		<label for="executive-signal-free-material-brevo-list-id"><?php esc_html_e( 'Brevo list ID', 'executive-signal-wordpress-theme' ); ?></label>
		<input
			class="widefat"
			id="executive-signal-free-material-brevo-list-id"
			name="executive_signal_free_material_brevo_list_id"
			type="text"
			value="<?php echo esc_attr( $brevo_list_id ); ?>"
		>
	</p>
	<p>
		<label for="executive-signal-free-material-brevo-delivery-url"><?php esc_html_e( 'Delivery redirect URL', 'executive-signal-wordpress-theme' ); ?></label>
		<input
			class="widefat"
			id="executive-signal-free-material-brevo-delivery-url"
			name="executive_signal_free_material_brevo_delivery_url"
			type="url"
			value="<?php echo esc_attr( $brevo_delivery_url ); ?>"
			placeholder="https://"
		>
	</p>
	<?php
}

/**
 * Save capture settings.
 *
 * @param int $post_id Current post ID.
 * @return void
 */
function executive_signal_save_free_material_meta( $post_id ) {
	$nonce = isset( $_POST['executive_signal_free_material_capture_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['executive_signal_free_material_capture_nonce'] ) ) : '';

	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'executive_signal_save_free_material_capture' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$cta_url            = isset( $_POST['executive_signal_free_material_cta_url'] ) ? esc_url_raw( wp_unslash( $_POST['executive_signal_free_material_cta_url'] ) ) : '';
	$cta_label          = isset( $_POST['executive_signal_free_material_cta_label'] ) ? sanitize_text_field( wp_unslash( $_POST['executive_signal_free_material_cta_label'] ) ) : '';
	$brevo_list_id      = isset( $_POST['executive_signal_free_material_brevo_list_id'] ) ? sanitize_text_field( wp_unslash( $_POST['executive_signal_free_material_brevo_list_id'] ) ) : '';
	$brevo_delivery_url = isset( $_POST['executive_signal_free_material_brevo_delivery_url'] ) ? esc_url_raw( wp_unslash( $_POST['executive_signal_free_material_brevo_delivery_url'] ) ) : '';

	if ( $cta_url ) {
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL, $cta_url );
	} else {
		delete_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL );
	}

	if ( $cta_label ) {
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL, $cta_label );
	} else {
		delete_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL );
	}

	if ( $brevo_list_id ) {
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID, $brevo_list_id );
	} else {
		delete_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_LIST_ID );
	}

	if ( $brevo_delivery_url ) {
		update_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL, $brevo_delivery_url );
	} else {
		delete_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_BREVO_DELIVERY_URL );
	}
}
add_action( 'save_post_' . EXECUTIVE_SIGNAL_FREE_MATERIAL_POST_TYPE, 'executive_signal_save_free_material_meta' );

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
 * @return array{label:string,url:string}
 */
function executive_signal_get_free_material_cta( $post_id = null ) {
	$post_id   = $post_id ? (int) $post_id : get_the_ID();
	$cta_url   = $post_id ? get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_URL, true ) : '';
	$cta_label = $post_id ? get_post_meta( $post_id, EXECUTIVE_SIGNAL_FREE_MATERIAL_CTA_LABEL, true ) : '';

	return array(
		'label' => $cta_label ? $cta_label : __( 'Download free material', 'executive-signal-wordpress-theme' ),
		'url'   => $cta_url ? $cta_url : '#capture',
	);
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
