<?php
/**
 * Component preview view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_component_preview_page' ) ) {
	/**
	 * Render the component preview page.
	 *
	 * @return void
	 */
	function cck_render_component_preview_page() {
		cck_require_admin_capability();

		$screen      = cck_get_admin_screen( 'component-preview' );
		$component_id = cck_get_component_preview_request_id();
		$data        = cck_get_component_preview_data( $component_id );

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'] );
		?>
		<?php if ( ! empty( $data['notice'] ) ) : ?>
			<div class="notice notice-warning inline"><p><?php echo esc_html( $data['notice'] ); ?></p></div>
		<?php endif; ?>

		<?php if ( empty( $data['is_valid'] ) ) : ?>
			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Component Detail', 'craft-commerce-kit' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Choose a registered component from the Components screen to inspect its metadata and preview.', 'craft-commerce-kit' ); ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=craft-commerce-kit-components' ) ); ?>"><?php esc_html_e( 'Back to Components', 'craft-commerce-kit' ); ?></a></p>
			</div>
		<?php else : ?>
			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading">
					<h2><?php echo esc_html( $data['component_name'] ); ?></h2>
					<span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Preview Ready', 'craft-commerce-kit' ); ?></span>
				</div>
				<p><?php echo esc_html( $data['description'] ); ?></p>
				<div class="cck-admin-template-meta">
					<span><strong><?php esc_html_e( 'ID', 'craft-commerce-kit' ); ?>:</strong> <code><?php echo esc_html( $data['component_id_label'] ); ?></code></span>
					<span><strong><?php esc_html_e( 'Renderer Callback', 'craft-commerce-kit' ); ?>:</strong> <code><?php echo esc_html( $data['callback'] ); ?></code></span>
					<span><strong><?php esc_html_e( 'Callable', 'craft-commerce-kit' ); ?>:</strong> <?php echo esc_html( $data['callback_callable'] ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ) ); ?></span>
				</div>
			</div>

			<div class="cck-admin-grid">
				<div class="cck-admin-card">
					<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Metadata', 'craft-commerce-kit' ); ?></h2></div>
					<table class="cck-admin-table">
						<tbody>
							<tr>
								<th scope="row"><?php esc_html_e( 'Supports', 'craft-commerce-kit' ); ?></th>
								<td><?php echo esc_html( implode( ', ', is_array( $data['supports'] ) ? $data['supports'] : array() ) ); ?></td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Shortcodes', 'craft-commerce-kit' ); ?></th>
								<td>
									<?php if ( ! empty( $data['shortcodes'] ) ) : ?>
										<ul class="cck-admin-code-list">
											<?php foreach ( $data['shortcodes'] as $shortcode ) : ?>
												<li><code><?php echo esc_html( $shortcode ); ?></code></li>
											<?php endforeach; ?>
										</ul>
									<?php else : ?>
										<span class="cck-admin-muted"><?php esc_html_e( 'Not registered.', 'craft-commerce-kit' ); ?></span>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Experience Usage', 'craft-commerce-kit' ); ?></th>
								<td>
									<?php if ( ! empty( $data['experience_usage'] ) ) : ?>
										<ul class="cck-admin-code-list">
											<?php foreach ( $data['experience_usage'] as $experience_name ) : ?>
												<li><?php echo esc_html( $experience_name ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php else : ?>
										<span class="cck-admin-muted"><?php esc_html_e( 'Not currently used.', 'craft-commerce-kit' ); ?></span>
									<?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="cck-admin-card">
					<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Defaults', 'craft-commerce-kit' ); ?></h2></div>
					<table class="cck-admin-table">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'Key', 'craft-commerce-kit' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Value', 'craft-commerce-kit' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data['defaults'] as $key => $value ) : ?>
								<tr>
									<td><code><?php echo esc_html( $key ); ?></code></td>
									<td><code><?php echo esc_html( is_scalar( $value ) ? (string) $value : wp_json_encode( $value ) ); ?></code></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Schema', 'craft-commerce-kit' ); ?></h2></div>
				<table class="cck-admin-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Key', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Type', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Default', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Required', 'craft-commerce-kit' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Sanitize', 'craft-commerce-kit' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data['schema'] as $key => $setting ) : ?>
							<tr>
								<td><code><?php echo esc_html( $key ); ?></code></td>
								<td><code><?php echo esc_html( cck_array_get( $setting, 'type', 'text' ) ); ?></code></td>
								<td><code><?php echo esc_html( is_scalar( cck_array_get( $setting, 'default', '' ) ) ? (string) cck_array_get( $setting, 'default', '' ) : wp_json_encode( cck_array_get( $setting, 'default', '' ) ) ); ?></code></td>
								<td><?php echo esc_html( cck_to_bool( cck_array_get( $setting, 'required', false ) ) ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ) ); ?></td>
								<td><code><?php echo esc_html( cck_array_get( $setting, 'sanitize_callback', 'sanitize_text_field' ) ); ?></code></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Preview', 'craft-commerce-kit' ); ?></h2></div>
				<div class="cck-component-preview">
					<?php if ( ! empty( $data['preview']['success'] ) ) : ?>
						<?php echo wp_kses_post( $data['preview']['html'] ); ?>
					<?php else : ?>
						<div class="notice notice-warning inline"><p><?php echo esc_html( $data['preview']['error'] ); ?></p></div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php
		cck_render_admin_workspace_close();
	}
}
