<?php
/**
 * USP component render dosyas?.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_usp' ) ) {
	/**
	 * USP component ??kt?s?n? olu?turur.
	 *
	 * @param array $atts     Temizlenmi? component de?erleri.
	 * @param array $manifest Component manifest verisi.
	 * @return string
	 */
	function cck_component_package_render_usp( $atts = array(), $manifest = array() ) {
		$items = array(
			array(
				'title' => isset( $atts['item_one_title'] ) ? $atts['item_one_title'] : '',
				'text'  => isset( $atts['item_one_text'] ) ? $atts['item_one_text'] : '',
			),
			array(
				'title' => isset( $atts['item_two_title'] ) ? $atts['item_two_title'] : '',
				'text'  => isset( $atts['item_two_text'] ) ? $atts['item_two_text'] : '',
			),
			array(
				'title' => isset( $atts['item_three_title'] ) ? $atts['item_three_title'] : '',
				'text'  => isset( $atts['item_three_text'] ) ? $atts['item_three_text'] : '',
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
