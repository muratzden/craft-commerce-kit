<?php
defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'collection-grid',
	'name'        => __( 'Collection Grid', 'craft-commerce-kit' ),
	'description' => __( 'Collection links rendered by the legacy collection-grid callback.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'screenoptions',
	'preview'     => array(
		'attributes' => array(
			'items'   => 'Featured,Curated highlights,/shop/featured/,featured.webp|New Arrivals,Fresh seasonal additions,/shop/new-arrivals/,new-arrivals.webp|Best Sellers,Customer-loved essentials,/shop/best-sellers/,best-sellers.webp',
			'columns' => '3',
		),
	),
	'callback'    => 'cck_component_package_render_collection_grid',
	'supports'    => array( 'background', 'spacing', 'typography', 'visibility' ),
	'settings'    => array(
		'items'   => array( 'type' => 'textarea', 'label' => __( 'Items', 'craft-commerce-kit' ), 'default' => 'Featured,Curated highlights,/shop/featured/,featured.webp|New Arrivals,Fresh seasonal additions,/shop/new-arrivals/,new-arrivals.webp|Best Sellers,Customer-loved essentials,/shop/best-sellers/,best-sellers.webp', 'sanitize_callback' => 'sanitize_text_field' ),
		'columns' => array( 'type' => 'number', 'label' => __( 'Columns', 'craft-commerce-kit' ), 'default' => '2', 'sanitize_callback' => 'absint' ),
	),
);
