<?php
/**
 * CTA component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'cta',
	'name'        => __( 'CTA', 'craft-commerce-kit' ),
	'description' => __( 'Call-to-action section with text and button.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'megaphone',

	'preview'     => array(
		'attributes' => array(
			'title'        => '',
			'text'         => __( 'Build reusable WooCommerce experiences with Craft Commerce Kit.', 'craft-commerce-kit' ),
			'button_label' => __( 'Shop Now', 'craft-commerce-kit' ),
			'button_url'   => '/shop/',
			'surface'      => 'transparent',
		),
	),

	'callback'    => 'cck_component_package_render_cta',

	'supports'    => array(
		'background',
		'spacing',
		'typography',
		'button',
		'visibility',
		'surface',
	),

	'settings'    => array(
		'title'        => array(
			'type'              => 'text',
			'label'             => __( 'Title', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),

		'text'         => array(
			'type'              => 'textarea',
			'label'             => __( 'Text', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		),

		'button_label' => array(
			'type'              => 'text',
			'label'             => __( 'Button Label', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),

		'button_url'   => array(
			'type'              => 'url',
			'label'             => __( 'Button URL', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),

		'surface'      => array(
			'type'              => 'select',
			'label'             => __( 'Background', 'craft-commerce-kit' ),
			'default'           => 'transparent',
			'options'           => cck_component_get_surface_options(),

			'sanitize_callback' => 'sanitize_key',
		),
	),
);
