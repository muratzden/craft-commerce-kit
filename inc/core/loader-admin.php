<?php
/**
 * Admin loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	require_once CCK_PLUGIN_DIR . 'inc/admin/loader.php';
	require_once CCK_PLUGIN_DIR . 'inc/admin/admin-page.php';
}
