<?php
/**
 * WooCommerce product trust notes.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_register_product_trust_notes_shortcode' ) ) {
	/**
	 * Register product trust notes shortcode.
	 *
	 * @return void
	 */
	function cck_register_product_trust_notes_shortcode() {
		add_shortcode( 'cck_product_trust_notes', 'cck_shortcode_product_trust_notes' );
	}
}

if ( ! function_exists( 'cck_shortcode_product_trust_notes' ) ) {
	/**
	 * Render product trust notes.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	function cck_shortcode_product_trust_notes( $atts = array() ) {
		$atts = shortcode_atts(
			array(
				'items' => 'Handmade|Genuine material|Secure checkout|Carefully packed',
			),
			$atts,
			'cck_product_trust_notes'
		);

		return cck_component_trust_block(
			array(
				'items' => $atts['items'],
			)
		);
	}
}
