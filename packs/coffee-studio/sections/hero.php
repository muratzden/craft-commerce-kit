<?php
/**
 * Coffee Studio hero section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'hero',
	'attributes' => array(
		'eyebrow'         => 'Small-batch coffee',
		'title'           => 'Coffee made for slower mornings.',
		'text'            => 'Seasonal beans, careful roasting, and transparent sourcing for a more considered daily ritual.',
		'primary_label'   => 'Shop Coffee',
		'primary_url'     => '/shop/',
		'secondary_label' => 'Meet the Roaster',
		'secondary_url'   => '/about/',
		'image_url'       => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'coffee-studio-hero.webp', 'Coffee beans and brewing tools' )['url'] : '',
	),
);
