<?php
/**
 * Runtime product contract.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_normalize_product' ) ) {
	function cck_runtime_normalize_product( $product ) {
		if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) {
			return array();
		}

		$product_id = $product->get_id();

		return array(
			'id'         => $product_id,
			'title'      => $product->get_name(),
			'url'        => get_permalink( $product_id ),
			'image_html' => $product->get_image( 'woocommerce_thumbnail' ),
			'price_html' => $product->get_price_html(),
		);
	}
}