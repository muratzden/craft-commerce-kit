<?php
/**
 * Hero component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_engine_render_hero' ) ) {
	/**
	 * Render the component engine hero.
	 *
	 * @param array $atts Component attributes.
	 * @return string
	 */
	function cck_component_engine_render_hero( $atts = array() ) {
		$atts = shortcode_atts(
			array(
				'eyebrow'      => __( 'Craft Commerce Kit', 'craft-commerce-kit' ),
				'title'        => __( 'Premium storefront components for WooCommerce.', 'craft-commerce-kit' ),
				'description'  => __( 'Build reusable commerce sections with a theme-independent component foundation.', 'craft-commerce-kit' ),
				'button_label' => __( 'Explore Components', 'craft-commerce-kit' ),
				'button_url'   => '#',
			),
			$atts,
			'cck_component_hero'
		);

		ob_start();
		?>
		<section class="cck-component cck-hero">
			<div class="cck-container cck-hero__inner">
				<div class="cck-hero__content">
					<p class="cck-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
					<p><?php echo esc_html( $atts['description'] ); ?></p>
					<a class="cck-button cck-button--primary" href="<?php echo esc_url( $atts['button_url'] ); ?>"><?php echo esc_html( $atts['button_label'] ); ?></a>
				</div>
				<div class="cck-hero__visual" aria-hidden="true">
					<div class="cck-hero__card">
						<span><?php esc_html_e( 'Storefront System', 'craft-commerce-kit' ); ?></span>
						<strong><?php esc_html_e( 'Reusable UI', 'craft-commerce-kit' ); ?></strong>
						<small><?php esc_html_e( 'WooCommerce ready', 'craft-commerce-kit' ); ?></small>
					</div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
