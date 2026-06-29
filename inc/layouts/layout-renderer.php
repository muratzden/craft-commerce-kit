<?php
/**
 * Layout renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_normalize_layout_component' ) ) {
	/**
	 * Layout component tan?m?n? ileride JSON yap?s?na uyumlu olacak ?ekilde normalize eder.
	 *
	 * @param mixed $component Layout component tan?m?.
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
			$component_id = sanitize_key( cck_array_get( $component, 'id', '' ) );
			$atts         = cck_array_get( $component, 'atts', array() );

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
	 * Kay?tl? bir layout i?indeki componentleri s?rayla render eder.
	 *
	 * @param string $layout_id Layout kimli?i.
	 * @param array  $data      ?leride JSON/veri tabanl? render i?in ayr?lan veri.
	 * @return string
	 */
	function cck_render_layout( $layout_id, $data = array() ) {
		$layout_id = sanitize_key( $layout_id );
		$layout    = cck_get_layout( $layout_id );

		if ( empty( $layout ) ) {
			cck_debug_log( 'Layout bulunamad?: ' . $layout_id );
			return '';
		}

		$components = cck_manifest_get( $layout, 'components', array() );

		if ( empty( $components ) || ! is_array( $components ) ) {
			return '';
		}

		cck_enqueue_frontend_assets();

		$output = '';

		foreach ( $components as $component ) {
			$component = cck_normalize_layout_component( $component );

			if ( empty( $component['id'] ) ) {
				continue;
			}

			$output .= cck_render_component( $component['id'], $component['atts'] );
		}

		return $output;
	}
}

if ( ! function_exists( 'cck_layout_shortcode' ) ) {
	/**
	 * Shortcode ?zerinden layout render eder.
	 *
	 * @param array $atts Shortcode de?erleri.
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
