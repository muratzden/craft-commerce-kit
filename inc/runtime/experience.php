<?php
/**
 * Experience runtime.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_experience_definition' ) ) {
	/**
	 * Get an experience definition.
	 *
	 * @param string $experience Experience slug.
	 * @return array
	 */
	function cck_get_experience_definition( $experience ) {
		$experience = sanitize_key( $experience );

		if ( '' === $experience ) {
			return array();
		}

		$file = CCK_PLUGIN_DIR . 'packs/' . $experience . '/experience.php';

		if ( ! file_exists( $file ) ) {
			return array();
		}

		$definition = require $file;

		return is_array( $definition ) ? $definition : array();
	}
}

if ( ! function_exists( 'cck_get_experience_layout_sections' ) ) {
	/**
	 * Get the section list for a registered experience layout.
	 *
	 * @param string $experience Experience slug.
	 * @return array
	 */
	function cck_get_experience_layout_sections( $experience ) {
		$experience = sanitize_key( $experience );

		if ( '' === $experience ) {
			return array();
		}

		$definition = cck_get_experience_definition( $experience );

		if ( empty( $definition['layout'] ) ) {
			return array();
		}

		$layout_file = CCK_PLUGIN_DIR . 'packs/' . $experience . '/layouts/' . sanitize_key( $definition['layout'] ) . '.php';

		if ( ! file_exists( $layout_file ) ) {
			return array();
		}

		$sections = require $layout_file;

		if ( ! is_array( $sections ) ) {
			return array();
		}

		$normalized = array();

		foreach ( $sections as $section ) {
			$section = sanitize_key( $section );

			if ( '' !== $section ) {
				$normalized[] = $section;
			}
		}

		return $normalized;
	}
}

if ( ! function_exists( 'cck_get_experience_section_count' ) ) {
	/**
	 * Get the number of sections in an experience layout.
	 *
	 * @param string $experience Experience slug.
	 * @return int
	 */
	function cck_get_experience_section_count( $experience ) {
		return count( cck_get_experience_layout_sections( $experience ) );
	}
}
