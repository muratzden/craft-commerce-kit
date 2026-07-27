<?php
/**
 * Layout renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_normalize_layout_component' ) ) {
	/**
	 * Normalize a layout component definition.
	 *
	 * @param mixed $component Layout component definition.
	 * @return array
	 */
	function cck_normalize_layout_component( $component ) {
		if ( is_string( $component ) ) {
			return array(
				'id'   => sanitize_key( $component ),
				'atts' => array(),
			);
		}

		if ( is_array( $component ) ) {
			$component_id = sanitize_key(
				cck_array_get(
					$component,
					'id',
					cck_array_get(
						$component,
						'type',
						cck_array_get( $component, 'component', '' )
					)
				)
			);
			$atts = cck_array_get(
				$component,
				'atts',
				cck_array_get( $component, 'attributes', array() )
			);

			return array(
				'id'   => $component_id,
				'atts' => is_array( $atts ) ? $atts : array(),
			);
		}

		return array(
			'id'   => '',
			'atts' => array(),
		);
	}
}

if ( ! function_exists( 'cck_render_layout' ) ) {
	/**
	 * Render the components of a registered layout in sequence.
	 *
	 * @param string $layout_id Registered layout ID.
	 * @param array  $data      Composition context and future overrides.
	 * @return string
	 */
	function cck_render_layout( $layout_id, $data = array() ) {
		$layout_id = sanitize_key( $layout_id );
		$queue     = cck_compose_layout( $layout_id, (array) $data );

		if ( is_wp_error( $queue ) ) {
			cck_debug_log(
				sprintf(
					'Layout composition failed [%1$s]: %2$s',
					$layout_id,
					$queue->get_error_message()
				)
			);

			return '';
		}

		if ( empty( $queue ) ) {
			return '';
		}

		cck_enqueue_frontend_assets();

		$output = '';

		foreach ( $queue as $component ) {
			$component_id = sanitize_key( cck_array_get( $component, 'id', '' ) );
			$atts         = cck_array_get( $component, 'atts', array() );

			if ( empty( $component_id ) ) {
				continue;
			}

			$output .= cck_render_component(
				$component_id,
				is_array( $atts ) ? $atts : array()
			);
		}

		return $output;
	}
}

if ( ! function_exists( 'cck_layout_shortcode' ) ) {
	/**
	 * Render a registered layout through the layout shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	function cck_layout_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => 'homepage',
			),
			(array) $atts,
			'cck_layout'
		);

		$layout_id = sanitize_key( $atts['id'] );

		if ( empty( $layout_id ) ) {
			return '';
		}

		return cck_render_layout( $layout_id );
	}
}
