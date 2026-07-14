<?php
/**
 * Layouts admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_layouts_page' ) ) {
	/**
	 * Render the manual layout composer screen.
	 *
	 * @return void
	 */
	function cck_render_layouts_page() {
		cck_require_admin_capability();

		$screen = cck_get_admin_screen( 'layouts' );
		$layouts = function_exists( 'cck_get_layout_registry' ) ? cck_get_layout_registry() : array();
		$manual_layout = function_exists( 'cck_get_manual_layout_components' ) ? cck_get_manual_layout_components() : array();
		$preview = function_exists( 'cck_render_layout' ) ? cck_render_layout( 'manual' ) : '';
		$notice = isset( $_GET['cck_notice'] ) ? sanitize_key( wp_unslash( $_GET['cck_notice'] ) ) : '';
		$header_meta = array(
			array(
				'label' => __( 'Version', 'craft-commerce-kit' ),
				'value' => defined( 'CCK_VERSION' ) ? CCK_VERSION : '',
			),
			array(
				'label' => __( 'Layouts', 'craft-commerce-kit' ),
				'value' => (string) count( $layouts ),
			),
			array(
				'label' => __( 'Manual Rows', 'craft-commerce-kit' ),
				'value' => (string) count( $manual_layout ),
			),
		);

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'], $header_meta );
		?>
		<?php if ( 'saved' === $notice ) : ?>
			<div class="notice notice-success inline"><p><?php esc_html_e( 'Manual layout saved.', 'craft-commerce-kit' ); ?></p></div>
		<?php elseif ( 'empty' === $notice ) : ?>
			<div class="notice notice-info inline"><p><?php esc_html_e( 'Manual layout saved without components.', 'craft-commerce-kit' ); ?></p></div>
		<?php endif; ?>

		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<div>
					<p class="cck-admin-kicker"><?php esc_html_e( 'Manual Composition', 'craft-commerce-kit' ); ?></p>
					<h2><?php esc_html_e( 'Editor', 'craft-commerce-kit' ); ?></h2>
				</div>
				<div class="cck-admin-template-actions">
					<code>[cck_layout id="manual"]</code>
				</div>
			</div>
			<p class="cck-admin-muted"><?php esc_html_e( 'Add registered components, reorder them, and edit schema-driven preview attributes without changing the renderer runtime.', 'craft-commerce-kit' ); ?></p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-layout-builder>
				<?php wp_nonce_field( 'cck_save_manual_layout' ); ?>
				<input type="hidden" name="action" value="cck_save_manual_layout" />

				<div class="cck-layout-builder__rows" data-layout-rows>
					<?php if ( ! empty( $manual_layout ) ) : ?>
						<?php foreach ( $manual_layout as $row_index => $component ) : ?>
							<?php echo cck_render_manual_layout_component_row( (string) $row_index, is_array( $component ) ? $component : array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<div class="cck-layout-builder__footer">
					<button type="button" class="button button-secondary" data-layout-add><?php esc_html_e( 'Add Component', 'craft-commerce-kit' ); ?></button>
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Layout', 'craft-commerce-kit' ); ?></button>
				</div>

				<p class="cck-admin-muted"><?php esc_html_e( 'Reordering, adding, removing, and changing component types save automatically. Use Save Layout after editing component fields.', 'craft-commerce-kit' ); ?></p>

				<template id="cck-layout-row-template">
					<?php echo cck_render_manual_layout_component_row( '__INDEX__', array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</template>
			</form>
		</div>

		<div class="cck-admin-card cck-admin-card--wide cck-preview-card">
			<div class="cck-admin-card__heading">
				<h2><?php esc_html_e( 'Manual Preview', 'craft-commerce-kit' ); ?></h2>
				<div class="cck-admin-template-actions">
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=craft-commerce-kit-component-preview&component=hero' ) ); ?>"><?php esc_html_e( 'Open Component Preview', 'craft-commerce-kit' ); ?></a>
				</div>
			</div>
			<p class="cck-admin-muted"><?php esc_html_e( 'This canvas uses the production layout renderer and the registered component callbacks.', 'craft-commerce-kit' ); ?></p>

			<div class="cck-preview-canvas cck-preview-desktop" data-preview-canvas>
				<div class="cck-preview-canvas__viewport">
					<div class="cck-preview-canvas__surface">
						<div class="cck-preview-canvas__frame">
							<div class="cck-preview-canvas__content">
								<?php if ( '' !== trim( $preview ) ) : ?>
									<div class="cck-component-preview">
										<?php echo wp_kses_post( $preview ); ?>
									</div>
								<?php else : ?>
									<div class="cck-admin-preview-empty-state">
										<h3><?php esc_html_e( 'Preview unavailable.', 'craft-commerce-kit' ); ?></h3>
										<p><?php esc_html_e( 'Add a component to the composition to render a live preview.', 'craft-commerce-kit' ); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<h2><?php esc_html_e( 'Registered Layouts', 'craft-commerce-kit' ); ?></h2>
				<span class="cck-admin-badge"><?php echo esc_html( number_format_i18n( count( $layouts ) ) ); ?></span>
			</div>
			<?php if ( empty( $layouts ) ) : ?>
				<div class="cck-admin-empty-state">
					<div class="cck-admin-empty-state__title"><?php esc_html_e( 'No layouts registered.', 'craft-commerce-kit' ); ?></div>
					<p><?php esc_html_e( 'The registry is empty, so there is nothing to inspect yet.', 'craft-commerce-kit' ); ?></p>
				</div>
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
