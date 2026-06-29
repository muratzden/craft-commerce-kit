<?php
/**
 * Tema override dosyalar?n? bulmak i?in yard?mc? fonksiyonlar.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_locate_component_template' ) ) {
	/**
	 * Component render dosyas?n? child theme, parent theme ve plugin s?ras?yla bulur.
	 *
	 * @param string $component_id     Component kimli?i.
	 * @param string $default_template Plugin i?indeki varsay?lan render dosyas?.
	 * @return string
	 */
	function cck_locate_component_template( $component_id, $default_template = '' ) {
		$component_id = sanitize_key( $component_id );

		if ( empty( $component_id ) ) {
			return '';
		}

		$theme_template = locate_template(
			array(
				'craft-commerce-kit/components/' . $component_id . '/render.php',
			),
			false,
			false
		);

		if ( ! empty( $theme_template ) ) {
			return $theme_template;
		}

		if ( ! empty( $default_template ) && file_exists( $default_template ) ) {
			return $default_template;
		}

		$legacy_default = CCK_PLUGIN_DIR . 'inc/components/' . $component_id . '/render.php';

		return file_exists( $legacy_default ) ? $legacy_default : '';
	}
}

if ( ! function_exists( 'cck_locate_layout_manifest' ) ) {
	/**
	 * Layout manifest dosyas?n? aktif tema ve plugin s?ras?yla bulur.
	 *
	 * @param string $layout_id        Layout kimli?i.
	 * @param string $default_manifest Plugin i?indeki varsay?lan manifest dosyas?.
	 * @return string
	 */
	function cck_locate_layout_manifest( $layout_id, $default_manifest = '' ) {
		$layout_id = sanitize_key( $layout_id );

		if ( empty( $layout_id ) ) {
			return '';
		}

		$theme_manifest = locate_template(
			array(
				'craft-commerce-kit/layouts/' . $layout_id . '/manifest.php',
			),
			false,
			false
		);

		if ( ! empty( $theme_manifest ) ) {
			return $theme_manifest;
		}

		return ! empty( $default_manifest ) && file_exists( $default_manifest ) ? $default_manifest : '';
	}
}
