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
				'title'      => __( 'The Atelier Tote', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$320' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'featured.webp', __( 'The Atelier Tote', 'craft-commerce-kit' ) ) : array(),
			),
			array(
				'title'      => __( 'Heritage Wallet', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$128' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'new-arrivals.webp', __( 'Heritage Wallet', 'craft-commerce-kit' ) ) : array(),
			),
			array(
				'title'      => __( 'Daily Carry Pouch', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$86' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'best-sellers.webp', __( 'Daily Carry Pouch', 'craft-commerce-kit' ) ) : array(),
			),
			array(
				'title'      => __( 'Travel Companion', 'craft-commerce-kit' ),
				'price_html'  => wp_kses_post( '$214' ),
				'url'        => home_url( '/shop/' ),
				'image'      => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'hero.webp', __( 'Travel Companion', 'craft-commerce-kit' ) ) : array(),
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
							<article class="cck-product-card">
								<a class="cck-product-card__link" href="<?php echo esc_url( $product['url'] ); ?>">
									<div class="cck-product-card__image">
										<?php echo wp_kses_post( $product['image_html'] ); ?>
									</div>

									<h3><?php echo esc_html( $product['title'] ); ?></h3>

									<?php if ( ! empty( $product['price_html'] ) ) : ?>
										<p class="cck-product-card__price"><?php echo wp_kses_post( $product['price_html'] ); ?></p>
									<?php endif; ?>
									<span class="cck-button cck-button--ghost"><?php esc_html_e( 'View Product', 'craft-commerce-kit' ); ?></span>
								</a>
							</article>
						<?php endforeach; ?>
					<?php else : ?>
						<?php foreach ( array_slice( $demo_products, 0, $limit ) as $demo_product ) : ?>
							<article class="cck-product-card cck-product-card--demo">
								<a class="cck-product-card__link" href="<?php echo esc_url( $demo_product['url'] ); ?>">
									<div class="cck-product-card__image">
										<?php if ( ! empty( $demo_product['image']['url'] ) ) : ?>
											<img src="<?php echo esc_url( $demo_product['image']['url'] ); ?>" alt="<?php echo esc_attr( $demo_product['image']['alt'] ); ?>" width="<?php echo esc_attr( (string) absint( $demo_product['image']['width'] ) ); ?>" height="<?php echo esc_attr( (string) absint( $demo_product['image']['height'] ) ); ?>" loading="lazy" decoding="async" />
										<?php endif; ?>
									</div>
									<h3><?php echo esc_html( $demo_product['title'] ); ?></h3>
									<p class="cck-product-card__price"><?php echo wp_kses_post( $demo_product['price_html'] ); ?></p>
									<span class="cck-button cck-button--ghost"><?php esc_html_e( 'View Product', 'craft-commerce-kit' ); ?></span>
								</a>
							</article>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
