<?php
/**
 * Site runtime provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_provider_site' ) ) {
	function cck_runtime_provider_site( $key, $fallback = '', $context = array() ) {
		return $fallback;
	}
}