<?php
/**
 * Add-on status detection.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_addon_statuses' ) ) {
	/**
	 * Return installation and activation states for registered add-ons.
	 *
	 * @return array
	 */
	function cck_get_addon_statuses() {
		static $statuses = null;

		if ( null !== $statuses ) {
			return $statuses;
		}

		if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();
		$statuses          = array();

		foreach ( cck_get_addon_registry() as $addon_id => $addon ) {
			$plugin_file    = isset( $addon['plugin_file'] ) ? $addon['plugin_file'] : '';
			$installed      = isset( $installed_plugins[ $plugin_file ] );
			$active         = $installed && is_plugin_active( $plugin_file );
			$network_active = $installed
				&& is_multisite()
				&& is_plugin_active_for_network( $plugin_file );
			$plugin_data    = $installed ? $installed_plugins[ $plugin_file ] : array();
			$state          = 'not-installed';

			if ( $active ) {
				$state = 'active';
			} elseif ( $installed ) {
				$state = 'inactive';
			}

			$statuses[ $addon_id ] = array_merge(
				$addon,
				array(
					'installed'      => $installed,
					'active'         => $active,
					'network_active' => $network_active,
					'state'          => $state,
					'version'        => ! empty( $plugin_data['Version'] ) ? (string) $plugin_data['Version'] : '',
				)
			);
		}

		$statuses = apply_filters( 'cck_addon_statuses', $statuses );

		return $statuses;
	}
}

if ( ! function_exists( 'cck_get_addon_status' ) ) {
	/**
	 * Return installation and activation state for one add-on.
	 *
	 * @param string $addon_id Add-on identifier.
	 * @return array|null
	 */
	function cck_get_addon_status( $addon_id ) {
		$addon_id = sanitize_key( $addon_id );
		$statuses = cck_get_addon_statuses();

		return isset( $statuses[ $addon_id ] ) ? $statuses[ $addon_id ] : null;
	}
}
