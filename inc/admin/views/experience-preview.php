<?php
/**
 * Experience preview admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_experience_preview_page' ) ) {
	/**
	 * Render the experience preview screen.
	 *
	 * @return void
	 */
	function cck_render_experience_preview_page() {
		cck_require_admin_capability();

		$screen        = cck_get_admin_screen( 'experience-preview' );
		$experience_id = isset( $_GET['experience'] ) ? sanitize_key( wp_unslash( $_GET['experience'] ) ) : '';
		$definition    = function_exists( 'cck_get_experience_definition' ) ? cck_get_experience_definition( $experience_id ) : array();
		$is_valid      = is_array( $definition ) && ! empty( $definition['id'] ) && sanitize_key( $definition['id'] ) === $experience_id;
		$preview       = array(
			'success' => false,
			'html'    => '',
			'error'   => __( 'Preview unavailable.', 'craft-commerce-kit' ),
		);

		if ( $is_valid && function_exists( 'cck_render_experience' ) ) {
			$rendered = cck_render_experience( $experience_id );

			if ( is_string( $rendered ) && '' !== trim( $rendered ) ) {
				$preview['success'] = true;
				$preview['html']    = $rendered;
				$preview['error']   = '';
			}
		}

		$publish_state = function_exists( 'cck_get_experience_publish_state' ) ? cck_get_experience_publish_state( $experience_id ) : array();
		$notice        = ! $is_valid ? __( 'Unknown experience.', 'craft-commerce-kit' ) : '';
		$shortcode     = '[cck_experience id="' . $experience_id . '"]';
		$meta          = array(
			array(
				'label' => __( 'ID', 'craft-commerce-kit' ),
				'value' => $experience_id,
			),
			array(
				'label' => __( 'Layout', 'craft-commerce-kit' ),
				'value' => isset( $definition['layout'] ) ? sanitize_key( $definition['layout'] ) : '—',
			),
			array(
				'label' => __( 'Sections', 'craft-commerce-kit' ),
				'value' => function_exists( 'cck_get_experience_section_count' ) ? (string) cck_get_experience_section_count( $experience_id ) : '0',
			),
			array(
				'label' => __( 'Status', 'craft-commerce-kit' ),
				'value' => isset( $publish_state['status'] ) ? $publish_state['status'] : __( 'Draft', 'craft-commerce-kit' ),
			),
		);

		cck_render_admin_workspace_open(
			$screen['page_title'],
			$screen['description'],
			array(
				array(
					'label' => __( 'Experience', 'craft-commerce-kit' ),
					'value' => ! empty( $definition['name'] ) ? $definition['name'] : $experience_id,
				),
				array(
					'label' => __( 'Published', 'craft-commerce-kit' ),
					'value' => ! empty( $publish_state['is_published'] ) ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ),
				),
			)
		);
		?>
		<?php if ( '' !== $notice ) : ?>
			<div class="notice notice-warning inline"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<div>
					<p class="cck-admin-kicker"><?php esc_html_e( 'Experience', 'craft-commerce-kit' ); ?></p>
					<h2><?php echo esc_html( ! empty( $definition['name'] ) ? $definition['name'] : __( 'Experience Preview', 'craft-commerce-kit' ) ); ?></h2>
				</div>
				<span class="cck-admin-status <?php echo esc_attr( ! empty( $publish_state['is_published'] ) ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>"><?php echo esc_html( isset( $publish_state['status'] ) ? $publish_state['status'] : __( 'Draft', 'craft-commerce-kit' ) ); ?></span>
			</div>

			<p class="cck-admin-muted"><?php echo esc_html( ! empty( $definition['description'] ) ? $definition['description'] : __( 'Production renderer output for this experience.', 'craft-commerce-kit' ) ); ?></p>

			<div class="cck-admin-preview-status-grid">
				<div class="cck-admin-preview-status-card">
					<span><?php esc_html_e( 'Published', 'craft-commerce-kit' ); ?></span>
					<strong><?php echo esc_html( ! empty( $publish_state['is_published'] ) ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ) ); ?></strong>
				</div>
				<div class="cck-admin-preview-status-card">
					<span><?php esc_html_e( 'Homepage', 'craft-commerce-kit' ); ?></span>
					<strong><?php echo esc_html( ! empty( $publish_state['is_homepage'] ) ? __( 'Yes', 'craft-commerce-kit' ) : __( 'No', 'craft-commerce-kit' ) ); ?></strong>
				</div>
				<div class="cck-admin-preview-status-card">
					<span><?php esc_html_e( 'Renderer', 'craft-commerce-kit' ); ?></span>
					<strong><code>cck_render_experience</code></strong>
				</div>
				<div class="cck-admin-preview-status-card">
					<span><?php esc_html_e( 'Shortcode', 'craft-commerce-kit' ); ?></span>
					<strong><code><?php echo esc_html( $shortcode ); ?></code></strong>
				</div>
			</div>
		</div>

		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<h2><?php esc_html_e( 'Preview', 'craft-commerce-kit' ); ?></h2>
			</div>
			<div class="cck-preview-canvas cck-preview-desktop">
				<div class="cck-preview-canvas__viewport">
					<div class="cck-preview-canvas__surface">
						<div class="cck-preview-canvas__frame">
							<div class="cck-preview-canvas__content">
								<div class="cck-component-preview">
									<?php if ( ! empty( $preview['success'] ) ) : ?>
										<?php echo wp_kses_post( $preview['html'] ); ?>
									<?php else : ?>
										<div class="cck-admin-preview-empty-state">
											<h3><?php esc_html_e( 'Preview unavailable.', 'craft-commerce-kit' ); ?></h3>
											<p><?php esc_html_e( 'This experience does not provide a preview yet.', 'craft-commerce-kit' ); ?></p>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="cck-admin-grid">
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Metadata', 'craft-commerce-kit' ); ?></h2>
				</div>
				<dl class="cck-admin-definition-list cck-admin-definition-list--compact">
					<?php foreach ( $meta as $item ) : ?>
						<div>
							<dt><?php echo esc_html( $item['label'] ); ?></dt>
							<dd><code><?php echo esc_html( (string) $item['value'] ); ?></code></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</div>

			<div class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Shortcode', 'craft-commerce-kit' ); ?></h2>
				</div>
				<div class="cck-admin-code-stack">
					<div class="cck-admin-code-chip"><code><?php echo esc_html( $shortcode ); ?></code></div>
				</div>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
