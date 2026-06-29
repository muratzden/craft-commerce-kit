<?php
/**
 * Component registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_registry' ) ) {
	/**
	 * Get reusable storefront component registry.
	 *
	 * @return array
	 */
	function cck_get_component_registry() {
		return array(
			'hero'         => array(
				'id'          => 'hero',
				'label'       => __( 'Hero', 'craft-commerce-kit' ),
				'description' => __( 'Premium storefront hero section.', 'craft-commerce-kit' ),
				'category'    => 'ui',
				'status'      => 'active',
				'callback'    => 'cck_component_engine_render_hero',
			),
			'usp'          => array(
				'id'          => 'usp',
				'label'       => __( 'USP', 'craft-commerce-kit' ),
				'description' => __( 'Three-column unique selling proposition section.', 'craft-commerce-kit' ),
				'category'    => 'ui',
				'status'      => 'active',
				'callback'    => 'cck_component_engine_render_usp',
			),
			'product_grid' => array(
				'id'          => 'product_grid',
				'label'       => __( 'Product Grid', 'craft-commerce-kit' ),
				'description' => __( 'Latest WooCommerce products grid.', 'craft-commerce-kit' ),
				'category'    => 'commerce',
				'status'      => 'active',
				'callback'    => 'cck_component_engine_render_product_grid',
			),
		);
	}
}

if ( ! function_exists( 'cck_get_components' ) ) {
	/**
	 * Get components for existing admin integrations.
	 *
	 * @return array
	 */
	function cck_get_components() {
		return cck_get_component_registry();
	}
}
