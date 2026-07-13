<?php
/**
 * Components admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_layouts_page' ) ) {
	/**
	 * Layouts admin sayfasını render eder.
	 *
	 * @return void
	 */
	function cck_render_layouts_page() {
		cck_require_admin_capability();
		$layouts = function_exists( 'cck_get_layout_registry' ) ? cck_get_layout_registry() : array();

		cck_render_admin_workspace_open( __( 'Layouts', 'craft-commerce-kit' ), __( 'Renderable component sequences that can be used through Craft Commerce Kit shortcodes.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Registered Layouts', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-badge"><?php echo esc_html( count( $layouts ) ); ?></span></div>
			<?php if ( empty( $layouts ) ) : ?>
				<p><?php esc_html_e( 'No layouts registered.', 'craft-commerce-kit' ); ?></p>
			<?php else : ?>
				<table class="cck-admin-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Layout', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Layout ID', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Version', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Component Sequence', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Shortcode', 'craft-commerce-kit' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $layouts as $layout ) : ?>
							<?php $layout_data = cck_get_admin_layout_display_data( $layout ); ?>
							<tr>
								<td><strong><?php echo esc_html( $layout_data['name'] ); ?></strong><?php if ( '' !== $layout_data['description'] ) : ?><br><span><?php echo esc_html( $layout_data['description'] ); ?></span><?php endif; ?></td>
								<td><code><?php echo esc_html( $layout_data['id'] ); ?></code></td>
								<td><?php echo esc_html( $layout_data['version'] ); ?></td>
								<td><div class="cck-admin-component-tags"><?php foreach ( $layout_data['component_ids'] as $component_id ) : ?><span><?php echo esc_html( $component_id ); ?></span><?php endforeach; ?></div></td>
								<td><div class="cck-admin-shortcode-example"><code><?php echo esc_html( $layout_data['shortcode'] ); ?></code><button type="button" class="button cck-admin-copy" data-cck-copy="<?php echo esc_attr( $layout_data['shortcode'] ); ?>"><?php esc_html_e( 'Copy', 'craft-commerce-kit' ); ?></button></div></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}