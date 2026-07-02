<?php
/**
 * Layout registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_layout_packages_path' ) ) {
	/**
	 * Layout tanımlarının bulunduğu dizini döndürür.
	 *
	 * @return string
	 */
	function cck_get_layout_packages_path() {
		return CCK_PLUGIN_DIR . 'inc/layouts/layouts/';
	}
}

if ( ! function_exists( 'cck_get_layout_manifest_files' ) ) {
	/**
	 * Layout manifest dosyalarını tek noktadan okur.
	 *
	 * @return array
	 */
	function cck_get_layout_manifest_files() {
		$layout_files = glob( cck_get_layout_packages_path() . '*.php' );

		if ( ! is_array( $layout_files ) ) {
			return array();
		}

		sort( $layout_files );

		return $layout_files;
	}
}

if ( ! function_exists( 'cck_validate_layout_manifest' ) ) {
	/**
	 * Layout manifest verisini güvenli varsayılanlarla normalize eder.
	 *
	 * @param array  $manifest    Layout manifest verisi.
	 * @param string $layout_path Layout dosya yolu.
	 * @return array
	 */
	function cck_validate_layout_manifest( $manifest, $layout_path, $fallback_id = '' ) {
		if ( ! is_array( $manifest ) ) {
			cck_debug_log( 'Layout manifest array değil: ' . $layout_path );
			return array();
		}

		$layout_id = sanitize_key( cck_manifest_get( $manifest, 'id', $fallback_id ? $fallback_id : basename( $layout_path, '.php' ) ) );

		if ( empty( $layout_id ) ) {
			cck_debug_log( 'Layout id boş: ' . $layout_path );
			return array();
		}

		$components = cck_manifest_get( $manifest, 'components', array() );

		$manifest['id']          = $layout_id;
		$manifest['name']        = cck_manifest_get( $manifest, 'name', $layout_id );
		$manifest['description'] = cck_manifest_get( $manifest, 'description', '' );
		$manifest['version']     = sanitize_text_field( cck_to_string( cck_manifest_get( $manifest, 'version', '1.0.0' ) ) );
		$manifest['components']  = is_array( $components ) ? array_values( $components ) : array();
		$manifest['_path']       = $layout_path;

		return apply_filters( 'cck_layout_manifest', $manifest );
	}
}

if ( ! function_exists( 'cck_load_layout_manifest' ) ) {
	/**
	 * Layout manifest dosyasını yükler.
	 *
	 * @param string $layout_path Layout dosya yolu.
	 * @return array
	 */
	function cck_load_layout_manifest( $layout_path, $layout_id = '' ) {
		if ( ! is_string( $layout_path ) || ! file_exists( $layout_path ) ) {
			cck_debug_log( 'Layout dosyası bulunamadı.' );
			return array();
		}

		$layout_id    = sanitize_key( $layout_id ? $layout_id : basename( $layout_path, '.php' ) );
		$located_path = cck_locate_layout_manifest( $layout_id, $layout_path );

		if ( empty( $located_path ) || ! file_exists( $located_path ) ) {
			cck_debug_log( 'Layout manifest bulunamadı: ' . $layout_id );
			return array();
		}

		$manifest = require $located_path;

		return cck_validate_layout_manifest( $manifest, $located_path, $layout_id );
	}
}

if ( ! function_exists( 'cck_get_layout_registry' ) ) {
	/**
	 * Layout manifest dosyalarını otomatik tarar ve registry verisini döndürür.
	 *
	 * @return array
	 */
	function cck_get_layout_registry() {
		static $registry = null;

		if ( null !== $registry ) {
			return $registry;
		}

		$registry = array();

		foreach ( cck_get_layout_manifest_files() as $layout_path ) {
			$layout = cck_load_layout_manifest( $layout_path, basename( $layout_path, '.php' ) );

			if ( empty( $layout['id'] ) ) {
				continue;
			}

			$registry[ $layout['id'] ] = $layout;
		}

		return $registry;
	}
}

if ( ! function_exists( 'cck_get_layout' ) ) {
	/**
	 * Belirli bir layout manifest verisini döndürür.
	 *
	 * @param string $layout_id Layout kimliği.
	 * @return array|null
	 */
	function cck_get_layout( $layout_id ) {
		$layout_id = sanitize_key( $layout_id );
		$registry  = cck_get_layout_registry();

		return cck_array_get( $registry, $layout_id, null );
	}
}
