<?php
/**
 * Hero component render dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_hero' ) ) {
	/**
	 * Hero component çıktısını oluşturur.
	 *
	 * @param array $atts     Temizlenmiş component değerleri.
	 * @param array $manifest Component manifest verisi.
	 * @return string
	 */
	function cck_component_package_render_hero( $atts = array(), $manifest = array() ) {
		ob_start();
		?>
		<section class="cck-component cck-hero">
			<div class="cck-container cck-hero__inner">
				<div class="cck-hero__content">
					<?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
						<p class="cck-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
					<?php endif; ?>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
					<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
						<p><?php echo esc_html( $atts['subtitle'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $atts['button_text'] ) ) : ?>
						<a class="cck-button cck-button--primary" href="<?php echo esc_url( $atts['button_url'] ); ?>"><?php echo esc_html( $atts['button_text'] ); ?></a>
					<?php endif; ?>
				</div>
				<div class="cck-hero__visual" aria-hidden="true">
					<div class="cck-hero__card">
						<span><?php esc_html_e( 'Storefront System', 'craft-commerce-kit' ); ?></span>
						<strong><?php esc_html_e( 'Reusable UI', 'craft-commerce-kit' ); ?></strong>
						<small><?php esc_html_e( 'WooCommerce ready', 'craft-commerce-kit' ); ?></small>
					</div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
