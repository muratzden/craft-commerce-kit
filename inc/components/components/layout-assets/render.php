<?php
/**
 * Layout Assets component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_layout_assets' ) ) {
	/**
	 * Render the Layout Assets component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_layout_assets( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		if ( function_exists( 'cck_component_layout_assets' ) ) {
			return cck_component_layout_assets( is_array( $atts ) ? $atts : array() );
		}

		return '';
	}
}