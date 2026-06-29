<?php
/**
 * Component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_render_callback' ) ) {
	/**
	 * Component render callback ad?n? d?nd?r?r.
	 *
	 * @param string $component_id Component kimli?i.
	 * @return string
	 */
	function cck_get_component_render_callback( $component_id ) {
		return 'cck_component_package_render_' . str_replace( '-', '_', sanitize_key( $component_id ) );
	}
}

if ( ! function_exists( 'cck_load_component_renderer' ) ) {
	/**
	 * Component render dosyas?n? y?kler.
	 *
	 * @param array $manifest Component manifest verisi.
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

if ( ! function_exists( 'cck_sanitize_component_atts' ) ) {
	/**
	 * Shortcode de?erlerini manifest ayarlar?na g?re temizler.
	 *
	 * @param array $atts     Shortcode de?erleri.
	 * @param array $manifest Component manifest verisi.
	 * @return array
	 */
	function cck_sanitize_component_atts( $atts, $manifest ) {
		$component_id = isset( $manifest['id'] ) ? $manifest['id'] : '';
		$settings     = isset( $manifest['settings'] ) && is_array( $manifest['settings'] ) ? $manifest['settings'] : array();
		$values       = cck_get_component_defaults( $component_id );
		$atts         = is_array( $atts ) ? $atts : array();

		foreach ( $settings as $setting_id => $setting ) {
			if ( ! array_key_exists( $setting_id, $atts ) ) {
				continue;
			}

			$sanitize_callback = isset( $setting['sanitize_callback'] ) && is_callable( $setting['sanitize_callback'] ) ? $setting['sanitize_callback'] : 'sanitize_text_field';
			$values[ $setting_id ] = call_user_func( $sanitize_callback, wp_unslash( $atts[ $setting_id ] ) );
		}

		return $values;
	}
}

if ( ! function_exists( 'cck_render_component' ) ) {
	/**
	 * Kay?tl? bir component'i g?venli ?ekilde render eder.
	 *
	 * @param string $component_id Component kimli?i.
	 * @param array  $atts         Shortcode de?erleri.
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

		$values = cck_sanitize_component_atts( $atts, $manifest );

		ob_start();
		$html = call_user_func( $callback, $values, $manifest );

		if ( is_string( $html ) ) {
			echo wp_kses_post( $html );
		}

		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_component_shortcode' ) ) {
	/**
	 * Shortcode ?zerinden component render eder.
	 *
	 * @param array $atts Shortcode de?erleri.
	 * @return string
	 */
	function cck_component_shortcode( $atts ) {
		$raw_atts = is_array( $atts ) ? $atts : array();
		$base_atts = shortcode_atts(
			array(
				'id' => 'hero',
			),
			$raw_atts,
			'cck_component'
		);

		$component_id = sanitize_key( $base_atts['id'] );

		if ( empty( $component_id ) ) {
			return '';
		}

		$raw_atts['id'] = $component_id;

		return cck_render_component( $component_id, $raw_atts );
	}
}
