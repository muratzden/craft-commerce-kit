<?php
/**
 * Admin navigation helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_admin_nav_items' ) ) {
	/**
	 * Get workspace navigation items.
	 *
	 * @return array
	 */
	function cck_get_admin_nav_items() {
		$items = array();

		foreach ( cck_get_admin_screen_ids() as $screen_id ) {
			$screen = cck_get_admin_screen( $screen_id );

			if ( empty( $screen['slug'] ) || empty( $screen['label'] ) ) {
				continue;
			}

			$items[ $screen['slug'] ] = $screen['label'];
		}

		return $items;
	}
}

if ( ! function_exists( 'cck_get_current_admin_page' ) ) {
	/**
	 * Get current CCK admin page slug.
	 *
	 * @return string
	 */
	function cck_get_current_admin_page() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		$page   = 'craft-commerce-kit';

		if ( $screen && ! empty( $screen->id ) ) {
			$page = str_replace( 'craft-commerce-kit_page_', '', $screen->id );
			$page = str_replace( 'toplevel_page_', '', $page );
		}

		return array_key_exists( $page, cck_get_admin_nav_items() ) ? $page : 'craft-commerce-kit';
	}
}
