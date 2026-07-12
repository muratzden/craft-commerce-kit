<?php
defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'collection-grid',
	'name'        => __( 'Collection Grid', 'craft-commerce-kit' ),
	'description' => __( 'Collection links rendered by the legacy collection-grid callback.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'screenoptions',
	'preview'     => '',
	'callback'    => 'cck_component_collection_grid',
	'supports'    => array( 'background', 'spacing', 'typography', 'visibility' ),
	'settings'    => array(
		'items'   => array( 'type' => 'textarea', 'label' => __( 'Items', 'craft-commerce-kit' ), 'default' => 'Bags,/product-category/bags/|Wallets,/product-category/wallets/', 'sanitize_callback' => 'sanitize_text_field' ),
		'columns' => array( 'type' => 'number', 'label' => __( 'Columns', 'craft-commerce-kit' ), 'default' => '2', 'sanitize_callback' => 'absint' ),
	),
);
