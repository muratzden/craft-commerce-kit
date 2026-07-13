<?php
defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'trust-block',
	'name'        => __( 'Trust Block', 'craft-commerce-kit' ),
	'description' => __( 'Trust statement metadata for the central renderer.', 'craft-commerce-kit' ),
	'version'     => '1.0.0',
	'category'    => 'ui',
	'icon'        => 'shield',
	'preview'     => array(
		'attributes' => array(
			'items' => 'Hand-finished::Small-batch production with attention to every stitch|Natural materials::Vegetable-tanned leather and honest hardware|Secure checkout::Built to inspire trust at every step',
		),
	),
	'callback'    => 'cck_component_trust_block',
	'supports'    => array( 'background', 'spacing', 'typography', 'visibility' ),
	'settings'    => array(
		'items' => array( 'type' => 'textarea', 'label' => __( 'Items', 'craft-commerce-kit' ), 'default' => 'Hand-finished::Small-batch production with attention to every stitch|Natural materials::Vegetable-tanned leather and honest hardware|Secure checkout::Built to inspire trust at every step', 'sanitize_callback' => 'sanitize_text_field' ),
	),
);
