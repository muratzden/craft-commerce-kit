<?php
/**
 * Brand Settings admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_brand_page' ) ) {
	/**
	 * Render the permanent single-brand settings screen.
	 *
	 * @return void
	 */
	function cck_render_brand_page() {
		cck_require_admin_capability();

		$screen  = cck_get_admin_screen( 'brands' );
		$profile = cck_get_brand_profile();

		$status = isset( $_GET['cck_brand_status'] )
			? sanitize_key( wp_unslash( $_GET['cck_brand_status'] ) )
			: '';

		cck_render_admin_workspace_open(
			$screen['page_title'],
			$screen['description']
		);
		?>

		<?php if ( 'saved' === $status ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Brand settings saved.', 'craft-commerce-kit' ); ?></p>
			</div>
		<?php elseif ( 'invalid' === $status ) : ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'Enter a valid brand name and brand ID.', 'craft-commerce-kit' ); ?></p>
			</div>
		<?php endif; ?>

		<div class="postbox">
			<h2 class="hndle">
				<span><?php esc_html_e( 'Brand Profile', 'craft-commerce-kit' ); ?></span>
			</h2>

			<div class="inside">
				<?php
				cck_render_brand_profile_form(
					array(
						'profile'      => $profile,
						'action'       => 'cck_save_brand_profile',
						'nonce_action' => 'cck_save_brand_profile',
						'submit_label' => __( 'Save Brand Settings', 'craft-commerce-kit' ),
						'form_class'   => 'cck-brand-settings-form',
					)
				);
				?>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}