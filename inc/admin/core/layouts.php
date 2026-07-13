<?php
/**
 * Admin layout helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_admin_layout_display_data' ) ) {
	/**
	 * Prepare layout display data for admin screens.
	 *
	 * @param array $layout Layout manifest data.
	 * @return array
	 */
	function cck_get_admin_layout_display_data( $layout ) {
		$layout_id     = isset( $layout['id'] ) ? sanitize_key( $layout['id'] ) : '';
		$name          = isset( $layout['label'] ) ? $layout['label'] : ( isset( $layout['name'] ) ? $layout['name'] : $layout_id );
		$description   = isset( $layout['description'] ) ? $layout['description'] : '';
		$version       = isset( $layout['version'] ) ? $layout['version'] : CCK_VERSION;
		$components    = isset( $layout['components'] ) && is_array( $layout['components'] ) ? $layout['components'] : array();
		$component_ids = array();

		foreach ( $components as $component ) {
			$normalized = cck_normalize_layout_component( $component );

			if ( ! empty( $normalized['id'] ) ) {
				$component_ids[] = $normalized['id'];
			}
		}

		return array(
			'id'            => $layout_id,
			'name'          => $name,
			'description'   => $description,
			'version'       => $version,
			'component_ids' => $component_ids,
			'shortcode'     => '[cck_layout id="' . $layout_id . '"]',
		);
	}
}