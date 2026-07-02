<?php
/**
 * Component interface standardı.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! interface_exists( 'CCK_Component_Interface' ) ) {
	/**
	 * Gelecekte class tabanlı component paketleri için ortak sözleşme sağlar.
	 */
	interface CCK_Component_Interface {
		/**
		 * Component manifest verisini döndürür.
		 *
		 * @return array
		 */
		public function get_manifest();

		/**
		 * Component varsayılan değerlerini döndürür.
		 *
		 * @return array
		 */
		public function get_defaults();

		/**
		 * Component HTML çıktısını üretir.
		 *
		 * @param array $atts Render değerleri.
		 * @return string
		 */
		public function render( $atts = array() );
	}
}
