<?php
/**
 * Coffee Studio brand binding.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_coffee_studio_brand' ) ) {
	/**
	 * Get Coffee Studio runtime brand data.
	 *
	 * @return array
	 */
	function cck_get_coffee_studio_brand() {
		return array(
			'brand_name' => 'Coffee Studio',
			'eyebrow'    => 'Small-batch coffee',
			'headline'   => 'Coffee made for slower mornings.',
			'text'       => 'Seasonal beans, careful roasting, and transparent sourcing for a more considered daily ritual.',
			'cta_label'  => 'Shop Coffee',
			'cta_url'    => '/shop/',
			'experience' => 'coffee-studio',
			'tokens'     => array(
				'colors' => array(
					'background'  => '#160F0B',
					'surface'     => '#211711',
					'surface_alt' => '#342219',
					'text'        => '#F3E8D8',
					'muted'       => '#B9A899',
					'heading'     => '#FFF8EE',
					'border'      => '#4A3428',
					'accent'      => '#C8793E',
					'dark'        => '#0D0907',
				),
			),
		);
	}
}

cck_register_brand(
	'coffee-studio',
	cck_get_coffee_studio_brand()
);
