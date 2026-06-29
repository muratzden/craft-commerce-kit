<?php
/**
 * Plugin loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

require_once CCK_PLUGIN_DIR . 'inc/core/helpers.php';
require_once CCK_PLUGIN_DIR . 'inc/core/assets.php';
require_once CCK_PLUGIN_DIR . 'inc/core/tokens.php';

require_once CCK_PLUGIN_DIR . 'inc/brand-packs/tilla-leather.php';
require_once CCK_PLUGIN_DIR . 'inc/templates/registry.php';
require_once CCK_PLUGIN_DIR . 'inc/components/component-interface.php';
require_once CCK_PLUGIN_DIR . 'inc/components/manifest-validator.php';
require_once CCK_PLUGIN_DIR . 'inc/components/registry.php';
require_once CCK_PLUGIN_DIR . 'inc/components/renderer.php';
require_once CCK_PLUGIN_DIR . 'inc/components/settings-renderer.php';

require_once CCK_PLUGIN_DIR . 'inc/components/hero.php';
require_once CCK_PLUGIN_DIR . 'inc/components/section-title.php';
require_once CCK_PLUGIN_DIR . 'inc/components/trust-block.php';
require_once CCK_PLUGIN_DIR . 'inc/components/image-text.php';
require_once CCK_PLUGIN_DIR . 'inc/components/cta.php';
require_once CCK_PLUGIN_DIR . 'inc/components/collection-grid.php';

require_once CCK_PLUGIN_DIR . 'inc/shortcodes/shortcodes.php';

if ( ! function_exists( 'cck_is_woocommerce_active' ) ) {
	/**
	 * WooCommerce eklentisinin aktif olup olmad???n? kontrol eder.
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
}

if ( is_admin() ) {
	require_once CCK_PLUGIN_DIR . 'inc/admin/admin-page.php';
}

add_action( 'wp_enqueue_scripts', 'cck_enqueue_assets' );
add_filter( 'pre_do_shortcode_tag', 'cck_enqueue_assets_for_shortcode', 10, 2 );
add_action( 'wp_head', 'cck_print_design_tokens', 5 );
add_action( 'init', 'cck_register_shortcodes' );

if ( cck_is_woocommerce_active() && function_exists( 'cck_register_product_trust_notes_shortcode' ) ) {
	add_action( 'init', 'cck_register_product_trust_notes_shortcode' );
}

if ( is_admin() && function_exists( 'cck_register_admin_page' ) ) {
	add_action( 'admin_menu', 'cck_register_admin_page' );
	add_action( 'admin_enqueue_scripts', 'cck_enqueue_admin_assets' );
}


