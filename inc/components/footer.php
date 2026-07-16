<?php
/**
 * Footer component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_footer' ) ) {
	/**
	 * Render a premium storefront footer.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_footer( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'about'     => __( 'A premium WooCommerce starter kit for refined artisan commerce.', 'craft-commerce-kit' ),
				'email'     => __( 'hello@example.com', 'craft-commerce-kit' ),
				'copyright' => sprintf( __( '© %1$s Craft Commerce Kit.', 'craft-commerce-kit' ), gmdate( 'Y' ) ),
			),
			$args,
			'cck_footer'
		);

		ob_start();
		?>
		<footer class="cck-component cck-footer">
			<div class="cck-container">
				<div class="cck-footer__panel">
					<div class="cck-footer__brand">
						<a class="cck-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<span class="cck-footer__logo-mark" aria-hidden="true">CCK</span>
							<span class="cck-footer__logo-text"><?php echo esc_html( function_exists( 'cck_get_active_brand_preset' ) ? cck_array_get( cck_get_active_brand_preset(), 'brand_name', __( 'Craft Commerce Kit', 'craft-commerce-kit' ) ) : __( 'Craft Commerce Kit', 'craft-commerce-kit' ) ); ?></span>
						</a>
						<p class="cck-footer__about"><?php echo esc_html( $args['about'] ); ?></p>
					</div>

					<div class="cck-footer__grid">
						<nav class="cck-footer__column" aria-label="<?php esc_attr_e( 'Collections', 'craft-commerce-kit' ); ?>">
							<h2><?php esc_html_e( 'Collections', 'craft-commerce-kit' ); ?></h2>
							<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Featured', 'craft-commerce-kit' ); ?></a>
							<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'New Arrivals', 'craft-commerce-kit' ); ?></a>
							<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Best Sellers', 'craft-commerce-kit' ); ?></a>
						</nav>

						<nav class="cck-footer__column" aria-label="<?php esc_attr_e( 'Support', 'craft-commerce-kit' ); ?>">
							<h2><?php esc_html_e( 'Support', 'craft-commerce-kit' ); ?></h2>
							<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'craft-commerce-kit' ); ?></a>
							<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'craft-commerce-kit' ); ?></a>
							<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'Shipping & Returns', 'craft-commerce-kit' ); ?></a>
						</nav>

						<div class="cck-footer__column cck-footer__newsletter" aria-label="<?php esc_attr_e( 'Newsletter', 'craft-commerce-kit' ); ?>">
							<h2><?php esc_html_e( 'Newsletter', 'craft-commerce-kit' ); ?></h2>
							<p><?php esc_html_e( 'New collection notes, product drops, and craft stories.', 'craft-commerce-kit' ); ?></p>
							<div class="cck-footer__newsletter-form" aria-hidden="true">
								<span class="cck-footer__input"><?php echo esc_html( $args['email'] ); ?></span>
								<button type="button" class="cck-button cck-button--secondary"><?php esc_html_e( 'Join', 'craft-commerce-kit' ); ?></button>
							</div>
						</div>
					</div>
				</div>

				<div class="cck-footer__bar">
					<p><?php echo esc_html( $args['copyright'] ); ?></p>
					<div class="cck-footer__socials" aria-label="<?php esc_attr_e( 'Social links', 'craft-commerce-kit' ); ?>">
						<a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'craft-commerce-kit' ); ?>"><?php echo cck_render_svg_icon( 'instagram' ); ?></a>
						<a href="#" aria-label="<?php esc_attr_e( 'Pinterest', 'craft-commerce-kit' ); ?>"><?php echo cck_render_svg_icon( 'pinterest' ); ?></a>
						<a href="#" aria-label="<?php esc_attr_e( 'View more', 'craft-commerce-kit' ); ?>"><?php echo cck_render_svg_icon( 'arrow-right' ); ?></a>
					</div>
				</div>
			</div>
		</footer>
		<?php

		return trim( ob_get_clean() );
	}
}
