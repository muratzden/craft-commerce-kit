<?php
/**
 * Settings admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_settings_page' ) ) {
	/**
	 * Render the settings screen.
	 *
	 * @return void
	 */
	function cck_render_settings_page() {
		cck_require_admin_capability();

		$screen  = cck_get_admin_screen( 'settings' );
		$settings = cck_get_admin_settings_data();

		cck_render_admin_workspace_open( $screen['page_title'], $screen['description'] );
		?>
		<div class="notice notice-info">
			<p><?php echo esc_html( $settings['message'] ); ?></p>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}
