<?php
/**
 * Hook registration loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'cck_enqueue_assets' ) ) {
	add_action( 'wp_enqueue_scripts', 'cck_enqueue_assets' );
}

if ( function_exists( 'cck_enqueue_assets_for_shortcode' ) ) {
	add_filter( 'pre_do_shortcode_tag', 'cck_enqueue_assets_for_shortcode', 10, 2 );
}

if ( function_exists( 'cck_print_design_tokens' ) ) {
	add_action( 'wp_head', 'cck_print_design_tokens', 5 );
}

if (
	function_exists( 'cck_is_woocommerce_active' )
	&& cck_is_woocommerce_active()
	&& function_exists( 'cck_register_product_trust_notes_shortcode' )
) {
	add_action( 'init', 'cck_register_product_trust_notes_shortcode' );
}

if ( is_admin() && function_exists( 'cck_register_admin_page' ) ) {
	add_action( 'admin_menu', 'cck_register_admin_page' );
}

if ( is_admin() && function_exists( 'cck_enqueue_admin_assets' ) ) {
	add_action( 'admin_enqueue_scripts', 'cck_enqueue_admin_assets' );
}

if ( function_exists( 'cck_render_global_header' ) ) {
	add_action( 'wp_body_open', 'cck_render_global_header', 20 );
}

if ( function_exists( 'cck_render_global_footer' ) ) {
	add_action( 'wp_footer', 'cck_render_global_footer', 20 );
}
