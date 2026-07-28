<?php
/**
 * Coffee Studio footer section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'footer',
	'attributes' => array(
		'brand_name' => 'Coffee Studio',
		'brand_url'  => home_url( '/' ),
		'about' => 'A focused storefront for independent roasters, thoughtful cafes, and better daily coffee.',
		'email' => 'hello@coffee-studio.local',
		'copyright'  => sprintf( '© %1$s Coffee Studio.', gmdate( 'Y' ) ),
	),
);
