<?php
/**
 * Section Title component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'section-title',
	'name'        => __( 'Section Title', 'craft-commerce-kit' ),
	'description' => __( 'Section heading metadata for the central renderer.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'heading',

	'preview'     => array(
		'attributes' => array(
			'eyebrow' => __( 'Editorial Story', 'craft-commerce-kit' ),
			'title'   => __( 'Made for brands that prefer calm confidence over noise.', 'craft-commerce-kit' ),
			'text'    => __( 'Use this section for editorial introductions, collection notes, and campaign messaging.', 'craft-commerce-kit' ),
			'align'   => 'center',
			'surface' => 'transparent',
		),
	),

	'callback'    => 'cck_component_package_render_section_title',

	'supports'    => array(
		'spacing',
		'typography',
		'visibility',
		'surface',
	),

	'settings'    => array(
		'eyebrow' => array(
			'type'              => 'text',
			'label'             => __( 'Eyebrow', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),

		'title'   => array(
			'type'              => 'text',
			'label'             => __( 'Title', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),

		'text'    => array(
			'type'              => 'textarea',
			'label'             => __( 'Text', 'craft-commerce-kit' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		),

		'align'   => array(
			'type'              => 'select',
			'label'             => __( 'Alignment', 'craft-commerce-kit' ),
			'default'           => 'left',
			'options'           => array(
				'left'   => __( 'Left', 'craft-commerce-kit' ),
				'center' => __( 'Center', 'craft-commerce-kit' ),
				'right'  => __( 'Right', 'craft-commerce-kit' ),
			),
			'sanitize_callback' => 'sanitize_key',
		),

		'surface' => array(
			'type'              => 'select',
			'label'             => __( 'Background', 'craft-commerce-kit' ),
			'default'           => 'transparent',
			'options'           => cck_component_get_surface_options(),

			'sanitize_callback' => 'sanitize_key',
		),
	),
);
