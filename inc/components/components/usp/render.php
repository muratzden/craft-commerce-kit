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
				'item_one_title'   => '',
				'item_one_text'    => '',
				'item_two_title'   => '',
				'item_two_text'    => '',
				'item_three_title' => '',
				'item_three_text'  => '',
				'surface'          => 'surface',
			)
		);

		$allowed_surfaces = array(
			'transparent',
			'background',
			'surface',
			'surface-alt',
			'dark',
		);

		$surface = in_array( $atts['surface'], $allowed_surfaces, true )
			? $atts['surface']
			: 'surface';

		$classes = array(
			'cck-section',
			'cck-component',
			'cck-usp',
			'cck-surface',
			'cck-surface--' . $surface,
		);

		$items = array(
			array(
				'title' => $atts['item_one_title'],
				'text'  => $atts['item_one_text'],
			),
			array(
				'title' => $atts['item_two_title'],
				'text'  => $atts['item_two_text'],
			),
			array(
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
