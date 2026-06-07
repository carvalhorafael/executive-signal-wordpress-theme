<?php
/**
 * Administrative notices for optional companion plugins.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns plugins recommended for the complete Executive Signal workflow.
 *
 * @return array<int, array{name: string, file: string, function: string, reason: string}>
 */
function executive_signal_get_recommended_plugins() {
	return array(
		array(
			'name'     => 'Free Materials',
			'file'     => 'free-materials/free-materials.php',
			'function' => 'free_materials',
			'reason'   => __( 'enables the free materials content domain.', 'executive-signal-wordpress-theme' ),
		),
		array(
			'name'     => 'Brevo Leads Capture',
			'file'     => 'brevo-leads-capture/brevo-leads-capture.php',
			'function' => 'brevo_leads_capture',
			'reason'   => __( 'handles free material capture forms and Brevo delivery.', 'executive-signal-wordpress-theme' ),
		),
	);
}

/**
 * Checks whether a companion plugin is active.
 *
 * @param array{name: string, file: string, function: string, reason: string} $plugin Plugin definition.
 * @return bool
 */
function executive_signal_is_recommended_plugin_active( array $plugin ) {
	if ( function_exists( $plugin['function'] ) ) {
		return true;
	}

	if ( ! function_exists( 'is_plugin_active' ) ) {
		$plugin_functions = ABSPATH . 'wp-admin/includes/plugin.php';

		if ( file_exists( $plugin_functions ) ) {
			require_once $plugin_functions;
		}
	}

	return function_exists( 'is_plugin_active' ) && is_plugin_active( $plugin['file'] );
}

/**
 * Returns recommended plugins that are not active.
 *
 * @return array<int, array{name: string, file: string, function: string, reason: string}>
 */
function executive_signal_get_missing_recommended_plugins() {
	return array_values(
		array_filter(
			executive_signal_get_recommended_plugins(),
			static function ( array $plugin ) {
				return ! executive_signal_is_recommended_plugin_active( $plugin );
			}
		)
	);
}

/**
 * Renders a discreet admin notice for inactive companion plugins.
 */
function executive_signal_render_recommended_plugins_notice() {
	if ( ! is_admin() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$missing_plugins = executive_signal_get_missing_recommended_plugins();

	if ( array() === $missing_plugins ) {
		return;
	}

	?>
	<div class="notice notice-info is-dismissible executive-signal-recommended-plugins-notice">
		<p>
			<strong><?php esc_html_e( 'Executive Signal recommended plugins', 'executive-signal-wordpress-theme' ); ?></strong>
		</p>
		<p><?php esc_html_e( 'For the complete materials workflow, install and activate:', 'executive-signal-wordpress-theme' ); ?></p>
		<ul>
			<?php foreach ( $missing_plugins as $plugin ) : ?>
				<li>
					<strong><?php echo esc_html( $plugin['name'] ); ?></strong>
					<?php echo esc_html( $plugin['reason'] ); ?>
					<code><?php echo esc_html( $plugin['file'] ); ?></code>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
add_action( 'admin_notices', 'executive_signal_render_recommended_plugins_notice' );
