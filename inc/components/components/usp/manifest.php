<?php
/**
 * USP component manifest.
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

	'preview'     => array(
		'attributes' => array(
			'item_one_icon'    => 'star-filled',
			'item_one_title'   => __( 'Handmade Quality', 'craft-commerce-kit' ),
			'item_one_text'    => __( 'Designed for product stories that value material, process, and detail.', 'craft-commerce-kit' ),
			'item_two_icon'    => 'yes-alt',
			'item_two_title'   => __( 'WooCommerce Ready', 'craft-commerce-kit' ),
			'item_two_text'    => __( 'Built to complement WooCommerce storefront flows without replacing native behavior.', 'craft-commerce-kit' ),
			'item_three_icon'  => 'awards',
			'item_three_title' => __( 'Modular Design', 'craft-commerce-kit' ),
			'item_three_text'  => __( 'Reusable sections can be rendered independently through the component definition system.', 'craft-commerce-kit' ),
			'surface'          => 'surface',
		),
	),

	'callback'    => 'cck_component_package_render_usp',

	'supports'    => array(
		'background',
		'spacing',
		'typography',
		'animation',
		'visibility',
		'surface',
	),

	'settings'    => array(
		'item_one_icon'    => array(
			'type'              => 'select',
			'label'             => __( 'First Item Icon', 'craft-commerce-kit' ),
			'default'           => 'star-filled',
			'options'           => array(
				'star-filled' => __( 'Star', 'craft-commerce-kit' ),
				'yes-alt'     => __( 'Check', 'craft-commerce-kit' ),
				'shield'      => __( 'Shield', 'craft-commerce-kit' ),
				'awards'      => __( 'Award', 'craft-commerce-kit' ),
				'hammer'      => __( 'Craft', 'craft-commerce-kit' ),
				'cart'        => __( 'Cart', 'craft-commerce-kit' ),
				'heart'       => __( 'Heart', 'craft-commerce-kit' ),
			),
			'sanitize_callback' => 'sanitize_key',
		),

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
			'sanitize_callback' => 'sanitize_textarea_field',
		),

		'item_two_icon'    => array(
			'type'              => 'select',
			'label'             => __( 'Second Item Icon', 'craft-commerce-kit' ),
			'default'           => 'yes-alt',
			'options'           => array(
				'star-filled' => __( 'Star', 'craft-commerce-kit' ),
				'yes-alt'     => __( 'Check', 'craft-commerce-kit' ),
				'shield'      => __( 'Shield', 'craft-commerce-kit' ),
				'awards'      => __( 'Award', 'craft-commerce-kit' ),
				'hammer'      => __( 'Craft', 'craft-commerce-kit' ),
				'cart'        => __( 'Cart', 'craft-commerce-kit' ),
				'heart'       => __( 'Heart', 'craft-commerce-kit' ),
			),
			'sanitize_callback' => 'sanitize_key',
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
			'sanitize_callback' => 'sanitize_textarea_field',
		),

		'item_three_icon'  => array(
			'type'              => 'select',
			'label'             => __( 'Third Item Icon', 'craft-commerce-kit' ),
			'default'           => 'awards',
			'options'           => array(
				'star-filled' => __( 'Star', 'craft-commerce-kit' ),
				'yes-alt'     => __( 'Check', 'craft-commerce-kit' ),
				'shield'      => __( 'Shield', 'craft-commerce-kit' ),
				'awards'      => __( 'Award', 'craft-commerce-kit' ),
				'hammer'      => __( 'Craft', 'craft-commerce-kit' ),
				'cart'        => __( 'Cart', 'craft-commerce-kit' ),
				'heart'       => __( 'Heart', 'craft-commerce-kit' ),
			),
			'sanitize_callback' => 'sanitize_key',
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
			'sanitize_callback' => 'sanitize_textarea_field',
		),

		'surface'          => array(
			'type'              => 'select',
			'label'             => __( 'Background', 'craft-commerce-kit' ),
			'default'           => 'surface',
			'options'           => cck_component_get_surface_options(),

			'sanitize_callback' => 'sanitize_key',
		),
	),
);
