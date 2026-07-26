<?php
/**
 * Overview admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_admin_page' ) ) {
	/**
	 * Render the overview page.
	 *
	 * @return void
	 */
	function cck_render_admin_page() {
		cck_require_admin_capability();

		$screen  = cck_get_admin_screen( 'overview' );
		$summary = cck_get_admin_overview_data();

		$brand_name = ! empty( $summary['active_brand_name'] )
			? $summary['active_brand_name']
			: __( 'Not configured', 'craft-commerce-kit' );

		$brand_id = ! empty( $summary['active_brand_id'] )
			? $summary['active_brand_id']
			: __( 'None', 'craft-commerce-kit' );

		$experience = ! empty( $summary['brand_experience'] )
			? ucwords(
				str_replace(
					array( '-', '_' ),
					' ',
					$summary['brand_experience']
				)
			)
			: __( 'None', 'craft-commerce-kit' );

		$setup_status = ! empty( $summary['setup_completed'] )
			? __( 'Complete', 'craft-commerce-kit' )
			: __( 'Incomplete', 'craft-commerce-kit' );

		$woocommerce_status = ! empty( $summary['woocommerce_active'] )
			? __( 'Active', 'craft-commerce-kit' )
			: __( 'Inactive', 'craft-commerce-kit' );

		$homepage_label = ! empty( $summary['homepage_label'] )
			? $summary['homepage_label']
			: __( 'Not set', 'craft-commerce-kit' );

		$last_published_label = ! empty( $summary['last_published_label'] )
			? $summary['last_published_label']
			: __( 'Not published yet', 'craft-commerce-kit' );

		$header_meta = array(
			array(
				'label' => __( 'Version', 'craft-commerce-kit' ),
				'value' => $summary['plugin_version'],
			),
			array(
				'label' => __( 'Components', 'craft-commerce-kit' ),
				'value' => (string) $summary['registered_components'],
			),
			array(
				'label' => __( 'Experiences', 'craft-commerce-kit' ),
				'value' => (string) $summary['registered_experiences'],
			),
			array(
				'label' => __( 'Brand Profile', 'craft-commerce-kit' ),
				'value' => ! empty( $summary['brand_profile_exists'] )
					? __( 'Ready', 'craft-commerce-kit' )
					: __( 'Missing', 'craft-commerce-kit' ),
			),
			array(
				'label' => __( 'Published', 'craft-commerce-kit' ),
				'value' => (string) $summary['published_experiences'],
			),
		);

		$status = isset( $_GET['cck_setup_status'] )
			? sanitize_key( wp_unslash( $_GET['cck_setup_status'] ) )
			: '';

		cck_render_admin_workspace_open(
			$screen['page_title'],
			$screen['description'],
			$header_meta
		);
		?>

		<?php if ( 'completed' === $status ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Brand setup completed successfully.', 'craft-commerce-kit' ); ?></p>
			</div>
		<?php endif; ?>

		<div class="cck-admin-overview-grid">
			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Plugin Summary', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Plugin Active', 'craft-commerce-kit' ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $summary['plugin_version'] ); ?></div>
				<p><?php esc_html_e( 'Runtime health, profile state, and environment information.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Components', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( number_format_i18n( (int) $summary['registered_components'] ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( number_format_i18n( (int) $summary['registered_components'] ) ); ?></div>
				<p><?php esc_html_e( 'Components available to the renderer and block editor.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Experiences', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( number_format_i18n( (int) $summary['registered_experiences'] ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( number_format_i18n( (int) $summary['registered_experiences'] ) ); ?></div>
				<p><?php esc_html_e( 'Available design experiences and reusable page structures.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Brand Profile', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-status <?php echo esc_attr( ! empty( $summary['setup_completed'] ) ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>"><?php echo esc_html( $setup_status ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $brand_name ); ?></div>
				<p><code><?php echo esc_html( $brand_id ); ?></code> &middot; <?php echo esc_html( $experience ); ?></p>
				<p>
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=craft-commerce-kit-brands' ) ); ?>">
						<?php esc_html_e( 'Edit Brand Settings', 'craft-commerce-kit' ); ?>
					</a>
				</p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Published Experiences', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( number_format_i18n( (int) $summary['published_experiences'] ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( number_format_i18n( (int) $summary['published_experiences'] ) ); ?></div>
				<p><?php esc_html_e( 'Experience pages currently published to WordPress.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Homepage', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( ! empty( $summary['homepage_experience_id'] ) ? $summary['homepage_experience_id'] : __( 'Not set', 'craft-commerce-kit' ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $homepage_label ); ?></div>
				<p><?php esc_html_e( 'Experience currently assigned as the static homepage.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Last Published', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( ! empty( $summary['last_published_id'] ) ? $summary['last_published_id'] : __( 'None', 'craft-commerce-kit' ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $last_published_label ); ?></div>
				<p><?php esc_html_e( 'Most recent experience publish event.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'WooCommerce', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-status <?php echo esc_attr( $summary['woocommerce_active'] ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>"><?php echo esc_html( $woocommerce_status ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $woocommerce_status ); ?></div>
				<p><?php esc_html_e( 'Commerce components follow the active WooCommerce environment.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Environment', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php esc_html_e( 'Runtime', 'craft-commerce-kit' ); ?></span>
				</div>
				<p class="cck-admin-muted"><?php echo esc_html( $summary['environment_summary'] ); ?></p>
				<div class="cck-admin-overview-inline">
					<span><strong><?php esc_html_e( 'Version', 'craft-commerce-kit' ); ?></strong><code><?php echo esc_html( $summary['plugin_version'] ); ?></code></span>
					<span><strong><?php esc_html_e( 'Components', 'craft-commerce-kit' ); ?></strong><code><?php echo esc_html( (string) $summary['registered_components'] ); ?></code></span>
					<span><strong><?php esc_html_e( 'Experiences', 'craft-commerce-kit' ); ?></strong><code><?php echo esc_html( (string) $summary['registered_experiences'] ); ?></code></span>
					<span><strong><?php esc_html_e( 'Brand', 'craft-commerce-kit' ); ?></strong><code><?php echo esc_html( $brand_id ); ?></code></span>
				</div>
			</article>
		</div>

		<div class="notice notice-info inline">
			<p><?php esc_html_e( 'This overview reflects the active single-brand profile and live runtime state.', 'craft-commerce-kit' ); ?></p>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}