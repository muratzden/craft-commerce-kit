<?php
/**
 * Component registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_packages_path' ) ) {
	/**
	 * Component paketlerinin bulundu?u dizini d?nd?r?r.
	 *
	 * @return string
	 */
	function cck_get_component_packages_path() {
		return CCK_PLUGIN_DIR . 'inc/components/components/';
	}
}

if ( ! function_exists( 'cck_get_component_supported_features' ) ) {
	/**
	 * Manifest i?inde izin verilen supports de?erlerini d?nd?r?r.
	 *
	 * @return array
	 */
	function cck_get_component_supported_features() {
		return array(
			'background',
			'spacing',
			'typography',
			'button',
			'animation',
			'visibility',
		);
	}
}

if ( ! function_exists( 'cck_normalize_component_settings' ) ) {
	/**
	 * Component ayarlar?n? g?venli ve standart bir yap?ya d?n??t?r?r.
	 *
	 * @param array $settings Manifest ayarlar?.
	 * @return array
	 */
	function cck_normalize_component_settings( $settings ) {
		$normalized = array();

		if ( ! is_array( $settings ) ) {
			return $normalized;
		}

		foreach ( $settings as $setting_id => $setting ) {
			$setting_id = sanitize_key( $setting_id );

			if ( empty( $setting_id ) || ! is_array( $setting ) ) {
				continue;
			}

			$sanitize_callback = isset( $setting['sanitize_callback'] ) ? $setting['sanitize_callback'] : 'sanitize_text_field';

			if ( ! is_callable( $sanitize_callback ) ) {
				$sanitize_callback = 'sanitize_text_field';
			}

			$normalized[ $setting_id ] = array(
				'type'              => isset( $setting['type'] ) ? sanitize_key( $setting['type'] ) : 'text',
				'label'             => isset( $setting['label'] ) ? $setting['label'] : $setting_id,
				'description'       => isset( $setting['description'] ) ? $setting['description'] : '',
				'default'           => isset( $setting['default'] ) ? $setting['default'] : '',
				'required'          => ! empty( $setting['required'] ),
				'sanitize_callback' => $sanitize_callback,
			);
		}

		return $normalized;
	}
}

if ( ! function_exists( 'cck_normalize_component_manifest' ) ) {
	/**
	 * Component manifest verisini framework API'leri i?in do?rular ve normalize eder.
	 *
	 * @param array  $manifest      Component manifest verisi.
	 * @param string $component_dir Component dizini.
	 * @return array
	 */
	function cck_normalize_component_manifest( $manifest, $component_dir ) {
		$component_id = isset( $manifest['id'] ) ? sanitize_key( $manifest['id'] ) : '';

		if ( empty( $component_id ) ) {
			return array();
		}

		$supports = isset( $manifest['supports'] ) && is_array( $manifest['supports'] ) ? $manifest['supports'] : array();
		$supports = array_values( array_intersect( array_map( 'sanitize_key', $supports ), cck_get_component_supported_features() ) );

		$manifest['id']          = $component_id;
		$manifest['name']        = isset( $manifest['name'] ) ? $manifest['name'] : $component_id;
		$manifest['label']       = $manifest['name'];
		$manifest['description'] = isset( $manifest['description'] ) ? $manifest['description'] : '';
		$manifest['version']     = isset( $manifest['version'] ) ? sanitize_text_field( $manifest['version'] ) : '1.0.0';
		$manifest['category']    = isset( $manifest['category'] ) ? sanitize_key( $manifest['category'] ) : 'ui';
		$manifest['icon']        = isset( $manifest['icon'] ) ? sanitize_key( $manifest['icon'] ) : 'layout';
		$manifest['preview']     = isset( $manifest['preview'] ) ? esc_url_raw( $manifest['preview'] ) : '';
		$manifest['supports']    = $supports;
		$manifest['settings']    = cck_normalize_component_settings( isset( $manifest['settings'] ) ? $manifest['settings'] : array() );
		$manifest['_path']       = trailingslashit( $component_dir );
		$manifest['_render']     = trailingslashit( $component_dir ) . 'render.php';

		return $manifest;
	}
}

if ( ! function_exists( 'cck_get_component_registry' ) ) {
	/**
	 * Component manifest dosyalar?n? otomatik tarar ve registry verisini d?nd?r?r.
	 *
	 * @return array
	 */
	function cck_get_component_registry() {
		static $registry = null;

		if ( null !== $registry ) {
			return $registry;
		}

		$registry       = array();
		$manifest_files = glob( cck_get_component_packages_path() . '*/manifest.php' );

		if ( ! is_array( $manifest_files ) ) {
			return $registry;
		}

		sort( $manifest_files );

		foreach ( $manifest_files as $manifest_path ) {
			$component_dir = dirname( $manifest_path );
			$manifest      = require $manifest_path;

			if ( ! is_array( $manifest ) ) {
				continue;
			}

			$manifest = cck_normalize_component_manifest( $manifest, $component_dir );

			if ( empty( $manifest['id'] ) || ! file_exists( $manifest['_render'] ) ) {
				continue;
			}

			$registry[ $manifest['id'] ] = $manifest;
		}

		return $registry;
	}
}

if ( ! function_exists( 'cck_get_component_manifest' ) ) {
	/**
	 * Belirli bir component manifest verisini d?nd?r?r.
	 *
	 * @param string $component_id Component kimli?i.
	 * @return array|null
	 */
	function cck_get_component_manifest( $component_id ) {
		$component_id = sanitize_key( $component_id );
		$registry     = cck_get_component_registry();

		return isset( $registry[ $component_id ] ) ? $registry[ $component_id ] : null;
	}
}

if ( ! function_exists( 'cck_get_component' ) ) {
	/**
	 * Component paket verisini d?nd?r?r.
	 *
	 * @param string $component_id Component kimli?i.
	 * @return array|null
	 */
	function cck_get_component( $component_id ) {
		return cck_get_component_manifest( $component_id );
	}
}

if ( ! function_exists( 'cck_get_component_settings' ) ) {
	/**
	 * Component ayar tan?mlar?n? d?nd?r?r.
	 *
	 * @param string $component_id Component kimli?i.
	 * @return array
	 */
	function cck_get_component_settings( $component_id ) {
		$manifest = cck_get_component_manifest( $component_id );

		return isset( $manifest['settings'] ) && is_array( $manifest['settings'] ) ? $manifest['settings'] : array();
	}
}

if ( ! function_exists( 'cck_get_component_defaults' ) ) {
	/**
	 * Component ayarlar?n?n varsay?lan de?erlerini d?nd?r?r.
	 *
	 * @param string $component_id Component kimli?i.
	 * @return array
	 */
	function cck_get_component_defaults( $component_id ) {
		$defaults = array();

		foreach ( cck_get_component_settings( $component_id ) as $setting_id => $setting ) {
			$defaults[ $setting_id ] = isset( $setting['default'] ) ? $setting['default'] : '';
		}

		return $defaults;
	}
}

if ( ! function_exists( 'cck_get_components' ) ) {
	/**
	 * Mevcut admin entegrasyonlar? i?in component registry verisini d?nd?r?r.
	 *
	 * @return array
	 */
	function cck_get_components() {
		return cck_get_component_registry();
	}
}
