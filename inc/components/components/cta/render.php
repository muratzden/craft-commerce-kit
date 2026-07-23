<?php
/**
 * CTA component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_cta' ) ) {
	/**
	 * Render the CTA component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_cta( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts = wp_parse_args(
			is_array( $atts ) ? $atts : array(),
			array(
				'title'        => '',
				'text'         => '',
				'button_label' => '',
				'button_url'   => '',
			)
		);

		ob_start();
		?>
		<section class="cck-section cck-cta">
			<div class="cck-container cck-cta__inner">
				<?php if ( '' !== $atts['title'] ) : ?>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php endif; ?>

				<?php if ( '' !== $atts['text'] ) : ?>
					<p><?php echo esc_html( $atts['text'] ); ?></p>
				<?php endif; ?>

				<?php if ( '' !== $atts['button_label'] ) : ?>
					<a class="cck-button cck-button--primary" href="<?php echo esc_url( $atts['button_url'] ); ?>"><?php echo esc_html( $atts['button_label'] ); ?></a>
				<?php endif; ?>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
