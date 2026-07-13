<?php
/**
 * Experiences admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_experiences_page' ) ) {
	/**
	 * Render the experiences catalog.
	 *
	 * @return void
	 */
	function cck_render_experiences_page() {
		cck_require_admin_capability();

		$screen      = cck_get_admin_screen( 'experiences' );
		$experiences = cck_get_admin_experience_rows();

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'] );
		?>
		<div class="postbox">
			<h2 class="hndle"><span><?php esc_html_e( 'Registered Experiences', 'craft-commerce-kit' ); ?></span></h2>
			<div class="inside">
				<?php if ( empty( $experiences ) ) : ?>
					<div class="notice notice-warning inline"><p><?php esc_html_e( 'No experiences are currently registered.', 'craft-commerce-kit' ); ?></p></div>
				<?php else : ?>
					<table class="widefat striped">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'ID', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Label', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Brand', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Layout', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Section count', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Status', 'craft-commerce-kit' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $experiences as $experience ) : ?>
								<tr>
									<td><code><?php echo esc_html( $experience['id'] ); ?></code></td>
									<td><?php echo esc_html( $experience['label'] ); ?></td>
									<td><?php echo esc_html( '' !== $experience['brand'] ? $experience['brand'] : __( 'None', 'craft-commerce-kit' ) ); ?></td>
									<td><?php echo esc_html( '' !== $experience['layout'] ? $experience['layout'] : __( 'None', 'craft-commerce-kit' ) ); ?></td>
									<td><?php echo esc_html( (string) $experience['section_count'] ); ?></td>
									<td><?php echo esc_html( $experience['status'] ); ?></td>
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
