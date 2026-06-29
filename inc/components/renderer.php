<?php
/**
 * Component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

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
		$registry     = cck_get_component_registry();

		if ( empty( $component_id ) || ! isset( $registry[ $component_id ] ) ) {
			return '';
		}

		$callback = isset( $registry[ $component_id ]['callback'] ) ? $registry[ $component_id ]['callback'] : '';

		if ( ! is_callable( $callback ) ) {
			return '';
		}

		cck_enqueue_frontend_assets();

		ob_start();
		$html = call_user_func( $callback, is_array( $atts ) ? $atts : array() );

		if ( is_string( $html ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Component callbacks return escaped HTML.
			echo $html;
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
