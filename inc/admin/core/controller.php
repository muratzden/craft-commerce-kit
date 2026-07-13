<?php
/**
 * Admin screen controller.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_admin_screen_registry' ) ) {
	/**
	 * Get the canonical admin screen registry.
	 *
	 * @return array
	 */
	function cck_get_admin_screen_registry() {
		return array(
			'overview' => array(
				'id'          => 'overview',
				'label'       => __( 'Overview', 'craft-commerce-kit' ),
				'page_title'  => __( 'Overview', 'craft-commerce-kit' ),
				'description' => __( 'Plugin status, catalog counts, and runtime health at a glance.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit',
				'callback'    => 'cck_render_admin_page',
			),
			'components' => array(
				'id'          => 'components',
				'label'       => __( 'Components', 'craft-commerce-kit' ),
				'page_title'  => __( 'Components', 'craft-commerce-kit' ),
				'description' => __( 'Read-only catalog of registered component metadata.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-components',
				'callback'    => 'cck_render_components_page',
			),
			'experiences' => array(
				'id'          => 'experiences',
				'label'       => __( 'Experiences', 'craft-commerce-kit' ),
				'page_title'  => __( 'Experiences', 'craft-commerce-kit' ),
				'description' => __( 'Read-only catalog of registered experience metadata.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-experiences',
				'callback'    => 'cck_render_experiences_page',
			),
			'brands' => array(
				'id'          => 'brands',
				'label'       => __( 'Brands', 'craft-commerce-kit' ),
				'page_title'  => __( 'Brands', 'craft-commerce-kit' ),
				'description' => __( 'Registered brand catalog and active brand state.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-brands',
				'callback'    => 'cck_render_brand_page',
			),
			'settings' => array(
				'id'          => 'settings',
				'label'       => __( 'Settings', 'craft-commerce-kit' ),
				'page_title'  => __( 'Settings', 'craft-commerce-kit' ),
				'description' => __( 'Available configurable settings and security-safe defaults.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-settings',
				'callback'    => 'cck_render_settings_page',
			),
		);
	}
}

if ( ! function_exists( 'cck_get_admin_screen_ids' ) ) {
	/**
	 * Get admin screen IDs in canonical order.
	 *
	 * @return array
	 */
	function cck_get_admin_screen_ids() {
		return array_keys( cck_get_admin_screen_registry() );
	}
}

if ( ! function_exists( 'cck_get_admin_screen' ) ) {
	/**
	 * Get a single admin screen definition.
	 *
	 * @param string $screen_id Screen ID.
	 * @return array
	 */
	function cck_get_admin_screen( $screen_id ) {
		$screen_id = sanitize_key( $screen_id );

		if ( '' === $screen_id ) {
			return array();
		}

		$registry = cck_get_admin_screen_registry();

		return isset( $registry[ $screen_id ] ) ? $registry[ $screen_id ] : array();
	}
}

if ( ! function_exists( 'cck_get_admin_nav_items' ) ) {
	/**
	 * Get workspace navigation items.
	 *
	 * @return array
	 */
	function cck_get_admin_nav_items() {
		$items = array();

		foreach ( cck_get_admin_screen_registry() as $screen ) {
			$items[ $screen['slug'] ] = $screen['label'];
		}

		return $items;
	}
}

if ( ! function_exists( 'cck_get_current_admin_page' ) ) {
	/**
	 * Get the current Craft Commerce Kit screen slug.
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
