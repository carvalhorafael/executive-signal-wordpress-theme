<?php
/**
 * GitHub Releases updater integration.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXECUTIVE_SIGNAL_THEME_SLUG', basename( EXECUTIVE_SIGNAL_THEME_DIR ) );
define( 'EXECUTIVE_SIGNAL_THEME_UPDATE_URI', 'https://github.com/carvalhorafael/executive-signal-wordpress-theme' );
define( 'EXECUTIVE_SIGNAL_THEME_RELEASE_API', 'https://api.github.com/repos/carvalhorafael/executive-signal-wordpress-theme/releases/latest' );
define( 'EXECUTIVE_SIGNAL_THEME_RELEASE_ASSET', 'executive-signal-wordpress-theme.zip' );
define( 'EXECUTIVE_SIGNAL_THEME_RELEASE_CACHE_KEY', 'executive_signal_theme_latest_release' );

/**
 * Normalize a GitHub release tag into a WordPress theme version.
 *
 * @param string $tag Release tag.
 * @return string
 */
function executive_signal_normalize_github_release_version( $tag ) {
	return ltrim( (string) $tag, 'vV' );
}

/**
 * Find the expected ZIP asset URL in a GitHub release payload.
 *
 * @param array<string, mixed> $release GitHub release payload.
 * @return string
 */
function executive_signal_find_github_release_asset_url( $release ) {
	if ( empty( $release['assets'] ) || ! is_array( $release['assets'] ) ) {
		return '';
	}

	foreach ( $release['assets'] as $asset ) {
		if (
			is_array( $asset )
			&& EXECUTIVE_SIGNAL_THEME_RELEASE_ASSET === ( $asset['name'] ?? '' )
			&& ! empty( $asset['browser_download_url'] )
		) {
			return (string) $asset['browser_download_url'];
		}
	}

	return '';
}

/**
 * Convert a GitHub release payload into WordPress update data.
 *
 * @param array<string, mixed> $release GitHub release payload.
 * @param string               $current_version Installed theme version.
 * @return array<string, string>|false
 */
function executive_signal_theme_update_from_release( $release, $current_version ) {
	if ( empty( $release['tag_name'] ) ) {
		return false;
	}

	$new_version = executive_signal_normalize_github_release_version( (string) $release['tag_name'] );
	$package     = executive_signal_find_github_release_asset_url( $release );

	if ( '' === $package || ! version_compare( $new_version, $current_version, '>' ) ) {
		return false;
	}

	return array(
		'theme'       => EXECUTIVE_SIGNAL_THEME_SLUG,
		'version'     => $new_version,
		'new_version' => $new_version,
		'url'         => isset( $release['html_url'] ) ? (string) $release['html_url'] : EXECUTIVE_SIGNAL_THEME_UPDATE_URI,
		'package'     => $package,
	);
}

/**
 * Get the latest GitHub release payload with a transient cache.
 *
 * @return array<string, mixed>|false
 */
function executive_signal_get_latest_github_release() {
	$cached = get_site_transient( EXECUTIVE_SIGNAL_THEME_RELEASE_CACHE_KEY );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$response = wp_remote_get(
		EXECUTIVE_SIGNAL_THEME_RELEASE_API,
		array(
			'headers' => array(
				'Accept' => 'application/vnd.github+json',
			),
			'timeout' => 5,
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return false;
	}

	$decoded = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $decoded ) ) {
		return false;
	}

	set_site_transient( EXECUTIVE_SIGNAL_THEME_RELEASE_CACHE_KEY, $decoded, HOUR_IN_SECONDS );

	return $decoded;
}

/**
 * Hook WordPress theme update checks for the GitHub Update URI.
 *
 * @param mixed                $update Existing update payload.
 * @param array<string, mixed> $theme_data Installed theme data.
 * @param string               $theme_slug Theme slug.
 * @return mixed
 */
function executive_signal_filter_github_theme_update( $update, $theme_data, $theme_slug ) {
	if ( EXECUTIVE_SIGNAL_THEME_SLUG !== $theme_slug || EXECUTIVE_SIGNAL_THEME_UPDATE_URI !== ( $theme_data['UpdateURI'] ?? '' ) ) {
		return $update;
	}

	$release = executive_signal_get_latest_github_release();

	if ( ! $release ) {
		return $update;
	}

	$release_update = executive_signal_theme_update_from_release( $release, EXECUTIVE_SIGNAL_THEME_VERSION );

	return $release_update ? $release_update : $update;
}
add_filter( 'update_themes_github.com', 'executive_signal_filter_github_theme_update', 10, 3 );
