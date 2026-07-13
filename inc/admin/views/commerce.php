<?php
/**
 * Components admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_commerce_page' ) ) {
	/**
	 * Render commerce page.
	 *
	 * @return void
	 */
	function cck_render_commerce_page() {
		cck_require_admin_capability();
		cck_render_admin_workspace_open( __( 'Commerce', 'craft-commerce-kit' ), __( 'WooCommerce product data connected to premium craft presentation layers.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Commerce Experience', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-status cck-admin-status--muted"><?php esc_html_e( 'Presentation Layer', 'craft-commerce-kit' ); ?></span></div>
			<ul class="cck-admin-list"><li><?php esc_html_e( 'Product data presentation', 'craft-commerce-kit' ); ?></li><li><?php esc_html_e( 'Archive presentation', 'craft-commerce-kit' ); ?></li><li><?php esc_html_e( 'Checkout reassurance content', 'craft-commerce-kit' ); ?></li></ul>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}