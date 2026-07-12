<?php
/**
 * Demo brand.
 *
 * @package CraftCommerceKit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cck_get_demo_brand' ) ) {
	/**
	 * Get demo brand data.
	 *
	 * @return array
	 */
	function cck_get_demo_brand() {
		return array(
			'brand_name' => 'Demo Brand',
			'eyebrow'    => 'Handcrafted Commerce',
			'headline'   => 'Create elegant product storytelling sections.',
			'text'       => 'A sample brand profile for demonstrating Craft Commerce Kit components.',
			'cta_label'  => 'View Collection',
			'cta_url'    => '#',
		);
	}
}