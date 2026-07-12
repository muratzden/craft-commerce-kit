<?php
/**
 * Trust component render dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_trust_block' ) ) {
/**
 * Trust component çıktısını oluşturur.
 *
 * @param array $atts     Temizlenmiş component değerleri.
 * @param array $manifest Component manifest verisi.
 * @return string
 */
function cck_component_package_render_trust_block( $atts = array(), $manifest = array() ) {
if ( function_exists( 'cck_component_trust_block' ) ) {
return cck_component_trust_block( $atts );
}

return '';
}
}
