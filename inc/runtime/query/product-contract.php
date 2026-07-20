<?php
/**
 * Runtime product contract.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_normalize_product' ) ) {
	/**
	 * Normalize a WooCommerce product for runtime product queries.
	 *
	 * @param object $product Product object.
	 * @return array
	 */
	function cck_runtime_normalize_product( $product ) {
		if ( ! function_exists( 'cck_contract_normalize_product' ) ) {
			return array();
		}

		$contract = cck_contract_normalize_product(
			$product,
			array(
				'context' => 'runtime',
			)
		);

		if ( empty( $contract ) ) {
			return array();
		}

		return array(
			'id'         => isset( $contract['id'] ) ? absint( $contract['id'] ) : 0,
			'title'      => isset( $contract['identity']['title'] ) ? $contract['identity']['title'] : '',
			'url'        => isset( $contract['identity']['url'] ) ? $contract['identity']['url'] : '',
			'image_html' => isset( $contract['media']['featured']['html'] ) ? $contract['media']['featured']['html'] : '',
			'price_html' => isset( $contract['pricing']['price_html'] ) ? $contract['pricing']['price_html'] : '',
		);
	}
}
