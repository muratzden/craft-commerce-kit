<?php
/**
 * Theme runtime provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_provider_theme' ) ) {
	function cck_runtime_provider_theme( $key, $fallback = '', $context = array() ) {
		return $fallback;
	}
}