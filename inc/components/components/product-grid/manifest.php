<?php
/**
 * Product Grid component manifest dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'product_grid',
	'name'        => __( 'Product Grid', 'craft-commerce-kit' ),
	'description' => __( 'Latest WooCommerce products grid.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'commerce',
	'icon'        => 'products',
	'preview'     => '',
	'supports'    => array(
		'background',
		'spacing',
		'typography',
		'visibility',
	),
	'settings'    => array(
		'eyebrow' => array(
			'type'              => 'text',
			'label'             => __( 'Eyebrow', 'craft-commerce-kit' ),
			'description'       => __( 'Small label displayed above the product grid title.', 'craft-commerce-kit' ),
			'default'           => __( 'Latest Products', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'title'   => array(
			'type'              => 'text',
			'label'             => __( 'Title', 'craft-commerce-kit' ),
			'description'       => __( 'Product grid heading.', 'craft-commerce-kit' ),
			'default'           => __( 'Fresh from the storefront.', 'craft-commerce-kit' ),
			'required'          => true,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'limit'   => array(
			'type'              => 'number',
			'label'             => __( 'Product Limit', 'craft-commerce-kit' ),
			'description'       => __( 'Number of products to display.', 'craft-commerce-kit' ),
			'default'           => 4,
			'required'          => false,
			'sanitize_callback' => 'absint',
		),
	),
);
