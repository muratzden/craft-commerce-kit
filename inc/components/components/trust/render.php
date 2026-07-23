<?php
/**
 * Trust Block component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_trust_block' ) ) {
	/**
	 * Render the Trust Block component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_trust_block( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts = shortcode_atts(
			array(
				'items' => 'Handmade::Small-batch production with attention to every stitch|Honest materials::Vegetable-tanned leather, honest hardware, and refined finishes|Built to age::Pieces designed to soften beautifully over time',
			),
			is_array( $atts ) ? $atts : array(),
			'cck_trust_block'
		);

		$items = array_filter( array_map( 'trim', explode( '|', $atts['items'] ) ) );

		ob_start();
		?>
		<section class="cck-section cck-trust">
			<div class="cck-container">
				<div class="cck-trust__grid">
					<?php foreach ( $items as $item ) : ?>
						<?php
						$parts = array_map( 'trim', explode( '::', $item, 2 ) );
						$title = isset( $parts[0] ) ? $parts[0] : '';
						$text  = isset( $parts[1] ) ? $parts[1] : $title;
						?>
						<article class="cck-trust__item">
							<span class="cck-trust__icon"><?php echo cck_render_svg_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<h3><?php echo esc_html( $title ); ?></h3>
							<p><?php echo esc_html( $text ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
