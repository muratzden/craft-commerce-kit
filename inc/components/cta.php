<?php
/**
 * CTA component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_cta' ) ) {
	/**
	 * Render CTA.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_cta( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'title'        => '',
				'text'         => '',
				'button_label' => '',
				'button_url'   => '',
			),
			$args,
			'cck_cta'
		);

		ob_start();
		?>
		<section class="cck-section cck-cta">
			<div class="cck-container cck-cta__inner">
				<?php if ( '' !== $args['title'] ) : ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $args['text'] ) : ?>
					<p><?php echo esc_html( $args['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $args['button_label'] ) : ?>
					<a class="cck-button cck-button--primary" href="<?php echo esc_url( $args['button_url'] ); ?>"><?php echo esc_html( $args['button_label'] ); ?></a>
				<?php endif; ?>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
