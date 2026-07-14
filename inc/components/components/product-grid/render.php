<?php
/**
 * Product Grid component render file.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_product_grid' ) ) {
	function cck_component_package_render_product_grid( $atts = array(), $manifest = array() ) {
		$limit = isset( $atts['limit'] ) ? absint( $atts['limit'] ) : 4;
		$limit = min( 8, max( 1, $limit ) );
		$result = function_exists( 'cck_runtime_query_products' )
			? cck_runtime_query_products(
				array(
					'type'  => isset( $atts['type'] ) ? $atts['type'] : 'latest',
					'limit' => $limit,
				)
			)
			: array();

		$result   = is_array( $result ) ? $result : array();
		$products = isset( $result['items'] ) && is_array( $result['items'] ) ? $result['items'] : array();
		$available = ! empty( $result['available'] );
		$demo_products = array(
			array(
				'slug'       => 'the-atelier-tote',
				'title'      => __( 'The Atelier Tote', 'craft-commerce-kit' ),
				'short'      => __( 'A structured carryall for daily essentials.', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$320' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_wc_get_product_card_demo_image_asset' ) ? cck_wc_get_product_card_demo_image_asset( 'the-atelier-tote' ) : array(),
			),
			array(
				'slug'       => 'heritage-wallet',
				'title'      => __( 'Heritage Wallet', 'craft-commerce-kit' ),
				'short'      => __( 'Slim storage with a tailored leather finish.', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$128' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_wc_get_product_card_demo_image_asset' ) ? cck_wc_get_product_card_demo_image_asset( 'heritage-wallet' ) : array(),
			),
			array(
				'slug'       => 'daily-carry-pouch',
				'title'      => __( 'Daily Carry Pouch', 'craft-commerce-kit' ),
				'short'      => __( 'Compact organization for everyday movement.', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$86' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_wc_get_product_card_demo_image_asset' ) ? cck_wc_get_product_card_demo_image_asset( 'daily-carry-pouch' ) : array(),
			),
			array(
				'slug'       => 'travel-companion',
				'title'      => __( 'Travel Companion', 'craft-commerce-kit' ),
				'short'      => __( 'Built for transit, tickets, and essentials.', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$214' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_wc_get_product_card_demo_image_asset' ) ? cck_wc_get_product_card_demo_image_asset( 'travel-companion' ) : array(),
			),
		);

		ob_start();
		?>
		<section class="cck-component cck-product-grid">
			<div class="cck-container">
				<div class="cck-section-heading">
					<?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
						<p class="cck-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $atts['title'] ) ) : ?>
						<h2><?php echo esc_html( $atts['title'] ); ?></h2>
					<?php endif; ?>
				</div>

				<div class="cck-product-grid__items">
					<?php if ( $available && ! empty( $products ) ) : ?>
						<?php foreach ( $products as $product ) : ?>
							<?php
							$product_object = function_exists( 'wc_get_product' ) && ! empty( $product['id'] ) ? wc_get_product( absint( $product['id'] ) ) : null;

							if ( $product_object instanceof WC_Product && function_exists( 'cck_wc_render_product_card_markup' ) ) {
								echo cck_wc_render_product_card_markup( $product_object, 'product-grid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								continue;
							}

							if ( function_exists( 'cck_wc_render_product_card_markup' ) ) {
								$card = array(
									'id'                => ! empty( $product['id'] ) ? absint( $product['id'] ) : sanitize_key( cck_array_get( $product, 'slug', cck_array_get( $product, 'title', 'product-grid' ) ) ),
									'context'           => 'product-grid',
									'url'               => cck_array_get( $product, 'url', home_url( '/shop/' ) ),
									'title'             => cck_array_get( $product, 'title', __( 'View Product', 'craft-commerce-kit' ) ),
									'short_description' => '',
									'badge_html'        => '',
									'image_html'        => cck_array_get( $product, 'image_html', '' ),
									'image_url'         => '',
									'price_html'        => cck_array_get( $product, 'price_html', '' ),
									'rating_html'       => '',
									'wishlist_html'     => cck_wc_cardify_action_html( '', 'heart', __( 'Wishlist', 'craft-commerce-kit' ), 'cck-product-card__slot-button--wishlist' ),
									'quick_view_html'   => cck_wc_cardify_action_html( '', 'eye', __( 'Quick view', 'craft-commerce-kit' ), 'cck-product-card__slot-button--quick-view' ),
									'add_to_cart_html'  => cck_wc_cardify_action_html( '', 'bag', __( 'Add to cart', 'craft-commerce-kit' ), 'cck-product-card__action-button--cart' ),
								);

								echo cck_wc_render_product_card_markup( $card, 'product-grid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								continue;
							}
							?>
						<?php endforeach; ?>
					<?php else : ?>
						<?php foreach ( array_slice( $demo_products, 0, $limit ) as $product_index => $demo_product ) : ?>
							<?php
							$demo_image = isset( $demo_product['image']['url'] ) && '' !== $demo_product['image']['url']
								? sprintf(
									'<img src="%1$s" alt="%2$s" width="%3$d" height="%4$d" loading="lazy" decoding="async" />',
									esc_url( $demo_product['image']['url'] ),
									esc_attr( cck_to_string( cck_array_get( $demo_product['image'], 'alt', $demo_product['title'] ) ) ),
									absint( cck_array_get( $demo_product['image'], 'width', 1000 ) ),
									absint( cck_array_get( $demo_product['image'], 'height', 1000 ) )
								)
								: '';

							$demo_card = array(
								'id'                => sanitize_key( $demo_product['slug'] ),
								'context'           => 'product-grid',
								'url'               => esc_url_raw( cck_array_get( $demo_product, 'url', home_url( '/shop/' ) ) ),
								'title'             => cck_array_get( $demo_product, 'title', __( 'View Product', 'craft-commerce-kit' ) ),
								'short_description' => cck_array_get( $demo_product, 'short', '' ),
								'badge_html'        => '',
								'image_html'        => $demo_image,
								'image_url'         => cck_array_get( $demo_product['image'], 'url', '' ),
								'price_html'        => cck_array_get( $demo_product, 'price_html', '' ),
								'rating_html'       => '',
								'wishlist_html'     => cck_wc_cardify_action_html( '', 'heart', __( 'Wishlist', 'craft-commerce-kit' ), 'cck-product-card__slot-button--wishlist' ),
								'quick_view_html'   => cck_wc_cardify_action_html( '', 'eye', __( 'Quick view', 'craft-commerce-kit' ), 'cck-product-card__slot-button--quick-view' ),
								'add_to_cart_html'  => cck_wc_cardify_action_html( '', 'bag', __( 'Add to cart', 'craft-commerce-kit' ), 'cck-product-card__action-button--cart' ),
							);

							echo function_exists( 'cck_wc_render_product_card_from_definition' )
								? cck_wc_render_product_card_from_definition( $demo_card ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								: '';
							?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
