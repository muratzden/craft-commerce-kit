<?php
/**
 * Product Grid component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'product_grid',
	'name'        => __( 'Product Grid', 'craft-commerce-kit' ),
	'description' => __( 'Latest WooCommerce products grid.', 'craft-commerce-kit' ),
	'category'    => 'commerce',
	'version'     => '1.0.0',
	'supports'    => array(
		'background',
		'spacing',
		'typography',
	),
);
