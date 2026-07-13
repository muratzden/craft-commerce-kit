<?php
/**
 * Admin capability helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_user_can_manage_admin' ) ) {
	/**
	 * Check whether the current user can manage the CCK admin.
	 *
	 * @return bool
	 */
	function cck_user_can_manage_admin() {
		return current_user_can( 'manage_options' );
	}
}

if ( ! function_exists( 'cck_require_admin_capability' ) ) {
	/**
	 * Require administrator capability before rendering a page.
	 *
	 * @return void
	 */
	function cck_require_admin_capability() {
		if ( cck_user_can_manage_admin() ) {
			return;
		}

		wp_die(
			esc_html__(
				'Sorry, you are not allowed to access this page.',
				'craft-commerce-kit'
			)
		);
	}
}