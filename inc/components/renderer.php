<?php
/**
 * Component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_render_callback' ) ) {
	/**
	 * Get a component render callback name.
	 *
	 * @param string $component_id Component ID.
	 * @return string
	 */
	function cck_get_component_render_callback( $component_id ) {
		return 'cck_component_package_render_' . str_replace( '-', '_', sanitize_key( $component_id ) );
	}
}

if ( ! function_exists( 'cck_load_component_renderer' ) ) {
	/**
	 * Load a component render file.
	 *
	 * @param array $manifest Component manifest.
	 * @return string
	 */
	function cck_load_component_renderer( $manifest ) {
		if ( empty( $manifest['id'] ) || empty( $manifest['_render'] ) || ! file_exists( $manifest['_render'] ) ) {
			return '';
		}

		require_once $manifest['_render'];

		$callback = cck_get_component_render_callback( $manifest['id'] );

		return is_callable( $callback ) ? $callback : '';
	}
}

if ( ! function_exists( 'cck_render_component' ) ) {
	/**
	 * Render a registered component.
	 *
	 * @param string $component_id Component ID.
	 * @param array  $atts         Component attributes.
	 * @return string
	 */
	function cck_render_component( $component_id, $atts = array() ) {
		$component_id = sanitize_key( $component_id );
		$manifest     = cck_get_component_manifest( $component_id );

		if ( empty( $manifest ) ) {
			return '';
		}

		$callback = cck_load_component_renderer( $manifest );

		if ( empty( $callback ) ) {
			return '';
		}

		cck_enqueue_frontend_assets();

		ob_start();
		$html = call_user_func( $callback, is_array( $atts ) ? $atts : array(), $manifest );

		if ( is_string( $html ) ) {
			echo wp_kses_post( $html );
		}

		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_component_shortcode' ) ) {
	/**
	 * Render a component from shortcode attributes.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	function cck_component_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => 'hero',
			),
			(array) $atts,
			'cck_component'
		);

		$component_id = sanitize_key( $atts['id'] );

		if ( empty( $component_id ) ) {
			return '';
		}

		return cck_render_component( $component_id, $atts );
	}
}
