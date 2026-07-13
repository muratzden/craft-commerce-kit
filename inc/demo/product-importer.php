<?php
/**
 * Demo product catalog importer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_demo_catalog_find_product_id_by_slug' ) ) {
	function cck_demo_catalog_find_product_id_by_slug( $slug ) {
		$slug = sanitize_title( cck_to_string( $slug ) );

		if ( '' === $slug ) {
			return 0;
		}

		$posts = get_posts(
			array(
				'post_type'              => 'product',
				'name'                   => $slug,
				'post_status'            => array( 'publish', 'draft', 'pending', 'private' ),
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			)
		);

		return ! empty( $posts[0] ) ? absint( $posts[0] ) : 0;
	}
}

if ( ! function_exists( 'cck_demo_catalog_find_product_id_by_sku' ) ) {
	function cck_demo_catalog_find_product_id_by_sku( $sku ) {
		$sku = sanitize_text_field( cck_to_string( $sku ) );

		if ( '' === $sku || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
			return 0;
		}

		return absint( wc_get_product_id_by_sku( $sku ) );
	}
}

if ( ! function_exists( 'cck_demo_catalog_build_relationship_ids' ) ) {
	function cck_demo_catalog_build_relationship_ids( array $items, array $slug_map ) {
		$ids = array();

		foreach ( $items as $item ) {
			$key = sanitize_title( cck_to_string( $item ) );

			if ( '' !== $key && isset( $slug_map[ $key ] ) ) {
				$ids[] = absint( $slug_map[ $key ] );
			}
		}

		return array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
	}
}

if ( ! function_exists( 'cck_demo_catalog_save_product_meta' ) ) {
	function cck_demo_catalog_save_product_meta( $product_id, $slug ) {
		update_post_meta( $product_id, '_cck_demo_product', 'yes' );
		update_post_meta( $product_id, '_cck_demo_product_slug', sanitize_title( $slug ) );
	}
}

if ( ! function_exists( 'cck_demo_catalog_seed_product_image_assets' ) ) {
	function cck_demo_catalog_seed_product_image_assets() {
		$target_dir = CCK_PLUGIN_DIR . 'assets/demo/catalog/';

		if ( ! file_exists( $target_dir ) ) {
			wp_mkdir_p( $target_dir );
		}
	}
}

if ( ! function_exists( 'cck_import_demo_products' ) ) {
	function cck_import_demo_products() {
		if ( ! cck_is_woocommerce_active() || ! class_exists( 'WC_Product_Simple' ) ) {
			return array(
				'created' => 0,
				'updated' => 0,
				'skipped' => 0,
			);
		}

		cck_demo_catalog_seed_product_image_assets();

		$products = function_exists( 'cck_demo_product_catalog_data' ) ? cck_demo_product_catalog_data() : array();
		$slug_map  = array();
		$report    = array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
		);

		foreach ( $products as $definition ) {
			if ( empty( $definition['sku'] ) || empty( $definition['slug'] ) || empty( $definition['name'] ) ) {
				$report['skipped']++;
				continue;
			}

			$product_id = cck_demo_catalog_find_product_id_by_sku( $definition['sku'] );
			$product    = $product_id ? wc_get_product( $product_id ) : new WC_Product_Simple();

			if ( ! $product instanceof WC_Product ) {
				$report['skipped']++;
				continue;
			}

			$is_new = ! $product_id;
			$slug   = sanitize_title( $definition['slug'] );

			$product->set_name( sanitize_text_field( $definition['name'] ) );
			$product->set_slug( $slug );
			$product->set_status( 'publish' );
			$product->set_catalog_visibility( 'visible' );
			$product->set_sku( sanitize_text_field( $definition['sku'] ) );
			$product->set_description( wp_kses_post( isset( $definition['description'] ) ? $definition['description'] : '' ) );
			$product->set_short_description( wp_kses_post( isset( $definition['short_description'] ) ? $definition['short_description'] : '' ) );
			$product->set_regular_price( isset( $definition['regular_price'] ) ? wc_format_decimal( $definition['regular_price'] ) : '' );
			$product->set_sale_price( isset( $definition['sale_price'] ) ? wc_format_decimal( $definition['sale_price'] ) : '' );
			$product->set_price( '' !== $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price() );
			$product->set_manage_stock( true );
			$product->set_stock_quantity( isset( $definition['stock_quantity'] ) ? absint( $definition['stock_quantity'] ) : 12 );
			$product->set_stock_status( $product->get_stock_quantity() > 0 ? 'instock' : 'outofstock' );
			$product->set_featured( in_array( $slug, array( 'executive-messenger-bag', 'classic-briefcase', 'travel-organizer' ), true ) );
			$product->set_virtual( false );
			$product->set_downloadable( false );

			$attachment_id = 0;
			if ( ! empty( $definition['featured_image'] ) ) {
				$attachment_id = cck_demo_catalog_import_attachment( $definition['featured_image'], $definition['name'] );
			}

			if ( $attachment_id ) {
				$product->set_image_id( $attachment_id );
			}

			$gallery_ids = array();
			if ( ! empty( $definition['gallery'] ) && is_array( $definition['gallery'] ) ) {
				foreach ( $definition['gallery'] as $gallery_file ) {
					$gallery_id = cck_demo_catalog_import_attachment( $gallery_file, $definition['name'] );
					if ( $gallery_id && $gallery_id !== $attachment_id ) {
						$gallery_ids[] = $gallery_id;
					}
				}
			}
			$product->set_gallery_image_ids( array_values( array_unique( array_map( 'absint', $gallery_ids ) ) ) );

			$category_ids = cck_demo_catalog_collect_term_ids( 'product_cat', isset( $definition['categories'] ) && is_array( $definition['categories'] ) ? $definition['categories'] : array() );
			$tag_ids      = cck_demo_catalog_collect_term_ids( 'product_tag', isset( $definition['tags'] ) && is_array( $definition['tags'] ) ? $definition['tags'] : array() );
			$product->set_category_ids( $category_ids );
			$product->set_tag_ids( $tag_ids );

			$product->update_meta_data( '_cck_demo_product', 'yes' );
			$product->update_meta_data( '_cck_demo_product_slug', $slug );
			$product->update_meta_data( '_cck_demo_sku', sanitize_text_field( $definition['sku'] ) );
			$product->update_meta_data( '_cck_demo_seed', '1' );
			$product->save();

			$slug_map[ $slug ] = $product->get_id();
			$report[ $is_new ? 'created' : 'updated' ]++;
		}

		foreach ( $products as $definition ) {
			$product_id = cck_demo_catalog_find_product_id_by_sku( isset( $definition['sku'] ) ? $definition['sku'] : '' );

			if ( ! $product_id ) {
				continue;
			}

			$product = wc_get_product( $product_id );

			if ( ! $product instanceof WC_Product ) {
				continue;
			}

			$upsells     = cck_demo_catalog_build_relationship_ids( isset( $definition['upsells'] ) && is_array( $definition['upsells'] ) ? $definition['upsells'] : array(), $slug_map );
			$cross_sells = cck_demo_catalog_build_relationship_ids( isset( $definition['cross_sells'] ) && is_array( $definition['cross_sells'] ) ? $definition['cross_sells'] : array(), $slug_map );

			$product->set_upsell_ids( $upsells );
			$product->set_cross_sell_ids( $cross_sells );
			$product->update_meta_data( '_cck_demo_upsells', $upsells );
			$product->update_meta_data( '_cck_demo_cross_sells', $cross_sells );
			$product->save();
		}

		update_option( 'cck_demo_product_catalog_seeded', array_keys( $slug_map ), false );

		return $report;
	}
}

