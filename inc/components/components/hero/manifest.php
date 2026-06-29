<?php
/**
 * Hero component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'hero',
	'name'        => __( 'Hero', 'craft-commerce-kit' ),
	'description' => __( 'Premium storefront hero section.', 'craft-commerce-kit' ),
	'category'    => 'ui',
	'version'     => '1.0.0',
	'supports'    => array(
		'background',
		'spacing',
		'typography',
		'button',
	),
);
