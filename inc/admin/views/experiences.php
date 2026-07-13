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
		$notice_code = isset( $_GET['cck_notice'] ) ? sanitize_key( wp_unslash( $_GET['cck_notice'] ) ) : '';
		$notice_map  = array(
			'published'      => array( 'success', __( 'Experience published successfully.', 'craft-commerce-kit' ) ),
			'homepage_set'   => array( 'success', __( 'Homepage updated successfully.', 'craft-commerce-kit' ) ),
			'publish_error'  => array( 'error', __( 'Unable to publish this experience.', 'craft-commerce-kit' ) ),
			'homepage_error' => array( 'error', __( 'Unable to update the homepage.', 'craft-commerce-kit' ) ),
		);

		$header_meta = array(
			array(
				'label' => __( 'Experiences', 'craft-commerce-kit' ),
				'value' => (string) count( $experiences ),
			),
			array(
				'label' => __( 'Published', 'craft-commerce-kit' ),
				'value' => function_exists( 'cck_get_published_experiences' ) ? (string) count( cck_get_published_experiences() ) : '0',
			),
		);

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'], $header_meta );
		?>
		<?php if ( isset( $notice_map[ $notice_code ] ) ) : ?>
			<?php
			$notice_type = $notice_map[ $notice_code ][0];
			$notice_text = $notice_map[ $notice_code ][1];
			?>
			<div class="notice notice-<?php echo esc_attr( $notice_type ); ?> inline"><p><?php echo esc_html( $notice_text ); ?></p></div>
		<?php endif; ?>

		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<h2><?php esc_html_e( 'Published Experiences', 'craft-commerce-kit' ); ?></h2>
				<span class="cck-admin-badge"><?php echo esc_html( number_format_i18n( count( $experiences ) ) ); ?></span>
			</div>

			<?php if ( empty( $experiences ) ) : ?>
				<div class="cck-admin-empty-state">
					<div class="cck-admin-empty-state__title"><?php esc_html_e( 'No experiences are currently registered.', 'craft-commerce-kit' ); ?></div>
					<p><?php esc_html_e( 'The registry is empty, so there is nothing to publish yet.', 'craft-commerce-kit' ); ?></p>
				</div>
			<?php else : ?>
				<div class="cck-admin-component-grid cck-admin-experience-grid">
					<?php foreach ( $experiences as $experience ) : ?>
						<?php
						$experience_id = isset( $experience['id'] ) ? sanitize_key( $experience['id'] ) : '';
						$preview_url   = isset( $experience['preview_url'] ) ? $experience['preview_url'] : '';
						$page_url      = isset( $experience['page_url'] ) ? $experience['page_url'] : '';
						$is_published  = ! empty( $experience['is_published'] );
						$is_homepage   = ! empty( $experience['is_homepage'] );
						$layout_label  = ! empty( $experience['layout'] ) ? ucwords( str_replace( array( '-', '_' ), ' ', (string) $experience['layout'] ) ) : __( 'None', 'craft-commerce-kit' );
						$brand_label   = ! empty( $experience['brand'] ) ? ucwords( str_replace( array( '-', '_' ), ' ', (string) $experience['brand'] ) ) : __( 'None', 'craft-commerce-kit' );
						$last_updated   = ! empty( $experience['updated_at'] ) ? wp_date( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), (int) $experience['updated_at'] ) : __( 'Never', 'craft-commerce-kit' );
						$published_at  = ! empty( $experience['published_at'] ) ? wp_date( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), (int) $experience['published_at'] ) : __( 'Never', 'craft-commerce-kit' );
						?>
						<article class="cck-admin-component-card cck-admin-experience-card">
							<div class="cck-admin-card__heading">
								<h3><?php echo esc_html( $experience['label'] ); ?></h3>
								<div class="cck-admin-badge-row">
									<span class="cck-admin-status <?php echo esc_attr( $is_published ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>"><?php echo esc_html( $experience['publish_status'] ); ?></span>
									<?php if ( $is_homepage ) : ?>
										<span class="cck-admin-badge"><?php esc_html_e( 'Homepage', 'craft-commerce-kit' ); ?></span>
									<?php else : ?>
										<span class="cck-admin-badge"><?php esc_html_e( 'Homepage No', 'craft-commerce-kit' ); ?></span>
									<?php endif; ?>
								</div>
							</div>

							<p class="cck-admin-kicker"><code><?php echo esc_html( $experience_id ); ?></code></p>

							<div class="cck-admin-component-tags">
								<span><?php echo esc_html( sprintf( __( 'Brand %s', 'craft-commerce-kit' ), $brand_label ) ); ?></span>
								<span><?php echo esc_html( sprintf( __( 'Layout %s', 'craft-commerce-kit' ), $layout_label ) ); ?></span>
								<span><?php echo esc_html( sprintf( __( '%d sections', 'craft-commerce-kit' ), (int) $experience['section_count'] ) ); ?></span>
							</div>

							<dl class="cck-admin-summary-list">
								<dt><?php esc_html_e( 'Status', 'craft-commerce-kit' ); ?></dt>
								<dd><?php echo esc_html( $experience['publish_status'] ); ?></dd>
								<dt><?php esc_html_e( 'Page ID', 'craft-commerce-kit' ); ?></dt>
								<dd><code><?php echo esc_html( ! empty( $experience['page_id'] ) ? (string) (int) $experience['page_id'] : '—' ); ?></code></dd>
								<dt><?php esc_html_e( 'Published At', 'craft-commerce-kit' ); ?></dt>
								<dd><?php echo esc_html( $published_at ); ?></dd>
								<dt><?php esc_html_e( 'Last Updated', 'craft-commerce-kit' ); ?></dt>
								<dd><?php echo esc_html( $last_updated ); ?></dd>
							</dl>

							<div class="cck-admin-template-actions">
								<?php if ( ! empty( $preview_url ) ) : ?>
									<a class="button button-primary button-small" href="<?php echo esc_url( $preview_url ); ?>"><?php esc_html_e( 'Preview →', 'craft-commerce-kit' ); ?></a>
								<?php endif; ?>

								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="cck_publish_experience" />
									<input type="hidden" name="experience_id" value="<?php echo esc_attr( $experience_id ); ?>" />
									<?php wp_nonce_field( 'cck_publish_experience_' . $experience_id ); ?>
									<button type="submit" class="button button-primary button-small"><?php echo esc_html( $is_published ? __( 'Publish Again', 'craft-commerce-kit' ) : __( 'Publish', 'craft-commerce-kit' ) ); ?></button>
								</form>

								<?php if ( ! empty( $page_url ) ) : ?>
									<a class="button button-small" href="<?php echo esc_url( $page_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View', 'craft-commerce-kit' ); ?></a>
								<?php else : ?>
									<span class="cck-admin-muted"><?php esc_html_e( 'View unavailable', 'craft-commerce-kit' ); ?></span>
								<?php endif; ?>

								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="cck_set_homepage_experience" />
									<input type="hidden" name="experience_id" value="<?php echo esc_attr( $experience_id ); ?>" />
									<?php wp_nonce_field( 'cck_set_homepage_experience_' . $experience_id ); ?>
									<button type="submit" class="button button-small"><?php esc_html_e( 'Set Homepage', 'craft-commerce-kit' ); ?></button>
								</form>
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
