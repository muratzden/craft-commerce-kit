<?php
/**
 * Section Title component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_section_title' ) ) {
	/**
	 * Render the Section Title component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_section_title( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts = wp_parse_args(
			is_array( $atts ) ? $atts : array(),
			array(
				'eyebrow' => '',
				'title'   => '',
				'text'    => '',
				'align'   => 'left',
				'surface' => 'transparent',
			)
		);

		$allowed_alignments = array(
			'left',
			'center',
			'right',
		);

		$align = in_array( $atts['align'], $allowed_alignments, true )
			? $atts['align']
			: 'left';

		$classes = array_merge(
			array(
				'cck-section-title',
				'cck-section-title--' . $align,
			),
			cck_component_get_surface_classes(
				$atts['surface'],
				'transparent'
			)
		);

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( '' !== $atts['eyebrow'] ) : ?>
				<p class="cck-eyebrow">
					<?php echo esc_html( $atts['eyebrow'] ); ?>
				</p>
			<?php endif; ?>

			<?php if ( '' !== $atts['title'] ) : ?>
				<h2><?php echo esc_html( $atts['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( '' !== $atts['text'] ) : ?>
				<p><?php echo esc_html( $atts['text'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php

		return trim( ob_get_clean() );
	}
}
