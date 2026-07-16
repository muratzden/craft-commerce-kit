<?php
/**
 * Component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_registry' ) ) {
	/**
	 * Return the central component renderer registry.
	 *
	 * @return array
	 */
	function &cck_component_registry() {
		if ( ! isset( $GLOBALS['cck_component_renderers'] ) || ! is_array( $GLOBALS['cck_component_renderers'] ) ) {
			$GLOBALS['cck_component_renderers'] = array();
		}

		return $GLOBALS['cck_component_renderers'];
	}
}

if ( ! function_exists( 'cck_register_component_renderer' ) ) {
	/**
	 * Register a component renderer callback.
	 *
	 * @param string          $id       Component ID.
	 * @param callable|string $callback Renderer callback.
	 * @return bool
	 */
	function cck_register_component_renderer( $id, $callback ) {
		$id = sanitize_key( $id );

		if ( '' === $id || ! is_callable( $callback ) ) {
			return false;
		}

		$registry        = &cck_component_registry();
		$registry[ $id ] = $callback;

		return true;
	}
}

if ( ! function_exists( 'cck_get_component_renderer' ) ) {
	/**
	 * Get a registered component renderer callback.
	 *
	 * @param string $id Component ID.
	 * @return callable|string|false
	 */
	function cck_get_component_renderer( $id ) {
		$id = sanitize_key( $id );

		if ( '' === $id ) {
			return false;
		}

		$registry = &cck_component_registry();

		return isset( $registry[ $id ] ) && is_callable( $registry[ $id ] ) ? $registry[ $id ] : false;
	}
}

if ( ! function_exists( 'cck_register_core_component_renderers' ) ) {
	/**
	 * Register bundled component renderers.
	 *
	 * @return void
	 */
	function cck_register_core_component_renderers() {
		$renderers = array(
			'brand-preset'    => 'cck_component_brand_preset',
			'header'          => 'cck_component_header',
			'header-actions'  => 'cck_component_header_actions',
			'layout-assets'   => 'cck_component_layout_assets',
			'footer'          => 'cck_component_footer',
			'hero'            => 'cck_component_hero',
			'collection-grid' => 'cck_component_collection_grid',
			'cta'             => 'cck_component_cta',
			'image-text'      => 'cck_component_image_text',
			'section-title'   => 'cck_component_section_title',
			'trust-block'     => 'cck_component_trust_block',
			'trust'           => 'cck_component_trust_block',
			'usp'             => 'cck_component_package_render_usp',
			'product-grid'    => 'cck_component_package_render_product_grid',
			'product_grid'    => 'cck_component_package_render_product_grid',
		);

		foreach ( $renderers as $component_id => $callback ) {
			cck_register_component_renderer( $component_id, $callback );
		}
	}
}

if ( ! function_exists( 'cck_get_component_render_callback' ) ) {
	/**
	 * Component render callback adını döndürür.
	 *
	 * @param string $component_id Component kimliği.
	 * @return string
	 */
	function cck_get_component_render_callback( $component_id ) {
		return 'cck_component_package_render_' . str_replace( '-', '_', sanitize_key( $component_id ) );
	}
}

if ( ! function_exists( 'cck_load_component_renderer' ) ) {
	/**
	 * Component render dosyasını yükler.
	 *
	 * @param array $manifest Component manifest verisi.
	 * @return callable|string
	 */
	function cck_load_component_renderer( $manifest ) {
		$component_id = cck_manifest_get( $manifest, 'id', '' );
		$render_path  = cck_locate_component_template( $component_id, cck_manifest_get( $manifest, '_render', '' ) );

		if ( empty( $component_id ) || empty( $render_path ) || ! file_exists( $render_path ) ) {
			cck_debug_log( 'Component render dosyası yüklenemedi: ' . cck_to_string( $component_id ) );
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
	 * Shortcode değerlerini manifest ayarlarına göre temizler.
	 *
	 * @param array $atts     Shortcode değerleri.
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
	 * Kayıtlı bir component'i güvenli şekilde render eder.
	 *
	 * @param string $component_id Component kimliği.
	 * @param array  $atts         Shortcode değerleri.
	 * @return string
	 */
	function cck_render_component( $component_id, $atts = array() ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}

		$definition = is_array( $component_id ) ? $component_id : array();

		if ( is_array( $component_id ) ) {
			if ( ! empty( $component_id['component'] ) ) {
				$component_id = $component_id['component'];
			} elseif ( ! empty( $component_id['type'] ) ) {
				$component_id = $component_id['type'];
			} elseif ( ! empty( $component_id['name'] ) ) {
				$component_id = $component_id['name'];
			} elseif ( ! empty( $component_id['id'] ) ) {
				$component_id = $component_id['id'];
			} else {
				return '';
			}

			$atts = function_exists( 'cck_merge_attributes' ) ? cck_merge_attributes( $definition, $atts ) : array();
		}

		if ( ! is_string( $component_id ) ) {
			return '';
		}

		$component_id = sanitize_key( $component_id );

		if ( '' === $component_id ) {
			return '';
		}

		$registered_callback = cck_get_component_renderer( $component_id );

		if ( $registered_callback ) {
			ob_start();
			$html   = call_user_func( $registered_callback, $atts );
			$output = ob_get_clean();

			if ( is_string( $html ) ) {
				$output .= $html;
			}

			return (string) $output;
		}

		$manifest     = cck_get_component_manifest( $component_id );

		if ( empty( $manifest ) ) {
			cck_debug_log( 'Component manifest bulunamadı: ' . $component_id );
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
	 * Shortcode üzerinden component render eder.
	 *
	 * @param array $atts Shortcode değerleri.
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
