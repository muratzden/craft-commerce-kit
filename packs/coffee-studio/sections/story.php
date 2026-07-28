<?php
/**
 * Coffee Studio story section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'image-text',
	'attributes' => array(
		'title'        => 'From origin to cup, every choice matters.',
		'text'         => 'We source expressive seasonal lots and roast them in small batches to preserve sweetness, balance, and the character of each origin.',
		'button_label' => 'Our Approach',
		'button_url'   => '/about/',
		'reverse'      => 'true',
		'image_url'    => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'coffee-studio-story.webp', 'Coffee roasting process' )['url'] : '',
	),
);
