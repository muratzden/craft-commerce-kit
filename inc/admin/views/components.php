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
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<h2><?php esc_html_e( 'Registered Components', 'craft-commerce-kit' ); ?></h2>
				<span class="cck-admin-badge"><?php echo esc_html( number_format_i18n( count( $components ) ) ); ?></span>
			</div>

			<?php if ( empty( $components ) ) : ?>
				<div class="cck-admin-empty-state">
					<div class="cck-admin-empty-state__title"><?php esc_html_e( 'No components are currently registered.', 'craft-commerce-kit' ); ?></div>
					<p><?php esc_html_e( 'The registry is empty, so there is nothing to preview yet.', 'craft-commerce-kit' ); ?></p>
				</div>
			<?php else : ?>
				<div class="cck-admin-component-grid">
					<?php foreach ( $components as $component ) : ?>
						<article class="cck-admin-component-card">
							<div class="cck-admin-card__heading">
								<h3><?php echo esc_html( $component['label'] ); ?></h3>
								<span class="cck-admin-status <?php echo esc_attr( 'Callable' === $component['status'] ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>"><?php echo esc_html( $component['status'] ); ?></span>
							</div>

							<p class="cck-admin-kicker"><code><?php echo esc_html( $component['id'] ); ?></code></p>

							<div class="cck-admin-component-tags">
								<?php if ( ! empty( $component['version'] ) ) : ?>
									<span><?php echo esc_html( sprintf( __( 'Version %s', 'craft-commerce-kit' ), $component['version'] ) ); ?></span>
								<?php endif; ?>
								<span><?php echo esc_html( sprintf( __( '%d supports', 'craft-commerce-kit' ), (int) $component['supports_count'] ) ); ?></span>
								<span><?php echo esc_html( sprintf( __( '%d defaults', 'craft-commerce-kit' ), (int) $component['defaults_count'] ) ); ?></span>
								<span><?php echo esc_html( sprintf( __( '%d schema fields', 'craft-commerce-kit' ), (int) $component['schema_fields_count'] ) ); ?></span>
							</div>

							<dl class="cck-admin-summary-list">
								<dt><?php esc_html_e( 'Callback', 'craft-commerce-kit' ); ?></dt>
								<dd><code><?php echo esc_html( $component['callback'] ); ?></code></dd>
								<dt><?php esc_html_e( 'Used By', 'craft-commerce-kit' ); ?></dt>
								<dd>
									<?php if ( ! empty( $component['used_by'] ) ) : ?>
										<?php echo esc_html( implode( ', ', $component['used_by'] ) ); ?>
									<?php else : ?>
										<span class="cck-admin-muted"><?php esc_html_e( 'Not currently used', 'craft-commerce-kit' ); ?></span>
									<?php endif; ?>
								</dd>
								<dt><?php esc_html_e( 'Supports', 'craft-commerce-kit' ); ?></dt>
								<dd><?php echo esc_html( (string) $component['supports_count'] ); ?></dd>
							</dl>

							<div class="cck-admin-template-actions">
								<?php if ( ! empty( $component['preview_url'] ) ) : ?>
									<a class="button button-primary button-small" href="<?php echo esc_url( $component['preview_url'] ); ?>"><?php esc_html_e( 'Open Preview →', 'craft-commerce-kit' ); ?></a>
								<?php else : ?>
									<span class="cck-admin-muted"><?php esc_html_e( 'Preview unavailable', 'craft-commerce-kit' ); ?></span>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
