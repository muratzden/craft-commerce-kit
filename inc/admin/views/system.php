<?php
/**
 * System admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_system_page' ) ) {
	/**
	 * Render the system readiness screen.
	 *
	 * @return void
	 */
	function cck_render_system_page() {
		cck_require_admin_capability();

		$screen = cck_get_admin_screen( 'system' );
		$theme  = wp_get_theme();

		$brand_profile = function_exists( 'cck_get_brand_profile' )
			? cck_get_brand_profile()
			: array();

		$active_brand = function_exists( 'cck_get_active_brand' )
			? cck_get_active_brand()
			: array();

		$components = function_exists( 'cck_get_admin_components' )
			? cck_get_admin_components()
			: array();

		$experiences = function_exists( 'cck_get_experiences' )
			? cck_get_experiences()
			: array();

		$published_experiences = function_exists( 'cck_get_published_experiences' )
			? cck_get_published_experiences()
			: array();

		$upload_dir = wp_upload_dir();
		$upload_path = isset( $upload_dir['basedir'] )
			? $upload_dir['basedir']
			: '';

		$upload_writable = '' !== $upload_path &&
			is_dir( $upload_path ) &&
			is_writable( $upload_path );

		$setup_completed = 1 === (int) get_option(
			'cck_setup_completed',
			0
		);

		$woocommerce_active = function_exists( 'cck_is_woocommerce_active' )
			? cck_is_woocommerce_active()
			: false;

		$cck_addon_statuses = cck_get_addon_statuses();
		$active_cck_addons  = count(
			array_filter(
				$cck_addon_statuses,
				static function ( $addon_status ) {
					return 'active' === $addon_status['state'];
				}
			)
		);

		$brand_name = ! empty( $active_brand['brand_name'] )
			? $active_brand['brand_name']
			: __( 'Not configured', 'craft-commerce-kit' );

		$brand_experience = ! empty( $active_brand['experience'] )
			? ucwords(
				str_replace(
					array( '-', '_' ),
					' ',
					$active_brand['experience']
				)
			)
			: __( 'None', 'craft-commerce-kit' );

		$checks = array(
			array(
				'label'  => __( 'WordPress', 'craft-commerce-kit' ),
				'value'  => get_bloginfo( 'version' ),
				'status' => 'ready',
			),
			array(
				'label'  => __( 'PHP', 'craft-commerce-kit' ),
				'value'  => PHP_VERSION,
				'status' => version_compare( PHP_VERSION, '7.4', '>=' )
					? 'ready'
					: 'warning',
			),
			array(
				'label'  => __( 'WooCommerce', 'craft-commerce-kit' ),
				'value'  => $woocommerce_active
					? __( 'Active', 'craft-commerce-kit' )
					: __( 'Inactive', 'craft-commerce-kit' ),
				'status' => $woocommerce_active ? 'ready' : 'warning',
			),
			array(
				'label'  => __( 'Active Theme', 'craft-commerce-kit' ),
				'value'  => $theme->exists()
					? $theme->get( 'Name' )
					: __( 'Unknown', 'craft-commerce-kit' ),
				'status' => $theme->exists() ? 'ready' : 'warning',
			),
			array(
				'label'  => __( 'Brand Profile', 'craft-commerce-kit' ),
				'value'  => ! empty( $brand_profile )
					? $brand_name
					: __( 'Missing', 'craft-commerce-kit' ),
				'status' => ! empty( $brand_profile ) ? 'ready' : 'warning',
			),
			array(
				'label'  => __( 'Setup Status', 'craft-commerce-kit' ),
				'value'  => $setup_completed
					? __( 'Complete', 'craft-commerce-kit' )
					: __( 'Incomplete', 'craft-commerce-kit' ),
				'status' => $setup_completed ? 'ready' : 'warning',
			),
			array(
				'label'  => __( 'Active Experience', 'craft-commerce-kit' ),
				'value'  => $brand_experience,
				'status' => ! empty( $active_brand['experience'] )
					? 'ready'
					: 'warning',
			),
			array(
				'label'  => __( 'CCK Add-ons', 'craft-commerce-kit' ),
				'value'  => sprintf(
					/* translators: %s: Active add-on count. */
					__( '%s active', 'craft-commerce-kit' ),
					number_format_i18n( $active_cck_addons )
				),
				'status' => $active_cck_addons > 0 ? 'ready' : 'unavailable',
			),
			array(
				'label'  => __( 'Registered Components', 'craft-commerce-kit' ),
				'value'  => (string) count( $components ),
				'status' => ! empty( $components ) ? 'ready' : 'warning',
			),
			array(
				'label'  => __( 'Registered Experiences', 'craft-commerce-kit' ),
				'value'  => (string) count( $experiences ),
				'status' => ! empty( $experiences ) ? 'ready' : 'warning',
			),
			array(
				'label'  => __( 'Published Experiences', 'craft-commerce-kit' ),
				'value'  => (string) count( $published_experiences ),
				'status' => ! empty( $published_experiences )
					? 'ready'
					: 'unavailable',
			),
			array(
				'label'  => __( 'Uploads Directory', 'craft-commerce-kit' ),
				'value'  => $upload_writable
					? __( 'Writable', 'craft-commerce-kit' )
					: __( 'Not writable', 'craft-commerce-kit' ),
				'status' => $upload_writable ? 'ready' : 'warning',
			),
		);

		$warning_count = 0;

		foreach ( $checks as $check ) {
			if ( 'warning' === $check['status'] ) {
				++$warning_count;
			}
		}

		$runtime_ready = 0 === $warning_count;
		$status_labels = array(
			'ready'       => __( 'Ready', 'craft-commerce-kit' ),
			'warning'     => __( 'Warning', 'craft-commerce-kit' ),
			'unavailable' => __( 'Unavailable', 'craft-commerce-kit' ),
		);

		cck_render_admin_workspace_open(
			$screen['page_title'],
			$screen['description']
		);
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading">
				<h2><?php esc_html_e( 'Runtime Readiness', 'craft-commerce-kit' ); ?></h2>
				<span class="cck-admin-status <?php echo esc_attr( $runtime_ready ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>">
					<?php
					echo esc_html(
						$runtime_ready
							? __( 'Ready', 'craft-commerce-kit' )
							: sprintf(
								_n(
									'%s warning',
									'%s warnings',
									$warning_count,
									'craft-commerce-kit'
								),
								number_format_i18n( $warning_count )
							)
					);
					?>
				</span>
			</div>

			<p class="cck-admin-muted">
				<?php esc_html_e( 'Live environment and plugin readiness checks for the current WordPress installation.', 'craft-commerce-kit' ); ?>
			</p>
		</div>

		<div class="cck-admin-overview-grid">
			<?php foreach ( $checks as $check ) : ?>
				<article class="cck-admin-card">
					<div class="cck-admin-card__heading">
						<h2><?php echo esc_html( $check['label'] ); ?></h2>
						<span class="cck-admin-status <?php echo esc_attr( 'ready' === $check['status'] ? 'cck-admin-status--active' : 'cck-admin-status--muted' ); ?>">
							<?php echo esc_html( $status_labels[ $check['status'] ] ); ?>
						</span>
					</div>

					<div class="cck-admin-overview-card__value">
						<?php echo esc_html( $check['value'] ); ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="notice notice-info inline">
			<p>
				<?php
				esc_html_e(
					'Unavailable means optional content has not been published yet; warnings identify configuration items that may require attention.',
					'craft-commerce-kit'
				);
				?>
			</p>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
