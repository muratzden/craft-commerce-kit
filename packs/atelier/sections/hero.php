<?php
/**
 * Atelier hero section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'   => 'hero',
	'bindings'   => array(
		'eyebrow'       => 'brand.eyebrow',
		'title'         => 'brand.headline',
		'text'          => 'brand.text',
		'primary_label' => 'brand.cta_label',
		'primary_url'   => 'brand.cta_url',
	),
	'attributes' => array(
		'eyebrow'         => 'Craft Commerce Kit',
		'title'           => 'Crafted for the quiet luxury of everyday rituals.',
		'text'            => 'Build reusable commerce sections with a theme-independent component foundation that still feels premium.',
		'primary_label'   => 'Explore the Collection',
		'primary_url'     => '/shop/',
		'secondary_label' => 'View the Workshop',
		'secondary_url'   => '/about/',
		'image_url'       => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'hero.webp', 'Atelier hero' )['url'] : '',
	),
);
