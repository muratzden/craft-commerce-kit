<?php
/**
 * Product Grid component render file.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_product_grid' ) ) {
	function cck_component_package_render_product_grid( $atts = array(), $manifest = array() ) {
		$result = function_exists( 'cck_runtime_query_products' )
			? cck_runtime_query_products(
				array(
					'type'  => isset( $atts['type'] ) ? $atts['type'] : 'latest',
					'limit' => isset( $atts['limit'] ) ? $atts['limit'] : 4,
				)
			)
			: array();

		$result   = is_array( $result ) ? $result : array();
		$products = isset( $result['items'] ) && is_array( $result['items'] ) ? $result['items'] : array();
		$available = ! empty( $result['available'] );

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
					<?php if ( ! $available ) : ?>
						<p><?php esc_html_e( 'WooCommerce is required to display products.', 'craft-commerce-kit' ); ?></p>
					<?php elseif ( ! empty( $products ) ) : ?>
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
								</a>
							</article>
						<?php endforeach; ?>
					<?php else : ?>
						<p><?php esc_html_e( 'No products found.', 'craft-commerce-kit' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
