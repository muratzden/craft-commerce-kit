<?php
/**
 * Component interface standard?.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! interface_exists( 'CCK_Component_Interface' ) ) {
	/**
	 * Gelecekte class tabanl? component paketleri i?in ortak s?zle?me sa?lar.
	 */
	interface CCK_Component_Interface {
		/**
		 * Component manifest verisini d?nd?r?r.
		 *
		 * @return array
		 */
		public function get_manifest();

		/**
		 * Component varsay?lan de?erlerini d?nd?r?r.
		 *
		 * @return array
		 */
		public function get_defaults();

		/**
		 * Component HTML ??kt?s?n? ?retir.
		 *
		 * @param array $atts Render de?erleri.
		 * @return string
		 */
		public function render( $atts = array() );
	}
}
