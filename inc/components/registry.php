<?php
/**
 * Component registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_packages_path' ) ) {
	/**
	 * Component paketlerinin bulunduğu dizini döndürür.
	 *
	 * @return string
	 */
	function cck_get_component_packages_path() {
		return CCK_PLUGIN_DIR . 'inc/components/components/';
	}
}

if ( ! function_exists( 'cck_get_component_supported_features' ) ) {
	/**
	 * Manifest içinde izin verilen supports değerlerini döndürür.
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

if ( ! function_exists( 'cck_get_component_manifest_files' ) ) {
	/**
	 * Component manifest dosyalarını tek noktadan okur.
	 *
	 * @return array
	 */
	function cck_get_component_manifest_files() {
		$manifest_files = glob( cck_get_component_packages_path() . '*/manifest.php' );

		if ( ! is_array( $manifest_files ) ) {
			return array();
		}

		sort( $manifest_files );

		return $manifest_files;
	}
}

if ( ! function_exists( 'cck_load_component_manifest' ) ) {
	/**
	 * Manifest dosyasını yükler ve validator üzerinden geçirir.
	 *
	 * @param string $manifest_path Manifest dosya yolu.
	 * @return array
	 */
	function cck_load_component_manifest( $manifest_path ) {
		if ( ! is_string( $manifest_path ) || ! file_exists( $manifest_path ) ) {
			cck_debug_log( 'Manifest dosyası bulunamadı.' );
			return array();
		}

		$component_dir = dirname( $manifest_path );
		$manifest      = require $manifest_path;

		return cck_validate_component_manifest( $manifest, $component_dir );
	}
}

if ( ! function_exists( 'cck_get_component_registry' ) ) {
	/**
	 * Component manifest dosyalarını otomatik tarar ve registry verisini döndürür.
	 *
	 * @return array
	 */
	function cck_get_component_registry() {
		static $registry = null;

		if ( null !== $registry ) {
			return $registry;
		}

		$registry = array();

		foreach ( cck_get_component_manifest_files() as $manifest_path ) {
			$manifest = cck_load_component_manifest( $manifest_path );

			if ( empty( $manifest['id'] ) ) {
				continue;
			}

			if ( empty( $manifest['_render'] ) || ! file_exists( $manifest['_render'] ) ) {
				cck_debug_log( 'Render dosyası bulunamadı: ' . $manifest['id'] );
				continue;
			}

			$registry[ $manifest['id'] ] = $manifest;
		}

		return $registry;
	}
}

if ( ! function_exists( 'cck_get_component_manifest' ) ) {
	/**
	 * Belirli bir component manifest verisini döndürür.
	 *
	 * @param string $component_id Component kimliği.
	 * @return array|null
	 */
	function cck_get_component_manifest( $component_id ) {
		$component_id = sanitize_key( $component_id );
		$registry     = cck_get_component_registry();

		return cck_array_get( $registry, $component_id, null );
	}
}

if ( ! function_exists( 'cck_get_component' ) ) {
	/**
	 * Component paket verisini döndürür.
	 *
	 * @param string $component_id Component kimliği.
	 * @return array|null
	 */
	function cck_get_component( $component_id ) {
		return cck_get_component_manifest( $component_id );
	}
}

if ( ! function_exists( 'cck_get_component_settings' ) ) {
	/**
	 * Component ayar tanımlarını döndürür.
	 *
	 * @param string $component_id Component kimliği.
	 * @return array
	 */
	function cck_get_component_settings( $component_id ) {
		$manifest = cck_get_component_manifest( $component_id );

		return is_array( $manifest ) ? cck_manifest_get( $manifest, 'settings', array() ) : array();
	}
}

if ( ! function_exists( 'cck_get_component_defaults' ) ) {
	/**
	 * Component ayarlarının varsayılan değerlerini döndürür.
	 *
	 * @param string $component_id Component kimliği.
	 * @return array
	 */
	function cck_get_component_defaults( $component_id ) {
		$defaults = array();

		foreach ( cck_get_component_settings( $component_id ) as $setting_id => $setting ) {
			$defaults[ $setting_id ] = cck_array_get( $setting, 'default', '' );
		}

		return apply_filters( 'cck_component_defaults', $defaults, $component_id );
	}
}

if ( ! function_exists( 'cck_get_components' ) ) {
	/**
	 * Mevcut admin entegrasyonları için component registry verisini döndürür.
	 *
	 * @return array
	 */
	function cck_get_components() {
		return cck_get_component_registry();
	}
}
