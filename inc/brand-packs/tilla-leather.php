<?php
/**
 * Tilla Leather brand pack.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_tilla_leather_brand_pack' ) ) {
	/**
	 * Get Tilla Leather preset content.
	 *
	 * @return array
	 */
	function cck_get_tilla_leather_brand_pack() {
		return array(
			'brand_name'      => 'Tilla Leather',
			'eyebrow'         => 'Handmade in Türkiye',
			'hero_title'      => 'Crafted by hand. Built to age.',
			'hero_text'       => 'Quiet luxury handmade leather goods from Türkiye.',
			'primary_label'   => 'Shop Collection',
			'primary_url'     => '/shop/',
			'secondary_label' => 'Visit the Workshop',
			'secondary_url'   => '/workshop/',
			'cta_title'       => 'A piece made to stay with you.',
			'cta_text'        => 'Explore handmade leather goods designed with patience, purpose, and material honesty.',
		);
	}
}
