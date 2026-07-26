<?php
/**
 * Setup Wizard view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_setup_page' ) ) {
	/**
	 * Render the first-run setup page.
	 *
	 * @return void
	 */
	function cck_render_setup_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$profile = cck_get_brand_profile();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Craft Commerce Kit Setup', 'craft-commerce-kit' ); ?></h1>

			<p class="description">
				<?php esc_html_e( 'Create the single brand profile used by your storefront and block editor.', 'craft-commerce-kit' ); ?>
			</p>

			<div class="postbox">
				<div class="inside">
					<?php
					cck_render_brand_profile_form(
						array(
							'profile'      => $profile,
							'action'       => 'cck_complete_setup',
							'nonce_action' => 'cck_complete_setup',
							'submit_label' => __( 'Save and complete setup', 'craft-commerce-kit' ),
							'form_class'   => 'cck-setup-form',
						)
					);
					?>
				</div>
			</div>
		</div>
		<?php
	}
}