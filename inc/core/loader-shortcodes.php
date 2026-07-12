<?php
/**
 * Shortcode loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

require_once CCK_PLUGIN_DIR . 'inc/shortcodes/shortcodes.php';

if ( function_exists( 'cck_register_shortcodes' ) ) {
	add_action( 'init', 'cck_register_shortcodes' );
}
