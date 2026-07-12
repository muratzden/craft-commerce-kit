<?php
/**
 * WooCommerce runtime provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_provider_woo' ) ) {
	function cck_runtime_provider_woo( $key, $fallback = '', $context = array() ) {
		$key = is_string( $key ) ? sanitize_key( $key ) : '';

		if ( '' === $key ) {
			return $fallback;
		}

		if ( ! is_array( $context ) ) {
			$context = array();
		}

		if ( 'products' === $key && function_exists( 'cck_runtime_query_products' ) ) {
			$query = isset( $context['query'] ) && is_array( $context['query'] ) ? $context['query'] : array();

			return cck_runtime_query_products( $query );
		}

		return $fallback;
	}
}