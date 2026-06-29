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
	 * @param array $atts     Component attributes.
	 * @param array $manifest Component manifest.
	 * @return string
	 */
	function cck_component_package_render_usp( $atts = array(), $manifest = array() ) {
		$items = array(
			array(
				'title' => __( 'Handmade Quality', 'craft-commerce-kit' ),
				'text'  => __( 'Designed for product stories that value material, process, and detail.', 'craft-commerce-kit' ),
			),
			array(
				'title' => __( 'WooCommerce Ready', 'craft-commerce-kit' ),
				'text'  => __( 'Built to complement WooCommerce storefront flows without replacing native behavior.', 'craft-commerce-kit' ),
			),
			array(
				'title' => __( 'Modular Design', 'craft-commerce-kit' ),
				'text'  => __( 'Reusable sections can be rendered independently through the component engine.', 'craft-commerce-kit' ),
			),
		);

		ob_start();
		?>
		<section class="cck-component cck-usp">
			<div class="cck-container cck-usp-grid">
				<?php foreach ( $items as $item ) : ?>
					<article class="cck-usp-item">
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
