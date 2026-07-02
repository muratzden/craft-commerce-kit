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
