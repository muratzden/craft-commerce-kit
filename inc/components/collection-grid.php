<?php
/**
 * Collection grid component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_collection_grid' ) ) {
	/**
	 * Render collection grid.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_collection_grid( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'items'   => 'Bags,/product-category/bags/|Wallets,/product-category/wallets/',
				'columns' => '2',
			),
			$args,
			'cck_collection_grid'
		);

		$columns = absint( $args['columns'] );
		$columns = min( 4, max( 1, $columns ) );
		$items   = array();
		$rows    = array_filter( array_map( 'trim', explode( '|', (string) $args['items'] ) ) );

		foreach ( $rows as $row ) {
			$parts = array_map( 'trim', explode( ',', $row, 2 ) );

			if ( empty( $parts[0] ) ) {
				continue;
			}

			$items[] = array(
				'label' => $parts[0],
				'url'   => isset( $parts[1] ) ? $parts[1] : '#',
			);
		}

		ob_start();
		?>
		<section class="cck-section cck-collection-grid" style="<?php echo esc_attr( '--cck-grid-columns:' . $columns ); ?>">
			<div class="cck-container cck-collection-grid__inner">
				<?php foreach ( $items as $item ) : ?>
					<a class="cck-collection-card" href="<?php echo esc_url( $item['url'] ); ?>">
						<span><?php echo esc_html( $item['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
