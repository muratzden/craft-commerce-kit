<?php
/**
 * Homepage layout manifest dosyas?.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'homepage',
	'name'        => __( 'Homepage', 'craft-commerce-kit' ),
	'description' => __( 'Default component-based storefront homepage layout.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'components'  => array(
		'hero',
		'usp',
		'product_grid',
	),
);
