<?php
defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'image-text',
	'name'        => __( 'Image Text', 'craft-commerce-kit' ),
	'description' => __( 'Image and text metadata for the central renderer.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'align-pull-left',
	'preview'     => array(
		'attributes' => array(
			'title'        => __( 'Built with patient hands and thoughtful materials.', 'craft-commerce-kit' ),
			'text'         => __( 'Use this block for workshop photography, collection storytelling, or an editorial brand statement.', 'craft-commerce-kit' ),
			'button_label' => __( 'Visit the Workshop', 'craft-commerce-kit' ),
			'button_url'   => '/workshop/',
			'image_url'    => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'story.webp', __( 'Story image', 'craft-commerce-kit' ) )['url'] : content_url( 'uploads/woocommerce-placeholder-768x768.webp' ),
			'reverse'      => 'true',
		),
	),
	'callback'    => 'cck_component_image_text',
	'supports'    => array( 'background', 'spacing', 'typography', 'button', 'visibility' ),
	'settings'    => array(
		'title'        => array( 'type' => 'text', 'label' => __( 'Title', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'text'         => array( 'type' => 'textarea', 'label' => __( 'Text', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'button_label' => array( 'type' => 'text', 'label' => __( 'Button Label', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
		'button_url'   => array( 'type' => 'url', 'label' => __( 'Button URL', 'craft-commerce-kit' ), 'default' => '', 'sanitize_callback' => 'esc_url_raw' ),
		'image_url'    => array( 'type' => 'url', 'label' => __( 'Image URL', 'craft-commerce-kit' ), 'default' => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'story.webp', __( 'Story image', 'craft-commerce-kit' ) )['url'] : '', 'sanitize_callback' => 'esc_url_raw' ),
		'reverse'      => array( 'type' => 'checkbox', 'label' => __( 'Reverse', 'craft-commerce-kit' ), 'default' => 'false', 'sanitize_callback' => 'sanitize_text_field' ),
	),
);
