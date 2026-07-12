<?php
/**
 * Runtime provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_provider_runtime' ) ) {
	function cck_runtime_provider_runtime( $key, $fallback = '', $context = array() ) {
		return $fallback;
	}
}