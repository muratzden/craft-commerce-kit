<?php
/**
 * USP component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_usp' ) ) {
	/**
	 * Render the USP component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_usp( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts = wp_parse_args(
			is_array( $atts ) ? $atts : array(),
			array(
				'item_one_icon'    => 'star-filled',
				'item_one_title'   => '',
				'item_one_text'    => '',
				'item_two_icon'    => 'yes-alt',
				'item_two_title'   => '',
				'item_two_text'    => '',
				'item_three_icon'  => 'awards',
				'item_three_title' => '',
				'item_three_text'  => '',
				'surface'          => 'surface',
			)
		);

		$classes = array_merge(
			array(
				'cck-section',
				'cck-component',
				'cck-usp',
			),
			cck_component_get_surface_classes(
				$atts['surface'],
				'surface'
			)
		);

		$items = array(
			array(
				'icon'  => $atts['item_one_icon'],
				'title' => $atts['item_one_title'],
				'text'  => $atts['item_one_text'],
			),
			array(
				'icon'  => $atts['item_two_icon'],
				'title' => $atts['item_two_title'],
				'text'  => $atts['item_two_text'],
			),
			array(
				'icon'  => $atts['item_three_icon'],
				'title' => $atts['item_three_title'],
				'text'  => $atts['item_three_text'],
			),
		);

		ob_start();
		?>
		<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="cck-container cck-usp-grid">
				<?php foreach ( $items as $item ) : ?>
					<article class="cck-usp-item">
						<?php if ( '' !== $item['icon'] ) : ?>
							<span class="dashicons dashicons-<?php echo esc_attr( $item['icon'] ); ?> cck-usp-item__icon" aria-hidden="true"></span>
						<?php endif; ?>
						<?php if ( '' !== $item['title'] ) : ?>
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>

						<?php if ( '' !== $item['text'] ) : ?>
							<p><?php echo esc_html( $item['text'] ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}

