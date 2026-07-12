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
	'attributes' => array(),
);