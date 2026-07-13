<?php
/**
 * Atelier story section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'image-text',
	'attributes' => array(
		'title'        => 'Reusable sections without theme lock-in.',
		'text'         => 'Use this block for brand storytelling, product education, campaign landing pages, or collection highlights with a stronger visual rhythm.',
		'button_label' => 'Browse Store',
		'button_url'   => '/shop/',
		'reverse'      => 'true',
		'image_url'    => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'story.webp', 'Atelier story' )['url'] : '',
	),
);
