<?php
/**
 * Admin navigation partial.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;
?>

<aside class="cck-admin-sidebar" aria-label="<?php esc_attr_e( 'Craft Commerce Kit navigation', 'craft-commerce-kit' ); ?>">
	<nav class="cck-admin-nav nav-tab-wrapper">
		<?php foreach ( cck_get_admin_nav_items() as $slug => $label ) : ?>
			<a class="cck-admin-nav-item nav-tab <?php echo esc_attr( $current_page === $slug ? 'nav-tab-active is-active' : '' ); ?>"
				href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug ) ); ?>">
				<?php echo esc_html( $label ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
</aside>
