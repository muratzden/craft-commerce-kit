<?php
/**
 * Shared component surface contract.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_get_surface_options' ) ) {
	/**
	 * Return supported component surface options.
	 *
	 * @return array<string, string>
	 */
	function cck_component_get_surface_options() {
		return array(
			'transparent' => __( 'Transparent', 'craft-commerce-kit' ),
			'background'  => __( 'Brand Background', 'craft-commerce-kit' ),
			'surface'     => __( 'Surface', 'craft-commerce-kit' ),
			'surface-alt' => __( 'Alternate Surface', 'craft-commerce-kit' ),
			'dark'        => __( 'Dark', 'craft-commerce-kit' ),
		);
	}
}

if ( ! function_exists( 'cck_component_normalize_surface' ) ) {
	/**
	 * Validate a surface value and apply a fallback.
	 *
	 * @param mixed  $surface  Surface value.
	 * @param string $fallback Fallback surface.
	 * @return string
	 */
	function cck_component_normalize_surface( $surface, $fallback = 'background' ) {
		$allowed = array_keys( cck_component_get_surface_options() );

		if ( ! in_array( $fallback, $allowed, true ) ) {
			$fallback = 'background';
		}

		$surface = is_string( $surface )
			? sanitize_key( $surface )
			: '';

		return in_array( $surface, $allowed, true )
			? $surface
			: $fallback;
	}
}

if ( ! function_exists( 'cck_component_get_surface_classes' ) ) {
	/**
	 * Build standard surface CSS classes.
	 *
	 * @param mixed  $surface  Surface value.
	 * @param string $fallback Fallback surface.
	 * @return array<int, string>
	 */
	function cck_component_get_surface_classes( $surface, $fallback = 'background' ) {
		$surface = cck_component_normalize_surface( $surface, $fallback );

		return array(
			'cck-surface',
			'cck-surface--' . $surface,
		);
	}
}
