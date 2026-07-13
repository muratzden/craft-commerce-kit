<?php
/**
 * Components admin view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_templates_page' ) ) {
	/**
	 * Render templates page.
	 *
	 * @return void
	 */
	function cck_render_templates_page() {
		cck_require_admin_capability();
		$templates = function_exists( 'cck_get_templates' ) ? cck_get_templates() : array();

		cck_render_admin_workspace_open( __( 'Templates', 'craft-commerce-kit' ), __( 'Premium craft website structures such as Homepage, About / Brand Story, Workshop / Process, Contact, and Collection pages.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-template-grid">
			<?php foreach ( $templates as $template ) : ?>
				<?php $components = isset( $template['components'] ) && is_array( $template['components'] ) ? $template['components'] : array(); ?>
				<div class="cck-admin-card cck-admin-template-card">
					<div class="cck-admin-card__heading"><h2><?php echo esc_html( isset( $template['name'] ) ? $template['name'] : $template['id'] ); ?></h2><span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Available', 'craft-commerce-kit' ); ?></span></div>
					<p><?php echo esc_html( isset( $template['description'] ) ? $template['description'] : '' ); ?></p>
					<div class="cck-admin-template-meta"><span><?php /* translators: %s: Template version. */ echo esc_html( sprintf( __( 'Version %s', 'craft-commerce-kit' ), isset( $template['version'] ) ? $template['version'] : '0.1.0' ) ); ?></span><span><?php /* translators: %d: Number of components. */ echo esc_html( sprintf( __( '%d Components', 'craft-commerce-kit' ), count( $components ) ) ); ?></span></div>
					<div class="cck-admin-component-tags">
						<?php foreach ( $components as $component ) : ?>
							<span><?php echo esc_html( $component ); ?></span>
						<?php endforeach; ?>
					</div>
					<div class="cck-admin-template-actions"><button type="button" class="button" disabled><?php esc_html_e( 'Preview', 'craft-commerce-kit' ); ?></button><button type="button" class="button button-primary" disabled><?php esc_html_e( 'Import', 'craft-commerce-kit' ); ?></button></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}