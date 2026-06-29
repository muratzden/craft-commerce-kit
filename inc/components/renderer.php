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
	 * @return callable|string
	 */
	function cck_load_component_renderer( $manifest ) {
		$component_id = cck_manifest_get( $manifest, 'id', '' );
		$render_path  = cck_locate_component_template( $component_id, cck_manifest_get( $manifest, '_render', '' ) );

		if ( empty( $component_id ) || empty( $render_path ) || ! file_exists( $render_path ) ) {
			cck_debug_log( 'Component render dosyas? y?klenemedi: ' . cck_to_string( $component_id ) );
			return '';
		}

		ob_start();
		require_once $render_path;
		ob_end_clean();

		$callback = cck_get_component_render_callback( $component_id );

		if ( is_callable( $callback ) ) {
			return $callback;
		}

		return function ( $values, $manifest ) use ( $render_path ) {
			$atts = $values;
			ob_start();
			include $render_path;

			return ob_get_clean();
		};
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
		$component_id = cck_manifest_get( $manifest, 'id', '' );
		$settings     = cck_manifest_get( $manifest, 'settings', array() );
		$values       = cck_get_component_defaults( $component_id );
		$atts         = is_array( $atts ) ? $atts : array();

		foreach ( $settings as $setting_id => $setting ) {
			if ( ! array_key_exists( $setting_id, $atts ) ) {
				continue;
			}

			$sanitize_callback = cck_sanitize_callback_name( cck_array_get( $setting, 'sanitize_callback', 'sanitize_text_field' ) );
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
			cck_debug_log( 'Component manifest bulunamad?: ' . $component_id );
			return '';
		}

		$callback = cck_load_component_renderer( $manifest );

		if ( empty( $callback ) ) {
			return '';
		}

		$values = cck_sanitize_component_atts( $atts, $manifest );

		cck_enqueue_frontend_assets();
		do_action( 'cck_before_render_component', $component_id, $values, $manifest );

		ob_start();
		$html = call_user_func( $callback, $values, $manifest );

		if ( is_string( $html ) ) {
			echo wp_kses_post( $html );
		}

		$output = ob_get_clean();

		do_action( 'cck_after_render_component', $component_id, $values, $manifest, $output );

		return $output;
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
		$raw_atts  = is_array( $atts ) ? $atts : array();
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
