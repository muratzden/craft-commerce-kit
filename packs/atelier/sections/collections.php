<?php
/**
 * Atelier collections section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'collection-grid',
	'attributes' => array(
		'items'   => 'Featured,/shop/|New Arrivals,/shop/|Best Sellers,/shop/',
		'columns' => '3',
	),
);