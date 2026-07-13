<?php
/**
 * Cta component render dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_cta' ) ) {
/**
 * Cta component çıktısını oluşturur.
 *
 * @param array $atts     Temizlenmiş component değerleri.
 * @param array $manifest Component manifest verisi.
 * @return string
 */
function cck_component_package_render_cta( $atts = array(), $manifest = array() ) {
if ( function_exists( 'cck_component_cta' ) ) {
return cck_component_cta( $atts );
}

return '';
}
}
