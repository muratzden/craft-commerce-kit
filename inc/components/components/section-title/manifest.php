<?php
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
			'text'    => __( 'Set a clear section hierarchy for product stories, collection intros, and campaign notes.', 'craft-commerce-kit' ),
			'align'   => 'center',
		),
	),
	'callback'    => 'cck_component_section_title',
	'supports'    => array( 'spacing', 'typography', 'visibility' ),
	'settings'    => array(
		'eyebrow' => array( 'type' => 'text', 'label' => __( 'Eyebrow', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'title'   => array( 'type' => 'text', 'label' => __( 'Title', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'text'    => array( 'type' => 'textarea', 'label' => __( 'Text', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'align'   => array( 'type' => 'text', 'label' => __( 'Alignment', 'craft-commerce-kit' ), 'default' => 'left', 'sanitize_callback' => 'sanitize_key' ),
	),
);
