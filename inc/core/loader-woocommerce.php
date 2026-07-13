<?php
/**
 * WooCommerce integration loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_is_woocommerce_active' ) ) {
	/**
	 * Check whether WooCommerce is active.
	 *
	 * @return bool
	 */
	function cck_is_woocommerce_active() {
		if ( class_exists( 'WooCommerce' ) ) {
			return true;
		}

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
			return true;
		}

		if ( is_multisite() ) {
			$network_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );

			return isset( $network_plugins['woocommerce/woocommerce.php'] );
		}

		return false;
	}
}

if ( cck_is_woocommerce_active() ) {
	require_once CCK_PLUGIN_DIR . 'inc/woocommerce/product-trust-notes.php';
	require_once CCK_PLUGIN_DIR . 'inc/demo/loader.php';
	require_once CCK_PLUGIN_DIR . 'inc/woocommerce/storefront.php';
}
