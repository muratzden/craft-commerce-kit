<?php
/**
 * Asset loading.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_enqueue_assets' ) ) {
	/**
	 * Enqueue public assets.
	 *
	 * @return void
	 */
	function cck_enqueue_assets() {
		$css_path = CCK_PLUGIN_DIR . 'assets/css/cck.css';
		$js_path  = CCK_PLUGIN_DIR . 'assets/js/cck.js';

		wp_enqueue_style(
			'craft-commerce-kit',
			CCK_PLUGIN_URL . 'assets/css/cck.css',
			array(),
			file_exists( $css_path ) ? filemtime( $css_path ) : CCK_VERSION
		);

		wp_enqueue_script(
			'craft-commerce-kit',
			CCK_PLUGIN_URL . 'assets/js/cck.js',
			array(),
			file_exists( $js_path ) ? filemtime( $js_path ) : CCK_VERSION,
			true
		);
	}
}

if ( ! function_exists( 'cck_enqueue_admin_assets' ) ) {
	/**
	 * Enqueue admin console assets.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	function cck_enqueue_admin_assets( $hook_suffix ) {
		$admin_pages = array(
			'toplevel_page_craft-commerce-kit',
			'craft-commerce-kit_page_craft-commerce-kit-components',
			'craft-commerce-kit_page_craft-commerce-kit-templates',
			'craft-commerce-kit_page_craft-commerce-kit-brand',
			'craft-commerce-kit_page_craft-commerce-kit-commerce',
			'craft-commerce-kit_page_craft-commerce-kit-system',
		);

		if ( ! in_array( $hook_suffix, $admin_pages, true ) ) {
			return;
		}

		$css_path = CCK_PLUGIN_DIR . 'assets/css/admin.css';
		$js_path  = CCK_PLUGIN_DIR . 'assets/js/admin.js';

		wp_enqueue_style(
			'craft-commerce-kit-admin',
			CCK_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			file_exists( $css_path ) ? filemtime( $css_path ) : CCK_VERSION
		);

		wp_enqueue_script(
			'craft-commerce-kit-admin',
			CCK_PLUGIN_URL . 'assets/js/admin.js',
			array(),
			file_exists( $js_path ) ? filemtime( $js_path ) : CCK_VERSION,
			true
		);
	}
}


