<?php
/**
 * Hero component manifest dosyası.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'hero',
	'name'        => __( 'Hero', 'craft-commerce-kit' ),
	'description' => __( 'Premium storefront hero section.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'cover-image',
	'preview'     => '',
	'callback'    => 'cck_component_hero',
	'supports'    => array(
		'background',
		'spacing',
		'typography',
		'button',
		'animation',
		'visibility',
	),
	'settings'    => array(
		'eyebrow'     => array(
			'type'              => 'text',
			'label'             => __( 'Eyebrow', 'craft-commerce-kit' ),
			'description'       => __( 'Small label displayed above the hero title.', 'craft-commerce-kit' ),
			'default'           => __( 'Craft Commerce Kit', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'title'       => array(
			'type'              => 'text',
			'label'             => __( 'Title', 'craft-commerce-kit' ),
			'description'       => __( 'Main hero headline.', 'craft-commerce-kit' ),
			'default'           => __( 'Premium Leather Goods', 'craft-commerce-kit' ),
			'required'          => true,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'text'        => array(
			'type'              => 'textarea',
			'label'             => __( 'Text', 'craft-commerce-kit' ),
			'description'       => __( 'Supporting hero text.', 'craft-commerce-kit' ),
			'default'           => __( 'Build reusable commerce sections with a theme-independent component foundation.', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'primary_label' => array(
			'type'              => 'text',
			'label'             => __( 'Primary Button Label', 'craft-commerce-kit' ),
			'description'       => __( 'Primary hero button label.', 'craft-commerce-kit' ),
			'default'           => __( 'Explore Components', 'craft-commerce-kit' ),
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'primary_url'  => array(
			'type'              => 'url',
			'label'             => __( 'Primary Button URL', 'craft-commerce-kit' ),
			'description'       => __( 'Primary hero button destination.', 'craft-commerce-kit' ),
			'default'           => '#',
			'required'          => false,
			'sanitize_callback' => 'esc_url_raw',
		),
		'secondary_label' => array(
			'type'              => 'text',
			'label'             => __( 'Secondary Button Label', 'craft-commerce-kit' ),
			'description'       => __( 'Secondary hero button label.', 'craft-commerce-kit' ),
			'default'           => '',
			'required'          => false,
			'sanitize_callback' => 'sanitize_text_field',
		),
		'secondary_url'   => array(
			'type'              => 'url',
			'label'             => __( 'Secondary Button URL', 'craft-commerce-kit' ),
			'description'       => __( 'Secondary hero button destination.', 'craft-commerce-kit' ),
			'default'           => '',
			'required'          => false,
			'sanitize_callback' => 'esc_url_raw',
		),
		'image_url'       => array(
			'type'              => 'url',
			'label'             => __( 'Image URL', 'craft-commerce-kit' ),
			'description'       => __( 'Hero visual image URL.', 'craft-commerce-kit' ),
			'default'           => '',
			'required'          => false,
			'sanitize_callback' => 'esc_url_raw',
		),
	),
);
