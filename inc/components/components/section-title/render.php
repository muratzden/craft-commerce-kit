<?php
/**
 * Section Title component render dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_section_title' ) ) {
/**
 * Section Title component çıktısını oluşturur.
 *
 * @param array $atts     Temizlenmiş component değerleri.
 * @param array $manifest Component manifest verisi.
 * @return string
 */
function cck_component_package_render_section_title( $atts = array(), $manifest = array() ) {
if ( function_exists( 'cck_component_section_title' ) ) {
return cck_component_section_title( $atts );
}

return '';
}
}
