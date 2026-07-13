<?php
/**
 * Admin component helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_admin_components' ) ) {
	/**
	 * Get admin component registry.
	 *
	 * @return array
	 */
	function cck_get_admin_components() {
		return function_exists( 'cck_get_component_registry' ) ? cck_get_component_registry() : array();
	}
}

if ( ! function_exists( 'cck_get_admin_component_label' ) ) {
	/**
	 * Get component label for admin display.
	 *
	 * @param array|string $component Component metadata or label.
	 * @return string
	 */
	function cck_get_admin_component_label( $component ) {
		if ( is_array( $component ) ) {
			if ( ! empty( $component['name'] ) ) {
				return $component['name'];
			}

			if ( ! empty( $component['label'] ) ) {
				return $component['label'];
			}

			return isset( $component['id'] ) ? $component['id'] : '';
		}

		return (string) $component;
	}
}

if ( ! function_exists( 'cck_get_component_category_label' ) ) {
	/**
	 * Get component category label.
	 *
	 * @param string $category Component category.
	 * @return string
	 */
	function cck_get_component_category_label( $category ) {
		$labels = array(
			'ui'       => __( 'UI Component', 'craft-commerce-kit' ),
			'commerce' => __( 'Commerce Component', 'craft-commerce-kit' ),
		);

		return isset( $labels[ $category ] ) ? $labels[ $category ] : ucfirst( $category );
	}
}

if ( ! function_exists( 'cck_get_component_status_label' ) ) {
	/**
	 * Get component status label.
	 *
	 * @param string $status Component status.
	 * @return string
	 */
	function cck_get_component_status_label( $status ) {
		$labels = array(
			'active'   => __( 'Active', 'craft-commerce-kit' ),
			'planned'  => __( 'Planned', 'craft-commerce-kit' ),
			'disabled' => __( 'Disabled', 'craft-commerce-kit' ),
		);

		return isset( $labels[ $status ] ) ? $labels[ $status ] : ucfirst( $status );
	}
}