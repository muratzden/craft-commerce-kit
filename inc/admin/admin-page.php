<?php
/**
 * Admin pages.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_admin_workspace_open' ) ) {
	/**
	 * Render shared workspace opening markup.
	 *
	 * @param string $title Page title.
	 * @param string $description Page description.
	 * @param array  $meta Page-level summary metadata.
	 * @return void
	 */
	function cck_render_admin_workspace_open( $title, $description, array $meta = array() ) {
		$current_page = cck_get_current_admin_page();
		?>
		<div class="wrap cck-admin-dashboard">
			<?php require __DIR__ . '/partials/header.php'; ?>

			<div class="cck-admin-workspace">
				<?php require __DIR__ . '/partials/navigation.php'; ?>


				<main class="cck-admin-content">
					<header class="cck-admin-page-title">
						<h2><?php echo esc_html( $title ); ?></h2>
						<p><?php echo esc_html( $description ); ?></p>
					</header>
					<div class="cck-admin-page">
		<?php
	}
}

if ( ! function_exists( 'cck_render_admin_workspace_close' ) ) {
	/**
	 * Render shared workspace closing markup.
	 *
	 * @return void
	 */
	function cck_render_admin_workspace_close() {
		require __DIR__ . '/partials/footer.php';
	}
}
