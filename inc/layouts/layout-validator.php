<?php
/**
 * Layout validator.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_validate_layout' ) ) {
	/**
	 * Validate and normalize a layout definition.
	 *
	 * @param mixed $layout Layout definition or registered layout ID.
	 * @return array|WP_Error
	 */
	function cck_validate_layout( $layout ) {
		if ( is_string( $layout ) ) {
			$layout_id = sanitize_key( $layout );
			$layout    = cck_get_layout( $layout_id );

			if ( empty( $layout ) ) {
				return new WP_Error(
					'cck_layout_not_found',
					sprintf(
						/* translators: %s: layout ID. */
						__( 'Layout not found: %s', 'craft-commerce-kit' ),
						$layout_id
					)
				);
			}
		}

		if ( ! is_array( $layout ) ) {
			return new WP_Error(
				'cck_invalid_layout',
				__( 'Layout definition must be an array.', 'craft-commerce-kit' )
			);
		}

		$layout_id = sanitize_key( cck_array_get( $layout, 'id', '' ) );

		if ( empty( $layout_id ) ) {
			return new WP_Error(
				'cck_layout_missing_id',
				__( 'Layout ID is required.', 'craft-commerce-kit' )
			);
		}

		$components = cck_array_get( $layout, 'components', array() );

		if ( ! is_array( $components ) ) {
			return new WP_Error(
				'cck_layout_invalid_components',
				__( 'Layout components must be an array.', 'craft-commerce-kit' )
			);
		}

		$normalized_components = array();

		foreach ( $components as $index => $component ) {
			$normalized = cck_normalize_layout_component( $component );

			if ( empty( $normalized['id'] ) ) {
				return new WP_Error(
					'cck_layout_component_missing_id',
					sprintf(
						/* translators: %d: component position. */
						__( 'Layout component at position %d has no valid ID.', 'craft-commerce-kit' ),
						(int) $index + 1
					)
				);
			}

			$manifest = function_exists( 'cck_get_component_manifest' )
				? cck_get_component_manifest( $normalized['id'] )
				: array();

			if ( ! is_array( $manifest ) || empty( $manifest['id'] ) ) {
				return new WP_Error(
					'cck_layout_component_not_found',
					sprintf(
						/* translators: %s: component ID. */
						__( 'Layout component is not registered: %s', 'craft-commerce-kit' ),
						$normalized['id']
					)
				);
			}

			$normalized_components[] = $normalized;
		}

		$layout['id']          = $layout_id;
		$layout['name']        = sanitize_text_field( cck_to_string( cck_array_get( $layout, 'name', $layout_id ) ) );
		$layout['description'] = sanitize_text_field( cck_to_string( cck_array_get( $layout, 'description', '' ) ) );
		$layout['version']     = sanitize_text_field( cck_to_string( cck_array_get( $layout, 'version', '1.0.0' ) ) );
		$layout['components']  = $normalized_components;

		return apply_filters( 'cck_validated_layout', $layout );
	}
}