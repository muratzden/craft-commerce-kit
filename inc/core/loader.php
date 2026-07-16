<?php
/**
 * Plugin loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

/*
 * Bootstrap / Core APIs
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-core.php';

/*
 * Runtime Kernel
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-runtime.php';

/*
 * Rendering
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-rendering.php';

/*
 * Brand Runtime
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-brand-runtime.php';

/*
 * Experience Packs
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-experiences.php';

/*
 * Layouts and Shortcodes
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-shortcodes.php';

/*
 * WooCommerce Integration
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-woocommerce.php';

/*
 * Admin
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-admin.php';

/*
 * Hooks
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-hooks.php';

if ( ! function_exists( 'cck_should_load_layout_loader' ) ) {
	/**
	 * Determine whether the layout loader is needed for the current request.
	 *
	 * @return bool
	 */
	function cck_should_load_layout_loader() {
		if ( is_admin() || wp_doing_ajax() ) {
			return true;
		}

		if ( ! is_singular() ) {
			return false;
		}

		$post = get_queried_object();

		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		return false !== strpos( (string) $post->post_content, '[cck_layout' );
	}
}

if ( ! function_exists( 'cck_maybe_load_layout_loader' ) ) {
	/**
	 * Load the layout layer only when a layout shortcode is present on the request.
	 *
	 * @return void
	 */
	function cck_maybe_load_layout_loader() {
		static $loaded = false;

		if ( $loaded || cck_should_load_layout_loader() ) {
			$loaded = true;
			require_once CCK_PLUGIN_DIR . 'inc/core/loader-layouts.php';
		}
	}
}

if ( is_admin() || wp_doing_ajax() ) {
	require_once CCK_PLUGIN_DIR . 'inc/core/loader-layouts.php';
} else {
	add_action( 'wp', 'cck_maybe_load_layout_loader', 0 );
}
