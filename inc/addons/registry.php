<?php
/**
 * Add-on registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_addon_registry' ) ) {
	/**
	 * Return the registered standalone CCK add-ons.
	 *
	 * @return array
	 */
	function cck_get_addon_registry() {
		$addons = array(
			'cck-delivery-estimate-options' => array(
				'id'               => 'cck-delivery-estimate-options',
				'name'             => __( 'CCK Delivery Estimate Options for WooCommerce', 'craft-commerce-kit' ),
				'plugin_file'      => 'cck-delivery-estimate-options/cck-delivery-estimate-options.php',
				'text_domain'      => 'cck-delivery-estimate-options',
				'requires_plugins' => array( 'woocommerce' ),
			),
			'cck-gift-package-options' => array(
				'id'               => 'cck-gift-package-options',
				'name'             => __( 'CCK Gift Package Options for WooCommerce', 'craft-commerce-kit' ),
				'plugin_file'      => 'cck-gift-package-options/cck-gift-package-options.php',
				'text_domain'      => 'cck-gift-package-options',
				'requires_plugins' => array( 'woocommerce' ),
			),
			'cck-leather-color-options' => array(
				'id'               => 'cck-leather-color-options',
				'name'             => __( 'CCK Leather Color Options for WooCommerce', 'craft-commerce-kit' ),
				'plugin_file'      => 'cck-leather-color-options/cck-leather-color-options.php',
				'text_domain'      => 'cck-leather-color-options',
				'requires_plugins' => array( 'woocommerce' ),
			),
			'cck-personalization-options' => array(
				'id'               => 'cck-personalization-options',
				'name'             => __( 'CCK Personalization Options for WooCommerce', 'craft-commerce-kit' ),
				'plugin_file'      => 'cck-personalization-options/cck-personalization-options.php',
				'text_domain'      => 'cck-personalization-options',
				'requires_plugins' => array( 'woocommerce' ),
			),
			'cck-quick-view-options' => array(
				'id'               => 'cck-quick-view-options',
				'name'             => __( 'CCK Quick View Options for WooCommerce', 'craft-commerce-kit' ),
				'plugin_file'      => 'cck-quick-view-options/cck-quick-view-options.php',
				'text_domain'      => 'cck-quick-view-options',
				'requires_plugins' => array( 'woocommerce' ),
			),
			'cck-wishlist-options' => array(
				'id'               => 'cck-wishlist-options',
				'name'             => __( 'CCK Wishlist Options for WooCommerce', 'craft-commerce-kit' ),
				'plugin_file'      => 'cck-wishlist-options/cck-wishlist-options.php',
				'text_domain'      => 'cck-wishlist-options',
				'requires_plugins' => array( 'woocommerce' ),
			),
		);

		return apply_filters( 'cck_addon_registry', $addons );
	}
}

if ( ! function_exists( 'cck_get_addon' ) ) {
	/**
	 * Return one registered add-on.
	 *
	 * @param string $addon_id Add-on identifier.
	 * @return array|null
	 */
	function cck_get_addon( $addon_id ) {
		$addon_id = sanitize_key( $addon_id );
		$addons   = cck_get_addon_registry();

		return isset( $addons[ $addon_id ] ) ? $addons[ $addon_id ] : null;
	}
}
