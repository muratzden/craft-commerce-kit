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
	'category'    => 'ui',
	'version'     => '1.0.0',
	'supports'    => array(
		'background',
		'spacing',
		'typography',
	),
);
