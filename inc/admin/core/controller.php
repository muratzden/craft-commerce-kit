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
			'layouts' => array(
				'id'          => 'layouts',
				'label'       => __( 'Layouts', 'craft-commerce-kit' ),
				'page_title'  => __( 'Layouts', 'craft-commerce-kit' ),
				'description' => __( 'Manual component composition and reusable layout sequences.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-layouts',
				'callback'    => 'cck_render_layouts_page',
			),
			'component-preview' => array(
				'id'          => 'component-preview',
				'label'       => __( 'Component Preview', 'craft-commerce-kit' ),
				'page_title'  => __( 'Component Preview', 'craft-commerce-kit' ),
				'description' => __( 'Read-only preview of a registered component rendered by the production callback.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-component-preview',
				'callback'    => 'cck_render_component_preview_page',
				'hidden'      => true,
			),
			'experiences' => array(
				'id'          => 'experiences',
				'label'       => __( 'Experiences', 'craft-commerce-kit' ),
				'page_title'  => __( 'Experiences', 'craft-commerce-kit' ),
				'description' => __( 'Registered experience metadata, publishing, and homepage controls.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-experiences',
				'callback'    => 'cck_render_experiences_page',
			),
			'experience-preview' => array(
				'id'          => 'experience-preview',
				'label'       => __( 'Experience Preview', 'craft-commerce-kit' ),
				'page_title'  => __( 'Experience Preview', 'craft-commerce-kit' ),
				'description' => __( 'Read-only preview of a registered experience rendered by the production callback.', 'craft-commerce-kit' ),
				'slug'        => 'craft-commerce-kit-experience-preview',
				'callback'    => 'cck_render_experience_preview_page',
				'hidden'      => true,
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
			if ( ! empty( $screen['hidden'] ) ) {
				continue;
			}

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
					return 'craft-commerce-kit-experiences';
				}

				return $screen_item['slug'];
			}
		}

		return 'craft-commerce-kit';
	}
}
