<?php
/**
 * Layout composer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_compose_layout' ) ) {
	/**
	 * Build a render queue from a layout definition.
	 *
	 * @param mixed $layout Layout definition or registered layout ID.
	 * @param array $data   Reserved composition data and future overrides.
	 * @return array|WP_Error
	 */
	function cck_compose_layout( $layout, $data = array() ) {
		$validated = cck_validate_layout( $layout );

		if ( is_wp_error( $validated ) ) {
			return $validated;
		}

		$queue = array();

		foreach ( $validated['components'] as $component ) {
			$component_id = sanitize_key( cck_array_get( $component, 'id', '' ) );
			$input_atts   = cck_array_get( $component, 'atts', array() );
			$input_atts   = is_array( $input_atts ) ? $input_atts : array();
			$defaults     = function_exists( 'cck_get_component_defaults' )
				? cck_get_component_defaults( $component_id )
				: array();

			$defaults = is_array( $defaults ) ? $defaults : array();

			$queue[] = array(
				'id'   => $component_id,
				'atts' => wp_parse_args( $input_atts, $defaults ),
			);
		}

		/**
		 * Filter the final render queue.
		 *
		 * @param array $queue     Normalized component render queue.
		 * @param array $validated Validated layout definition.
		 * @param array $data      Composition context data.
		 */
		return apply_filters( 'cck_composed_layout', $queue, $validated, (array) $data );
	}
}