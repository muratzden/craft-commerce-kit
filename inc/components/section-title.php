<?php
/**
 * Section title component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_section_title' ) ) {
	/**
	 * Render a section title.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_section_title( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'eyebrow' => '',
				'title'   => '',
				'text'    => '',
				'align'   => 'left',
			),
			$args,
			'cck_section_title'
		);

		$align = in_array( $args['align'], array( 'left', 'center', 'right' ), true ) ? $args['align'] : 'left';

		ob_start();
		?>
		<div class="cck-section-title cck-section-title--<?php echo esc_attr( $align ); ?>">
			<?php if ( '' !== $args['eyebrow'] ) : ?>
				<p class="cck-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $args['title'] ) : ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== $args['text'] ) : ?>
				<p><?php echo esc_html( $args['text'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php

		return trim( ob_get_clean() );
	}
}
