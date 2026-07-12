<?php
/**
 * Runtime product queries.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_query_products' ) ) {
	function cck_runtime_query_products( $query = array() ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return array();
		}

		if ( ! is_array( $query ) ) {
			$query = array();
		}

		$type  = isset( $query['type'] ) ? sanitize_key( $query['type'] ) : 'latest';
		$limit = isset( $query['limit'] ) ? absint( $query['limit'] ) : 4;
		$limit = max( 1, min( 12, $limit ) );

		$args = array(
			'status' => 'publish',
			'limit'  => $limit,
			'return' => 'objects',
		);

		if ( 'featured' === $type ) {
			$args['featured'] = true;
		} else {
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
		}

		$products = wc_get_products( $args );

		if ( ! is_array( $products ) ) {
			return array();
		}

		$normalized = array();

		foreach ( $products as $product ) {
			$item = function_exists( 'cck_runtime_normalize_product' ) ? cck_runtime_normalize_product( $product ) : array();

			if ( ! empty( $item ) ) {
				$normalized[] = $item;
			}
		}

		return $normalized;
	}
}