<?php
/**
 * Brand runtime provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_provider_brand' ) ) {
	function cck_runtime_provider_brand( $key, $fallback = '', $context = array() ) {
		$key = is_string( $key ) ? sanitize_key( $key ) : '';

		if ( '' === $key ) {
			return $fallback;
		}

		$brand = function_exists( 'cck_get_active_brand' ) ? cck_get_active_brand( $context ) : array();

		if ( is_array( $brand ) && array_key_exists( $key, $brand ) ) {
			return $brand[ $key ];
		}

		return $fallback;
	}
}
