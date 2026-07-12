<?php
/**
 * Experience Registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_registry_register' ) ) {
	require_once CCK_PLUGIN_DIR . 'inc/registry/registry.php';
}

if ( ! function_exists( 'cck_register_experience' ) ) {

	/**
	 * Register an experience.
	 *
	 * @param string $id       Experience ID.
	 * @param array  $manifest Experience manifest.
	 * @return void
	 */
	function cck_register_experience( $id, array $manifest ) {
		$id = sanitize_key( $id );

		if ( '' === $id ) {
			return;
		}

		cck_registry_register( 'experience', $id, $manifest );

		$GLOBALS['cck_runtime_experiences'] = cck_registry_all( 'experience' );
	}
}

if ( ! function_exists( 'cck_get_experience' ) ) {

	/**
	 * Get a registered experience.
	 *
	 * @param string $id Experience ID.
	 * @return array
	 */
	function cck_get_experience( $id ) {
		$id = sanitize_key( $id );

		if ( '' === $id ) {
			return array();
		}

		return cck_registry_get( 'experience', $id );
	}
}

if ( ! function_exists( 'cck_get_experiences' ) ) {

	/**
	 * Get all registered experiences.
	 *
	 * @return array
	 */
	function cck_get_experiences() {
		return cck_registry_all( 'experience' );
	}
}