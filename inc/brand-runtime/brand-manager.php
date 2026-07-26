<?php
/**
 * Brand runtime manager.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_register_brand' ) ) {
	function cck_register_brand( $brand_id, array $definition ) {
		$brand_id = sanitize_key( $brand_id );

		if ( '' === $brand_id || ! function_exists( 'cck_registry_register' ) ) {
			return false;
		}

		$definition['id'] = $brand_id;

		return cck_registry_register( 'brand', $brand_id, $definition );
	}
}

if ( ! function_exists( 'cck_get_brand' ) ) {
	function cck_get_brand( $brand_id ) {
		$brand_id = sanitize_key( $brand_id );

		if ( '' === $brand_id || ! function_exists( 'cck_registry_get' ) ) {
			return array();
		}

		return cck_registry_get( 'brand', $brand_id );
	}
}

if ( ! function_exists( 'cck_get_saved_brand_profile_runtime' ) ) {
	/**
	 * Get the saved single-brand profile for runtime use.
	 *
	 * @return array
	 */
	function cck_get_saved_brand_profile_runtime() {
		$profile = get_option( 'cck_brand_profile', array() );

		if ( ! is_array( $profile ) ) {
			return array();
		}

		$profile['id'] = isset( $profile['id'] )
			? sanitize_key( $profile['id'] )
			: '';

		$profile['brand_name'] = isset( $profile['brand_name'] )
			? sanitize_text_field( $profile['brand_name'] )
			: '';

		if ( '' === $profile['id'] || '' === $profile['brand_name'] ) {
			return array();
		}

		if ( isset( $profile['experience'] ) ) {
			$profile['experience'] = sanitize_key(
				$profile['experience']
			);
		}

		return $profile;
	}
}
if ( ! function_exists( 'cck_get_active_brand_id' ) ) {
	function cck_get_active_brand_id( $context = array() ) {
		if ( is_array( $context ) && ! empty( $context['brand_id'] ) ) {
			$brand_id = sanitize_key( $context['brand_id'] );

			if ( ! empty( cck_get_brand( $brand_id ) ) ) {
				return $brand_id;
			}
		}

		if ( is_array( $context ) && ! empty( $context['experience'] ) ) {
			$experience = sanitize_key( $context['experience'] );

			foreach ( cck_registry_all( 'brand' ) as $brand_id => $brand ) {
				if (
					is_array( $brand ) &&
					isset( $brand['experience'] ) &&
					$experience === sanitize_key( $brand['experience'] )
				) {
					return $brand_id;
				}
			}
		}

		$profile = cck_get_saved_brand_profile_runtime();

		if ( ! empty( $profile ) ) {
			return $profile['id'];
		}

		$brand_id = sanitize_key(
			get_option( 'cck_active_brand', 'tilla-leather' )
		);

		if ( '' !== $brand_id && ! empty( cck_get_brand( $brand_id ) ) ) {
			return $brand_id;
		}

		return 'default';
	}
}

if ( ! function_exists( 'cck_get_active_brand' ) ) {
	function cck_get_active_brand( $context = array() ) {
		$brand_id = cck_get_active_brand_id( $context );
		$profile  = cck_get_saved_brand_profile_runtime();

		if (
			! empty( $profile ) &&
			isset( $profile['id'] ) &&
			$brand_id === $profile['id']
		) {
			return $profile;
		}

		return cck_get_brand( $brand_id );
	}
}

if ( ! function_exists( 'cck_resolve_brand_attributes' ) ) {
	function cck_resolve_brand_attributes( $brand_id, array $context = array() ) {
		unset( $context );

		$brand_id = sanitize_key( $brand_id );
		$profile  = cck_get_saved_brand_profile_runtime();

		if (
			! empty( $profile ) &&
			isset( $profile['id'] ) &&
			$brand_id === $profile['id']
		) {
			$brand = $profile;
		} else {
			$brand = cck_get_brand( $brand_id );
		}

		if ( empty( $brand ) ) {
			return array();
		}

		return isset( $brand['attributes'] ) && is_array( $brand['attributes'] )
			? $brand['attributes']
			: $brand;
	}
}

if ( ! function_exists( 'cck_resolve_brand_attribute' ) ) {
	function cck_resolve_brand_attribute( $brand, $key, $fallback = '' ) {
		if ( ! is_array( $brand ) || empty( $key ) ) {
			return $fallback;
		}

		return isset( $brand[ $key ] ) ? $brand[ $key ] : $fallback;
	}
}

if ( ! function_exists( 'cck_merge_section_attributes' ) ) {
	function cck_merge_section_attributes( $definition ) {
		$attributes = isset( $definition['attributes'] ) && is_array( $definition['attributes'] ) ? $definition['attributes'] : array();

		if ( empty( $definition['brand'] ) || ! is_array( $definition['brand'] ) ) {
			return $attributes;
		}

		$brand = cck_get_active_brand();

		foreach ( $definition['brand'] as $target => $source ) {
			$target = sanitize_key( $target );

			if ( '' === $target || empty( $source ) ) {
				continue;
			}

			$attributes[ $target ] = cck_resolve_brand_attribute( $brand, $source, isset( $attributes[ $target ] ) ? $attributes[ $target ] : '' );
		}

		return $attributes;
	}
}
