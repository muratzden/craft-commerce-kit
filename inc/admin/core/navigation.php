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
		$registry = cck_get_admin_screen_registry();
		$page     = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

		if ( '' !== $page ) {
			foreach ( $registry as $screen ) {
				if ( empty( $screen['slug'] ) || $page !== $screen['slug'] ) {
					continue;
				}

				if ( ! empty( $screen['hidden'] ) ) {
					return 'craft-commerce-kit-components';
				}

				return $screen['slug'];
			}
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && ! empty( $screen->id ) ) {
			$page = str_replace( 'craft-commerce-kit_page_', '', $screen->id );
			$page = str_replace( 'toplevel_page_', '', $page );

			foreach ( $registry as $screen_item ) {
				if ( empty( $screen_item['slug'] ) || $page !== $screen_item['slug'] ) {
					continue;
				}

				if ( ! empty( $screen_item['hidden'] ) ) {
					return 'craft-commerce-kit-components';
				}

				return $screen_item['slug'];
			}
		}

		return 'craft-commerce-kit';
	}
}
