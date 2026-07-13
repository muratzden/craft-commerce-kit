<?php
/**
 * Components admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_components_page' ) ) {
	/**
	 * Render the components catalog.
	 *
	 * @return void
	 */
	function cck_render_components_page() {
		cck_require_admin_capability();

		$screen     = cck_get_admin_screen( 'components' );
		$components = cck_get_admin_component_rows();

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'] );
		?>
		<div class="postbox">
			<h2 class="hndle"><span><?php esc_html_e( 'Registered Components', 'craft-commerce-kit' ); ?></span></h2>
			<div class="inside">
				<?php if ( empty( $components ) ) : ?>
					<div class="notice notice-warning inline"><p><?php esc_html_e( 'No components are currently registered.', 'craft-commerce-kit' ); ?></p></div>
				<?php else : ?>
					<table class="widefat striped">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'ID', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Label', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Callback', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Supports', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Defaults', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Schema', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Status', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Actions', 'craft-commerce-kit' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $components as $component ) : ?>
								<tr>
									<td><code><?php echo esc_html( $component['id'] ); ?></code></td>
									<td>
										<strong><?php echo esc_html( $component['label'] ); ?></strong>
										<?php if ( ! empty( $component['aliases'] ) ) : ?>
											<br><span class="description"><?php echo esc_html( sprintf( __( 'Alias: %s', 'craft-commerce-kit' ), implode( ', ', $component['aliases'] ) ) ); ?></span>
										<?php endif; ?>
									</td>
									<td><code><?php echo esc_html( $component['callback'] ); ?></code></td>
									<td><?php echo esc_html( (string) $component['supports_count'] ); ?></td>
									<td><?php echo esc_html( (string) $component['defaults_count'] ); ?></td>
									<td><?php echo esc_html( (string) $component['schema_fields_count'] ); ?></td>
									<td><?php echo esc_html( $component['status'] ); ?></td>
									<td>
										<?php if ( ! empty( $component['preview_url'] ) ) : ?>
											<a class="button button-small" href="<?php echo esc_url( $component['preview_url'] ); ?>"><?php esc_html_e( 'Preview', 'craft-commerce-kit' ); ?></a>
										<?php else : ?>
											<span class="cck-admin-muted"><?php esc_html_e( 'Unavailable', 'craft-commerce-kit' ); ?></span>
										<?php endif; ?>
									</td>
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
