<?php
/**
 * Brand Preset component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_brand_preset' ) ) {
	/**
	 * Render the Brand Preset component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_brand_preset( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		if ( function_exists( 'cck_component_brand_preset' ) ) {
			return cck_component_brand_preset( is_array( $atts ) ? $atts : array() );
		}

		return '';
	}
}