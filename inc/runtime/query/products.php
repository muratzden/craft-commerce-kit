<?php
/**
 * Runtime product queries.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_runtime_normalize_product_query' ) ) {
	function cck_runtime_normalize_product_query( $query = array() ) {
		$query = is_array( $query ) ? $query : array();

		$type = isset( $query['type'] ) && is_scalar( $query['type'] ) ? sanitize_key( (string) $query['type'] ) : 'latest';
		$type = in_array( $type, array( 'latest', 'featured' ), true ) ? $type : 'latest';

		$limit = isset( $query['limit'] ) && is_scalar( $query['limit'] ) && is_numeric( $query['limit'] ) ? (int) $query['limit'] : 4;
		$limit = $limit > 0 ? min( 12, $limit ) : 4;

		return array(
			'type'  => $type,
			'limit' => $limit,
		);
	}
}

if ( ! function_exists( 'cck_runtime_empty_product_query_result' ) ) {
	function cck_runtime_empty_product_query_result( $query_args = array(), $available = false ) {
		return array(
			'items'       => array(),
			'total'       => 0,
			'query_args'  => is_array( $query_args ) ? $query_args : array(),
			'has_results' => false,
			'available'   => (bool) $available,
		);
	}
}

if ( ! function_exists( 'cck_runtime_query_products' ) ) {
	function cck_runtime_query_products( $query = array() ) {
		$query = cck_runtime_normalize_product_query( $query );

		if ( ! function_exists( 'wc_get_products' ) ) {
			return cck_runtime_empty_product_query_result( $query, false );
		}

		$args = array(
			'status' => 'publish',
			'limit'  => $query['limit'],
			'return' => 'objects',
		);

		if ( 'featured' === $query['type'] ) {
			$args['featured'] = true;
		} else {
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
		}

		$products = wc_get_products( $args );

		if ( ! is_array( $products ) ) {
			return cck_runtime_empty_product_query_result( $query, true );
		}

		$normalized = array();
		$seen_ids   = array();

		foreach ( $products as $product ) {
			$item = function_exists( 'cck_runtime_normalize_product' ) ? cck_runtime_normalize_product( $product ) : array();

			if ( empty( $item['id'] ) || isset( $seen_ids[ $item['id'] ] ) ) {
				continue;
			}

			$seen_ids[ $item['id'] ] = true;
			$normalized[]             = $item;
		}

		return array(
			'items'       => $normalized,
			'total'       => count( $normalized ),
			'query_args'  => $query,
			'has_results' => ! empty( $normalized ),
			'available'   => true,
		);
	}
}
