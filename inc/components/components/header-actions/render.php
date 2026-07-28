<?php
/**
 * Header Actions component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_header_actions' ) ) {
	/**
	 * Render the Header Actions component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_header_actions( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts   = shortcode_atts( array(), is_array( $atts ) ? $atts : array(), 'cck_header_actions' );
		$counts = cck_get_layout_action_counts();
		$urls   = cck_get_layout_action_urls();
		$labels = array(
			'account'  => __( 'Account', 'craft-commerce-kit' ),
			'cart'     => __( 'Cart', 'craft-commerce-kit' ),
		);

		unset( $atts );

		ob_start();
		?>
		<div class="cck-site-header__actions">
			<a class="cck-header-action cck-header-action--account" href="<?php echo esc_url( $urls['account'] ); ?>" aria-label="<?php echo esc_attr( $labels['account'] ); ?>">
				<span class="cck-header-action__icon" aria-hidden="true"><?php echo cck_render_layout_action_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $labels['account'] ); ?></span>
			</a>

			<a class="cck-cart-action cck-header-action--cart" href="<?php echo esc_url( $urls['cart'] ); ?>" aria-label="<?php echo esc_attr( $labels['cart'] ); ?>">
				<span class="cck-header-action__icon" aria-hidden="true"><?php echo cck_render_layout_action_icon( 'bag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="cck-header-action__count"<?php echo $counts['cart'] > 0 ? ' data-count="' . esc_attr( (string) $counts['cart'] ) . '"' : ''; ?>><?php echo $counts['cart'] > 0 ? esc_html( (string) $counts['cart'] ) : ''; ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $labels['cart'] ); ?></span>
			</a>
		</div>
		<?php

		return trim( ob_get_clean() );
	}
}