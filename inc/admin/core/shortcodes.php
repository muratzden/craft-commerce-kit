<?php
/**
 * Admin shortcode helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_admin_shortcodes' ) ) {
	/**
	 * Get admin shortcode registry.
	 *
	 * @return array
	 */
	function cck_get_admin_shortcodes() {
		$shortcodes = array(
			array(
				'code'        => '[cck_hero]',
				'description' => __( 'Displays a premium craft hero section with eyebrow, headline, text, actions, and optional image.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_section_title]',
				'description' => __( 'Displays a reusable section heading with optional eyebrow, text, and alignment.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_trust_block]',
				'description' => __( 'Displays a grid of trust notes for craft and commerce pages.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_image_text]',
				'description' => __( 'Displays a responsive image and text storytelling section.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_cta]',
				'description' => __( 'Displays a focused call-to-action block.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_collection_grid]',
				'description' => __( 'Displays a linked collection grid using pipe and comma formatted items.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_tilla_hero]',
				'description' => __( 'Displays the Tilla Leather hero preset.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_tilla_cta]',
				'description' => __( 'Displays the Tilla Leather call-to-action preset.', 'craft-commerce-kit' ),
			),
			array(
				'code'        => '[cck_tilla_home]',
				'description' => __( 'Displays the Tilla Leather homepage demo skeleton.', 'craft-commerce-kit' ),
			),
		);

		if ( cck_is_woocommerce_active() ) {
			$shortcodes[] = array(
				'code'        => '[cck_product_trust_notes]',
				'description' => __( 'Displays product trust notes when WooCommerce is active.', 'craft-commerce-kit' ),
			);
		}

		return $shortcodes;
	}
}