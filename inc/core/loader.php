<?php
/**
 * Plugin loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

/*
 * Bootstrap / Core APIs
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-core.php';

/*
 * Runtime Kernel
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-runtime.php';

/*
 * Rendering
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-rendering.php';

/*
 * Brand Runtime
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-brand-runtime.php';

/*
 * Experience Packs
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-experiences.php';

/*
 * Layouts and Shortcodes
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-layouts.php';
require_once CCK_PLUGIN_DIR . 'inc/core/loader-shortcodes.php';

/*
 * WooCommerce Integration
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-woocommerce.php';

/*
 * Admin
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-admin.php';

/*
 * Hooks
 */
require_once CCK_PLUGIN_DIR . 'inc/core/loader-hooks.php';
