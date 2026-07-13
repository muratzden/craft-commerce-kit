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
		$brand   = is_array( $summary['default_brand'] ) ? $summary['default_brand'] : array();
		$brand_name = '';

		if ( ! empty( $summary['active_brand_name'] ) ) {
			$brand_name = $summary['active_brand_name'];
		} elseif ( ! empty( $brand['name'] ) ) {
			$brand_name = $brand['name'];
		} elseif ( ! empty( $brand['brand_name'] ) ) {
			$brand_name = $brand['brand_name'];
		} else {
			$brand_name = __( 'Unknown', 'craft-commerce-kit' );
		}

		$woocommerce_status = ! empty( $summary['woocommerce_active'] ) ? __( 'Active', 'craft-commerce-kit' ) : __( 'Inactive', 'craft-commerce-kit' );
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
				'label' => __( 'Brands', 'craft-commerce-kit' ),
				'value' => (string) $summary['registered_brands'],
			),
		);

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'], $header_meta );
		?>
		<div class="cck-admin-overview-grid">
			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Plugin Summary', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Plugin Active', 'craft-commerce-kit' ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $summary['plugin_version'] ); ?></div>
				<p><?php esc_html_e( 'Live runtime health, catalogue counts, and environment state are shown here from the production registry.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Components', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( sprintf( _n( '%s item', '%s items', (int) $summary['registered_components'], 'craft-commerce-kit' ), number_format_i18n( (int) $summary['registered_components'] ) ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( number_format_i18n( (int) $summary['registered_components'] ) ); ?></div>
				<p><?php esc_html_e( 'Registered component definitions available to the renderer and preview engine.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Experiences', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( sprintf( _n( '%s item', '%s items', (int) $summary['registered_experiences'], 'craft-commerce-kit' ), number_format_i18n( (int) $summary['registered_experiences'] ) ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( number_format_i18n( (int) $summary['registered_experiences'] ) ); ?></div>
				<p><?php esc_html_e( 'Experience catalog and runtime usage are kept read-only here.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Brands', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( sprintf( _n( '%s item', '%s items', (int) $summary['registered_brands'], 'craft-commerce-kit' ), number_format_i18n( (int) $summary['registered_brands'] ) ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( number_format_i18n( (int) $summary['registered_brands'] ) ); ?></div>
				<p><?php esc_html_e( 'Registered brand presets and the currently active brand are shown live.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'WooCommerce', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-status <?php echo esc_attr( $summary['woocommerce_active'] ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>"><?php echo esc_html( $woocommerce_status ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $woocommerce_status ); ?></div>
				<p><?php esc_html_e( 'Component preview behavior stays aligned with the live WooCommerce environment.', 'craft-commerce-kit' ); ?></p>
			</article>

			<article class="cck-admin-card">
				<div class="cck-admin-card__heading">
					<h2><?php esc_html_e( 'Active Brand', 'craft-commerce-kit' ); ?></h2>
					<span class="cck-admin-badge"><?php echo esc_html( ! empty( $summary['active_brand_id'] ) ? $summary['active_brand_id'] : __( 'None', 'craft-commerce-kit' ) ); ?></span>
				</div>
				<div class="cck-admin-overview-card__value"><?php echo esc_html( $brand_name ); ?></div>
				<p><?php esc_html_e( 'This is the runtime brand currently reflected by the registry.', 'craft-commerce-kit' ); ?></p>
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
					<span><strong><?php esc_html_e( 'Brands', 'craft-commerce-kit' ); ?></strong><code><?php echo esc_html( (string) $summary['registered_brands'] ); ?></code></span>
				</div>
			</article>
		</div>

		<div class="notice notice-info inline">
			<p><?php esc_html_e( 'This screen is read-only and reflects the live runtime registry.', 'craft-commerce-kit' ); ?></p>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
