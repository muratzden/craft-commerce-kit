<?php
/**
 * Component manifest do?rulay?c?.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_required_manifest_fields' ) ) {
	/**
	 * Manifest i?in beklenen temel alanlar? d?nd?r?r.
	 *
	 * @return array
	 */
	function cck_get_component_required_manifest_fields() {
		return array(
			'id',
			'name',
			'description',
			'version',
			'category',
			'supports',
			'settings',
		);
	}
}

if ( ! function_exists( 'cck_validate_component_manifest' ) ) {
	/**
	 * Manifest alanlar?n? do?rular ve eksikleri g?venli varsay?lanlarla tamamlar.
	 *
	 * @param array  $manifest      Manifest verisi.
	 * @param string $component_dir Component dizini.
	 * @return array
	 */
	function cck_validate_component_manifest( $manifest, $component_dir ) {
		if ( ! is_array( $manifest ) ) {
			cck_debug_log( 'Manifest array de?il: ' . $component_dir );
			return array();
		}

		foreach ( cck_get_component_required_manifest_fields() as $field ) {
			if ( ! array_key_exists( $field, $manifest ) ) {
				cck_debug_log( 'Manifest alan? eksik: ' . $field . ' (' . $component_dir . ')' );
			}
		}

		$component_id = sanitize_key( cck_manifest_get( $manifest, 'id', basename( $component_dir ) ) );

		if ( empty( $component_id ) ) {
			cck_debug_log( 'Manifest id bo?: ' . $component_dir );
			return array();
		}

		$manifest['id']          = $component_id;
		$manifest['name']        = cck_manifest_get( $manifest, 'name', $component_id );
		$manifest['label']       = $manifest['name'];
		$manifest['description'] = cck_manifest_get( $manifest, 'description', '' );
		$manifest['version']     = sanitize_text_field( cck_to_string( cck_manifest_get( $manifest, 'version', '1.0.0' ) ) );
		$manifest['category']    = sanitize_key( cck_manifest_get( $manifest, 'category', 'ui' ) );
		$manifest['icon']        = sanitize_key( cck_manifest_get( $manifest, 'icon', 'layout' ) );
		$manifest['preview']     = esc_url_raw( cck_manifest_get( $manifest, 'preview', '' ) );
		$manifest['supports']    = cck_validate_component_supports( cck_manifest_get( $manifest, 'supports', array() ) );
		$manifest['settings']    = cck_validate_component_settings( cck_manifest_get( $manifest, 'settings', array() ) );
		$manifest['_path']       = trailingslashit( $component_dir );
		$manifest['_render']     = trailingslashit( $component_dir ) . 'render.php';

		return apply_filters( 'cck_component_manifest', $manifest );
	}
}

if ( ! function_exists( 'cck_validate_component_supports' ) ) {
	/**
	 * Supports listesini izin verilen de?erlerle s?n?rlar.
	 *
	 * @param array $supports Supports listesi.
	 * @return array
	 */
	function cck_validate_component_supports( $supports ) {
		$allowed  = cck_get_component_supported_features();
		$supports = cck_sanitize_key_list( $supports );

		return array_values( array_intersect( $supports, $allowed ) );
	}
}

if ( ! function_exists( 'cck_validate_component_settings' ) ) {
	/**
	 * Settings tan?mlar?n? standart yap?ya d?n??t?r?r.
	 *
	 * @param array $settings Settings tan?mlar?.
	 * @return array
	 */
	function cck_validate_component_settings( $settings ) {
		$validated = array();

		if ( ! is_array( $settings ) ) {
			return $validated;
		}

		foreach ( $settings as $setting_id => $setting ) {
			$setting_id = sanitize_key( $setting_id );

			if ( empty( $setting_id ) || ! is_array( $setting ) ) {
				cck_debug_log( 'Ge?ersiz setting atland?: ' . $setting_id );
				continue;
			}

			$validated[ $setting_id ] = array(
				'type'              => sanitize_key( cck_array_get( $setting, 'type', 'text' ) ),
				'label'             => cck_array_get( $setting, 'label', $setting_id ),
				'description'       => cck_array_get( $setting, 'description', '' ),
				'default'           => cck_array_get( $setting, 'default', '' ),
				'required'          => cck_to_bool( cck_array_get( $setting, 'required', false ) ),
				'sanitize_callback' => cck_sanitize_callback_name( cck_array_get( $setting, 'sanitize_callback', 'sanitize_text_field' ) ),
			);
		}

		return $validated;
	}
}
