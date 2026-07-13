<?php
/**
 * Brands admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_brand_page' ) ) {
	/**
	 * Render the brands catalog.
	 *
	 * @return void
	 */
	function cck_render_brand_page() {
		cck_require_admin_capability();

		$screen = cck_get_admin_screen( 'brands' );
		$brands = cck_get_admin_brand_rows();

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'] );
		?>
		<div class="postbox">
			<h2 class="hndle"><span><?php esc_html_e( 'Registered Brands', 'craft-commerce-kit' ); ?></span></h2>
			<div class="inside">
				<?php if ( empty( $brands ) ) : ?>
					<div class="notice notice-warning inline"><p><?php esc_html_e( 'No brands are currently registered.', 'craft-commerce-kit' ); ?></p></div>
				<?php else : ?>
					<table class="widefat striped">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'ID', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Label / name', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Preset source', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Experience', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Status', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Attributes', 'craft-commerce-kit' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $brands as $brand ) : ?>
								<tr>
									<td><code><?php echo esc_html( $brand['id'] ); ?></code></td>
									<td><?php echo esc_html( $brand['label'] ); ?></td>
									<td><?php echo esc_html( $brand['source'] ); ?></td>
									<td><?php echo esc_html( '' !== $brand['experience'] ? $brand['experience'] : __( 'None', 'craft-commerce-kit' ) ); ?></td>
									<td><?php echo esc_html( $brand['status'] ); ?></td>
									<td><?php echo esc_html( (string) $brand['attribute_count'] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
