<?php
/**
 * Seed the minimal WordPress state required by the mandatory browser smoke tests.
 *
 * This file is executed once through `wp eval-file` before Playwright starts.
 * Keeping the setup in one process avoids dozens of container and WP-CLI boots.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$required_plugins = array(
	'free-materials/free-materials.php',
	'crm-leads-capture/crm-leads-capture.php',
);

foreach ( $required_plugins as $required_plugin ) {
	if ( ! is_plugin_active( $required_plugin ) ) {
		$result = activate_plugin( $required_plugin );

		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( esc_html( $result->get_error_message() ) );
		}
	}
}

switch_theme( 'executive-signal-wordpress-theme' );

/**
 * Create or update a page used by the smoke suite.
 *
 * @param string $slug     Page slug.
 * @param string $title    Page title.
 * @param string $template Optional page template.
 * @return int
 * @throws RuntimeException When WordPress cannot persist the page.
 */
function executive_signal_e2e_upsert_page( $slug, $title, $template = '' ) {
	$page    = get_page_by_path( $slug, OBJECT, 'page' );
	$data    = array(
		'ID'          => $page instanceof WP_Post ? $page->ID : 0,
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_name'   => $slug,
		'post_title'  => $title,
	);
	$page_id = wp_insert_post( $data, true );

	if ( is_wp_error( $page_id ) ) {
		throw new RuntimeException( esc_html( $page_id->get_error_message() ) );
	}

	if ( '' !== $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}

	return (int) $page_id;
}

$home_page_id     = executive_signal_e2e_upsert_page( 'inicio', 'Início', 'page-home.php' );
$blog_page_id     = executive_signal_e2e_upsert_page( 'blog', 'Blog' );
$coo_page_id      = executive_signal_e2e_upsert_page( 'coo-as-a-service', 'COO as a Service', 'page-coo-as-a-service.php' );
$speaking_page_id = executive_signal_e2e_upsert_page( 'palestras', 'Palestras', 'page-palestras.php' );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_page_id );
update_option( 'page_for_posts', $blog_page_id );
update_option( 'permalink_structure', '/%postname%/' );
update_post_meta( $coo_page_id, '_crm_leads_capture_profile', 'coo-as-a-service' );
update_post_meta( $speaking_page_id, '_crm_leads_capture_profile', 'speaker-invitation' );

$tracking_fields = array_map(
	static function ( $name ) {
		return array(
			'name'     => $name,
			'type'     => 'text',
			'group'    => 'tracking',
			'required' => false,
		);
	},
	array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_name' )
);

$capture_profiles = array(
	'coo-as-a-service'   => array(
		'name'      => 'COO as a Service',
		'slug'      => 'coo-as-a-service',
		'fields'    => array_merge(
			array(
				array(
					'name'     => 'name',
					'type'     => 'text',
					'group'    => 'lead',
					'required' => true,
				),
				array(
					'name'     => 'email',
					'type'     => 'email',
					'group'    => 'lead',
					'required' => true,
				),
				array(
					'name'     => 'whatsapp',
					'type'     => 'phone',
					'group'    => 'lead',
					'required' => true,
				),
			),
			$tracking_fields,
			array(
				array(
					'name'     => 'company',
					'type'     => 'text',
					'group'    => 'custom_fields',
					'required' => true,
				),
				array(
					'name'           => 'role',
					'type'           => 'select',
					'group'          => 'custom_fields',
					'required'       => true,
					'allowed_values' => array( 'founder', 'ceo', 'executive' ),
				),
				array(
					'name'     => 'company_url',
					'type'     => 'url',
					'group'    => 'custom_fields',
					'required' => false,
				),
				array(
					'name'     => 'challenge',
					'type'     => 'textarea',
					'group'    => 'custom_fields',
					'required' => true,
				),
				array(
					'name'     => 'consent',
					'type'     => 'boolean',
					'group'    => 'consent',
					'required' => true,
				),
			)
		),
		'context'   => array( 'source' => 'coo_as_a_service' ),
		'providers' => array(
			'brevo'      => array(
				'list_ids'      => array( 123 ),
				'attribute_map' => array(),
			),
			'rd_station' => array(
				'conversion_identifier' => 'coo-as-a-service-interest',
				'tags'                  => array( 'coo-as-a-service' ),
				'field_map'             => array(),
			),
		),
		'success'   => array( 'message' => 'Recebi seu contexto. Vou analisar as informações e entrarei em contato.' ),
	),
	'speaker-invitation' => array(
		'name'      => 'Convite para palestras',
		'slug'      => 'speaker-invitation',
		'fields'    => array_merge(
			array(
				array(
					'name'     => 'name',
					'type'     => 'text',
					'group'    => 'lead',
					'required' => true,
				),
				array(
					'name'     => 'email',
					'type'     => 'email',
					'group'    => 'lead',
					'required' => true,
				),
				array(
					'name'     => 'whatsapp',
					'type'     => 'phone',
					'group'    => 'lead',
					'required' => true,
				),
			),
			$tracking_fields,
			array(
				array(
					'name'     => 'organization',
					'type'     => 'text',
					'group'    => 'custom_fields',
					'required' => true,
				),
				array(
					'name'     => 'objective_context',
					'type'     => 'textarea',
					'group'    => 'custom_fields',
					'required' => true,
				),
				array(
					'name'           => 'format',
					'type'           => 'select',
					'group'          => 'custom_fields',
					'required'       => true,
					'allowed_values' => array( 'presencial', 'online', 'hibrido' ),
				),
				array(
					'name'     => 'consent',
					'type'     => 'boolean',
					'group'    => 'consent',
					'required' => true,
				),
			)
		),
		'context'   => array( 'source' => 'speaker_invitation' ),
		'providers' => array(
			'brevo'      => array(
				'list_ids'      => array( 123 ),
				'attribute_map' => array(),
			),
			'rd_station' => array(
				'conversion_identifier' => 'speaker-invitation',
				'tags'                  => array( 'speaker', 'event' ),
				'field_map'             => array(),
			),
		),
		'success'   => array( 'message' => 'Recebi os detalhes do evento. Vou analisar o convite e retornar.' ),
	),
);

update_option( 'crm_leads_capture_profiles', $capture_profiles, false );

$menu_name   = 'E2E Primary';
$menu_object = wp_get_nav_menu_object( $menu_name );
$menu_id     = $menu_object instanceof WP_Term ? $menu_object->term_id : wp_create_nav_menu( $menu_name );

if ( is_wp_error( $menu_id ) ) {
	throw new RuntimeException( esc_html( $menu_id->get_error_message() ) );
}

$menu_items = wp_get_nav_menu_items( $menu_id );

if ( is_array( $menu_items ) ) {
	foreach ( $menu_items as $menu_item ) {
		wp_delete_post( $menu_item->ID, true );
	}
}

wp_update_nav_menu_item(
	$menu_id,
	0,
	array(
		'menu-item-title'  => 'Início',
		'menu-item-url'    => home_url( '/' ),
		'menu-item-status' => 'publish',
	)
);
$parent_item_id = wp_update_nav_menu_item(
	$menu_id,
	0,
	array(
		'menu-item-title'  => 'Atuação',
		'menu-item-url'    => '#',
		'menu-item-status' => 'publish',
	)
);
wp_update_nav_menu_item(
	$menu_id,
	0,
	array(
		'menu-item-title'     => 'Palestras',
		'menu-item-url'       => get_permalink( $speaking_page_id ),
		'menu-item-parent-id' => $parent_item_id,
		'menu-item-status'    => 'publish',
	)
);

$locations            = get_theme_mod( 'nav_menu_locations', array() );
$locations['primary'] = (int) $menu_id;
set_theme_mod( 'nav_menu_locations', $locations );

flush_rewrite_rules();
