<?php
/**
 * Component loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

require_once CCK_PLUGIN_DIR . 'inc/components/component-interface.php';
require_once CCK_PLUGIN_DIR . 'inc/components/manifest-validator.php';
require_once CCK_PLUGIN_DIR . 'inc/components/registry.php';
require_once CCK_PLUGIN_DIR . 'inc/components/renderer.php';
require_once CCK_PLUGIN_DIR . 'inc/components/settings-renderer.php';

require_once CCK_PLUGIN_DIR . 'inc/components/hero.php';
require_once CCK_PLUGIN_DIR . 'inc/components/section-title.php';
require_once CCK_PLUGIN_DIR . 'inc/components/trust-block.php';
require_once CCK_PLUGIN_DIR . 'inc/components/image-text.php';
require_once CCK_PLUGIN_DIR . 'inc/components/cta.php';
require_once CCK_PLUGIN_DIR . 'inc/components/collection-grid.php';
require_once CCK_PLUGIN_DIR . 'inc/components/components/product-grid/render.php';

if ( function_exists( 'cck_register_core_component_renderers' ) ) {
	cck_register_core_component_renderers();
}
