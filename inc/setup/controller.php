<?php
/**
 * Setup wizard controller.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_register_setup_page' ) ) {
	function cck_register_setup_page() {
		add_submenu_page(
			null,
			__( 'Craft Commerce Kit Setup', 'craft-commerce-kit' ),
			__( 'Setup', 'craft-commerce-kit' ),
			'manage_options',
			'craft-commerce-kit-setup',
			'cck_render_setup_page'
		);
	}
}

add_action( 'admin_menu', 'cck_register_setup_page' );

if ( ! function_exists( 'cck_maybe_redirect_to_setup' ) ) {
	function cck_maybe_redirect_to_setup() {
		if (
			! is_admin() ||
			wp_doing_ajax() ||
			defined( 'WP_CLI' ) ||
			! current_user_can( 'manage_options' )
		) {
			return;
		}

		global $pagenow;

		if ( 'admin-post.php' === $pagenow ) {
			return;
		}

		if ( get_option( 'cck_setup_completed', false ) ) {
			delete_option( 'cck_setup_redirect_pending' );
			return;
		}

		if ( ! get_option( 'cck_setup_redirect_pending', false ) ) {
			return;
		}

		$page = isset( $_GET['page'] )
			? sanitize_key( wp_unslash( $_GET['page'] ) )
			: '';

		if ( 'craft-commerce-kit-setup' === $page ) {
			return;
		}

		delete_option( 'cck_setup_redirect_pending' );

		wp_safe_redirect(
			admin_url( 'admin.php?page=craft-commerce-kit-setup' )
		);
		exit;
	}
}

add_action( 'admin_init', 'cck_maybe_redirect_to_setup', 5 );

if ( ! function_exists( 'cck_handle_setup_submission' ) ) {
	function cck_handle_setup_submission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__(
					'Sorry, you are not allowed to perform this action.',
					'craft-commerce-kit'
				)
			);
		}

		check_admin_referer( 'cck_complete_setup' );

		$profile = isset( $_POST['cck_brand_profile'] ) && is_array( $_POST['cck_brand_profile'] )
			? wp_unslash( $_POST['cck_brand_profile'] )
			: array();

		if ( ! cck_save_brand_profile( $profile ) ) {
			wp_safe_redirect(
				add_query_arg(
					'cck_setup_status',
					'invalid',
					admin_url( 'admin.php?page=craft-commerce-kit-setup' )
				)
			);
			exit;
		}

		update_option( 'cck_setup_completed', 1, false );
		delete_option( 'cck_setup_redirect_pending' );

		wp_safe_redirect(
			add_query_arg(
				'cck_setup_status',
				'completed',
				admin_url( 'admin.php?page=craft-commerce-kit' )
			)
		);
		exit;
	}
}

add_action(
	'admin_post_cck_complete_setup',
	'cck_handle_setup_submission'
);
if ( ! function_exists( 'cck_handle_brand_profile_update' ) ) {
	/**
	 * Save the permanent Brand Settings form.
	 *
	 * @return void
	 */
	function cck_handle_brand_profile_update() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__(
					'Sorry, you are not allowed to perform this action.',
					'craft-commerce-kit'
				)
			);
		}

		check_admin_referer( 'cck_save_brand_profile' );

		$profile = isset( $_POST['cck_brand_profile'] ) &&
			is_array( $_POST['cck_brand_profile'] )
				? wp_unslash( $_POST['cck_brand_profile'] )
				: array();

		$status = cck_save_brand_profile( $profile )
			? 'saved'
			: 'invalid';

		if ( 'saved' === $status ) {
			update_option( 'cck_setup_completed', 1, false );
		}

		wp_safe_redirect(
			add_query_arg(
				'cck_brand_status',
				$status,
				admin_url(
					'admin.php?page=craft-commerce-kit-brands'
				)
			)
		);
		exit;
	}
}

add_action(
	'admin_post_cck_save_brand_profile',
	'cck_handle_brand_profile_update'
);
