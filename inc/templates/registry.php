<?php
/**
 * Template registry.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_templates' ) ) {
	/**
	 * Get registered template metadata.
	 *
	 * @return array
	 */
	function cck_get_templates() {
		$template_files = array(
			'homepage.php',
			'about.php',
			'workshop.php',
			'contact.php',
			'product-landing.php',
		);
		$templates      = array();

		foreach ( $template_files as $template_file ) {
			$template_path = CCK_PLUGIN_DIR . 'inc/templates/' . $template_file;

			if ( ! file_exists( $template_path ) ) {
				continue;
			}

			$template = require $template_path;

			if ( ! is_array( $template ) || empty( $template['id'] ) ) {
				continue;
			}

			$templates[ $template['id'] ] = $template;
		}

		return $templates;
	}
}
