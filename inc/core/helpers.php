<?php
/**
 * Genel yardımcı fonksiyonlar.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_debug_log' ) ) {
	/**
	 * Debug açıkken standart prefix ile log yazar.
	 *
	 * @param string $message Log mesajı.
	 * @return void
	 */
	function cck_debug_log( $message ) {
		if ( ! defined( 'CCK_DEBUG' ) || ! CCK_DEBUG ) {
			return;
		}

		if ( is_array( $message ) || is_object( $message ) ) {
			$message = wp_json_encode( $message );
		}

		error_log( '[Craft Commerce Kit] ' . (string) $message );
	}
}

if ( ! function_exists( 'cck_array_get' ) ) {
	/**
	 * Array içinden güvenli değer okur.
	 *
	 * @param array  $array   Kaynak array.
	 * @param string $key     Okunacak anahtar.
	 * @param mixed  $default Varsayılan değer.
	 * @return mixed
	 */
	function cck_array_get( $array, $key, $default = null ) {
		return is_array( $array ) && array_key_exists( $key, $array ) ? $array[ $key ] : $default;
	}
}

if ( ! function_exists( 'cck_manifest_get' ) ) {
	/**
	 * Manifest içinden güvenli alan okur.
	 *
	 * @param array  $manifest Manifest verisi.
	 * @param string $key      Alan adı.
	 * @param mixed  $default  Varsayılan değer.
	 * @return mixed
	 */
	function cck_manifest_get( $manifest, $key, $default = null ) {
		return cck_array_get( $manifest, $key, $default );
	}
}

if ( ! function_exists( 'cck_to_bool' ) ) {
	/**
	 * Gelen değeri boolean tipe dönüştürür.
	 *
	 * @param mixed $value Değer.
	 * @return bool
	 */
	function cck_to_bool( $value ) {
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}
}

if ( ! function_exists( 'cck_to_string' ) ) {
	/**
	 * Gelen değeri güvenli string değere dönüştürür.
	 *
	 * @param mixed $value Değer.
	 * @return string
	 */
	function cck_to_string( $value ) {
		if ( is_scalar( $value ) || null === $value ) {
			return (string) $value;
		}

		return '';
	}
}

if ( ! function_exists( 'cck_sanitize_key_list' ) ) {
	/**
	 * Liste elemanlarını sanitize_key ile temizler.
	 *
	 * @param array $items Liste.
	 * @return array
	 */
	function cck_sanitize_key_list( $items ) {
		if ( ! is_array( $items ) ) {
			return array();
		}

		return array_values( array_filter( array_map( 'sanitize_key', $items ) ) );
	}
}

if ( ! function_exists( 'cck_sanitize_callback_name' ) ) {
	/**
	 * Kullanılabilir sanitize callback değerini döndürür.
	 *
	 * @param mixed $callback Callback değeri.
	 * @return callable|string
	 */
	function cck_sanitize_callback_name( $callback ) {
		return is_callable( $callback ) ? $callback : 'sanitize_text_field';
	}
}

if ( ! function_exists( 'cck_get_demo_asset_path' ) ) {
	/**
	 * Get the absolute path for a bundled demo asset.
	 *
	 * @param string $filename Asset filename.
	 * @return string
	 */
	function cck_get_demo_asset_path( $filename ) {
		$filename = sanitize_file_name( cck_to_string( $filename ) );

		if ( '' === $filename ) {
			return '';
		}

		return CCK_PLUGIN_DIR . 'assets/demo/' . $filename;
	}
}

if ( ! function_exists( 'cck_get_demo_asset' ) ) {
	/**
	 * Get demo asset metadata.
	 *
	 * @param string $filename Asset filename.
	 * @param string $alt      Alt text.
	 * @return array
	 */
	function cck_get_demo_asset( $filename, $alt = '' ) {
		static $asset_cache = array();

		$filename = sanitize_file_name( cck_to_string( $filename ) );

		if ( '' === $filename ) {
			return array(
				'url'    => '',
				'path'   => '',
				'width'  => 0,
				'height' => 0,
				'alt'    => sanitize_text_field( cck_to_string( $alt ) ),
			);
		}

		if ( ! isset( $asset_cache[ $filename ] ) ) {
			$path = cck_get_demo_asset_path( $filename );
			$url  = '' !== $path ? CCK_PLUGIN_URL . 'assets/demo/' . $filename : '';
			$size = array( 0, 0 );

			if ( '' !== $path && file_exists( $path ) ) {
				$detected = @getimagesize( $path );

				if ( is_array( $detected ) && ! empty( $detected[0] ) && ! empty( $detected[1] ) ) {
					$size = array( absint( $detected[0] ), absint( $detected[1] ) );
				}
			}

			$asset_cache[ $filename ] = array(
				'url'    => $url,
				'path'   => $path,
				'width'  => $size[0],
				'height' => $size[1],
			);
		}

		return array_merge(
			$asset_cache[ $filename ],
			array(
				'alt' => sanitize_text_field( cck_to_string( $alt ) ),
			)
		);
	}
}

if ( ! function_exists( 'cck_render_svg_icon' ) ) {
	/**
	 * Render a small inline SVG icon.
	 *
	 * @param string $icon Icon name.
	 * @return string
	 */
	function cck_render_svg_icon( $icon ) {
		$icon = sanitize_key( $icon );

		$icons = array(
			'arrow-right' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5 12h12"></path><path d="M13 6l6 6-6 6"></path></svg>',
			'shield'      => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3l7 3v5c0 4.8-3.1 8.7-7 10-3.9-1.3-7-5.2-7-10V6l7-3z"></path><path d="M9 12l2 2 4-5"></path></svg>',
			'spark'       => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3l1.9 6.1L20 11l-6.1 1.9L12 19l-1.9-6.1L4 11l6.1-1.9L12 3z"></path></svg>',
			'leaf'        => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 4c-7.5.3-13 4.2-15 11 .9 3 3.2 5 6.5 5 6 0 9.5-5.3 8.5-16z"></path><path d="M5 19c3.3-4.3 6.9-7.1 11-9"></path></svg>',
			'arrow-trend' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 16l6-6 4 4 6-8"></path><path d="M14 6h6v6"></path></svg>',
			'heart'       => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 20s-7-4.4-9.2-8.4C1 8.1 2.6 5.5 5.6 5.1c1.8-.2 3.4.7 4.4 2 1-1.3 2.6-2.2 4.4-2 3 .4 4.6 3 2.8 6.5C19 15.6 12 20 12 20z"></path></svg>',
			'eye'         => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path><circle cx="12" cy="12" r="2.8"></circle></svg>',
			'bag'         => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 8h12l-1 12H7L6 8z"></path><path d="M9 8a3 3 0 0 1 6 0"></path></svg>',
			'star'        => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 16.9 6.6 19.8l1-6.1L3.2 9.4l6.1-.9L12 3z"></path></svg>',
			'instagram'   => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="4" y="4" width="16" height="16" rx="4"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17" cy="7" r="1"></circle></svg>',
			'pinterest'   => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3a9 9 0 0 0-3 17.5c-.1-1-.2-2.6 0-3.7l1.4-5.9s-.4-.9-.4-2.2c0-2 1.2-3.4 2.7-3.4 1.3 0 1.9 1 1.9 2.2 0 1.4-.9 3.4-1.4 5.3-.4 1.6.8 2.9 2.4 2.9 2.9 0 5-3.7 5-8.1 0-3.3-2.2-5.8-6.2-5.8-4.5 0-7.3 3.4-7.3 7.1 0 1.3.5 2.2 1.2 2.8.1.2.1.4.1.6l-.5 2c-.2.6-.5 1.4-.8 2 .9.3 1.9.4 2.8.4 4.9 0 9-4 9-9A9 9 0 0 0 12 3z"></path></svg>',
		);

		return isset( $icons[ $icon ] ) ? $icons[ $icon ] : $icons['arrow-right'];
	}
}
