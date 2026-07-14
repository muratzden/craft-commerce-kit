<?php
/**
 * Layout renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_normalize_layout_component' ) ) {
	/**
	 * Layout component tanımını ileride JSON yapısına uyumlu olacak şekilde normalize eder.
	 *
	 * @param mixed $component Layout component tanımı.
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
			$component_id = sanitize_key( cck_array_get( $component, 'id', cck_array_get( $component, 'type', cck_array_get( $component, 'component', '' ) ) ) );
			$atts         = cck_array_get( $component, 'atts', cck_array_get( $component, 'attributes', array() ) );

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
	 * Kayıtlı bir layout içindeki componentleri sırayla render eder.
	 *
	 * @param string $layout_id Layout kimliği.
	 * @param array  $data      İleride JSON/veri tabanlı render için ayrılan veri.
	 * @return string
	 */
	function cck_render_layout( $layout_id, $data = array() ) {
		$layout_id = sanitize_key( $layout_id );
		$layout    = cck_get_layout( $layout_id );

		if ( empty( $layout ) ) {
			cck_debug_log( 'Layout bulunamadı: ' . $layout_id );
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
	 * Shortcode üzerinden layout render eder.
	 *
	 * @param array $atts Shortcode değerleri.
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
