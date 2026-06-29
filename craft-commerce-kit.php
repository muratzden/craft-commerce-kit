<?php
/**
 * Plugin Name: Craft Commerce Kit
 * Plugin URI:  https://muratozden.com.tr/craft-commerce-kit
 * Description: A theme-independent WooCommerce UI framework for premium artisan, boutique, handmade, craft, and quiet luxury commerce brands.
 * Version:     0.2.0
 * Author:      Craft Commerce Kit
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: craft-commerce-kit
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

define( 'CCK_VERSION', '0.2.0' );
define( 'CCK_PLUGIN_FILE', __FILE__ );
define( 'CCK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once CCK_PLUGIN_DIR . 'inc/core/loader.php';
