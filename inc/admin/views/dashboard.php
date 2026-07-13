<?php
/**
 * Overview admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_admin_page' ) ) {
	/**
	 * Render the overview page.
	 *
	 * @return void
	 */
	function cck_render_admin_page() {
		cck_require_admin_capability();

		$screen  = cck_get_admin_screen( 'overview' );
		$summary = cck_get_admin_overview_data();
		$brand   = is_array( $summary['default_brand'] ) ? $summary['default_brand'] : array();
		$brand_name = '';

		if ( ! empty( $summary['active_brand_name'] ) ) {
			$brand_name = $summary['active_brand_name'];
		} elseif ( ! empty( $brand['name'] ) ) {
			$brand_name = $brand['name'];
		} elseif ( ! empty( $brand['brand_name'] ) ) {
			$brand_name = $brand['brand_name'];
		} else {
			$brand_name = __( 'Unknown', 'craft-commerce-kit' );
		}

		$woocommerce_status = ! empty( $summary['woocommerce_active'] ) ? __( 'Active', 'craft-commerce-kit' ) : __( 'Inactive', 'craft-commerce-kit' );

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'] );
		?>
		<div class="postbox">
			<h2 class="hndle"><span><?php esc_html_e( 'Runtime Summary', 'craft-commerce-kit' ); ?></span></h2>
			<div class="inside">
				<table class="widefat striped">
					<tbody>
						<tr>
							<th scope="row"><?php esc_html_e( 'Plugin version', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( $summary['plugin_version'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Registered components', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( (string) $summary['registered_components'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Registered experiences', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( (string) $summary['registered_experiences'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Registered brands', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( (string) $summary['registered_brands'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Active brand', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( $brand_name ); ?><?php if ( ! empty( $summary['active_brand_id'] ) ) : ?> <code><?php echo esc_html( $summary['active_brand_id'] ); ?></code><?php endif; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'WooCommerce', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( $woocommerce_status ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Environment summary', 'craft-commerce-kit' ); ?></th>
							<td><?php echo esc_html( $summary['environment_summary'] ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="notice notice-info inline">
			<p><?php esc_html_e( 'This screen is read-only and reflects the live runtime registry.', 'craft-commerce-kit' ); ?></p>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
