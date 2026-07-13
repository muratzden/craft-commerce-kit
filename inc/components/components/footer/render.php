<?php
/**
 * Footer component render file.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_footer' ) ) {
	/**
	 * Footer component output.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_footer( $atts = array(), $manifest = array() ) {
		if ( function_exists( 'cck_component_footer' ) ) {
			return cck_component_footer( $atts );
		}

		return '';
	}
}
