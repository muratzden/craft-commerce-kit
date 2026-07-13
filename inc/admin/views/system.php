<?php
/**
 * Components admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_system_page' ) ) {
	/**
	 * Render system page.
	 *
	 * @return void
	 */
	function cck_render_system_page() {
		cck_require_admin_capability();
		$theme              = wp_get_theme();
		$templates          = function_exists( 'cck_get_templates' ) ? cck_get_templates() : array();
		$woocommerce_active = cck_is_woocommerce_active();
		$items              = array(
			array( 'label' => __( 'Plugin Version', 'craft-commerce-kit' ), 'value' => CCK_VERSION ),
			array( 'label' => __( 'WordPress Version', 'craft-commerce-kit' ), 'value' => get_bloginfo( 'version' ) ),
			array( 'label' => __( 'PHP Version', 'craft-commerce-kit' ), 'value' => PHP_VERSION ),
			array( 'label' => __( 'WooCommerce', 'craft-commerce-kit' ), 'value' => $woocommerce_active ? __( 'Active', 'craft-commerce-kit' ) : __( 'Inactive', 'craft-commerce-kit' ) ),
			array( 'label' => __( 'Theme', 'craft-commerce-kit' ), 'value' => $theme->exists() ? $theme->get( 'Name' ) : __( 'Unknown', 'craft-commerce-kit' ) ),
			array( 'label' => __( 'Loaded Components', 'craft-commerce-kit' ), 'value' => count( cck_get_admin_components() ) ),
			array( 'label' => __( 'Loaded Templates', 'craft-commerce-kit' ), 'value' => count( $templates ) ),
		);

		cck_render_admin_workspace_open( __( 'System', 'craft-commerce-kit' ), __( 'Compatibility and readiness checks for Craft Commerce Kit rendering.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-overview">
				<?php foreach ( $items as $item ) : ?>
					<div class="cck-admin-overview__item"><span class="cck-admin-overview__check" aria-hidden="true">✓</span><span class="cck-admin-overview__label"><?php echo esc_html( $item['label'] ); ?></span><strong><?php echo esc_html( $item['value'] ); ?></strong></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}