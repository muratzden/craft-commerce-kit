<?php
/**
 * Header component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_header' ) ) {
	/**
	 * Render the Header component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_header( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		if ( function_exists( 'cck_component_header' ) ) {
			return cck_component_header( is_array( $atts ) ? $atts : array() );
		}

		return '';
	}
}