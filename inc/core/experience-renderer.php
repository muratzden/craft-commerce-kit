<?php
/**
 * Experience Renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_experience' ) ) {
	function cck_render_experience( $id = 'atelier' ) {
		$id = sanitize_key( $id );

		if ( '' === $id ) {
			return '';
		}

		$experience_file = CCK_PLUGIN_DIR . 'packs/' . $id . '/experience.php';

		if ( ! file_exists( $experience_file ) ) {
			return '';
		}

		$experience = require $experience_file;

		if ( ! is_array( $experience ) || empty( $experience['layout'] ) ) {
			return '';
		}

		$layout      = sanitize_key( $experience['layout'] );
		$layout_file = CCK_PLUGIN_DIR . 'packs/' . $id . '/layouts/' . $layout . '.php';

		if ( ! file_exists( $layout_file ) ) {
			return '';
		}

		$sections = require $layout_file;

		if ( ! is_array( $sections ) ) {
			return '';
		}

		$output = '<div class="cck-experience cck-experience-' . esc_attr( $id ) . ' cck-layout-' . esc_attr( $layout ) . '">';

		foreach ( $sections as $section ) {
			$output .= cck_render_experience_section( $id, $section );
		}

		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'cck_render_experience_section' ) ) {
	function cck_render_experience_section( $experience_id, $section ) {
		$experience_id = sanitize_key( $experience_id );

		if ( '' === $experience_id || ! is_string( $section ) ) {
			return '';
		}

		$section = sanitize_key( $section );

		if ( '' === $section ) {
			return '';
		}

		$section_file = CCK_PLUGIN_DIR . 'packs/' . $experience_id . '/sections/' . $section . '.php';

		if ( ! file_exists( $section_file ) ) {
			return '';
		}

		$definition = require $section_file;

		if ( ! is_array( $definition ) || empty( $definition['component'] ) ) {
			return '';
		}

		if ( ! function_exists( 'cck_render_component' ) ) {
			return '';
		}

		return cck_render_component( $definition );
	}
}

if ( ! function_exists( 'cck_shortcode_experience' ) ) {
	function cck_shortcode_experience( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => 'atelier',
			),
			$atts,
			'cck_experience'
		);

		return cck_render_experience( $atts['id'] );
	}
}
