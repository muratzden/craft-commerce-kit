<?php
/**
 * Asset loading.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_frontend_shortcodes' ) ) {
	/**
	 * Get shortcodes that require frontend assets.
	 *
	 * @return array
	 */
	function cck_get_frontend_shortcodes() {
		return array(
			'cck_tilla_home',
			'cck_hero',
			'cck_section_title',
			'cck_trust_block',
			'cck_image_text',
			'cck_cta',
			'cck_collection_grid',
			'cck_product_trust_notes',
			'cck_component',
			'cck_layout',
		);
	}
}

if ( ! function_exists( 'cck_content_has_frontend_shortcode' ) ) {
	/**
	 * Check whether content contains a CCK shortcode.
	 *
	 * @param string $content Post content.
	 * @return bool
	 */
	function cck_content_has_frontend_shortcode( $content ) {
		if ( ! is_string( $content ) || '' === $content ) {
			return false;
		}

		foreach ( cck_get_frontend_shortcodes() as $shortcode ) {
			if ( has_shortcode( $content, $shortcode ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'cck_should_enqueue_frontend_assets' ) ) {
	/**
	 * Determine whether frontend assets should be loaded on this request.
	 *
	 * @return bool
	 */
	function cck_should_enqueue_frontend_assets() {
		if ( is_admin() || wp_doing_ajax() ) {
			return false;
		}

		if ( is_singular() ) {
			$post = get_post();

			return $post instanceof WP_Post && cck_content_has_frontend_shortcode( $post->post_content );
		}

		return false;
	}
}

if ( ! function_exists( 'cck_enqueue_frontend_assets' ) ) {
	/**
	 * Enqueue public assets once per request.
	 *
	 * @return void
	 */
	function cck_enqueue_frontend_assets() {
		static $enqueued = false;

		if ( $enqueued ) {
			return;
		}

		$enqueued = true;

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

if ( ! function_exists( 'cck_enqueue_assets' ) ) {
	/**
	 * Enqueue public assets when a CCK render target is present.
	 *
	 * @return void
	 */
	function cck_enqueue_assets() {
		if ( cck_should_enqueue_frontend_assets() ) {
			cck_enqueue_frontend_assets();
		}
	}
}

if ( ! function_exists( 'cck_enqueue_assets_for_shortcode' ) ) {
	/**
	 * Enqueue frontend assets just before a CCK shortcode renders.
	 *
	 * @param mixed  $return Short-circuit return value.
	 * @param string $tag    Shortcode tag.
	 * @return mixed
	 */
	function cck_enqueue_assets_for_shortcode( $return, $tag ) {
		if ( in_array( $tag, cck_get_frontend_shortcodes(), true ) ) {
			cck_enqueue_frontend_assets();
		}

		return $return;
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
			'craft-commerce-kit_page_craft-commerce-kit-layouts',
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
