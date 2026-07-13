<?php
defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'footer',
	'name'        => __( 'Footer', 'craft-commerce-kit' ),
	'description' => __( 'Premium storefront footer.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'footer',
	'preview'     => array(
		'attributes' => array(
			'about'     => __( 'A premium WooCommerce starter kit for refined artisan commerce.', 'craft-commerce-kit' ),
			'email'     => 'hello@craft-commerce-kit.local',
			'copyright' => sprintf( __( '© %1$s Craft Commerce Kit.', 'craft-commerce-kit' ), gmdate( 'Y' ) ),
		),
	),
	'callback'    => 'cck_component_footer',
	'supports'    => array( 'background', 'spacing', 'typography', 'visibility' ),
	'settings'    => array(
		'about'     => array( 'type' => 'textarea', 'label' => __( 'About', 'craft-commerce-kit' ), 'default' => __( 'A premium WooCommerce starter kit for refined artisan commerce.', 'craft-commerce-kit' ), 'sanitize_callback' => 'sanitize_text_field' ),
		'email'     => array( 'type' => 'text', 'label' => __( 'Newsletter Placeholder', 'craft-commerce-kit' ), 'default' => 'hello@example.com', 'sanitize_callback' => 'sanitize_text_field' ),
		'copyright' => array( 'type' => 'text', 'label' => __( 'Copyright', 'craft-commerce-kit' ), 'default' => sprintf( __( '© %1$s Craft Commerce Kit.', 'craft-commerce-kit' ), gmdate( 'Y' ) ), 'sanitize_callback' => 'sanitize_text_field' ),
	),
);
