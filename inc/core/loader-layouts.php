<?php
/**
 * Layout loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

/*
 * Shared schema helpers required by manual layout sanitization.
 */
require_once CCK_PLUGIN_DIR . 'inc/admin/core/schema-fields.php';

require_once CCK_PLUGIN_DIR . 'inc/layouts/layout-registry.php';
require_once CCK_PLUGIN_DIR . 'inc/layouts/layout-validator.php';
require_once CCK_PLUGIN_DIR . 'inc/layouts/layout-composer.php';
require_once CCK_PLUGIN_DIR . 'inc/layouts/layout-renderer.php';
require_once CCK_PLUGIN_DIR . 'inc/layouts/manual-layout.php';
