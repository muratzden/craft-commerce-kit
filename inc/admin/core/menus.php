<?php
/**
 * Admin menu registration.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_register_admin_page' ) ) {

	/**
	 * Register admin menu pages.
	 *
	 * @return void
	 */
	function cck_register_admin_page() {
		$screen = cck_get_admin_screen( 'overview' );

		add_menu_page(
			__( 'Craft Commerce Kit', 'craft-commerce-kit' ),
			__( 'Craft Commerce Kit', 'craft-commerce-kit' ),
			'manage_options',
			$screen['slug'],
			$screen['callback'],
			'dashicons-layout',
			58
		);

		foreach ( cck_get_admin_screen_ids() as $screen_id ) {
			$screen = cck_get_admin_screen( $screen_id );

			if ( empty( $screen['slug'] ) || empty( $screen['callback'] ) ) {
				continue;
			}

			if ( ! empty( $screen['hidden'] ) ) {
				add_submenu_page(
					'craft-commerce-kit',
					$screen['page_title'],
					$screen['label'],
					'manage_options',
					$screen['slug'],
					$screen['callback']
				);

				remove_submenu_page( 'craft-commerce-kit', $screen['slug'] );

				continue;
			}

			add_submenu_page(
				'craft-commerce-kit',
				$screen['page_title'],
				$screen['label'],
				'manage_options',
				$screen['slug'],
				$screen['callback']
			);
		}
	}
}
