<?php
/**
 * User runtime provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_provider_user' ) ) {
	function cck_runtime_provider_user( $key, $fallback = '', $context = array() ) {
		return $fallback;
	}
}