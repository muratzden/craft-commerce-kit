<?php
/**
 * Brand runtime loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

require_once CCK_PLUGIN_DIR . 'inc/brand-runtime/brand-manager.php';
require_once CCK_PLUGIN_DIR . 'inc/brand-runtime/brands/default.php';
require_once CCK_PLUGIN_DIR . 'inc/brand-runtime/brands/demo.php';

cck_register_brand( 'default', cck_get_default_brand() );
cck_register_brand( 'demo', cck_get_demo_brand() );

/*
 * Legacy Brand Pack (temporary)
 * Used only for backward compatibility with existing cck_tilla_* shortcodes.
 * Will be removed in a future major release.
 */
require_once CCK_PLUGIN_DIR . 'inc/brand-packs/tilla-leather.php';
