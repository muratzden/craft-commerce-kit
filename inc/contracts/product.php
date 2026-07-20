<?php
/**
 * Product contract helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_contract_normalize_product' ) ) {
	/**
	 * Normalize a WooCommerce product into the CCK product contract shape.
	 *
	 * @param mixed $product Product object or ID.
	 * @param array $args Optional normalization arguments.
	 * @return array
	 */
	function cck_contract_normalize_product( $product, array $args = array() ) {
		if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) {
			return array();
		}

		$product_id = absint( $product->get_id() );
		$context    = isset( $args['context'] ) ? sanitize_key( $args['context'] ) : 'default';

		return array(
			'id'       => $product_id,
			'type'     => method_exists( $product, 'get_type' ) ? sanitize_key( $product->get_type() ) : '',
			'status'   => method_exists( $product, 'get_status' ) ? sanitize_key( $product->get_status() ) : '',
			'context'  => $context,
			'identity' => array(
				'title'      => method_exists( $product, 'get_name' ) ? $product->get_name() : '',
				'slug'       => method_exists( $product, 'get_slug' ) ? $product->get_slug() : '',
				'url'        => function_exists( 'get_permalink' ) ? get_permalink( $product_id ) : '',
				'sku'        => method_exists( $product, 'get_sku' ) ? $product->get_sku() : '',
				'categories' => cck_contract_get_product_terms( $product_id, 'product_cat' ),
				'tags'       => cck_contract_get_product_terms( $product_id, 'product_tag' ),
			),
			'content'  => array(
				'short_description' => method_exists( $product, 'get_short_description' ) ? wp_strip_all_tags( (string) $product->get_short_description() ) : '',
				'description'       => method_exists( $product, 'get_description' ) ? wp_kses_post( (string) $product->get_description() ) : '',
				'excerpt'           => method_exists( $product, 'get_short_description' ) ? wp_trim_words( wp_strip_all_tags( (string) $product->get_short_description() ), 28 ) : '',
				'features'          => array_slice( cck_contract_get_product_attributes( $product ), 0, 3 ),
				'attributes'        => cck_contract_get_product_attributes( $product ),
			),
			'pricing'  => array(
				'price_html'         => method_exists( $product, 'get_price_html' ) ? $product->get_price_html() : '',
				'regular_price_html' => method_exists( $product, 'get_regular_price' ) && '' !== $product->get_regular_price() && function_exists( 'wc_price' ) ? wc_price( $product->get_regular_price() ) : '',
				'sale_price_html'    => method_exists( $product, 'get_sale_price' ) && '' !== $product->get_sale_price() && function_exists( 'wc_price' ) ? wc_price( $product->get_sale_price() ) : '',
				'is_on_sale'         => method_exists( $product, 'is_on_sale' ) ? (bool) $product->is_on_sale() : false,
			),
			'rating'   => array(
				'average' => method_exists( $product, 'get_average_rating' ) ? (float) $product->get_average_rating() : 0,
				'count'   => method_exists( $product, 'get_rating_count' ) ? absint( $product->get_rating_count() ) : 0,
				'html'    => function_exists( 'wc_get_rating_html' ) && method_exists( $product, 'get_average_rating' ) ? wc_get_rating_html( (float) $product->get_average_rating(), method_exists( $product, 'get_rating_count' ) ? absint( $product->get_rating_count() ) : 0 ) : '',
			),
			'stock'    => array(
				'status'      => method_exists( $product, 'get_stock_status' ) ? sanitize_key( $product->get_stock_status() ) : '',
				'is_in_stock' => method_exists( $product, 'is_in_stock' ) ? (bool) $product->is_in_stock() : false,
				'quantity'    => method_exists( $product, 'get_stock_quantity' ) ? $product->get_stock_quantity() : null,
				'label'       => method_exists( $product, 'is_in_stock' ) && $product->is_in_stock() ? __( 'In stock', 'craft-commerce-kit' ) : __( 'Out of stock', 'craft-commerce-kit' ),
			),
			'media'    => array(
				'featured' => cck_contract_get_product_featured_media( $product ),
				'gallery'  => cck_contract_get_product_gallery_media( $product ),
				'video'    => array(
					'url'       => '',
					'provider'  => '',
					'id'        => '',
					'thumbnail' => '',
				),
			),
			'purchase' => cck_contract_get_product_purchase( $product ),
			'badges'   => array(
				'html'  => function_exists( 'cck_wc_get_product_badges' ) ? implode( '', cck_wc_get_product_badges( $product ) ) : '',
				'items' => array(),
			),
			'meta'     => array(
				'source'     => 'woocommerce',
				'product_id' => $product_id,
			),
		);
	}
}

if ( ! function_exists( 'cck_contract_get_product_terms' ) ) {
	/**
	 * Get product taxonomy labels for the product contract.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $taxonomy Taxonomy name.
	 * @return array
	 */
	function cck_contract_get_product_terms( $product_id, $taxonomy ) {
		$product_id = absint( $product_id );
		$taxonomy   = sanitize_key( $taxonomy );

		if ( ! $product_id || '' === $taxonomy || ! function_exists( 'wp_get_post_terms' ) ) {
			return array();
		}

		$terms = wp_get_post_terms( $product_id, $taxonomy, array( 'fields' => 'names' ) );

		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			return array();
		}

		return array_values( array_map( 'sanitize_text_field', $terms ) );
	}
}

if ( ! function_exists( 'cck_contract_get_product_attributes' ) ) {
	/**
	 * Get normalized product attributes.
	 *
	 * @param object $product Product object.
	 * @return array
	 */
	function cck_contract_get_product_attributes( $product ) {
		if ( ! is_object( $product ) || ! method_exists( $product, 'get_attributes' ) ) {
			return array();
		}

		$attributes = array();

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! is_object( $attribute ) || ! method_exists( $attribute, 'get_name' ) ) {
				continue;
			}

			$name  = $attribute->get_name();
			$label = function_exists( 'wc_attribute_label' ) ? wc_attribute_label( $name ) : $name;
			$value = '';

			if ( method_exists( $attribute, 'is_taxonomy' ) && $attribute->is_taxonomy() && function_exists( 'wc_get_product_terms' ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'names' ) );
				$value = is_array( $terms ) ? implode( ', ', array_map( 'sanitize_text_field', $terms ) ) : '';
			} elseif ( method_exists( $attribute, 'get_options' ) ) {
				$value = implode( ', ', array_map( 'sanitize_text_field', (array) $attribute->get_options() ) );
			}

			if ( '' === $label || '' === $value ) {
				continue;
			}

			$attributes[] = array(
				'name'  => sanitize_key( $name ),
				'label' => sanitize_text_field( $label ),
				'value' => sanitize_text_field( $value ),
			);
		}

		return $attributes;
	}
}

if ( ! function_exists( 'cck_contract_get_product_featured_media' ) ) {
	/**
	 * Get normalized featured product media.
	 *
	 * @param object $product Product object.
	 * @return array
	 */
	function cck_contract_get_product_featured_media( $product ) {
		$empty = array(
			'id'     => 0,
			'html'   => '',
			'url'    => '',
			'alt'    => '',
			'width'  => 0,
			'height' => 0,
		);

		if ( ! is_object( $product ) || ! method_exists( $product, 'get_image_id' ) ) {
			return $empty;
		}

		$image_id = absint( $product->get_image_id() );

		if ( ! $image_id ) {
			return $empty;
		}

		$url  = function_exists( 'wp_get_attachment_image_url' ) ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : '';
		$html = function_exists( 'wp_get_attachment_image' )
			? wp_get_attachment_image(
				$image_id,
				'woocommerce_single',
				false,
				array(
					'loading'  => 'eager',
					'decoding' => 'async',
				)
			)
			: '';
		$meta = function_exists( 'wp_get_attachment_metadata' ) ? wp_get_attachment_metadata( $image_id ) : array();

		return array(
			'id'     => $image_id,
			'html'   => $html,
			'url'    => is_string( $url ) ? $url : '',
			'alt'    => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			'width'  => isset( $meta['width'] ) ? absint( $meta['width'] ) : 0,
			'height' => isset( $meta['height'] ) ? absint( $meta['height'] ) : 0,
		);
	}
}

if ( ! function_exists( 'cck_contract_get_product_gallery_media' ) ) {
	/**
	 * Get normalized product gallery media.
	 *
	 * @param object $product Product object.
	 * @return array
	 */
	function cck_contract_get_product_gallery_media( $product ) {
		if ( ! is_object( $product ) || ! method_exists( $product, 'get_gallery_image_ids' ) ) {
			return array();
		}

		$gallery = array();

		foreach ( (array) $product->get_gallery_image_ids() as $image_id ) {
			$image_id = absint( $image_id );

			if ( ! $image_id ) {
				continue;
			}

			$url  = function_exists( 'wp_get_attachment_image_url' ) ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : '';
			$html = function_exists( 'wp_get_attachment_image' )
				? wp_get_attachment_image(
					$image_id,
					'woocommerce_single',
					false,
					array(
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				)
				: '';
			$meta = function_exists( 'wp_get_attachment_metadata' ) ? wp_get_attachment_metadata( $image_id ) : array();

			$gallery[] = array(
				'id'     => $image_id,
				'html'   => $html,
				'url'    => is_string( $url ) ? $url : '',
				'alt'    => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
				'width'  => isset( $meta['width'] ) ? absint( $meta['width'] ) : 0,
				'height' => isset( $meta['height'] ) ? absint( $meta['height'] ) : 0,
			);
		}

		return $gallery;
	}
}

if ( ! function_exists( 'cck_contract_get_product_purchase' ) ) {
	/**
	 * Get normalized product purchase data.
	 *
	 * @param object $product Product object.
	 * @return array
	 */
	function cck_contract_get_product_purchase( $product ) {
		if ( ! is_object( $product ) ) {
			return array(
				'add_to_cart_html'  => '',
				'wishlist_html'     => '',
				'quick_action_html' => '',
				'options_html'      => '',
				'is_purchasable'    => false,
			);
		}

		$options_html = '';

		if ( function_exists( 'do_action' ) ) {
			ob_start();
			do_action( 'cck_product_purchase_options', $product );
			do_action( 'cck_product_option_blocks', $product );
			$options_html = trim( ob_get_clean() );
		}

		return array(
			'add_to_cart_html'  => '',
			'wishlist_html'     => '',
			'quick_action_html' => '',
			'options_html'      => $options_html,
			'is_purchasable'    => method_exists( $product, 'is_purchasable' ) ? (bool) $product->is_purchasable() : false,
		);
	}
}