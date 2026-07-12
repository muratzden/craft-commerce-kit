<?php
defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'image-text',
	'name'        => __( 'Image Text', 'craft-commerce-kit' ),
	'description' => __( 'Image and text metadata for the central renderer.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'align-pull-left',
	'preview'     => '',
	'callback'    => 'cck_component_image_text',
	'supports'    => array( 'background', 'spacing', 'typography', 'button', 'visibility' ),
	'settings'    => array(
		'title'        => array( 'type' => 'text', 'label' => __( 'Title', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'text'         => array( 'type' => 'textarea', 'label' => __( 'Text', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'button_label' => array( 'type' => 'text', 'label' => __( 'Button Label', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'button_url'   => array( 'type' => 'url', 'label' => __( 'Button URL', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'esc_url_raw' ),
		'image_url'    => array( 'type' => 'url', 'label' => __( 'Image URL', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'esc_url_raw' ),
		'reverse'      => array( 'type' => 'checkbox', 'label' => __( 'Reverse', 'craft-commerce-kit' ), 'default' => 'false', 'sanitize_callback' => 'sanitize_text_field' ),
	),
);
