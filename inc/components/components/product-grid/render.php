<?php
/**
 * Product Grid component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_product_grid' ) ) {
	/**
	 * Render the product grid component.
	 *
	 * @param array $atts     Component attributes.
	 * @param array $manifest Component manifest.
	 * @return string
	 */
	function cck_component_package_render_product_grid( $atts = array(), $manifest = array() ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return '<section class="cck-component cck-product-grid"><div class="cck-container"><p>' . esc_html__( 'WooCommerce is required to display products.', 'craft-commerce-kit' ) . '</p></div></section>';
		}

		$query = new WP_Query(
			array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'posts_per_page'      => 4,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		ob_start();
		?>
		<section class="cck-component cck-product-grid">
			<div class="cck-container">
				<div class="cck-section-heading">
					<p class="cck-eyebrow"><?php esc_html_e( 'Latest Products', 'craft-commerce-kit' ); ?></p>
					<h2><?php esc_html_e( 'Fresh from the storefront.', 'craft-commerce-kit' ); ?></h2>
				</div>
				<div class="cck-product-grid__items">
					<?php if ( $query->have_posts() ) : ?>
						<?php while ( $query->have_posts() ) : ?>
							<?php
							$query->the_post();
							$product = wc_get_product( get_the_ID() );

							if ( ! $product ) {
								continue;
							}
							?>
							<article class="cck-product-card">
								<a class="cck-product-card__link" href="<?php the_permalink(); ?>">
									<div class="cck-product-card__image"><?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?></div>
									<h3><?php echo esc_html( get_the_title() ); ?></h3>
									<p class="cck-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
								</a>
							</article>
						<?php endwhile; ?>
					<?php else : ?>
						<p><?php esc_html_e( 'No products found.', 'craft-commerce-kit' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}
}
