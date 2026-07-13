<?php
/**
 * Admin data provider.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_admin_component_alias_map' ) ) {
	/**
	 * Get component alias map keyed by official component ID.
	 *
	 * @return array
	 */
	function cck_get_admin_component_alias_map() {
		return array(
			'trust-block'   => array( 'trust' ),
			'product-grid'  => array( 'product_grid' ),
		);
	}
}

if ( ! function_exists( 'cck_get_admin_environment_summary' ) ) {
	/**
	 * Get a normalized environment summary.
	 *
	 * @return array
	 */
	function cck_get_admin_environment_summary() {
		$theme      = wp_get_theme();
		$theme_name = $theme->exists() ? $theme->get( 'Name' ) : __( 'Unknown', 'craft-commerce-kit' );

		return array(
			'wp_version'  => get_bloginfo( 'version' ),
			'php_version' => PHP_VERSION,
			'theme_name'  => $theme_name,
		);
	}
}

if ( ! function_exists( 'cck_get_admin_overview_data' ) ) {
	/**
	 * Get overview panel data.
	 *
	 * @return array
	 */
	function cck_get_admin_overview_data() {
		$components  = function_exists( 'cck_get_component_registry' ) ? cck_get_component_registry() : array();
		$experiences  = function_exists( 'cck_get_experiences' ) ? cck_get_experiences() : array();
		$brands       = function_exists( 'cck_registry_all' ) ? cck_registry_all( 'brand' ) : array();
		$active_brand = function_exists( 'cck_get_active_brand' ) ? cck_get_active_brand() : array();
		$active_brand_id = function_exists( 'cck_get_active_brand_id' ) ? cck_get_active_brand_id() : '';
		$active_brand_name = '';

		if ( is_array( $active_brand ) ) {
			$active_brand_name = ! empty( $active_brand['name'] ) ? $active_brand['name'] : ( ! empty( $active_brand['brand_name'] ) ? $active_brand['brand_name'] : '' );
		}

		$environment = cck_get_admin_environment_summary();

		return array(
			'plugin_version'        => defined( 'CCK_VERSION' ) ? CCK_VERSION : '',
			'registered_components' => count( $components ),
			'registered_experiences'=> count( $experiences ),
			'registered_brands'     => count( $brands ),
			'active_brand_id'       => $active_brand_id,
			'active_brand_name'     => $active_brand_name,
			'default_brand'         => function_exists( 'cck_get_brand' ) ? cck_get_brand( 'default' ) : array(),
			'woocommerce_active'    => function_exists( 'cck_is_woocommerce_active' ) ? cck_is_woocommerce_active() : false,
			'environment'           => $environment,
			'environment_summary'   => sprintf(
				'%1$s / PHP %2$s / %3$s',
				isset( $environment['wp_version'] ) ? $environment['wp_version'] : '',
				isset( $environment['php_version'] ) ? $environment['php_version'] : '',
				isset( $environment['theme_name'] ) ? $environment['theme_name'] : ''
			),
		);
	}
}

if ( ! function_exists( 'cck_get_admin_component_rows' ) ) {
	/**
	 * Get component catalog rows.
	 *
	 * @return array
	 */
	function cck_get_admin_component_rows() {
		$registry = function_exists( 'cck_get_component_registry' ) ? cck_get_component_registry() : array();
		$aliases  = cck_get_admin_component_alias_map();
		$rows     = array();

		foreach ( $registry as $component_id => $component ) {
			$callback       = isset( $component['callback'] ) ? $component['callback'] : '';
			$supports       = isset( $component['supports'] ) && is_array( $component['supports'] ) ? $component['supports'] : array();
			$settings       = isset( $component['settings'] ) && is_array( $component['settings'] ) ? $component['settings'] : array();
			$component_name = '';

			if ( ! empty( $component['name'] ) ) {
				$component_name = $component['name'];
			} elseif ( ! empty( $component['label'] ) ) {
				$component_name = $component['label'];
			} else {
				$component_name = $component_id;
			}

			$rows[] = array(
				'id'                  => $component_id,
				'label'               => $component_name,
				'callback'            => $callback,
				'supports_count'      => count( $supports ),
				'defaults_count'      => count( function_exists( 'cck_get_component_defaults' ) ? cck_get_component_defaults( $component_id ) : array() ),
				'schema_fields_count'  => count( $settings ),
				'status'              => is_string( $callback ) && '' !== $callback && is_callable( $callback ) ? __( 'Callable', 'craft-commerce-kit' ) : __( 'Missing callback', 'craft-commerce-kit' ),
				'aliases'             => isset( $aliases[ $component_id ] ) ? $aliases[ $component_id ] : array(),
			);
		}

		return $rows;
	}
}

if ( ! function_exists( 'cck_get_admin_experience_rows' ) ) {
	/**
	 * Get experience catalog rows.
	 *
	 * @return array
	 */
	function cck_get_admin_experience_rows() {
		$experiences = function_exists( 'cck_get_experiences' ) ? cck_get_experiences() : array();
		$brands      = function_exists( 'cck_registry_all' ) ? cck_registry_all( 'brand' ) : array();
		$rows        = array();

		foreach ( $experiences as $experience_id => $experience ) {
			$brand_id = '';
			foreach ( $brands as $candidate_id => $brand ) {
				if ( is_array( $brand ) && isset( $brand['experience'] ) && sanitize_key( $brand['experience'] ) === $experience_id ) {
					$brand_id = $candidate_id;
					break;
				}
			}

			$layout = isset( $experience['layout'] ) ? sanitize_key( $experience['layout'] ) : '';

			$section_count = function_exists( 'cck_get_experience_section_count' ) ? cck_get_experience_section_count( $experience_id ) : 0;

			$rows[] = array(
				'id'            => $experience_id,
				'label'         => ! empty( $experience['name'] ) ? $experience['name'] : $experience_id,
				'brand'         => $brand_id,
				'layout'        => $layout,
				'section_count' => $section_count,
				'status'        => ( '' !== $layout && $section_count > 0 ) ? __( 'Ready', 'craft-commerce-kit' ) : __( 'Incomplete', 'craft-commerce-kit' ),
			);
		}

		return $rows;
	}
}

if ( ! function_exists( 'cck_get_admin_brand_source_label' ) ) {
	/**
	 * Get a human-readable brand source label.
	 *
	 * @param string $brand_id Brand ID.
	 * @param array  $brand    Brand definition.
	 * @return string
	 */
	function cck_get_admin_brand_source_label( $brand_id, array $brand ) {
		$brand_id = sanitize_key( $brand_id );

		if ( '' === $brand_id ) {
			return __( 'Registry', 'craft-commerce-kit' );
		}

		if ( isset( $brand['experience'] ) && 'atelier' === sanitize_key( $brand['experience'] ) ) {
			return __( 'Atelier preset', 'craft-commerce-kit' );
		}

		if ( in_array( $brand_id, array( 'default', 'demo' ), true ) ) {
			return __( 'Runtime brand', 'craft-commerce-kit' );
		}

		return __( 'Registry', 'craft-commerce-kit' );
	}
}

if ( ! function_exists( 'cck_get_admin_brand_rows' ) ) {
	/**
	 * Get brand catalog rows.
	 *
	 * @return array
	 */
	function cck_get_admin_brand_rows() {
		$brands         = function_exists( 'cck_registry_all' ) ? cck_registry_all( 'brand' ) : array();
		$active_brand   = function_exists( 'cck_get_active_brand_id' ) ? cck_get_active_brand_id() : '';
		$rows           = array();

		foreach ( $brands as $brand_id => $brand ) {
			$label = '';

			if ( ! empty( $brand['name'] ) ) {
				$label = $brand['name'];
			} elseif ( ! empty( $brand['brand_name'] ) ) {
				$label = $brand['brand_name'];
			} else {
				$label = $brand_id;
			}

			$attribute_count = 0;

			if ( isset( $brand['attributes'] ) && is_array( $brand['attributes'] ) ) {
				$attribute_count = count( $brand['attributes'] );
			} else {
				$attribute_count = count( $brand );

				if ( isset( $brand['id'] ) ) {
					$attribute_count--;
				}
			}

			$status_items = array();

			if ( 'default' === $brand_id ) {
				$status_items[] = __( 'Default', 'craft-commerce-kit' );
			}

			if ( $active_brand === $brand_id ) {
				$status_items[] = __( 'Active', 'craft-commerce-kit' );
			}

			if ( empty( $status_items ) ) {
				$status_items[] = __( 'Registered', 'craft-commerce-kit' );
			}

			$rows[] = array(
				'id'              => $brand_id,
				'label'           => $label,
				'source'          => cck_get_admin_brand_source_label( $brand_id, $brand ),
				'experience'      => isset( $brand['experience'] ) ? sanitize_key( $brand['experience'] ) : '',
				'status'          => implode( ', ', $status_items ),
				'attribute_count' => $attribute_count,
			);
		}

		return $rows;
	}
}

if ( ! function_exists( 'cck_get_admin_settings_data' ) ) {
	/**
	 * Get settings view data.
	 *
	 * @return array
	 */
	function cck_get_admin_settings_data() {
		return array(
			'message' => __( 'No configurable settings are currently available.', 'craft-commerce-kit' ),
		);
	}
}
