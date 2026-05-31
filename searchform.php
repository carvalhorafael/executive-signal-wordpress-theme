<?php
/**
 * Search form template.
 *
 * @package ExecutiveSignal
 */

$executive_signal_search_args    = isset( $args ) && is_array( $args ) ? $args : array();
$executive_signal_search_id      = $executive_signal_search_args['id'] ?? wp_unique_id( 'search-field-' );
$executive_signal_search_class   = $executive_signal_search_args['class'] ?? 'search-form';
$executive_signal_search_submit  = $executive_signal_search_args['submit_label'] ?? __( 'Search', 'executive-signal-wordpress-theme' );
$executive_signal_search_button  = $executive_signal_search_args['button_content'] ?? esc_html( $executive_signal_search_submit );
$executive_signal_search_label   = $executive_signal_search_args['aria_label'] ?? ( $executive_signal_search_args['label'] ?? __( 'Search', 'executive-signal-wordpress-theme' ) );
$executive_signal_search_value   = $executive_signal_search_args['value'] ?? get_search_query();
$executive_signal_search_classes = trim( $executive_signal_search_class );
?>

<form class="<?php echo esc_attr( $executive_signal_search_classes ); ?>" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $executive_signal_search_id ); ?>"><?php echo esc_html( $executive_signal_search_label ); ?></label>
	<input
		id="<?php echo esc_attr( $executive_signal_search_id ); ?>"
		class="search-field es-header-search__field"
		type="search"
		name="s"
		value="<?php echo esc_attr( $executive_signal_search_value ); ?>"
		placeholder="<?php esc_attr_e( 'Search...', 'executive-signal-wordpress-theme' ); ?>"
	>
	<button class="search-submit es-header-search__submit" type="submit" aria-label="<?php echo esc_attr( $executive_signal_search_submit ); ?>">
		<?php echo wp_kses_post( $executive_signal_search_button ); ?>
	</button>
</form>
