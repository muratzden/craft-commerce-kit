<?php
/**
 * Admin loader.
 *
 * Loads all admin core modules and admin views.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/core/capabilities.php';
require_once __DIR__ . '/core/controller.php';
require_once __DIR__ . '/core/data-provider.php';
require_once __DIR__ . '/core/component-preview.php';
require_once __DIR__ . '/core/schema-fields.php';
require_once __DIR__ . '/core/menus.php';
require_once __DIR__ . '/core/navigation.php';
require_once __DIR__ . '/core/components.php';
require_once __DIR__ . '/core/layouts.php';
require_once __DIR__ . '/core/shortcodes.php';

/*
|--------------------------------------------------------------------------
| Views
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/views/dashboard.php';
require_once __DIR__ . '/views/components.php';
require_once __DIR__ . '/views/component-preview.php';
require_once __DIR__ . '/views/experience-preview.php';
require_once __DIR__ . '/views/brand.php';
require_once __DIR__ . '/views/experiences.php';
require_once __DIR__ . '/views/layouts.php';
require_once __DIR__ . '/views/settings.php';
