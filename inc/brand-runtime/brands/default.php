<?php
/**
 * Default brand.
 *
 * @package CraftCommerceKit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cck_get_default_brand' ) ) {
	/**
	 * Get default brand data.
	 *
	 * @return array
	 */
	function cck_get_default_brand() {
		return array(
			'brand_name' => 'Default Brand',
			'eyebrow'    => 'Craft Commerce Kit',
			'headline'   => 'Build flexible WooCommerce storefront sections.',
			'text'       => 'Use reusable components, templates, and layouts without locking your store to a specific theme.',
			'cta_label'  => 'Explore Components',
			'cta_url'    => '#',
		);
	}
}