<?php
/**
 * Unified registry kernel.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_registry_store' ) ) {
	/**
	 * Get the shared registry store.
	 *
	 * @return array
	 */
	function &cck_registry_store() {
		static $store = array();

		return $store;
	}
}

if ( ! function_exists( 'cck_registry_register' ) ) {
	/**
	 * Register an item in the shared registry.
	 *
	 * @param string $type Registry type.
	 * @param string $id   Item ID.
	 * @param array  $data Item data.
	 * @return bool
	 */
	function cck_registry_register( $type, $id, $data ) {
		if ( ! is_string( $type ) || '' === trim( $type ) ) {
			return false;
		}

		if ( ! is_string( $id ) || '' === trim( $id ) ) {
			return false;
		}

		if ( ! is_array( $data ) ) {
			return false;
		}

		$type = sanitize_key( $type );
		$id   = sanitize_key( $id );

		$store =& cck_registry_store();

		if ( ! isset( $store[ $type ] ) ) {
			$store[ $type ] = array();
		}

		$store[ $type ][ $id ] = $data;

		return true;
	}
}

if ( ! function_exists( 'cck_registry_get' ) ) {
	/**
	 * Get an item from the shared registry.
	 *
	 * @param string $type Registry type.
	 * @param string $id   Item ID.
	 * @return array
	 */
	function cck_registry_get( $type, $id ) {
		if ( ! is_string( $type ) || '' === trim( $type ) ) {
			return array();
		}

		if ( ! is_string( $id ) || '' === trim( $id ) ) {
			return array();
		}

		$type = sanitize_key( $type );
		$id   = sanitize_key( $id );

		$store =& cck_registry_store();

		if ( ! isset( $store[ $type ][ $id ] ) ) {
			return array();
		}

		return $store[ $type ][ $id ];
	}
}

if ( ! function_exists( 'cck_registry_all' ) ) {
	/**
	 * Get all items for a registry type.
	 *
	 * @param string $type Registry type.
	 * @return array
	 */
	function cck_registry_all( $type ) {
		if ( ! is_string( $type ) || '' === trim( $type ) ) {
			return array();
		}

		$type = sanitize_key( $type );

		$store =& cck_registry_store();

		if ( ! isset( $store[ $type ] ) ) {
			return array();
		}

		return $store[ $type ];
	}
}

if ( ! function_exists( 'cck_registry_exists' ) ) {
	/**
	 * Check whether an item exists in the shared registry.
	 *
	 * @param string $type Registry type.
	 * @param string $id   Item ID.
	 * @return bool
	 */
	function cck_registry_exists( $type, $id ) {
		if ( ! is_string( $type ) || '' === trim( $type ) ) {
			return false;
		}

		if ( ! is_string( $id ) || '' === trim( $id ) ) {
			return false;
		}

		$type = sanitize_key( $type );
		$id   = sanitize_key( $id );

		$store =& cck_registry_store();

		return isset( $store[ $type ][ $id ] );
	}
}

if ( ! function_exists( 'cck_registry_remove' ) ) {
	/**
	 * Remove an item from the shared registry.
	 *
	 * @param string $type Registry type.
	 * @param string $id   Item ID.
	 * @return bool
	 */
	function cck_registry_remove( $type, $id ) {
		if ( ! is_string( $type ) || '' === trim( $type ) ) {
			return false;
		}

		if ( ! is_string( $id ) || '' === trim( $id ) ) {
			return false;
		}

		$type = sanitize_key( $type );
		$id   = sanitize_key( $id );

		$store =& cck_registry_store();

		if ( ! isset( $store[ $type ][ $id ] ) ) {
			return false;
		}

		unset( $store[ $type ][ $id ] );

		return true;
	}
}