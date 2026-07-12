<?php
/**
 * Runtime attribute resolver.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_resolve_binding' ) ) {
	function cck_resolve_binding( $binding, $fallback = '', $context = array() ) {
		if ( ! is_string( $binding ) || '' === trim( $binding ) ) {
			return $fallback;
		}

		if ( ! is_array( $context ) ) {
			$context = array();
		}

		$parts = explode( '.', $binding, 2 );

		if ( 2 !== count( $parts ) ) {
			return $fallback;
		}

		$provider = sanitize_key( $parts[0] );
		$key      = sanitize_key( $parts[1] );

		if ( '' === $provider || '' === $key ) {
			return $fallback;
		}

		if ( function_exists( 'cck_runtime_dispatch_provider' ) ) {
			return cck_runtime_dispatch_provider( $provider, $key, $fallback, $context );
		}

		return $fallback;
	}
}

if ( ! function_exists( 'cck_resolve_bindings' ) ) {
	function cck_resolve_bindings( $bindings, $attributes = array(), $context = array() ) {
		if ( ! is_array( $attributes ) ) {
			$attributes = array();
		}

		if ( ! is_array( $context ) ) {
			$context = array();
		}

		if ( ! is_array( $bindings ) ) {
			return $attributes;
		}

		foreach ( $bindings as $target => $binding ) {
			$target = sanitize_key( $target );

			if ( '' === $target ) {
				continue;
			}

			$fallback = array_key_exists( $target, $attributes ) ? $attributes[ $target ] : '';
			$value    = cck_resolve_binding( $binding, $fallback, $context );

			if ( is_array( $value ) && ! is_array( $fallback ) ) {
				continue;
			}

			if ( is_object( $value ) || is_resource( $value ) ) {
				continue;
			}

			$attributes[ $target ] = $value;
		}

		return $attributes;
	}
}

if ( ! function_exists( 'cck_merge_attributes' ) ) {
	function cck_merge_attributes( $definition, $context = array() ) {
		if ( ! is_array( $definition ) ) {
			return array();
		}

		$attributes = isset( $definition['attributes'] ) && is_array( $definition['attributes'] ) ? $definition['attributes'] : array();
		$bindings   = isset( $definition['bindings'] ) && is_array( $definition['bindings'] ) ? $definition['bindings'] : array();
		$context    = is_array( $context ) ? array_merge( $definition, $context ) : $definition;

		return cck_resolve_bindings( $bindings, $attributes, $context );
	}
}
