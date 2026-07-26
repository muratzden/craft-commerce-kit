<?php
/**
 * Tilla Leather brand.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_tilla_leather_brand' ) ) {
	/**
	 * Get Tilla Leather runtime brand data.
	 *
	 * @return array
	 */
	function cck_get_tilla_leather_brand() {
		return array(
			'brand_name' => 'Tilla Leather',
			'eyebrow'    => "Handmade in T\u{00FC}rkiye",
			'headline'   => 'Crafted by hand. Built to age.',
			'text'       => "Quiet luxury handmade leather goods from T\u{00FC}rkiye.",
			'cta_label'  => 'Shop Collection',
			'cta_url'    => '/shop/',
			'tokens'     => array(
				'colors' => array(
					'background'  => '#F7F1E7',
					'surface'     => '#FFFDF8',
					'surface_alt' => '#E6D6C0',
					'text'        => '#2A1B13',
					'muted'       => '#75675D',
					'heading'     => '#17120E',
					'border'      => '#D6C0A4',
					'accent'      => '#9B5C32',
					'dark'        => '#17120E',
				),
			),
		);
	}
}