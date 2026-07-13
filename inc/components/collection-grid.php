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
				'items'   => 'Featured,Curated highlights,/shop/featured/,featured.webp|New Arrivals,Fresh seasonal additions,/shop/new-arrivals/,new-arrivals.webp|Best Sellers,Customer-loved essentials,/shop/best-sellers/,best-sellers.webp',
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
			$parts = array_map( 'trim', explode( ',', $row, 4 ) );

			if ( empty( $parts[0] ) ) {
				continue;
			}

			$image_file = isset( $parts[3] ) && '' !== $parts[3] ? sanitize_file_name( $parts[3] ) : 'featured.webp';

			$items[] = array(
				'label'       => $parts[0],
				'subtitle'    => isset( $parts[1] ) && '' !== $parts[1] ? $parts[1] : __( 'Explore the edit', 'craft-commerce-kit' ),
				'url'         => isset( $parts[2] ) && '' !== $parts[2] ? $parts[2] : '#',
				'image'       => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( $image_file, $parts[0] ) : array(),
			);
		}

		ob_start();
		?>
		<section class="cck-section cck-collection-grid" style="<?php echo esc_attr( '--cck-grid-columns:' . $columns ); ?>">
			<div class="cck-container cck-collection-grid__inner">
				<?php foreach ( $items as $item ) : ?>
					<a class="cck-collection-card" href="<?php echo esc_url( $item['url'] ); ?>">
						<span class="cck-collection-card__media">
							<?php if ( ! empty( $item['image']['url'] ) ) : ?>
								<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['image']['alt'] ); ?>" width="<?php echo esc_attr( (string) absint( $item['image']['width'] ) ); ?>" height="<?php echo esc_attr( (string) absint( $item['image']['height'] ) ); ?>" loading="lazy" decoding="async" />
							<?php endif; ?>
						</span>
						<span class="cck-collection-card__overlay" aria-hidden="true"></span>
						<span class="cck-collection-card__content">
							<span class="cck-collection-card__eyebrow"><?php echo esc_html( __( 'Collection', 'craft-commerce-kit' ) ); ?></span>
							<strong><?php echo esc_html( $item['label'] ); ?></strong>
							<span><?php echo esc_html( $item['subtitle'] ); ?></span>
							<span class="cck-collection-card__arrow"><?php echo cck_render_svg_icon( 'arrow-right' ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
