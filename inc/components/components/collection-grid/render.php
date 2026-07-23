<?php
/**
 * Collection Grid component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_collection_grid' ) ) {
	/**
	 * Render the Collection Grid component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_collection_grid( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts = wp_parse_args(
			is_array( $atts ) ? $atts : array(),
			array(
				'items'   => 'Featured,Curated highlights,/shop/featured/,featured.webp|New Arrivals,Fresh seasonal additions,/shop/new-arrivals/,new-arrivals.webp|Best Sellers,Customer-loved essentials,/shop/best-sellers/,best-sellers.webp',
				'columns' => '2',
			)
		);

		$columns = absint( $atts['columns'] );
		$columns = min( 4, max( 1, $columns ) );
		$items   = array();
		$rows    = array_filter( array_map( 'trim', explode( '|', (string) $atts['items'] ) ) );

		foreach ( $rows as $row ) {
			$parts = array_map( 'trim', explode( ',', $row, 4 ) );

			if ( empty( $parts[0] ) ) {
				continue;
			}

			$image_file = isset( $parts[3] ) && '' !== $parts[3]
				? sanitize_file_name( $parts[3] )
				: 'featured.webp';

			$items[] = array(
				'label'    => $parts[0],
				'subtitle' => isset( $parts[1] ) && '' !== $parts[1]
					? $parts[1]
					: __( 'Explore the edit', 'craft-commerce-kit' ),
				'url'      => isset( $parts[2] ) && '' !== $parts[2] ? $parts[2] : '#',
				'image'    => function_exists( 'cck_get_demo_asset' )
					? cck_get_demo_asset( $image_file, $parts[0] )
					: array(),
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
							<span class="cck-collection-card__eyebrow"><?php echo esc_html__( 'Collection', 'craft-commerce-kit' ); ?></span>
							<strong><?php echo esc_html( $item['label'] ); ?></strong>
							<span><?php echo esc_html( $item['subtitle'] ); ?></span>
							<span class="cck-collection-card__arrow"><?php echo cck_render_svg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
