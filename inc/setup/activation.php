<?php
/**
 * Setup activation state.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_activate_plugin_setup' ) ) {
	/**
	 * Mark setup redirect as pending after plugin activation.
	 *
	 * @return void
	 */
	function cck_activate_plugin_setup() {
		if ( get_option( 'cck_setup_completed', false ) ) {
			return;
		}

		update_option( 'cck_setup_redirect_pending', 1, false );
	}
}