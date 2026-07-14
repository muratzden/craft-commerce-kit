<?php
/**
 * Manual layout storage and renderer helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_manual_layout_option_key' ) ) {
	/**
	 * Get the option key that stores the manual layout composition.
	 *
	 * @return string
	 */
	function cck_get_manual_layout_option_key() {
		return 'cck_manual_layout';
	}
}

if ( ! function_exists( 'cck_get_manual_layout_seed_components' ) ) {
	/**
	 * Get a premium starter composition for the manual layout.
	 *
	 * @return array
	 */
	function cck_get_manual_layout_seed_components() {
		$order = array( 'hero', 'section-title', 'image-text', 'collection-grid', 'trust-block', 'cta', 'footer' );
		$components = array();

		foreach ( $order as $component_id ) {
			$manifest = function_exists( 'cck_get_component_manifest' ) ? cck_get_component_manifest( $component_id ) : array();

			if ( ! is_array( $manifest ) || empty( $manifest['id'] ) ) {
				continue;
			}

			$preview = cck_array_get( $manifest, 'preview', array() );
			$attributes = array();

			if ( is_array( $preview ) && isset( $preview['attributes'] ) && is_array( $preview['attributes'] ) ) {
				$attributes = $preview['attributes'];
			} elseif ( function_exists( 'cck_get_component_defaults' ) ) {
				$attributes = cck_get_component_defaults( $component_id );
			}

			$components[] = array(
				'type'       => sanitize_key( $component_id ),
				'attributes'  => is_array( $attributes ) ? $attributes : array(),
			);
		}

		return $components;
	}
}

if ( ! function_exists( 'cck_get_manual_layout_default_components' ) ) {
	/**
	 * Return the default manual layout composition.
	 *
	 * @return array
	 */
	function cck_get_manual_layout_default_components() {
		$seed = cck_get_manual_layout_seed_components();

		return ! empty( $seed ) ? $seed : array();
	}
}

if ( ! function_exists( 'cck_sanitize_manual_layout_component' ) ) {
	/**
	 * Sanitize a single manual layout component row.
	 *
	 * @param mixed $component Raw component row.
	 * @return array
	 */
	function cck_sanitize_manual_layout_component( $component ) {
		if ( ! is_array( $component ) ) {
			return array();
		}

		$component_id = sanitize_key( cck_array_get( $component, 'type', cck_array_get( $component, 'component', '' ) ) );
		$manifest     = function_exists( 'cck_get_component_manifest' ) ? cck_get_component_manifest( $component_id ) : array();

		if ( ! is_array( $manifest ) || empty( $manifest['id'] ) ) {
			return array();
		}

		$settings = cck_manifest_get( $manifest, 'settings', array() );
		$input_atts = cck_array_get( $component, 'attributes', array() );
		$input_atts = is_array( $input_atts ) ? $input_atts : array();
		$sanitized = array();

		foreach ( $settings as $setting_id => $setting ) {
			$setting_id = sanitize_key( $setting_id );
			$default    = cck_get_schema_field_default( is_array( $setting ) ? $setting : array() );

			if ( array_key_exists( $setting_id, $input_atts ) ) {
				$sanitized[ $setting_id ] = cck_sanitize_schema_field_value( $input_atts[ $setting_id ], is_array( $setting ) ? $setting : array() );
			} else {
				$sanitized[ $setting_id ] = $default;
			}
		}

		return array(
			'type'       => sanitize_key( $manifest['id'] ),
			'attributes'  => $sanitized,
		);
	}
}

if ( ! function_exists( 'cck_sanitize_manual_layout_components' ) ) {
	/**
	 * Sanitize the manual layout component list.
	 *
	 * @param mixed $components Raw component list.
	 * @return array
	 */
	function cck_sanitize_manual_layout_components( $components ) {
		if ( ! is_array( $components ) ) {
			return array();
		}

		$sanitized = array();

		foreach ( $components as $component ) {
			$clean = cck_sanitize_manual_layout_component( $component );

			if ( empty( $clean['type'] ) ) {
				continue;
			}

			$sanitized[] = $clean;
		}

		return array_values( $sanitized );
	}
}

if ( ! function_exists( 'cck_get_manual_layout_components' ) ) {
	/**
	 * Get the stored manual layout composition.
	 *
	 * @return array
	 */
	function cck_get_manual_layout_components() {
		$stored = get_option( cck_get_manual_layout_option_key(), null );

		if ( null === $stored ) {
			return cck_get_manual_layout_default_components();
		}

		if ( ! is_array( $stored ) || ! isset( $stored['components'] ) || ! is_array( $stored['components'] ) ) {
			return array();
		}

		return cck_sanitize_manual_layout_components( $stored['components'] );
	}
}

if ( ! function_exists( 'cck_get_manual_layout_definition' ) ) {
	/**
	 * Get the virtual manual layout definition.
	 *
	 * @return array
	 */
	function cck_get_manual_layout_definition() {
		return array(
			'id'          => 'manual',
			'name'        => __( 'Manual Composition', 'craft-commerce-kit' ),
			'description' => __( 'Editable component sequence stored in the option registry.', 'craft-commerce-kit' ),
			'version'     => defined( 'CCK_VERSION' ) ? CCK_VERSION : '1.0.0',
			'components'  => cck_get_manual_layout_components(),
			'_path'       => '',
		);
	}
}

if ( ! function_exists( 'cck_save_manual_layout_components' ) ) {
	/**
	 * Persist a manual layout component list.
	 *
	 * @param mixed $components Raw component list.
	 * @return array
	 */
	function cck_save_manual_layout_components( $components ) {
		$payload = array(
			'components' => cck_sanitize_manual_layout_components( $components ),
			'updated_at' => current_time( 'timestamp' ),
		);

		update_option( cck_get_manual_layout_option_key(), $payload, false );

		return $payload;
	}
}

if ( ! function_exists( 'cck_handle_manual_layout_save_request' ) ) {
	/**
	 * Handle manual layout save POST requests.
	 *
	 * @return void
	 */
	function cck_handle_manual_layout_save_request() {
		cck_require_admin_capability();
		check_admin_referer( 'cck_save_manual_layout' );

		$raw = isset( $_POST['cck_manual_layout']['components'] ) ? wp_unslash( $_POST['cck_manual_layout']['components'] ) : array();
		$result = cck_save_manual_layout_components( $raw );
		$args = array(
			'page' => 'craft-commerce-kit-layouts',
			'cck_notice' => ! empty( $result['components'] ) ? 'saved' : 'empty',
		);

		wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
		exit;
	}
}

add_action( 'admin_post_cck_save_manual_layout', 'cck_handle_manual_layout_save_request' );
