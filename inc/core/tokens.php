<?php
/**
 * Design tokens.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_design_tokens' ) ) {
	/**
	 * Get design tokens.
	 *
	 * @param string $brand_id Optional registered brand identifier.
	 * @return array
	 */
	function cck_get_design_tokens( $brand_id = '' ) {
		$tokens = array(
			'colors'  => array(
				'background'  => '#F7F3EA',
				'surface'     => '#FFFFFF',
				'surface_alt' => '#E8DDCC',
				'text'        => '#2B1A12',
				'muted'       => '#6E6258',
				'heading'     => '#171412',
				'border'      => '#D8C9B6',
				'accent'      => '#B87945',
				'dark'        => '#171412',
			),
			'spacing' => array(
				'xs'  => '8px',
				'sm'  => '16px',
				'md'  => '24px',
				'lg'  => '48px',
				'xl'  => '72px',
				'xxl' => '112px',
			),
			'radius'  => array(
				'sm' => '6px',
				'md' => '12px',
				'lg' => '24px',
			),
			'layout'  => array(
				'container' => '1180px',
				'narrow'    => '760px',
				'wide'      => '1440px',
			),
		);

		$brand_id = sanitize_key( $brand_id );

		if ( '' !== $brand_id && function_exists( 'cck_get_brand' ) ) {
			$active_brand = cck_get_brand( $brand_id );
		} else {
			$active_brand = function_exists( 'cck_get_active_brand' )
				? cck_get_active_brand()
				: array();
		}

		$brand_tokens = isset( $active_brand['tokens'] ) && is_array( $active_brand['tokens'] )
			? $active_brand['tokens']
			: array();

		foreach ( $brand_tokens as $group => $values ) {
			if ( ! is_array( $values ) ) {
				continue;
			}

			$tokens[ $group ] = array_merge(
				isset( $tokens[ $group ] ) && is_array( $tokens[ $group ] )
					? $tokens[ $group ]
					: array(),
				$values
			);
		}

		return apply_filters( 'cck_design_tokens', $tokens );
	}
}

if ( ! function_exists( 'cck_token_css_name' ) ) {
	/**
	 * Build a CSS custom property name.
	 *
	 * @param string $group Token group.
	 * @param string $key Token key.
	 * @return string
	 */
	function cck_token_css_name( $group, $key ) {
		return '--cck-' . sanitize_key( $group ) . '-' . sanitize_key( str_replace( '_', '-', $key ) );
	}
}

if ( ! function_exists( 'cck_get_design_tokens_css' ) ) {
	/**
	 * Build design token CSS for a selector.
	 *
	 * @param string $selector CSS selector.
	 * @param string $brand_id Optional registered brand identifier.
	 * @return string
	 */
	function cck_get_design_tokens_css( $selector = ':root', $brand_id = '' ) {
		$tokens   = cck_get_design_tokens( $brand_id );
		$selector = trim( $selector );

		if ( '' === $selector ) {
			$selector = ':root';
		}

		$css = $selector . '{';

		foreach ( $tokens as $group => $values ) {
			if ( ! is_array( $values ) ) {
				continue;
			}

			foreach ( $values as $key => $value ) {
				$css .= cck_token_css_name( $group, $key );
				$css .= ':';
				$css .= sanitize_text_field( $value );
				$css .= ';';
			}
		}

		$css .= '}';

		return $css;
	}
}

if ( ! function_exists( 'cck_print_design_tokens' ) ) {
	/**
	 * Print CSS custom properties.
	 *
	 * @return void
	 */
	function cck_print_design_tokens() {
		echo '<style id="cck-design-tokens">';
		echo esc_html( cck_get_design_tokens_css( ':root' ) );
		echo '</style>' . "\n";
	}
}
