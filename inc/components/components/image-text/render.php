<?php
/**
 * İmage Text component render dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_image_text' ) ) {
/**
 * İmage Text component çıktısını oluşturur.
 *
 * @param array $atts     Temizlenmiş component değerleri.
 * @param array $manifest Component manifest verisi.
 * @return string
 */
function cck_component_package_render_image_text( $atts = array(), $manifest = array() ) {
if ( function_exists( 'cck_component_image_text' ) ) {
return cck_component_image_text( $atts );
}

return '';
}
}
