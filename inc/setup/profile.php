<?php
/**
 * Brand profile helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_brand_profile_defaults' ) ) {
	function cck_get_brand_profile_defaults() {
		return array(
			'id'         => '',
			'brand_name' => get_bloginfo( 'name' ),
			'experience' => 'atelier',
			'eyebrow'    => '',
			'headline'   => '',
			'text'       => '',
			'cta_label'  => '',
			'cta_url'    => '',
			'tokens'     => array(
				'colors' => array(
					'background' => '#F7F3EA',
					'surface'    => '#FFFFFF',
					'text'       => '#2B1A12',
					'accent'     => '#B87945',
				),
			),
		);
	}
}

if ( ! function_exists( 'cck_get_brand_profile' ) ) {
	function cck_get_brand_profile() {
		$profile = get_option( 'cck_brand_profile', array() );

		return array_replace_recursive(
			cck_get_brand_profile_defaults(),
			is_array( $profile ) ? $profile : array()
		);
	}
}

if ( ! function_exists( 'cck_sanitize_brand_profile' ) ) {
	function cck_sanitize_brand_profile( array $profile ) {
		$brand_name = isset( $profile['brand_name'] )
			? sanitize_text_field( $profile['brand_name'] )
			: '';

		$brand_id = isset( $profile['id'] )
			? sanitize_key( $profile['id'] )
			: '';

		if ( '' === $brand_id && '' !== $brand_name ) {
			$brand_id = sanitize_title( $brand_name );
		}

		$colors = isset( $profile['tokens']['colors'] ) && is_array( $profile['tokens']['colors'] )
			? $profile['tokens']['colors']
			: array();

		return array(
			'id'         => $brand_id,
			'brand_name' => $brand_name,
			'experience' => isset( $profile['experience'] )
				? sanitize_key( $profile['experience'] )
				: 'atelier',
			'eyebrow'    => isset( $profile['eyebrow'] )
				? sanitize_text_field( $profile['eyebrow'] )
				: '',
			'headline'   => isset( $profile['headline'] )
				? sanitize_text_field( $profile['headline'] )
				: '',
			'text'       => isset( $profile['text'] )
				? sanitize_textarea_field( $profile['text'] )
				: '',
			'cta_label'  => isset( $profile['cta_label'] )
				? sanitize_text_field( $profile['cta_label'] )
				: '',
			'cta_url'    => isset( $profile['cta_url'] )
				? esc_url_raw( $profile['cta_url'] )
				: '',
			'tokens'     => array(
				'colors' => array(
					'background' => isset( $colors['background'] )
						? sanitize_hex_color( $colors['background'] )
						: '',
					'surface'    => isset( $colors['surface'] )
						? sanitize_hex_color( $colors['surface'] )
						: '',
					'text'       => isset( $colors['text'] )
						? sanitize_hex_color( $colors['text'] )
						: '',
					'accent'     => isset( $colors['accent'] )
						? sanitize_hex_color( $colors['accent'] )
						: '',
				),
			),
		);
	}
}

if ( ! function_exists( 'cck_save_brand_profile' ) ) {
	function cck_save_brand_profile( array $profile ) {
		$profile = cck_sanitize_brand_profile( $profile );

		if ( '' === $profile['brand_name'] || '' === $profile['id'] ) {
			return false;
		}

		update_option( 'cck_brand_profile', $profile, false );

		return true;
	}
}