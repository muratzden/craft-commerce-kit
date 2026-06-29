<?php
/**
 * Component registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_packages_path' ) ) {
	/**
	 * Get the component package directory.
	 *
	 * @return string
	 */
	function cck_get_component_packages_path() {
		return CCK_PLUGIN_DIR . 'inc/components/components/';
	}
}

if ( ! function_exists( 'cck_normalize_component_manifest' ) ) {
	/**
	 * Normalize a component manifest for framework APIs.
	 *
	 * @param array  $manifest      Component manifest.
	 * @param string $component_dir Component directory.
	 * @return array
	 */
	function cck_normalize_component_manifest( $manifest, $component_dir ) {
		$component_id = isset( $manifest['id'] ) ? sanitize_key( $manifest['id'] ) : '';

		if ( empty( $component_id ) ) {
			return array();
		}

		$manifest['id']          = $component_id;
		$manifest['name']        = isset( $manifest['name'] ) ? $manifest['name'] : $component_id;
		$manifest['label']       = $manifest['name'];
		$manifest['description'] = isset( $manifest['description'] ) ? $manifest['description'] : '';
		$manifest['category']    = isset( $manifest['category'] ) ? sanitize_key( $manifest['category'] ) : 'ui';
		$manifest['version']     = isset( $manifest['version'] ) ? $manifest['version'] : '1.0.0';
		$manifest['supports']    = isset( $manifest['supports'] ) && is_array( $manifest['supports'] ) ? array_values( array_map( 'sanitize_key', $manifest['supports'] ) ) : array();
		$manifest['_path']       = trailingslashit( $component_dir );
		$manifest['_render']     = trailingslashit( $component_dir ) . 'render.php';

		return $manifest;
	}
}

if ( ! function_exists( 'cck_get_component_registry' ) ) {
	/**
	 * Get reusable storefront component registry from package manifests.
	 *
	 * @return array
	 */
	function cck_get_component_registry() {
		static $registry = null;

		if ( null !== $registry ) {
			return $registry;
		}

		$registry       = array();
		$component_dirs = glob( cck_get_component_packages_path() . '*/manifest.php' );

		if ( ! is_array( $component_dirs ) ) {
			return $registry;
		}

		sort( $component_dirs );

		foreach ( $component_dirs as $manifest_path ) {
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
	 * Get a component manifest by ID.
	 *
	 * @param string $component_id Component ID.
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
	 * Get component package data by ID.
	 *
	 * @param string $component_id Component ID.
	 * @return array|null
	 */
	function cck_get_component( $component_id ) {
		return cck_get_component_manifest( $component_id );
	}
}

if ( ! function_exists( 'cck_get_components' ) ) {
	/**
	 * Get components for existing admin integrations.
	 *
	 * @return array
	 */
	function cck_get_components() {
		return cck_get_component_registry();
	}
}
