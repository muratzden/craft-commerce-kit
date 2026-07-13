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

		$screen       = cck_get_admin_screen( 'component-preview' );
		$component_id = cck_get_component_preview_request_id();
		$data         = cck_get_component_preview_data( $component_id );
		$header_meta   = array();

		if ( ! empty( $data['is_valid'] ) ) {
			$header_meta = array(
				array(
					'label' => __( 'Component', 'craft-commerce-kit' ),
					'value' => $data['component_name'],
				),
				array(
					'label' => __( 'Version', 'craft-commerce-kit' ),
					'value' => isset( $data['version'] ) ? $data['version'] : '',
				),
				array(
					'label' => __( 'Preview', 'craft-commerce-kit' ),
					'value' => __( 'Read only', 'craft-commerce-kit' ),
				),
			);
		}

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'], $header_meta );

		$format_value = static function ( $value ) {
			if ( is_array( $value ) ) {
				if ( empty( $value ) ) {
					return '—';
				}

				return wp_json_encode( $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			}

			$value = is_scalar( $value ) ? trim( (string) $value ) : '';

			return '' === $value ? '—' : $value;
		};
		?>
		<?php if ( ! empty( $data['notice'] ) ) : ?>
			<div class="notice notice-warning inline"><p><?php echo esc_html( $data['notice'] ); ?></p></div>
		<?php endif; ?>

		<?php if ( empty( $data['is_valid'] ) ) : ?>
			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Component Detail', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-status cck-admin-status--muted"><?php esc_html_e( 'Unknown component', 'craft-commerce-kit' ); ?></span>
				</div>
				<p><?php esc_html_e( 'Choose a registered component from the Components screen to inspect its metadata and preview.', 'craft-commerce-kit' ); ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=craft-commerce-kit-components' ) ); ?>"><?php esc_html_e( 'Back to Components', 'craft-commerce-kit' ); ?></a></p>
			</div>
		<?php else : ?>
			<div class="cck-admin-card cck-admin-card--wide cck-admin-preview-hero">
				<div class="cck-admin-card__heading">
					<div>
						<p class="cck-admin-kicker"><?php esc_html_e( 'Hero', 'craft-commerce-kit' ); ?></p>
						<h2><?php echo esc_html( $data['component_name'] ); ?></h2>
					</div>
					<span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Preview Ready', 'craft-commerce-kit' ); ?></span>
				</div>
				<p class="cck-admin-muted"><?php echo esc_html( $data['description'] ); ?></p>
				<div class="cck-admin-preview-status-grid">
					<div class="cck-admin-preview-status-card">
						<span><?php esc_html_e( 'Status', 'craft-commerce-kit' ); ?></span>
						<strong><?php echo esc_html( $data['callback_callable'] ? __( 'Callable', 'craft-commerce-kit' ) : __( 'Missing callback', 'craft-commerce-kit' ) ); ?></strong>
					</div>
					<div class="cck-admin-preview-status-card">
						<span><?php esc_html_e( 'Callable', 'craft-commerce-kit' ); ?></span>
						<strong><?php echo esc_html( $data['callback_callable'] ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ) ); ?></strong>
					</div>
					<div class="cck-admin-preview-status-card">
						<span><?php esc_html_e( 'Renderer', 'craft-commerce-kit' ); ?></span>
						<strong><code><?php echo esc_html( $data['callback'] ); ?></code></strong>
					</div>
					<div class="cck-admin-preview-status-card">
						<span><?php esc_html_e( 'Used By', 'craft-commerce-kit' ); ?></span>
						<strong>
							<?php if ( ! empty( $data['experience_usage'] ) ) : ?>
								<?php echo esc_html( implode( ', ', $data['experience_usage'] ) ); ?>
							<?php else : ?>
								<?php esc_html_e( 'Not currently used', 'craft-commerce-kit' ); ?>
							<?php endif; ?>
						</strong>
					</div>
					<div class="cck-admin-preview-status-card">
						<span><?php esc_html_e( 'Version', 'craft-commerce-kit' ); ?></span>
						<strong><code><?php echo esc_html( $format_value( isset( $data['version'] ) ? $data['version'] : '' ) ); ?></code></strong>
					</div>
				</div>
			</div>

			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Preview', 'craft-commerce-kit' ); ?></h2>
					<div class="cck-admin-preview-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Preview viewport', 'craft-commerce-kit' ); ?>">
						<button type="button" class="cck-admin-preview-tab is-active" aria-pressed="true"><?php esc_html_e( 'Desktop', 'craft-commerce-kit' ); ?></button>
						<button type="button" class="cck-admin-preview-tab" aria-disabled="true" disabled><?php esc_html_e( 'Tablet', 'craft-commerce-kit' ); ?><span><?php esc_html_e( 'Disabled', 'craft-commerce-kit' ); ?></span></button>
						<button type="button" class="cck-admin-preview-tab" aria-disabled="true" disabled><?php esc_html_e( 'Mobile', 'craft-commerce-kit' ); ?><span><?php esc_html_e( 'Disabled', 'craft-commerce-kit' ); ?></span></button>
					</div>
				</div>
				<div class="cck-component-preview-shell">
					<div class="cck-component-preview-stage">
						<div class="cck-component-preview-frame">
							<div class="cck-component-preview">
								<?php if ( ! empty( $data['preview']['success'] ) ) : ?>
									<?php echo wp_kses_post( $data['preview']['html'] ); ?>
								<?php else : ?>
									<div class="notice notice-warning inline"><p><?php echo esc_html( $data['preview']['error'] ); ?></p></div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="cck-admin-grid">
				<div class="cck-admin-card">
					<div class="cck-admin-card__heading">
						<h2><?php esc_html_e( 'Attributes', 'craft-commerce-kit' ); ?></h2>
					</div>
					<?php if ( empty( $data['defaults'] ) ) : ?>
						<div class="cck-admin-empty-state">
							<div class="cck-admin-empty-state__title"><?php esc_html_e( 'No defaults registered.', 'craft-commerce-kit' ); ?></div>
							<p><?php esc_html_e( 'This component exposes no default attributes.', 'craft-commerce-kit' ); ?></p>
						</div>
					<?php else : ?>
						<dl class="cck-admin-definition-list">
							<?php foreach ( $data['defaults'] as $key => $value ) : ?>
								<div>
									<dt><code><?php echo esc_html( $key ); ?></code></dt>
									<dd><code><?php echo esc_html( $format_value( $value ) ); ?></code></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					<?php endif; ?>
				</div>

				<div class="cck-admin-card">
					<div class="cck-admin-card__heading">
						<h2><?php esc_html_e( 'Metadata', 'craft-commerce-kit' ); ?></h2>
					</div>
					<dl class="cck-admin-definition-list cck-admin-definition-list--compact">
						<div>
							<dt><?php esc_html_e( 'ID', 'craft-commerce-kit' ); ?></dt>
							<dd><code><?php echo esc_html( $data['component_id_label'] ); ?></code></dd>
						</div>
						<div>
							<dt><?php esc_html_e( 'Renderer Callback', 'craft-commerce-kit' ); ?></dt>
							<dd><code><?php echo esc_html( $data['callback'] ); ?></code></dd>
						</div>
						<div>
							<dt><?php esc_html_e( 'Callable', 'craft-commerce-kit' ); ?></dt>
							<dd><code><?php echo esc_html( $data['callback_callable'] ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ) ); ?></code></dd>
						</div>
						<div>
							<dt><?php esc_html_e( 'Supports', 'craft-commerce-kit' ); ?></dt>
							<dd>
								<?php if ( ! empty( $data['supports'] ) ) : ?>
									<div class="cck-admin-badge-row">
										<?php foreach ( $data['supports'] as $support ) : ?>
											<span class="cck-admin-badge"><?php echo esc_html( $support ); ?></span>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<code><?php echo esc_html( '—' ); ?></code>
								<?php endif; ?>
							</dd>
						</div>
					</dl>
				</div>
			</div>

			<div class="cck-admin-grid">
				<div class="cck-admin-card">
					<div class="cck-admin-card__heading">
						<h2><?php esc_html_e( 'Schema', 'craft-commerce-kit' ); ?></h2>
					</div>
					<?php if ( empty( $data['schema'] ) ) : ?>
						<div class="cck-admin-empty-state">
							<div class="cck-admin-empty-state__title"><?php esc_html_e( 'No schema available.', 'craft-commerce-kit' ); ?></div>
							<p><?php esc_html_e( 'This component does not expose schema fields.', 'craft-commerce-kit' ); ?></p>
						</div>
					<?php else : ?>
						<div class="cck-admin-schema-list">
							<?php foreach ( $data['schema'] as $key => $setting ) : ?>
								<article class="cck-admin-schema-card">
									<div class="cck-admin-schema-card__head">
										<h3><code><?php echo esc_html( $key ); ?></code></h3>
										<div class="cck-admin-badge-row">
											<span class="cck-admin-badge"><?php echo esc_html( cck_array_get( $setting, 'type', 'text' ) ); ?></span>
											<span class="cck-admin-badge"><?php echo esc_html( cck_to_bool( cck_array_get( $setting, 'required', false ) ) ? __( 'Required', 'craft-commerce-kit' ) : __( 'Optional', 'craft-commerce-kit' ) ); ?></span>
										</div>
									</div>
									<dl class="cck-admin-definition-list cck-admin-definition-list--compact">
										<div>
											<dt><?php esc_html_e( 'Default', 'craft-commerce-kit' ); ?></dt>
											<dd><code><?php echo esc_html( $format_value( cck_array_get( $setting, 'default', '' ) ) ); ?></code></dd>
										</div>
										<div>
											<dt><?php esc_html_e( 'Sanitize', 'craft-commerce-kit' ); ?></dt>
											<dd><code><?php echo esc_html( $format_value( cck_array_get( $setting, 'sanitize_callback', 'sanitize_text_field' ) ) ); ?></code></dd>
										</div>
									</dl>
								</article>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="cck-admin-card">
					<div class="cck-admin-card__heading">
						<h2><?php esc_html_e( 'Shortcodes', 'craft-commerce-kit' ); ?></h2>
					</div>
					<?php if ( empty( $data['shortcodes'] ) ) : ?>
						<div class="cck-admin-empty-state">
							<div class="cck-admin-empty-state__title"><?php esc_html_e( 'No shortcode examples.', 'craft-commerce-kit' ); ?></div>
							<p><?php esc_html_e( 'This component does not expose a direct shortcode example.', 'craft-commerce-kit' ); ?></p>
						</div>
					<?php else : ?>
						<div class="cck-admin-code-stack">
							<?php foreach ( $data['shortcodes'] as $shortcode ) : ?>
								<div class="cck-admin-code-chip"><code><?php echo esc_html( $shortcode ); ?></code></div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="cck-admin-card__subheading"><?php esc_html_e( 'Experience Usage', 'craft-commerce-kit' ); ?></div>
					<?php if ( ! empty( $data['experience_usage'] ) ) : ?>
						<div class="cck-admin-badge-row">
							<?php foreach ( $data['experience_usage'] as $experience_name ) : ?>
								<span class="cck-admin-badge"><?php echo esc_html( $experience_name ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="cck-admin-empty-state cck-admin-empty-state--compact">
							<div class="cck-admin-empty-state__title"><?php esc_html_e( 'Not currently used', 'craft-commerce-kit' ); ?></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php
		cck_render_admin_workspace_close();
	}
}
