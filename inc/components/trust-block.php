<?php
/**
 * Trust block component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_trust_block' ) ) {
	/**
	 * Render trust notes.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_trust_block( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'items' => 'Handmade|Honest materials|Small-batch production|Built to age',
			),
			$args,
			'cck_trust_block'
		);

		$items = array_filter( array_map( 'trim', explode( '|', $args['items'] ) ) );

		ob_start();
		?>
		<section class="cck-section cck-trust">
			<div class="cck-container">
				<div class="cck-trust__grid">
					<?php foreach ( $items as $item ) : ?>
						<div class="cck-trust__item"><?php echo esc_html( $item ); ?></div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
