<?php
/**
 * Theme Customizer settings.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer controls for theme presentation settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function executive_signal_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'executive_signal_blog',
		array(
			'title'       => esc_html__( 'Blog', 'executive-signal-wordpress-theme' ),
			'description' => esc_html__( 'Controls the introductory copy shown on the posts page.', 'executive-signal-wordpress-theme' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'executive_signal_blog_eyebrow',
		array(
			'default'           => executive_signal_get_blog_eyebrow_default(),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'executive_signal_blog_eyebrow',
		array(
			'label'   => esc_html__( 'Blog eyebrow', 'executive-signal-wordpress-theme' ),
			'section' => 'executive_signal_blog',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'executive_signal_blog_title',
		array(
			'default'           => executive_signal_get_blog_title_default(),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'executive_signal_blog_title',
		array(
			'label'   => esc_html__( 'Blog title', 'executive-signal-wordpress-theme' ),
			'section' => 'executive_signal_blog',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'executive_signal_blog_description',
		array(
			'default'           => executive_signal_get_blog_description_default(),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'executive_signal_blog_description',
		array(
			'label'   => esc_html__( 'Blog description', 'executive-signal-wordpress-theme' ),
			'section' => 'executive_signal_blog',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'executive_signal_customize_register' );
