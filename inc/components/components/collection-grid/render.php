<?php
/**
 * Collection Grid component render dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_collection_grid' ) ) {
/**
 * Collection Grid component çıktısını oluşturur.
 *
 * @param array $atts     Temizlenmiş component değerleri.
 * @param array $manifest Component manifest verisi.
 * @return string
 */
function cck_component_package_render_collection_grid( $atts = array(), $manifest = array() ) {
if ( function_exists( 'cck_component_collection_grid' ) ) {
return cck_component_collection_grid( $atts );
}

return '';
}
}
