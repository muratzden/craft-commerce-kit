<?php
/**
 * Shortcode registration.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_register_shortcodes' ) ) {
	/**
	 * Register public shortcodes.
	 *
	 * @return void
	 */
	function cck_register_shortcodes() {
		add_shortcode( 'cck_hero', 'cck_component_hero' );
		add_shortcode( 'cck_section_title', 'cck_component_section_title' );
		add_shortcode( 'cck_trust_block', 'cck_component_trust_block' );
		add_shortcode( 'cck_image_text', 'cck_component_image_text' );
		add_shortcode( 'cck_cta', 'cck_component_cta' );
		add_shortcode( 'cck_collection_grid', 'cck_component_collection_grid' );
		add_shortcode( 'cck_component', 'cck_component_shortcode' );
		add_shortcode( 'cck_layout', 'cck_layout_shortcode' );
		add_shortcode( 'cck_experience', 'cck_shortcode_experience' );

		add_shortcode( 'cck_tilla_hero', 'cck_shortcode_tilla_hero' );
		add_shortcode( 'cck_tilla_cta', 'cck_shortcode_tilla_cta' );
		add_shortcode( 'cck_tilla_home', 'cck_shortcode_tilla_home' );
	}
}

if ( ! function_exists( 'cck_shortcode_tilla_hero' ) ) {
	/**
	 * Render Tilla hero preset.
	 *
	 * @return string
	 */
	function cck_shortcode_tilla_hero() {
		$brand = cck_get_tilla_leather_brand_pack();

		return cck_render_component(
			'hero',
			array(
				'eyebrow'         => $brand['eyebrow'],
				'title'           => $brand['hero_title'],
				'text'            => $brand['hero_text'],
				'primary_label'   => $brand['primary_label'],
				'primary_url'     => $brand['primary_url'],
				'secondary_label' => $brand['secondary_label'],
				'secondary_url'   => $brand['secondary_url'],
			)
		);
	}
}

if ( ! function_exists( 'cck_shortcode_tilla_cta' ) ) {
	/**
	 * Render Tilla CTA preset.
	 *
	 * @return string
	 */
	function cck_shortcode_tilla_cta() {
		$brand = cck_get_tilla_leather_brand_pack();

		return cck_component_cta(
			array(
				'title'        => $brand['cta_title'],
				'text'         => $brand['cta_text'],
				'button_label' => $brand['primary_label'],
				'button_url'   => $brand['primary_url'],
			)
		);
	}
}

if ( ! function_exists( 'cck_shortcode_tilla_home' ) ) {
	/**
	 * Render Tilla homepage skeleton.
	 *
	 * @return string
	 */
	function cck_shortcode_tilla_home() {
		$output  = cck_shortcode_tilla_hero();
		$output .= cck_render_component( 'trust-block' );
		$output .= '<section class="cck-section"><div class="cck-container">';
		$output .= cck_component_section_title(
			array(
				'eyebrow' => 'Tilla Leather',
				'title'   => 'Leather goods with a quieter kind of confidence.',
				'text'    => 'A homepage foundation for collections, craft notes, and product storytelling.',
				'align'   => 'center',
			)
		);
		$output .= '</div></section>';
		$output .= cck_component_collection_grid(
			array(
				'items'   => 'Bags,/product-category/bags/|Wallets,/product-category/wallets/|Belts,/product-category/belts/',
				'columns' => '3',
			)
		);
		$output .= cck_component_image_text(
			array(
				'title'        => 'Material honesty, shaped by hand.',
				'text'         => 'Use this block for workshop photography, process notes, or a brand story section.',
				'button_label' => 'Visit the Workshop',
				'button_url'   => '/workshop/',
				'reverse'      => 'true',
			)
		);
		$output .= cck_shortcode_tilla_cta();

		return $output;
	}
}
