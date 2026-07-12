<?php
/**
 * Runtime provider dispatcher.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_dispatch_provider' ) ) {
	function cck_runtime_dispatch_provider( $provider, $key, $fallback = '', $context = array() ) {
		$provider = is_string( $provider ) ? sanitize_key( $provider ) : '';
		$key      = is_string( $key ) ? sanitize_key( $key ) : '';

		if ( '' === $provider || '' === $key ) {
			return $fallback;
		}

		if ( ! is_array( $context ) ) {
			$context = array();
		}

		$callback = 'cck_runtime_provider_' . $provider;

		if ( function_exists( $callback ) ) {
			return call_user_func( $callback, $key, $fallback, $context );
		}

		return $fallback;
	}
}