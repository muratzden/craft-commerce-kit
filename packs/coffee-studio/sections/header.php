<?php
/**
 * Coffee Studio header section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'header',
	'attributes' => array(
		'brand_name' => 'Coffee Studio',
		'brand_url'  => home_url( '/' ),
		'nav'        => array(
			array(
				'label' => 'Shop Coffee',
				'url'   => home_url( '/shop/' ),
			),
			array(
				'label' => 'Our Approach',
				'url'   => home_url( '/about/' ),
			),
			array(
				'label' => 'Brew Guide',
				'url'   => home_url( '/brew-guide/' ),
			),
		),
	),
);
