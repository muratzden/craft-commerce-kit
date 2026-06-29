<?php
/**
 * USP component manifest dosyas?.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'usp',
	'name'        => __( 'USP', 'craft-commerce-kit' ),
	'description' => __( 'Three-column unique selling proposition section.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'star-filled',
	'preview'     => '',
	'supports'    => array(
		'background',
		'spacing',
		'typography',
		'animation',
		'visibility',
	),
	'settings'    => array(
		'item_one_title'   => array(
			'type'              => 'text',
			'label'             => __( 'First Item Title', 'craft-commerce-kit' ),
			'description'       => __( 'Title for the first USP item.', 'craft-commerce-kit' ),
			'default'           => __( 'Handmade Quality', 'craft-commerce-kit' ),
			'required'          => true,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'item_one_text'    => array(
			'type'              => 'textarea',
			'label'             => __( 'First Item Text', 'craft-commerce-kit' ),
			'description'       => __( 'Text for the first USP item.', 'craft-commerce-kit' ),
			'default'           => __( 'Designed for product stories that value material, process, and detail.', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'item_two_title'   => array(
			'type'              => 'text',
			'label'             => __( 'Second Item Title', 'craft-commerce-kit' ),
			'description'       => __( 'Title for the second USP item.', 'craft-commerce-kit' ),
			'default'           => __( 'WooCommerce Ready', 'craft-commerce-kit' ),
			'required'          => true,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'item_two_text'    => array(
			'type'              => 'textarea',
			'label'             => __( 'Second Item Text', 'craft-commerce-kit' ),
			'description'       => __( 'Text for the second USP item.', 'craft-commerce-kit' ),
			'default'           => __( 'Built to complement WooCommerce storefront flows without replacing native behavior.', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'item_three_title' => array(
			'type'              => 'text',
			'label'             => __( 'Third Item Title', 'craft-commerce-kit' ),
			'description'       => __( 'Title for the third USP item.', 'craft-commerce-kit' ),
			'default'           => __( 'Modular Design', 'craft-commerce-kit' ),
			'required'          => true,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'item_three_text'  => array(
			'type'              => 'textarea',
			'label'             => __( 'Third Item Text', 'craft-commerce-kit' ),
			'description'       => __( 'Text for the third USP item.', 'craft-commerce-kit' ),
			'default'           => __( 'Reusable sections can be rendered independently through the component definition system.', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
);
