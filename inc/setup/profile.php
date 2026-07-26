<?php
/**
 * Brand profile helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_brand_profile_defaults' ) ) {
	/**
	 * Get default single-brand profile values.
	 *
	 * @return array
	 */
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
					'background' => '#f7f3ea',
					'surface'    => '#ffffff',
					'text'       => '#2b1a12',
					'accent'     => '#b87945',
				),
			),
		);
	}
}

if ( ! function_exists( 'cck_get_brand_profile' ) ) {
	/**
	 * Get the saved profile merged with defaults.
	 *
	 * @return array
	 */
	function cck_get_brand_profile() {
		$profile = get_option( 'cck_brand_profile', array() );

		return array_replace_recursive(
			cck_get_brand_profile_defaults(),
			is_array( $profile ) ? $profile : array()
		);
	}
}

if ( ! function_exists( 'cck_sanitize_brand_profile_color' ) ) {
	/**
	 * Sanitize a profile color and preserve a valid fallback.
	 *
	 * @param mixed  $value    Submitted color.
	 * @param string $fallback Fallback color.
	 * @return string
	 */
	function cck_sanitize_brand_profile_color( $value, $fallback ) {
		$color = is_string( $value )
			? sanitize_hex_color( $value )
			: '';

		if ( is_string( $color ) && '' !== $color ) {
			return strtolower( $color );
		}

		$fallback = sanitize_hex_color( $fallback );

		return is_string( $fallback ) && '' !== $fallback
			? strtolower( $fallback )
			: '#000000';
	}
}

if ( ! function_exists( 'cck_validate_brand_profile' ) ) {
	/**
	 * Validate and normalize a submitted brand profile.
	 *
	 * @param array $profile Submitted profile.
	 * @return array|WP_Error
	 */
	function cck_validate_brand_profile( array $profile ) {
		$defaults = cck_get_brand_profile_defaults();
		$current  = cck_get_brand_profile();

		$brand_name = isset( $profile['brand_name'] )
			? sanitize_text_field( $profile['brand_name'] )
			: '';

		if ( '' === $brand_name ) {
			return new WP_Error(
				'missing_brand_name',
				__( 'Enter a brand name.', 'craft-commerce-kit' )
			);
		}

		$brand_id = isset( $profile['id'] )
			? sanitize_key( $profile['id'] )
			: '';

		if ( '' === $brand_id ) {
			$brand_id = sanitize_title( $brand_name );
		}

		if ( '' === $brand_id ) {
			return new WP_Error(
				'invalid_brand_id',
				__( 'Enter a valid brand ID.', 'craft-commerce-kit' )
			);
		}

		$experience = isset( $profile['experience'] )
			? sanitize_key( $profile['experience'] )
			: 'atelier';

		if ( ! in_array( $experience, array( 'atelier' ), true ) ) {
			$experience = 'atelier';
		}

		$cta_url = isset( $profile['cta_url'] )
			? trim( (string) $profile['cta_url'] )
			: '';

		if ( '' !== $cta_url ) {
			$cta_url = esc_url_raw(
				$cta_url,
				array( 'http', 'https' )
			);

			if ( '' === $cta_url ) {
				return new WP_Error(
					'invalid_cta_url',
					__( 'Enter a valid HTTP or HTTPS CTA URL.', 'craft-commerce-kit' )
				);
			}
		}

		$submitted_colors = isset( $profile['tokens']['colors'] ) &&
			is_array( $profile['tokens']['colors'] )
				? $profile['tokens']['colors']
				: array();

		$current_colors = isset( $current['tokens']['colors'] ) &&
			is_array( $current['tokens']['colors'] )
				? $current['tokens']['colors']
				: $defaults['tokens']['colors'];

		$colors = array();

		foreach ( array( 'background', 'surface', 'text', 'accent' ) as $color_key ) {
			$submitted_value = isset( $submitted_colors[ $color_key ] )
				? $submitted_colors[ $color_key ]
				: '';

			$fallback_value = isset( $current_colors[ $color_key ] )
				? $current_colors[ $color_key ]
				: $defaults['tokens']['colors'][ $color_key ];

			$colors[ $color_key ] = cck_sanitize_brand_profile_color(
				$submitted_value,
				$fallback_value
			);
		}

		return array(
			'id'         => $brand_id,
			'brand_name' => $brand_name,
			'experience' => $experience,
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
			'cta_url'    => $cta_url,
			'tokens'     => array(
				'colors' => $colors,
			),
		);
	}
}

if ( ! function_exists( 'cck_sanitize_brand_profile' ) ) {
	/**
	 * Sanitize a profile for compatibility with existing integrations.
	 *
	 * @param array $profile Submitted profile.
	 * @return array
	 */
	function cck_sanitize_brand_profile( array $profile ) {
		$validated = cck_validate_brand_profile( $profile );

		return is_wp_error( $validated )
			? array()
			: $validated;
	}
}

if ( ! function_exists( 'cck_save_brand_profile' ) ) {
	/**
	 * Validate and save a brand profile.
	 *
	 * @param array $profile Submitted profile.
	 * @return true|WP_Error
	 */
	function cck_save_brand_profile( array $profile ) {
		$validated = cck_validate_brand_profile( $profile );

		if ( is_wp_error( $validated ) ) {
			return $validated;
		}

		update_option( 'cck_brand_profile', $validated, false );

		return true;
	}
}